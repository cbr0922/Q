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
	forDemo("editCountriesForm.php", DEMOMODE_1);
}
@$pcountrycode       = dbQuotes($_REQUEST['countryCode']);
@$poldcountrycode    = dbQuotes($_REQUEST['oldcountrycode']);
@$pcountryname       = dbQuotes($_REQUEST['countryName']);
@$paction	   		= $_REQUEST['action'];

if ($paction=="update") {
	$mySQL="UPDATE ".$idGroup."_countries SET countryCode='$pcountrycode', countryName='$pcountryname' WHERE idGroup=$idGroup AND countryCode='$poldcountrycode'";
    $message=EDITCOUNTRIESEXEC_1;
}

if ($paction=="delete") {
	$mySQL="DELETE from ".$idGroup."_countries WHERE idGroup=$idGroup AND countryCode='$poldcountrycode'";
    $message=EDITCOUNTRIESEXEC_2;
}

if ($paction=="add") {
	$mySQL="INSERT into ".$idGroup."_countries (countryCode, countryName, idGroup) VALUES ('$pcountrycode', '$pcountryname', $idGroup)";
    $message=EDITCOUNTRIESEXEC_3;
}

if ($paction=="deleteAll") {
	$mySQL="DELETE from ".$idGroup."_countries WHERE idGroup=$idGroup";
    $message=EDITCOUNTRIESEXEC_4;
}

$obj->query($mySQL);
$obj->closeDb();
header("Location: editCountriesForm.php?message=".urlencode($message)."");
?>