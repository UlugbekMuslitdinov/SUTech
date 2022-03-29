<?php
	$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("access");
?>

<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li class="active">Kronos CSV Import</li>
</ol>
<h1>Kronos CSV Import</h1>

<?php
	ini_set('auto_detect_line_endings',TRUE);
	$file = "/mnt/krontr_webintra/KronosEmplData.csv";
	if (file_exists($file)) {
		$handle = fopen($file,'r');
	
		$total_changed = 0;
	
		$query = 'TRUNCATE TABLE import__kronos';
		$result = $db_link->query($query);
		echo mysqli_error($db_link);
	
		while ( ($data = fgetcsv($handle) ) !== FALSE ) {
			$query = "INSERT INTO import__kronos (emplid, first_name, last_name,
			kfs, pcn, email, phone_personal, phone_work, netid, date_of_birth,
			emp_category, sup_emplid, sup_name) VALUES (";
			$cur_field=0;
			foreach ($data as $newfield) {
				if ($cur_field==9) {
					$query .= 'STR_TO_DATE("'.$newfield.'", "%m/%d/%Y"), ';
				}
				else if ($cur_field<12) {
					$query .= '"'.$newfield.'", ';
				}
				else {
					$query .= '"'.$newfield.'")';
				}
				$cur_field++;
			}
			$result = $db_link->query($query);
			if ($result) {
				$total_changed++;
			}
			else {
				var_dump($query);
				echo '<div class="alert alert-danger">'.mysqli_error($db_link).'</div>';
			}
		}
		ini_set('auto_detect_line_endings',FALSE);
		fclose($handle);
	}
	else {
		echo '<div class="alert alert-danger"><b>ERROR:</b> Kronos Import File Does Not Exist<br />
		<i>(Check smb/nmb services and verify krontr_webintra mounted)</i></div>';
	}
?>
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Import Completed</div>
  <div class="panel-body">
    Total number of records import from CSV file: <?=$total_changed;?>
  </div>
</div>
<br />
<a href="/access/sync/users"><button type="button" class="btn btn-success">Sync Import > Access DB</button></a>
<?php
	require_once("footer2.php");
?>