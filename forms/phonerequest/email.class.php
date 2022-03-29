<?php
/*

Modified: 1/31/2017

By default message connot contain html code and cannot be empty.
So, if you want to change those setting, use 
sendEmail::changeEmailSetting('msgContainHtml',true);
sendEmail::changeEmailSetting('msgIsEmpty',false);

// These two will store email address for receiver and sender
// and the email address will be validated.
// $name is to specify the email since multiple email can be stored in the array.
sendEmail::setReceiver($name,$email);
sendEmail::setSender($name,$email);

// And use get to return validated email address.
// if email is not valid, then it will return empty.
sendEmail::getReceiver($name,$email);
sendEmail::getSender($name,$email);

// This goes between <body></body>. 
// You can set html code for $message.
sendEmail::setMessage($message);

// Set title for the email
sendEmail::setEmailTitle($title);

// You can set css style for the email
sendEmail::setMessageStyle($style);

// Send email
// It will return the array to report results whether it is successful.
$results = sendEmail::finallySendEmail($sender_name,$receiver_name);

// Send to all receiver - haven't tested yet.
sendEmail::sendEmailToAll($sender);

// Send to many - haven't tested yet.
$receivers = ['name1','name2','name3'];
sendEmail::sendEmailToMany($sender,$receivers);
*/

class sendEmail{

	protected static $receiverEmail = array();
	protected static $senderEmail = array();
	protected static $message = '';
	protected static $emailSetting = array(																'msgContainHtml' => false,										'msgIsEmpty' => false												);
	protected static $results = array();
	protected static $error_count = 0;
	protected static $title = '';
	protected static $messageStyle = '';

	public static function changeEmailSetting($type,$value){
		self::$emailSetting[$type] = $value;
	}

	/**
     * Set email address for receiver
     *
     * @param  String $name, String $email
     * @return void
     */
	public static function setReceiver($name, $email){
		self::beforeSetEmail($name,$email,'receiver');
	}

	/**
     * Get email address for receiver
     *
     * @param  String $name
     * @return String $emailaddress or empty string 
     */
	public static function getReceiver($name){
		return self::beforeGetMail($name,'receiver');
	}

	public static function getAllReceiver(){
		return self::$receiverEmail;
	}

	/**
     * Set email address for sender
     *
     * @param  String $name, String $email
     * @return void
     */
	public static function setSender($name, $email){
		self::beforeSetEmail($name,$email,'sender');
	}

	/**
     * Get email address for sender
     *
     * @param  String $name
     * @return String $emailaddress or empty string
     */
	public static function getSender($name){
		return self::beforeGetMail($name,'sender');
	}

	public static function getAllSender(){
		return self::$senderEmail;
	}

	/**
     * Check if emailaddress is valid.
     *
     * @param  String $name, String $email, String $type
     * @return void
     */
	private static function beforeSetEmail($name,$email,$type){
		$arr = array();
		if ($type == 'receiver'){
			$arr = &self::$receiverEmail;
			$whichFunction = 'setReceiver';
		}else if($type == 'sender'){
			$arr = &self::$senderEmail;
			$whichFunction = 'setSender';
		}
		$results = &self::$results;
		// Make sure if name is already in the array
		// If it exists, then don't store it.
		if (in_array($name,$arr)){
			$results[$name] =  [
										'type'         =>  $type,
										'email'        =>  $email,
										'error_exist'  =>  True,
										'error'        =>  'Same name cannot be used for '.$whichFunction.'('.$name.','.$email.').'
								];
			self::$error_count++;
		}else{
			// Check if email is valid
			if ($email == ''){
				// Email is invalid. So, leave the error message to user.
				$results[$name] = [
									'type'         =>  $type,
									'email'        =>  $email,
									'error_exist'  =>  True,
									'error'        =>  'Enter the email address.'
								  ];
				self::$error_count++;
			}elseif(self::validateEmailAddress($email)){
				// Email is valid
				$arr[$name] = $email;
				$results[$name] = [
									'type'         =>  $type,
									'email'        =>  $email,
									'error_exist'  =>  False,
									'error'        =>  'Valid email address.'
								  ];
			}else{
				// Email is invalid. So, leave the error message to user.
				$results[$name] = [
									'type'         =>  $type,
									'email'        =>  $email,
									'error_exist'  =>  True,
									'error'        =>  'Email address is not valid.'
								  ];
				self::$error_count++;
			}			
		}
	}

	/**
     * Before return email, check if email is validated
     *
     * @param  String $name, String $type
     * @return String
     */
	private static function beforeGetMail($name,$type){
		if ($type == 'receiver'){
			$arr = self::$receiverEmail;
		}else if($type == 'sender'){
			$arr = self::$senderEmail;
		}

		if (array_key_exists($name, $arr)){
			return $arr[$name];
		}else {
			return '';
		}
	}

	/**
     * Validate email address
     *
     * @param  String $email
     * @return boolean
     */
	private static function validateEmailAddress($email){

        // PHP email validation
        if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && self::domain_exists($email))){
            // This email address is not considered valid
            $error_msg = 'Not valid email address';
            // self::setError($inputName, $email, $error_msg);
            self::$error_count++;
            $retVal = False;
        }else{
            // Valid email address
            $retVal = True;
        }

        return $retVal;
	}

	/**
     * check email domain exists.
     *
     * @param  String $email, String $record
     * @return boolean
     */
	private static function domain_exists($email, $record = 'MX'){
        list($user, $domain) = explode('@', $email);
        return checkdnsrr($domain, $record);
    }

	public static function setMessage($message){
		$setting = self::$emailSetting;
		$results = &self::$results;
		if($setting['msgIsEmpty']==false){
			if ($message!=''){
				if ($setting['msgContainHtml']==false){
					if($message != strip_tags($message)){
						self::$message = strip_tags($message);
						$results['message'] = [
													'success'  => False,
													'error'  => 'Cannot include html tags.'
												   ];
						self::$error_count++;
					}else{
						self::$message = $message;
						$results['message'] = [
													'success'  => True,
													'error'  => ''
												   ];
					}
				}else{
					self::$message = $message;
					$results['message'] = [
													'success'  => True,
													'error'  => ''
												   ];
				}
			}else{
				$results['message'] = [
													'success'  => False,
													'error'  => 'Message is empty.'
												   ];
				self::$error_count++;
			}

		}
	}

	public static function setEmailTitle($title){
		self::$title = $title;
	}

	public static function setMessageStyle($style){
		self::$messageStyle = $style;
	}

	public static function setMessageBody(){
		$msg = '<html>
	    			<head>
	    				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    				<style type="text/css">'.
	    				self::$messageStyle.'
	    				</style>
	    			</head>
	    			<body>'.
	    			self::$message.
	    			'</body>
	    		</html>';
	    return $msg;
	}

	/**
     * send email to one receiver with one sender
     *
     * @param  String $email, String $record
     * @return boolean
     */
	public static function finallySendEmail($sender_name = '',$receiver_name = ''){
		$results = &self::$results;
		if (self::$error_count == 0){

			// Set content-type header for sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			$headers .= 'From: '.self::$senderEmail[$sender_name]."\r\n"
						 .' '.$sender_name;  

			// send email
			try {
				mail(self::$receiverEmail[$receiver_name],self::$title,self::setMessageBody(),$headers);
				$results['emailConfirm'] = [
											'success'  => True,
											'message'  => 'Email was sent successfully.'
										   ];
			} catch (Exception $e) {
				$results['emailConfirm'] = [
											'success'  => False,
											'message'  => 'Email was not sent correctly.'
										   ];
			}
		}else{
			$results['emailConfirm'] = [
										'success'  => False,
										'message'  => 'Email was not sent correctly.'
									   ];
		}
		return $results;
	}

	/**
     * send email to all with one sender
     *
     * @param  String $sender
     * @return array
     */
	public static function sendEmailToAll($sender){
		$results = &self::$results;
		if (self::$error_count == 0){ // no error found

			// Set content-type header for sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			$headers .= 'From: '.self::$senderEmail[$sender_name]."\r\n"
						 .' '.$sender_name;

			foreach (self::$receiverEmail as $name => $email) {
				// send email
				try {
					mail($email,self::$title,self::setMessageBody(),$headers);
					$results['emailConfirm'] = [
												'success'  => True,
												'message'  => 'Email was sent successfully.'
											   ];
				} catch (Exception $e) {
					if (array_key_exists('names', $results['emailConfirm'])){
						$results['emailConfirm'] = [
												'success'  => False,
												'names'    => $results['emailConfirm']['names'].'/'.$name,
												'message'  => 'Email was not sent correctly.'.$e
											   ];
					}else{
						$results['emailConfirm'] = [
												'success'  => False,
												'names'    => $name,
												'message'  => 'Email was not sent correctly.'.$e
											   ];
					}
				} // end catch			
			}// end of for loop

		}else{ // There was error. Return with results.
			$results['emailConfirm'] = [
										'success'  => False,
										'message'  => 'Email was not sent correctly.'
									   ];
		}

		return $results;
	}

	/**
     * send email to all with one sender
     *
     * @param  String $sender, Array $receivers
     * @return array
     */
	public static function sendEmailToMany($sender,$receivers = array()){
		$results = &self::$results;
		if (self::$error_count == 0){ // no error found

			// Set content-type header for sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			$headers .= 'From: '.self::$senderEmail[$sender_name]."\r\n"
						 .' '.$sender_name;

			foreach ($receivers as $name) {
				// send email
				try {
					mail(self::$receiverEmail[$name],self::$title,self::setMessageBody(),$headers);
					$results['emailConfirm'] = [
												'success'  => True,
												'message'  => 'Email was sent successfully.'
											   ];
				} catch (Exception $e) {
						if (array_key_exists('names', $results['emailConfirm'])){
							$results['emailConfirm'] = [
													'success'  => False,
													'names'    => $results['emailConfirm']['names'].'/'.$name,
													'message'  => 'Email was not sent correctly.'
												   ];
						}else{
							$results['emailConfirm'] = [
													'success'  => False,
													'names'    => $name,
													'message'  => 'Email was not sent correctly.'
												   ];
						}
				}
			}

		}else{ // There was error. Return with results.
			$results['emailConfirm'] = [
										'success'  => False,
										'message'  => 'Email was not sent correctly.'
									   ];
		}

		return $results;
	}





	public static function errorCount(){
        return self::$error_count;
    }

}

?>