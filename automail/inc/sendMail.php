<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

require_once('swiftmailer/lib/swift_required.php');

Function sendMail($idGroup, $toEmail, $toName, $emailSubject, $htmlEmailBody, $textEmailBody, $attachments, $charset, $flag) {
	$obj 		= new db_class();
	//sender data
	$groupEmailComponent	=	$obj->getSetting("groupEmailComponent", $idGroup);
	$groupSenderEmail       =	$obj->getSetting("groupSenderEmail", $idGroup);
	$groupReplyToEmail      =	$obj->getSetting("groupReplyToEmail", $idGroup);
	$groupName 	            =	$obj->getSetting("groupName", $idGroup);
	$groupGlobalCharset     =	$obj->getSetting("groupGlobalCharset", $idGroup);
	if (!$charset) {$charset=$groupGlobalCharset;}
	$groupUseInlineImages   =	$obj->getSetting("groupUseInlineImages", $idGroup);
	$groupScriptUrl         =	$obj->getSetting("groupScriptUrl", $idGroup);
	try {
		if ($groupEmailComponent=="smtp") {
				$mySQL="SELECT smtpServer, smtpUsername, smtpPassword, smtpPort, smtpSecureConnection, smtpAuthRequired  FROM ".$idGroup."_smtpServers WHERE idGroup=$idGroup AND isActive=-1 AND isPreferred=-1 LIMIT 1";
				$result	= $obj->query($mySQL);
				$rows 	= $obj->num_rows($result);
				if ($rows==0){die("=>At least one SMTP server must be Active and Preferred");}
				$row = $obj->fetch_array($result);
				$groupSmtpServer         =	$row['smtpServer'];
				$groupSmtpUsername       =	$row['smtpUsername'];
				$groupSmtpPassword       =	$row['smtpPassword'];
				$groupSmtpPort           =	$row['smtpPort'];
				$groupSmtpSecureConnection=	$row['smtpSecureConnection'];
				$groupAuthRequired       =	$row['smtpAuthRequired'];

		
		
		    $transport2 = Swift_SmtpTransport::newInstance($groupSmtpServer, $groupSmtpPort, $groupSmtpSecureConnection);
		    if ($groupAuthRequired==-1) {
		        $transport2->setUsername($groupSmtpUsername);
		        $transport2->setPassword($groupSmtpPassword);}
		        //$transport2->setTimeout(20);
		} else if ($groupEmailComponent=="phpmail") {
		    $transport2 = Swift_MailTransport::newInstance();
		} else if ($groupEmailComponent=="sendmail") {
		  $transport2 = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
		} else  if ($groupEmailComponent=="qmail") {
		  //$mail->IsQmail();
		}
		$mailer = Swift_Mailer::newInstance($transport2);
		$message = Swift_Message::newInstance($transport2);
		$message->setCharset($charset);
		$message->setPriority(3); //normal, 1:highest
		$message->setFrom(array($groupSenderEmail => $groupName));
		if ($groupReplyToEmail) {
		    $message->setReplyTo($groupReplyToEmail);
		}
		if (strlen($attachments)>1) {
		    $Attachments  = explode(",", $attachments);
		    $attachCount  = sizeof($Attachments);
		    for ($i=0; $i<$attachCount; $i++)  {
		    	if (file_exists('../attachments/'.$Attachments[$i])) {
		        	$message->attach(Swift_Attachment::fromPath('../attachments/'.$Attachments[$i]));
				}
		    }
		}
		$headers = $message->getHeaders();
		$message->addTo($toEmail, $toName);
		$message->setSubject($emailSubject);
		// $message->setEncoder(Swift_Encoding::getBase64Encoding()); // get8BitEncoding

		$htmlEmailBody = str_ireplace("\n", "", $htmlEmailBody);
		$htmlEmailBody = str_ireplace("\r", "\r\n", $htmlEmailBody);

		// INLINE IMAGES
		 if ($htmlEmailBody && $groupUseInlineImages==-1) {
		    $pinlineImages  = extractPicNames($htmlEmailBody);
		    if ($pinlineImages) {
		        $embImages      = explode(",", $pinlineImages);
		        $ImgCount  = sizeof($embImages);
		        for ($k=0; $k<$ImgCount; $k++)  {
		            $htmlEmailBody	= str_ireplace($groupScriptUrl.$embImages[$k],$message->embed(Swift_Image::fromPath('..'.$embImages[$k])), $htmlEmailBody);
		        }
		    }
		}
		if ($flag=="h") {     //normal...
		    $message->setBody($htmlEmailBody, 'text/html');
		}
		else if ($flag=="t") {
		    $message->setBody($textEmailBody, 'text/plain');
		}
		else if ($flag=="m") {
		    $message->setBody($htmlEmailBody, 'text/html');
		    $message->addPart($textEmailBody, 'text/plain');
		}
		//$logger = new Swift_Plugins_Loggers_EchoLogger(); 
		//$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
		
		$mailer->send($message);
	}
	catch (Exception $e) {
	   	//if ($mailDebug=="-1") {
	    	//echo "Error (File: ".$e->getFile().", line ". $e->getLine()."): ".$e->getMessage();
	    	echo $e->getMessage();
	    //}
	}
}
?>