<?php
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
function editUser(id) {
	document.getElementById('action_form').action = "edit_user.php";
	document.getElementById('user_id').value = id;
	document.getElementById('action_form').submit();
}
function deleteUser(id) {
	document.getElementById('action_form').action = "delete_user.php";
	document.getElementById('user_id').value = id;
	document.getElementById('action_form').submit();
}
</script>
<?php
require_once("mysql/include.php");
selectDB("sucs");
$result = mysql_query('SELECT *
		FROM users
		ORDER BY last_name')
	or die(mysql_error());
?>
<h1>Users &amp; Groups - Users</h1>
<div id="create_actions" style="float: left; width:100%;">
	<div style="float:right;">
		<a href="groups.php">
			<img src="/images/icons/group_edit.png" />Manage Groups
		</a>
	</div>
	<div style="float:right;margin-right:30px;">
		<a href="add_user.php">
			<img src="/images/icons/user_add.png" />Add User
		</a>
	</div>
</div>
<div style="clear:both;"></div>
<table width="100%" style="margin-top: 10px;">
	<tr>
		<td class="column_header">First Name</td>
		<td class="column_header">Last Name</td>
		<td class="column_header">NetID (email)</td>
		<td class="column_header action_column">Edit</td>
		<td class="column_header action_column">Delete</td>
	</tr>
<?php
while($cur_user = mysql_fetch_array($result)){
	echo '<tr>
		<td class="user_column">'.$cur_user['first_name'].'</td>
		<td class="user_column">'.$cur_user['last_name'].'</td>
		<td class="user_column"><a href="mailto:'.$cur_user['email'].'">'.$cur_user['netID'].'</a></td>
		<td class="user_column user_action">
			<img class="edit_permissions" src="/images/icons/user_edit.png"
			title="Edit User Information" alt="Edit User Information"
			onclick="editUser('.$cur_user['user_id'].');" style="cursor:pointer;" />
		</td>
		<td class="user_column user_action">
			<img class="delte_user" src="/images/icons/user_delete.png"
			title="Delete User" alt="Delete User"
			onclick="deleteUser('.$cur_user['user_id'].');" style="cursor:pointer;" />
		</td>
	</tr>';
}
?>
</table>
<form id="action_form" name="action_form" method="post" action="#">
	<input id="user_id" name="user_id" type="hidden" value="" />
</form>
<?php
        require_once("footer.php");
?>