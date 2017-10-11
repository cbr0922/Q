<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('./includes/languages.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
$obj 		= new db_class();
if (@$pdemomode) {
	forDemo("smtpForm.php", DEMOMODE_1);
}
@$idSmtp			       	= dbQuotes($_REQUEST['idSmtp']);
@$groupAuthRequired       	= dbQuotes($_REQUEST['groupAuthRequired']);
@$groupSmtpServer       	= dbQuotes($_REQUEST['groupSmtpServer']);
@$groupSmtpUsername       	= dbQuotes($_REQUEST['groupSmtpUsername']);
@$groupSmtpPassword       	= dbQuotes($_REQUEST['groupSmtpPassword']);
@$groupSmtpPort       		= dbQuotes($_REQUEST['groupSmtpPort']);
@$groupSmtpSecureConnection	= dbQuotes($_REQUEST['groupSmtpSecureConnection']);
@$groupAntiFloodBatch       = dbQuotes($_REQUEST['groupAntiFloodBatch']);
@$groupAntiFloodPause       = dbQuotes($_REQUEST['groupAntiFloodPause']);
@$isActive			       = dbQuotes($_REQUEST['isActive']);
@$isPreferred		       = dbQuotes($_REQUEST['isPreferred']);
if ($isActive!=-1) {$isActive=0;}
if ($isPreferred!=-1) { $isPreferred=0;}

@$paction	   		= $_REQUEST['action'];

if ($paction=="add" && $groupSmtpServer && $groupSmtpPort) {
	$mySQL="INSERT into ".$idGroup."_smtpServers (smtpServer, smtpUsername, smtpPassword, smtpPort, smtpSecureConnection, smtpAuthRequired, smtpAntiFloodBatch, smtpAntiFloodPause, idGroup) 
	VALUES ('$groupSmtpServer', '$groupSmtpUsername', '$groupSmtpPassword', '$groupSmtpPort', '$groupSmtpSecureConnection', '$groupAuthRequired', '$groupAntiFloodBatch', '$groupAntiFloodPause', $idGroup)";
	$message=SMTPSRV_9;
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: smtpForm.php?message=".urlencode($message)."");
}
elseif ($paction=="delete" && $idSmtp) {
	$mySQL="DELETE from ".$idGroup."_smtpServers WHERE idGroup=$idGroup AND idSmtp=$idSmtp";
 	$message=SMTPSRV_10;
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: smtpForm.php?message=".urlencode($message)."");

}
elseif ($paction=="update" && $idSmtp && $groupSmtpServer && $groupSmtpPort) {	
	
	if ($paction=="update" && $isPreferred==-1) {	//set all others as non-preferred
		$mySQL="UPDATE ".$idGroup."_smtpServers SET isPreferred=0 WHERE idSmtp<>$idSmtp";
		$obj->query($mySQL);
	}

	$mySQL="UPDATE ".$idGroup."_smtpServers SET 
	smtpServer='$groupSmtpServer', smtpUsername='$groupSmtpUsername',
	smtpPassword='$groupSmtpPassword', smtpPort='$groupSmtpPort', 
	smtpSecureConnection='$groupSmtpSecureConnection', smtpAuthRequired='$groupAuthRequired', 
	smtpAntiFloodBatch='$groupAntiFloodBatch', smtpAntiFloodPause='$groupAntiFloodPause',
	isActive=$isActive, isPreferred=$isPreferred 
 	WHERE idGroup=$idGroup AND idSmtp=$idSmtp";
	$message=SMTPSRV_11;
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: smtpForm.php?message=".urlencode($message)."");
}
else {
	header("Location: smtpForm.php?message=".urlencode(SMTPSRV_12)."");
}
?>