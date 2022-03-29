<?php
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");
?>

<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li class="active">Access</li>
</ol>
<h1>Access Portal</h1>

<div class="list-group">
  <a href="/access/search" class="list-group-item"><i class="fa fa-search"></i> EDS User Lookup</a>
  <a href="/access/birthdays" class="list-group-item"><i class="fa fa-star"></i> Birthdays by Month</a>
  <a href="/access/departments" class="list-group-item"><i class="fa fa-tags"></i> Departments</a>
<!--  <a href="/access/users" class="list-group-item"><i class="fa fa-users"></i> Users</a>-->
</div>

<?php
include_once('footer2.php');
?>
