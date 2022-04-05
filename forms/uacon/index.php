<?php
//    include("webauth/include.php");
//    require_once("header.php");
//    require_once("sidebar.php");
//    require_once("mysql/include.php");
//	define('__ROOT__', dirname(dirname(__FILE__, $levels=2))); //Note $levels=2 tells dirname() to return parent directory path two levels up, not default one, because thsi index.php needs to escape /forms folder to /sucs which is  two levels away
    	include($_SERVER["DOCUMENT_ROOT"].'/template/webauth/include.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/header.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/sidebar.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/mysql/include.php');
    
    
    $conn = select_db("sucs");
?>
<h1>Departmental/Catworks Email Account Request</h1>
<?php
if (isset($_SESSION["errors"])) {
    echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMISSION" />Please fill out all required fields.</div>';
}
?>
<style type="text/css">
h2 {
    font-weight:bold;
}
#limitlbl_0 {
    float:right;
}
#form_container {
	float:none !important;
}
.access_option {
    margin-top:4px;
}
.foodpro_location_section, .register_pin_section, .other_section {
	display: none;
}
#employee_info_proto {
	display: none;
}
.employee_info_multi {
	width: 695px;
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
	margin-bottom: 9px;
}
.employee_info_multi .form_field{
	width: 335px;
}
.field_text
{
	width:330px;
}
.info-text {
	color:#666;
}
.access_option {
	display: inline-block;
	min-width: 160px;
	margin-right: 10px;
	margin-top: 2px;
}
.access_option:nth-child(3n+1){
	margin-right:50px;
}
.access_option input {
	margin: 0px 1px;
	vertical-align: top;
}
.required_error{
	border: none !important;
	outline: 1px solid #c00 !important;
}
</style>
<script type="text/javascript">
function departmentForm(visibility) {
    document.getElementById("new_account").style.display = visibility;
}
function accessForm(visibility) {
    document.getElementById("update_access").style.display = visibility;
}
function setForm(form) {
    if (form == "departmental") {
        departmentForm("block");
        accessForm("none");
    }
    if (form == "access") {
        departmentForm("none");
        accessForm("block");
    }
}

var employees = [];
var employee_fields = {
	"number"	: "employee_info_number",
	"new"		: "new_catwork",
	"first"		: "employee_first_name",
	"last"		: "employee_last_name",
	"netid"		: "employee_netid"
};
var em_fields = <?php if(!empty($_SESSION["formdata"]["employee_multi"])){echo json_encode($_SESSION["formdata"]["employee_multi"]);}else{echo "{}";} ?>;
var em_errors = <?php if(!empty($_SESSION["errors"]["employee_multi"])){  echo json_encode($_SESSION["errors"]["employee_multi"]);}else{  echo "{}";} ?>;

function DomEach(){
	if(arguments[0] instanceof Node){
		var node = arguments[0];
		var selector = arguments[1];
		var handler = arguments[2];
	}else{
		var node = document;
		var selector = arguments[0];
		var handler = arguments[1];
	}
	var elements = node.querySelectorAll(selector);
	for(var i=0; i<elements.length; i++){
		handler(elements[i]);
	}
}

function addEmployee(){
	var newIndex = employees.length;
	var newEmpl = {"shGroups":{}};
	newEmpl.container = document.getElementById("employee_info_proto").cloneNode(true);
	newEmpl.container.id="employee_info_"+newIndex.toString();
	for(field in employee_fields){
		newEmpl[field] = newEmpl.container.querySelector("#"+employee_fields[field]+"_proto");
		if(newEmpl[field].className.indexOf("proto-radioset") !== -1){
			newEmpl[field].id = employee_fields[field]+"_"+newIndex.toString();
			newEmpl[field].radioset = [];
			var targetValue = "";
			if(newIndex in em_errors){
				if(employee_fields[field] in em_errors[newIndex]){
					newEmpl[field].className += " required_error";
				}
				targetValue = em_fields[newIndex][employee_fields[field]];
			}
			DomEach(newEmpl[field], "input", function(e){
				e.name = newEmpl[field].id;
				newEmpl[field].radioset[e.value] = e;
				if(e.value == targetValue){
					e.checked = true;
				}
			});
		}else if(newEmpl[field].className.indexOf("proto-checkset") !== -1){
			newEmpl[field].id = employee_fields[field]+"_"+newIndex.toString();
			newEmpl[field].checkset = [];
			if(newIndex in em_errors){
				if(employee_fields[field] in em_errors[newIndex]){
					newEmpl[field].className += " required_error";
				}
			}
			DomEach(newEmpl[field], "input", function(e){
				e.name = newEmpl[field].id+"[]";
				newEmpl[field].checkset[e.value] = e;
				if(newIndex in em_errors && !em_errors[newIndex][employee_fields[field]]){
					if(typeof em_fields[newIndex][employee_fields[field]][e.value] != "undefined"){
						e.checked = true;
					}
				}
				if(e.parentNode.className.indexOf("showhide-trigger-") !== -1){
					p = e.parentNode;
					var shGroup = /(?:^|\s)showhide-trigger-(.*?)(?:\s|$)/.exec(p.className)[1];
					if(shGroup in newEmpl.shGroups){
						newEmpl.shGroups[shGroup].triggers.push(e);
						p.onclick=newEmpl.shGroups[shGroup].trigger;
					}else{
						newEmpl.shGroups[shGroup] = {};
						var shg = newEmpl.shGroups[shGroup];
						shg.triggers = [e];
						shg.trigger = function(){
							var checked = false;
							for(var i in shg.triggers){
								if(shg.triggers[i].checked){
									checked = true;
								}
							}
							if(newIndex==0){
								if(checked){
									DomEach(newEmpl.container, ".showhide-target-"+shGroup, function(e){
										var i = e.querySelector("input");
										i.disabled=false;
									});
								}else{
									DomEach(newEmpl.container, ".showhide-target-"+shGroup, function(e){
										var i = e.querySelector("input");
										i.disabled=true;
									});
								}
							}else{
								if(checked){
									DomEach(newEmpl.container, ".showhide-target-"+shGroup, function(e){
										e.style.display="block";
									});
								}else{
									DomEach(newEmpl.container, ".showhide-target-"+shGroup, function(e){
										e.style.display="none";
									});
								}
							}
						}
						if(newIndex==0){
							DomEach(newEmpl.container, ".showhide-target-"+shGroup, function(e){
								e.style.display="block";
							});
						}
						p.onclick = shg.trigger;
					}
				}
			});
		}else{
			newEmpl[field].id = employee_fields[field]+"_"+newIndex.toString();
			newEmpl[field].name = newEmpl[field].id;
			if(newIndex in em_errors){
				if(employee_fields[field] in em_errors[newIndex]){
					newEmpl[field].className += " required_error";
				}
				if(typeof em_fields[newIndex][employee_fields[field]] !== "boolean")
					newEmpl[field].value = em_fields[newIndex][employee_fields[field]];
			}
		}
	}
	for(var shGroup in newEmpl.shGroups){
		newEmpl.shGroups[shGroup].trigger();
	}
	newEmpl.remove = newEmpl.container.querySelector(".action-remove");
	newEmpl.remove.onclick = function(){removeEmployee(newIndex);};
	if(newIndex>0){
		DomEach(newEmpl.container, ".info-text", function(e){
			e.style.display="none";
		});
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
					if(employees[i][field].className.indexOf("proto-radioset") !== -1){
						var targetValue = "";
						DomEach(employees[i+1][field], "input", function(e){
							if(e.checked){
								targetValue = e.value;
							}
						});
						DomEach(employees[i][field], "input", function(e){
							if(e.value == targetValue){
								e.checked = true;
							}
						});
					}else if(employees[i][field].className.indexOf("proto-checkset") !== -1){
						var checks = [];
						DomEach(employees[i+1][field], "input", function(e){
							if(e.checked){
								checks.push(e.value);
							}
						});
						DomEach(employees[i][field], "input", function(e){
							if(checks.indexOf(e.value)!==-1){
								e.checked = true;
							}else{
								e.checked = false;
							}
						});
					}else if(employees[i][field].className.indexOf("proto-checkbox") !== -1){
						employees[i][field].checked = employees[i+1][field].checked;
					}else{
						employees[i][field].value = employees[i+1][field].value;
					}
				}
				for(var shGroup in employees[i].shGroups){
					employees[i].shGroups[shGroup].trigger();
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

function validateAndSubmit(){
	var errors = [];
	for(var i in employees){
		if(employees[i].netid.value != ""){
			if(employees[i].netid.value.indexOf("@") != -1){
				employees[i].netid.className+=" required_error";
				errors.push("Employee #"+String(i*1+1)+": The user's NetID, not their full email address.\n\n    (NETID@email.arizona.edu)");
			}else if(employees[i].netid.value.indexOf("arizona.edu") != -1){
				employees[i].netid.className+=" required_error";
				errors.push("Employee #"+String(i*1+1)+": The user's NetID, not their full email address.\n\n    (NETID@email.arizona.edu)");
			}
			if(/^\d+$/.test(employees[i].netid.value)){
				employees[i].netid.className+=" required_error";
				errors.push("Employee #"+String(i*1+1)+": The user's NetID is NOT A NUMBER such as the EmployeeID");
			}
			/*if(!(employees[i].netid.value.match(/^[A-Za-z]+$/))){
				employees[i].netid.className+=" required_error";
				errors.push("Employee #"+String(i*1+1)+": The user's NetID should NOT contain any spaces or special characters).");
			}*/
		}
	}
	if(errors.length==0){
		document.getElementById('update_access').submit();
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
</script>
<div id="employee_info_proto" class="employee_info_multi">
	<input type="hidden" id="employee_info_number_proto" value="1" />
	<div style="width: 723px;">
		<div style=" color: #000; font-weight: bold;margin-top:5px;">
			<label>
				<input id="new_catwork_proto" class="proto-checkbox" value="1" type="checkbox" />
				Check here if student employee so a catworks account can be created.
			</label>
		</div>
		<div class="form_field  form_left_field">
			<div class="field_title">
				First Name:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_first_name_proto" type="text" maxlength="45" class="field_text" />
			</div>
		</div>
		<div class="form_field">
			<div class="field_title">
				Last Name:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_last_name_proto" type="text" maxlength="45" class="field_text" />
			</div>
		</div>
		<div class="form_field form_left_field">
			<div class="field_title">
				NetID:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_netid_proto" type="text" maxlength="60" class="field_text" />
			</div>
			<span class="info-text" style="color:#333;">
				(This is <b>NOT a number</b> such as the EmployeeID)
			</span>
		</div>
		<div style="float: left; margin-top: 23.5px;">
			<button type="button" class="action-remove" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Remove Employee</button>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
<div id="form_wrapper">
<div id="form_container">
	<div id="form_select" style="font-weight: bold;">
		<h2>Form Type</h2>
		<label>
			<input onclick="setForm('access');" type="radio" id="access_request" name="form_type"/> Add/Remove Access to Departmental Account<br />
				<div style="margin-left: 40px;font-weight:normal;font-style:italic;color:#333;">
					This form is for requesting a change in a users access to a
					given departmental account. You need to fill out a request for
					each user. This is also where you request the the creation of a
					student "catworks" account on UAConnect.
				</div>
		</label>
		<label>
			<input onclick="setForm('departmental');" type="radio" id="departmental_request" name="form_type"/> Request New Departmental Account<br />
				<div style="margin-left: 40px;font-weight:normal;font-style:italic;color:#333;">
					This form is for requesting the creation of new departmental
					accounts. The requesting supervisor will automatically be given
					access.
				</div>
		</label>
	</div>
	<form id="update_access" style="display:none;" method="post" action="./submit_access.php">
		<input type="hidden" name="form_type" value="Access" />
		<div style="margin-bottom:5px;margin-top:20px;border-top: 1px #999 solid;padding-top:20px;">
			<h2>Supervisor Information</h2>
			<div style="width: 341px; margin-right: 20px; float: left;">
				<div class="form_field form_left_field">
					<div class="field_title">
						Requesting Supervisor:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="supervisor_name" id="supervisor_name" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["supervisor_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_name"]) && $_SESSION["formdata"]["supervisor_name"]!="") {echo $_SESSION["formdata"]["supervisor_name"];} ?>" />
					</div>
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Phone#:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="supervisor_phone" id="supervisor_phone" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["supervisor_phone"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_phone"])) {echo $_SESSION["formdata"]["supervisor_phone"];} ?>" />
					</div>
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Supervisor's E-mail:
					</div>
					<div>
						<input name="supervisor_email" id="supervisor_email" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["supervisor_email"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_email"])&&$_SESSION["formdata"]["supervisor_email"]!="") {echo $_SESSION["formdata"]["supervisor_email"];} else {echo $_SESSION['webauth']['netID']."@email.arizona.edu";} ?>" />
					</div>
				</div>
			</div>
			<div class="form_field">
				<div class="field_title">
					Departmental Email Account Name:
					<span class="field_required">*</span>
				</div>
				<div>
					<select name="account_name[]" multiple size="7" style="width:100%;" class="<? if ($_SESSION["errors"]["account_name"]) {echo "required_error";} ?>">
						<?php
							$result = $conn->query('SELECT * FROM exch_departments ORDER BY name')
								or die($conn->error());
							while ($cur = $result->fetch_assoc()) {
								if(is_array($_SESSION["formdata"]["account_name"]) && in_array($cur["department_id"], $_SESSION["formdata"]["account_name"])){
									echo '<option selected value="'.$cur["department_id"].'">'.$cur["name"].'@email.arizona.edu</option>';
								}else{
									echo '<option value="'.$cur["department_id"].'">'.$cur["name"].'@email.arizona.edu</option>';
								}
							}
						?>
					</select>
					(Select multiple accounts using the ctrl key and click.)
				</div>
			</div>
			<div style="clear:both;"></div>
	
			<div style="margin-bottom:5px;margin-top:10px;border-top: 1px #999 solid;padding-top:20px;width:100%;">
				<h2>Add Employee</h2>
				<div style="width: 676px;margin-left: 10px;margin-top: 5px;margin-bottom: 10px;font-weight: normal;font-style: italic;color: #333;">
						Student employees who need access to UAConnect Email/Calendaring
						accounts need to request a netid@catworks.arizona.edu email
						address be created which will use their NetID password.
						They will still have their netid@email.arizona.edu email
						account in CatMail, and it is recommended that they keep
						the two accounts distinct and separate, as the UAConnect
						accounts will be deleted after termination of employment.
				</div>
				<div id="employee_info_container">
				</div>
				<div id="action-add" style="width: 100%; padding-bottom: 5px;">
					<button type="button" onclick="addEmployee()" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Add Employee</button>
				</div>
			</div>
		</div>
	
		<div style="margin-top:5px;padding-top:20px;border-top: 1px #999 solid;width:703px;overflow:auto;">
			<h2>Remove Above Employees' Access</h2>
			<div style="margin-left:10px;float:left;">
				<div class="fieldset_left">
					<b>Confirm Remove Employee Access?:</b><br />
					<input name="delete" value="Yes" type="checkbox" <?php if ($_SESSION["formdata"]["delete"]=="Yes") {echo "checked";} ?> /> Yes
				</div>
			</div>
		</div>
	
		<div style="margin-top: 10px;clear:both;">
			<input type="submit" name="cancel" value="Cancel" />&nbsp;&nbsp;&nbsp;
			<input type="button" name="validate" value="Submit" onclick="validateAndSubmit();" />
		</div>
	</form>
	<form id="new_account" style="display:none;" method="post" action="./submit_departmental.php">
		<input type="hidden" name="form_type" value="Departmental" />
		<div style="margin-bottom:10px;margin-top:20px;border-top: 1px #999 solid;padding-top:20px;">
			<h2>Supervisor Information</h2>
			<div class="form_field form_left_field" style="margin-top:0;">
				<div class="field_title">
					Requesting Supervisor:
					<span class="field_required">*</span>
				</div>
				<div>
					<input name="supervisor_name" id="supervisor_name" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["supervisor_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_name"]) && $_SESSION["formdata"]["supervisor_name"]!="") {echo $_SESSION["formdata"]["supervisor_name"];} ?>" />
				</div>
			</div>
			<div class="form_field" style="margin-top: 0;">
				<div class="field_title">
					Phone#:
					<span class="field_required">*</span>
				</div>
				<div>
					<input name="supervisor_phone" id="supervisor_phone" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["supervisor_phone"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_phone"])) {echo $_SESSION["formdata"]["supervisor_phone"];} ?>" />
				</div>
			</div>
			<div class="form_field form_left_field">
				<div class="field_title">
					Supervisor's E-mail:
				</div>
				<div>
					<input name="supervisor_email" id="supervisor_email" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["supervisor_email"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_email"])&&$_SESSION["formdata"]["supervisor_email"]!="") {echo $_SESSION["formdata"]["supervisor_email"];} else {echo $_SESSION['webauth']['netID']."@email.arizona.edu";} ?>" />
				</div>
			</div>
			<div class="form_field">
				<div class="field_title">
					Department/Unit:
					<span class="field_required">*</span>
				</div>
				<div>
					<input name="department" id="department" type="text" maxlength="60" class="field_text <?php if ($_SESSION["errors"]["department"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["department"])) {echo $_SESSION["formdata"]["department"];} ?>" />
				</div>
			</div>
			<div style="clear:both;"></div>
			
			<div style="margin-bottom:10px;margin-top:20px;border-top: 1px #999 solid;padding-top:20px;width:100%;overflow:auto;">
				<h2>New Account Information</h2>
				<div style="margin-left: 20px;margin-top:15px;color:#333;font-style: italic;">
					All departmental accounts will need to begin with the prefix
					"SU-". For example the Tech department would request an account
					with the name SU-Tech@email.arizona.edu
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Preferred Account Name:
						<span class="field_required">*</span>
					</div>
					<div>
						<input name="name_1" id="name_1" type="text" maxlength="60" class="field_text <?php if ($_SESSION["errors"]["name_1"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["name_1"]) && $_SESSION["formdata"]["name_1"]!="") {echo $_SESSION["formdata"]["name_1"];} ?>" />
					</div>
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Alternative Account Name #1:
					</div>
					<div>
						<input name="name_2" id="name_2" type="text" maxlength="60" class="field_text <?php if ($_SESSION["errors"]["name_2"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["name_2"]) && $_SESSION["formdata"]["name_2"]!="") {echo $_SESSION["formdata"]["name_2"];} ?>" />
					</div>
				</div>
				<div class="form_field form_left_field">
					<div class="field_title">
						Alternative Account Name #2:
					</div>
					<div>
						<input name="name_3" id="name_3" type="text" maxlength="60" class="field_text <?php if ($_SESSION["errors"]["name_3"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["name_3"]) && $_SESSION["formdata"]["name_3"]!="") {echo $_SESSION["formdata"]["name_3"];} ?>" />
					</div>
				</div>
				<div style="clear:both;"></div>
				<div class="form_field" style="width: 696px;">
					<div class="field_title">
						Description of account and what it will be used for:
						<span class="field_required">*</span>
					</div>
					<textarea name="description" id="description_text" maxlength="255" lengthcut="true" rows="5" style="width: 100%; resize: none;" class="<?php if ($_SESSION["errors"]["description"]) {echo "required_error";} ?>"><?php if (isset($_SESSION["formdata"]["description"]) && $_SESSION["formdata"]["description"]!="") {echo $_SESSION["formdata"]["description"];} ?></textarea>
				</div>
			</div>
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
<?php
if ($_SESSION["formdata"]["form_type"] == "Departmental") {
    echo 'setForm("departmental");';
    echo 'document.getElementById("departmental_request").checked = true;';
    echo 'document.getElementById("access_request").disabled = true;';
}
else if ($_SESSION["formdata"]["form_type"] == "Access") {
    echo 'setForm("access");';
    echo 'document.getElementById("access_request").checked = true;';
    echo 'document.getElementById("departmental_request").disabled = true;';
}
?>
</script>
<script>
initEmployees();
</script>
<?php
	unset($_SESSION["errors"]);
	unset($_SESSION["formdata"]);
    //require_once("footer.php");
    require_once($_SERVER["DOCUMENT_ROOT"].'/template/footer.php');
?>