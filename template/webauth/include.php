<?
	if($_SERVER['HTTPS']!="on")
	{
		$redirect= "https://pearl.sunion.arizona.edu".$_SERVER['REQUEST_URI'];
		header("Location:$redirect");
		die;
	}
	session_start();
	//check wether or not they have already been logged in via webauth
	if(!isset($_SESSION['webauth']['netID']) || $_SESSION['webauth']['netID']==''){
		
		//check if page to return has been specified otherwise use current page as the return to
		if($webauth_service == '' || !isset($webauth_service))		$_SESSION['webauth_service'] = $_SERVER['PHP_SELF'];
		else														$_SESSION['webauth_service'] = $webauth_service;
		
		//save possibly set splash page to session so splash.php can grab it and display it
		$_SESSION['webauth_splash'] = $webauth_splash;
		
		//redirect to webauth so user can sign in allowing the host to be a variable so all domains can use this same file
		header("Location: https://webauth.arizona.edu/webauth/login?service=https://".$_SERVER['SERVER_NAME']."/template/webauth/redirect.php");
		exit();
		
		//after beign sent to webauth they will be redirected to redirect.php
	}
	
	function create_logout_link($text){
		//session_destroy();
		return 'https://webauth.arizona.edu/webauth/logout?logout_href=http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'&logout_text='.$text;
	}
?>
