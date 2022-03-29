<?php
	if ($_POST['no']=="No") {
		header('Location: /site_users/index.php');
	}
	require_once("mysql/include.php");
	selectDB("sucs");
	if (isset($_POST['user_id']) && $_POST['yes']=="Yes") {
		$result = mysql_query('DELETE FROM users
				WHERE user_id = "'.intval($_POST["user_id"]).'"')
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		header('Location: /site_users/index.php');
	}
	$webauth_script_override = "/site_users/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
?>
<style type="text/css">
#container img {
	padding:0;
}
</style>
<?php
$result = mysql_query('SELECT user_id, first_name, last_name
		FROM users
		WHERE user_id = "'.intval($_POST["user_id"]).'"')
	or die(mysql_error());
	$cur_user = mysql_fetch_array($result);
?>
<h1>Users &amp; Groups - Delete User</h1>
<?php
if (!isset($_POST['user_id'])) {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occured.</div>';
}
?>
<form id="edit_form" name="edit_form" method="post" action="#">
	Are you sure you want to the delete "<? echo $cur_user['first_name'].' '.$cur_user['last_name']; ?>"?<br />

	<br /><br />
	<?php echo '<input type="hidden" name="user_id" id="user_id" value="'.intval($_POST["user_id"]).'" />'; ?>
	<input type="submit" name="no" id="no" value="No" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="yes" id="yes" value="Yes" />
</form>
<?php
        require_once("footer.php");
?>
