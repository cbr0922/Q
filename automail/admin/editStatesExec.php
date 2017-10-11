<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('./includes/languages.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
$obj 		= new db_class();
if (@$pdemomode) {
	forDemo("editStatesForm.php", DEMOMODE_1);
}
@$pstatecode       = dbQuotes($_REQUEST['stateCode']);
@$poldstatecode    = dbQuotes($_REQUEST['oldstatecode']);
@$pstatename       = dbQuotes($_REQUEST['stateName']);
@$paction	   		= $_REQUEST['action'];

if ($paction=="update") {
	$mySQL="UPDATE ".$idGroup."_states SET stateCode='$pstatecode', stateName='$pstatename' WHERE idGroup=$idGroup AND stateCode='$poldstatecode'";
    $message=EDITSTATESEXEC_1;
}

if ($paction=="delete") {
	$mySQL="DELETE from ".$idGroup."_states WHERE idGroup=$idGroup AND stateCode='$poldstatecode'";
    $message=EDITSTATESEXEC_2;
}

if ($paction=="add") {
	$mySQL="INSERT into ".$idGroup."_states (stateCode, stateName, idGroup) VALUES ('$pstatecode', '$pstatename', $idGroup)";
    $message=EDITSTATESEXEC_3;
}

if ($paction=="deleteAll") {
	$mySQL="DELETE from ".$idGroup."_states WHERE idGroup=$idGroup";
    $message=EDITSTATESEXEC_4;
}

$obj->query($mySQL);
$obj->closeDb();
header("Location: editStatesForm.php?message=".urlencode($message)."");
?>