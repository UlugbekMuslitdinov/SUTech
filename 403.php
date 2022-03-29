<?php
    $page_title = "403 Permission Denied";
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
        <img src="/images/403.png" title="403 Error" alt="403 Error" />
    </div>
    <h1 style="font-family: Georgia; color: #6E2E14; margin-bottom:20px;">
        <span style="font-family: Georgia; font-size: 38px;">403</span> -
        <span style="font-family: Georgia; font-size: 18px;">Permission Denied</span>
    </h1>
    We aren't sure where you are looking to go but it would seem you didn't
    belong ending on this page as we have received a
    <i>403-Permission Denied</i> Error.
    
    <br /><br />We apologize for the inconvenience but we suggest you retrace
    your steps. If you continue to experience this issue please contact the
    site administrator, <a href="mailto: su-web@email.arizona.edu">su-web@email.arizona.edu</a>.
    
    <br /><br /><br /><span style="font-style: italic; color: #666;">In the
    meantime, this cute puppy gives you an adorable look of disappointment for
    ending up where it didn't seem you belonged.</span>
</div>
<?php
        require_once("footer.php");
?>
