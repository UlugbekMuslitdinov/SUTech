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
  <li class="active">Building Access Request</li>
</ol>
<h1>Building Access Request</h1>
<?php
	if ($_POST["submit_req"]=="submit_req") {
		unset($_SESSION["formdata"]);
		$fields = array("form_type", "sup_name", "sup_phone", "emp_name_first",
			"emp_name_last", "emp_catcard", "emp_pin", "emp_department",
			"access_other", "alarm", "alarm_area", "alarm_passphrase",
			"emp_catcard_new", "replacement_problem");
		foreach ($fields as $cur) {
			$_SESSION["formdata"][$cur] = mysql_real_escape_string($_POST[$cur]);
		}
		$_SESSION["formdata"]["access"] = $_POST["access"];
		$_SESSION["formdata"]["delete"] = $_POST["delete"];
		$to = "su-buildingaccess@list.arizona.edu";
		$subject = $_SESSION["formdata"]['form_type']." - ".$_SESSION["formdata"]["emp_name_first"]." ".$_SESSION["formdata"]["emp_name_last"];
		$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
		   'Reply-To: TECH Web Mailer <no-reply@tech.union.arizona.edu>' . "\r\n" .
		   'X-Mailer: PHP/' . phpversion();
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "Bcc: su-tech@email.arizona.edu\r\n";
		$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
		Submited: '.date("F j, Y, g:i a").'
		<br /><h2 style="margin-bottom:0;">Supervisor Information</h2>
		Name: <b>'.$_SESSION["formdata"]["sup_name"].'</b><br />
		Phone#: <b>'.$_SESSION["formdata"]["sup_phone"].'</b><br />
		NetID: <b>'.$_SESSION['webauth']['netID'].'</b></br >

		<br /><h2 style="margin-bottom:0;">Employee Information</h2>
		First Name: <b>'.$_SESSION["formdata"]["emp_name_first"].'</b><br />
		Last Name: <b>'.$_SESSION["formdata"]["emp_name_last"].'</b><br />
		Catcard#: <b>'.$_SESSION["formdata"]["emp_catcard"].'</b><br />
		4-digit Pin#: <b>'.$_SESSION["formdata"]["emp_pin"].'</b><br />
		Unit/Department: <b>'.$_SESSION["formdata"]["emp_department"].'</b><br />';

		if ($_SESSION["formdata"]["form_type"]=="New Employee") {
			$message .= '<br /><h2 style="margin-bottom:0;">New Employee Access</h2>
			<h3 style="margin-bottom:0;">General Access</h3>
			Areas to Access:<br />';
			if ($_SESSION["formdata"]["access"] > 0) {
				foreach ($_SESSION["formdata"]["access"] as $curarea) {
					$query = 'SELECT * FROM  bldgrequest__area WHERE id='.$curarea;
					$result = $db_link->query($query);
					while($row = $result->fetch_array()) {
						$message .= '<b>'.$row["name"].'</b><br />';
					}
				}
			}
			if (isset($_SESSION["formdata"]["access_other"])&&$_SESSION["formdata"]["access_other"]!="") {
				$message .= 'Other Areas to Access: <b>'.$_SESSION["formdata"]["access_other"].'</b><br />';
			}
			$message .= '<h3 style="margin-bottom:0;">Alarm Access</h3>
			Need Alarm Access?: <b>'.$_SESSION["formdata"]["alarm"].'</b><br />';
			if (isset($_SESSION["formdata"]["alarm_area"])&&$_SESSION["formdata"]["alarm_area"]!="") {
				$message .= 'Alarm Access Area: <b>'.$_SESSION["formdata"]["alarm_area"].'</b><br />';
			}
			if (isset($_SESSION["formdata"]["alarm_passphrase"])&&$_SESSION["formdata"]["alarm_passphrase"]!="") {
				$message .= 'Alarm Access Password: <b>'.$_SESSION["formdata"]["alarm_passphrase"].'</b><br />';
			}
		}

		if ($_SESSION["formdata"]["form_type"]=="Update Employee") {
			$message .= '<br /><h2 style="margin-bottom:0;">Replacement CatCard#/Other Changes/Problems</h2>
			Replacement Catcard#: <b>'.$_SESSION["formdata"]["emp_catcard_new"].'</b><br />
			Problems: <b>'.$_SESSION["formdata"]["replacement_problem"].'</b><br />';
		}

		if ($_SESSION["formdata"]["form_type"]=="Delete Employee") {
			$message .= '<br /><h2 style="margin-bottom:0;">Please Remove Employee Access</h2>';
		}

		$message .= '</body></html>';
		$email = mail($to, $subject, $message, $headers);
		
		
		$to = $_SESSION['webauth']['netID']."@email.arizona.edu";
        $subject = "Building Access Request Confirmation - ".$_SESSION["formdata"]["emp_name_first"]." ".$_SESSION["formdata"]["emp_name_last"];
        $headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
           'Reply-To: TECH Web Mailer <no-reply@tech.sunion.arizona.edu>' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        //$headers .= "Bcc: nbischof@email.arizona.edu\r\n";
        $message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
        <br /><h3 style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;padding-top:5px;padding-bottom:6px;margin-top:0px;margin-bottom:10px;">Building Access Request Summary</h3>
        Submited: '.date("F j, Y, g:i a").'
        <br />**This is not the date or time recieved by our staff.**
        <br /><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Please allow atleast 1-3 bussiness days to process your request.</i>

        <br /><h3 style="margin-bottom:0;">Employee Information</h3>
        First Name: <b>'.$_SESSION["formdata"]["emp_name_first"].'</b><br />
		Last Name: <b>'.$_SESSION["formdata"]["emp_name_last"].'</b><br />
        Catcard#: <b>################</b><br />
        4-digit Pin#: <b>####</b><br />
        Unit/Department: <b>'.$_SESSION["formdata"]["emp_department"].'</b><br />';

        if ($_SESSION["formdata"]["form_type"]=="New Employee") {
			$message .= '<br /><h2 style="margin-bottom:0;">New Employee Access</h2>
			<h3 style="margin-bottom:0;">General Access</h3>
			Areas to Access:<br />';
			if ($_SESSION["formdata"]["access"] > 0) {
				foreach ($_SESSION["formdata"]["access"] as $curarea) {
					$query = 'SELECT * FROM  bldgrequest__area WHERE id='.$curarea;
					$result = $db_link->query($query);
					while($row = $result->fetch_array()) {
						$message .= '<b>'.$row["name"].'</b><br />';
					}
				}
			}
			if (isset($_SESSION["formdata"]["access_other"])&&$_SESSION["formdata"]["access_other"]!="") {
				$message .= 'Other Areas to Access: <b>'.$_SESSION["formdata"]["access_other"].'</b><br />';
			}
			$message .= '<h3 style="margin-bottom:0;">Alarm Access</h3>
			Need Alarm Access?: <b>'.$_SESSION["formdata"]["alarm"].'</b><br />';
			if (isset($_SESSION["formdata"]["alarm_area"])&&$_SESSION["formdata"]["alarm_area"]!="") {
				$message .= 'Alarm Access Area: <b>'.$_SESSION["formdata"]["alarm_area"].'</b><br />';
			}
			if (isset($_SESSION["formdata"]["alarm_passphrase"])&&$_SESSION["formdata"]["alarm_passphrase"]!="") {
				$message .= 'Alarm Access Password: <b>'.$_SESSION["formdata"]["alarm_passphrase"].'</b><br />';
			}
		}

		if ($_SESSION["formdata"]["form_type"]=="Update Employee") {
			$message .= '<br /><h2 style="margin-bottom:0;">Replacement CatCard#/Other Changes/Problems</h2>
			Replacement Catcard#: <b>################</b><br />
			Problems: <b>'.$_SESSION["formdata"]["replacement_problem"].'</b><br />';
		}

		if ($_SESSION["formdata"]["form_type"]=="Delete Employee") {
			$message .= '<br /><h2 style="margin-bottom:0;">Please Remove Employee Access</h2>';
		}

        $message .= '</body></html>';
        $email = mail($to, $subject, $message, $headers);
		
		echo 'Request was submitted.';
	}
	else {
?>
<style type="text/css">
.form_section {
	display: none;
}
#limitlbl_0 {
    float:right;
}
</style>
<div class="theme_form">
	<!-- form: -->
	<section>
		<form id="defaultForm" method="post" class="form-horizontal">
			<div class="col-xs-12">
				<h4 style="margin-left: -25px;">Form Type</h4>
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<div class="radio">
								<input type="radio" name="form_type" id="form_type_new" onclick="setForm('new');" value="New Employee" />New Access
							</div>
							<div class="radio">
								<input type="radio" name="form_type" id="form_type_update" onclick="setForm('update');" value="Update Employee" />Replacement CatCard#/Other Changes/Problems
							</div>
							<div class="radio">
								<input type="radio" name="form_type" id="form_type_delete" onclick="setForm('delete');" value="Delete Employee" />Delete Employee's Access
							</div>
						</div>
					</div>
				</div>
				<div class="form_section" id="form_section_all">
					<hr class="row" style="margin-top:0;" />
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
					<div class="row form_section" id="form_section_catcard">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<label class="control-label">Catcard#</label>
								<div>
									<input type="text" class="form-control" name="emp_catcard" maxlength="32" placeholder="eg. 6017090202212345" />
								</div>
							</div>
						</div>
						<div class="col-sm-5 col-xs-12 col-sm-offset-1">
							<div class="form-group">
								<label class="control-label">4-digit PIN</label> <span style="color:#888;font-style:italic;">(Cannot be last 4 digits of CatCard)</span>
								<div>
									<input type="text" class="form-control" name="emp_pin" maxlength="32" placeholder="eg. 1885" />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<label class="control-label">Department/Unit</label>
								<div>
									<input type="text" class="form-control" name="emp_department" maxlength="32" placeholder="eg. Wilbur's Waffle Cart" />
								</div>
							</div>
						</div>
					</div>
					<hr class="row" />
				</div>
				<div id="form_section_new" class="form_section">
					<h4 style="margin-left: -25px;">New Employee Access</h4>
					<div class="row">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<label class="control-label">Area Access</label>
								<div class="input-group">
									<?php
										$query = 'SELECT * FROM  bldgrequest__area ORDER BY name DESC';
										$result = $db_link->query($query);
										while($row = $result->fetch_array()) {
											echo '<div class="checkbox">
														<label><input type="checkbox" name="access[]" value="'.$row["id"].'">'.$row["name"].'</label>
													</div>';
										}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Other Areas to Access</label>
								<div>
									<input type="text" class="form-control" name="access_other" maxlength="32" placeholder="eg. Somewhere Special" />
								</div>
							</div>
						</div>
						<div class="col-sm-5 col-xs-12 col-sm-offset-1">
							<div class="form-group">
								<label class="control-label">Alarm Access</label>
								<div class="radio">
									<input type="radio" name="alarm" id="alarm_yes" value="Yes" />Yes
								</div>
								<div class="radio">
									<input type="radio" name="alarm" id="alarm_no" value="No" />No
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Alarm Access Area</label>
								<div>
									<input type="text" class="form-control" name="alarm_area" maxlength="32" placeholder="eg. Cash Room" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Alarm Access Passphrase</label>
								<div>
									<input type="text" class="form-control" name="alarm_passphrase" maxlength="32" placeholder="eg. wildcats" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="form_section_update" class="form_section">
					<h4 style="margin-left: -25px;">Update Employee Access</h4>
					<div class="row">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<label class="control-label">Replacement Catcard#</label>
								<div>
									<input type="text" class="form-control" name="emp_catcard_new" maxlength="32" placeholder="eg. Wilbur's Waffle Cart" />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-11 col-xs-12">
							<div class="form-group">
								<label class="control-label">Other/Problem</label>
								<textarea class="form-control" name="replacement_problem" id="replacement_problem_text" maxlength="255" lengthcut="true" rows="5" style="width: 100%; resize: none;"></textarea>
								<div style="color:#888;font-style:italic;">
									Please provide a time and specific location of any door(s)
									which pertain to your problem.<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(e.g. North Food Court Door
									West of Einstein's or a room number)
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="form_section_delete" class="form_section">
					<h4 style="margin-left: -25px;">Delete Employee Access</h4>
					<div class="row">
						<div class="col-sm-5 col-xs-12">
							<div class="form-group">
								<div class="input-group">
									<div class="checkbox">
										<label><input type="checkbox" name="delete[]" value="Yes">Confirm Remove Employee Access</label>
									</div>
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
<script type="text/javascript" language="javascript" src="../charcount.js"></script>
<script type="text/javascript" src="/js/bootstrapValidator.js"></script>
<script type="text/javascript">
parseCharCounts();
function showSectionAll(visibility) {
    document.getElementById("form_section_all").style.display = visibility;
}
function showSectionCatcard(visibility) {
    document.getElementById("form_section_catcard").style.display = visibility;
}
function showSectionNew(visibility) {
    document.getElementById("form_section_new").style.display = visibility;
}
function showSectionUpdate(visibility) {
    document.getElementById("form_section_update").style.display = visibility;
}
function showSectionDelete(visibility) {
    document.getElementById("form_section_delete").style.display = visibility;
}
function setForm(form) {
    showSectionAll("block");
    if (form == "new") {
        showSectionNew("block");
		showSectionCatcard("block");
        showSectionUpdate("none");
        showSectionDelete("none");
    }
    if (form == "update") {
        showSectionNew("none");
		showSectionCatcard("none");
        showSectionUpdate("block");
        showSectionDelete("none");
    }
    if (form == "delete") {
        showSectionNew("none");
		showSectionCatcard("none");
        showSectionUpdate("none");
        showSectionDelete("block");
	}
}
$(document).ready(function() {
    $('#defaultForm').bootstrapValidator({
        message: 'This changes are not valid',
        fields: {
			form_type: {
                message: 'Please provide form type.',
                validators: {
                    notEmpty: {
                        message: '\'Form type\' is required and can\'t be empty'
                    }
                }
            },
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
			emp_department: {
                message: 'Please provide employee\'s department/unit.',
                validators: {
                    notEmpty: {
                        message: '\'Employee\'s Department/Unit\' is required and can\'t be empty'
                    }
                }
            }
        }
    });
});
</script>