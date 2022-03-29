<?php
	require_once("mysql/include.php");
    $db_link = select_db("access");

if (isset($_POST["month"])&&$_POST["month"]!="none"&&$_POST["function"]=="Download CSV") {
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment;filename="birthdays_download.csv"');
	header('Cache-Control: max-age=0');

	$out = fopen('php://output', 'w');

	$query = 'SELECT first_name, last_name, DATE_FORMAT(date_of_birth,"%b-%e") as dob FROM import__kronos WHERE MONTH(date_of_birth)="'.$_POST["month"].'" ORDER BY MONTH(date_of_birth), DAYOFMONTH(date_of_birth)';
	$result = $db_link->query($query);
	while($results = $result->fetch_array()) {
		$row = array(
			$results['first_name'].' '.$results['last_name'],
			$results['dob']
    	);
		fputcsv($out, $row, ',', '"');
	}

	fclose($out);
	die;exit;
}
	//$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	require_once("header.php");
    require_once("sidebar.php");
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

?>

	<div style="float:right;width:200px;margin-top:20px;background:#F7F7F1;border:1px solid #D2D2C4;">
        <div style="font-size:18px;font-weight:bold;color:#FFF;background:#AB051F;width:196px;padding:2px;">SU Access Pages</div>
        <div style="margin-left:15px;margin-top:10px;margin-bottom:10px;">
        <ul>
        	<li><a href="/access">SU Access Home</a></li>
        	<li><a href="/access/import.php">Run Kronos CSV Import</a></li>
        	<li><a href="/access/sync.php">Sync Import > Access DB</a></li>
        	<li><a href="/access/search.php">EDS User Lookup</a></li>
        	<li><a href="/access/birthdays.php">Birthdays by Month</a></li>
        	<li><a href="/access/devices.php">Inventoried Devices</a></li>
        	<li><a href="/access/building.php">Building Access</a></li>
        	<li><a href="/access/systems.php">Systems Access</a></li>
       	</ul>
        </div>
	</div>

	<h1>Birthdays by Month</h1><br />

	<form name="birthdays" action="#" method="POST">
		Choose a month: <select name="month" onchange="this.form.submit();">
			<option name="none" value="none" ></option>
<?php
$months = array(
	1 => "January",
	2 => "February",
	3 => "March",
	4 => "April",
	5 => "May",
	6 => "June",
	7 => "July",
	8 => "August",
	9 => "September",
	10 => "October",
	11 => "November",
	12 => "December"
);
foreach($months as $month_num => $month) {
	echo '<option name="'.$month.'" value="'.$month_num.'"';
	if ($month_num == $_POST["month"]) {
		echo ' selected="true"';
	}
	echo ' >'.$month.'</option>';
}
?>
		</select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="function" value="Download CSV">
		<br /><br />
    </form>
<?php
	if (!$_POST["month"]||$_POST["month"]=="none") {
		echo '<br />Select a month from the dropdown.';
	}
	else {
		$query = 'SELECT first_name, last_name, date_of_birth FROM import__kronos WHERE MONTH(date_of_birth)="'.$_POST["month"].'" ORDER BY MONTH(date_of_birth), DAYOFMONTH(date_of_birth)';
		$result = $db_link->query($query);
		echo '<table>';
		while($row = $result->fetch_array()) {
			echo '<tr><td>'.$row["first_name"].' '.$row["last_name"].'</td><td>'.date('M-j',strtotime($row["date_of_birth"])).'</td></tr>';
		}
		echo '</table>';

	}
	require_once("footer.php");
?>