<?php
    $page_title = "404 Page Not Found";
    require_once("header.php");
    require_once("sidebar.php");
?>
<style type="text/css">
.feature_image {
    float:left;
}
.feature_title {
    font-size:16px;
    font-weight:bold;
    color:#036;
    margin-top:4px;
    float:left;
}
td {
    width:50%;
    height:100px;
}
</style>
<div id="403_conent">
    <div style="float:right;margin-left:20px;">
        <img src="/images/404.png" title="404 Error" alt="404 Error" />
    </div>
    <h1 style="font-family: Georgia; color: #6E2E14; margin-bottom:20px;">
        <span style="font-family: Georgia; font-size: 38px;">404</span> -
        <span style="font-family: Georgia; font-size: 18px;">Page Not Found</span>
    </h1>
    We apologize but it looks as if we were unable to find the page you
    requested, generating a <i>404-Page Not Found</i> Error.
    
    <br /><br />We will take note that of what you were unable able to find, but
    if you continue to experience the problem please contact the site
    administrator, <a href="mailto: su-web@email.arizona.edu">su-web@email.arizona.edu</a>. 
    
    <br /><br /><br /><span style="font-style: italic; color: #666;">In the
    meantime, this cute puppy is here to try and ease the pain of your issue
    at hand and brighten your day.</span>
</div>
<?php
        require_once("footer.php");
?>
