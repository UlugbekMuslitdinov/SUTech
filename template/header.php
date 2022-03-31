<?php
	session_start();
	//$_SESSION['webauth']['netID'] = "kmbeyer";
	//session_destroy();
	if($_SERVER['HTTPS']!="on")
	{
		$redirect= "https://tech.union.arizona.edu/". "/" . $_SERVER['REQUEST_URI'];
		// $redirect= "https://pearl.sunion.arizona.edu".$_SERVER['REQUEST_URI'];
		//header("Location:$redirect");
	}

	$deny = $require_authorization;
	$_SESSION["sucs_authorized"]=false;
	if ($_POST['action']=="logout"||strval($_GET["action"])=="logout") {
		session_destroy();
		//header("Location: https://webauth.arizona.edu/webauth/logout?logout_href=https://pearl.sunion.arizona.edu/strap&logout_text=Return%20To%20Student%20Unions%20Computer%20Support");
		header("Location: /template/webauth/undisguise.php?logout_href=".$_SERVER["PHP_SELF"]);
	}
	else if ($_POST['action']=="login"||strval($_GET["action"])=="login") {
		include_once("webauth/include.php");
	}
	$script = $_SERVER["SCRIPT_NAME"];
	if (isset($webauth_script_override) && $webauth_script_override!="") {
		$script = $webauth_script_override;
	}
	if (isset($_SESSION['webauth']['netID']) && $require_authorization==true) {
		require_once("mysql/include.php");
		selectDB("sucs");
		$result = mysql_query('SELECT users.netID, users.user_id, COUNT( permission_id ) AS access_records
                FROM users, permissions, resources
                WHERE users.user_id = permissions.user_id
                AND permissions.resource_id = resources.resource_id
				AND resources.script = "'.$script.'"
            	AND users.netID = "'.$_SESSION['webauth']['netID'].'"')
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		$records = $result["access_records"];
		$result = mysql_query('SELECT users.netID, users.user_id
				FROM users
				WHERE users.netID = "'.$_SESSION['webauth']['netID'].'"')
		or die(mysql_error());
		$result = mysql_fetch_array($result);
		$user_id = $result["user_id"];
		$result = mysql_query('SELECT COUNT( permission_id ) AS access_records
                FROM memberships, permissions, resources
                WHERE memberships.user_id = '.intval($user_id).'
                AND memberships.group_id = permissions.group_id
                AND permissions.resource_id = resources.resource_id
				AND resources.script = "'.$script.'"')
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		$records += $result["access_records"];
		if (isset($result) && $records>0) {
                        $_SESSION["sucs_authorized"]=true;
			$deny = false;
        }
	}
	// think this fixes not logged in but auth required
	else if (!isset($_SESSION['webauth']['netID']) && $require_authorization==true) {
		include_once("webauth/include.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132189719-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-132189719-1');
</script>

<!-- DEBUG INFO
<?php
echo "Require Auth: ";
var_dump($require_authorization);
echo "Deny: ";
var_dump($deny);
echo "Authorized: ";
var_dump($_SESSION["sucs_authorized"]);
?>
-->

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!--<link rel="stylesheet" type="text/css" href="/samarketing.css" />-->
	<link rel="stylesheet" type="text/css" href="/drupalstyles.css" />
	<link rel="stylesheet" type="text/css" href="/styles.css" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/favicon.ico" />
    <script src="/plugins/fancy_zoom/js-global/FancyZoom.js" type="text/javascript"></script>
    <script src="/plugins/fancy_zoom/js-global/FancyZoomHTML.js" type="text/javascript"></script>
	<title>SU Tech
	    <?php
	       if (isset($page_title)) {
	           echo " - ".$page_title;
	       }
	    ?>
	</title>
<script language="JavaScript" type="text/javascript">
<!--
function webauthAction ( selectedaction )
{
  document.webauthform.action.value = selectedaction ;
  document.webauthform.submit() ;
}
-->
</script>
</head>

<body class="front logged-in page-node one-sidebar sidebar-left" onload="setupZoom()">
	<div id="container">
		<div id="ualogo" style="background:#FFF;">
			<a href="http://www.arizona.edu"><img src="/images/white-blue-banner.gif" alt="The University of Arizona" style="padding:0; margin-left:-20px;" /></a>
		</div>

		<a href="/index.php"><div id="logobar" style="background: url(/images/techheader.png); height: 90px; margin-top:6px;">
		</div></a>

		<br />
<?php
if ($deny && $_SERVER["SCRIPT_NAME"]!="/denied.php") {
	include("/srv/www/htdocs/sucs/denied.php");
	die;
}
?>

