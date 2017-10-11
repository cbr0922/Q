<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header ("Content-type: text/plain");

include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
$obj = new db_class();

if (@$pdemomode) {
	forDemo2("demo");
}

@$idAdminF			= dbQuotes($_POST['idAdminF']);
@$adminNameF		= dbQuotes($_POST['adminNameF']);
@$adminPasswordF	= dbQuotes($_POST['adminPasswordF']);
@$adminFullNameF	= dbQuotes($_POST['adminFullNameF']);
@$adminEmailF		= dbQuotes($_POST['adminEmailF']);
@$emailAlert	 	= dbQuotes($_POST['emailAlert']);
@$activeAdmin	 	= dbQuotes($_POST['activeAdmin']);

if ($emailAlert!=-1) {  $emailAlert=0;}
if ($activeAdmin!=-1) {  $activeAdmin=0;}

if ($idAdminF) {
	//check if email exists
	$mySQL = "SELECT count(*) from ".$idGroup."_admins where (adminEmail='".@$adminEmailF."' OR adminName='".@$adminNameF."') AND idAdmin<>".$idAdminF."";
	if (($obj->get_rows("$mySQL"))!=0) {
		echo '1';	//email exists
		die;
	}
	else {
		$mysql="UPDATE ".$idGroup."_admins SET adminFullName='".$adminFullNameF."', adminEmail='".$adminEmailF."', adminName='".$adminNameF."', adminPassword='".$adminPasswordF."', emailAlert=$emailAlert, active=$activeAdmin WHERE idAdmin=$idAdminF";
		$obj->query($mysql);
		echo 'updated';
	}
}
else {
// adding
//check if email exists
$mySQL="SELECT adminEmail from ".$idGroup."_admins where adminEmail='".$adminEmailF."' OR adminName='".@$adminNameF."'";
$result = $obj->query($mySQL);
if ($obj->num_rows($result)!=0) {
	echo '1';
	die;
}
else {
//insert admin
	$mySQL2="INSERT INTO ".$idGroup."_admins (adminName, adminPassword, adminFullName, adminEmail, emailAlert, active, idGroup) VALUES ('".$adminNameF."', '".$adminPasswordF."', '".$adminFullNameF."', '".$adminEmailF."', $emailAlert, $activeAdmin, $idGroup)";
	$result2 = $obj->query($mySQL2);
	$lastId =  $obj->insert_id();
	$mySQL3="INSERT INTO ".$idGroup."_adminStatistics (idAdmin) VALUES ($lastId)";
	$obj->query($mySQL3);
echo 'added';
}
}

?>