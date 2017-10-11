<?php
function sendTestCampaign($pselectedemail, $pselectedname, $pidNewsletter,$ptype,$idGroup) {
require_once('../inc/swiftmailer/lib/swift_required.php');

$objT 		= new db_class();
$groupName 	 =	$objT->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$objT->getSetting("groupTimeOffsetFromServer", $idGroup);
$myDay      = myDatenow();
$failures="";
if (@$pdemomode) {
	forDemo2(DEMOMODE_1);
}
$idCampaign=-1;	//simulated sending
$pinlineImages="";
if ($ptype=="-1") {
	$idHtmlNewsletter	= $pidNewsletter;	
	$idTextNewsletter	= 0;
	$type	            = 1;
}
else if ($ptype=="0") {
	$idHtmlNewsletter	= 0;
	$idTextNewsletter	= $pidNewsletter;
	$type	            = 2;
}	 
  
  $idSendFilter		    = 0;
  $ga_utm_source	    = "";
  $pfromName			= "";
  $pfromEmail			= "";
  $preplyToEmail		= "";

$groupSenderEmail       =	$objT->getSetting("groupSenderEmail", $idGroup);
$groupReplyToEmail      =	$objT->getSetting("groupReplyToEmail", $idGroup);
$groupBounceToEmail     =	$objT->getSetting("groupBounceToEmail", $idGroup);
$groupContactEmail      =	$objT->getSetting("groupContactEmail", $idGroup);
$groupScriptUrl         =	$objT->getSetting("groupScriptUrl", $idGroup);
$groupSite              =	$objT->getSetting("groupSite", $idGroup);
$groupEmailComponent	=	$objT->getSetting("groupEmailComponent", $idGroup);
$groupDebugSendMail      =	$objT->getSetting("groupDebugSendMail", $idGroup);

$groupEncryptionPassword        =	$objT->getSetting("groupEncryptionPassword", $idGroup);
$groupUseInlineImages           =	$objT->getSetting("groupUseInlineImages", $idGroup);
$groupActiveLinkTrackingHtml    =	$objT->getSetting("groupActiveLinkTrackingHtml", $idGroup);
$groupActiveLinkTrackingText    =	$objT->getSetting("groupActiveLinkTrackingText", $idGroup);
$groupActiveViewsTracking       =	$objT->getSetting("groupActiveViewsTracking", $idGroup);
$groupEnableBatchSending        =	0;
$groupBatchSize                 =	$objT->getSetting("groupBatchSize", $idGroup);
$groupBatchInterval             =	$objT->getSetting("groupBatchInterval", $idGroup);
$groupGlobalCharset             =	$objT->getSetting("groupGlobalCharset", $idGroup);
$trackMails                     =   $objT->getSetting("groupTrackMailTo", $idGroup);

$invalidEmailsFound=0;
//if ($rows) {
    switch ($type) {
    case 1:
        $ptypeIs = 'Html';
        $pEmailSubject  = getNewsletterData($idHtmlNewsletter, $idGroup, 0);
        $pHtmlBody      = getNewsletterData($idHtmlNewsletter, $idGroup, 1);
        $pAttachments   = getNewsletterData($idHtmlNewsletter, $idGroup, 2);
        $pCharset       = getNewsletterData($idHtmlNewsletter, $idGroup, 3);
        if ($groupUseInlineImages==-1){$pinlineImages  = extractPicNames($pHtmlBody);}
        $mySQL="UPDATE ".$idGroup."_newsletters SET sent=-1, dateSent='$myDay' WHERE idNewsletter=$idHtmlNewsletter";
        $objT->query($mySQL);
        break;
    case 2:
        $ptypeIs = 'Text';
        $pEmailSubject  = getNewsletterData($idTextNewsletter, $idGroup, 0);
        $pTextBody      = getNewsletterData($idTextNewsletter, $idGroup, 1);
        $pAttachments   = getNewsletterData($idTextNewsletter, $idGroup, 2);
        $pCharset       = getNewsletterData($idTextNewsletter, $idGroup, 3);
        $pHtmlBody="";
        $mySQLt="UPDATE ".$idGroup."_newsletters SET sent=-1, dateSent='$myDay' WHERE idNewsletter=$idTextNewsletter";
        $objT->query($mySQLt);
        break;
    default:
        $ptypeIs='Undefined';
    }
    //Create an array of non-variable data
    $mailData["idHtmlNewsletter"]   = $idHtmlNewsletter;
    $mailData["idTextNewsletter"]   = $idTextNewsletter;
    $mailData["groupScriptUrl"]     = $groupScriptUrl;
    $mailData["idCampaign"]          = $idCampaign;
    $mailData["groupContactEmail"]  = $groupContactEmail;
    $mailData["groupSite"]          = $groupSite;
    $mailData["groupName"]          = $groupName;
    $mailData["idGroup"]            = $idGroup;
	$mailData["date_time"]			= date("M d Y, H:i" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));
	$mailData["date_time_2"]		= date("l d, F Y" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));

    if ($groupActiveViewsTracking=="-1" && !empty($pHtmlBody)) {
        $pHtmlBody=addViewsTracker($pHtmlBody);
    }
    if (!empty($pHtmlBody) && !empty($ga_utm_source) && $groupActiveLinkTrackingHtml=="0") {
        $pHtmlBody= addGoogleTracking($pHtmlBody, $mailData);
    }
    else if (!empty($pHtmlBody) && $groupActiveLinkTrackingHtml=="-1") {
        $pHtmlBody= extractLinks($pHtmlBody, $mailData, $trackMails);
    }
    /*********************************************************************************/
    /************** GLOBAL SENDMAIL OPTIONS *****************************************/
    /*********************************************************************************/

    if ($groupEmailComponent=="smtp") {
		$mySQL="SELECT idSmtp, smtpServer, smtpUsername, smtpPassword, smtpPort, smtpSecureConnection, smtpAuthRequired, smtpAntiFloodBatch, smtpAntiFloodPause FROM ".$idGroup."_smtpServers WHERE idGroup=$idGroup AND isActive=-1 ORDER BY smtpLastUsed Asc LIMIT 1";
		$result	= $objT->query($mySQL);
		$rows 	= $objT->num_rows($result);
		if ($rows==0){die("=>At least one SMTP server must be Active and Preferred");}
		$row = $objT->fetch_array($result);
		$idSmtp			         =	$row['idSmtp'];
		$groupSmtpServer         =	$row['smtpServer'];
		$groupSmtpUsername       =	$row['smtpUsername'];
		$groupSmtpPassword       =	$row['smtpPassword'];
		$groupSmtpPort           =	$row['smtpPort'];
		$groupSmtpSecureConnection=	$row['smtpSecureConnection'];
		$groupAuthRequired       =	$row['smtpAuthRequired'];
		$groupAntiFloodBatch     =	$row['smtpAntiFloodBatch'];
		$groupAntiFloodPause     =	$row['smtpAntiFloodPause'];

        $transport = Swift_SmtpTransport::newInstance($groupSmtpServer, $groupSmtpPort, $groupSmtpSecureConnection);
        if ($groupAuthRequired==-1) {
            $transport->setUsername($groupSmtpUsername);
            $transport->setPassword($groupSmtpPassword);}
    } else if ($groupEmailComponent=="phpmail") {
        $transport = Swift_MailTransport::newInstance();
    } else if ($groupEmailComponent=="sendmail") {
        $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
        //"-bs" runs in SMTP mode so theoretically it will act like the SMTP Transport
        //"-t" runs in piped mode with no feedback, but theoretically faster, though not advised
    } else  if ($groupEmailComponent=="qmail") {
        //$mail->IsQmail();
    }
	$mailer = Swift_Mailer::newInstance($transport);
	// To use the ArrayLogger
	//$logger = new Swift_Plugins_Loggers_EchoLogger();
	//$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
/*********************************************************************************/
/*********************************************************************************/
        $psubject	            = $pEmailSubject;       //original value
        $sub["idEmail"]         = 0;
        $pidEmail               = 0;
        $sub["email"]           = $pselectedemail;
        $sub["email2"]          = myEncrypt($sub["email"], $groupEncryptionPassword);
        $sub["name"]            = $pselectedname;
        $sub["lastName"]        = "";
        $sub["subCompany"]      = "";
        $sub["address"]         =  "";
        $sub["city"]            =  "";
        $sub["state"]           =  "";
        $sub["zip"]             =  "";
        $sub["country"]         =  "";
        $sub["subPhone1"]       =  "";
        $sub["subPhone2"]       =  "";
        $sub["subMobile"]       =  "";
        $sub["subPassword"]     =  "";
        $sub["prefersHtml"]     =  "";
        $sub["dateSubscribed"]  =  "";
        $sub["subBirthDay"]     =  "";
        $sub["subBirthMonth"]   =  "";
        $sub["subBirthYear"]    =  "";
        $sub["dateLastUpdated"] =  "";
        $sub["dateLastEmailed"] =  "";
        $sub["customSubField1"] =  "";
        $sub["customSubField2"] =  "";
        $sub["customSubField3"] =  "";
        $sub["customSubField4"] =  "";
        $sub["customSubField5"] =  "";
        $sub["fullName"] =($sub["name"] || $sub["lastName"])? $sub["name"].' '.$sub["lastName"]:$sub["email"];
        //personalize subject line
        $psubject	= str_ireplace("#subname#",     $sub["name"], $psubject);
        //$psubject	= str_ireplace("#sublastname#", $sub["lastName"], $psubject);
        if (!empty($pHtmlBody)) {
            $pHtmlBody = str_ireplace("\n", "", $pHtmlBody);
            $pHtmlBody = str_ireplace("\r", "\r\n", $pHtmlBody);
            $pbody_h	= subscriberTags($pHtmlBody, $sub, $mailData);
            $pbody_h    = otherHtmlTags($pbody_h, $sub, $mailData);
        }
        if (!empty($pTextBody)) {
            $pbody_t    = subscriberTags($pTextBody, $sub, $mailData);
    	    if ($groupActiveLinkTrackingText=="-1") {
    	        $pbody_t    = tracklinkstext($pbody_t, $sub, $mailData);
    	    }
            $pbody_t    = otherTextTags($pbody_t, $sub, $mailData);
        }
        $message = Swift_Message::newInstance($transport);
        $message->setCharset($pCharset);
		//$message->setReadReceiptTo('your@address.tld');
        $message->setPriority(3); //normal, 1:highest
        $message->setFrom(array($groupSenderEmail => $groupName));
        $headers = $message->getHeaders();
        //$headers->addTextHeader('X-CMP', $idCampaign.'CMPEND');
        $headers->addTextHeader('X-software', 'nuevoMailer.com');
		//$headers->addTextHeader('List-Unsubscribe', '<mailto:'.$groupBounceToEmail.'>, <http://www.domain.com/nvmailer/subscriber/optOut.php?email='.$sub["email2"].'&a=2&c='.$idCampaign.'>');
		$message->setReturnPath($groupBounceToEmail);
        if ($groupReplyToEmail) {
            $message->setReplyTo($groupReplyToEmail);
        }
        // CHECKING THE ADDRESS FORMAT.
		if (Swift_Validate::email($sub["email"])) {
        	$message->addTo($sub["email"], $sub["name"]);
            $message->setSubject($psubject);
              // ATTACHMENTS
              if (strlen($pAttachments)>1) {
              	$Attachments  = explode(",", $pAttachments);
                $attachCount  = sizeof($Attachments);
                	for ($i=0; $i<$attachCount; $i++)  {
                  		if (file_exists('../attachments/'.$Attachments[$i])) {
                    	 	$message->attach(Swift_Attachment::fromPath('../attachments/'.$Attachments[$i]));
				  		}
              		}
			  }
              // INLINE IMAGES
              if ($pinlineImages && $groupUseInlineImages==-1) {
                  $embImages  = explode(",", $pinlineImages);
                  $ImgCount  = sizeof($embImages);
                  for ($k=0; $k<$ImgCount; $k++)  {
                  	if (file_exists('../'.$embImages[$k])) {
                      	$pbody_h	= str_ireplace($groupScriptUrl.$embImages[$k],$message->embed(Swift_Image::fromPath('..'.$embImages[$k])), $pbody_h);
					}
                	}
            	}
              if ($type==2) {             // text only
                  $message->setBody($pbody_t, 'text/plain');
              }
              if (!empty($pbody_h)) {   //html
                  $message->setBody($pbody_h, 'text/html');
             }
              if ($type==3) {            //this is for multipart.
                  $message->addPart($pbody_t, 'text/plain');
              }
            try {
                $mailer->send($message, $failures);
                if (!$mailer) {
                     throw new Swift_TransportException($e);
                }
            }
            catch (Swift_TransportException $e){      //catches falied auth, BAD HOST. BAD PORT
				if (!$transport->isStarted()) {
					//echo "Error (File: ".$invalid->getFile().", line ". $invalid->getLine()."): ".$invalid->getMessage();
                    echo $e->getMessage().'<br>';
                    die("Mail Transport is not started.");
                }
                else {
                    echo $e->getMessage().'<br>';
                }
            }
		}
		else {
         	$invalidEmailsFound=$invalidEmailsFound+1;
    	}
$objT->closeDb();
}
?>