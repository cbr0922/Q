<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj = new db_class();
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/html; charset='.$groupGlobalCharset.'');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

@$url			= $_REQUEST['resource'];

echo (getBodyFromUrl($url, $groupGlobalCharset));

$obj->closeDb();
?>
