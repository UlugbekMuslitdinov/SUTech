<?php
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/header.php');
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/sidebar.php');  
?>

<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="webissue.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="webissue.js"></script>


<!--<div class="dialog_error">
<img src="/images/dialog-warning.png" height="18px" alt="Error Diaglog" /> 
Kronos will be unavailable beginning <b>Monday, September 29th, at 8:00 AM</b>.
Please make sure to have any required changes or approvals prior to this time.
After this time, please <b>DO NOT</b> access Kronos until notified by email.
</div>
<div style="clear:both;"></div>-->


<!-- <div style="float:right;width:200px;">
	<div style="float:right;width:200px;margin-top:20px;background:#F7F7F1;border:1px solid #D2D2C4;">
	        <div style="font-size:18px;font-weight:bold;color:#FFF;background:#003876;width:196px;padding:2px;">Quick Links</div>
	        <div style="margin-left:15px;margin-top:10px;margin-bottom:10px;">
	        <ul>
	        	<li><a href="/faq">Frequently Asked Questions</a></li>
	        	<li><a href="https://netid.arizona.edu/">UA NetID</a></li>
	        	<li><a href="https://kronos.life.arizona.edu/wfc/navigator/logon">Kronos</a></li>
	        	<li><a href="https://su-seqweb.catnet.arizona.edu/QuadPoint/">Sequoia Web Client</a></li>
	        	<li><a href="http://uaconnect.arizona.edu/">UAConnect Staff Email</a></li>
	        	<li><a href="http://arizona.edu/phonebook">UA Phonebook</a></li>
	        	<li><a href="http://uits.arizona.edu/">UITS</a></li>
	       	</ul>
	        </div>
	</div>

	<?php
	//     if ($_SESSION['webauth']['netID']=="nbischof"||$_SESSION['webauth']['netID']=="ghorner"||$_SESSION['webauth']['netID']=="dianelc"||$_SESSION['webauth']['netID']=="lchaudoi"||$_SESSION['webauth']['netID']=="ldj"||$_SESSION['webauth']['netID']=="mbenjes") {
	//         echo '<div style="float:right;width:200px;margin-top:20px;background:#F7F7F1;border:1px solid #D2D2C4;">
 //        <div style="font-size:18px;font-weight:bold;color:#FFF;background:#AB051F;width:196px;padding:2px;">Tech Links</div>
 //        <div style="margin-left:15px;margin-top:10px;margin-bottom:10px;">
 //        <ul>
 //        	<li><a href="https://150.135.72.12">KVM</a>&nbsp;&nbsp;<span style="font-style: italic; color: #666;">IE Only</span></li>
 //        	<li><a href="http://uits.arizona.edu/forms">UITS Forms</a></li>
 //        	<li><a href="https://www.telcom.arizona.edu">Telcom/NetOps</a></li>
 //        	<li><a href="https://netid.arizona.edu/uaconnect/studentemp/">Catworks Account Request</a></li>
 //        	<li><a href="https://su-resmgmt.catnet.arizona.edu/">SU Spiceworks</a></li>
 //        	<li><a href="https://behind.blackboard.com/s/">Behind the Blackboard</a></li>
 //        	<li><a href="https://bigred.sunion.arizona.edu/reportingsystem/user/login.aspx">Blackboard Reports</a></li>
 //        	<li><a href="https://uassist.arizona.edu/arsys/shared/login.jsp?/arsys/forms/uassist-client.arizona.edu/AmerX%3AAccessRequests/Default+Admin+View/?cacheid=d5453d05&mode=CREATE&format=html">Amer-X Remedy</a></li>
 //       	</ul>
 //        </div>
	// </div>';
	//     }
    ?>
</div> -->


<div class="main-content" style="margin-right:10px;">

   <?php
	
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			//include_once('request_form.php');
			include_once(__ROOT__.'/webissue/request_form.php');
		}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			//require_once('email_request.php');
			require_once(__ROOT__.'/webissue/email_request.php');
		}

	?> 

</div>
<?php
        //require_once("footer.php");
        require_once(__ROOT__.'/template/footer.php');
?>
