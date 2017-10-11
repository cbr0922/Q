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
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$pTimeOffsetFromServerN 	= -$pTimeOffsetFromServer;

$idCampaign 			= $_POST["idCampaign"];
$idTask     			= $_POST["idTask"];
$dateTaskCreated 	    = myDatenow();
$activationDateTime     = $_POST["activationDate"];
$activationHour			= $_POST["activationTimeH"];
$activationMinute		= $_POST["activationTimeM"];
$strSQL1="";
$strSQL2="";

$activationDateTime	= date("Y-m-d" , strtotime($activationDateTime)).' '.$activationHour.':'.$activationMinute.':00';
$activationDateTime	= addOffset($activationDateTime, -$pTimeOffsetFromServer, "Y-m-d H:i:s");
$NumberOfMessagesToSend = $_POST["numberOfMessagesToSend"];
$RepeatEveryPeriodValue = $_POST["RepeatEveryPeriodValue"];
$repeatEveryPeriod      = $_POST["RepeatEveryPeriod"];
if (is_numeric($NumberOfMessagesToSend) && is_numeric($RepeatEveryPeriodValue) && $repeatEveryPeriod!="0") {
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
	$RepeatEveryXseconds    = $multiplier*abs($RepeatEveryPeriodValue);
	$RepeatDetailsMemo		= abs($RepeatEveryPeriodValue).' / ' .$memoDetails;

    if ($NumberOfMessagesToSend==0) {
        $RepeatEveryXseconds=0;
        $RepeatDetailsMemo="";
    }
}
else {
	$NumberOfMessagesToSend=0;
	$RepeatEveryXseconds=0;
	$RepeatDetailsMemo="";
}
$strSQL1 = ", numberOfMessagesToSend=".abs($NumberOfMessagesToSend).", repeatEveryXseconds= ".$RepeatEveryXseconds.", repeatDetailsMemo='".$RepeatDetailsMemo."'";


@$RecurringEvent		= $_POST["recurringEvent"];
if ($RecurringEvent!="-1") {$RecurringEvent="0";}
@$ReactivatePeriodValue	= $_POST["reactivatePeriodValue"];
@$ReactivatePeriod	    = $_POST["reactivatePeriod"];

if ($RecurringEvent=="-1" && is_numeric($ReactivatePeriodValue) && $ReactivatePeriod!="0") {
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
	$ReactivateAfterXseconds	= $multiplier*abs($ReactivatePeriodValue);
	$ReactivateDetailsMemo		= abs($ReactivatePeriodValue).' / ' .$ReactivateDetailsMemo;
}
else {
    $RecurringEvent = 0;
    $ReactivateAfterXseconds=0;
    $ReactivateDetailsMemo="";
}
$strSQL2 = ", taskRecurring=".$RecurringEvent.", reactivateAfterXSeconds=".abs($ReactivateAfterXseconds).", reactivateDetailsMemo='".$ReactivateDetailsMemo."' ";

$mySQL="UPDATE ".$idGroup."_tasks SET activationDateTime='".$activationDateTime."' ".$strSQL1 .$strSQL2." WHERE idGroup=$idGroup AND idTask=$idTask";
$obj->query($mySQL);
header("Location: _schedulerTasks.php?message=".urlencode(SCHEDULERTASKS_45));
?>