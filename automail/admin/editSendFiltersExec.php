<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/languages.php');

if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}

@$pidSendFilter		= $_REQUEST['idSendFilter'];
@$psendFilterCode	= dbQuotes($_REQUEST['sendFilterCode']);
@$psendFilterDesc	= dbQuotes($_REQUEST['sendFilterDesc']);

if (!empty($_REQUEST['add'])) {
	$sendFilterDesc = dbQuotes(EDITSENDFILTERSEXEC_7);
	$mySQL="INSERT into ".$idGroup."_sendFilters (sendFilterDesc, sendFilterCode, createdBy, idGroup) VALUES ('$sendFilterDesc', '$psendFilterCode', $sesIDAdmin, $idGroup)";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: filters.php?message=".urlencode(EDITSENDFILTERSEXEC_3)."");
}

if (!empty($_REQUEST['update'])) {
	$mySQL="UPDATE ".$idGroup."_sendFilters SET sendFilterCode='$psendFilterCode', sendFilterDesc='$psendFilterDesc' WHERE idSendFilter=$pidSendFilter";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: filters.php?message=".urlencode(EDITSENDFILTERSEXEC_1)."");
}

if (@$_REQUEST['action']=="delete") {
	header("Location: delete.php?idSendFilter=$pidSendFilter");
}

if (!empty($_REQUEST['addblank'])) {
	$sendFilterDesc = dbQuotes(EDITSENDFILTERSEXEC_7);
	$mySQL="INSERT into ".$idGroup."_sendFilters (sendFilterDesc, sendFilterCode, createdBy, idGroup) VALUES ('$sendFilterDesc', ' AND ".$idGroup."_subscribers.field', $sesIDAdmin, $idGroup)";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: filters.php?message=".urlencode(EDITSENDFILTERSEXEC_4)."");
}

if (!empty($_REQUEST['birthday'])) {
	$bstring = " AND subBirthDay=\'##pBday##\' AND subBirthMonth=\'##pBmonth##\'";
	$bDescription = dbQuotes(EDITSENDFILTERSEXEC_6);
	$mySQL="INSERT into ".$idGroup."_sendFilters (sendFilterDesc, sendFilterCode, createdBy, idGroup) VALUES ('$bDescription', '$bstring', $sesIDAdmin, $idGroup)";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: filters.php?message=".urlencode(EDITSENDFILTERSEXEC_5)."");
}


if (!empty($_REQUEST['xDaysAfterFilter'])) {
	$x = ceil($_REQUEST['daysafter']);
	$z = $x+1;
	$y = $x-1;
	$fSQL2=" AND DATEDIFF(CURDATE(), dateSubscribed) <$z AND DATEDIFF(CURDATE(), dateSubscribed) >$y";

	$fSQL = dbQuotes($fSQL2);
	$psendFilterDesc=$x.DATEFILTERS_18;
	$mySQL="INSERT into ".$idGroup."_sendFilters (sendFilterDesc, sendFilterCode, createdBy, idGroup) VALUES ('$psendFilterDesc', '$fSQL', $sesIDAdmin, $idGroup)";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: filters.php?message=".urlencode(EDITSENDFILTERSEXEC_3)."");
}
?>