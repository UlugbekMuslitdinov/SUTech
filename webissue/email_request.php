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
	$firstname = inputToArray($_POST['first_name'],'firstname');
	$lastname = inputToArray($_POST['last_name'],'lastname');
	$email = $_POST['email'];    //.'@email.arizona.edu'; //email from form will only be NetID, need to add @email.arizona.edu extension
	$email = inputToArray($email,'email'); 
	//$email = inputToArray($_POST['email'],'email'); 
	$phone = inputToArray($_POST['phone'],'phone');
	$url = inputToArray($_POST['url'],'url');
	$msg = inputToArray($_POST['supportRequestText'],'text');
//   	$msg = str_replace("\n","<br>",$msg);// trim msg to convert all <br> to newline
   	//the above trim has been removed because it seems to be adding whitespace the textarea form did not have originally
   	//the fix to this whitespace problem in textarea was adding wrap="hard" to textarea in request_form.php and omitting str_replace trim change to $msg
    // This solution doesn't work. It is better just to remove this step and it solves the whitespace problem
	$title = inputToArray($_POST['web_support_title'],'title');
	$urgent = inputToArray($_POST['optionsUrgent'],'urgent');
	
	// If input has an error
	if (directToBackWithError($errors)){
		
		$files = isset($_FILES['files']) ? $_FILES['files'] : False;

		// Connect database 'sucs'
		$conn = select_db('sucs');

		// Setting bind_param dynamically
		$a_param_type = ['s','s','s','s','s','s','s','s'];

		// Query - order of column should match the order of values in $inputArray
		$query = "INSERT INTO web_support (name_first,name_last,email,phone,url,web_support,web_support_title,urgent) VALUES (?,?,?,?,?,?,?,?)";

		// prepare and bind
		if ( $stmt = $conn->prepare($query) ){

			/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
			$a_params = array();
			
			$param_type = '';
			$n = count($a_param_type);
			for($i = 0; $i < $n; $i++) {
			  $param_type .= $a_param_type[$i];
			}
			 
			//with call_user_func_array, array params must be passed by reference 
			$a_params[] = & $param_type;			 
			for($i = 0; $i < $n; $i++) {
			  // with call_user_func_array, array params must be passed by reference 
			  $a_params[] = & $paramInput[$i];
			}

			call_user_func_array(array($stmt,'bind_param'),$a_params);

			// Execute
			if( $stmt->execute() ){
				// Get id from stored data
				$id = $stmt->insert_id;
				$stmt->close();
				// Query to get timestamp
				$query = "SELECT timestamp FROM web_support WHERE id = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);
				$stmt->execute();
				$stmt->bind_result($request_time);
				$stmt->fetch();
				$stmt->close();
				/* use this if file upload is disabled */
				// if ( emailToSUTech($email,$title,$msg,$firstname,$lastname,$request_time) ) {
				// 	// Destroy connection to database
				// 	$conn->close();
				// 	afterEmailSent();
				// }

				if ( $files != False ){
					storeAttachedFile($conn,$files,$id);
				}else {
					// Report the error
					directToBackWithError($errors);
				}

				if ( emailToSUTech($id,$email,$title,$msg,$firstname,$lastname,$phone,$url,$request_time) ) {
					afterEmailSent();
				}else {
					// Report the error
					directToBackWithError($errors);
				}

			}else {
				// Failed to execute INSERT query
				// die("Errormessage: ". $conn->error);
				$err_msg = 'There was a problem sending an email! - errCode001';
			    array_push($errors, $err_msg);
			}
		}else {
			// Failed to prepare query
			die("Errormessage: ". $conn->error);
			$err_msg = 'There was a problem sending an email! - errCode002';
			array_push($errors, $err_msg);
		}
		// Check if there is an error
		directToBackWithError($errors);
	}
}else {
	header("Location: http://".$_SERVER[HTTP_HOST]."/webissue/index.php");
	die();
}


function inputToArray($input,$type){

	global $errors;
	if ( "" == trim($input) ){
		switch ($type) {
			case 'firstname':
				$err_msg = 'Enter your name';
				array_push($errors, $err_msg);
				$input = " ";
				break;

			case 'lastname':
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

function storeAttachedFile($conn,$files,$id){

	// Will check first if there is any attached files.
	$file_leng = count($files['name']);
	
	if ( $file_leng > 0 ){
		
		// Global Variables
		global $errors;
		global $countFile;

		// Move file to server 
		$target_dir = 'resources/';
		$failed = 0;
		for ( $i=0; $i < $file_leng; $i++ ) { 
			if ( $files['size'][$i] != 0 ){
				$countFile = $i + 1;
				// make a path with new name
				$filename = $files['name'][$i];
				$ext = end((explode(".", $filename)));
				// $new_filename = basename($filename,'.'.$ext).'-'.date("Y-m-d-h-i-sa").'.'.$ext;
				$new_filename = $id.'-'.$i.'-'.date("Ymd").'.'.$ext;
	        	$file_path = $target_dir . $new_filename;
	        	
	        	if(!move_uploaded_file($files['tmp_name'][$i],$file_path)){
	        		// Handle error when file is failed to move to upload
	        		$err_msg = 'Uploading file failed - filename: '.$filename;
	        		array_push($errors, $err_msg);
	        	}else {
	        		// echo $file_path;
	        	}

	        	// Insert file path to database
	        		// Query
	        	$query = "INSERT INTO web_support_files (ws_id,file_path,original_filename) VALUES (?,?,?)";

	        		// Prepare and Bind
				if ( $stmt = $conn->prepare($query) ){

					// Check length of original filename
					if ( strlen($filename) > 50 ){
						$filename = substr($filename,49);
					}

					$stmt->bind_param("iss",$id,$file_path,$filename);

					// Execute
					if( $stmt->execute() ){
						// Everything has been completed successfully.
						if ($file_leng==1){
							return true;
						}
					}else {
						// Failed to INSERT into database
						// die("Errormessage: ". $conn->error);
						$err_msg = 'There was a problem sending an email! - errCode003';
	        			array_push($errors, $err_msg);
						return false;
					}
				}else {
					// Failed to prepare query
					// die("Errormessage: ". $conn->error);
					$err_msg = 'There was a problem sending an email! - errCode004';
	        		array_push($errors, $err_msg);
				}

	        }
	        // else {
	        // 	if ( $file_leng == 1 ){
	        // 		return true;
	        // 	}else {
	        // 		$failed++;
	        // 	}
	        // }
		}
		// if ($file_leng == $failed ){
		// 	return true;
		// }else {
		// 	return false;
		// }
		return true;
	}else {
		// No attached files
		// echo 'no attached file';
		return true;
	}
}

function directToBackWithError($errors){
	if ( count($errors) > 0 ){
		global $inputArray;
		$_SESSION['web_issue_errors'] = $errors;
		$_SESSION['WI_old_inputs'] = $inputArray;
		include_once('request_form.php');
		return false;
	}else{
		return true;
	}
}

function emailToSUTech($id,$email,$subject,$msg,$first_name,$last_name,$phone,$url,$request_time){

	$title = "SU Tech - Website related Issue";

	// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	$headers .= 'From: '.$email."\r\n"
				 .' '.$first_name.' '.$last_name;

	// Add number of attached files
	global $countFile;

	$msg = emailBodyToSUTech($id,$email,$subject,$msg,$first_name,$last_name,$phone,$url,$request_time,$countFile);

	$SU_tech_email = 'su-web@email.arizona.edu';  


	// send email
	try {
		mail($SU_tech_email,$title,$msg,$headers);
		emailToClient($email,$id,$first_name,$last_name,$subject);
		unset($_SESSION['WI_old_inputs']);
		return true;
	} catch (Exception $e) {
		global $errors;
		$err_msg = 'There was a problem sending an email! - errCode005';
	    array_push($errors, $err_msg);
	    return false;
	}
}

function emailBodyToSUTech($id,$email,$subject,$webissue,$first_name,$last_name,$phone,$url,$request_time,$countFile){

	$msg = '<html>
    		<head>
    		<style>
    		.suis-table {
    			width:600px; 
    			font-family: "Open Sans", Arial, sans-serif;
			font-size: 12px;
				
    		}
    		.suis-p {
    			margin:0px;
    			margin-bottom:3px;
    			border:2px solid #fff;
    		}
    		.suis-th {
    			width: 30%;
    			padding:5px 5px;
    			background-color: #9E9E9E !important;
    			color: #fff;
    		}
    		.suis-td {
    			
    			min-height:27px;
    			padding:5px 5px;
    			background-color: #d0d0d0;
			
    		}
    		.suiss-th {
    			width: 100%;
    			padding:5px 5px;
    			background-color: #9E9E9E !important;
    			color: #fff;
			
    		}
    		.suiss-td {
    			width: 100%;
    			min-height:27px;
    			padding:10px 10px;
    			background-color: #d0d0d0;
			white-space: normal !important;
    		}
    		</style>
    		</head>
    		<body><table style="width: 100%;"><tr><td><center><table class="suis-table">';
    $msg .= '<tr class="suis-p"><td class="suis-th">ID</td> <td class="suis-td">'.$id.'</td></tr>';
    $msg .= '<tr class="suis-p"><td class="suis-th">Subject</td> <td class="suis-td">'.$subject.'</td></tr>';
    $msg .= '<tr class="suis-p"><td class="suis-th">First Name</td> <td class="suis-td">'.$first_name.'</td></tr>';
    $msg .= '<tr class="suis-p"><td class="suis-th">Last Name</td> <td class="suis-td">'.$last_name.'</td></tr>';
    $msg .= '<tr class="suis-p"><td class="suis-th">Phone</td> <td class="suis-td">'.$phone.'</td></tr>';
    $msg .= '<tr class="suis-p"><td class="suis-th">Email</td> <td class="suis-td">'.$email.'</td></tr>';
    $msg .= '<tr class="suis-p"><td class="suis-th">URL</td> <td class="suis-td">'.$url.'</td></tr></table>';
    $msg .= "<table class='suis-table'><tr class='suis-p'><td class='suiss-th' style=''>Web Issue</td></tr>";
    $msg .= "<tr class='suis-p'><td class='suiss-td' style=''>".$webissue."</td></tr></table>";
    $msg .= '<table class="suis-table"><tr class="suis-p"><td class="suis-th">Request Time</td> <td class="suis-td">'.$request_time.'</td></tr>';
    $msg .= '<tr class="suis-p"><td class="suis-th">Number of Attached Files</td> <td class="suis-td">'.$countFile.'</td></tr>';
    $msg .= '</table></center></td></tr></table></body></html>';
    return $msg;
}

function emailToClient($email,$id,$first_name,$last_name,$subject){
	$title = "SU Tech Web - New Request Received";

	// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: su-web@email.arizona.edu';
	$msg = emailBodyToClient($id,$first_name,$last_name,$subject);

	// send email
	mail($email,$title,$msg,$headers);
}

function emailBodyToClient($id,$first_name,$last_name,$subject){
	$msg = '<html><head></head><body><table style="width: 100%;"><tr><td><table class="suis-table">';
    $msg .= '<tr><td>Name: '.$first_name. ' ' .$last_name. '</td></tr>'; 
    $msg .= '<tr><td>Subject: '.$subject.' </td></tr>';
	$msg .= '<tr><td>&nbsp;</td></tr>';
    $msg .= '<tr><td>Thank you! <br />We have received your request.</td></tr>';
	$msg .= '<tr><td>We will resolve the issue and let you know.<br />  Please feel free to email su-web@email.arizona.edu if you have an additional comment.</td></tr>';
	$msg .= '<tr><td>&nbsp;</td></tr>';
	$msg .= '<tr><td>Student Unions Web</td></tr>';
    $msg .= '</table></td></tr></table></body></html>';
	return $msg;
}

function afterEmailSent(){

	?>
	<script type="text/javascript">

	method = "post";
	path = 'emailConfirm.php';

	var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", 'emailConfirm');
    hiddenField.setAttribute("value", 'sent');

    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();

	</script>
	<?php

}




?>