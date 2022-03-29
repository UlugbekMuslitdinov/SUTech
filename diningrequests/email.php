<?php

include_once('Core/email.class.php');

sendEmail::setSender('dr_visitor',$_POST['email']);
sendEmail::setReceiver('dr_receiver','eotkank87@email.arizona.edu');
sendEmail::setMessage($_POST['message']);
$result = sendEmail::finallySendEmail('dr_visitor','dr_receiver');
echo json_encode($result);

?>