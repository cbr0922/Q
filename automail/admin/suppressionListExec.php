<?php
set_time_limit(0);
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj 		= new db_class();
$groupName	= $obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/html; charset='.$groupGlobalCharset.'');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
if (@$pdemomode) {
	forDemo2(DEMOMODE_1);
}
@$action =  $_GET['action'];
$psubscribers 	= $_GET['subsToBan'];
$psubscribers	= explode("\n", $psubscribers);
$subs 			= sizeof($psubscribers);
$myDay = myDatenow();
$existing=0;
$newAdded=0;

if ($action=='suppress') {
for ($i=0; $i<$subs; $i++)  {
	if (strlen(trim($psubscribers[$i]))>5) {
	$mySQL1="SELECT idEmail FROM ".$idGroup."_subscribers where idGroup=$idGroup AND email='".trim(dbQuotes($psubscribers[$i]))."'";
	$result	= $obj->query($mySQL1);
	$row = $obj->fetch_array($result);
	if (!$row) {	//HE DOES NOT EXIST SO WE INSERT HIM
		$newAdded=$newAdded+1;
		$iSQL="INSERT INTO ".$idGroup."_subscribers (idGroup, email, emailisBanned, dateSubscribed, confirmed, prefersHtml) VALUES ($idGroup, '".trim(dbQuotes($psubscribers[$i]))."', -1, '$myDay', 0, -1)";
		$obj->query($iSQL);
	}
	else {
		$existing=$existing+1;
		$nidemail	= $row['0'];
		$mySQL3="UPDATE ".$idGroup."_subscribers SET emailisBanned=-1 where idEmail=$nidemail";
		$obj->query($mySQL3);
	}
	}
}
$bannedSubscribers 	= $obj->tableCount_condition($idGroup."_subscribers", " where emailIsBanned=-1 AND idGroup=".$idGroup."");
echo '<span class="menuSmall">';
if ($existing>0) {echo $existing.'&nbsp;'.SUPLIST_4.'<br>';}
if ($newAdded>0) {echo $newAdded.'&nbsp;'.SUPLIST_5.'<br>';}
echo SUPLIST_2.'&nbsp;'.$bannedSubscribers.'&nbsp;'.SUPLIST_3;
echo '</span>';
} //for suppress action

if ($action=='activateAll') {
   	$mySQL3="UPDATE ".$idGroup."_subscribers SET emailisBanned=0 where emailisBanned=-1";
	$obj->query($mySQL3);
	$affected = $obj->affected_rows();
	$bannedSubscribers 	= $obj->tableCount_condition($idGroup."_subscribers", " where emailIsBanned=-1 AND idGroup=".$idGroup."");
	echo '<span class="menuSmall">'.$affected.'&nbsp;'.SUPLIST_14.'</span><br>';
	echo '<span class="menuSmall">'.SUPLIST_2.'&nbsp;'.$bannedSubscribers.'&nbsp;'.SUPLIST_3.'</span>';
	return false;
}


if ($action=='deleteAll') {
	$affected="";
	$mySQL1="SELECT idEmail from ".$idGroup."_subscribers where emailIsBanned=-1";
    $mySQL="DELETE FROM ".$idGroup."_listRecipients where idEmail IN ($mySQL1)";
    $obj->query($mySQL);
	$mySQL2="Delete from ".$idGroup."_subscribers where emailIsBanned=-1";
	$result=$obj->query($mySQL2);
    $affected = $obj->affected_rows();
	$bannedSubscribers 	= $obj->tableCount_condition($idGroup."_subscribers", " where emailIsBanned=-1 AND idGroup=".$idGroup."");
	echo '<span class="menuSmall">'.SUPLIST_15.'</span><br>';
	echo '<span class="menuSmall">'.SUPLIST_2.'&nbsp;'.$bannedSubscribers.'&nbsp;'.SUPLIST_3.'</span>';
	return false;
}

if ($action=='removeAll') {
	$affected="";
	$mySQL1="SELECT idEmail from ".$idGroup."_subscribers where emailIsBanned=-1";
    $mySQL="DELETE FROM ".$idGroup."_listRecipients where idEmail IN ($mySQL1)";
    $obj->query($mySQL);
	$bannedSubscribers 	= $obj->tableCount_condition($idGroup."_subscribers", " where emailIsBanned=-1 AND idGroup=".$idGroup."");
	echo '<span class="menuSmall">'.SUPLIST_13.'</span><br>';
	echo '<span class="menuSmall">'.SUPLIST_2.'&nbsp;'.$bannedSubscribers.'&nbsp;'.SUPLIST_3.'</span>';
	return false;
}

$obj->closeDb();
?>