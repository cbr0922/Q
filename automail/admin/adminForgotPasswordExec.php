<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/plain");
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('../inc/sendMail.php');

$obj = new db_class();

if (@$pdemomode) {
	forDemo2 ("3");
}

$pemail		= $_REQUEST['adminEmail'];
$pemail		= dbQuotes(dbProtect("$pemail", "200"));

$mySQL="SELECT adminName, adminPassword, adminFullName FROM ".$idGroup."_admins WHERE adminEmail='$pemail'";
$result = $obj->query($mySQL);
if ($obj->num_rows($result)!=1) {
	echo '2'; die;
}
else {
    $row 	        = $obj->fetch_row($result);
	$padminName 	= $row[0];
	$ppassword 	    = $row[1];
    $adminFullName  = $row[2];
    $pEmailSubject  = ADMIN_INDEX_14;
    $pEmailBody     = ADMIN_INDEX_12. $padminName."/".$ppassword;
    sendMail($idGroup, $pemail, $adminFullName,  $pEmailSubject, $pEmailBody, $pEmailBodyT="", $attachments="", $charset="", "h");
    echo '3';
    die;
}
$obj->closeDb();
?>