<?php
//	include("webauth/include.php");
//	require_once("mysql/include.php");
//	require_once("header.php");
//	require_once("sidebar.php");
	define('__ROOT__', dirname(dirname(__FILE__, $levels=2))); //Note $levels=2 tells dirname() to return parent directory path two levels up, not default one, because thsi index.php needs to escape /forms folder to /sucs which is  two levels away
    	include(__ROOT__.'/template/webauth/include.php');
	require_once(__ROOT__.'/template/mysql/include.php');
	require_once(__ROOT__.'/template/header.php');
	require_once(__ROOT__.'/template/sidebar.php');
	
	function mail_switch($to, $subject, $message, $headers){
		//if(strpos($_SERVER["HTTP_HOST"], "127.0.")!==false){
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
	$required_fields = array("supervisor_name", "supervisor_phone");
	$fields = array("supervisor_name"=>80, "supervisor_phone"=>20);
	$addtl_required_fields = array("request_type", "employee_first_name",
		"employee_last_name", "employee_title", "employee_email", "employee_phone",
		"employee_unit", "employee_netid", "employee_id", "access");
	$addtl_fields = array("request_type"=>20, "employee_position"=>20, "employee_first_name"=>45,
		"employee_last_name"=>45, "employee_title"=>80, "employee_email"=>80, "employee_phone"=>20,
		"employee_unit"=>60, "employee_netid"=>60, "employee_id"=>30, "access"=>0,
		"catcard"=>20, "register_pin"=>20, "updates"=>0, "comments"=>250);
	$all_fields = array_merge($fields, $addtl_fields);
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
			foreach($new_fields as $field=>$max_length){
				$actual=$field."_".$emp;
				if(is_array($_POST[$actual])){
					$_SESSION["formdata"]["employee_multi"][$emp][$field] = Array();
					foreach($_POST[$actual] as $subfield){
						$_SESSION["formdata"]["employee_multi"][$emp][$field][$subfield] = true;
					}
				}else{
					$_SESSION["formdata"]["employee_multi"][$emp][$field] = substr($_POST[$actual], 0, $max_length);
				}
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
	foreach ($fields as $cur=>$max_length) {
		$_SESSION["formdata"][$cur] = substr($_POST[$cur], 0, $max_length);
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
	$query  = "INSERT INTO pos_access_requests (";
	$query .= join(", ", array_keys($all_fields));
	$query .= ") VALUES (";
	$query .= rtrim(str_repeat("?, ", count($all_fields)), ", ");
	$query .= ");";
	$stmt = $conn->prepare($query);
	if(!$stmt){
		error_log("submit.php:".__LINE__.": Error in preparing statement: ".$conn->error);
		exit(0);
	}
	$stmt_emp = Array();
	$stmt_params = Array();
	$stmt_types = "";
	foreach($all_fields as $key=>$max_length){
		if($key=="access"){
			$stmt_types .= "i";
			$stmt_params["access_flags"] = &$stmt_emp["access_flags"];
		}else if($key=="updates"){
			$stmt_types .= "i";
			$stmt_params["updates_flags"] = &$stmt_emp["updates_flags"];
		}else{
			$stmt_types .= "s";
			$stmt_params[$key] = &$stmt_emp[$key];
		}
	}
	call_user_func_array(array($stmt, 'bind_param'), array_merge(array($stmt_types), $stmt_params));
	foreach($fields as $key=>$max_length){
		$stmt_emp[$key] = substr($_POST[$key], 0, $max_length);
	}
	
	// $to = "yontaek@email.arizona.edu";
	$to = "su-tech-serv@email.arizona.edu";
	
	$count_employees = count($_SESSION["formdata"]["employee_multi"]);
	if($count_employees > 1){
		$subject = "Point of Sales System Access Request - ".$count_employees." Employees";
	}else{
		$subject = "Point of Sales System Access Request - ".$_SESSION["formdata"]["employee_multi"][0]["employee_type"]." - ".$_SESSION["formdata"]["employee_multi"][0]["employee_first_name"]." ".$_SESSION["formdata"]["employee_multi"][0]["employee_last_name"];
	}
	$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
	   'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
	   'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Bcc: su-tech-serv@email.arizona.edu\r\n";
    // $headers .= "Bcc: su-web@email.arizona.edu\r\n";
	$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
	Submitted: '.date("F j, Y, g:i a").'
	<br /><h2 style="margin-bottom:0;">Supervisor Information</h2>
	Name: <b>'.$_POST['supervisor_name'].'</b><br />
	Phone#: <b>'.$_POST['supervisor_phone'].'</b><br />
	NetID: <b>'.$_SESSION['webauth']['netID'].'</b></br >';
	
	$current_employee = 1;
	foreach($_SESSION["formdata"]["employee_multi"] as $num=>$emp){
		if($count_employees > 1){
			$message .= '<br /><h2 style="margin-bottom:0;">Employee Information #'.$current_employee.'</h2>';
		}else{
			$message .= '<br /><h2 style="margin-bottom:0;">Employee Information</h2>';
		}
		$message .= 'Type: <b>'.$emp['request_type'].'</b><br />';
		if($emp['employee_position']!=""){
			$message .= 'Position: <b>'.$emp['employee_position'].'</b><br />';
		}
		$message .= 'First Name: <b>'.$emp['employee_first_name'].'</b><br />
		Last Name: <b>'.$emp['employee_last_name'].'</b><br />
		Title\Job: <b>'.$emp['employee_title'].'</b><br />
		E-mail: <b>'.$emp['employee_email'].'</b><br />
		Work Phone#: <b>'.$emp['employee_phone'].'</b><br />
		Department/Unit: <b>'.$emp['employee_unit'].'</b><br />
		NetID: <b>'.$emp['employee_netid'].'</b><br />
		Employee ID: <b>'.$emp['employee_id'].'</b><br />
		
		<br /><h2 style="margin-bottom:0;">Employee Access</h2>
		Systems to grant access:<br />
		<b>';
		if (count($emp["access"])>0) {
			foreach ($emp["access"] as $checkbox=>$value) {
				$message .= $checkbox."<br />";
			}
		}
		$message .= '</b>';
		if (!empty($emp['register_pin'])) {
			$message .= 'Register Pin: <b>'.$emp['register_pin'].'</b><br />';
		}
		if (!empty($emp['catcard'])) {
			$message .= 'Catcard#: <b>'.$emp['catcard'].'</b><br />';
		}
		
		$access_checkbox_bitmap = Array(
			"Cashier Access"		=> 0b001,
			"Lead Cashier Access" 	=> 0b010,
			"Sequoia Web Reporting"	=> 0b100
		);
		$access_flags = 0b000;
		foreach($emp["access"] as $checkbox=>$value){
			$access_flags |= $access_checkbox_bitmap[$checkbox];
		}
		$stmt_emp["access_flags"] = $access_flags;
		
		if (is_array($emp["updates"]) && count($emp["updates"])) {
			$message .= '<br /><h2 style="margin-bottom:0;">New Employee Updates</h2>
			What\'s being updated:<br />
			<b>';
			foreach ($emp["updates"] as $checkbox=>$value) {
				$message .= $checkbox."<br />";
			}
			$message .= '</b>';
		}
		
		$updates_checkbox_bitmap = Array(
			"Profit Center Location Change"	=> 0b00001,
			"Remove Profit Center Location"	=> 0b00010,
			"Cashier Access Level"			=> 0b00100,
			"Catcard # Update"				=> 0b01000,
			"Pin # Change"					=> 0b10000
		);
		$updates_flags = 0b00000;
		if (is_array($emp["updates"])) {
			foreach(@$emp["updates"] as $checkbox=>$value){
				$updates_flags |= $updates_checkbox_bitmap[$checkbox];
			}
		}
		$stmt_emp["updates_flags"] = $updates_flags;
		
		foreach($emp as $key=>$value){
			$stmt_emp[$key] = $value;
		}
		if(!$stmt->execute()){
			error_log("submit.php:".__LINE__.": Failed to insert record: ".$conn->error);
			exit(0);
		}
		
		$current_employee++;
	}
	$message .= '</body></html>';
	$email = mail_switch($to, $subject, $message, $headers);

    if ($email) {
		$to = $_SESSION['webauth']['netID']."@email.arizona.edu";
		if($count_employees > 1){
			$subject = "Point of Sales System Access Request - ".$count_employees." Employees";
		}else{
			$subject = "Point of Sales System Access Request - ".$_SESSION["formdata"]["employee_multi"][0]["employee_first_name"]." ".$_SESSION["formdata"]["employee_multi"][0]["employee_last_name"];
		}
		$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
		'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		// $headers .= "Bcc: su-web@email.arizona.edu\r\n";
		$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
		<br /><h3 style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;padding-top:5px;padding-bottom:6px;margin-top:0px;margin-bottom:10px;">Workstation Systems Access Request Summary</h3>
		Submitted: '.date("F j, Y, g:i a").'
		<br />**This is not the date or time received by our staff.**
		<br /><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Please allow at least 2-5 business days to process your request.</i>';
		
		$current_employee = 1;
		foreach($_SESSION["formdata"]["employee_multi"] as $num=>$emp){
			if($count_employees > 1){
				$message .= '<br /><h2 style="margin-bottom:0;">Employee Information #'.$current_employee.'</h2>';
			}else{
				$message .= '<br /><h2 style="margin-bottom:0;">Employee Information</h2>';
			}
			$message .= 'Type: <b>'.$emp['request_type'].'</b><br />';
			if($emp['employee_position']!=""){
				$message .= 'Position: <b>'.$emp['employee_position'].'</b><br />';
			}
			$message .= 'First Name: <b>'.$emp['employee_first_name'].'</b><br />
			Last Name: <b>'.$emp['employee_last_name'].'</b><br />
			Title\Job: <b>'.$emp['employee_title'].'</b><br />
			E-mail: <b>'.$emp['employee_email'].'</b><br />
			Department/Unit: <b>'.$emp['employee_unit'].'</b><br />
			
			<br /><h3 style="margin-bottom:0;">Employee Access</h3>
			Systems to grant access:<br />
			<b>';
			if (count($emp["access"])>0) {
				foreach ($emp["access"] as $checkbox=>$value) {
					$message .= $checkbox."<br />";
				}
			}
			$message .= '</b>';
			if (!empty($emp['register_pin'])) {
				$message .= 'Register Pin: <b>'.$emp['register_pin'].'</b><br />';
			}
			if (!empty($emp['catcard'])) {
				$message .= 'Catcard#: <b>'.$emp['catcard'].'</b><br />';
			}
			
			if (is_array($emp["updates"]) && count($emp["updates"])>0) {
				$message .= '<br /><h3 style="margin-bottom:0;">New Employee Updates</h3>
				What\'s being updated:<br />
				<b>';
				foreach ($emp["updates"] as $checkbox=>$value) {
					$message .= $checkbox."<br />";
				}
				$message .= '</b>';
			}
			
			if (!empty($emp['comments'])) {
				$message .= '<br/>Comments: <b>'.$emp['comments'].'</b><br />';
			}
			$current_employee++;
		}
		$message .= '</body></html>';
		$email = mail_switch($to, $subject, $message, $headers);
    }
	unset($_SESSION["formdata"]);
?>
<h1>Point of Sale Access Request Form</h1><br />
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
