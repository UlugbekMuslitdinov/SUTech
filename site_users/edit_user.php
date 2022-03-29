<?php
	$submition_error = false;
	require_once("mysql/include.php");
	selectDB("sucs");
	if (isset($_POST['user_id'])) {
		if ($_POST['submit']=="Submit") {
			$fields = array("first_name", "last_name", "netID", "email");
			foreach ($fields as $cur_field) {
				if (!isset($_POST[$cur_field]) || $_POST[$cur_field]=="") {
					$submition_error = true;
				}
			}
			if ($submition_error == false) {
				$result = mysql_query('UPDATE users
						SET first_name = "'.mysql_real_escape_string($_POST["first_name"]).'",
						last_name = "'.mysql_real_escape_string($_POST["last_name"]).'",
						netID = "'.mysql_real_escape_string($_POST["netID"]).'",
						email = "'.mysql_real_escape_string($_POST["email"]).'"
						WHERE  user_id ='.intval($_POST["user_id"]))
					or die(mysql_error());
			}
		}
		if ($_POST['cancel']=="Cancel" || ($_POST['submit']=="Submit" && $submition_error == false)) {
			header('Location: /site_users/index.php');
		}
		$result = mysql_query('SELECT *
				FROM users
				WHERE user_id = '.intval($_POST["user_id"]))
			or die(mysql_error());
		$cur_user = mysql_fetch_array($result);
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
<h1>Users &amp; Groups - Edit User Information</h1>
<?php
if ($_POST['submit']=="Submit" && $submition_error == true) {
	echo '<div class="dialog_invalid"><img src="/images/icons/error.png" alt="INVALID SUBMITION" />All fields are required.</div>';
}
if (!isset($_POST['user_id'])) {
	echo '<div class="dialog_error"><img src="/images/icons/exclamation.png" alt="ERROR" />An unexpected error occured.</div>';
}
?>
<form id="edit_form" name="edit_form" method="post" action="#">
	<table style="margin-top: 20px; margin-bottom: 10px;">
		<tr>
			<td>First Name:</td>
			<td><input type="text" size="40" id="first_name" name="first_name" value="<? echo $cur_user['first_name']; ?>" /></td>
		</tr>
		<tr>
			<td>Last Name:</td>
			<td><input type="text" size="40" id="last_name" name="last_name" value="<? echo $cur_user['last_name']; ?>" /></td>
		</tr>
		<tr>
			<td>NetID:</td>
			<td><input type="text" size="40" id="netID" name="netID" value="<? echo $cur_user['netID']; ?>" /></td>
		</tr>
		<tr>
			<td>E-Mail Address:</td>
			<td><input type="text" size="40" id="email" name="email" value="<? echo $cur_user['email']; ?>" /></td>
		</tr>
	</table>
	<?php echo '<input type="hidden" name="user_id" id="user_id" value="'.intval($_POST["user_id"]).'" />'; ?>
	<input type="submit" name="submit" id="submit" value="Submit" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="cancel" id="cancel" value="Cancel" />
</form>
<?php
        require_once("footer.php");
?>