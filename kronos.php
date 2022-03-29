<?php
//    require_once("header.php");
//    require_once("sidebar.php");
	require_once("./template/header.php");
	require_once("./template/sidebar.php");
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
    width:100%;
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
.feature {
    margin-top: 10px;
    float: left;
    background-color: #FFF;
	width: 604px;
	padding-bottom: 10px;
    border-bottom: 1px solid #999;
}
#feature_mobile, #feature_browser {
    border-bottom:none;
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
.store_link {
	float:left;
	margin-left:10px;
	margin-top:15px;
}
hr {
	height: 1px;
	border: 0;
	color: #999;
	background-color: #999;
	width: 100%;
}
.kronos_link {
	float:left;
	clear:both;
	margin-top:6px;
}
</style>

<div class="right_sidebar_feature" style="float:right;width:110px;">
	<div><img alt="Kronos" width="110px" src="/images/Kronos-HorizLogo.png" /></div>
	<div style="float:left;padding-left:5px;clear:both;">
	<div class="kronos_link"><a href="https://kronos.life.arizona.edu/wfc/applications/wtk/html/ess/quick-ts.jsp">Time Stamp Login</a></div>
	<div class="kronos_link"><a href="https://kronos.life.arizona.edu/wfc/navigator/logon">Main Login</a></div>
	<div class="kronos_link"><a href="#">Test/Training</a></div>
	</div>
</div>

<h1>Remote Kronos Access</h1>
<div class="feature" id="feature_vpn">
	<div class="feature_image"><img src="/images/AnyConnect.png" /></div>
	<span style="font-weight:bold;font-size:14px;">Kronos VPN Install Instructions</span>
	<br />
	<div style="padding-left:100px;margin-top:8px;">
		<ol>
			<li class="numlist">
				<span>
					<a href="https://vpn.arizona.edu">Click here</a> to install the UA Campus VPN.
					<br/>
					<span style="color:#666;font-style:italic;">Make SURE this works before you try the Kronos AnyConnect)</span>
				</span>
			</li>
			<li style="margin-top:8px;">
				<span>After you have installed the UA VPN and successfully connected, disconnect and
					<a href="https://vpn.arizona.edu/studentunion">click here</a> to connect to the Kronos Supervisors VPN
				</span>
			</li>
		</ol>
	</div>
	<div style="color:#666;font-style:italic;margin-left:8px;margin-bottom:10px;margin-top:16px;clear:both;">
		For more information about the UA VPN
		<a href="http://uits.arizona.edu/services/vpn">click here</a>.
	</div>
</div>
<div class="feature" id="feature_java">
	<div class="feature_image"><img src="/images/javalogo.png" /></div>
	<span style="font-weight:bold;font-size:14px;">Java Versions For Kronos</span>
	<br /><br />
	<span style="color: #b00; font-weight: bold;"></span>
	Our current version of Kronos will only work properly with the following versions of Java.
	<br />
	<div style="padding-left:100px;margin-top:8px;">
		<a href="/downloads/jre-7u67-windows-i586.exe"><img style="margin-right: 5px;" src="/images/links.png" />Java 7 Update 67</a>
	</div>
	<div style="color:#666;font-style:italic;margin-left:8px;margin-bottom:10px;margin-top:16px;clear:both;">
		Please remember you will need to remove all older versions of Java, the above is the latest officially supported version at this time. 
		<a href="http://windows.microsoft.com/en-us/windows/uninstall-change-program#uninstall-change-program=windows-7">Click here</a> for more information about uninstalling programs on your home computer.
	</div>
</div>
<div class="feature" id="feature_flash">
	<div class="feature_image"><img src="/images/flashplayer75.png" /></div>
	<span style="font-weight:bold;font-size:14px;">Adobe Flash Player For Kronos</span>
	<br /><br />
	<span style="color: #b00; font-weight: bold;"></span>
	Make sure you have the latest version of Adobe Flash Player installed and updated.
	<br />
	<div style="padding-left:100px;margin-top:8px;">
		<a href="http://get.adobe.com/flashplayer/"><img style="margin-right: 5px;" src="/images/links.png" />Get Adobe Flash Player</a>
	</div>
</div>
<div class="feature" id="feature_browser">
	<div class="feature_image"><img src="/images/browsers_thumb75.png" /></div>
	<span style="font-weight:bold;font-size:14px;">Browser For Kronos</span>
	<br /><br />
	<span style="color: #b00; font-weight: bold;"></span>
	Please make sure you are running Windows 7 or later on Windows PC's with up-to-date Internet Explorer 11 on PC's or the latest version of Apple OS X (10.7.5, Lion or later) with latest updates.
	<br />
	<div style="padding-left:100px;margin-top:8px;">
		<img style="margin-right: 5px;" src="/images/links.png" />Windows PC User's - <a target="_blank" href="http://www.microsoft.com/en-us/download/internet-explorer-11-details.aspx">Internet Explorer 11</a>
	</div>
	<div style="padding-left:100px;margin-top:8px;">
		<img style="margin-right: 5px;" src="/images/links.png" />Mac User's - Make sure you have latest OS X and Apple Software Updates
	</div>
	<div style="color:#666;font-style:italic;margin-left:8px;margin-bottom:10px;margin-top:16px;clear:both;">
		For more information on campus licensing, visit
		<a href="http://uabookstore.arizona.edu/campuslicensing/staff/">The UA BookStores</a>.
	</div>
	<div style="color:#666;font-style:italic;margin-left:8px;margin-bottom:10px;margin-top:16px;">
		For help with getting the above software installed and up to date on your home computer please contact
		<a href="http://uits.arizona.edu/departments/the247">The 24/7 IT Support Center</a>.
	</div>
	<div style="color:#666;font-style:italic;margin-left:8px;margin-bottom:10px;margin-top:16px;">
		For help with computers which are property of the Student Unions, please 
		<a href="http://150.135.72.231/MX4/_private/RequestWork1.asp?ID=14">submit a help request</a>.
	</div>
</div>
<?php
//        require_once("footer.php");
	require_once("./template/footer.php");
?>
