<?php
	$webauth_script_override = "/site_access/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	require_once("mysql/include.php");
	selectDB("sucs");
	if($_POST['submit']=="Add User" && isset($_POST['resource_id'])) {
		$result = mysql_query('SELECT user_id
				FROM users
				WHERE netID = "'.mysql_real_escape_string($_POST["netID"]).'"')
			or die(mysql_error());
		$user_id = mysql_fetch_array($result);
		$user_id = $user_id['user_id'];
		if ($user_id > 0) {
			$result = mysql_query('INSERT INTO permissions
					(user_id, resource_id)
					VALUES ("'.$user_id.'",
						"'.intval($_POST["resource_id"]).'")')
				or die(mysql_error());
		}
		header('Location: /site_access/edit_permissions.php?resource_id='.$_POST["resource_id"]);
	}
?>