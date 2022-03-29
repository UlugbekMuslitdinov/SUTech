<?php
	if ($_POST['no']=="No") {
		header('Location: /site_users/groups.php');
	}
	require_once("mysql/include.php");
	selectDB("sucs");
	if (isset($_POST['group_id']) && $_POST['yes']=="Yes") {
		$result = mysql_query('DELETE FROM groups
				WHERE group_id = "'.intval($_POST["group_id"]).'"')
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		header('Location: /site_users/groups.php');
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
$result = mysql_query('SELECT *
		FROM groups
		WHERE group_id = "'.intval($_POST["group_id"]).'"')
	or die(mysql_error());
	$cur_group = mysql_fetch_array($result);
?>
<h1>Users &amp; Groups - Delete User</h1>
<?php
if (!isset($_POST['group_id'])) {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occured.</div>';
}
?>
<form id="edit_form" name="edit_form" method="post" action="#">
	Are you sure you want to the delete "<? echo $cur_group['name']; ?>"?<br />

	<br /><br />
	<?php echo '<input type="hidden" name="group_id" id="group_id" value="'.intval($_POST["group_id"]).'" />'; ?>
	<input type="submit" name="no" id="no" value="No" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="yes" id="yes" value="Yes" />
</form>
<?php
        require_once("footer.php");
?>
