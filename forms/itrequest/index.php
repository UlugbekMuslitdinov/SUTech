<?php
	$static_nav=true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("request");
?>
<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li>Forms</li>
  <li class="active">IT Help Desk Ticket</li>
</ol>
<h1>IT Help Desk Ticket</h1>
<?php
	if ($_POST["submit_req"]=="submit_req") {
		unset($_SESSION["formdata"]);
		$fields = array("name", "email", "phone_number",
			"phone_number_alt", "department", "building", "room",
			"category", "severity", "req_date", "summary",
			"description");
		foreach ($fields as $cur) {
			$_SESSION["formdata"][$cur] = mysql_real_escape_string($_POST[$cur]);
		}
		$to = "su-tech@email.arizona.edu";
		$subject = "New IT Request - ".$_SESSION["formdata"]["summary"];
		$mail_body = "Name: ".$_SESSION["formdata"]["name"]."\r\n"
		."Summary: ".$_SESSION["formdata"]["summary"]."\r\n"
		."Description: ".$_SESSION["formdata"]["description"]."\r\n"
		."#created by ".$_SESSION["formdata"]["email"]."\r\n"
		."#set Phone Number=".$_SESSION["formdata"]["phone_number"]."\r\n"
		."#set Department=".$_SESSION["formdata"]["department"]."\r\n"
		."#set Building=".$_SESSION["formdata"]["building"]."\r\n"
		."#set Room Number=".$_SESSION["formdata"]["room"]."\r\n"
		."#category ".$_SESSION["formdata"]["category"]."\r\n"
		."#set Severity=".$_SESSION["formdata"]["severity"]."\r\n";
		$headers  = "From:su-tech-no-reply@email.arizona.edu\r\n";
		$headers .= "BCC:yontaek@email.arizona.edu\r\n";
		$headers .= "Content-type: text\r\n";
		//var_dump($mail_body);
		mail($to, $subject, $mail_body, $headers);
		echo 'Request was submitted.';
	}
	else {
?>
<style type="text/css">
#limitlbl_0 {
    float:right;
}
</style>
<div class="theme_form">
	<!-- form: -->
	<section>
		<form id="defaultForm" method="post" class="form-horizontal">
			<div class="col-xs-12">
<?php
$fields = array("name" => "Name",
				"email" => "E-mail Address",
				"phone_number" => "Phone Number",
				"department" => "Department",
				"building" => "Building",
				"room" => "Room",
				"category" => "Category",
				"severity" => "Severity",
				"summary" => "Summary",
				"description" => "Description");
$examples = array("name" => "Wilbur Wildcat",
				"email" => "wilburw@email.arizona.edu",
				"phone_number" => "555-1234",
				"room" => "156",
				"summary" => "Unable to print files in color.",
				"description" => "When attempting to print from Word, Internet Explorer, or Google Chrome, I am unable to print in color from the computers connected to our printer.");
$editable = array("name", "email", "phone_number","phone_number_alt","department","building","room","severity","req_date","category","summary","description");
$coloffset = intval(0);
foreach ($fields as $cur_field => $cur_title) {
	if (($coloffset%2)==0) {
		echo '<div class="row">';
	}
	if ($cur_field=="description") {
		echo '<div class="col-sm-11 col-xs-12';
	}
	else {
		echo '<div class="col-sm-5 col-xs-12';
		if (($coloffset%2)==1) {
			echo ' col-sm-offset-1';
		}
	}
	$coloffset++;
	echo '">';
	if ($cur_field == "department") {
		echo '<div class="form-group">
          <label class="control-label" for="department">Department:</label>
          <select class="form-control" id="department" name="department">
			<option value=""></option>';
        
		$query = 'SELECT name FROM  itrequest__department ORDER BY name ASC';
		$result = $db_link->query($query);
		while($row = $result->fetch_array()) {
			echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
		}
		
		echo '</select>
        </div>
		';
	}
	else if ($cur_field == "building") {
		echo '<div class="form-group">
          <label class="control-label" for="building">Building:</label>
          <select class="form-control" id="building" name="building">
			<option value=""></option>';
        
		$query = 'SELECT name FROM  itrequest__building ORDER BY id ASC';
		$result = $db_link->query($query);
		while($row = $result->fetch_array()) {
			echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
		}
		
		echo '</select>
        </div>
		';
	}
	else if ($cur_field == "severity") {
		echo '<div class="form-group">
          <label class="control-label" for="severity">Severity:</label>
          <select class="form-control" id="severity" name="severity">
			<option value=""></option>';
        
		$query = 'SELECT name FROM  itrequest__severity ORDER BY id ASC';
		$result = $db_link->query($query);
		while($row = $result->fetch_array()) {
			echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
		}
		
		echo '</select>
        </div>
		';
	}
	else if ($cur_field == "req_date") {
		echo '<div class="form-group"><label class="control-label" for="req_date">Required Date:</label>
		<div class="input-group date pull-left">
		  <input type="text" class="form-control" name="req_date" ><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
		</div></div>';
	}
	else if ($cur_field == "category") {
		echo '<div class="form-group">
          <label class="control-label" for="category">Category:</label>
          <select class="form-control" id="category" name="category">
			<option value=""></option>';
        
		$query = 'SELECT name FROM  itrequest__category ORDER BY id ASC';
		$result = $db_link->query($query);
		while($row = $result->fetch_array()) {
			echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
		}
		
		echo '</select>
        </div>
		';
	}
	else if ($cur_field == "description") {
		echo '<div class="form-group">
			<label class="control-label" for="description">Description:</label>
			<textarea class="form-control" name="description" id="description_text" maxlength="255" lengthcut="true" rows="3" style="width: 100%; resize: none;" placeholder="eg. '.$examples[$cur_field].'"></textarea>
		</div>';
	}
	else {
		echo '<div class="form-group">
			<label class="control-label">'.$cur_title.'</label>
			<div>
				<input type="text" class="form-control" name="'.$cur_field.'" maxlength="32" placeholder="eg. '.$examples[$cur_field].'" ';
		if (!in_array($cur_field,$editable)) {
			echo 'disabled ';
		}
		echo '/>
			</div>
		</div>';
	}
	echo '</div>';
	if (($coloffset%2)==0||$coloffset==(count($fields)-2)) {
		echo '</div>';
	}
}
?>
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
$(document).ready(function() {
    $('#defaultForm').bootstrapValidator({
        message: 'This changes are not valid',
        fields: {
            name: {
                message: 'Please provide your name.',
                validators: {
                    notEmpty: {
                        message: 'Your \'name\' is required and can\'t be empty'
                    }
                }
            },
			phone_number: {
                message: 'Please provide your phone number.',
                validators: {
                    notEmpty: {
                        message: '\'Phone number\' is required and can\'t be empty'
                    }
                }
            },
			email: {
                message: 'Please provide your E-mail Address.',
                validators: {
                    notEmpty: {
                        message: '\'E-mail Address\' is required and can\'t be empty'
                    }
                }
            },
			department: {
                message: 'Please provide your department.',
                validators: {
                    notEmpty: {
                        message: '\'Department\' is required and can\'t be empty'
                    }
                }
            },
			building: {
                message: 'Please provide your building.',
                validators: {
                    notEmpty: {
                        message: '\'Building\' is required and can\'t be empty'
                    }
                }
            },
			room: {
                message: 'Please provide your room.',
                validators: {
                    notEmpty: {
                        message: '\'Room\' is required and can\'t be empty'
                    }
                }
            },
			severity: {
                message: 'Please provide your severity.',
                validators: {
                    notEmpty: {
                        message: '\'Severity\' is required and can\'t be empty'
                    }
                }
            },
			summary: {
                message: 'Please provide a summary of the isssue.',
                validators: {
                    notEmpty: {
                        message: '\'Summary\' is required and can\'t be empty'
                    }
                }
            }
        }
    });
});
</script>