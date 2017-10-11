<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj = new db_class();
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/html; charset='.$groupGlobalCharset.'');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
include('mailCount.php');
$campaignname		= $_REQUEST['campaignname'];

@$fromName			= $_REQUEST['fromName'];
@$fromEmail			= $_REQUEST['fromEmail'];
@$replyToEmail		= $_REQUEST['replyToEmail'];

$pidTextNewsletter	= $_REQUEST['idtextbody'];
$pidHtmlNewsletter	= $_REQUEST['idhtmlbody'];
$purltosend		    = $_REQUEST['urltosend'];
$pemailsubject		= $_REQUEST['emailsubject'];
$pprefers		    = $_REQUEST['prefers'];
$pconfirmed		    = $_REQUEST['confirmed'];
$pidSendFilter		= $_REQUEST['idSendFilter'];
$paction			= $_REQUEST['action'];
@$listsTicked		= count($_REQUEST['idList']);
$recipients         = $_REQUEST['recipients'];

@$pgoogle_check	=	$_REQUEST['google_check'];
$putm_source	=	$_REQUEST['utm_source'];
$putm_medium	=	$_REQUEST['utm_medium'];
$putm_campaign	=	$_REQUEST['utm_campaign'];
$putm_term		=	$_REQUEST['utm_term'];
$putm_content	=	$_REQUEST['utm_content'];

if ($pgoogle_check=="-1" && $putm_source<>"") {
/*	$pnotes			= "Google Analytics" .CAMPAIGNCREATE_52."\r\n";
	$pnotes			= $pnotes."utm_source: ".$putm_source."\r\n";
	$pnotes			= $pnotes."utm_medium: ".$putm_medium."\r\n";
	$pnotes			= $pnotes."utm_campaign: ".$putm_campaign."\r\n";
	$pnotes			= $pnotes."utm_term: ".$putm_term."\r\n";
	$pnotes			= $pnotes."utm_content: ".$putm_content."\r\n";
*/
	$pnotes			="";
} else {
	$pnotes			="";
	$putm_source	="";
	$putm_medium	="";
	$putm_campaign	="";
	$putm_term		="";
	$putm_content	="";
}





$joins="";
$sqlLists="";
$justLists="";
if ($listsTicked!=0) {
    for ($z=0; $z<$listsTicked; $z++)  {
        $justLists .= $_REQUEST['idList'][$z].', ';
    }
    $justLists = rtrim($justLists, ", ");
}

if ($recipients=="allLists") {
    $joins="y";$pidlist=0;$plistname=CAMPAIGNCREATE_11;}
if ($recipients=="selectedLists") {
    $joins="y";$pidlist=0;$plistname=CAMPAIGNCREATE_29.': '.$justLists;}
if ($recipients=="selectedLists" && $listsTicked==1) {
    $joins="y";$pidlist=$_REQUEST['idList'][0];$plistname=dbQuotes(CAMPAIGNCREATE_38).$pidlist.': '.dbQuotes(getlistname($pidlist, $idGroup));}
if ($recipients=="allSubs") {
    $joins="n";$pidlist=-1;$plistname=CAMPAIGNCREATE_28;}

if ($paction=="count") {	//IT WILL COUNT
    $pidEmailToStart=0;
    $check=1; 	//1 is for full count
	$xRecords = mailCount($pprefers, $pconfirmed, $justLists, $pidSendFilter, $pidEmailToStart, $check, $idGroup, $joins);
	echo $xRecords;
    die;
}

$pSendFilterDesc="";
if ($pidSendFilter!=0) {
	$pSendFilterDesc = getSendFilterDesc($pidSendFilter, $idGroup);
}
if ($paction="createentry") { //IT WILL ONLY CREATE A LOG ENTRY
    $phtmlSubject="";
    $ptextSubject="";

    $pidEmailToStart=0;
    $check=0; 	//0 only checks
    $xRecords = 	mailCount($pprefers, $pconfirmed, $justLists, $pidSendFilter, $pidEmailToStart, $check, $idGroup, $joins);
    //die($xRecords);
	//returning 0 is accepted for scheduling.

	if ($pidTextNewsletter!=0 || $pidHtmlNewsletter!=0) {
		$pemailsubject="";           //reset the custom subject.
	}

	if ($pidTextNewsletter!=0 AND $pidHtmlNewsletter!=0) {
		$ptype = "3";           //multipart
	}
	if ($pidTextNewsletter==0 AND $pidHtmlNewsletter!=0) {
		$ptype = "1";           //html only
    }
    //GET HTML TITLE
    if ($pidHtmlNewsletter!=0) {
        $phtmlSubject=getnewslettername($pidHtmlNewsletter, $idGroup);
    }
	//GET TEXT TITLE
    if ($pidTextNewsletter!=0) {
        $ptextSubject=getnewslettername($pidTextNewsletter, $idGroup);
    }
    //JUST TEXT, NO HTML NEWSLETTER, NEEDED INFO FOR CREATING THE MAILLOG ENTRY
    if 	($pidHtmlNewsletter== 0 AND $purltosend=="") {
		$ptype			=	"2"; //text only
    }
    //SEND A URL
    if (!empty($purltosend)) {
			$plogsubject    = $pemailsubject.' (URL)';
			$ptype		    = "4";	//URL send
	}
    //CREATE A MAILLOG ENTRY
    $dateCreated =myDatenow();

    if (@$pdemomode) {
        forDemo2("demo");
    }
   $mySQLm1="INSERT INTO ".$idGroup."_campaigns (campaignName, idGroup, joins, idList, listName, idHtmlNewsletter, htmlNewsletterName, textNewsletterName, dateCreated, idAdmin, type, idTextNewsletter, confirmed, prefers, idSendFilter, urlToSend, emailSubject, sendFilterDesc, mLists, ga_utm_source, ga_utm_medium, ga_utm_campaign, ga_utm_term, ga_utm_content, notes, fromName, fromEmail, replyToEmail) VALUES ('".dbQuotesArr($campaignname)."', $idGroup, '$joins', $pidlist, '$plistname', $pidHtmlNewsletter, '".dbQuotesArr($phtmlSubject)."',  '".dbQuotesArr($ptextSubject)."', '$dateCreated', $sesIDAdmin, $ptype, $pidTextNewsletter, $pconfirmed, $pprefers, $pidSendFilter, '".dbQuotes($purltosend)."', '".dbQuotes($pemailsubject)."', '".dbQuotes($pSendFilterDesc)."', '$justLists', '".dbQuotes($putm_source)."', '".dbQuotes($putm_medium)."', '".dbQuotes($putm_campaign)."', '".dbQuotes($putm_term)."', '".dbQuotes($putm_content)."', '".$pnotes."', '".dbQuotesArr($fromName)."', '".dbQuotesArr($fromEmail)."', '".dbQuotesArr($replyToEmail)."')";
   //echo $mySQLm1;
   $obj->query($mySQLm1);
}
$obj->closeDb();
?>
