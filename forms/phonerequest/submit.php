<?php 

//include($_SERVER["DOCUMENT_ROOT"] . "forms/phonerequest/db/db.php");
//include($_SERVER["DOCUMENT_ROOT"] . "forms/phonerequest/email.class.php");
//define('__ROOT__', dirname(dirname(__FILE__)));
include($_SERVER["DOCUMENT_ROOT"] . "/forms/phonerequest/db/db.php");
include($_SERVER["DOCUMENT_ROOT"] . "/forms/phonerequest/email.class.php");

$fields = "supervisor_name, supervisor_phone, employee_status, building, room_number, net_id, jack, jack_id, voicemail, long_distance, need_phone, call_appearance, kfs_number, call_appearance1, call_appearance2, call_appearance3, call_appearance4";


// ADD JACK_ID
$sql = "INSERT INTO phone_requests ($fields) VALUES ('$_POST[supervisor_name]', '$_POST[supervisor_phone]', '$_POST[employee_status]', '$_POST[building]', '$_POST[room_number]', '$_POST[net_id]', '$_POST[jack]', '$_POST[jack_id]', '$_POST[voicemail]', '$_POST[long_distance]', '$_POST[need_phone]', '$_POST[call_appearance]', '$_POST[kfs_number]', '$_POST[call_appearance1]', '$_POST[call_appearance2]', '$_POST[call_appearance3]', '$_POST[call_appearance4]')";

if ($conn->query($sql) === TRUE) {
} else {
	// print_r($_POST);
    echo "Error: " . $sql . "<br>" . $conn->error;
}

function generateEmail() {
	$msg = "<h2>Phone Request Form</h2>";
	$msg .= "<table>";

	$msg .= "<tr><td>Supervisor's Name: </td><td>$_POST[supervisor_name]</td></tr>";
	$msg .= "<tr><td>Supervisor's Phone: </td><td>$_POST[supervisor_phone]</td></tr>";
	$msg .= "<tr><td>Building: </td><td>$_POST[building]</td></tr>";
	$msg .= "<tr><td>Room Number: </td><td>$_POST[room_number]</td></tr>";
	$msg .= "<tr><td>Employee Status: </td><td>$_POST[employee_status]</td></tr>";
	$msg .= "<tr><td>Net ID: </td><td>$_POST[net_id]</td></tr>";
	$msg .= "<tr><td>Needs Jack?: </td><td>$_POST[jack]</td></tr>";

	if($_POST['jack'] == "yes")
		$msg .= "<tr><td>Jack ID: </td><td>$_POST[jack_id]</td></tr>";

	$msg .= "<tr><td>Voicemail: </td><td>$_POST[voicemail]</td></tr>";
	$msg .= "<tr><td>Long Distance: </td><td>$_POST[long_distance]</td></tr>";
	$msg .= "<tr><td>Needs Phone: </td><td>$_POST[need_phone]</td></tr>";
	$msg .= "<tr><td>Call Appearances: </td><td>$_POST[call_appearance]</td></tr>";
	if($_POST['call_appearance'] == "yes"){
		$msg .= "<tr><td>Call Appearance 1: </td><td>$_POST[call_appearance1]</td></tr>";
		$msg .= "<tr><td>Call Appearance 2: </td><td>$_POST[call_appearance2]</td></tr>";
		$msg .= "<tr><td>Call Appearance 3: </td><td>$_POST[call_appearance3]</td></tr>";
		$msg .= "<tr><td>Call Appearance 4: </td><td>$_POST[call_appearance4]</td></tr>";
	}

	$msg .= "<tr><td>KFS Number: </td><td>$_POST[kfs_number]</td></tr>";
	$msg .= "</table>";
	return $msg;
}

	$email = new sendEmail();
	// Send email to SU Tech
	$email->setSender($_POST["supervisor_name"], $_POST["net_id"] . '@email.arizona.edu');
	$email->setReceiver('SU Tech', 'su-tech-serv@email.arizona.edu');
	// $email->setReceiver('SU Tech', 'yontaek@hotmail.com');
	$email->changeEmailSetting('msgContainHtml',true);
	$email->setEmailTitle('Phone Request Form');

	$msg = generateEmail();

	$email->setMessage($msg);
	$err = $email->finallySendEmail($_POST["supervisor_name"],'SU Tech');

	// Send copy to supervisor
	$email->setReceiver($_POST['supervisor_name'],  $_POST["net_id"] . '@email.arizona.edu');
	$email->setSender('SU Tech', 'su-tech-serv@email.arizona.edu');
	//$email->setSender('SU Tech', 'yontaek@hotmail.com');
	$email->setMessage($msg);
	$err = $email->finallySendEmail('SU Tech', $_POST["supervisor_name"]);

	header("Location: https://tech.union.arizona.edu");

	include($_SERVER["DOCUMENT_ROOT"] . "/forms/phonerequest/confirmation.php");

	$conn->close();

	exit();

?>