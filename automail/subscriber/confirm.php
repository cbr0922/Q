<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../admin/includes/auxFunctions.php');
include('../inc/sendMail.php');
include('../inc/sendLast.php');
include('../inc/languages.php');

$groupEncryptionPassword    = $obj->getSetting("groupEncryptionPassword", $idGroup);
$groupShowWelcomeScreen		= $obj->getSetting("groupShowWelcomeScreen", $idGroup);		//also editable in settings page
$groupWelcomeScreen			= $obj->getSetting("groupWelcomeScreen", $idGroup);
$groupWelcomeUrl			= $obj->getSetting("groupWelcomeUrl", $idGroup);
$groupSendWelcomeEmail		= $obj->getSetting("groupSendWelcomeEmail", $idGroup);		//also editable in settings page
$groupWelcomeEmailBody		= $obj->getSetting("groupWelcomeEmailBody", $idGroup);
$groupWelcomeEmailBodyT		= $obj->getSetting("groupWelcomeEmailBodyT", $idGroup);
$groupWelcomeEmailSubject	= $obj->getSetting("groupWelcomeEmailSubject", $idGroup);
$mailData["groupName"]      =	$obj->getSetting("groupName", $idGroup);
$groupSenderEmail           =	$obj->getSetting("groupSenderEmail", $idGroup);
$groupReplyToEmail          =	$obj->getSetting("groupReplyToEmail", $idGroup);
$mailData["groupContactEmail"]      =	$obj->getSetting("groupContactEmail", $idGroup);
$mailData["groupScriptUrl"]         =	$obj->getSetting("groupScriptUrl", $idGroup);
$mailData["groupSite"]              =	$obj->getSetting("groupSite", $idGroup);
$pTimeOffsetFromServer		=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$myDay      				= 	myDatenow();
$mailData["date_time"]			= date("M d Y, H:i" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));
$mailData["date_time_2"]		= date("l d, F Y" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));
$groupEmailComponent =	$obj->getSetting("groupEmailComponent", $idGroup);
$groupDebugSendMail      =	$obj->getSetting("groupDebugSendMail", $idGroup);
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);

$emlEn     = dbQuotes(dbProtect($_GET['e2'],400));
$pEmail    = myDecrypt($emlEn, $groupEncryptionPassword);
$sub["email"]=$pEmail ;
$sub["email2"]  = myEncrypt($pEmail, $groupEncryptionPassword);
$ppassword = dbQuotes(dbProtect($_GET['psw'],40));
$sub["subPassword"]=$ppassword;

(isset($_GET['c']))?$idCampaign=dbQuotes(dbProtect($_GET['c'],500)):$idCampaign="0";
if ($idCampaign==-1 ) {
	echo SUBACCOUNT_71;
	return false;
}
if (!$pEmail && !$ppassword) {echo SUBACCOUNT_35;die;}
//get subscriber info
$mySQL2="select idEmail, name, lastName, subCompany, address, zip, state, country, city, subPhone1, subPhone2, subMobile, subPassword, prefersHtml, customSubField1, customSubField2,
		customSubField3, customSubField4, customSubField5, subBirthDay, subBirthMonth, subBirthYear from ".$idGroup."_subscribers WHERE email='".$pEmail."' AND subPassword='".$ppassword."'";
$result = $obj->query($mySQL2);
if ($obj->num_rows($result)!=1) {
	echo SUBACCOUNT_21;
    die;
} else {
    $rowSub = $obj->fetch_array($result);
	$sub["idEmail"]	    = $rowSub['idEmail'];
	$sub["name"] 	=  $rowSub['name'];
	$sub["lastName"]= $rowSub['lastName'];
	$sub["prefers"] 	= $rowSub['prefersHtml'];
    if ($sub["name"] || $sub["lastName"]) {
        $pfullName = $sub["name"].' '.$sub["lastName"];
    }
    else {$pfullName=$sub["email"];}
		$sub["subCompany"]	= $rowSub["subCompany"];
		$sub["address"]	= $rowSub["address"];
		$sub["city"]	= $rowSub["city"];
		$sub["state"]	= $rowSub["state"];
		$sub["zip"]	= $rowSub["zip"];
		$sub["country"]	= $rowSub["country"];
		$sub["subPhone1"]	= $rowSub["subPhone1"];
		$sub["subPhone2"]	= $rowSub["subPhone2"];
		$sub["subMobile"]	= $rowSub["subMobile"];
		$sub["subBirthDay"]	= $rowSub["subBirthDay"];
		$sub["subBirthMonth"]	= $rowSub["subBirthMonth"];
		$sub["subBirthYear"]	= $rowSub["subBirthYear"];
		$sub["customSubField1"]	= $rowSub["customSubField1"];
		$sub["customSubField2"]	= $rowSub["customSubField2"];
		$sub["customSubField3"]	= $rowSub["customSubField3"];
		$sub["customSubField4"]	= $rowSub["customSubField4"];
		$sub["customSubField5"]	= $rowSub["customSubField5"];
		// list-listing
		$listNamesDivider = "<br>";
		$listNamesDividerT = "\r\n";
		$listListing  = "";
		$listListingT = "";
		$mailData["listListing"]="";
		$mailData["listListingT"]="";
		$mySQL4="SELECT idList from ".$idGroup."_listRecipients WHERE idList in (SELECT idList from ".$idGroup."_lists WHERE isPublic=-1) AND idEmail=".$sub["idEmail"];
		$checked = $obj->query($mySQL4);
	   	if ($obj->num_rows($checked)>0) {
			while ($row = $obj->fetch_array($checked)){
				$zList   = dbQuotes(dbProtect($row['idList'],500));
				$addList = getlistname($zList, $idGroup);
				$listListing = $listListing.$addList.$listNamesDivider;
		        $listListingT = $listListingT.$addList.$listNamesDividerT;
			}
		    $mailData["listListing"]=$listListing;
		    $mailData["listListingT"]=$listListingT;
	  	}

    //update subscriber set to confirm
    $mySQL="update ".$idGroup."_subscribers set confirmed=-1 WHERE email='".$pEmail."'";
    $obj->query($mySQL);
    
	if ($groupSendWelcomeEmail==-1) {    //prepare and send the normal welcome email and message
        $pEmailSubject   =	confirmationdata($groupWelcomeEmailSubject,$sub, $mailData);
        $pEmailBody     = confirmationdata($groupWelcomeEmailBody, $sub, $mailData);
        $pEmailBodyT    = confirmationdata($groupWelcomeEmailBodyT, $sub, $mailData);
        $attachments="";
        sendMail($idGroup, $sub["email"], $pfullName,  $pEmailSubject, $pEmailBody, $pEmailBodyT, $attachments, $groupGlobalCharset, "m");
    }
    $sub["prefers"] = "-1";
    sendLast($sub["idEmail"], $mailData, $idGroup);
	if ($groupShowWelcomeScreen==-1) {
        echo confirmationdata($groupWelcomeScreen, $sub, $mailData);
    } else {header("Location:$groupWelcomeUrl");die;}
    //sendLast($sub["idEmail"], $mailData, $idGroup);
}
$obj->closeDb();
?>
