<?php 
session_start();
if($_SESSION['webauth_splash'] != ''){
	//include('https://'.$_SERVER['SERVER_NAME'].$_SESSION['webauth_splash']);
	?>
	<iframe src="<?='http://'.$_SERVER['SERVER_NAME'].$_SESSION['webauth_splash']?>" frameborder="0" marginwidth="0" marginheight="0" height="100%" width="100%"></iframe>
	<?php
}
else{
	print '<div align="center"><h3 style="text-align:center; color:rgb(51, 51, 51); font-size:13px; font-family:arial; font-weight:400;">Establish UA NetID authentication now to access<br />protected services later.</h3>';	
}
?>