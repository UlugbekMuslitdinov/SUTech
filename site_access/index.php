<?php
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");
?>
<style type="text/css">
.resource_action {
	padding:0;
	text-align:center;
	padding-top:2px;
}
.ui-widget {
	font-size: 11px !important;
	font-family: 'Lucida Grande', Lucida, Verdana, sans-serif !important;
}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
function editResource(id) {
	document.getElementById('action_form').action = "edit_resource.php";
	document.getElementById('resource_id').value = id;
	document.getElementById('action_form').submit();
}
function editPermissions(id) {
	document.getElementById('action_form').action = "edit_permissions.php";
	document.getElementById('resource_id').value = id;
	document.getElementById('action_form').submit();
}
function delteResource(id) {
	document.getElementById('action_form').action = "delete_resource.php";
	document.getElementById('resource_id').value = id;
	document.getElementById('action_form').submit();
}
</script>
<?php
require_once("mysql/include.php");
selectDB("sucs");
$result = mysql_query('SELECT resource_id, name, script
		FROM resources
		ORDER BY name')
	or die(mysql_error());
?>
<h1>Access Control</h1>
<div id="create_actions" style="float: left; width:100%;">
	<div style="float:right;">
		<a href="add_resource.php">
			<img src="/images/icons/page_add.png" />Add Resource
		</a>
	</div>
</div>
<div style="clear:both;"></div>
<table width="100%" style="margin-top: 10px;">
	<tr>
		<td class="column_header">Page Name</td>
		<td class="column_header">Page Location</td>
		<td class="column_header action_column">Edit</td>
		<td class="column_header action_column">Perm.</td>
		<td class="column_header action_column">Delete</td>
	</tr>
<?php
while($cur_resource = mysql_fetch_array($result)){
	echo '<tr>
		<td class="resource_column">'.$cur_resource['name'].'</td>
		<td class="resource_column"><a href="'.$cur_resource['script'].'">'.$cur_resource['script'].'</a></td>
		<td class="resource_column resource_action">
			<img class="edit_resource" src="/images/icons/page_edit.png"
			title="Edit Resource Information" alt="Edit Resource Information"
			onclick="editResource('.$cur_resource['resource_id'].');" style="cursor:pointer;" />
		</td>
		<td class="resource_column resource_action">
			<img class="edit_permissions" src="/images/icons/page_key.png"
			title="Edit User Permissions" alt="Edit User Permissions"
			onclick="editPermissions('.$cur_resource['resource_id'].');" style="cursor:pointer;" />
		</td>
		<td class="resource_column resource_action">
			<img class="edit_permissions" src="/images/icons/page_delete.png"
			title="Delete Resource Information & Permissions" alt="Delete Resource Information & Permissions"
			onclick="delteResource('.$cur_resource['resource_id'].');" style="cursor:pointer;" />
		</td>
	</tr>';
}
?>
</table>
<form id="action_form" name="action_form" method="post" action="#">
	<input id="resource_id" name="resource_id" type="hidden" value="" />
</form>
<?php
        require_once("footer.php");
?>
