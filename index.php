<?php
    // require_once("./template/header.php");
	require_once ('C:/xampp/htdocs/template/header.php');
    require_once ('C:/xampp/htdocs/template/sidebar.php');
?>
<style type="text/css">
.feature_image {
    float:left;
    padding:0;
}
.feature_image img {
    margin:0;
    margin-right: 10px;
    margin-bottom: 10px;
}
.feature_title {
    font-weight:bold !important;
    color:#AB051F;
    background-color:#FFF;
    float:left;
    width:467px;
    font-size: 14px !important;
}
.feature_title a {
    font-size: 14px;
    font-weight:bold;
    color:#003876;
}
#feature_help {
    margin-top:0;
}
.feature_list {
    width:475px;
}
.feature {
    margin-top:10px;
    float:left;
    background-color:#FFF;
}
#feature_help, #feature_bldg, #feature_sys , #feature_email, #feature_mx4, #feature_diningrequest, #feature_projects {
    border-bottom: 1px solid #999;
}
.feature:last-child {
	border-bottom:none;
    padding-bottom:10px;
}
.feature_title h1, .feature_title a {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 22px;
    font-weight: bold;
    color: #AB051F;
}
ol {
	font-weight: bold;
}
li span {
	font-weight: normal;
}
</style>

<!--<div class="dialog_error">
<img src="/images/dialog-warning.png" height="18px" alt="Error Diaglog" /> 
Kronos will be unavailable beginning <b>Monday, September 29th, at 8:00 AM</b>.
Please make sure to have any required changes or approvals prior to this time.
After this time, please <b>DO NOT</b> access Kronos until notified by email.
</div>
<div style="clear:both;"></div>-->


<div style="float:right;width:200px;">
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
	    if ($_SESSION['webauth']['netID']=="yontaek"||$_SESSION['webauth']['netID']=="ghorner"||$_SESSION['webauth']['netID']=="dianelc"||$_SESSION['webauth']['netID']=="lchaudoi"||$_SESSION['webauth']['netID']=="ldj"||$_SESSION['webauth']['netID']=="mbenjes") {
	        echo '<div style="float:right;width:200px;margin-top:20px;background:#F7F7F1;border:1px solid #D2D2C4;">
        <div style="font-size:18px;font-weight:bold;color:#FFF;background:#AB051F;width:196px;padding:2px;">Tech Links</div>
        <div style="margin-left:15px;margin-top:10px;margin-bottom:10px;">
        <ul>
        	<li><a href="https://150.135.72.12">KVM</a>&nbsp;&nbsp;<span style="font-style: italic; color: #666;">IE Only</span></li>
        	<li><a href="http://uits.arizona.edu/forms">UITS Forms</a></li>
        	<li><a href="https://www.telcom.arizona.edu">Telcom/NetOps</a></li>
        	<li><a href="https://netid.arizona.edu/uaconnect/studentemp/">Catworks Account Request</a></li>
        	<li><a href="https://su-resmgmt.catnet.arizona.edu/">SU Spiceworks</a></li>
        	<li><a href="https://behind.blackboard.com/s/">Behind the Blackboard</a></li>
        	<li><a href="https://bigred.sunion.arizona.edu/reportingsystem/user/login.aspx">Blackboard Reports</a></li>
        	<li><a href="https://uassist.arizona.edu/arsys/shared/login.jsp?/arsys/forms/uassist-client.arizona.edu/AmerX%3AAccessRequests/Default+Admin+View/?cacheid=d5453d05&mode=CREATE&format=html">Amer-X Remedy</a></li>
       	</ul>
        </div>
	</div>';
	    }
    ?>
    </div>


<div class="feature_list" style="margin-right:10px;">
    <div class="feature" id="feature_sys" style="padding-bottom: 10px;">
                <div class="feature_title"><a href="https://su-netmgmt.catnet.arizona.edu/portal" target="_blank" ><h1>Having an IT related issue?</h1></a></div>
        <div class="feature_image"><a href="https://su-netmgmt.catnet.arizona.edu/portal" target="_blank" ><img src="/images/SU_Tech_icons-07_75.jpg" /></a></div>
        <br /><br />If you are having problem with your computer, register, need to request software, or have other technical problems please submit a help request.<br />
        <div style="padding-left:100px;margin-top:8px;"><a href="https://su-netmgmt.catnet.arizona.edu/portal" target="_blank" ><img style="margin-right: 5px;" src="/images/links.png" />Submit Help Request</a></div>
		<div style="clear:both;font-style: italic; color: #666;margin-top: 8px;">If you have an emergency and can't reach us please call 621-7755.</div>
    </div>
    <div class="feature" id="feature_sys" style="padding-bottom: 10px;">
                <div class="feature_title"><a href="/webissue/index.php" ><h1>Having a Website related issue?</h1></a></div>
        <div class="feature_image"><a href="/webissue/index.php" ><img src="/images/SU_Tech_icons-04_75.jpg" /></a></div>
        <br /><br />If you find any problem with the Student Union’s websites, including errors, wrong information or outdated pages, please submit comments.<br />
        <div style="padding-left:100px;margin-top:8px;"><a href="/webissue/index.php"><img style="margin-right: 5px;" src="/images/links.png" />Submit Help Request</a></div>
		<div style="clear:both;font-style: italic; color: #666;margin-top: 8px;">If you would rather talk in person, please call 621-9892.</div>
    </div>
    <div class="feature" id="feature_sys" style="padding-bottom: 10px;">
	        <div class="feature_title"><a href="/forms/workstationaccess"><h1>Workstation Logon</h1></a></div>
	        <div class="feature_image"><a href="/forms/workstationaccess"><img src="/images/SU_Tech_icons-03_75.jpg" /></a></div>
	        <br /><br />Request employee access to systems such as Foodpro or the Student Union Computer Workstations.<br />
	        <div style="padding-left:100px;margin-top:8px;"><a href="/forms/workstationaccess"><img style="margin-right: 5px;" src="/images/links.png" />Workstation Access Request</a></div>
	        <div style="padding-left:98px;font-style: italic; color: #666; margin-top: 2px;">(NetID Required)</div>
    </div>
    <div class="feature" id="feature_sys" style="padding-bottom: 10px;">
	        <div class="feature_title"><a href="/forms/posaccess"><h1>Point of Sale Logon</h1></a></div>
	        <div class="feature_image"><a href="/forms/posaccess"><img src="/images/SUTech_Web_Icon_17.jpg" /></a></div>
	        <br /><br />Request employee access to Point of Sale and Sequoia Web Reporting systems.<br />
	        <div style="padding-left:100px;margin-top:8px;"><a href="/forms/posaccess"><img style="margin-right: 5px;" src="/images/links.png" />Point of Sale Access Request</a></div>
	        <div style="padding-left:98px;font-style: italic; color: #666; margin-top: 2px;">(NetID Required)</div>
    </div>

    <!-- Dining Requests -->
    <div class="feature" id="feature_diningrequest" style="padding-bottom: 10px;">
            <div class="feature_title"><a href="https://su-diningrequests.catnet.arizona.edu/portal" target="_blank"><h1>Dining Request</h1></a></div>
            <div class="feature_image"><a href="https://su-diningrequests.catnet.arizona.edu/portal" target="_blank"><img src="/images/dining_request.png" style="width: 85px;" /></a></div>
            <br /><br />If you need to add new buttons/items to Sequoia registers, change prices on registers or are having Foodpro issues, please submit a Dining Request. <br />
            <div style="padding-left:100px;margin-top:8px;">
                <a href="https://su-diningrequests.catnet.arizona.edu/portal" target="_blank">
                    <img style="margin-right: 5px;" src="/images/links.png" />Dining Request
                </a>
            </div>
            <div style="padding-left:98px;font-style: italic; color: #666; margin-top: 2px;">(NetID Required)</div>
    </div>

    <div class="feature" id="feature_bldg" style="padding-bottom: 10px;">
        <div class="feature_title"><a href="/forms/buildingaccess"><h1>Access to Doors & Alarms</h1></a></div>
        <div class="feature_image"><a href="/forms/buildingaccess"><img class="no_topleft" src="/images/SU_Tech_icons-01_75.jpg" /></a></div>
        <br /><br />Request building access for new employees, report
        replacement CatCard#, or request building access to be deleted.<br />
        <div style="padding-left:100px;margin-top:8px;"><a href="/forms/buildingaccess"><img style="margin-right: 5px;" src="/images/links.png" />Building Access Request</a></div>
        <div style="padding-left:98px;font-style: italic; color: #666; margin-top: 2px;">(NetID Required)</div>
    </div>

    <div class="feature" id="feature_bldg" style="padding-bottom: 10px;">
        <div class="feature_title"><a href="/forms/keylocker"><h1>Access to Key & Locker</h1></a></div>
        <div class="feature_image"><a href="/forms/keylocker"><img class="no_topleft" src="/images/key_locker.jpg" /></a></div>
        <br /><br />Request employee locker and/or door key.<br />
        <div style="padding-left:100px;margin-top:8px;"><a href="/forms/keylocker"><img style="margin-right: 5px;" src="/images/links.png" />Key & Locker Access Request</a></div>
        <div style="padding-left:98px;font-style: italic; color: #666; margin-top: 2px;">(NetID Required)</div>
    </div>
    <div class="feature" id="feature_email" style="padding-bottom: 10px;">
		<div class="feature_title"><a href="/forms/uacon/index.php"><h1>Departmental Email Account Access</h1></a></div>
        <div class="feature_image"><a href="/forms/uacon/index.php"><img src="/images/SU_Tech_icons-02_75.jpg" /></a></div>
        <br /><br /><span style="color: #b00; font-weight: bold;"></span>
        Request new departmental account, changes to mailbox access, and new student employee catworks accounts.<br />
        <div style="padding-left:100px;margin-top:8px;"><a href="/forms/uacon/index.php"><img style="margin-right: 5px;" src="/images/links.png" />Departmental Email Account Access</a></div>
        <div style="padding-left:98px;font-style: italic; color: #666; margin-top: 2px;">(NetID Required)</div>
    </div>

    <div class="feature" id="feature_mx4">
                <div class="feature_title" style="margin-bottom:5px;"><a href="https://www.myschoolbuilding.com/sso/default.aspx?acctnum=915540480" target="_blank"><h1>Maintenance/Housekeeping/Event Services Request Form</h1></a></div>
                <div class="feature_image"><a target="_blank" href="http://150.135.72.231/MX4/"><img  style="padding-bottom:0px !important;" src="/images/SU_Tech_icons-05_75.jpg" /></a></div>
        <br /><br /><div style="margin-left:10px;"><!--Choose which department you would like to send your request to:-->
        <div style="float:left;width:260px;">
        <ul style="margin-left:10px;margin-bottom:4px;">
            	<li><a target="_blank" href="https://www.myschoolbuilding.com/sso/default.aspx?acctnum=915540480">Submit Help Request</a></li>
<!--                <li><a target="_blank" href="https://su-netmgmt.catnet.arizona.edu/portal">Technology Support</a> (Reboot it)</li>-->


        </ul>
        </div>
        <div style="clear:both;"></div>
        <!-- <div style="margin-left:10px;margin-top: 15px;"><a target="_blank" href="http://150.135.72.231/MX4/_private/RequestWorkSearch.asp"><img style="margin-right: 5px;" src="/images/links.png" />Check the status of an existing Maintenance Request</a></div> -->

        <div style="margin-left:10px;margin-top: 8px; margin-bottom:20px;"><a target="_blank" href="https://login.schooldude.com/sso/default.aspx?acctnum=915540480"><img style="margin-right: 5px;" src="/images/links.png" />Admin Login</a></div></div>
    </div>

    <div class="feature" id="feature_projects" style="padding-bottom: 10px;">
	        <div class="feature_title"><h1>Project Request Form</h1></div>
	        <div class="feature_image"><img class="no_topleft" src="/images/SU_Tech_icons-06_75.jpg" /></div>
	        <div style="float:left;">
				<ul style="margin-left:10px;">
					<li><a target="_blank" href="/forms/UnionPRF.docx">Union Project Request Form (docx)</a></li>
					<li><a target="_blank" href="/forms/UnionPRF.pdf">Union Project Request Form (pdf)</a></li>
				</ul>
	        </div>
    </div>

    <div class="feature" id="feature_phonerequest" style="padding-bottom: 100px;">
            <div class="feature_title"><a href="/forms/phonerequest"><h1>Phone Setup Request</h1></a></div>
            <div class="feature_image"><a href="/forms/phonerequest"><img src="/images/phone_icon.png" /></a></div>
            <br /><br />Request digital or analog phone line to be setup for a new or current employee. <br />
            <div style="padding-left:100px;margin-top:8px;"><a href="/forms/phonerequest"><img style="margin-right: 5px;" src="/images/links.png" />Phone Setup Request</a><br />
                <a href="/forms/phonerequest/voicemail.pdf" target="_blank" ><img style="margin-right: 5px;" src="/images/links.png"/>Voicemail Quick Guide</a>
            </div>
            <div style="padding-left:98px;font-style: italic; color: #666; margin-top: 2px;">(NetID Required)</div>
    </div>

</div>
<?php
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/template/footer.php');
?>
