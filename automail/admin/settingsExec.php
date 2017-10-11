<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj = new db_class();

if (!empty($_POST['save'])) {
  	if (@$pdemomode) {
		forDemo("message.php", DEMOMODE_1);
	}

foreach ($_POST as $key => $value) {
 	if ($key!="save") {
		$mySQL="UPDATE ".$idGroup."_groupSettings SET $key='".dbQuotes($value)."' WHERE idGroup=".$idGroup;
		$obj->query($mySQL);
	}
  }
$strMessage = '<div align=center><img src="./images/doneOk.png">&nbsp;<span class=message>'.SETTINGSMODIFYEXEC_1.'</span></div>';
}
else {
	$strMessage = '<div align=center><img src="./images/cancel.gif">&nbsp;<span class=message>'.SETTINGSMODIFYEXEC_2.'</span></div>';
}

$groupName 	=	$obj->getSetting("groupName", $idGroup);
include('header.php');
echo $strMessage;
include('footer.php');
?>