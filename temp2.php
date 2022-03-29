<?php

/*

File created  : 10/18/2016
File modified : 10/27/2016

*/


if ( isset($_POST['submit']) ){
	
	$errors = array();
	$countFile = 0;
	
	// Initialize inputs 
		/* order is important */
	$inputArray = array();
	$paramInput = array();
	$first_name = inputToArray($_POST['first_name'],'first_name');
	$last_name = inputToArray($_POST['last_name'],'last_name');
	$email = inputToArray($_POST['email'],'email');
	$subject = "Email Testing";
//	$phone = inputToArray($_POST['phone'],'phone');
//	$url = inputToArray($_POST['url'],'url'); 
//	$msg = inputToArray($_POST['supportRequestText'],'text');
//    $msg = str_replace(PHP_EOL,"<br>",$msg);// trim msg to convert all <br> to newline 
//	$title = inputToArray($_POST['web_support_title'],'title');
//	$urgent = inputToArray($_POST['optionsUrgent'],'urgent');
	// print($last_name);

}
function inputToArray($input,$type){

	global $errors;
	if ( "" == trim($input) ){
		switch ($type) {
			case 'first_name':
				$err_msg = 'Enter your name';
				array_push($errors, $err_msg);
				$input = " ";
				break;

			case 'last_name':
				$err_msg = 'Enter your name';
				array_push($errors, $err_msg);
				$input = " ";
				break;

			case 'email':
				$err_msg = 'Your email address was not entered';
				array_push($errors, $err_msg);
				$input = " ";
				break;

			case 'phone':
				$input = NULL;
				break;

			case 'url':
				$input = '';
				break;

			case 'title':
				$input = NULL;
				break;

			case 'text':
				$err_msg = 'Write your request';
				array_push($errors, $err_msg);
				$input = " ";
				break;
		}
	}else {
		switch ($type) {
			case 'phone':
				if(preg_match("/[a-z]/i", $input)){
				    $err_msg = 'Enter Valid Phone Number';
				    $input = '';
					array_push($errors, $err_msg);
				}
				break;
		}
	}

	// Store in $inputArray
	global $inputArray;
	global $paramInput;
	array_push($paramInput,$input);
	$inputArray[$type] = $input;
	return $input;
}
function emailToClient($email,$first_name,$last_name,$subject){
	$title = "SU Tech Web - New Request Received";

	// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: su-web@email.arizona.edu';
	$msg = emailBodyToClient($id,$first_name,$last_name,$subject);

	// send email
	mail($email,$title,$msg,$headers);
}

function emailBodyToClient($first_name,$last_name,$subject){
	$msg = '<html><head></head><body><table style="width: 100%;"><tr><td><table class="suis-table">';
    $msg .= '<tr><td>Testing</td></tr>'; 
	$msg .= '<tr><td>Name: '.$last_name. '</td></tr>'; 
	$msg .= '<tr><td>Student Unions</td></tr>';
    $msg .= '</table></td></tr></table></body></html>';
	return $msg;
}
emailToClient($email,$first_name,$last_name,$subject);
print("Email Sent to: " . $email);
?>