<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
header("Cache-Control: no-store, no-cache");
header("Pragma: no-cache");
header ("Content-type: text/plain");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
$obj = new db_class();


$padminName 		= $_GET['adminName'];
$padminPassword 	= $_GET['adminPassword'];
$padminName 		= dbProtect("$padminName", "25");
$padminPassword 	= dbProtect("$padminPassword", "25");
$padminName			= dbQuotes("$padminName");
$padminPassword		= dbQuotes("$padminPassword");
$prememberme		= $_GET['rememberme'];
$prememberme		= dbQuotes(dbProtect("$prememberme", "3"));

$mySQL="SELECT idAdmin, adminName, idGroup, adminPassword FROM ".$idGroup."_admins WHERE active=-1 AND adminName='".$padminName."' AND adminPassword='".$padminPassword."'";
$result = $obj->query($mySQL);
if ($obj->num_rows($result)!=1) {
	echo '0';
	return false;
	die;
}
else {
$row 	= $obj->fetch_row($result);
//session_set_cookie_params(3600);	// 1 day
session_start();
if (!isset($_SESSION['idAdmin']))
{
    $_SESSION['idAdmin'] = $row[0];
}
if (!isset($_SESSION['adminName']))
{
    $_SESSION['adminName'] = $row[1];
}
if (!isset($_SESSION['idGroup']))
{
    $_SESSION['idGroupL'] = $row[2];
}

$today = myDatenow();
$mySQL2="UPDATE ".$idGroup."_admins SET adminLastLogin='".$today."' WHERE idAdmin=".$_SESSION['idAdmin'];
$result = $obj->query($mySQL2);
session_regenerate_id();
echo '1';
}
?>