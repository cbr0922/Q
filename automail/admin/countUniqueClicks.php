<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');

@$predirect 		= $_REQUEST['redirect'];
(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
@$selectedCampaigns = $_REQUEST['selectedCampaigns'];
@$plinkurl 		= $_REQUEST['linkUrl'];
$strLink="";
if ($plinkurl) {$strLink = "&linkUrl=".urlencode($plinkurl); }

@$pSettingValue	= $_REQUEST['turn'];
@$pshowMsg 		= $_REQUEST['showMsg'];

if ($pshowMsg=="-1") {
	$msgString = "&message=".urlencode(COUNTUNIQUECLICKS_1);
} elseif ($pshowMsg=="0") {
		$msgString = "&message=".urlencode(COUNTUNIQUECLICKS_2);
} else {$msgString="";}

$obj->query("UPDATE ".$idGroup."_groupSettings SET groupShowUniqueClicks=$pSettingValue WHERE idGroup=$idGroup");
$obj->closeDb();

if ($predirect=="views") {
	header("Location: viewStatsSubs.php?idCampaign=$fidCampaign&$msgString");
}
if ($predirect=="clicks") {
    header("Location: clickStatsSubs.php?idCampaign=$fidCampaign&$msgString".$strLink);
}
if ($predirect=="clicksAggr") {
    header("Location: clickStatsSubsAggr.php?selectedCampaigns=$selectedCampaigns&$msgString".$strLink);
}

if ($predirect=="uvc") {
    header("Location: uvcStatsSubs.php?idCampaign=$fidCampaign&$msgString".$strLink);
}
?>