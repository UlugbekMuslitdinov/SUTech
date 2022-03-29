<?php
	$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	include_once('header2.php');
	require_once("mysql/include.php");
	$db_link = select_db("access");
	//ini_set('display_errors', '1');
	//error_reporting(E_ALL);
	$fields = array("display_name" => "Display Name",
				"short_name" => "Short Name",
				"abbreviation" => "Abbreviation",
				"phone" => "Phone",
				"acct_num" => "Account Number",
				"foodpro_num" => "FoodPro Number");
	$examples = array("display_name" => "Cactus Grill",
				"short_name" => "Cactus",
				"abbreviation" => "CG",
				"phone" => "555-1234",
				"acct_num" => "163XXX0",
				"foodpro_num" => "XX");
	$editable = array("display_name", "phone", "acct_num", "foodpro_num");
?>
<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li><a href="/access/departments">Departments</a></li>
  <li class="active">Edit Department</li>
</ol>
<h1>Edit Department</h1>
<?php
	if ($_POST["submit"]=="submit") {
		if (intval($_POST["submit_id"])>0) {
			$query = 'UPDATE department SET ';
			foreach($editable as $cur_field) {
				$_POST[$cur_field] = strip_tags(mysql_real_escape_string(trim($_POST[$cur_field])));
				if (!isset($_POST[$cur_field])||$_POST[$cur_field]=="") {
					$query .= $cur_field.' = NULL,';
				}
				else {
					$query .= $cur_field.' = "'.$_POST[$cur_field].'",';
				}
			}
			$query = rtrim($query, ",");
			$query .= ' WHERE department.id="'.intval($_POST["submit_id"]).'"';
			$result = $db_link->query($query);
			if ($result) {
				echo '<div class="alert alert-success">Changes to '.strip_tags(mysql_real_escape_string(trim($_POST["display_name"]))).' were successful.</div>';
			}
			else {
				echo '<div class="alert alert-danger"><b>ERROR: </b>'.$db_link->error.'</div>';
			}
		}
		else {
			echo 'submitted without hidden value?';
			die;
		}
	}

	if (intval($_GET["dep_id"])>0) {
		$dep_id = intval($_GET["dep_id"]);
		$query = 'SELECT * FROM department WHERE id="'.$dep_id.'"';
		$result = $db_link->query($query);
		if ($result) {
			$cur_dep = $result->fetch_array();
		}
		else {
			echo 'ERROR: Unable to fetch current department.';
			die;
		}
?>
<div class="theme_form">
	<!-- form: -->
	<section>
		<form id="defaultForm" method="post" class="form-horizontal">
			<div class="col-md-4 col-sm-5">
<?php
foreach ($fields as $cur_field => $cur_title) {
	echo '<div class="form-group">
		<label class="control-label">'.$cur_title.'</label>
		<div>
			<input type="text" class="form-control" name="'.$cur_field.'" id="'.$cur_field.'" placeholder="eg. '.$examples[$cur_field].'" value="';
	if (isset($cur_dep[$cur_field])&&$cur_dep[$cur_field]!="") {
		echo $cur_dep[$cur_field];
	}
	echo '" ';
	if (!in_array($cur_field,$editable)) {
		echo 'disabled ';
	}
	echo '/>
		</div>
	</div>';
}
echo '<input type="hidden" name="submit_id" value="'.$dep_id.'" />';
?>
			</div>
			<div class="form-group col-xs-12">
				<div>
					<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit Changes</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="/access/departments"><button type="button" class="btn btn-default">Return to Department List</button></a>
				</div>
			</div>
		</form>
	</section>
	<!-- :form -->
</div>

<?php
}
else {
	echo '<div class="alert alert-danger"><b>ERROR:</b> Invalid Department ID.</div>';
}
include_once('footer2.php');
?>
<script type="text/javascript" src="/js/bootstrapValidator.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#defaultForm').bootstrapValidator({
        message: 'Changes are not valid',
        fields: {
            display_name: {
                validators: {
                    notEmpty: {
                        message: '\'Display Name\' is required and can\'t be empty'
                    }
                }
            },
			short_name: {
                validators: {
                    notEmpty: {
                        message: '\'Short name\' is required and can\'t be empty'
                    }
                }
            },
			abbreviation: {
                validators: {
                    notEmpty: {
                        message: '\'Abbreviation\' is required and can\'t be empty'
                    }
                }
            },
			phone: {
				validators: {
                    digits: {
                        message: '\'Phone Number\' can contain only digits'
                    }
                }
            },
			account_num: {
                validators: {
                    digits: {
                        message: '\'Account Number\' can contain only digits'
                    }
                }
            },
			foodpro_num: {
                validators: {
                    digits: {
                        message: '\'Foodpro Number\' can contain only digits'
                    }
                }
            }
        }
    });
});
</script>