<?php
//	require_once("header.php");
//	require_once("sidebar.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "template/header.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "template/sidebar.php");
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
<h1 style="margin-bottom:20px;">FAQs</h1>

<div id="faq">
    <div class="faq_question" id="faq_question_0">I am having issues with equipment in a meeting room, who do I contact?</div>
    <div class="faq_answer" id="faq_answer_0">
        Please contact event services (520) 621-1414. They will help to provide support with
        Audio/Video equipment and your other needs for help with meeting rooms.
    </div>
    
    <div class="faq_question" id="faq_question_1">Who do I contact if I have an urgent issue (eg. Broken keyboard/mouse, machine won't power on, etc...)?</div>
    <div class="faq_answer" id="faq_answer_1">
        If you have an urgent issue, please feel free to call the Arizona Student Unions Tech phone line at <b>(520) - 626-3252</b>. A member of our support team will assist with your problem. If no one is available, please leave a message and we will get back to you as soon as possible. Another option is to submit a help request ticket (<a href="https://su-netmgmt.catnet.arizona.edu/portal" target="_blank">https://su-netmgmt.catnet.arizona.edu/portal</a>) and mark the severity as critical or immediate urgencies in the drop down.
    </div>
    
    <div class="faq_question" id="faq_question_2">Who do I contact if I cannot access foodpro?</div>
    <div class="faq_answer" id="faq_answer_2">
        If you have a new employee and need a Workstation as well as foodpro access, you will want to fill out the Workstation access form (<a href="https://pearl.sunion.arizona.edu/forms/workstationaccess" target="_blank">https://pearl.sunion.arizona.edu/forms/workstationaccess</a>). Under the “access to what” field, you will want check the “foodpro” checkbox.
        <br/><br/>
         If you already have Workstation access, but only need foodpro access, you will want to put in a ticket to dining services <a href="https://su-diningrequests.catnet.arizona.edu/portal" target="_blank">https://su-diningrequests.catnet.arizona.edu/portal</a>.
    </div>
    
    <div class="faq_question" id="faq_question_3">I have/am hiring a new employee, how do they get accounts?</div>
    <div class="faq_answer" id="faq_answer_3">
        Please DO NOT fill out MX4 work requests for new account creation. To
        request the account you will need to have the new employee's netID
        (<a href="https://netid.arizona.edu/">more info</a>). This is not a 
        number or their employeeID; it is most commonly the first part of the
        employee's email address <u>NetID</u>@email.arizona.edu. Below is a list of
        resources you will need to actually request an account.
        <ul style="margin-left: 20px;">
            <li><a href="/forms/computeraccess">Union Computer Systems Access Request</a> (Foodpro, or Computer Workstations)</li>
            <li><a href="/forms/buildingaccess">Building Access Requests</a></li>
            <li><a href="https://netid.arizona.edu/">UA NetID</a></li>
            <li><a href="/forms/uacon">OWA Staff Email</a> - outlook.office365.edu/owa</li>
        </ul>
    </div>
    
    <div class="faq_question" id="faq_question_4">A website such as netvupoint or uaccess will not work or says my browser is not up to date. How do I fix this?</div>
    <div class="faq_answer" id="faq_answer_4">
        It is likely that you will need to enable/disable <b>Compatibility View</b> in
            Internet Explorer. To do so, click the button which looks like a torn
            piece of paper at the right side of the address toolbar as seen in
            the screenshots bellow:<br /><br />
            <div style="margin-left: 60px;">Internet Explorer 8:
            <br /><img src="/images/ie8_compatibility.png" /></div>
            <div style="margin-left: 60px;">Internet Explorer 9:
            <br /><img src="/images/ie9_compatibility.png" /></div>
    </div>
    
    <div class="faq_question" id="faq_question_5">A piece of software is saying it needs to be updated or that I do not have permission to install something?</div>
    <div class="faq_answer" id="faq_answer_5">
        We are here to provide software updates/installation and often work to make sure that this is an automated process. In some cases, however we must visit your workstation to provide updates. To request assistance you can fill out a workstation access request here: <a href="https://pearl.sunion.arizona.edu/forms/workstationaccess" target="_blank">https://pearl.sunion.arizona.edu/forms/workstationaccess</a>. If the issue is urgent, you can call the Arizona Student Unions Tech phone line at 520-626-3252. A member of our team will assist with your problem. If no one is available please leave a message and we will get back to you as soon as possible. 
    </div>
    
	<div class="faq_question" id="faq_question_6"><a href="https://it.arizona.edu/documentation/how-set-or-stop-email-forward-office-365" target="_blank">How to Set or Stop an Email Forward in Office 365</a></div>
	
	<div class="faq_question" id="faq_question_7"><a href="https://it.arizona.edu/documentation/uaconnect365-mobile-settings" target="_blank">UAConnect365 Mobile Settings</a></div>
</div>

<div style="clear: both;"></div>
</div>
<?php
        require_once($_SERVER["DOCUMENT_ROOT"] . "template/footer.php");
	//require_once(__ROOT__.'/template/footer.php');
?>
