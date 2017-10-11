<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
set_time_limit(0);
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');

$admin=$_GET["admin"];
$admin=dbQuotes(dbProtect($admin, 250));
if (!$admin)	{
    //schedulerLog(myDatenow()."-->Missing admin credentials. Cannot login-->STOP", $idGroup, "XX", $sBase3);
    $obj->closeDb();
    echo "Bounce manager message: No admin credentials";
 	die;
}
$posOf_             = stripos($admin, "_");
$adminLen           = strlen($admin);
$padminName 		= substr($admin,0,$posOf_);
$padminPassword 	= substr($admin,$posOf_+1);

$mySQL="SELECT idAdmin, adminName, idGroup, adminPassword FROM ".$idGroup."_admins WHERE adminName='".$padminName."' AND adminPassword='".$padminPassword."'";
$result = $obj->query($mySQL);
if ($obj->num_rows($result)!=1) {
    //schedulerLog(myDatenow()."-->Wrong admin credentials.-->STOP", $idGroup, "XX", $sBase3);
	echo "Bounce manager message: Wrong admin credentials";
    die;
}
else {
    $row 	= $obj->fetch_row($result);
    $idGroupL = $row[2];
}

include('_bmProcess.php');

echo BOUNCEMANAGER_26."\r\n";
echo BOUNCEMANAGER_27.' '.$totals."\r\n";
echo BOUNCEMANAGER_29.' '.$softs."\r\n";
echo BOUNCEMANAGER_30.' '.$hards."\r\n";
echo BOUNCEMANAGER_33.' '.$autoResponders."\r\n";
echo BOUNCEMANAGER_31.' '.$noWeightAssigned.' '.BOUNCEMANAGER_32."\r\n";
?>