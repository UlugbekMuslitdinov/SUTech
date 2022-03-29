<?php
	$webauth_script_override = "/site_users/index.php";
	$require_authorization = true;
	include("webauth/include.php");
	$submition_error = false;
	require_once("mysql/include.php");
	selectDB("sucs");
	if (isset($_POST['user_id'])) {
		if ($_POST['submit']=="Create Group") {
			$fields = array("name");
			foreach ($fields as $cur_field) {
				if (!isset($_POST[$cur_field]) || $_POST[$cur_field]=="") {
					$submition_error = true;
				}
			}
			if ($submition_error == false) {
				$result = mysql_query('INSERT INTO groups
						(name)
						VALUES ("'.mysql_real_escape_string($_POST["name"]).'")')
					or die(mysql_error());
			}
		}
		if ($_POST['cancel']=="Cancel" || ($_POST['submit']=="Create Group" && $submition_error == false)) {
			header('Location: /site_users/groups.php');
		}
	}
	require_once("header.php");
	require_once("sidebar.php");
?>
<style type="text/css">
#container img {
	padding:0;
}
</style>
<h1>Users &amp; Groups - Create Group</h1>
<?php
if ($_POST['submit']=="Create Group" && $submition_error == true) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMITION" />All fields are required.</div>';
}
?>
<form id="edit_form" name="edit_form" method="post" action="#">
	<table style="margin-top: 20px; margin-bottom: 10px;">
		<tr>
			<td>Group Name:</td>
			<td><input type="text" size="40" id="name" name="name" value="<? echo $cur_user['name']; ?>" /></td>
		</tr>
		</tr>
	</table>
	<?php echo '<input type="hidden" name="user_id" id="user_id" value="'.intval($_POST["user_id"]).'" />'; ?>
	<input type="submit" name="submit" id="submit" value="Create Group" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="cancel" id="cancel" value="Cancel" />
</form>
<?php
        require_once("footer.php");
?>