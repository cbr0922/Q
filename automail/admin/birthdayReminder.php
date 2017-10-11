<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

/*
***** HOW TO USE THIS FILE *****
You must create a cron job that hits the file in this way:
GET http://www.domain.com/nuevoMailer/admin/birthdayReminder.php?admin=admin_123
if the alias command GET is not recognized by your server replace GET with curl -L -s OR WGET
Change www.domain.com/nuevoMailer with your own url.
Replace also admin_123 with your actual username and password always separated with _

The cron job should run at least once a day, preferably in the morning.
Attention: this file does not send out birthday newsletters.
It only notifies the administrators about how many subscribers have their birthday today.
*/

include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
include('../inc/sendMail.php');

$obj = new db_class();

@$admin=$_GET["admin"];
$admin=dbQuotes(dbProtect($admin, 250));
if (!$admin)	{
    $obj->closeDb();
 	die("Missing admin credentials.");
}
$posOf_             = stripos($admin, "_");
$adminLen           = strlen($admin);
$padminName 		= substr($admin,0,$posOf_);
$padminPassword 	= substr($admin,$posOf_+1);

$mySQL="SELECT idAdmin, adminName, idGroup, adminPassword FROM ".$idGroup."_admins WHERE adminName='".$padminName."' AND adminPassword='".$padminPassword."'";
$result = $obj->query($mySQL);
if ($obj->num_rows($result)!=1) {
    $obj->closeDb();
	die("Wrong admin credentials");
}
else {

		$groupGlobalCharset 	=   $obj->getSetting("groupGlobalCharset", $idGroup);
		$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
		$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
		$groupName 				=	$obj->getSetting("groupName", $idGroup);

		//birthdays
		$dayNow 	= date("j", strtotime("+$pTimeOffsetFromServer hours"));
		$monthNow 	= date("n", strtotime("+$pTimeOffsetFromServer hours"));
		$mySQLb 	= "SELECT count(*) as birthDays FROM ".$idGroup."_subscribers where idGroup=$idGroup AND subBirthDay='".$dayNow."' AND subBirthMonth='".$monthNow."'";
		$birthdays 	= $obj->get_rows($mySQLb);

		$myDay 				= addOffset(myDatenow(), $pTimeOffsetFromServer, $groupDateTimeFormat);
		$pSubject	   		= HOME_43;
		$pbody				= LISTNEWSLETTERSUBSCRIBERS_2.': '.$birthdays."\r\n".$myDay;
		//THE ALERT GOES TO ADMINS WHO WANT TO RECEIVE ALERTS
		$mySQLal="SELECT adminFullName, adminEmail FROM ".$idGroup."_admins WHERE emailAlert=-1 AND active=-1";
		$result	= $obj->query($mySQLal);
		while ($row=$obj->fetch_array($result)) {
		    $adminEmail=$row['adminEmail'];
		    $adminName=$row['adminFullName'];
			sendMail($idGroup, $adminEmail, $adminName, $pSubject, $htmlEmailBody="", $pbody, $pattachments="", $groupGlobalCharset, "t");
		}
}
//echo $birthdays;
?>