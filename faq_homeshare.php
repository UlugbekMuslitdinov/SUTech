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
    display: none;
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
<h1 style="margin-bottom:20px;">Connecting to new home share from Novell Workstations</h1>

<div id="faq">
    <div class="faq_question" id="faq_question_0">Mapping temporary S: drive</div>
    <div class="faq_answer" id="faq_answer_0" style="display:block !important;">
		<ol>
			<li>
				Click on the <b>Start</b> (Windows) Icon
			</li>
			<li>
				Type in "credential manager" in the search bar and select <b>Credential Manager</b> to run it
				<img src="/images/screenshots/maphome/01_credman.png" />
			</li>
			<li>
				Click on <b>Add a Windows credential</b>
				<img src="/images/screenshots/maphome/02_addcred.png" />
			</li>
			<li>
				<ul><li>In the "Internet or network address" enter: <b>su-share.catnet.arizona.edu</b></li>
					<li>In the "User name" field type in <b>CATNET\</b> followed by your UA NetID username<br />
						<i>(Your UA NetID username is the first part of your email address. eg. wilbur@email.arizona.edu)</i></li>
					<li>In the "Password" field type in your UA NetID password</li>
				</ul>
				See the example below for how wilbur filled in the fields.
				<img src="/images/screenshots/maphome/03_creddetails" />
			</li>
			<li>
				Double click the computer icon on your desktop
				<img src="/images/screenshots/maphome/04_mycomputer.png" />
			</li>
			<li>
				Click on <b>Map network drive</b>
				<img src="/images/screenshots/maphome/05_mapdrive.png" />
			</li>
			<li>
				Click on the drop down for Drive. Select "S"</b> <i>(this will later become the new H: drive)</i>
			</li>
			<li>
				In the Folder bar, type in "\\su-share.catnet.arizona.edu\home"</b>
			</li>
			<li>
				Make sure the "Reconnect at logon" is checked. Your screen should now be filled out like below.
				<img src="/images/screenshots/maphome/06_sdetails.png" />
			</li>
			<li>
				You should now see the set of department folders which you have permissions to view
				<img src="/images/screenshots/maphome/07_depfolders.png" />
			</li>
			<li>
				To find the S: drive at a later time, double click the computer icon on your desktops and you should see it under <b>Network Locations</b>
				<img src="/images/screenshots/maphome/08_viewdrives.png" />
			</li>
			<li>
				You have now completed connect to the new home share. If you have any questions, concerns, or comments, please contact IT at <a href="mailto:su-tech@email.arizona.edu">su-tech@email.arizona.edu</a> or (520) 626-3252
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
