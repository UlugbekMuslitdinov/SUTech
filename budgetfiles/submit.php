<?php
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");

	if ($_POST["cancel"] == "Cancel") {
		header('Location: /index.php');
		exit;
	}

	unset($_SESSION["errors"]);
	$errors = array();
	$return = false;
    if ($_POST["form_type"]=="New Access") {
        $required_fields = array("supervisor_name", "supervisor_phone",
            "employee_first_name", "employee_last_name", "catcard",
            "employee_unit");
    }
    else {
    	$required_fields = array("supervisor_name", "supervisor_phone",
            "employee_first_name", "employee_last_name", "employee_unit");
    }
	foreach ($required_fields as $cur) {
		if (!isset($_POST[$cur]) || $_POST[$cur]=="") {
			$errors[$cur] = true;
			$return = true;
		}
	}
	$fields = array("supervisor_name", "supervisor_phone",
		"employee_first_name", "employee_last_name", "catcard",
		"pin", "employee_unit", "access", "other_areas",
		"alarm_access", "alarm_area", "alarm_password",
		"replacement_catcard", "replacement_other", "replacement_problem",
		"delete", "form_type");
	foreach ($fields as $cur) {
		$_SESSION["formdata"][$cur] = $_POST[$cur];
		$_POST["formdata"][$cur] = mysql_real_escape_string($_POST["formdata"][$cur]);
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
	unset($_SESSION["formdata"]);

	$to = "su-buildingaccess@list.arizona.edu";
	$subject = $_POST['form_type']." - ".$_POST["employee_first_name"]." ".$_POST["employee_last_name"];
	$headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
	   'Reply-To: TECH Web Mailer <no-reply@pearl.sunion.arizona.edu>' . "\r\n" .
	   'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Bcc: su-tech@email.arizona.edu\r\n";
	$message = '<html><body style="font-family: arial, sans-serif;font-size: 13px;">
	Submited: '.date("F j, Y, g:i a").'
	<br /><h2 style="margin-bottom:0;">Supervisor Information</h2>
	Name: <b>'.$_POST['supervisor_name'].'</b><br />
	Phone#: <b>'.$_POST['supervisor_phone'].'</b><br />
	Email: <b><a href="mailto:'.$_POST['supervisor_email'].'">'.$_POST['supervisor_email'].'</a></b><br />
	NetID: <b>'.$_SESSION['webauth']['netID'].'</b></br >

	<br /><h2 style="margin-bottom:0;">Employee Information</h2>
	First Name: <b>'.$_POST['employee_first_name'].'</b><br />
	Last Name: <b>'.$_POST['employee_last_name'].'</b><br />
	Catcard#: <b>'.$_POST['catcard'].'</b><br />
	4-digit Pin#: <b>'.$_POST['pin'].'</b><br />
	Unit/Department: <b>'.$_POST['employee_unit'].'</b><br />';

	if ($_POST["access"] > 0 || (isset($_POST["alarm_access"])&&$_POST["alarm_access"]!="") ||
	(isset($_POST["other_areas"])&&$_POST["other_areas"]!="") ||
	(isset($_POST["alarm_area"])&&$_POST["alarm_area"]!="") ||
	(isset($_POST["alarm_password"])&&$_POST["alarm_password"]!="")) {
		$message .= '<br /><h2 style="margin-bottom:0;">New Employee Access</h2>
		<h3 style="margin-bottom:0;">General Access</h3>
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
		$message .= '<h3 style="margin-bottom:0;">Alarm Access</h3>
		Need Alarm Access?: <b>'.$_POST["alarm_access"].'</b><br />';
		if (isset($_POST["alarm_area"])&&$_POST["alarm_area"]!="") {
			$message .= 'Alarm Access Area: <b>'.$_POST["alarm_area"].'</b><br />';
		}
		if (isset($_POST["alarm_password"])&&$_POST["alarm_password"]!="") {
			$message .= 'Alarm Access Password: <b>'.$_POST["alarm_password"].'</b><br />';
		}
	}

	if ((isset($_POST["replacement_catcard"])&&$_POST["replacement_catcard"]!="") ||
	(isset($_POST["replacement_other"])&&$_POST["replacement_other"]!="") ||
	(isset($_POST["replacement_problem"])&&$_POST["replacement_problem"]!="")) {
		$message .= '<br /><h2 style="margin-bottom:0;">Replacement CatCard#/Other Changes/Problems</h2>
		Replacement Catcard#: <b>'.$_POST["replacement_catcard"].'</b><br />
		Other (Specify): <b>'.$_POST["replacement_other"].'</b><br />
		Problems: <b>'.$_POST["replacement_problem"].'</b><br />';
	}

	if ($_POST["delete"]=="Yes") {
		$message .= '<br /><h2 style="margin-bottom:0;">Please Remove Employee Access</h2>';
	}

	$message .= '</body></html>';
	$email = mail($to, $subject, $message, $headers);



	if ($email) {
    	$to = $_POST['supervisor_email'];
        $subject = "Building Access Request Confirmation - ".$_POST["employee_first_name"]." ".$_POST["employee_last_name"];
        $headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
           'Reply-To: TECH Web Mailer <no-reply@pearl.sunion.arizona.edu>' . "\r\n" .
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
        First Name: <b>'.$_POST['employee_first_name'].'</b><br />
        Last Name: <b>'.$_POST['employee_last_name'].'</b><br />
        Catcard#: <b>################</b><br />
        4-digit Pin#: <b>####</b><br />
        Unit/Department: <b>'.$_POST['employee_unit'].'</b><br />';

        if ($_POST["access"] > 0 || (isset($_POST["alarm_access"])&&$_POST["alarm_access"]!="") ||
        (isset($_POST["other_areas"])&&$_POST["other_areas"]!="") ||
        (isset($_POST["alarm_area"])&&$_POST["alarm_area"]!="") ||
        (isset($_POST["alarm_password"])&&$_POST["alarm_password"]!="")) {
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
            Need Alarm Access?: <b>'.$_POST["alarm_access"].'</b><br />';
            if (isset($_POST["alarm_area"])&&$_POST["alarm_area"]!="") {
                $message .= 'Alarm Access Area: <b>'.$_POST["alarm_area"].'</b><br />';
            }
            if (isset($_POST["alarm_password"])&&$_POST["alarm_password"]!="") {
                $message .= 'Alarm Access Password: <b>'.$_POST["alarm_password"].'</b><br />';
            }
        }

        if ((isset($_POST["replacement_catcard"])&&$_POST["replacement_catcard"]!="") ||
        (isset($_POST["replacement_other"])&&$_POST["replacement_other"]!="") ||
        (isset($_POST["replacement_problem"])&&$_POST["replacement_problem"]!="")) {
            $message .= '<br /><h3 style="margin-bottom:0;">Replacement CatCard#/Other Changes/Problems</h3>
            Replacement Catcard#: <b>################</b><br />
            Other (Specify): <b>'.$_POST["replacement_other"].'</b><br />
            Problems: <b>'.$_POST["replacement_problem"].'</b><br />';
        }

        if ($_POST["delete"]=="Yes") {
            $message .= '<br /><h3 style="margin-bottom:0;">Remove Employee Access</h3>';
        }

        $message .= '</body></html>';
        $email = mail($to, $subject, $message, $headers);
    }
?>
<h1>Building Access Request Form</h1><br />
<?php
if ($email) {
	echo '<p>Your request has been recieved successfully. Please allow atleast 1-3 bussiness days to process your request.</p>
	<p><i>The supervisor listed as the conact should recieve a confirmation email shortly.</i></p>';
}
else {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occured.</div>';
}
    require_once("footer.php");
?>
