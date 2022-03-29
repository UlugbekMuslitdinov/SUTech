<?php
	$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("access");
	
	if ($_POST["submit"]=="submit") {
		if (intval($_POST["submit_id"])>0) {
			echo 'Sorry, hit back button. Functionality not implemented yet.';
			die;
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
<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li><a href="/access/departments">Departments</a></li>
  <li class="active">Edit Department</li>
</ol>
<h1>Edit Department</h1>
<div class="theme_form">
	<!-- form: -->
	<section>
		<form id="defaultForm" method="post" class="form-horizontal">
			<div class="col-md-4 col-sm-5">
<?php
$fields = array("display_name" => "Display Name",
				"short_name" => "Short Name",
				"abbreviation" => "Abbreviation");
$examples = array("display_name" => "Cactus Grill",
				"short_name" => "Cactus",
				"abbreviation" => "CG");
$editable = array("display_name");
foreach ($fields as $cur_field => $cur_title) {
	echo '<div class="form-group">
		<label class="control-label">'.$cur_title.'</label>
		<div>
			<input type="text" class="form-control" name="'.$cur_field.'" placeholder="eg. '.$examples[$cur_field].'" value="'.$cur_dep[$cur_field].'" ';
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
        message: 'This changes are not valid',
        fields: {
            display_name: {
                message: 'The display name is not valid',
                validators: {
                    notEmpty: {
                        message: '\'Display Name\' is required and can\'t be empty'
                    }
                }
            },
			short_name: {
                message: 'The short name is not valid',
                validators: {
                    notEmpty: {
                        message: '\'Short name\' is required and can\'t be empty'
                    }
                }
            },
			abbreviation: {
                message: 'The abbreviation is not valid',
                validators: {
                    notEmpty: {
                        message: '\'Abbreviation\' is required and can\'t be empty'
                    }
                }
            }
        }
    });
});
</script>