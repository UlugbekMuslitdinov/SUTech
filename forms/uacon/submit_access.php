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
	$return = false;
	$required_fields = array("supervisor_name", "supervisor_phone", "supervisor_email", "account_name");
	$fields = array("supervisor_name"=>80, "supervisor_phone"=>20, "supervisor_email"=>80, "account_name"=>0, "delete"=>1);
	$addtl_required_fields = array("employee_first_name", "employee_last_name", "employee_netid");
	$addtl_fields = array("employee_first_name"=>45, "employee_last_name"=>45, "employee_netid"=>60, "new_catwork"=>1);
	$all_fields = array_merge($fields, $addtl_fields);
	unset($all_fields["account_name"]);
	$_SESSION["formdata"]["form_type"] = $_POST["form_type"];
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
					$_SESSION["formdata"]["employee_multi"][$emp][$field] = $_POST[$actual];
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
		if(is_array($_POST[$cur])){
			$_SESSION["formdata"][$cur] = $_POST[$cur];
		}else{
			$_SESSION["formdata"][$cur] = substr($_POST[$cur], 0, $max_length);
		}
	}
	if ($return) {
		$_SESSION["errors"] = $errors;
		header('Location: index.php');
		exit;
	}
	
	$conn = select_db("sucs");
	$query  = "INSERT INTO departmental_access_requests (`";
	$query .= join("`, `", array_keys($all_fields));
	$query .= "`) VALUES (";
	$query .= rtrim(str_repeat("?, ", count($all_fields)), ", ");
	$query .= ");";
	$stmt = $conn->prepare($query);
	if(!$stmt){
		error_log("submit_access.php:86: Error in preparing statement: ".$conn->error);
		exit(0);
	}
	$stmt_emp = Array();
	$stmt_params = Array();
	$stmt_types = "";
	foreach($all_fields as $key=>$max_length){
		if($key=="delete" || $key=="new_catwork"){
			$stmt_types .= "i";
			$stmt_params[$key] = &$stmt_emp[$key];
		}else{
			$stmt_types .= "s";
			$stmt_params[$key] = &$stmt_emp[$key];
		}
	}
	call_user_func_array(array($stmt, 'bind_param'), array_merge(array($stmt_types), $stmt_params));
	foreach($fields as $key=>$max_length){
		if($key=="delete"){
			$stmt_emp["delete"] = !empty($_POST["delete"]) ? 1 : 0;
		}else if($key!="account_name"){
			$stmt_emp[$key] = substr($_POST[$key], 0, $max_length);
		}
	}
	
	$query  = "INSERT INTO departmental_access_request_account (";
	$query .= "request_id, exch_department_id";
	$query .= ") VALUES (?, ?);";
	$stmt2 = $conn->prepare($query);
	if(!$stmt2){
		error_log("submit_access.php:115: Error in preparing secondary statement: ".$conn->error);
		exit(0);
	}
	$stmt2_var = Array("request_id"=>0, "exch_department_id"=>0);
	$stmt2->bind_param("ii", $stmt2_var["request_id"], $stmt2_var["exch_department_id"]);
	
	$to = "su-tech-serv@email.arizona.edu";
	//$to = "su-uamemail@list.arizona.edu";
	//$to = "su-web@email.arizona.edu";
	
	$count_employees = count($_SESSION["formdata"]["employee_multi"]);
	if($count_employees > 1){
		$subject = "UAConnect Catworks Account/Access Request - ".$count_employees." Employees";
	}else{
		$subject = "UAConnect Catworks Account/Access Request - ".$_SESSION["formdata"]["employee_multi"][0]["employee_first_name"]." ".$_SESSION["formdata"]["employee_multi"][0]["employee_last_name"]." (".$_SESSION["formdata"]["employee_multi"][0]["employee_netid"].")";
	}
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
	
	$message .= 'Departmental Account(s):<br />';
	$result = $conn->query("SELECT * FROM sucs.exch_departments");
	$account_list = Array();
	while($inter = $result->fetch_assoc()){$account_list[$inter["department_id"]] = $inter["name"];}
	foreach($_POST["account_name"] as $acct_id){
		$message .= "<b>".$account_list[$acct_id]."@email.arizona.edu</b><br/>";
	}
	
	$current_employee = 1;
	foreach($_SESSION["formdata"]["employee_multi"] as $num=>$emp){
		if($count_employees > 1){
			$message .= '<br /><h2 style="margin-bottom:0;">Employee Information #'.$current_employee.'</h2>';
		}else{
			$message .= '<br /><h2 style="margin-bottom:0;">Employee Information</h2>';
		}
		if (isset($emp['new_catwork']) && $emp['new_catwork']!="") {
			$message .= 'Type: <b>New Catwork Account Request</b><br />';
		}
		else {
			$message .= 'Type: <b>Existing Exchange Account Request</b><br />';
		}
		$message .= 'First Name: <b>'.$emp['employee_first_name'].'</b><br />';
		$message .= 'Last Name: <b>'.$emp['employee_last_name'].'</b><br />';
		$message .= 'NetID: <b>'.$emp['employee_netid'].'</b><br />';
		
		foreach($emp as $key=>$value){
			$stmt_emp[$key] = $value;
		}
		
		if(!$stmt->execute()){
			error_log("submit_access.php:173: Failed to insert record: ".$conn->error);
			exit(0);
		}
		$access_record_id = $conn->insert_id;
		
		$stmt2_var["request_id"] = $access_record_id;
		foreach($_POST["account_name"] as $acct_id){
			$stmt2_var["exch_department_id"] = $acct_id;
			if(!$stmt2->execute()){
				error_log("submit_access.php:182: Failed to insert secondary record: ".$conn->error);
				exit(0);
			}
		}
		
		$current_employee++;
	}
	
	if (isset($_POST['delete']) && $_POST['delete']!="") {
		$message .= '<br/><h3>Deletion: </h3><b>Remove Employee Access</b><br />';
	}
	
	$message .= '</body></html>';
	$email = mail_switch($to, $subject, $message, $headers);
	
	if ($email) {
		$to = $_POST['supervisor_email'];
		if($count_employees > 1){
			$subject = "UAConnect Catworks Account/Access Request Confirmation - ".$count_employees." Employees";
		}else{
			$subject = "UAConnect Catworks Account/Access Request Confirmation - ".$_SESSION["formdata"]["employee_multi"][0]["employee_first_name"]." ".$_SESSION["formdata"]["employee_multi"][0]["employee_last_name"]." (".$_SESSION["formdata"]["employee_multi"][0]["employee_netid"].")";
		}
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
		Please allow at least 2-5 business days to process your request.</i><br/>';
		
		$message .= 'Departmental Account(s):<br />';
		foreach($_POST["account_name"] as $acct_id){
			$message .= "<b>".$account_list[$acct_id]."@email.arizona.edu</b><br/>";
		}
		
		$current_employee = 1;
		foreach($_SESSION["formdata"]["employee_multi"] as $num=>$emp){
			if($count_employees > 1){
				$message .= '<br /><h2 style="margin-bottom:0;">Employee Information #'.$current_employee.'</h2>';
			}else{
				$message .= '<br /><h2 style="margin-bottom:0;">Employee Information</h2>';
			}
			if (isset($emp['new_catwork']) && $emp['new_catwork']!="") {
				$message .= 'Type: <b>New Catwork Account Request</b><br />';
			}
			else {
				$message .= 'Type: <b>Existing Exchange Account Request</b><br />';
			}
			$message .= 'First Name: <b>'.$emp['employee_first_name'].'</b><br />';
			$message .= 'Last Name: <b>'.$emp['employee_last_name'].'</b><br />';
			$message .= 'NetID: <b>'.$emp['employee_netid'].'</b><br />';
			
			$current_employee++;
		}
		
		if (isset($_POST['delete']) && $_POST['delete']!="") {
			$message .= '<br/><h3>Deletion: </h3><b>Remove Employee Access</b><br />';
		}
	
		$message .= '</body></html>';
		$email = mail_switch($to, $subject, $message, $headers);
	}
?>
<h1>Departmental/Catworks Email Account Request</h1><br />
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