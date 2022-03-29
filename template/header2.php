<?php
	session_start();
	//$_SESSION['webauth']['netID'] = "kmbeyer";
	//session_destroy();
	if($_SERVER['HTTPS']!="on")
	{
		$redirect= "https://tech.union.arizona.edu/".$_SERVER['REQUEST_URI'];
		// $redirect= "https://pearl.sunion.arizona.edu".$_SERVER['REQUEST_URI'];
		header("Location:$redirect");
	}

	$deny = $require_authorization;
	$_SESSION["sucs_authorized"]=false;
	if ($_POST['action']=="logout"||strval($_GET["action"])=="logout") {
		session_destroy();
		header("Location: https://webauth.arizona.edu/webauth/logout?logout_href=https://tech.union.arizona.edu/strap&logout_text=Return%20To%20Student%20Unions%20Computer%20Support");
		//header("Location: https://webauth.arizona.edu/webauth/logout?logout_href=https://pearl.sunion.arizona.edu/strap&logout_text=Return%20To%20Student%20Unions%20Computer%20Support");
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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="SU Technology Support">
    <meta name="author" content="su-tech@email.arizona.edu">
    <link rel="shortcut icon" href="./favicon.ico">

    <title>SU Tech</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">	
    <!-- Bootstrap theme -->
    <link href="/css/bootstrap-theme.css" rel="stylesheet">
		
	<link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

    <link href="/FooTable-2/css/footable.core.css?v=2-0-1" rel="stylesheet" type="text/css"/>
    <link href="/FooTable-2/css/theme.css" rel="stylesheet" type="text/css"/>
	
	<link rel="stylesheet" href="/css/bootstrapValidator.css"/>
	
	<link href="https://eternicode.github.io/bootstrap-datepicker/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="https://redbar.secure.arizona.edu/sites/default/files/ua-banner/ua-web-branding/css/ua-web-branding.css">
	
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	
    <!-- Custom styles for this template -->
    <link href="/theme.css" rel="stylesheet">
		
	<?php
		if ($static_nav) {
			echo '<link href="/theme_static-nav.css" rel="stylesheet">';
		}
		else {
			echo '<link href="/theme_fixed-nav.css" rel="stylesheet">';
		}
	?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- navbar -->
	<?php
		if ($static_nav) {
			echo '<div class="navbar navbar-inverse navbar-static-top" role="navigation">';
		}
		else {
			echo '<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">';
		}
	?>
      <div id="ua-web-branding-banner-v1" class="ua-wrapper bgDark dark-gray">
	  		    <a class="ua-home asdf" href="http://arizona.edu" title="The University of Arizona">
	  		      <p>The University of Arizona</p>
	  		    </a>
		  </div>
      <div class="container">

		  <div class="nav_container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="#">SU Technology Support</a>
			</div>
			<div class="navbar-collapse collapse">
			  <ul class="nav navbar-nav">
				<li><a href="/strap">Home</a></li>
				<li><a href="/aboutus2">Contact Us</a></li>
                <li class="Site Menu">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Site Menu <b class="caret"></b></a>
				  <ul class="dropdown-menu">
<?php
				require_once("mysql/include.php");
				selectDB("sucs");
				$links = array("Technology Support Links" => "/techlinks.php",
				"Access Portal <i>(beta)</i>" => "/access/index.php",
				"Nick B's Page" => "/nickb/index.php",
				"Site Access Control" => "/site_access/index.php",
				"Site Users &amp; Groups" => "/site_users/index.php",
				"Wiki - Knowledgebase" => "/wiki",
				"Kronos Remote Access" => "/kronos2",
				"F.A.Q." => "/faq.php");

				foreach($links as $name => $link) {
					$print = false;

					$result = mysql_query('SELECT resources.name, COUNT(resource_id) AS access_records
									FROM resources
									WHERE resources.script = "'.$link.'"')
					or die(mysql_error());
							$result = mysql_fetch_array($result);
							if (!isset($result) || $result["access_records"]<1) {
									$print = true;
							}
					else {
							$result = mysql_query('SELECT users.netID, users.user_id, COUNT( permission_id ) AS access_records
									FROM users, permissions, resources
									WHERE users.user_id = permissions.user_id
									AND permissions.resource_id = resources.resource_id
									AND resources.script = "'.$link.'"
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
									AND resources.script = "'.$link.'"')
								or die(mysql_error());
							$result = mysql_fetch_array($result);
							$records += $result["access_records"];
								if (isset($result) && $records>0) {
							$print = true;
								}
					}

					if ($print) {
						echo '<li><a href="'.$link.'">'.$name.'</a></li>';
					}
				}
				
				echo '<li class="divider"></li><li>';
				if (isset($_SESSION['webauth']['netID'])) {
					echo '<a href="/strap.php?action=logout">Logout</a>';
				}
				else {
					echo '<a href="/strap.php?action=login">Login</a>';
				}
				echo '</li>';
?>
				  </ul>
				</li>
			  </ul>
			</div><!--/.nav-collapse -->
		  </div>
      </div>
    </div>

    <div class="container theme-showcase">


<?php
if ($deny && $_SERVER["SCRIPT_NAME"]!="/denied.php") {
	echo 'Permission Denied. Make sure you are logged in, otherwise contact Nick.';
	include("footer2.php");
	die;
}
?>