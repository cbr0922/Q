<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj = new db_class();

@$pidlist = $_REQUEST['idList'];
$plistname=getlistname($pidlist, $idGroup);

//using left-join
//$mySQL="SELECT ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers LEFT JOIN ".$idGroup."_listRecipients ON ".$idGroup."_subscribers.idEmail = ".$idGroup."_listRecipients.idEmail WHERE (((".$idGroup."_listRecipients.idEmail) Is Null))";
//using not exists
//$mySQL="SELECT distinct ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers WHERE NOT EXISTS (SELECT idEmail FROM ".$idGroup."_listRecipients WHERE ".$idGroup."_listRecipients.idEmail=".$idGroup."_subscribers.idEmail)";
// using NOT IN
$mySQL="SELECT DISTINCT ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail NOT IN (SELECT idEmail FROM ".$idGroup."_listRecipients)";
$result	= $obj->query($mySQL);
while ($row = $obj->fetch_array($result)){
    $mySql2="INSERT INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$row['idEmail'].", $pidlist, $idGroup)";
    $obj->query($mySql2);
}
$obj->closeDb();
header("Location: listNewsletterSubscribers.php?message=".urlencode(LISTNEWSLETTERSUBSCRIBERS_18.$plistname)."");
?>




