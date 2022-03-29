<?php
	if ($_POST['no']=="No") {
		header('Location: /site_users/edit_group.php?group_id='.$_POST["remove_group_id"]);
	}
	require_once("mysql/include.php");
	selectDB("sucs");
	if (isset($_POST['remove_user_id']) && isset($_POST['remove_group_id']) && $_POST['yes']=="Yes") {
		$result = mysql_query('DELETE FROM memberships
				WHERE user_id = "'.intval($_POST["remove_user_id"]).'"
				AND group_id = "'.intval($_POST["remove_group_id"]).'"')
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		header('Location: /site_users/edit_group.php?group_id='.$_POST["remove_group_id"]);
	}
	$webauth_script_override = "/site_users/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
?>
<?php
$result = mysql_query('SELECT user_id, first_name, last_name
		FROM users
		WHERE user_id = "'.intval($_POST["remove_user_id"]).'"')
	or die(mysql_error());
$cur_user = mysql_fetch_array($result);
$result = mysql_query('SELECT *
		FROM groups
		WHERE group_id = "'.intval($_POST["remove_group_id"]).'"')
	or die(mysql_error());
$cur_group = mysql_fetch_array($result);
?>
<h1>Users &amp; Groups - Remove User From Group</h1>
<?php
if (!isset($_POST['remove_user_id']) || !isset($_POST['remove_group_id'])) {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occured.</div>';
}
?>
<form id="edit_form" name="edit_form" method="post" action="#">
	Are you sure you want to the delete "<? echo $cur_user['first_name'].' '.$cur_user['last_name']; ?>", from the group "<? echo $cur_group['name']; ?>"?<br />

	<br /><br />
	<?php echo '<input type="hidden" name="remove_user_id" id="remove_user_id" value="'.intval($_POST["remove_user_id"]).'" />'; ?>
	<?php echo '<input type="hidden" name="remove_group_id" id="remove_group_id" value="'.intval($_POST["remove_group_id"]).'" />'; ?>
	<input type="submit" name="no" id="no" value="No" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="yes" id="yes" value="Yes" />
</form>
<?php
        require_once("footer.php");
?>
