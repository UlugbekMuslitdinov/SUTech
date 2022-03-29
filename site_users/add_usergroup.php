<?php
	$submition_error = false;
	require_once("mysql/include.php");
	selectDB("sucs");
	$webauth_script_override = "/site_users/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	if($_POST['submit']=="Add User" ) {
		$result = mysql_query('SELECT user_id
				FROM users
				WHERE netID = "'.mysql_real_escape_string($_POST["netID"]).'"')
			or die(mysql_error());
		$user_id = mysql_fetch_array($result);
		$user_id = $user_id['user_id'];
		if ($user_id > 0) {
			$result = mysql_query('INSERT INTO memberships
					(user_id, group_id)
					VALUES ("'.$user_id.'",
						"'.intval($_POST["group_id"]).'")')
				or die(mysql_error());
		}
		header('Location: /site_users/edit_group.php?group_id='.$_POST["group_id"]);
	}
	else {
		$submition_error = true;
	}
	require_once("header.php");
	require_once("sidebar.php");
?>
<style type="text/css">
#container img {
	padding:0;
}
</style>
<h1>Users &amp; Groups - Add User</h1>
<?php
if ($_POST['submit']=="Add User" && $submition_error == true) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMITION" />All fields are required.</div>';
}
        require_once("footer.php");
?>