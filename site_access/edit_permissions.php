<?php
	if ($_POST['cancel']=="Cancel") {
		header('Location: /site_access/index.php');
	}
	if (!isset($_POST['resource_id']) && intval($_GET['resource_id'])>0) {
		$_POST['resource_id'] = intval($_GET['resource_id']);
	}
	$webauth_script_override = "/site_access/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
?>
<script type="text/javascript">
function removeUser(id) {
	document.getElementById('remove_user_form').action = "remove_userpermission.php";
	document.getElementById('remove_user_id').value = id;
	document.getElementById('remove_user_form').submit();
}
function removeGroup(id) {
	document.getElementById('remove_group_form').action = "remove_grouppermission.php";
	document.getElementById('remove_group_id').value = id;
	document.getElementById('remove_group_form').submit();
}
</script>
<?php
require_once("mysql/include.php");
selectDB("sucs");
?>
<h1>Access Control - Edit Permissions</h1>
<div style="margin-top:30px;"><h2>Users With Access</h2></div>
<table width="100%" style="margin-top: 10px;">
	<tr>
		<td class="column_header">First Name</td>
		<td class="column_header">Last Name</td>
		<td class="column_header">NetID (email)</td>
		<td class="column_header action_column">Remove</td>
	</tr>
<?php
$result = mysql_query('SELECT *
		FROM users, permissions, resources
		WHERE users.user_id = permissions.user_id
		AND permissions.resource_id = resources.resource_id
		AND resources.resource_id = "'.intval($_POST["resource_id"]).'"
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
			onclick="removeUser('.$cur_user['user_id'].');" style="cursor:pointer;" />
		</td>
	</tr>';
}
?>
<form id="remove_user_form" name="remove_user_form" method="post" action="#">
	<input id="remove_user_id" name="remove_user_id" type="hidden" value="" />
	<input id="resource_id" name="resource_id" type="hidden" value="<?php echo $_POST['resource_id']; ?>" />
</form>
</table>
<div style="margin-top:30px;"><h2>Grant Exisiting User Access</h2></div>
<form id="add_user_form" name="add_user_form" method="post" action="add_userpermission.php">
	<table style="margin-top: 20px; margin-bottom: 10px;">
		<tr>
			<td>NetID:</td>
			<td><input type="text" size="40" id="netID" name="netID" value="" /></td>
		</tr>
	</table>
	<?php echo '<input type="hidden" name="resource_id" id="resource_id" value="'.intval($_POST["resource_id"]).'" />'; ?>
	<input type="submit" name="submit" id="submit" value="Add User" />
</form>

<div style="margin-top:30px;"><h2>Groups With Access</h2></div>
<table width="100%" style="margin-top: 10px;">
	<tr>
		<td class="column_header">Name</td>
		<td class="column_header action_column">Remove</td>
	</tr>
<?php
$result = mysql_query('SELECT groups.name, groups.group_id
		FROM groups, permissions, resources
		WHERE groups.group_id = permissions.group_id
		AND permissions.resource_id = resources.resource_id
		AND resources.resource_id = "'.intval($_POST["resource_id"]).'"
		ORDER BY groups.name')
	or die(mysql_error());
while($cur_group = mysql_fetch_array($result)){
	echo '<tr>
		<td class="group_column">'.$cur_group['name'].'</td>
		<td class="group_column group_action">
			<img class="delte_group" src="/images/icons/group_delete.png"
			title="Remove Group" alt="Remove Group"
			onclick="removeGroup('.$cur_group['group_id'].');" style="cursor:pointer;" />
		</td>
	</tr>';
}
?>
<form id="remove_group_form" name="remove_group_form" method="post" action="#">
	<input id="remove_group_id" name="remove_group_id" type="hidden" value="" />
	<input id="resource_id" name="resource_id" type="hidden" value="<?php echo $_POST['resource_id']; ?>" />
</form>
</table>
<div style="margin-top:30px;"><h2>Grant Exisiting Group Access</h2></div>
<form id="add_group_form" name="add_group_form" method="post" action="add_grouppermission.php">
	<table style="margin-top: 20px; margin-bottom: 10px;">
		<tr>
			<td>Group Name:</td>
			<td><input type="text" size="40" id="name" name="name" value="" /></td>
		</tr>
	</table>
	<?php echo '<input type="hidden" name="resource_id" id="resource_id" value="'.intval($_POST["resource_id"]).'" />'; ?>
	<input type="submit" name="submit" id="submit" value="Add Group" />
</form>
<?php
        require_once("footer.php");
?>
