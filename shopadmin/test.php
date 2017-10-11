<?php
require_once( dirname(__FILE__)."/../configs.inc.php" );
include_once "class.phpmailer.php";
include_once "../Config/Smtp.config.php";
$email_to = "pigangel_yang@yahoo.com.cn";
$s_email_subject = "你好啊";
$s_email_message = "測試";
echo $smtpserver;
		if ($email_to!=""){
			$mails = new PHPMailer(true);
			$mails->Charset    = 'utf-8';
			$mails->Host       = $smtpserver;
			$mails->SMTPAuth   = false;  
			$mails->Port       = $smtpserverport;                    // set the SMTP port for the GMAIL server
		   $mails->Username   = $smtpuser; // SMTP account username
		   $mails->Password   = $smtppass;        // SMTP account password
			$mails->From       = $smtpusermail;
			$mails->FromName   = "UTV";
			$email_array = explode(",",$email_to);
		    foreach($email_array as $k=>$to){
				if (trim($to)!="")
					$mails->AddAddress($to);
			}
			$mails->Subject  = $s_email_subject;
		
			$mails->WordWrap   = 80; // set word wrap
		
			$mails->MsgHTML($s_email_message);
			$mails->IsHTML(true); // send as HTML
		$mails->IsSMTP();
			$mails->Send();
		}
?>