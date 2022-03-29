<?php
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
	
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
	
	$required_fields = array("supervisor_name", "supervisor_phone", "form_type", "access");
	$fields = array("supervisor_name"=>80, "supervisor_phone"=>20, "access"=>0,
		"other_areas"=>120, "alarm_access"=>1, "alarm_area"=>80,
	"form_type"=>45, "net_id"=>45);
$addtl_required_fields = array("employee_first_name", "employee_last_name", "catcard", "employee_unit", "employee_id");
$addtl_fields = array("employee_first_name"=>45, "employee_last_name"=>45, "catcard"=>20, "pin"=>10, "employee_unit"=>60, "employee_id"=>45, "alarm_password"=>20, "net_id"=>45);
	$all_fields = array_merge($fields, $addtl_fields);
	unset($all_fields["access"]);
	if(isset($_POST["employee_info_number_0"])){
		$errors["employee_multi"]=Array();
		for($emp=0; isset($_POST["employee_info_number_".$emp]); $emp++){
			$new_req_fields = $addtl_required_fields;
			$new_fields = $addtl_fields;
			$errors["employee_multi"][$emp]=Array();
			$_SESSION["formdata"]["employee_multi"][$emp]=Array();
			foreach($new_req_fields as $field){
				$actual=$field."_".$emp;
				if (!isset($_POST[$actual]) || $_POST[$actual]=="") {
					$errors["employee_multi"][$emp][$field] = true;
					$return = true;
				}
			}
			foreach($new_fields as $field=>$len){
				$actual=$field."_".$emp;
				$_SESSION["formdata"]["employee_multi"][$emp][$field] = $_POST[$actual];
			}
		}
	}else{
		$return = true;
	}
		
	foreach ($required_fields as $cur) {
		if (!isset($_POST[$cur]) || $_POST[$cur]=="") {
			$errors[$cur] = true;
			$return = true;
		}
	}
	foreach ($fields as $cur=>$len) {
		$_SESSION["formdata"][$cur] = $_POST[$cur];
	}
	if (count($_POST["access"])>0) {
		foreach ($_POST["access"] as $checkbox) {
			$_SESSION["formdata"]["access"][$checkbox] = true;
		}
	}
	if ($return) {
		$_SESSION["errors"] = $errors;
		header('Location: index.php');
		exit;
	}
	
	$conn = select_db("sucs");
	$query  = "INSERT INTO building_access_requests (`";
	$query .= join("`, `", array_keys($all_fields));
	$query .= "`) VALUES (";
	$query .= rtrim(str_repeat("?, ", count($all_fields)), ", ");
	$query .= ");";
	$stmt = $conn->prepare($query);
	if(!$stmt){
		error_log("submit_new.php:86: Error in preparing statement: ".$conn->error);
		exit(0);
	}
	$stmt_emp = Array();
	$stmt_params = Array();
	$stmt_types = "";
	foreach($all_fields as $key=>$max_length){
		if($key=="alarm_access"){
			$stmt_types .= "i";
			$stmt_params[$key] = &$stmt_emp[$key];
		}else{
			$stmt_types .= "s";
			$stmt_params[$key] = &$stmt_emp[$key];
		}
	}
	call_user_func_array(array($stmt, 'bind_param'), array_merge(array($stmt_types), $stmt_params));
	foreach($fields as $key=>$max_length){
		if($key=="alarm_access"){
			$stmt_emp[$key] = $_POST[$key] == "Yes" ? 1 : 0;
		}else if($key!="access"){
			$stmt_emp[$key] = substr($_POST[$key], 0, $max_length);
		}
	}
	
	$query  = "INSERT INTO building_access_request_access (";
	$query .= "request_id, location_id";
	$query .= ") VALUES (?, ?);";
	$stmt2 = $conn->prepare($query);
	if(!$stmt2){
		error_log("submit_new.php:115: Error in preparing secondary statement: ".$conn->error);
		exit(0);
	}
	$stmt2_var = Array("request_id"=>0, "location_id"=>0);
	$stmt2->bind_param("ii", $stmt2_var["request_id"], $stmt2_var["location_id"]);
	
	$to = "su-tech-serv@email.arizona.edu";
	// $to = "su-buildingaccess@list.arizona.edu";
	$count_employees = count($_SESSION["formdata"]["employee_multi"]);
	if($count_employees>1){
		$subject = "Building Access Request - $count_employees Employees";
	}else{
		$subject = "Building Access Request - ".$_SESSION["formdata"]["employee_multi"][0]["employee_first_name"]." ".$_SESSION["formdata"]["employee_multi"][0]["employee_last_name"];
	}
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
	
	foreach($_SESSION["formdata"]["employee_multi"] as $num=>$emp){
		$message .= '<br /><h2 style="margin-bottom:0;">Employee #'.($num+1).' Information</h2>
		First Name: <b>'.$emp['employee_first_name'].'</b><br />
		Last Name: <b>'.$emp['employee_last_name'].'</b><br />
		Catcard#: <b>'.$emp['catcard'].'</b><br />
		4-digit Pin#: <b>'.$emp['pin'].'</b><br />
		Unit/Department: <b>'.$emp['employee_unit'].'</b><br />
	Net ID: <b>'.$emp['net_id'].'</b><br />
		Employee ID: <b>'.$emp['employee_id'].'</b><br />';
		if(isset($emp['alarm_password'])){
			$message .= 'Alarm Password: <b>'.$emp['alarm_password'].'</b><br />';
		}
		
		foreach($emp as $key=>$value){
			$stmt_emp[$key] = $value;
		}
		
		if(!$stmt->execute()){
			error_log("submit_new.php:156: Failed to insert record: ".$conn->error);
			exit(0);
		}
		$access_record_id = $conn->insert_id;
		
		$stmt2_var["request_id"] = $access_record_id;
		
		if(isset($_POST["access"])){
		foreach($_POST["access"] as $loc_id=>$label){
			$stmt2_var["location_id"] = $loc_id;
			if(!$stmt2->execute()){
				error_log("submit_new.php:165: Failed to insert secondary record: ".$conn->error);
				exit(0);
			}
		}
	}
	}
	
	if ($_POST["access"] > 0) {
		$message .= '<br /><h2 style="margin-bottom:0;">New Employee Access</h2>
		<h3 style="margin-bottom:0;">General Access</h3>
		Areas to Access:<br />';
		if ($_POST["access"] > 0) {
			$message .= '<b>';
			foreach ($_POST["access"] as $loc_id=>$checkbox) {
				$message .= $checkbox."<br />";
			}
			$message .= '</b>';
		}
		if (isset($_POST["other_areas"])&&$_POST["other_areas"]!="") {
			$message .= 'Other Areas to Access: <b>'.$_POST["other_areas"].'</b><br />';
		}
		$message .= '<h3 style="margin-bottom:0;">Alarm Access</h3>
		Need Alarm Access?: <b>'.$_POST["alarm_access"]=="Y"?"Yes":"No".'</b><br />';
		if (isset($_POST["alarm_area"])&&$_POST["alarm_area"]!="") {
			$message .= 'Alarm Access Area: <b>'.$_POST["alarm_area"].'</b><br />';
		}
	}
	
	$message .= '</body></html>';
	$email = mail_switch($to, $subject, $message, $headers);
	
	if ($email) {
		$to = $_POST['supervisor_email'];
		if(count($_SESSION["formdata"]["employee_multi"])>1){
			$subject = "Building Access Request Confirmation - $count_employees Employees";
		}else{
			$subject = "Building Access Request Confirmation - ".$_SESSION["formdata"]["employee_multi"][0]["employee_first_name"]." ".$_SESSION["formdata"]["employee_multi"][0]["employee_last_name"];
		}
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
		
		foreach($_SESSION["formdata"]["employee_multi"] as $num=>$emp){
			$message .= '<br /><h2 style="margin-bottom:0;">Employee #'.($num+1).' Information</h2>
			First Name: <b>'.$emp['employee_first_name'].'</b><br />
			Last Name: <b>'.$emp['employee_last_name'].'</b><br />
			Catcard#: <b>'.$emp['catcard'].'</b><br />
			4-digit Pin#: <b>####</b><br />
			Unit/Department: <b>'.$emp['employee_unit'].'</b><br />
		Net ID: <b>'.$emp['net_id'].'</b><br />
			Employee ID: <b>'.$emp['employee_id'].'</b><br />';
			if(isset($emp['alarm_password'])){
				$message .= 'Alarm Password: <b>######</b><br />';
			}
		}
	
		if ($_POST["access"] > 0){
			$message .= '<br /><h3 style="margin-bottom:0;">New Employee Access</h3>
			<h4 style="margin-bottom:0;">General Access</h4>
			Areas to Access:<br />';
			if ($_POST["access"] > 0) {
				$message .= '<b>';
				foreach ($_POST["access"] as $checkbox) {
					$message .= $checkbox."<br />";
				}
				$message .= '</b>';
			}
			if (isset($_POST["other_areas"])&&$_POST["other_areas"]!="") {
				$message .= 'Other Areas to Access: <b>'.$_POST["other_areas"].'</b><br />';
			}
			$message .= '<h4 style="margin-bottom:0;">Alarm Access</h4>
			Need Alarm Access?: <b>'.$_POST["alarm_access"]=="Y"?"Yes":"No".'</b><br />';
			if (isset($_POST["alarm_area"])&&$_POST["alarm_area"]!="") {
				$message .= 'Alarm Access Area: <b>'.$_POST["alarm_area"].'</b><br />';
			}
		}
	
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
    require_once("footer.php");
?>
