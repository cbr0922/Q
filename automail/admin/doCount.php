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
$obj = new db_class();
include('mailCount.php');

//$sesIDAdmin
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$pBday 	    = date("j", strtotime("+$pTimeOffsetFromServer hours"));
$pBmonth 	= date("n", strtotime("+$pTimeOffsetFromServer hours"));


$pidCampaign = $_REQUEST['idCampaign'];
$mySQL="SELECT  idList, joins, mLists, idStopEmail, confirmed, prefers, idSendFilter, mailCounter FROM ".$idGroup."_campaigns WHERE idCampaign=$pidCampaign";
$result			= $obj->query($mySQL);
$row 			= $obj->fetch_array($result);

$pidlist			=	$row['idList'];
$joins  			=	$row['joins'];
$mLists 			=	$row['mLists'];
$pidemailtostart	=	$row['idStopEmail'];
$pconfirmed		    =	$row['confirmed'];
$pprefers		    =	$row['prefers'];
$pidSendFilter	    =	$row['idSendFilter'];
$pmailCounter	    =	ceil($row['mailCounter']);   //not needed
                //mailCount($pprefers, $pconfirmed, $sqlLists, $pidSendFilter, $pidEmailToStart, $check, $idGroup, $joins)
$xRecords = 	mailCount($pprefers, $pconfirmed, $mLists, $pidSendFilter, $pidemailtostart, 1, $idGroup, $joins);	    //1 is for full count
echo $xRecords.'#'.$pmailCounter.'#'.$pidemailtostart;
?>
