<?php
//	include("webauth/include.php");
//	require_once("header.php");
//	require_once("sidebar.php");
//	define('__ROOT__', dirname(dirname(__FILE__, $levels=2))); //Note $levels=2 tells dirname() to return parent directory path two levels up, not default one, because thsi index.php needs to escape /forms folder to /sucs which is  two levels away
    	require_once($_SERVER["DOCUMENT_ROOT"].'/template/webauth/include.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/header.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/sidebar.php');
?>
<h1>Workstation Access Request Form</h1>
<?php
if (isset($_SESSION["errors"])) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMISSION" />Please fill out all required fields.</div>';
}
?>
<style type="text/css">
.form_field {
	margin-right: 20px;
}
h2 {
    font-weight:bold;
}
#limitlbl_0 {
    float:right;
}
#employee_info_proto {
	display: none;
}
.employee_info_multi {
	width: 703px;
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
.field_text
{
	width:330px;
}
.info-text {
	color:#666;
}
.access_option {
	display: block;
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
<script>
var employees = [];
var employee_fields = {
	"number"	: "employee_info_number",
	"type"		: "request_type",
	"position"	: "employee_position",
	"first"		: "employee_first_name",
	"last"		: "employee_last_name",
	"title"		: "employee_title",
	"email"		: "employee_email",
	"phone"		: "employee_phone",
	"unit"		: "employee_unit",
	"netid"		: "employee_netid",
	"ws_tag"	: "workstation_tag",
	"access"	: "access",
	"foodpro"	: "foodpro_location",
	"updates"	: "updates",
	"comments"	: "comments"
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
		DomEach(newEmpl[field], "input", function(e){
			if(["radio", "checkbox"].indexOf(e.type) !== -1 && e.parentNode.className.indexOf("showhide-trigger-") !== -1){
				p = e.parentNode;
				var shGroup = /(?:^|\s)showhide-trigger-(.*?)(?:\s|$)/.exec(p.className)[1];
				if(shGroup in newEmpl.shGroups){
					newEmpl.shGroups[shGroup].triggers.push(e);
					newEmpl.shGroups[shGroup].parents.push(p);
					p.onclick=newEmpl.shGroups[shGroup].trigger;
					newEmpl.shGroups[shGroup].trigger();
				}else{
					newEmpl.shGroups[shGroup] = {};
					var shg = newEmpl.shGroups[shGroup];
					shg.triggers = [e];
					shg.parents = [p];
					shg.trigger = function(){
						var checked = false;
						for(var i in shg.triggers){
							if(shg.parents[i].className.indexOf("showhide-triggeronly") !== -1 ? false : shg.triggers[i].checked){
								checked = true;
							}
						}
						if(newIndex==0){
							if(checked){
								DomEach(newEmpl.container, ".showhide-target-"+shGroup, function(e){
									DomEach(e, "input", function(i){
										i.disabled=false;
									});
								});
							}else{
								DomEach(newEmpl.container, ".showhide-target-"+shGroup, function(e){
									DomEach(e, "input", function(i){
										i.disabled=true;
									});
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
					p.onchange = shg.trigger;
					shg.trigger();
				}
			}
		});
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
		if (employees[i].access.checkset["FoodPro"].checked) {
			if (employees[i].foodpro.value=="") {
				employees[i].foodpro.className+=" required_error";
				errors.push("Employee #"+String(i*1+1)+": You must provide a FoodPro Location Number to request FoodPro access.");
			}
		}
		if (employees[i].type.radioset["Update Employee"].checked) {
			var updates_ok = false;
			DomEach(employees[i].updates, 'input', function(e){
				if(e.checked) updates_ok = true;
			});
			if(!updates_ok) errors.push("Employee #"+String(i*1+1)+": You must specify what is being updated about an updated employee.");
		}
	}
	if(errors.length==0){
		document.getElementById('request_form').submit();
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
		<div class="form_field proto-radioset" id="request_type_proto">
			<div class="field_title">
				Request Type:
				<span class="field_required">*</span>
			</div>
			<div>
				<label class="showhide-trigger-updates showhide-triggeronly">
					<input value="New Employee" type="radio" />
					New Employee
				</label><br/>
				<label class="showhide-trigger-updates">
					<input value="Update Employee" type="radio" />
					Updated Employee
				</label>
			</div>
		</div>
		<div class="form_field proto-radioset" id="employee_position_proto">
			<div class="field_title">
				Employee Position:
			</div>
			<div>
				<label>
					<input value="Staff" type="radio" />
					Staff
				</label><br />
				<label>
					<input value="Student" type="radio" />
					Student
				</label>
			</div>
		</div>
		<div class="form_field">
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
		<div class="form_field">
			<div class="field_title">
				Title/Job:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_title_proto" type="text" maxlength="80" class="field_text" />
			</div>
		</div>
		<div class="form_field">
			<div class="field_title">
				University of Arizona E-Mail:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_email_proto" type="text" maxlength="80" class="field_text" />
			</div>
		</div>
		<div class="form_field">
			<div class="field_title">
				Work Phone #:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_phone_proto" type="text" maxlength="20" class="field_text" />
			</div>
		</div>
		<div class="form_field">
			<div class="field_title">
				Department/Unit:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_unit_proto" type="text" maxlength="60" class="field_text" />
			</div>
		</div>
		<div class="form_field">
			<div class="field_title">
				Employee's NetID:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="employee_netid_proto" type="text" maxlength="60" class="field_text" />
			</div>
			<span class="info-text">
				(This is <b>NOT a number</b> such as the EmployeeID)
			</span>
		</div>
		<div class="form_field">
			<div class="field_title">
				Specific Workstation Name/Tag:
				<span class="field_required">*</span>
			</div>
			<div>
				<input id="workstation_tag_proto" size="140" maxlength="140" type="text" class="field_text" />
			</div>
			<span class="info-text">
				(Located on the NUC, on the back of the monitor.)
			</span>
		</div>
		<div class="form_field proto-checkset" style="width: 100%;" id="access_proto">
			<div class="field_title">
				Access to what?:
				<span class="field_required">*</span>
			</div>
			<label class="access_option">
				<input value="Workstation" type="checkbox" />
				Workstation
			</label>
			<label class="access_option showhide-trigger-foodpro">
				<input value="FoodPro" type="checkbox" />
				FoodPro
			</label>
		</div>
		<div class="form_field showhide-target-foodpro" >
			<div class="field_title">
				FoodPro Location #:
			</div>
			<div>
				<input id="foodpro_location_proto" type="text" maxlength="45" class="field_text" />
			</div>
		</div>
		<div class="form_field proto-checkset showhide-target-updates" style="width: 100%;" id="updates_proto">
			<div class="field_title">
				New Employee Updates: <span class="info-text" style="font-weight: normal">(What's being updated)</span>
			</div>
			<label class="access_option">
				<input value="Department" type="checkbox" />
				Department
			</label>
			<label class="access_option">
				<input value="Workstation Name/Tag" type="checkbox" />
				Workstation Name/Tag
			</label>
		</div>
		<div class="form_field" style="width: 691px;">
			<div class="field_title">
				Comments:
			</div>
			<div>
				<input id="comments_proto" type="text" maxlength="250" class="field_text" style="width: 100%;" />
			</div>
			<div class="info-text">The comments are <b>NOT</b> a place to request work be done such as installs. To submit a work request, please select cancel below and submit a help desk request <a href="https://su-netmgmt.catnet.arizona.edu/portal" target="_blank">here</a>.</div>
		</div>
	</div>
	<div style="float: left; width: 100%; padding-top: 10px;">
		<button type="button" class="action-remove" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Remove Employee</button>
	</div>
	<div style="clear:both;"></div>
</div>
<form id="request_form" name="request_form" method="post" action="submit.php">
<div id="form_wrapper">
<div id="form_container">
	<div id="supervisor_info" style="margin-bottom:20px;">
		<h2>Supervisor Information</h2>
		<div class="form_field form_left_field" style="margin-top:0;">
			<div class="field_title">
				Requesting Supervisor:
				<span class="field_required">*</span>
			</div>
			<div>
				<input name="supervisor_name" id="supervisor_name" type="text" maxlength="80" class="field_text <?php if ($_SESSION["errors"]["supervisor_name"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_name"]) && $_SESSION["formdata"]["supervisor_name"]!="") {echo $_SESSION["formdata"]["supervisor_name"];} ?>" />
			</div>
		</div><br />
		<div class="form_field form_left_field">
			<div class="field_title">
				Phone#:
				<span class="field_required">*</span>
			</div>
			<div>
				<input name="supervisor_phone" id="supervisor_phone" type="text" maxlength="20" class="field_text <?php if ($_SESSION["errors"]["supervisor_phone"]) {echo "required_error";} ?>" value="<?php if (isset($_SESSION["formdata"]["supervisor_phone"])) {echo $_SESSION["formdata"]["supervisor_phone"];} ?>" />
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>

	<div id="employee_info" style="margin-bottom:20px;padding-top: 5px; border-top: 1px solid black;">
		<h2>Employee Information</h2>
		<div id="employee_info_container">
		</div>
		<div id="action-add" style="width: 100%; border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 15px; margin-top: 5px">
			<button type="button" onclick="addEmployee()" style="font-weight: bold; color: #3338ff;" onMouseOver="style.color='#E00A0D'" onMouseOut="style.color='#3338ff'">Add Employee</button>
		</div>
	</div>

	<div style="clear:both;"></div>
		<input type="submit" name="cancel" value="Cancel" />&nbsp;&nbsp;&nbsp;
		<input type="button" name="validate" value="Submit" onclick="validateAndSubmit();" />
	</div>
</div>
</form>
<script>
initEmployees();
</script>
<?php
	unset($_SESSION["errors"]);
	unset($_SESSION["formdata"]);
//    require_once("footer.php");
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/footer.php');
?>
