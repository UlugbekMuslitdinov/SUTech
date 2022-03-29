<?php
//	include("./template/webauth/include.php");
//	require_once("./template/header.php");
//	require_once("./template/sidebar.php");
	// define('__ROOT__', dirname(dirname(__FILE__, $levels=2))); //Note $levels=2 tells dirname() to return parent directory path two levels up, not default one, because thsi index.php needs to escape /forms folder to /sucs which is  two levels away
    // include(__ROOT__.'/template/webauth/include.php');
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/webauth/include.php');
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/header.php');
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/sidebar.php');
	//require_once(__ROOT__.'/template/header.php');
//	require_once(__ROOT__.'/template/sidebar.php');	
?>
<h1>Key & Locker Request Form</h1>
<?php
if (isset($_SESSION["errors"])) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMISSION" />Please fill out all required fields.</div>';
}
?>
<style type="text/css">
	h2 {
		font-weight:bold;
	}
	#building_form {
		display: none;
	}
	#limitlbl_0 {
		float:right;
	}
	#employee_info_proto {
		display: none;
	}
	.employee_info_multi {
		width: 100%;
		border-style: solid;
		border-width: 1px;
		border-color: #000000;
		border-style: groove;
		border-radius: 3px;
		background-color:#CCCCCC;	
		margin-left: 0px;
		margin-top: 0px;
		padding: 3px;
		padding-bottom: 10px;
	}
	.employee_info_multi:last-child {
		border-bottom: 15;
	}
	.employee_info_multi input{
		width: 326px;
	}
	.field_text
	{
		width:330px;
	}
</style>
<script type="text/javascript">
	var employees = [];
	var employees_state = "single";
	var employee_single;
	var employee_fields = {
		"number"	: "employee_info_number",
		"first"		: "employee_first_name",
		"last"		: "employee_last_name",
		"catcard"	: "catcard",
		"unit"		: "employee_unit",
		"employee_id" : "employee_id",
		"phone" :  "employee_phone",
		"job" :  "employee_job",
		"net_id" : "net_id"
	};
	var em_fields = <?php if(!empty($_SESSION["formdata"]["employee_multi"])){echo json_encode($_SESSION["formdata"]["employee_multi"]);}else{echo "{}";} ?>;
	var em_errors = <?php if(!empty($_SESSION["errors"]["employee_multi"])){echo json_encode($_SESSION["errors"]["employee_multi"]);}else{echo "{}";} ?>;

	function validateAndSubmitNew(){
		var errors = [];
		// for(var i in employees){
		// 	if(employees[i].catcard.value != "" && employees[i].pin.value != ""){
		// 		pin = employees[i].pin.value;
		// 		catcard = employees[i].catcard.value;
		// 		if(pin == catcard.substring(catcard.length - 4)){
		// 			employees[i].pin.className+=" required_error";
		// 			errors.push("The user's pin can NOT be the last 4 digits of CatCard.");
		// 		}
		// 		if(!pin.match(/^[0-9]+$/)){
		// 			employees[i].pin.className+=" required_error";
		// 			errors.push("The 6-digit pin may only contain numbers.");
		// 		}
		// 		if ((pin.length < 4) && (pin.length > 6)) {
		// 			employees[i].pin.className+=" required_error";
		// 			errors.push("The pin must be 4 or 6-digits.");
		// 		}
		// 	}
		// }
		if(errors.length==0){
			document.getElementById('request_form_new').submit();
		}else{
			if(errors.length>1){
				if(errors.length>=10){
					var error = String(errors.length)+" form errors, including: \n";
				}else{
					var error = String(errors.length)+" form errors: \n";
				}
				for(var i = 0; i<errors.length && i<10; i++){
					error+=errors[i]+"\n";
				}
				alert(error);
			}else{
				alert(errors[0]);
			}
		}
	}

	function buildingForm(visibility) {
		document.getElementById("building_form").style.display = visibility;
	}
	function setForm(form) {
		if (form == "key") {
			buildingForm("block");
		}
		if (form == "locker") {
			buildingForm("none");
		}
	}

	function addEmployee(){
		var newIndex = employees.length;
		var newEmpl = {};
		newEmpl.container = document.getElementById("employee_info_proto").cloneNode(true);
		newEmpl.container.id="employee_info_"+newIndex.toString();
		for(field in employee_fields){
			newEmpl[field] = newEmpl.container.querySelector("#"+employee_fields[field]+"_proto");
			newEmpl[field].id = employee_fields[field]+"_"+newIndex.toString();
			newEmpl[field].name = newEmpl[field].id;
			if(newIndex in em_errors){
				if(employee_fields[field] in em_errors[newIndex]){
					newEmpl[field].className += " required_error";
				}
				newEmpl[field].value = em_fields[newIndex][employee_fields[field]];
			}
		}
		newEmpl.remove = newEmpl.container.querySelector(".action-remove");
		newEmpl.remove.onclick = function(){removeEmployee(newIndex);};
		if(newIndex>0){
			employees[0].remove.parentNode.style.display="";
		}else{
			newEmpl.remove.parentNode.style.display="none";
		}
		document.getElementById("employee_info_container").appendChild(newEmpl.container);
		employees.push(newEmpl);
	}
	function removeEmployee(index){
		if(employees.length>1){
			if(index == employees.length-1){
				employees[index].container.parentNode.removeChild(employees[index].container);
				employees.length--;
			}else if(index < employees.length-1){
				for(var i = index; i < employees.length-1; i++){
					for(var field in employee_fields){
						employees[i][field].value = employees[i+1][field].value;
					}
				}
				employees[employees.length-1].container.parentNode.removeChild(employees[employees.length-1].container);
				employees.length--;
			}else{
				console.warn("tried to remove nonexsistant employee record: "+index.toString());
			}
			if(employees.length == 1){
				employees[0].remove.parentNode.style.display="none";
			}
		}else{
			console.warn("tried to remove all employee records");
		}
	}
	function initEmployees(){
		if(em_errors.length){
			for(var i in em_errors){
				addEmployee();
			}
		}else{
			addEmployee();
		}
	}
</script>
<form id="request_form_new" name="request_form_new" method="post" action="./submit_new.php">
<div id="employee_info_proto" class="employee_info_multi">
	<input type="hidden" id="employee_info_number_proto" value="1" />
	<div class="form_field form_left_field">
		<div class="field_title">
			First Name:
			<span class="field_required">*</span>
		</div>
		<div>
			<input id="employee_first_name_proto" type="text" maxlength="45" class="field_text" value="<?php if (isset($_SESSION["formdata"]["employee_first_name"])) {echo $_SESSION["formdata"]["employee_first_name"];} ?>" />
		</div>
	</div>
	<div class="form_field">
		<div class="field_title">
			Last Name:
			<span class="field_required">*</span>
		</div>
		<div>
			<input id="employee_last_name_proto" type="text" maxlength="45" class="field_text" value="<?php if (isset($_SESSION["formdata"]["employee_last_name"])) {echo $_SESSION["formdata"]["employee_last_name"];} ?>" />
		</div>
	</div>
	<div class="form_field form_left_field">
		<div class="field_title">
			Catcard #: 
			<span class="field_required">*</span>
		</div>
		<div>
			<input id="catcard_proto" type="text" maxlength="20" class="field_text" value="<?php if (isset($_SESSION["formdata"]["catcard"])) {echo $_SESSION["formdata"]["catcard"];} ?>" />
		</div>
	</div>
	<div class="form_field">
		<div class="field_title">
			NetID (Portion of email before the @):
							<span class="field_spacing"></span>
		</div>
		<div>
			<input id="net_id_proto" type="text" class="field_text" maxlength="45" class="field_text" />
		</div>
	</div>
	<div class="form_field form_left_field">
		<div class="field_title">
			Unit/Department:
			<span class="field_required">*</span>
		</div>
		<div>
			<input id="employee_unit_proto" type="text" maxlength="60" class="field_text" value="<?php if (isset($_SESSION["formdata"]["employee_unit"])) {echo $_SESSION["formdata"]["employee_unit"];} ?>" />
		</div>
	</div>
	<div class="form_field">
		<div class="field_title">
			Employee ID:
			<span class="field_required">*</span>
		</div>
		<div>
			<input id="employee_id_proto" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_id"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_id"])) {echo $_SESSION["formdata"]["employee_id"];} ?>" />
		</div>
	</div>
	<div class="form_field">
		<div class="field_title">
			Phone #:
			<span class="field_required">*</span>
		</div>
		<div>
			<input id="employee_phone_proto" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_phone"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_phone"])) {echo $_SESSION["formdata"]["employee_phone"];} ?>" />
		</div>
	</div>
	<div class="form_field" style="margin-left: 20px;">
		<div class="field_title">
			Job Title:
			<span class="field_required">*</span>
		</div>
		<div>
			<input id="employee_job_proto" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_job"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_job"])) {echo $_SESSION["formdata"]["employee_job"];} ?>" />
		</div>
	</div>
	<!-- <div class="form_field form_left_field">
		<div class="field_title">
			Alarm Access Password:
			<span style="font-weight: normal;"><i>(if applicable)</i></span>
			<span class="field_spacing"></span>
		</div>
		<div>
			<input id="alarm_password_proto" type="password" maxlength="20" class="field_text" value="" />
		</div>
	</div>
	<div class="form_field">
		<div class="field_title">
			Alarm or Stadium or 85 North Pin:
			<span class="field_spacing">
				<span style="font-weight: normal; color: black;"><i>(Cannot be last 4 digits of CatCard)</i></span>
			</span>
		</div>
		<div>
			<input id="pin_proto" type="password" maxlength="10" class="field_text" value="<?php if (isset($_SESSION["formdata"]["pin"])) {echo $_SESSION["formdata"]["pin"];} ?>" />
		</div>
	</div> -->
	<div class="form_field" style="margin-left: 20px; padding-top: 22px;">
		<div class="field_title">
			<div class="action-remove"><button type="button" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Remove Employee</button></div>
		</div>
	</div>
	<div style="clear:both;"></div>
</div>
<div id="employee_stash" style="display: none;">
</div>


<div id="form_wrapper">
	<div id="form_container">
			<div style="margin-bottom:20px;margin-top:20px;border-top: 1px #999 solid;padding-top:20px;">
				<h2>Supervisor Information</h2>
				<div class="form_field form_left_field" style="margin-top:0;">
					<div class="field_title">
						Requesting Supervisor:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="supervisor_name" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["supervisor_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_name"]) && $_SESSION["formdata"]["supervisor_name"]!="") {echo $_SESSION["formdata"]["supervisor_name"];} ?>" />
					</div>
				</div>
				<div class="form_field" style="margin-top: 0;">
					<div class="field_title">
						Phone#:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="supervisor_phone" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["supervisor_phone"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_phone"])) {echo $_SESSION["formdata"]["supervisor_phone"];} ?>" />
					</div>
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Supervisor's E-mail:
					</div>
					<div>
						<input name="supervisor_email" type="text" class="field_text <?php if ($_SESSION["errors"]["supervisor_email"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_email"])&&$_SESSION["formdata"]["supervisor_email"]!="") {echo $_SESSION["formdata"]["supervisor_email"];} else {echo $_SESSION['webauth']['netID']."@email.arizona.edu";} ?>" />
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-bottom:20px;padding-top: 5px; border-top: 1px solid black;">
				<h2>Employee Information</h2>
				<div id="employee_info_container">
				</div>
				<div id="action-add" style="width: 100%; border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 15px; margin-top: 5px">
					<button type="button" onclick="addEmployee()" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Add Employee</button>
				</div>
			</div>
			<div id="form_select" style="font-weight: bold;">
				<h2>Form Type</h2>
				<label><input onclick="setForm('key');" type="radio" id="key_req" name="form_type" value="Key Request" />Key Request</label><br />
				<label><input onclick="setForm('locker');" type="radio" id="locker_req" name="form_type" value="Locker Request" />Locker Request - SUMC Only</label><br />
			</div>

			<div id="building_form" style="margin-bottom:20px;">
				<div style="margin-bottom:20px;margin-top:20px;border-top: 1px #999 solid;padding-top:20px;">
				<h2>Building Information</h2>
				<div class="form_field form_left_field" style="margin-top:0;">
					<div class="field_title">
						Building #:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="building_number" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["building_number"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["building_number"]) && $_SESSION["formdata"]["building_number"]!="") {echo $_SESSION["formdata"]["building_number"];} ?>" />
					</div>
				</div>
				<div class="form_field" style="margin-top: 0;">
					<div class="field_title">
						Room #:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="room_number" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["room_number"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["room_number"])) {echo $_SESSION["formdata"]["room_number"];} ?>" />
					</div>
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Notes:
					</div>
					<div>
						<!-- <input name="notes" type="textarea" /> -->
						<textarea name=notes></textarea>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			</div>


			<div style="clear:both;">
				<input type="submit" name="cancel" value="Cancel" />&nbsp;&nbsp;&nbsp;
				<input type="button" name="validate" value="Submit" onclick="validateAndSubmitNew();" />
			</div>
		</form>

		
	</div>
</div>

<script type="text/javascript" language="javascript" src="../charcount.js"></script>
<script>
	parseCharCounts();
	initEmployees();
	<?php
	// if ($_SESSION["formdata"]["form_type"] == "New Access") {
	// 	echo 'setForm("new");';
	// 	echo 'document.getElementById("new_access").checked = true;';
	// 	echo 'document.getElementById("update_access").disabled = true;';
	// 	echo 'document.getElementById("remove_access").disabled = true;';
	// }
	// else if ($_SESSION["formdata"]["form_type"] == "Update Access") {
	// 	echo 'setForm("update");';
	// 	echo 'document.getElementById("update_access").checked = true;';
	// 	echo 'document.getElementById("new_access").disabled = true;';
	// 	echo 'document.getElementById("remove_access").disabled = true;';
	// }
	// else if ($_SESSION["formdata"]["form_type"] == "Remove Access") {
	// 	echo 'setForm("delete");';
	// 	echo 'document.getElementById("remove_access").checked = true;';
	// 	echo 'document.getElementById("new_access").disabled = true;';
	// 	echo 'document.getElementById("update_access").disabled = true;';
	// }
	?>
</script>
<?php
unset($_SESSION["errors"]);
unset($_SESSION["formdata"]);
//require_once("./template/footer.php");
require_once(__ROOT__.'/template/footer.php');
?>
