<?php
	$require_authorization = true;
	$webauth_script_override = "/site_users/index.php";
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
?>
<style type="text/css">
.user_action {
	padding:0;
	text-align:center;
	padding-top:2px;
}
#create_actions img {
	vertical-align:text-top;
	margin-right:5px;
}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
function editGroup(id) {
	document.getElementById('action_form').action = "edit_group.php";
	document.getElementById('group_id').value = id;
	document.getElementById('action_form').submit();
}
function deleteGroup(id) {
	document.getElementById('action_form').action = "delete_group.php";
	document.getElementById('group_id').value = id;
	document.getElementById('action_form').submit();
}
</script>
<?php
require_once("mysql/include.php");
selectDB("sucs");
$result = mysql_query('SELECT *
		FROM groups
		ORDER BY name')
	or die(mysql_error());
?>
<h1>Users &amp; Groups - Groups</h1>
<div id="create_actions" style="float: left; width:100%;">
	<div style="float:right;">
		<a href="index.php">
			<img src="/images/icons/user_edit.png" />Manage Users
		</a>
	</div>
	<div style="float:right;margin-right:30px;">
		<a href="create_group.php">
			<img src="/images/icons/group_add.png" />Create Group
		</a>
	</div>
</div>
<div style="clear:both;"></div>
<table width="100%" style="margin-top: 10px;">
	<tr>
		<td class="column_header">Group Name</td>
		<td class="column_header action_column">Edit</td>
		<td class="column_header action_column">Delete</td>
	</tr>
<?php
while($cur_group = mysql_fetch_array($result)){
	echo '<tr>
		<td class="user_column">'.$cur_group['name'].'</td>
		<td class="user_column user_action">
			<img class="edit_groups" src="/images/icons/group_edit.png"
			title="Edit Group" alt="Edit Group"
			onclick="editGroup('.$cur_group['group_id'].');" style="cursor:pointer;" />
		</td>
		<td class="user_column user_action">
			<img class="delte_user" src="/images/icons/group_delete.png"
			title="Delete Group" alt="Delete Group"
			onclick="deleteGroup('.$cur_group['group_id'].');" style="cursor:pointer;" />
		</td>
	</tr>';
}
?>
</table>
<form id="action_form" name="action_form" method="post" action="#">
	<input id="group_id" name="group_id" type="hidden" value="" />
</form>
<?php
        require_once("footer.php");
?>