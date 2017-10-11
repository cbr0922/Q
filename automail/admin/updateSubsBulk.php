<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('./includes/languages.php');
include('../inc/stringFormat.php');
$obj 			= new db_class();

@$predirecturl	= $_POST['redirectUrl'];
@$paction 		= $_REQUEST['action'];
@$pidlist		= $_POST['idList'];
if (isset($_POST['idEmail'])) {
	@$emails		= count($_POST['idEmail']);
}
if (@$pdemomode) {
	forDemo2 ('message.php?message='.urlencode(DEMOMODE_1));
}
if ($pidemail=0){
		echo "home.php?message=No emails found";
}
if ($paction=="delete") {
	For ($i=0; $i<$emails;$i++){
		@$mySQL="delete from ".$idGroup."_listRecipients where idEmail=".$_POST['idEmail'][$i];
		$obj->query($mySQL);
		@$mySQL1="delete from ".$idGroup."_subscribers where idEmail=".$_POST['idEmail'][$i];
		$obj->query($mySQL1);
	}
	echo $predirecturl.'&message='.urlencode(DOSUBSCRIBERSLIST_20);
	die;
}

if ($paction=="confirm") {
	For ($i=0; $i<$emails;$i++){
		$mySQL="Update ".$idGroup."_subscribers set confirmed=-1 where idEmail=".$_POST['idEmail'][$i];
		$obj->query($mySQL);
	}
	echo $predirecturl.'&message='.urlencode(DOSUBSCRIBERSLIST_25);
	die;
}

if ($paction=="remove") {
	For ($i=0; $i<$emails;$i++){
		//remove subscriber from lists
		$mySQL="delete from ".$idGroup."_listRecipients where idList=$pidlist AND idEmail=".$_POST['idEmail'][$i];
		$obj->query($mySQL);
	}
	echo $predirecturl.'&message='.urlencode(DOSUBSCRIBERSLIST_9);
}
?>
