<?php
set_time_limit(0);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
include('mailCount.php');
include('../inc/encryption.php');
include('../inc/createGoogleString.php');
include('../inc/extractLinks.php');
require_once('../inc/swiftmailer/lib/swift_required.php');
include('../inc/sendMail.php');

$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$myDay      = myDatenow();
$failures="";
if (@$pdemomode) {
	forDemo2(DEMOMODE_1);
}

(isset($_GET['idCampaign']))?$idCampaign = $_GET['idCampaign']:$idCampaign="";
$pinlineImages="";
$mySQL="SELECT campaignName, joins, idList, mLists, idHtmlNewsletter, idTextNewsletter, htmlNewsletterName, textNewsletterName, urlToSend, emailSubject, idStopEmail, mailCounter, completed, type, confirmed, prefers, idSendFilter, ga_utm_source, fromName, fromEmail, replyToEmail FROM ".$idGroup."_campaigns WHERE idGroup=$idGroup AND idCampaign=$idCampaign";
$result	= $obj->query($mySQL);
$row = $obj->fetch_array($result);
  $pcampaignName		= $row['campaignName'];
  $joins	            = $row['joins'];
  $mLists	            = $row['mLists'];
  $idlist		        = $row['idList'];
  $idHtmlNewsletter     = $row['idHtmlNewsletter'];
  $idTextNewsletter	    = $row['idTextNewsletter'];
  $htmlNewsletterName	= $row['htmlNewsletterName'];
  $textNewsletterName	= $row['textNewsletterName'];
  $urlToSend            = $row['urlToSend'];
  $urlEmailSubject      = $row['emailSubject'];
  $idEmailToStart	    = $row['idStopEmail'];
  $mailCounter	        = $row['mailCounter'];
  $completed	        = $row['completed'];
  $type	                = $row['type'];
  $confirmed		    = $row['confirmed'];
  $prefers		        = $row['prefers'];
  $idSendFilter		    = $row['idSendFilter'];
  $ga_utm_source	    = $row['ga_utm_source'];
  $pfromName			= $row['fromName'];
  $pfromEmail			= $row['fromEmail'];
  $preplyToEmail		= $row['replyToEmail'];

if ($completed==-1) {header("Location: message.php?message=".urlencode(CAMPAIGNSEND_1)."");return false;}
if ($mailCounter==0){$mySQL="UPDATE ".$idGroup."_campaigns set dateStarted='$myDay'WHERE idGroup=$idGroup AND idCampaign=$idCampaign";$obj->query($mySQL);}


$pBday 	    = date("j", strtotime("+$pTimeOffsetFromServer hours"));
$pBmonth 	= date("n", strtotime("+$pTimeOffsetFromServer hours"));
$pNOW       = date("Y-m-d H:i:s", strtotime("+$pTimeOffsetFromServer hours"));

$groupSenderEmail       =	$obj->getSetting("groupSenderEmail", $idGroup);
if ($pfromEmail) 		{$groupSenderEmail=$pfromEmail;}
$groupReplyToEmail      =	$obj->getSetting("groupReplyToEmail", $idGroup);
if ($preplyToEmail) 	{$groupReplyToEmail=$preplyToEmail;}
if ($pfromName) 		{$groupName=$pfromName;}
$groupBounceToEmail     =	$obj->getSetting("groupBounceToEmail", $idGroup);
$groupContactEmail      =	$obj->getSetting("groupContactEmail", $idGroup);
$groupScriptUrl         =	$obj->getSetting("groupScriptUrl", $idGroup);
$groupSite              =	$obj->getSetting("groupSite", $idGroup);
$groupEmailComponent	=	$obj->getSetting("groupEmailComponent", $idGroup);
$groupDebugSendMail      =	$obj->getSetting("groupDebugSendMail", $idGroup);

$groupEncryptionPassword        =	$obj->getSetting("groupEncryptionPassword", $idGroup);
$groupUseInlineImages           =	$obj->getSetting("groupUseInlineImages", $idGroup);
$groupActiveLinkTrackingHtml    =	$obj->getSetting("groupActiveLinkTrackingHtml", $idGroup);
$groupActiveLinkTrackingText    =	$obj->getSetting("groupActiveLinkTrackingText", $idGroup);
$groupActiveViewsTracking       =	$obj->getSetting("groupActiveViewsTracking", $idGroup);
$groupEnableBatchSending        =	$obj->getSetting("groupEnableBatchSending", $idGroup);
$groupBatchSize                 =	$obj->getSetting("groupBatchSize", $idGroup);
$groupBatchInterval             =	$obj->getSetting("groupBatchInterval", $idGroup);
$groupGlobalCharset             =	$obj->getSetting("groupGlobalCharset", $idGroup);
$trackMails                     =   $obj->getSetting("groupTrackMailTo", $idGroup);
//$groupAntiFloodBatch            =   $obj->getSetting("groupAntiFloodBatch", $idGroup);
//$groupAntiFloodPause            =   $obj->getSetting("groupAntiFloodPause", $idGroup);

$limitSQL="";
If ($groupEnableBatchSending=="-1") {
	If (ceil($groupBatchSize)>0) {
		$limitSQL = " LIMIT ".ceil($groupBatchSize)." ";
    }
}
switch ($prefers) {
    case 1:
        $prefSQL = ' AND '.$idGroup.'_subscribers.prefersHtml=-1';
        break;
    case 2:
        $prefSQL = ' AND '.$idGroup.'_subscribers.prefersHtml=0';
        break;
    default:
        $prefSQL='';
}
switch ($confirmed) {
    case 1:
	    $confSQL = ' AND '.$idGroup.'_subscribers.confirmed=-1';
	    break;
	case 2:
		$confSQL = ' AND '.$idGroup.'_subscribers.confirmed=0';
	    break;
	default:
		$confSQL='';
}

$orderSQL = " ORDER BY ".$idGroup."_subscribers.idEmail asc ";

$filterSQL="";
if ($idSendFilter!=0) {
	$filterSQL  = getSendFilterCode($idSendFilter, $idGroup);
    $filterSQL  = str_replace("##pBday##", $pBday, $filterSQL);
    $filterSQL  = str_replace("##pBmonth##", $pBmonth, $filterSQL);
    $filterSQL  = str_replace("##now##", "'".$pNOW."'", $filterSQL);
}
$JOINSQL ="";
if ($joins=="y") {
	if ($mLists!="") {
		$JOINSQL = " AND EXISTS (select idEmail FROM ".$idGroup."_listRecipients WHERE idList in ($mLists) AND ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail)";
	}
	else {
	 	$JOINSQL = " AND EXISTS (select idEmail FROM ".$idGroup."_listRecipients WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail)";	
	}
}
$mySQL2="SELECT ".$idGroup."_subscribers.idEmail, email, name, lastName, subCompany, address, city, state, zip, country, subPhone1, subPhone2, subMobile, subPassword, prefersHtml, dateSubscribed, subBirthDay, subBirthMonth, subBirthYear, dateLastUpdated, dateLastEmailed, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5 
	FROM ".$idGroup."_subscribers 
	WHERE emailIsBanned=0 AND emailIsValid=-1 AND ".$idGroup."_subscribers.idEmail>$idEmailToStart ".$prefSQL .$confSQL 
	.$JOINSQL ." ".$filterSQL .$orderSQL .$limitSQL;
$subsResult = $obj->query($mySQL2);
$rows 	= $obj->num_rows($subsResult);
$newCounter=0;
$invalidEmailsFound=0;
if ($rows) {
    $pCountAllMessages = mailCount($prefers, $confirmed, $mLists, $idSendFilter, $idEmailToStart, 1, $idGroup, $joins);	    //1 is for full count
    switch ($type) {
    case 1:
        $ptypeIs = 'Html';
        $pEmailSubject  = getNewsletterData($idHtmlNewsletter, $idGroup, 0);
        $pHtmlBody      = getNewsletterData($idHtmlNewsletter, $idGroup, 1);
        $pAttachments   = getNewsletterData($idHtmlNewsletter, $idGroup, 2);
        $pCharset       = getNewsletterData($idHtmlNewsletter, $idGroup, 3);
        if ($groupUseInlineImages==-1){$pinlineImages  = extractPicNames($pHtmlBody);}
        $mySQL="UPDATE ".$idGroup."_newsletters SET sent=-1, dateSent='$myDay' WHERE idNewsletter=$idHtmlNewsletter";
        $obj->query($mySQL);
        break;
    case 2:
        $ptypeIs = 'Text';
        $pEmailSubject  = getNewsletterData($idTextNewsletter, $idGroup, 0);
        $pTextBody      = getNewsletterData($idTextNewsletter, $idGroup, 1);
        $pAttachments   = getNewsletterData($idTextNewsletter, $idGroup, 2);
        $pCharset       = getNewsletterData($idTextNewsletter, $idGroup, 3);
        $pHtmlBody="";
        $mySQLt="UPDATE ".$idGroup."_newsletters SET sent=-1, dateSent='$myDay' WHERE idNewsletter=$idTextNewsletter";
        $obj->query($mySQLt);
        break;
	case 3:
        $ptypeIs = 'Multipart';
        $pEmailSubject = getNewsletterData($idHtmlNewsletter, $idGroup, 0);
        $pHtmlBody      = getNewsletterData($idHtmlNewsletter, $idGroup, 1);
        $pTextBody      = getNewsletterData($idTextNewsletter, $idGroup, 1);
        $pAttachments1   = getNewsletterData($idHtmlNewsletter, $idGroup, 2);
		$pAttachments2   = getNewsletterData($idTextNewsletter, $idGroup, 2);
        $pAttachments   = $pAttachments1;
		if ($pAttachments2) {$pAttachments   = $pAttachments1.','.$pAttachments2;}
        $pCharset       = getNewsletterData($idHtmlNewsletter, $idGroup, 3);
        if ($groupUseInlineImages==-1){$pinlineImages  = extractPicNames($pHtmlBody);}
        $mySQL="UPDATE ".$idGroup."_newsletters SET sent=-1, dateSent='$myDay' WHERE idNewsletter=$idHtmlNewsletter";
        $obj->query($mySQL);
        $mySQLt="UPDATE ".$idGroup."_newsletters SET sent=-1, dateSent='$myDay' WHERE idNewsletter=$idTextNewsletter";
        $obj->query($mySQLt);
        break;
    case 4:
        $ptypeIs = 'URL';
        $pEmailSubject  = $urlEmailSubject;
        $pHtmlBody      = getBodyFromUrl($urlToSend, $groupGlobalCharset);
        $pAttachments   = "";
        $pCharset       = $groupGlobalCharset;
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
		$result	= $obj->query($mySQL);
		$rows 	= $obj->num_rows($result);
		if ($rows==0){die("=>At least one SMTP server must be Active and Preferred");}
		$row = $obj->fetch_array($result);
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
            //$transport->setTimeout(20);
            //$transport->start();

	//MORE SMTP servers
	/*	 We define a second transport: B
        $transportB = Swift_SmtpTransport::newInstance("mail.server.com", "25", $groupSmtpSecureConnection);
        if ($groupAuthRequired==-1) {
            $transportB->setUsername("email@server.com");
            $transportB->setPassword("password");}
	*/
	//In the same way we can add more servers: C, D etc


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
	//MORE SMTP: Comment the line above and uncomment the one below:
	//$mailer = new Swift_Mailer(Swift_LoadBalancedTransport::newInstance(array($transport, $transportB)));

    //ANTIFLOOD: limits the number of messages that may be sent during any single SMTP CONNECTION
    //Use AntiFlood to re-connect after X emails, specify a time in seconds to pause for (Z secs)
    if ($groupEmailComponent=="smtp" && $groupAntiFloodBatch>0 && $groupAntiFloodPause>0) {
        $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin($groupAntiFloodBatch, $groupAntiFloodPause));
    }

/*********************************************************************************/
/*********************************************************************************/
    while ($rowSub = $obj->fetch_array($subsResult)) {
        $newCounter=$newCounter+1;
        $mySQLd="UPDATE ".$idGroup."_subscribers SET dateLastEmailed='$myDay', timesMailed=timesMailed+1 WHERE idEmail=".$rowSub["idEmail"];
        $obj->query($mySQLd);

        $psubject	            = $pEmailSubject;       //original value
        $sub["idEmail"]         = $rowSub['idEmail'];
        $pidEmail               = $sub["idEmail"];
        $sub["email"]           = $rowSub['email'];
        $sub["email2"]          = myEncrypt($rowSub['email'], $groupEncryptionPassword);
        $sub["name"]            = $rowSub['name'];
        $sub["lastName"]        = $rowSub['lastName'];
        $sub["subCompany"]      =  $rowSub['subCompany'];
        $sub["address"]         =  $rowSub['address'];
        $sub["city"]            =  $rowSub['city'];
        $sub["state"]           =  $rowSub['state'];
        $sub["zip"]             =  $rowSub['zip'];
        $sub["country"]         =  $rowSub['country'];
        $sub["subPhone1"]       =  $rowSub['subPhone1'];
        $sub["subPhone2"]       =  $rowSub['subPhone2'];
        $sub["subMobile"]       =  $rowSub['subMobile'];
        $sub["subPassword"]     =  $rowSub['subPassword'];
        $sub["prefersHtml"]     =  $rowSub['prefersHtml'];  //?
        $sub["dateSubscribed"]  =  $rowSub['dateSubscribed'];
        $sub["subBirthDay"]     =  $rowSub['subBirthDay'];
        $sub["subBirthMonth"]   =  $rowSub['subBirthMonth'];
        $sub["subBirthYear"]    =  $rowSub['subBirthYear'];
        $sub["dateLastUpdated"] =  $rowSub['dateLastUpdated'];
        $sub["dateLastEmailed"] =  $rowSub['dateLastEmailed'];
        $sub["customSubField1"] =  $rowSub['customSubField1'];
        $sub["customSubField2"] =  $rowSub['customSubField2'];
        $sub["customSubField3"] =  $rowSub['customSubField3'];
        $sub["customSubField4"] =  $rowSub['customSubField4'];
        $sub["customSubField5"] =  $rowSub['customSubField5'];
        $sub["fullName"] =($sub["name"] || $sub["lastName"])? $sub["name"].' '.$sub["lastName"]:$sub["email"];
        //personalize subject line
        $psubject	= str_ireplace("#subname#",     $sub["name"], $psubject);
		//$psubject	= str_ireplace("#customSubField1#",     $sub["customSubField1"], $psubject);
        $psubject	= str_ireplace("#sublastname#", $sub["lastName"], $psubject);
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
        $headers->addTextHeader('X-CMP', $idCampaign.'CMPEND');
        //$headers->addTextHeader('X-software', 'nuevoMailer.com');
		//$headers->addTextHeader('List-Unsubscribe', '<mailto:'.$groupBounceToEmail.'>, <http://www.domain.com/nvmailer/subscriber/optOut.php?email='.$sub["email2"].'&a=2&c='.$idCampaign.'>');
		$message->setReturnPath($groupBounceToEmail);
        if ($groupReplyToEmail) {
            $message->setReplyTo($groupReplyToEmail);
        }
        $headers->addTextHeader('X-SID', $pidEmail.'SIDEND');

        // CHECKING THE ADDRESS FORMAT.
		if (Swift_Validate::email($sub["email"])) {
        	$message->addTo($sub["email"], $sub["fullName"]);
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
              //$mailer->send($message, $failures);
              // OR $mailer->send($message);
              // OR

            try {
                $mailer->send($message, $failures);
                if (!$mailer) {
                     throw new Swift_TransportException($e);
                }
                //else {
                //    $mySQL5="UPDATE ".$idGroup."_campaigns set mailCounter=mailCounter+1, idStopEmail=".$rowSub["idEmail"]." WHERE idCampaign=$idCampaign";
                //    $obj->query($mySQL5);
                //}
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
			//the total counter will not include failed emails.
	     	$mySQL5="UPDATE ".$idGroup."_campaigns set mailCounter=mailCounter+1, idStopEmail=".$rowSub["idEmail"]." WHERE idCampaign=$idCampaign";
	     	$obj->query($mySQL5);
		}
		else {
       		$catch="UPDATE ".$idGroup."_subscribers set emailIsValid=0 WHERE idEmail=".$pidEmail;
         	$obj->query($catch);
         	$invalidEmailsFound=$invalidEmailsFound+1;
         	$mySQL5="UPDATE ".$idGroup."_campaigns set idStopEmail=".$rowSub["idEmail"]." WHERE idCampaign=$idCampaign";
         	$obj->query($mySQL5);
    	}


    }   // subscribers while loop ends
    //update lists
    $thisDate = myDatenow();
    $whereLists= listsWhere($mLists, $idGroup);
    $mySQLl="UPDATE ".$idGroup."_lists set lastDateMailed='$thisDate' ".$whereLists;
    $obj->query($mySQLl);
    //update the date-time used for the smtp server
	if ($groupEmailComponent=="smtp" && $idSmtp) {
		$mySMTP="UPDATE ".$idGroup."_smtpServers set smtpLastUsed='$thisDate' WHERE idSmtp=$idSmtp";
		$obj->query($mySMTP);	
	}
	//it send the batch -> go to pause if there are more emails to send
    if ($groupEnableBatchSending=="-1") {
        if ($newCounter==ceil($groupBatchSize) && $newCounter!=ceil($pCountAllMessages)) {
            $obj->closeDb();
            header("Location: campaignPause.php?idCampaign=$idCampaign");
            return false;
        }
    }
}           //$rows from SELECT query
else {
    //echo "that point here";
}	        //lookin for subscribers

// close the campaign
$dateEnded = myDatenow();
$mySQL7="UPDATE ".$idGroup."_campaigns set completed=-1, dateCompleted='$dateEnded', mailError='' WHERE idCampaign=$idCampaign";
$obj->query($mySQL7);

$mySQL8="SELECT mailCounter from ".$idGroup."_campaigns WHERE idCampaign=$idCampaign";
$r          = $obj->query($mySQL8);
$row       = $obj->fetch_array($r);
$mailCounter= $row['mailCounter'];

$strInvalid="";
if ($invalidEmailsFound) {$strInvalid = '<br>['.HOME_16.': '.$invalidEmailsFound.']';}

$adminname      = getadminname($sesIDAdmin, $idGroup);
$adminemail     = getadminemail($sesIDAdmin, $idGroup);
$emailSubject   = CAMPAIGNSEND_6;
$htmlEmailBody  = CAMPAIGNSEND_7 .$mailCounter."<br>".CAMPAIGNSEND_2 .$idCampaign."<br>".CAMPAIGNCREATE_54.': '.$pcampaignName."<br>".$strInvalid;
sendMail($idGroup, $adminemail, $adminname, $emailSubject, $htmlEmailBody, $textEmailBody="", $attachments="", $charset="", "h");

include('headerNoMenu.php');
?>
<span class="title"><?php echo CAMPAIGNSEND_4; ?></span>
<p>
<?php echo "<br>" .CAMPAIGNSEND_3. $mailCounter;?>
</p>
<p>
<?php
if ($invalidEmailsFound) {
    echo $strInvalid;
}?>

</p>


<p>
	<input class="submit" type ="button" name = "button" value = "<?php echo CAMPAIGNSEND_5; ?>" onclick = "window.close();">
</p>

<?php
/*if ($failures && $groupEnableBatchSending!="-1") {
  echo '<br />There was a problem with the following emails but you should not remove them yet:<br />';
    foreach($failures as $key => $value) {echo $value.'<br />';}
}*/

$obj->free_result($result);
$obj->free_result($subsResult);
$obj->closeDb();
include('footer.php');
?>