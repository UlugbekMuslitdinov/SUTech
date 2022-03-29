<?php

include_once('db.class.php');

$student_email = $_POST['email']. '@email.arizona.edu';

$db = new DB();

$db_insert = $db->insert('nutrition_class');

$db_insert->into('first_name',$_POST['first_name'])
->into('last_name',$_POST['last_name'])
->into('email', $student_email)
->into('student_id',$_POST['student_id'])
->into('phone_number',$_POST['phone_number'])
->into('payment_option',$_POST['payment_option'])
->into('class_time', $_POST['time']);

if(isset($_POST['select_class'])){
	$db_insert->into('class_name', $_POST['select_class']);
}

$db_check = $db_insert->save();

$msg = '<h1>PlantEd Workshop Sign Up</h1><br>';
$msg .= 'First Name: ' . $_POST['first_name'] . '<br>';
$msg .= 'Last Name: ' . $_POST['last_name'] . '<br>';
$msg .= 'Email: ' . $student_email . '<br>';
$msg .= 'Student ID: ' . $_POST['student_id'] . '<br>';
$msg .= 'Phone Number: ' . $_POST['phone_number'] . '<br>';
$msg .= 'Payment Option: ' . $_POST['payment_option'] . '<br>';

if($_POST['payment_option'] != 'All Classes' && isset($_POST['select_class'])) {
	$msg .= 'Class: ' . $_POST['select_class'] . '<br>';
}

$msg .= 'Time: ' . $_POST['time'] . '<br>';
$msg .= 'Swipe Meal Plan: ' . $_POST['swipe_meal_plan'] . '<br>';


// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: PlantEd Workshop Sign Up <su-nutrition@email.arizona.edu>' . "\r\n";

mail("christinecarlson@email.arizona.edu","PlantEd Workshop Sign Up",$msg, $headers);
// mail("yontaek@gmail.com","PlantEd Workshop Sign Up",$msg, $headers);


$to_student = '<h1>PlantEd Workshop Sign Up</h1><br>';
$to_student = '<h2>Hi ' . $_POST['first_name'] . ' ' . $_POST['last_name'] . '!</h2>';
$to_student .= '<p>Please pay the required fee at NRich Urban Market in the Food Court (next to Einstein\'s) in the Student Union Memorial Center.  Payment options include     Meal Plan, Credit Card or Cash.  Your registration is not guaranteed until payment is made and classes are limited to 25 attendees.</p>';
$to_student .= 'Student ID: ' . $_POST['student_id'] . '<br>';
$to_student .= 'Phone Number: ' . $_POST['phone_number'] . '<br>';
$to_student .= 'Payment Option: ' . $_POST['payment_option'] . '<br>';

if($_POST['payment_option'] != 'All Classes' && isset($_POST['select_class'])) {
	$to_student .= 'Class: ' . $_POST['select_class'] . '<br>';
}

$to_student .= 'Time: ' . $_POST['time'] . '<br>';
$to_student .= 'Swipe Meal Plan: ' . $_POST['swipe_meal_plan'] . '<br>';

mail($student_email,"PlantEd Workshop Sign Up", $to_student, $headers);



if($temp)
	echo "records inserted successfully";


echo "<script>alert(\"You will receive a confirmation email soon. You MUST go to NRich Urban Market to pay for the classes.\");window.location = \"http://nutrition.union.arizona.edu\";</script>";


// header("Location: http://nutrition.union.arizona.edu");


?>