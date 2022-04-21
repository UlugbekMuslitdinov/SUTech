<?php
//	require_once("header.php");
//	require_once("sidebar.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/template/header.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/template/sidebar.php");
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
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.faq_answer').hide();
    $('.faq_question').click(function() {
        $("#faq_answer_"+this.id.split("_")[2]).slideToggle('slow',function(){});
    });
});
</script>


<div id="faq">
<h1 style="margin-bottom:20px;">Kronos 7.0 FAQs</h1>

<div id="faq">
    <div class="faq_question" id="faq_question_0">First time Java pop-ups on Windows 7</div>
    <div class="faq_answer" id="faq_answer_0">
		<ol>
			<li>
				Go to your desktop and click the provided shortcut to Kronos, it should be an icon with the orange, round Kronos logo.
			</li>
			<li>
				If this is your first time opening IE since the upgrade you will be greeted with a prompt to configure Internet Explorer's settings. Press "OK" to continue.
				<img src="/images/screenshots/kron7/win7/01_IESettings.png" />
			</li>
			<li>
				There will be a bar at the bottom of the screen requesting to enable/disable add-ons that are ready for use. Click "Choose add-ons".
				<img src="/images/screenshots/kron7/win7/02_EnableAddons.png" />
			</li>
			<li>
				When presented with the "Choose Add-ons" dialog, click the "Enable All" button at the bottom. Once all add-ons are enabled, close this window by clicking the "Done" button.
				<img src="/images/screenshots/kron7/win7/03_AddonList.png" />
			</li>
			<li>
				You should now be taken to the Kronos Workforce Central logon window. Enter your UA NetID username and password to enter the application.
				<img src="/images/screenshots/kron7/win7/05_Login.png" />
			</li>
			<li>
				Once you have logged in with your UA NetID username and password, <b>WAIT</b>. You will see an error stating Java is not installed first and then option bar about allowing the enabled Java add-on. Wait until you see a Java pop-up asking you "Do you want to run this application?". You will select the "Do not show this again for apps from this publisher and location above" box and then click the "Run" button. This will cause the page to reload, wait again.
				<img src="/images/screenshots/kron7/win7/06_RunApplet.png" />
			</li>
			<li>
				After the page has loaded you will now want to select down arrow button next to allow in the bar at the bottom of the screen. Then click "Allow for all websites"
				<img src="/images/screenshots/kron7/win7/07_AllowPlugin.png" />
			</li>
			<li>
				The page will reload one more time and you should be presented with a Java security warning. Select the "Do not show this again for this app and web site." box, and then click the allow button. You should now be able to use the new version of Kronos.
				<img src="/images/screenshots/kron7/win7/08_AllowAccess.png" />
			</li>
		</ol>
    </div>
    
    <div class="faq_question" id="faq_question_1">First time Java pop-ups on Windows XP</div>
    <div class="faq_answer" id="faq_answer_1">
		<ol>
			<li>
				Go to your desktop and click the provided shortcut to Kronos, it should be an icon with the orange, round Kronos logo.
			</li>
			<li>
				You should be taken to the Kronos Workforce Central logon window. Enter your UA NetID username and password to enter the application.
				<img src="/images/screenshots/kron7/winxp/01_login.png" />
			</li>
			<li>
				Once you have logged in with your UA NetID username and password, <b>WAIT</b>. You will see an error stating Java is not installed first and then option bar about allowing the enabled Java add-on to run. Wait until you see a Java pop-up asking you "Do you want to run this application?". You will select the "Do not show this again for apps from this publisher and location above" box and then click the "Run" button. This will cause the page to reload, wait again.
				<img src="/images/screenshots/kron7/winxp/04_runapplet.png" />
			</li>
			<li>
				After the page has reloaded, you will see a bar at the top of the page that asks if you want to allow this website to run the Java add-on. Select this bar then click "Run Add-on on All Websites" from the dropdown.
				<img src="/images/screenshots/kron7/winxp/05_allowplugin.png" />
			</li>
			<li>
				The page will reload again. This time a security warning will be displayed asking "Do you want to run this ActiveX control?", click the the "Run" button.
				<img src="/images/screenshots/kron7/winxp/06_runplugin.png" />
			</li>
			<li>
				The last pop-up will be one that Windows XP users will see each time the Java applet loads, for example, when switching between genies and each time you login.  This may be annoying, but go ahead and click the "Yes" button each time it appears.
				<img src="/images/screenshots/kron7/winxp/07_allowaccess.png" />
			</li>
		</ol>
    </div>
</div>

<div style="clear: both;"></div>
</div>
<?php
//        require_once("footer.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/template/footer.php");
?>
