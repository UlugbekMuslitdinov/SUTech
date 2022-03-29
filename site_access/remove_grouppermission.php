<?php
	$webauth_script_override = "/site_access/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	if ($_POST['no']=="No") {
		header('Location: /access/index.php');
	}
	require_once("mysql/include.php");
	selectDB("sucs");
	if (isset($_POST['resource_id']) && isset($_POST['remove_group_id'])) {
		$result = mysql_query('DELETE FROM permissions
				WHERE resource_id = "'.intval($_POST["resource_id"]).'"
				AND group_id = '.intval($_POST["remove_group_id"]))
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		header('Location: /site_access/edit_permissions.php?resource_id='.intval($_POST["resource_id"]));
	}
?>