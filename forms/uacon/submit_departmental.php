<?php
	
//	include("webauth/include.php");
//	require_once("header.php");
//	require_once("sidebar.php");
//	define('__ROOT__', dirname(dirname(__FILE__, $levels=2))); //Note $levels=2 tells dirname() to return parent directory path two levels up, not default one, because thsi index.php needs to escape /forms folder to /sucs which is  two levels away
    	include($_SERVER["DOCUMENT_ROOT"].'/template/webauth/include.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/header.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/sidebar.php');
	
	function mail_switch($to, $subject, $message, $headers){
		if(false){
			echo "<h1>TO: ".$to."</h1>";
			echo "<h2>Subject: ".$subject."</h2>";
			echo "<pre style=\"font-size: 8px;\" >Headers:<br/>".$headers."</pre><hr/>";
			echo $message;
			echo "<br/><hr/>";
			return true;
		}else{
			return mail($to, $subject, $message, $headers);
		}
	}
	
	if ($_POST["cancel"] == "Cancel") {
		header('Location: /index.php');
		exit;
	}
	
	unset($_SESSION["errors"]);
	$errors = array();
	$return = false;
	$required_fields = array("supervisor_name", "supervisor_phone", "supervisor_email",
		"department", "name_1", "description");
	foreach ($required_fields as $cur) {
		if (!isset($_POST[$cur]) || $_POST[$cur]=="") {
			$errors[$cur] = true;
			$return = true;
		}
	}
	
	$fields = array("supervisor_name"=>80, "supervisor_phone"=>20, "supervisor_email"=>80,
			"department"=>60, "name_1"=>60, "name_2"=>60, "name_3"=>60, "description"=>255);
	foreach ($fields as $cur=>$len) {
		$_POST["formdata"][$cur] = addslashes($_POST["formdata"][$cur]);
		$_SESSION["formdata"][$cur] = $_POST[$cur];
	}
	$_SESSION["formdata"]["form_type"] = $_POST["form_type"];
	
	if ($return) {
		$_SESSION["errors"] = $errors;
		header('Location: index.php');
		exit;
	}
	unset($_SESSION["formdata"]);
	
	$conn = select_db("sucs");
	$query  = "INSERT INTO departmental_account_requests (`";
	$query .= join("`, `", array_keys($fields));
	$query .= "`) VALUES (";
	$query .= rtrim(str_repeat("?, ", count($fields)), ", ");
	$query .= ");";
	$stmt = $conn->prepare($query);
	if(!$stmt){
		error_log("submit_departmental.php:48: Error in preparing statement: ".$conn->error);
		exit(0);
	}
	$stmt_emp = Array();
	$stmt_params = Array();
	$stmt_types = "";
	foreach($fields as $key=>$max_length){
		$stmt_types .= "s";
		$stmt_params[$key] = &$stmt_emp[$key];
	}
	call_user_func_array(array($stmt, 'bind_param'), array_merge(array($stmt_types), $stmt_params));
	foreach($fields as $key=>$max_length){
		$stmt_emp[$key] = substr($_POST[$key], 0, $max_length);
	}
	if(!$stmt->execute()){
		error_log("submit_departmental.php:66: Failed to insert record: ".$conn->error);
		exit(0);
	}
	
	$to = "su-tech-serv@email.arizona.edu";
	//$to = "su-uamemail@list.arizona.edu";
	//$to = "su-web@email.arizona.edu";
	$subject = "UAConnect Catworks Account/Access Request - (".$_POST['name_1'].")";
	$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
	'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Bcc: su-tech-serv@email.arizona.edu\r\n";
	//$headers .= "Bcc: su-tech@email.arizona.edu\r\n";
	$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
	Submitted: '.date("F j, Y, g:i a").'
	<br /><h2 style="margin-bottom:0;">Supervisor Information</h2>
	Name: <b>'.$_POST['supervisor_name'].'</b><br />
	Phone#: <b>'.$_POST['supervisor_phone'].'</b><br />
	NetID: <b>'.$_SESSION['webauth']['netID'].'</b><br />';
	
	$message .= 'Unit/Department: <b>'.$_POST['department'].'</b><br />';
	
	$message .= '<br /><h2 style="margin-bottom:0;">Account Information</h2>';
	$message .= 'Account Name Choice #1: <b>'.$_POST['name_1'].'</b><br />';
	if (isset($_POST['name_2']) && $_POST['name_2']!="") {
		$message .= 'Account Name Choice #2: <b>'.$_POST['name_2'].'</b><br />';
	}
	if (isset($_POST['name_2']) && $_POST['name_3']!="") {
		$message .= 'Account Name Choice #3: <b>'.$_POST['name_3'].'</b><br />';
	}
	$message .= 'Description of Account Use: <b>'.$_POST['description'].'</b><br />';
	
	$message .= '</body></html>';
	$email = mail_switch($to, $subject, $message, $headers);
	
	if ($email) {
		$to = $_POST['supervisor_email'];
		$subject = "UAConnect Catworks Account/Access Request Confirmation - (".$_POST['name_1'].")";
		$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
		'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "Bcc: su-web@email.arizona.edu\r\n";
		$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
		<br /><h3 style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;padding-top:5px;padding-bottom:6px;margin-top:0px;margin-bottom:10px;">Departmental/Catworks Email Account Request Summary</h3>
		Submited: '.date("F j, Y, g:i a").'
		<br />**This is not the date or time received by our staff.**
		<br /><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Please allow at least 2-5 business days to process your request.</i>';
	
		$message .= '<h2 style="margin-bottom:0;">Account Information</h2>';
		$message .= 'Unit/Department: <b>'.$_POST['department'].'</b><br />';
		$message .= 'Account Name Choice #1: <b>'.$_POST['name_1'].'</b><br />';
		if (isset($_POST['name_2']) && $_POST['name_2']!="") {
			$message .= 'Account Name Choice #2: <b>'.$_POST['name_2'].'</b><br />';
		}
		if (isset($_POST['name_2']) && $_POST['name_3']!="") {
			$message .= 'Account Name Choice #3: <b>'.$_POST['name_3'].'</b><br />';
		}
		$message .= 'Description of Account Use: <b>'.$_POST['description'].'</b><br />';
	
		$message .= '</body></html>';
		$email = mail_switch($to, $subject, $message, $headers);
	}
?>
<h1>Departmental Email Account Request</h1><br />
<?php
if ($email) {
	echo '<p>Your request has been received successfully. Please allow at least 2-5 business days to process your request.</p>
    <p><i>The supervisor listed as the contact should receive a confirmation email shortly.</i></p>';
}
else {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occurred.</div>';
}
//    require_once("footer.php");
    require_once(__ROOT__.'/template/footer.php');
?>