<?php
	$webauth_script_override = "/site_access/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	require_once("mysql/include.php");
	selectDB("sucs");
	if($_POST['submit']=="Add Group" && isset($_POST['resource_id'])) {
		$result = mysql_query('SELECT group_id
				FROM groups
				WHERE name = "'.mysql_real_escape_string($_POST["name"]).'"')
			or die(mysql_error());
		$group_id = mysql_fetch_array($result);
		$group_id = $group_id['group_id'];
		if ($group_id > 0) {
			$result = mysql_query('INSERT INTO permissions
					(group_id, resource_id)
					VALUES ("'.$group_id.'",
						"'.intval($_POST["resource_id"]).'")')
				or die(mysql_error());
		}
		header('Location: /site_access/edit_permissions.php?resource_id='.$_POST["resource_id"]);
	}
?>