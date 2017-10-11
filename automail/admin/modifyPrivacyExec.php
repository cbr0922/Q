<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 					= 	new db_class();

if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}
$pDetails	=	$_POST['pdetails'];
$pDetails	=   dbQuotes($pDetails);
$mySQL="UPDATE ".$idGroup."_privacyPage SET details='$pDetails' where idGroup=$idGroup";
$obj->query($mySQL);
header("Location: modifyPrivacyForm.php?message=".urlencode(MODIFYPRIVACYFORM_4)."");
?>


