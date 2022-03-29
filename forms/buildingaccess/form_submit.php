<?php
session_start();
// Process the form submission.
$supervisor_name = $_POST['supervisor_name'];
$supervisor_email = $_POST['supervisor_email'];
$supervisor_phone = $_POST['supervisor_phone'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$carcard = $_POST['catcard'];
$netid = $_POST['netid'];
$unit = $_POST['unit'];
$employee_id = $_POST['employee_id'];
$north85 = $_POST['north85'];
$alarm_access = $_POST['alarm_access'];
$alarm_pin = $_POST['alarm_pin'];
$alarm_password = $_POST['alarm_password'];
$alarm_other = $_POST['alarm_other'];
// Get access array.
$access = $_POST['access'];
// Get alarm area array.
$alarm_area = $_POST['alarm_area'];

// Send email.
$to = "su-web@email.arizona.edu";
// $to = "su-tech-serv@email.arizona.edu";
$to_supervisor = $_POST['supervisor_email'];
$subject = "Building Access Request - $first_name $last_name";
$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
'X-Mailer: PHP/' . phpversion();
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers .= "Bcc: " . $supervisor_email . "\r\n";
$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
	Submitted: '.date("F j, Y, g:i a").'
	<br /><h2 style="margin-bottom:0;">Supervisor Information</h2>
	Name: <b>'.$supervisor_name.'</b><br />
	Phone#: <b>'.$supervisor_phone.'</b><br />
	Email: <b><a href="mailto:'.$supervisor_email.'">'.$supervisor_email.'</a></b><br />
	NetID: <b>'.$_SESSION['webauth']['netID'].'</b></br >';
$message .= '<br /><h2 style="margin-bottom:0;">Employee Information</h2>
	First Name: <b>'.$first_name.'</b><br />
	Last Name: <b>'.$last_name.'</b><br />
	Catcard#: <b>'.$carcard.'</b><br />
	Net ID: <b>'.$netid.'</b><br />
	Unit/Department: <b>'.$unit.'</b><br />
	Employee ID: <b>'.$employee_id.'</b><br />
	85 North 4-digit Pin#: <b>'.$north85.'</b><br />';
$message .= '<br /><h2 style="margin-bottom:0;">General Access</h2>';
foreach($access as $access_array) {
	$message .= '<div>'.$access_array.'</div>';	
}
$message .= '<h2 style="margin-bottom:0;">Alarm Access</h2>
	Need Alarm Access: <b>'.$alarm_access.'</b><br />
	Alarm Pin: <b>'.$alarm_pin.'</b><br />
	Alarm Password: <b>'.$alarm_password.'</b><br />';
$message .= '<h3 style="margin-bottom:0;">Alarm Access Area</h3>';
foreach($alarm_area as $alarm_area_array) {
	$message .= '<div>'.$alarm_area_array.'</div>';	
}
$message .= '<br /><b>Other: '.$alarm_other.'</b><br />';
mail($to, $subject, $message, $headers);

// Redirect after sending email.
header("Location: http://".$_SERVER[HTTP_HOST]."/index.php");
?>
