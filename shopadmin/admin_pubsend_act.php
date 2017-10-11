<?php
include_once "Check_Admin.php";

$Array =  array("mailsubject"=>base64_decode($_POST['email_title']),"mailbody"=>base64_decode($_POST['email_content']));
include "SMTP.Class.inc.php";
include "Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
//$SMTP->MailForsmartshop(trim($_POST[email_single]),"GroupSend",$Array);
$SMTP->MailForsmartshop(trim($_POST[email_single]),"","GroupSend",$Array);
/*
include_once "./inc/mail.class.php";
//初始化电子邮件类
$mail = new Email();
$mail->setTo($to);
$mail->setFrom($from);
$mail->setSubject($title);
$mail_content=StripSlashes($mailtext);
$mail->setHTML($mailbody);
$mail->send();
*/
?>