<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/plain");

include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
include('mailCount.php');
$obj = new db_class();
if (@$pdemomode) {
	forDemo2("demo");
}

$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$myDay 			= myDatenow();
$pidCampaign 	= $_REQUEST['idCampaign'];
$pOldCampaignId	= $pidCampaign;
$pFilterOption 	= $_REQUEST['FilterOption'];
$plinks 		= $_REQUEST['links'];
if ($pFilterOption==6 OR $pFilterOption==7) {
	$pidLink=substr($plinks, 0, strpos($plinks, "@"));
	$plinkUrl=substr($plinks, strpos($plinks, "@")+1);
}
/*Select an option */
switch ($pFilterOption) {
	case 1: // Did not click and did not open at all
		$pfollowUpNotes = ALLSTATS_52 .$pOldCampaignId;
		$pReqSendFilterCode = " AND (NOT EXISTS (SELECT distinct idEmail FROM ".$idGroup."_clickStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND idCampaign=$pOldCampaignId) ";
		$pReqSendFilterCode .= "AND NOT EXISTS (SELECT distinct idEmail FROM ".$idGroup."_viewStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$pOldCampaignId))";
	break;
    case 2: // Did not open at all
		$pfollowUpNotes = ALLSTATS_53 .$pOldCampaignId;
        $pReqSendFilterCode = " AND NOT EXISTS (SELECT distinct idEmail FROM ".$idGroup."_viewStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$pOldCampaignId) ";
	break;
    case 3: // Did not click at all
		$pfollowUpNotes = ALLSTATS_54 .$pOldCampaignId;
        $pReqSendFilterCode = " AND NOT EXISTS  (SELECT distinct idEmail FROM ".$idGroup."_clickStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND idCampaign=$pOldCampaignId) ";
	break;
    case 4: // Opened at least once
		$pfollowUpNotes = ALLSTATS_55 .$pOldCampaignId;
		$pReqSendFilterCode = " AND EXISTS (SELECT distinct idEmail FROM ".$idGroup."_viewStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$pOldCampaignId) ";
	break;
    case 5: //Clicked at least one link
		$pfollowUpNotes = ALLSTATS_56 .$pOldCampaignId;
		$pReqSendFilterCode = " AND EXISTS (SELECT distinct idEmail FROM ".$idGroup."_clickStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND idCampaign=$pOldCampaignId) ";
    break;
    case 6: // Clicked a specific link
		$pfollowUpNotes = ALLSTATS_57 .$plinkUrl .ALLSTATS_58 .$pOldCampaignId;
	    $pReqSendFilterCode = ' AND EXISTS (SELECT distinct idEmail FROM '.$idGroup.'_clickStats WHERE '.$idGroup.'_subscribers.idEmail='.$idGroup.'_clickStats.idEmail AND idCampaign='.$pOldCampaignId.' AND idLink='.$pidLink.')';
	break;
    case 7: // Did NOT click a specific link
		$pfollowUpNotes = ALLSTATS_59 .$plinkUrl .ALLSTATS_58 .$pOldCampaignId;
        $pReqSendFilterCode = ' AND NOT EXISTS (SELECT distinct idEmail FROM '.$idGroup.'_clickStats WHERE '.$idGroup.'_subscribers.idEmail='.$idGroup.'_clickStats.idEmail AND idCampaign='.$pOldCampaignId.' AND idLink='.$pidLink.')';
    break;
    default:
    $pfollowUpNotes ="";
    $pReqSendFilterCode="";
}

//Get old campaign data
$mySQL1="SELECT idCampaign, idList, listName, idHtmlNewsletter as HtmlNsl, htmlNewsletterName, textNewsletterName, idTextNewsletter as TxtNsl, notes, type, confirmed, prefers, idSendFilter, urlToSend, emailSubject, sendFilterDesc, mLists, joins, fromName, fromEmail, replyToEmail, campaignName FROM ".$idGroup."_campaigns WHERE idCampaign=$pidCampaign";
$result	= $obj->query($mySQL1);
//$rows 	= $obj->num_rows($result);
$row = $obj->fetch_array($result);
$pOldCampaignId     = $row['idCampaign'];
$pidList 		    = $row['idList'];
$plistName 		    = $row['listName'];
$pidHtmlnewsletter 	= $row['HtmlNsl'];
$pidTextnewsletter 	= $row['TxtNsl'];
$phtmlNewsletterName= $row['htmlNewsletterName'];
$ptextNewsletterName= $row['textNewsletterName'];
$notes		 	    = $row['notes'];
$type		 	    = $row['type'];
$confirmed		    = $row['confirmed'];
$prefers 		    = $row['prefers'];
$pOldIdSendFilter	= $row['idSendFilter'];	//(if not 0 then there was one used), get the code and description and append the new one
$urlToSend		    = $row['urlToSend'];
$emailSubject		= $row['emailSubject'];
$pOldSendFilterDesc	= $row['sendFilterDesc'];
$pMoreNotes 		= ALLSTATS_60 .$pfollowUpNotes;
$notes		 	    .= "\r\n" ."\r\n"  .ALLSTATS_61 ."\r\n" .$pMoreNotes;
$mLists             = $row['mLists'];
$joins              = $row['joins'];
$fromName			= $row['fromName'];
$fromEmail			= $row['fromEmail'];
$replyToEmail		= $row['replyToEmail'];
$campaignName		= $row['campaignName'];

if ($pOldIdSendFilter!=0) {	//A filter was used for previous mailing
    if (!filterDeleted($pOldIdSendFilter, $idGroup)) { 		//it exists we found an old filter
    	$pOldSendFilterCode = getSendFilterCode($pOldIdSendFilter, $idGroup);
    	$pOldSendFilterDesc	= getSendFilterDesc($pOldIdSendFilter, $idGroup);
    	$pNewSendFilterCode = $pOldSendFilterCode .$pReqSendFilterCode;
    	$pNewSendFilterDesc	= $pOldSendFilterDesc .' and '.$pfollowUpNotes;
    }
    else { //old filter does not exist
    	$pNewSendFilterCode 	= $pReqSendFilterCode;
    	$pNewSendFilterDesc	    = $pMoreNotes;
    }
}
else {
    $pNewSendFilterCode = $pReqSendFilterCode;
    $pNewSendFilterDesc	= $pMoreNotes;
}
$pNewSendFilterCode=dbQuotes($pNewSendFilterCode);
//insert into filters table
$mySQL3="INSERT into ".$idGroup."_sendFilters (sendFilterCode, sendFilterDesc, createdBy, idGroup) VALUES ('".$pNewSendFilterCode."', '".dbQuotes($pNewSendFilterDesc)."', $sesIDAdmin, $idGroup)";
$obj->query($mySQL3);
$plastFilterId =  $obj->insert_id();

//CREATE THE NEW MAILLOG ENTRY
$idStopEmail		= 0;
$pcreationDate  =$myDay;
$mySQLm1="INSERT INTO ".$idGroup."_campaigns (idList, listName, idHtmlNewsletter, idTextNewsletter, htmlNewsletterName, textNewsletterName, dateCreated, idAdmin, notes, type, confirmed, prefers, idSendFilter, urlToSend, emailSubject, sendFilterDesc, idGroup, joins, mLists, fromName, fromEmail, replyToEmail, campaignName) VALUES ($pidList, '".dbQuotes($plistName)."', $pidHtmlnewsletter,  $pidTextnewsletter, '".dbQuotes($phtmlNewsletterName)."', '".dbQuotes($ptextNewsletterName)."', '$pcreationDate', $sesIDAdmin, '".dbQuotes($notes)."', $type, $confirmed, $prefers, $plastFilterId, '".dbQuotes($urlToSend)."', '".dbQuotes($emailSubject)."', '".dbQuotes($pNewSendFilterDesc)."', $idGroup, '$joins', '$mLists', '$fromName', '$fromEmail', '$replyToEmail', '$campaignName')";
$obj->query($mySQLm1);
echo "ok";
$obj->closeDb();
?>