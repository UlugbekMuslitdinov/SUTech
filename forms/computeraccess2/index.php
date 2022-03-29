<?php
	$static_nav=true;
	include("webauth/include.php");
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("request");
?>
<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li>Forms</li>
  <li class="active">System Access Request</li>
</ol>
<h1>System Access Request</h1>
<?php
	if ($_POST["submit_req"]=="submit_req") {
		unset($_SESSION["formdata"]);
		$fields = array("sup_name", "sup_phone", "emp_type", "emp_position",
			"emp_name_first", "emp_name_last", "emp_title", "emp_email",
			"emp_phone", "emp_department", "emp_netid", "emp_location",
			"foodpro_location", "register_pin", "other");
		foreach ($fields as $cur) {
			$_SESSION["formdata"][$cur] = mysql_real_escape_string($_POST[$cur]);
		}
		$_SESSION["formdata"]["access"] = $_POST["access"];
		$to = "su-systemsaccess@list.arizona.edu";
		$subject = "System Access Request - ".$_SESSION["formdata"]["emp_type"]." - ".$_SESSION["formdata"]["emp_netid"];
		$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
		Submited: '.date("F j, Y, g:i a").'
		<br /><h2 style="margin-bottom:0;">Supervisor Information</h2>
		Name: <b>'.$_SESSION["formdata"]["sup_name"].'</b><br />
		Phone#: <b>'.$_SESSION["formdata"]["sup_phone"].'</b><br />
		NetID: <b>'.$_SESSION['webauth']['netID'].'</b></br >

		<br /><h2 style="margin-bottom:0;">Employee Information</h2>
		Type: <b>'.$_SESSION["formdata"]["emp_type"].'</b><br />
		Position: <b>'.$_SESSION["formdata"]["emp_position"].'</b><br />
		First Name: <b>'.$_SESSION["formdata"]["emp_name_first"].'</b><br />
		Last Name: <b>'.$_SESSION["formdata"]["emp_name_last"].'</b><br />
		Title\Job: <b>'.$_SESSION["formdata"]["emp_title"].'</b><br />
		E-mail: <b>'.$_SESSION["formdata"]["emp_email"].'</b><br />
		Work Phone#: <b>'.$_SESSION["formdata"]["emp_phone"].'</b><br />
		Department/Unit: <b>'.$_SESSION["formdata"]["emp_department"].'</b><br />
		NetID: <b>'.$_SESSION["formdata"]["emp_netid"].'</b><br />
		Workstation Location: <b>'.$_SESSION["formdata"]["emp_location"].'</b><br />

		<br /><h2 style="margin-bottom:0;">Employee Access</h2>
		Systems to grant access:<br />
		<b>';
		if (count($_SESSION["formdata"]["access"])>0) {
			foreach ($_SESSION["formdata"]["access"] as $checkbox) {
				$message .= $checkbox."<br />";
			}
		}
		$message .= '</b>';
		if (isset($_SESSION["formdata"]['foodpro_location'])&&$_SESSION["formdata"]['foodpro_location']!="") {
			$message .= 'Foodpro Location: <b>'.$_SESSION["formdata"]['foodpro_location'].'</b><br />';
		}
		if (isset($_SESSION["formdata"]['register_pin'])&&$_SESSION["formdata"]['register_pin']!="") {
					$message .= 'Register Pin: <b>'.$_SESSION["formdata"]['register_pin'].'</b><br />';
		}
		if (isset($_SESSION["formdata"]['catcard'])&&$_SESSION["formdata"]['catcard']!="") {
						$message .= 'Catcard#: <b>'.$_SESSION["formdata"]['catcard'].'</b><br />';
		}
		if (isset($_SESSION["formdata"]['other'])&&$_SESSION["formdata"]['other']!="") {
			$message .= 'Other Access: <b>'.$_SESSION["formdata"]['other'].'</b><br />';
		}
		$message .= '</body></html>';
		$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
		   'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
		   'X-Mailer: PHP/' . phpversion();
		$headers .= "BCC:su-tech@email.arizona.edu\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		mail($to, $subject, $message, $headers);
		
		$to = $_SESSION['webauth']['netID']."@email.arizona.edu";
        $subject = "Systems Access Request Confirmation - ".$_SESSION["formdata"]["emp_netid"];
        $headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
           'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        //$headers .= "Bcc: nbischof@email.arizona.edu\r\n";
		$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
        <br /><h3 style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;padding-top:5px;padding-bottom:6px;margin-top:0px;margin-bottom:10px;">Computer Systems Access Request Summary</h3>
        Submited: '.date("F j, Y, g:i a").'
        <br />**This is not the date or time recieved by our staff.**
        <br /><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Please allow atleast 2-5 bussiness days to process your request.</i>

        <br /><h3 style="margin-bottom:0;">Employee Information</h3>
        Type: <b>'.$_SESSION["formdata"]["emp_type"].'</b><br />
        Position: <b>'.$_SESSION["formdata"]["emp_position"].'</b><br />
        First Name: <b>'.$_SESSION["formdata"]["emp_name_first"].'</b><br />
        Last Name: <b>'.$_SESSION["formdata"]["emp_name_last"].'</b><br />
        Title\Job: <b>'.$_SESSION["formdata"]["emp_title"].'</b><br />
        E-mail: <b>'.$_SESSION["formdata"]["emp_email"].'</b><br />
        Work Phone#: <b>'.$_SESSION["formdata"]["emp_phone"].'</b><br />
        Department/Unit: <b>'.$_SESSION["formdata"]["emp_department"].'</b><br />
        NetID: <b>'.$_SESSION["formdata"]["emp_netid"].'</b><br />
        Workstation Location: <b>'.$_SESSION["formdata"]["emp_location"].'</b><br />

        <br /><h3 style="margin-bottom:0;">Employee Access</h3>
        Systems to grant access:<br />
        <b>';
        if (count($_SESSION["formdata"]["access"])>0) {
			foreach ($_SESSION["formdata"]["access"] as $checkbox) {
				$message .= $checkbox."<br />";
			}
		}
		$message .= '</b>';
		if (isset($_SESSION["formdata"]['foodpro_location'])&&$_SESSION["formdata"]['foodpro_location']!="") {
			$message .= 'Foodpro Location: <b>'.$_SESSION["formdata"]['foodpro_location'].'</b><br />';
		}
		if (isset($_SESSION["formdata"]['register_pin'])&&$_SESSION["formdata"]['register_pin']!="") {
					$message .= 'Register Pin: <b>'.$_SESSION["formdata"]['register_pin'].'</b><br />';
		}
		if (isset($_SESSION["formdata"]['catcard'])&&$_SESSION["formdata"]['catcard']!="") {
						$message .= 'Catcard#: <b>'.$_SESSION["formdata"]['catcard'].'</b><br />';
		}
		if (isset($_SESSION["formdata"]['other'])&&$_SESSION["formdata"]['other']!="") {
			$message .= 'Other Access: <b>'.$_SESSION["formdata"]['other'].'</b><br />';
		}
        $message .= '</body></html>';
		$email = mail($to, $subject, $message, $headers);
		echo 'Request was submitted.';
	}
	else {
?>
<style type="text/css">
#foodpro_location_section, #register_pin_section, #other_section {
	display: none;
}
</style>
<script type="text/javascript">
function toggle_foodpro() {
	if (document.getElementById("foodpro_box").checked) {
		document.getElementById("foodpro_location_section").style.display = 'block';
	}
	else {
		document.getElementById("foodpro_location_section").style.display = 'none';
	}
}
function toggle_pin() {
	if (document.getElementById("cashier_box").checked || document.getElementById("lead_cashier_box").checked) {
		document.getElementById("register_pin_section").style.display = 'block';
	}
	else {
		document.getElementById("register_pin_section").style.display = 'none';
	}
}
function toggle_other() {
	if (document.getElementById("other_box").checked) {
		document.getElementById("other_section").style.display = 'block';
	}
	else {
		document.getElementById("other_section").style.display = 'none';
	}
}
</script>
<div class="theme_form">
	<!-- form: -->
	<section>
		<form id="defaultForm" method="post" class="form-horizontal">
			<div class="col-xs-12">
				<h4 style="margin-left: -25px;">Supervisor Information</h4>
				<div class="row">
					<div class="col-sm-5 col-xs-12">
						<div class="form-group">
							<label class="control-label">Supervisor Name</label>
							<div>
								<input type="text" class="form-control" name="sup_name" maxlength="32" placeholder="eg. President Hart" />
							</div>
						</div>
					</div>
					<div class="col-sm-5 col-xs-12 col-sm-offset-1">
						<div class="form-group">
							<label class="control-label">Supervisor Phone</label>
							<div>
								<input type="text" class="form-control" name="sup_phone" maxlength="32" placeholder="eg. 555-1234" />
							</div>
						</div>
					</div>
				</div>
				<hr class="row" />
				<h4 style="margin-left: -25px;">Employee Information</h4>
				<div class="row">
					<div class="col-sm-5 col-xs-12">
						<div class="form-group">
							<label class="control-label">Employee Type</label>
							<div class="input-group">
								<div class="radio">
									<input type="radio" name="emp_type" id="emp_type_new" value="New Employee" />New Employee
								</div>
								<div class="radio">
									<input type="radio" name="emp_type" id="emp_type_update" value="Update Employee" />Update Employee
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-5 col-xs-12 col-sm-offset-1">
						<div class="form-group">
							<label class="control-label">Employee Position</label>
							<div class="input-group">
								<div class="radio">
									<input type="radio" name="emp_position" id="emp_position_staff" value="Staff" />Staff
								</div>
								<div class="radio">
									<input type="radio" name="emp_position" id="emp_position_student" value="Student" />Student
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5 col-xs-12">
						<div class="form-group">
							<label class="control-label">First Name</label>
							<div>
								<input type="text" class="form-control" name="emp_name_first" maxlength="32" placeholder="eg. Wilbur" />
							</div>
						</div>
					</div>
					<div class="col-sm-5 col-xs-12 col-sm-offset-1">
						<div class="form-group">
							<label class="control-label">Last Name</label>
							<div>
								<input type="text" class="form-control" name="emp_name_last" maxlength="32" placeholder="eg. Wildcat" />
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5 col-xs-12">
						<div class="form-group">
							<label class="control-label">Title/Job</label>
							<div>
								<input type="text" class="form-control" name="emp_title" maxlength="32" placeholder="eg. Mascot" />
							</div>
						</div>
					</div>
					<div class="col-sm-5 col-xs-12 col-sm-offset-1">
						<div class="form-group">
							<label class="control-label">University of Arizona E-mail</label>
							<div>
								<input type="text" class="form-control" name="emp_email" maxlength="32" placeholder="eg. wilburw@email.arizona.edu" />
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5 col-xs-12">
						<div class="form-group">
							<label class="control-label">Work Phone#</label>
							<div>
								<input type="text" class="form-control" name="emp_phone" maxlength="32" placeholder="eg. 555-1234" />
							</div>
						</div>
					</div>
					<div class="col-sm-5 col-xs-12 col-sm-offset-1">
						<div class="form-group">
							<label class="control-label">Department/Unit</label>
							<div>
								<input type="text" class="form-control" name="emp_department" maxlength="32" placeholder="eg. Event Services" />
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5 col-xs-12">
						<div class="form-group">
							<label class="control-label">Employee's NetID</label>
							<div>
								<input type="text" class="form-control" name="emp_netid" maxlength="32" placeholder="eg. wilburw" />
							</div>
						</div>
					</div>
					<div class="col-sm-5 col-xs-12 col-sm-offset-1">
						<div class="form-group">
							<label class="control-label">Specific Workstation Location</label>
							<div>
								<input type="text" class="form-control" name="emp_location" maxlength="32" placeholder="eg. SUMC - Room 404 - Desk Against Window" />
							</div>
						</div>
					</div>
				</div>
				<hr class="row" />
				<h4 style="margin-left: -25px;">Requested Access</h4>
				<div class="row">
					<div class="col-sm-11 col-xs-12">
						<div class="form-group">
							<label class="control-label">System Access</label>
							<div class="input-group">
								<div class="checkbox">
									<label><input type="checkbox" name="access[]" value="Workstation Logon">Workstation Logon</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" id="foodpro_box" onclick="toggle_foodpro();" name="access[]" value="FoodPro" />FoodPro</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" name="access[]" value="Micros Reports" />Micros Reports</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" name="access[]" value="NetVuPoint" />NetVuPoint</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" id="cashier_box" onclick="toggle_pin();" name="access[]" value="Cashier Access" />Cashier Access</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" id="lead_cashier_box" onclick="toggle_pin();" name="access[]" value="Lead Cashier Access" />Lead Cashier Access</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" id="other_box" onclick="toggle_other();" name="access[]" value="Other" />Other</label>
								</div>
								<div class="checkbox disabled">
									<label><input type="checkbox" name="access[]" value="Kronos" disabled />Kronos</label>
									<span style="color:#888;font-style: italic;margin-left: 10px;">(For access to Kronos please contact the payroll office. 621-9460)</span>
								</div>
							</div>
						</div>
					</div>
					<div id="foodpro_location_section" class="row">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<label class="control-label">FoodPro Location</label>
								<div>
									<input type="text" class="form-control" name="foodpro_location" maxlength="32" placeholder="eg. 8" />
								</div>
							</div>
						</div>
					</div>
					<div id="register_pin_section" class="row">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<label class="control-label">Register Pin</label>
								<div>
									<input type="text" class="form-control" name="register_pin" maxlength="32" placeholder="eg. 1234" />
								</div>
							</div>
						</div>
					</div>
					<div id="other_section" class="row">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<label class="control-label">Other</label>
								<div>
									<input type="text" class="form-control" name="other" maxlength="32" placeholder="eg. Security Cameras" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group col-xs-12" style="margin-top:20px;">
				<div>
					<button type="submit" name="submit_req" value="submit_req" class="btn btn-primary">Submit Request</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="/"><button type="button" class="btn btn-default">Cancel</button></a>
				</div>
			</div>
		</form>
	</section>
	<!-- :form -->
</div>

<?php
	}
include_once('footer2.php');
?>
<script type="text/javascript" src="/js/bootstrapValidator.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#defaultForm').bootstrapValidator({
        message: 'This changes are not valid',
        fields: {
            sup_name: {
                message: 'Please provide supervisor name.',
                validators: {
                    notEmpty: {
                        message: '\'Supervisor name\' is required and can\'t be empty'
                    }
                }
            },
			sup_phone: {
                message: 'Please provide supervisor phone.',
                validators: {
                    notEmpty: {
                        message: '\'Supervisor phone\' is required and can\'t be empty'
                    }
                }
            },
			emp_type: {
                message: 'Please provide employee\s type.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s Type\' is required and must be selected'
                    }
                }
            },
			emp_position: {
                message: 'Please provide employee\'s position.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s Position\' is required and must be selected'
                    }
                }
            },
			emp_name_first: {
                message: 'Please provide employee\'s first name.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s first name\' is required and can\'t be empty'
                    }
                }
            },
			emp_name_last: {
                message: 'Please provide employee\'s last name.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s last name\' is required and can\'t be empty'
                    }
                }
            },
			emp_title: {
                message: 'Please provide employee\'s title.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s title\' is required and can\'t be empty'
                    }
                }
            },
			emp_email: {
                message: 'Please provide employee\'s e-mail.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s University of Arizona E-mail\' is required and can\'t be empty'
                    }
                }
            },
			emp_phone: {
                message: 'Please provide employee\'s phone.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s phone\' is required and can\'t be empty'
                    }
                }
            },
			emp_department: {
                message: 'Please provide employee\'s department/unit.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s Department/Unit\' is required and can\'t be empty'
                    }
                }
            },
			emp_netid: {
                message: 'Please provide employee\'s NetID.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s NetID\' is required and can\'t be empty'
                    }
                }
            },
			emp_location: {
                message: 'Please provide specific workstation location.',
                validators: {
                    notEmpty: {
                        message: '\'Specific Workstation Location\' is required and can\'t be empty'
                    }
                }
            },
			'access[]': {
                message: 'Please provide specific workstation location.',
                validators: {
                    notEmpty: {
                        message: '\'System Access\' is required and must be selected'
                    }
                }
            }
        }
    });
});
</script>