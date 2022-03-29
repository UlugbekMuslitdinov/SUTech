<?php
//	include("webauth/include.php");
//	require_once("header.php");
//	require_once("sidebar.php");
	define('__ROOT__', dirname(dirname(__FILE__, $levels=2))); //Note $levels=2 tells dirname() to return parent directory path two levels up, not default one, because thsi index.php needs to escape /forms folder to /sucs which is  two levels away
    	include(__ROOT__.'/template/webauth/include.php');
	require_once(__ROOT__.'/template/header.php');
	require_once(__ROOT__.'/template/sidebar.php');
	
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
	$_SESSION["formdata"] = Array();
	$return = false;
	$required_fields = array("supervisor_name", "supervisor_phone", "employee_first_name",
		"employee_last_name", "employee_unit", "form_type");
	$fields = array("supervisor_name"=>80, "supervisor_phone"=>20, "employee_first_name"=>45,
		"employee_last_name"=>45, "employee_unit"=>60, "replacement_catcard"=>20,
		"replacement_other"=>120, "replacement_problem"=>255, "form_type"=>45);
	foreach ($required_fields as $cur) {
		if (!isset($_POST[$cur]) || $_POST[$cur]=="") {
			$errors[$cur] = true;
			$return = true;
		}
	}
	foreach ($fields as $cur=>$len) {
		$_SESSION["formdata"][$cur] = $_POST[$cur];
	}
	if ($return) {
		$_SESSION["errors"] = $errors;
		header('Location: index.php');
		exit;
	}
	
	$conn = select_db("sucs");
	$query  = "INSERT INTO building_access_requests (`";
	$query .= join("`, `", array_keys($fields));
	$query .= "`) VALUES (";
	$query .= rtrim(str_repeat("?, ", count($fields)), ", ");
	$query .= ");";
	$stmt = $conn->prepare($query);
	if(!$stmt){
		error_log("submit_update.php:54: Error in preparing statement: ".$conn->error);
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
		error_log("submit_update.php:70: Failed to insert record: ".$conn->error);
		exit(0);
	}
	
	// $to = "su-tech-serv@email.arizona.edu";
        $to = "su-web@email.arizona.edu";
	$subject = "Update Building Access - ".$_POST["employee_first_name"]." ".$_POST["employee_last_name"];
	$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
	'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Bcc: su-tech@email.arizona.edu\r\n";
	$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
	Submitted: '.date("F j, Y, g:i a").'
	<br /><h2 style="margin-bottom:0;">Supervisor Information</h2>
	Name: <b>'.$_POST['supervisor_name'].'</b><br />
	Phone#: <b>'.$_POST['supervisor_phone'].'</b><br />
	Email: <b><a href="mailto:'.$_POST['supervisor_email'].'">'.$_POST['supervisor_email'].'</a></b><br />
	NetID: <b>'.$_SESSION['webauth']['netID'].'</b></br >';
	
	$message .= '<br /><h2 style="margin-bottom:0;">Employee Information</h2>
	First Name: <b>'.$_POST['employee_first_name'].'</b><br />
	Last Name: <b>'.$_POST['employee_last_name'].'</b><br />
	Catcard#: <b>'.$_POST['catcard'].'</b><br />
	4-digit Pin#: <b>'.$_POST['pin'].'</b><br />
	Unit/Department: <b>'.$_POST['employee_unit'].'</b><br />';
	
	$message .= '<br /><h2 style="margin-bottom:0;">Replacement CatCard#/Other Changes/Problems</h2>
	Replacement Catcard#: <b>'.$_POST["replacement_catcard"].'</b><br />
	Other (Specify): <b>'.$_POST["replacement_other"].'</b><br />
	Problems: <b>'.$_POST["replacement_problem"].'</b><br />';
	
	$message .= '</body></html>';
	$email = mail_switch($to, $subject, $message, $headers);
	
	if ($email) {
		$to = $_POST['supervisor_email'];
		$subject = "Building Access Request Confirmation - ".$_POST["employee_first_name"]." ".$_POST["employee_last_name"];
		$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
		'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		// $headers .= "Bcc: su-web@email.arizona.edu\r\n";
		$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
		<br /><h3 style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;padding-top:5px;padding-bottom:6px;margin-top:0px;margin-bottom:10px;">Building Access Request Summary</h3>
		Submitted: '.date("F j, Y, g:i a").'
		<br />**This is not the date or time received by our staff.**
		<br /><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Please allow at least 1-3 business days to process your request.</i><br/>';
		
		$message .= '<br /><h2 style="margin-bottom:0;">Employee Information</h2>
		First Name: <b>'.$_POST['employee_first_name'].'</b><br />
		Last Name: <b>'.$_POST['employee_last_name'].'</b><br />
		Catcard#: <b>'.$_POST['catcard'].'</b><br />
		4-digit Pin#: <b>####</b><br />
		Unit/Department: <b>'.$_POST['employee_unit'].'</b><br />';
		
		$message .= '<br /><h3 style="margin-bottom:0;">Replacement CatCard#/Other Changes/Problems</h3>
		Replacement Catcard#: <b>'.$_POST["replacement_catcard"].'</b><br />
		Other (Specify): <b>'.$_POST["replacement_other"].'</b><br />
		Problems: <b>'.$_POST["replacement_problem"].'</b><br />';
	
		$message .= '</body></html>';
		$email = mail_switch($to, $subject, $message, $headers);
	}
	
	unset($_SESSION["formdata"]);
?>
<h1>Building Access Request Form</h1><br />
<?php
if ($email) {
	echo '<p>Your request has been received successfully. Please allow at least 1-3 business days to process your request.</p>
	<p><i>The supervisor listed as the contact should receive a confirmation email shortly.</i></p>';
}
else {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occurred.</div>';
}
//    require_once("footer.php");
    require_once(__ROOT__.'/template/footer.php');
?>
