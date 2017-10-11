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

//make ALL subscribers confirmed
$mySQL="Update ".$idGroup."_subscribers set confirmed=-1";
$obj->query($mySQL);
$obj->closeDb();
header("Location: message.php?message=".urlencode(MAKEALLCONFIRMED_1)."");
?>
