<?php
//	require_once("header.php");
//	require_once("sidebar.php");
	require_once("./template/header.php");
	require_once("./template/sidebar.php");
?>
<style type="text/css">
#about img {
    float: left;
    margin-right: 5px;
    margin-top: 10px;
}
#faq {
    color: #555;
    font-size: 12px;
    line-height: 21px;
}
.faq_question {
    cursor: pointer;
    font-weight:bold;
    margin-top:8px;
    color: #036;
    font-size:12px;
}
.faq_answer {
    margin-left: 20px;
    margin-right: 60px;
}
.faq_answer img {
display: block;
	max-width: 500px;
	max-height: 350px;
}
li {
	padding-bottom:12px;
}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
/*$(document).ready(function() {
    $('.faq_answer').hide();
    $('.faq_question').click(function() {
        $("#faq_answer_"+this.id.split("_")[2]).slideToggle('slow',function(){});
    });
});*/
</script>


<div id="faq">
<h1 style="margin-bottom:20px;">FoodPro FAQs</h1>

<div id="faq">
    <div class="faq_question" id="faq_question_0">Opening FoodPro on a new account</div>
    <div class="faq_answer" id="faq_answer_0" style="display:block !important;">
		<ol>
			<li>
				Double click the computer icon on your desktop
				<img src="/images/screenshots/foodpro/newrun/04_mycomputer.png" />
			</li>
			<li>
				<ul>
					<li><b>IF</b> - you see a Network Location labled "P:" right-click and select disconnect</li>
					<li><b>IF NOT</b> -  skip to step 3</li>
					<img src="/images/screenshots/foodpro/newrun/12_disconnectpdrive.png" />
				</ul>
			</li>
			<li>
				Click on the <b>Start</b> (Windows) Icon
			</li>
			<li>
				Type in "credential manager" in the search bar and select <b>Credential Manager</b> to run it
				<img src="/images/screenshots/foodpro/newrun/01_start.png" />
			</li>
			<li>
				Click on <b>Add a Windows credential</b>
				<img src="/images/screenshots/foodpro/newrun/02_addwincred.png" />
			</li>
			<li>
				Enter the following:<br />
					Internet or network address: <b>150.135.72.222</b><br />
					User name: <b>Type In Your FoodPro Username</b><br />
					Password: <b>Type In Your FoodPro Password</b>
				<img src="/images/screenshots/foodpro/newrun/03_foodprocred.png" />
			</li>
			<li>
				Click <b>OK</b> to save these settings then close the Credential Manager
			</li>
			<li>
				Double click the computer icon on your desktop
				<img src="/images/screenshots/foodpro/newrun/04_mycomputer.png" />
			</li>
			<li>
				Click on <b>Map network drive</b>
				<img src="/images/screenshots/foodpro/newrun/05_mapdrive.png" />
			</li>
			<li>
				Click on the drop down for Drive. Select "P"</b>
			</li>
			<li>
				In the Folder bar, type in "\\150.135.72.222\foodpro"</b>
			</li>
			<li>
				Make sure the "Reconnect at logon" is checked. Your screen should now be filled out like below.
				<img src="/images/screenshots/foodpro/newrun/06_pdrive.png" />
			</li>
			<li>
				Click Finish and then the FoodPro network drive will popup.  Close out of the screen and then double click the FoodPro icon on your desktop to begin working in FoodPro. 
				<img src="/images/screenshots/foodpro/newrun/07_pdrivecontents.png" />
			</li>
			<li>
				If the <b>Configure Session</b> option pops up then click <b>Cancel </b>
				<img src="/images/screenshots/foodpro/newrun/08_configses.png" />
			</li>
			<li>
				Open the <b>Session</b> menu in the uper left hand corner and click <b>Open...</b>
				<img src="/images/screenshots/foodpro/newrun/09_openses.png" />
			</li>
			<li>
				Click and open the "FoodPro_Fix.ses" file
				<img src="/images/screenshots/foodpro/newrun/10_fpses.png" />
			</li>
			<li>
				It will then ask to save the changes.  <b>Do not save the changes.</b>  FoodPro will then open up.
				<img src="/images/screenshots/foodpro/newrun/11_savechanges.png" />
			</li>
			<li>
				You have now completed the new account FoodPro setup. If you have any questions, concerns, or comments, please contact IT at <a href="mailto:su-tech@email.arizona.edu">su-tech@email.arizona.edu</a> or (520) 626-3252
			</li> 
		</ol>
    </div>
</div>

<div style="clear: both;"></div>
</div>
<?php
//        require_once("footer.php");
	require_once("./template/footer.php");
?>
