<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include ('adminVerify.php');
include ('../inc/dbFunctions.php');
include ('../inc/stringFormat.php');
include ('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();

$pmake  = $_GET['h'];

if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}

if ($pmake==-1) {
	//make ALL subscribers html-registered
	$mySQL="Update ".$idGroup."_subscribers set prefersHtml=-1";
	header("Location: message.php?message=".urlencode(MAKEALLHTMLTEXT_1)."");
}

if ($pmake==0) {
	//make ALL subscribers text-registered
	$mySQL="Update ".$idGroup."_subscribers set prefersHtml=0";
	header("Location: message.php?message=".urlencode(MAKEALLHTMLTEXT_2)."");
}
?>