<?php
	include ($_SERVER['DOCUMENT_ROOT'] . '/template/webauth/include.php');
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/header.php');
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/sidebar.php');
?>
<h1>Building Access Request Form</h1>
<?php
if (isset($_SESSION["errors"])) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMISSION" />Please fill out all required fields.</div>';
}
?>
<style type="text/css">
	h2 {
		font-weight:bold;
	}
	#request_form_new, #request_form_update, #request_form_remove {
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
<!--We need this for the AJAX calls.--> 
<script type="text/javascript" src="./js/prototype.js"></script>
<script language="javascript">
// Slect alarm access.
function alarmAccess(num) {
	var url = 'alarm_access.php';
	var postData = "num=" + num;
	var placeholder = "alarm_placeholder";
	var myUpdater = new Ajax.Updater(placeholder,url,{method:'get',parameters:postData});
	}
</script>
<script type="text/javascript">
	var employees = [];
	var employees_state = "single";
	var employee_single;
	var employee_fields = {
		"number"	: "employee_info_number",
		"first"		: "employee_first_name",
		"last"		: "employee_last_name",
		"catcard"	: "catcard",
		"pin"		: "pin",
		"unit"		: "employee_unit",
		"employee_id" : "employee_id",
		// "alarm_password" : "alarm_password",
		"net_id" : "net_id"
	};
	var em_fields = <?php if(!empty($_SESSION["formdata"]["employee_multi"])){echo json_encode($_SESSION["formdata"]["employee_multi"]);}else{echo "{}";} ?>;
	var em_errors = <?php if(!empty($_SESSION["errors"]["employee_multi"])){echo json_encode($_SESSION["errors"]["employee_multi"]);}else{echo "{}";} ?>;

	function checkForm() {
		// Form validation to be added.
		return true;
	}
	
	function validateAndSubmitNew(){
		// document.getElementById('request_form_new').submit();
		var errors = [];
		for(var i in employees){
			if(employees[i].catcard.value != "" && employees[i].pin.value != ""){
				pin = employees[i].pin.value;
				catcard = employees[i].catcard.value;
				if(pin == catcard.substring(catcard.length - 4)){
					employees[i].pin.className+=" required_error";
					errors.push("The user's pin can NOT be the last 4 digits of CatCard.");
				}
				if(!pin.match(/^[0-9]+$/)){
					employees[i].pin.className+=" required_error";
					errors.push("The 6-digit pin may only contain numbers.");
				}
				if ((pin.length < 4) && (pin.length > 6)) {
					employees[i].pin.className+=" required_error";
					errors.push("The pin must be 4 or 6-digits.");
				}
			}
		}
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

	function newForm(visibility) {
		document.getElementById("request_form_new").style.display = visibility;
	}
	function updateForm(visibility) {
		document.getElementById("request_form_update").style.display = visibility;
	}
	function deleteForm(visibility) {
		document.getElementById("request_form_remove").style.display = visibility;
	}
	function setForm(form) {
		if (form == "new") {
			newForm("block");
			updateForm("none");
			deleteForm("none");
		}
		if (form == "update") {
			newForm("none");
			updateForm("block");
			deleteForm("none");
		}
		if (form == "delete") {
			newForm("none");
			updateForm("none");
			deleteForm("block");

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

<div id="employee_stash" style="display: none;">
</div>


<div id="form_wrapper">
	<div id="form_container">
		<div id="form_select" style="font-weight: bold;">
			<h2>Form Type</h2>
			<label><input onclick="setForm('new');" type="radio" id="new_access" name="form_type" value="New Access" /> New Access</label><br />
			<label><input onclick="setForm('update');" type="radio" id="update_access" name="form_type" value="Update Access" /> Replacement CatCard#/Other Changes/Problems</label><br />
			<label><input onclick="setForm('delete');" type="radio" id="remove_access" name="form_type" value="Remove Access" /> Delete Employee's Access</label>
		</div>
		
		<form id="request_form_new" name="request_form_new" method="post" action="./form_submit.php" onSubmit="return checkForm();">
		<!--Supervisor Information	-->		
			<input type="hidden" name="form_type" value="New Access" />
			<div style="margin-bottom:20px;margin-top:20px;border-top: 1px #999 solid;padding-top:20px;">
				<h2>Supervisor Information</h2>
				<div class="form_field form_left_field" style="margin-top:0;">
					<div class="field_title">
						Requesting Supervisor:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="supervisor_name" type="text" maxlength="80" class="field_text" value="" required />
					</div>
				</div>
				<div class="form_field" style="margin-top: 0;">
					<div class="field_title">
						Phone#:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="supervisor_phone" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["supervisor_phone"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_phone"])) {echo $_SESSION["formdata"]["supervisor_phone"];} ?>" required />
					</div>
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Supervisor's E-mail:
					</div>
					<div>
						<input name="supervisor_email" type="text" class="field_text" value="<?=$_SESSION['webauth']['netID']?>@arizona.edu" />
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			
			<!--Employee Information-->
			<!--<div id="employee_info_proto" class="employee_info_multi">
			<input type="hidden" id="employee_info_number_proto" value="1" />-->
			<div class="form_field form_left_field">
				<div class="field_title">
					First Name:
					<span class="field_required">*</span>
				</div>
				<div>
					<input name="first_name" id="employee_first_name_proto" type="text" maxlength="45" class="field_text" value="" required />
				</div>
			</div>
			<div class="form_field">
				<div class="field_title">
					Last Name:
					<span class="field_required">*</span>
				</div>
				<div>
					<input name="last_name" id="employee_last_name_proto" type="text" maxlength="45" class="field_text" value="" required/>
				</div>
			</div>
			<div class="form_field form_left_field">
				<div class="field_title">
					Catcard #: 
					<span class="field_required">*</span>
				</div>
				<div>
					<input id="catcard_proto" name="catcard" type="text" maxlength="20" class="field_text" value="<?php if (isset($_SESSION["formdata"]["catcard"])) {echo $_SESSION["formdata"]["catcard"];} ?>"  required />
				</div>
			</div>
			<div class="form_field">
				<div class="field_title">
					NetID (Portion of email before the @):
									<span class="field_spacing"></span>
				</div>
				<div>
					<input id="net_id_proto" name="netid" type="text" class="field_text" maxlength="45" class="field_text" />
				</div>
			</div>
			<div class="form_field form_left_field">
				<div class="field_title">
					Unit/Department:
					<span class="field_required">*</span>
				</div>
				<div>
					<input id="employee_unit_proto" name="unit" type="text" maxlength="60" class="field_text" value="<?php if (isset($_SESSION["formdata"]["employee_unit"])) {echo $_SESSION["formdata"]["employee_unit"];} ?>" required  />
				</div>
			</div>
			<div class="form_field">
				<div class="field_title">
					Employee ID:
					<span class="field_required">*</span>
				</div>
				<div>
					<input id="employee_id_proto" name="employee_id" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_id"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_id"])) {echo $_SESSION["formdata"]["employee_id"];} ?>" required />
				</div>
			</div>
			<div class="form_field form_left_field">
				<!--
				<div class="field_title">
					Alarm Access Password:
					<span style="font-weight: normal;"><i>(if applicable)</i></span>
					<span class="field_spacing"></span>
				</div>
				<div>
					<input id="alarm_password_proto" type="password" maxlength="20" class="field_text" value="" />
				</div>
				-->
			</div>
			<div class="form_field">
				<div class="field_title">
					85 North Pin:
					<span class="field_spacing">
						<span style="font-weight: normal; color: black;"><i>(Cannot be last 4 digits of CatCard)</i></span>
					</span>
				</div>
				<div>
					<input id="pin_proto" name="north85" type="password" maxlength="10" class="field_text" value="<?php if (isset($_SESSION["formdata"]["pin"])) {echo $_SESSION["formdata"]["pin"];} ?>" />
				</div>
			</div>
			
			<div class="form_field" style="margin-left: 20px; padding-top: 22px;">
				<!--<div class="field_title">
					<div class="action-remove"><button type="button" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Remove Employee</button></div>
				</div>-->
			</div>
			
			<div style="clear:both;"></div>
			<!--</div>-->

			<div style="margin-bottom:20px;padding-top: 5px; border-top: 1px solid black;">
				<h2>Employee Information</h2>
				<div id="employee_info_container">
				</div>
				
				<div id="action-add" style="width: 100%; border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 15px; margin-top: 5px">
					<!--Add/Remove Employee - Removed for now.-->
					<!--
					<button type="button" onclick="addEmployee()" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Add Employee</button>
					-->
				</div>
			</div>

			<div style="margin-bottom:20px;">
				<h2>New Employee Access</h2>
				<div style="margin:0px;">
					<div class="fieldset_left" style="margin-right:20px;">
						<h2 style="font-weight: normal;"> General Access</h2><br />
						<b><p class="<?php if ($_SESSION["errors"]["access"]) {echo "required_error";} ?>">Areas to Access: <span class="field_required">*</span></p></b><br />
						<input name="access[28]" value="Underground Doors by Linen Room" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Underground Doors by Linen Room"]) {echo "checked";} ?> />Underground Doors by Linen Room<br />
						<input name="access[1]" value="Locker Room" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Locker Room"]) {echo "checked";} ?> />Employee Locker Room Only<br />
						<input name="access[2]" value="Building Entrance" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Building Entrance"]) {echo "checked";} ?> />Building Entrance (includes locker room)<br />

						<input name="access[21]" value="Administrative Offices" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Administrative Offices"]) {echo "checked";} ?> />Administrative Offices<br />
						
						<input name="access[42]" value="Bear Down Gym Catering" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Bear Down Gym Catering"]) {echo "checked";} ?> />Bear Down Gym Catering<br />
						
						<input name="access[26]" value="Cactus Grill" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Cactus Grill"]) {echo "checked";} ?> />Cactus Grill<br />
						<input name="access[40]" value="Cash Room Customer Entrance" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Cash Room Customer Entrance"]) {echo "checked";} ?> />Cash Room Customer Entrance<br />
						<input name="access[31]" value="Catalyst" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Catalyst"]) {echo "checked";} ?> />Catalyst<br />
						<input name="access[6]" value="Catering" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Catering"]) {echo "checked";} ?> />Catering<br />
						<input name="access[32]" value="Catering Servery 3rd Floor" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Catering Servery 3rd Floor "]) {echo "checked";} ?> />Catering Servery 3rd Floor <br />
						
						<input name="access[7]" value="Dock" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Dock"]) {echo "checked";} ?> />Dock<br />
						<input name="access[8]" value="Einsteins" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Einsteins"]) {echo "checked";} ?> />Einstein's<br />
						
						<input name="access[25]" value="Employee Break Room" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Employee Break Room"]) {echo "checked";} ?> />Employee Break Room<br />
						
						<input name="access[33]" value="Esports Arena" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Esports Arena"]) {echo "checked";} ?> />Esports Arena<br />
						
						<input name="access[18]" value="Event Planning/Catering Rm 441 (24/7)" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["SAEM/AISS Marketing Staff"]) {echo "checked";} ?> />Event Planning/Catering Rm 441 (24/7) <br />
						<input name="access[19]" value="Event Planning/Catering Rm 441 (Limited)s" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["SAEM/AISS Marketing Students"]) {echo "checked";} ?> />Event Planning/Catering Rm 441 (Limited)<br />
						
						<input name="access[35]" value="Global Food Court 2nd floor" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Global Food Court 2nd floor"]) {echo "checked";} ?> />Global Food Court 2nd floor<br />
						
						<input name="access[36]" value="Global Loading Dock (includes 100E & W doors)" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Global Loading Dock (includes 100E & W doors)"]) {echo "checked";} ?> />Global Loading Dock (includes 100E & W doors)<br />
						
						<input name="access[10]" value="Global Market" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Global Market"]) {echo "checked";} ?> />Global Market<br />
						
						
						<input name="access[34]" value="Highland 24/7" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Highland 24/7"]) {echo "checked";} ?> />Highland 24/7<br />

						<input name="access[16]" value="Mealplan-CatCard Office 24-7" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Mealplan-CatCard Office"]) {echo "checked";} ?> />Mealplan Office (24/7)<br />
						<input name="access[17]" value="Mealplan-CatCard Office Students" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Mealplan-CatCard Office"]) {echo "checked";} ?> />Mealplan Office Students<br />

						<input name="access[11]" value="NRich" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["NRich"]) {echo "checked";} ?> />NRich<br />

						<input name="access[22]" value="Pangea/Scoop" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Pangea/Scoop"]) {echo "checked";} ?> />Pangea/Scoop<br />

						<input name="access[12]" value="Production/Dishroom" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Production/Dishroom"]) {echo "checked";} ?> />Production/Dishroom<br />

						<input name="access[41]" value="Red and Blue Market" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Red and Blue Market"]) {echo "checked";} ?> />Red and Blue Market<br />
						
						<input name="access[37]" value="Roof" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Roof"]) {echo "checked";} ?> />Roof<br />
						
						<input name="access[38]" value="Slot Canyon" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Slot Canyon"]) {echo "checked";} ?> />Slot Canyon<br />

						<input name="access[13]" value="Starbucks-BookStore 24-7" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Starbucks-BookStore-247"]) {echo "checked";} ?> />Starbucks-BookStore (24/7)<br />
						<input name="access[14]" value="Starbucks-BookStore Limited" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Starbucks-BookStore-Limited"]) {echo "checked";} ?> />Starbucks-BookStore (Limited)<br />
						<input name="access[15]" value="Starbucks-Library" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Starbucks-Library"]) {echo "checked";} ?> />Starbucks-Library<br />
						
						<input name="access[20]" value="Warehouse" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Warehouse"]) {echo "checked";} ?> />Warehouse<br />
						<input name="access[29]" value="85 North Limited" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["85 North Limited"]) {echo "checked";} ?> />85 North Limited (4 digit pin required)<br />
						<input name="access[39]" value="85 North 24/7" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["85 North 24/7"]) {echo "checked";} ?> />85 North 24/7 (4 digit pin required)<br />
						<label><input name="access[30]" value="Other" type="checkbox" <?php if ($_SESSION["formdata"]["access"]["Other"]) {echo "checked";} ?> onclick="showOtherInput()" />Other areas to access (Please Specify)</label><br />
						
						<span id="other-input-box" style="display:none; margin-left: 20px;"> 
						<p style="margin-bottom: 0px;">Other Areas to Access:</p>
						<input name="other_areas" id="other_areas" type="text" maxlength="120" class="field_text <?php if ($_SESSION["errors"]["other_areas"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["other_areas"])) {echo $_SESSION["formdata"]["other_areas"];} ?>" />
						</span>

						<script>
						function showOtherInput() {
							var other_check_box = document.getElementsByName("access[30]")[0];
							var other_input_wrap = document.getElementById("other-input-box");
							var other_input_box = document.getElementsByName("other_areas")[0];
							if (other_check_box.checked){
								other_input_wrap.style.display = "block";
							}
							else {
								other_input_box.value = "";
								other_input_wrap.style.display = "none";
							}
						}
						</script>
					</div>
					<div class="fieldset_right">
						<h2 style="font-weight: normal;">Alarm Access</h2><br />
						<b>Need Alarm Access?:</b><br />
						<input name="alarm_access" value="Yes" type="radio" onClick="alarmAccess(1)" /> Yes<br />
						<input name="alarm_access" value="No" type="radio" onClick="alarmAccess(0)" checked /> No<br /><br />
						<div id="alarm_placeholder"></div>
						<?php /*?><b>Alarm Access Password:</b>
							<span style="font-weight: normal;"><i>(if applicable)</i></span>
							<span class="field_spacing"></span><br />
							<input name="alarm_password" id="alarm_password" type="password" maxlength="20" class="field_text" value="<?php if (isset($_SESSION["formdata"]["alarm_password"])) {echo $_SESSION["formdata"]["alarm_password"];} ?>" /><br /><br />
						<b>Alarm Access Area:</b>
						<input name="alarm_area" id="alarm_area" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["alarm_area"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["alarm_area"])) {echo $_SESSION["formdata"]["alarm_area"];} ?>" /><?php */?>
						
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>

			<div style="clear:both;">
				<input type="submit" name="cancel" value="Cancel" />&nbsp;&nbsp;&nbsp;
				<input type="submit" name="validate" value="Submit" />
				<!--<input type="button" name="validate" value="Submit" onclick="validateAndSubmitNew();" />-->
			</div>
		</form>

		<form id="request_form_update" name="request_form_update" method="post" action="./submit_update.php">
			<input type="hidden" name="form_type" value="Update Access" />
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
				<div class="employee_info_multi">
					<div class="form_field form_left_field">
						<div class="field_title">
							First Name:
							<span class="field_required">*</span>
						</div>
						<div>
							<input name="employee_first_name" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_first_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_first_name"])) {echo $_SESSION["formdata"]["employee_first_name"];} ?>" />
						</div>
					</div>
					<div class="form_field">
						<div class="field_title">
							Last Name:
							<span class="field_required">*</span>
						</div>
						<div>
							<input name="employee_last_name" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_last_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_last_name"])) {echo $_SESSION["formdata"]["employee_last_name"];} ?>" />
						</div>
					</div>
					<div class="form_field form_left_field">
						<div class="field_title">
							Unit/Department:
							<span class="field_required">*</span>
						</div>
						<div>
							<input name="employee_unit" type="text" maxlength="60" class="field_text <?php if ($_SESSION["errors"]["employee_unit"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_unit"])) {echo $_SESSION["formdata"]["employee_unit"];} ?>" />
						</div>
					</div>
					<div class="form_field">
						<div class="field_title">
							Employee ID:
							<span class="field_required">*</span>
						</div>
						<div>
							<input name="employee_id" type="number" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_id"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_id"])) {echo $_SESSION["formdata"]["employee_id"];} ?>" /
							>
						</div>
					</div>
					<div class="form_field form_left_field">
						<div class="field_title">
							Alarm Access Password:
						</div>
						<div>
							<input name="alarm_password" type="password" maxlength="20" class="field_text" value="" />
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>

			<div style="margin-bottom:20px;">
				<h2>Replacement CatCard#/Other Changes/Problems</h2>
				<div>
					<div class="fieldset_left" style="margin-right: 20px">
						<b>Replacement Catcard#:</b>
						<input name="replacement_catcard" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["replacement_catcard"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["replacement_catcard"])) {echo $_SESSION["formdata"]["replacement_catcard"];} ?>" />
					</div>
					<div style="clear:both;"></div>
					<div class="fieldset_left">
						<b>Other (Specify):</b>
						<input name="replacement_other" type="text" maxlength="120" class="field_text <?php if ($_SESSION["errors"]["replacement_other"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["replacement_other"])) {echo $_SESSION["formdata"]["replacement_other"];} ?>" />
					</div>
					<div style="clear:both;"></div>
					<div class="fieldset_left" style="width:696px;">
						<b>Problems:</b>
						<textarea name="replacement_problem" maxlength="255" lengthcut="true" rows="5" style="width: 100%; resize: none;" class="<?php if ($_SESSION["errors"]["replacement_problem"]) {echo "required_error";} ?>"><?php if (isset($_SESSION["formdata"]["replacement_problem"]) && $_SESSION["formdata"]["replacement_problem"]!="") {echo $_SESSION["formdata"]["replacement_problem"];} ?></textarea>
						<div style="margin-left: 20px; margin-top: 5px; color: #333; font-style: italic;">
							Please provide a time and specific location of the door(s)
							which pertain to your problem.<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(e.g. North Food Court Door
							West of Einstein's or a room number)
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>

			<div style="clear:both;">
				<input type="submit" name="cancel" value="Cancel" />&nbsp;&nbsp;&nbsp;
				<input type="submit" />
			</div>
		</form>

		<form id="request_form_remove" name="request_form_remove" method="post" action="./submit_remove.php">
			<input type="hidden" name="form_type" value="Remove Access" />
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
				<div class="employee_info_multi">
					<div class="form_field form_left_field">
						<div class="field_title">
							First Name:
							<span class="field_required">*</span>
						</div>
						<div>
							<input name="employee_first_name" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_first_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_first_name"])) {echo $_SESSION["formdata"]["employee_first_name"];} ?>" />
						</div>
					</div>
					<div class="form_field">
						<div class="field_title">
							Last Name:
							<span class="field_required">*</span>
						</div>
						<div>
							<input name="employee_last_name" type="text" maxlength="45" class="field_text <?php if ($_SESSION["errors"]["employee_last_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_last_name"])) {echo $_SESSION["formdata"]["employee_last_name"];} ?>" />
						</div>
					</div>
					<div class="form_field form_left_field" id="catcard_info">
						<div class="field_title">
							<div style="float: left; margin-right: 3px;">Catcard #: <span class="field_required">*</span></div>
							<div class="field_required" style="float: left;" id="catcard_required">*</div>
							<div id="catcard_optional" style="float: left; font-weight: normal; font-style: italic;">(if known)</div>
						</div>
						<div>
							<input name="catcard" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["catcard"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["catcard"])) {echo $_SESSION["formdata"]["catcard"];} ?>" />
						</div>
					</div>
					<div class="form_field">
						<div class="field_title">
							Unit/Department:
							<span class="field_required">*</span>
						</div>
						<div>
							<input name="employee_unit" type="text" maxlength="60" class="field_text <?php if ($_SESSION["errors"]["employee_unit"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["employee_unit"])) {echo $_SESSION["formdata"]["employee_unit"];} ?>" />
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>

			<div style="margin-bottom:20px;">
				<h2>Delete Above Employee's Access</h2>
				<div style="margin:10px;">
					<div class="fieldset_left <?php if ($_SESSION["errors"]["delete"]) {echo "required_error";} ?>">
						<b>Confirm Remove Employee Access?:</b><br />
						<input name="delete" value="Yes" type="checkbox" <?php if ($_SESSION["formdata"]["delete"]=="Yes") {echo "checked";} ?> /> Yes
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>

			<div style="clear:both;">
				<input type="submit" name="cancel" value="Cancel" />&nbsp;&nbsp;&nbsp;
				<input type="submit" />
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" language="javascript" src="../charcount.js"></script>
<script>
	parseCharCounts();
	initEmployees();
	<?php
	if ($_SESSION["formdata"]["form_type"] == "New Access") {
		echo 'setForm("new");';
		echo 'document.getElementById("new_access").checked = true;';
		echo 'document.getElementById("update_access").disabled = true;';
		echo 'document.getElementById("remove_access").disabled = true;';
	}
	else if ($_SESSION["formdata"]["form_type"] == "Update Access") {
		echo 'setForm("update");';
		echo 'document.getElementById("update_access").checked = true;';
		echo 'document.getElementById("new_access").disabled = true;';
		echo 'document.getElementById("remove_access").disabled = true;';
	}
	else if ($_SESSION["formdata"]["form_type"] == "Remove Access") {
		echo 'setForm("delete");';
		echo 'document.getElementById("remove_access").checked = true;';
		echo 'document.getElementById("new_access").disabled = true;';
		echo 'document.getElementById("update_access").disabled = true;';
	}
	?>
</script>
<?php
unset($_SESSION["errors"]);
unset($_SESSION["formdata"]);
require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/footer.php');
?>
