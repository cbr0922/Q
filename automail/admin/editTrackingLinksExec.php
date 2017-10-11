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
	forDemo("editTrackingLinksForm.php", DEMOMODE_1);
}

@$pidlink		= $_REQUEST['idLink'];
@$plinkurl	    = $_REQUEST['linkUrl'];

if (!empty($_REQUEST['update'])) {
    $mySQL="UPDATE ".$idGroup."_links SET linkUrl='".dbQuotes($plinkurl)."' WHERE idLink=$pidlink";
    $obj->query($mySQL);
    $obj->closeDb();
    header("Location: editTrackingLinksForm.php?message=".urlencode(EDITTRACKINGLINKSEXEC_1)."");
}

if (@$_REQUEST['action']=="delete") {
    $mySQL="DELETE from ".$idGroup."_links WHERE idLink=$pidlink";
    $obj->query($mySQL);
    $obj->closeDb();
    header("Location: editTrackingLinksForm.php?message=".urlencode(EDITTRACKINGLINKSEXEC_2)."");
}

if (!empty($_REQUEST['add'])) {
    $mySQL="INSERT into ".$idGroup."_links (linkUrl, idGroup) VALUES ('".dbQuotes($plinkurl)."', $idGroup)";
    $obj->query($mySQL);
    $obj->closeDb();
    header("Location: editTrackingLinksForm.php?message=".urlencode(EDITTRACKINGLINKSEXEC_3)."");
}

?>