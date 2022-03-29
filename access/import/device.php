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
  <li class="active">Manually Add Device</li>
</ol>
<h1>Manually Add Device</h1>

<?php
	require_once("footer2.php");
?>