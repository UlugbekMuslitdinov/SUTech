<?php
	$submition_error = false;
	require_once("mysql/include.php");
	selectDB("sucs");
		if ($_POST['submit']=="Submit") {
			$fields = array("name", "script");
			foreach ($fields as $cur_field) {
				if (!isset($_POST[$cur_field]) || $_POST[$cur_field]=="") {
					$submition_error = true;
				}
			}
			if ($submition_error == false) {
				$result = mysql_query('INSERT INTO resources
						(name, script)
						VALUES ("'.mysql_real_escape_string($_POST["name"]).'",
						"'.mysql_real_escape_string($_POST["script"]).'")')
					or die(mysql_error());
			}
		}
		if ($_POST['cancel']=="Cancel" || ($_POST['submit']=="Submit" && $submition_error == false)) {
			header('Location: /site_access/index.php');
		}
		$result = mysql_query('SELECT resource_id, name, script
				FROM resources
				WHERE resource_id = '.intval($_POST["resource_id"]))
			or die(mysql_error());
		$cur_resource = mysql_fetch_array($result);
	$webauth_script_override = "/site_access/index.php";
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
<h1>Access Control - Add Resource</h1>
<?php
if ($_POST['submit']=="Submit" && $submition_error == true) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMITION" />All fields are required.</div>';
}
?>
<form id="edit_form" name="edit_form" method="post" action="#">
	<table style="margin-top: 20px; margin-bottom: 10px;">
		<tr>
			<td>Name:</td>
			<td><input type="text" size="40" id="name" name="name" value="<? echo $cur_resource['name']; ?>" /></td>
		</tr>
		<tr>
			<td>Script Location:</td>
			<td><input type="text" size="40" id="script" name="script" value="<? echo $cur_resource['script']; ?>" /></td>
		</tr>
	</table>
	<input type="submit" name="submit" id="submit" value="Submit" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="cancel" id="cancel" value="Cancel" />
</form>
<?php
        require_once("footer.php");
?>