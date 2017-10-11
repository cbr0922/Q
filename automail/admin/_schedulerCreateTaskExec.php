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
$groupName 	            =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	"Y-m-d H:i:s";

$pTimeOffsetFromServerN 	= -$pTimeOffsetFromServer;

$idCampaign 				= $_POST["idCampaign"];

$dateTaskCreated 	    = myDatenow();
$activationDateTime     = $_POST["activationDate"];
$activationHour			= $_POST["activationTimeH"];
$activationMinute		= $_POST["activationTimeM"];
$type 					= $_POST["type"];

$activationDateTime	= date("Y-m-d" , strtotime($activationDateTime)).' '.$activationHour.':'.$activationMinute.':00';
$activationDateTime	= addOffset($activationDateTime, -$pTimeOffsetFromServer, $groupDateTimeFormat);

if ($type=="repeatevery") {
	$NumberOfMessagesToSend = $_POST["numberOfMessagesToSend"];
	$RepeatEveryPeriodValue = $_POST["RepeatEveryPeriodValue"];
	$repeatEveryPeriod      = $_POST["RepeatEveryPeriod"];
    switch ($repeatEveryPeriod) {
	case "we":
   		$multiplier = 60*60*24*7;
   		$memoDetails = SCHEDULERTASKS_39;
        break;
	case "da":
   		$multiplier = 60*60*24;
   		$memoDetails = SCHEDULERTASKS_40;
        break;
   	case "ho":
   		$multiplier = 60*60;
   		$memoDetails = SCHEDULERTASKS_41;
        break;
   	case "mi":
   		$multiplier = 60;
   		$memoDetails = SCHEDULERTASKS_42;
        break;
    default:
    }
	$RepeatEveryXseconds    = $multiplier*$RepeatEveryPeriodValue;
	$RepeatDetailsMemo		= $RepeatEveryPeriodValue.' / ' .$memoDetails;
}
else {
	//we are sending all at once
	$NumberOfMessagesToSend=0;
	$RepeatEveryXseconds=0;
	$RepeatDetailsMemo="";
}

@$RecurringEvent		= $_POST["recurringEvent"];
if ($RecurringEvent!="-1") {$RecurringEvent="0";}
if ($RecurringEvent=="-1") {
	$ReactivatePeriodValue	= $_POST["reactivatePeriodValue"];
	$ReactivatePeriod	    = $_POST["reactivatePeriod"];

	switch ($ReactivatePeriod) {
	case "mo":
		$multiplier = 60*60*24*30;
		$ReactivateDetailsMemo = SCHEDULERTASKS_38;
        break;
	case "we":
		$multiplier = 60*60*24*7;
		$ReactivateDetailsMemo = SCHEDULERTASKS_39;
        break;
	case "da":
		$multiplier = 60*60*24;
		$ReactivateDetailsMemo = SCHEDULERTASKS_40;
        break;
	case "ho":
		$multiplier = 60*60;
		$ReactivateDetailsMemo = SCHEDULERTASKS_41;
        break;
	case "mi":
		$multiplier = 60;
		$ReactivateDetailsMemo = SCHEDULERTASKS_42;
        break;
        default:
	}
	$ReactivateAfterXseconds	= $multiplier*$ReactivatePeriodValue;
	$ReactivateDetailsMemo		= $ReactivatePeriodValue.' / ' .$ReactivateDetailsMemo;
} else {
	$ReactivateAfterXseconds=0;
	$ReactivateDetailsMemo="";
}

$mySQL="INSERT into ".$idGroup."_tasks (idCampaign, idAdmin, idGroup, dateTaskCreated, activationDateTime, numberOfMessagesToSend, repeatEveryXseconds, repeatDetailsMemo, taskRecurring, reactivateAfterXSeconds, reactivateDetailsMemo)
VALUES ($idCampaign, $sesIDAdmin, $idGroup, '".$dateTaskCreated."', '".$activationDateTime."', $NumberOfMessagesToSend, $RepeatEveryXseconds, '".$RepeatDetailsMemo."', $RecurringEvent, $ReactivateAfterXseconds,  '".$ReactivateDetailsMemo."')";
$obj->query($mySQL);
header("Location: _schedulerTasks.php?message=".urlencode(SCHEDULERTASKS_30));
?>