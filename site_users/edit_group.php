<?php
	$submition_error = false;
	require_once("mysql/include.php");
	selectDB("sucs");
	if (!isset($_POST['group_id']) && intval($_GET['group_id'])>0) {
		$_POST['group_id'] = intval($_GET['group_id']);
	}
	if (isset($_POST['group_id'])) {
		if ($_POST['submit']=="Change Name") {
			$fields = array("name");
			foreach ($fields as $cur_field) {
				if (!isset($_POST[$cur_field]) || $_POST[$cur_field]=="") {
					$submition_error = true;
				}
			}
			if ($submition_error == false) {
				$result = mysql_query('UPDATE groups
						SET name = "'.mysql_real_escape_string($_POST["name"]).'"
						WHERE  group_id ='.intval($_POST["group_id"]))
					or die(mysql_error());
			}
		}
		if ($_POST['cancel']=="Cancel" || ($_POST['submit']=="Change Name" && $submition_error == false)) {
			header('Location: /site_users/groups.php');
		}
		$result = mysql_query('SELECT *
				FROM groups
				WHERE group_id = '.intval($_POST["group_id"]))
			or die(mysql_error());
		$cur_user = mysql_fetch_array($result);
	}
	$webauth_script_override = "/site_users/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
?>
<script type="text/javascript">
function removeUser(user,group) {
	document.getElementById('remove_user_form').action = "remove_usergroup.php";
	document.getElementById('remove_user_id').value = user;
	document.getElementById('remove_group_id').value = group;
	document.getElementById('remove_user_form').submit();
}
</script>
<h1>Users &amp; Groups - Edit Group</h1>
<?php
if ($_POST['submit']=="Change Name" && $submition_error == true) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMITION" />All fields are required.</div>';
}
if (!isset($_POST['group_id'])) {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occured.</div>';
}
?>
<form id="edit_form" name="edit_form" method="post" action="#">
	<table style="margin-top: 20px; margin-bottom: 10px;">
		<tr>
			<td>Group Name:</td>
			<td><input type="text" size="40" id="name" name="name" value="<? echo $cur_user['name']; ?>" /></td>
		</tr>
	</table>
	<?php echo '<input type="hidden" name="group_id" id="group_id" value="'.intval($_POST["group_id"]).'" />'; ?>
	<input type="submit" name="submit" id="submit" value="Change Name" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="cancel" id="cancel" value="Cancel" />
</form>

<div style="clear:both;"></div>
<div style="margin-top:30px;"><h2>Members</h2></div>
<table width="100%" style="margin-top: 10px;">
	<tr>
		<td class="column_header">First Name</td>
		<td class="column_header">Last Name</td>
		<td class="column_header">NetID (email)</td>
		<td class="column_header action_column">Remove</td>
	</tr>
<?php
$result = mysql_query('SELECT *
		FROM users, groups, memberships
		WHERE users.user_id = memberships.user_id
		ANd groups.group_id = memberships.group_id
		AND memberships.group_id = '.intval($_POST["group_id"]).'
		ORDER BY last_name')
	or die(mysql_error());
while($cur_user = mysql_fetch_array($result)){
	echo '<tr>
		<td class="user_column">'.$cur_user['first_name'].'</td>
		<td class="user_column">'.$cur_user['last_name'].'</td>
		<td class="user_column"><a href="mailto:'.$cur_user['email'].'">'.$cur_user['netID'].'</a></td>
		<td class="user_column user_action">
			<img class="delte_user" src="/images/icons/user_delete.png"
			title="Remove User" alt="Remove User"
			onclick="removeUser('.$cur_user['user_id'].','.$cur_user['group_id'].');" style="cursor:pointer;" />
		</td>
	</tr>';
}
?>
<form id="remove_user_form" name="remove_user_form" method="post" action="#">
	<input id="remove_user_id" name="remove_user_id" type="hidden" value="" />
	<input id="remove_group_id" name="remove_group_id" type="hidden" value="" />
</form>
</table>

<div style="margin-top:30px;"><h2>Add Exisiting User</h2></div>
<form id="add_form" name="add_form" method="post" action="add_usergroup.php">
	<table style="margin-top: 20px; margin-bottom: 10px;">
		<tr>
			<td>NetID:</td>
			<td><input type="text" size="40" id="netID" name="netID" value="" /></td>
		</tr>
	</table>
	<?php echo '<input type="hidden" name="group_id" id="group_id" value="'.intval($_POST["group_id"]).'" />'; ?>
	<input type="submit" name="submit" id="submit" value="Add User" />
</form>
<?php
        require_once("footer.php");
?>