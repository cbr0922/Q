<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('stringFormat.php');
$groupSite  =	$obj->getSetting("groupSite", $idGroup);
$pidemail		= dbProtect($_GET['sid'], 15);
$idCampaign		= dbProtect($_GET['c'],15);
$pIP = $_SERVER['REMOTE_ADDR'];
$pDate 	= myDatenow();
$pemailClient=$_SERVER['HTTP_USER_AGENT'];
//update views
$mySQL="INSERT into ".$idGroup."_viewStats (idEmail, idCampaign, ipOpened, dateOpened, idGroup, emailClient) VALUES ($pidemail, $idCampaign, '$pIP', '$pDate', $idGroup, '$pemailClient')";
$obj->query($mySQL);
//stream file
$filename = "cleardot.gif";
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: html/multipart");
header("Content-Type: image/gif");
header("Content-length: 0");
@readfile($filename);
$obj->closeDb();
?>