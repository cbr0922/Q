<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('./includes/languages.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');

if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}
$obj = new db_class();
$today = myDatenow();
@$idlist 			= $_POST['idList'];
$plistname 			= dbQuotes($_POST['listName']);
$plistdescription 	= dbQuotes($_POST['listDescription']);
@$plistpublic 		= $_POST['isPublic'];
if ($plistpublic!=-1) {
  $plistpublic=0;
}
if (!$plistname) {
	header("Location: lists.php?message=".urlencode(LISTS_29)."");
}
else
{
	if (!$idlist) {
		$mySQL="INSERT INTO ".$idGroup."_lists (listName, listDescription, isPublic, dateCreated, createdBy, idGroup) VALUES ('$plistname', '$plistdescription', $plistpublic, '$today', $sesIDAdmin, $idGroup)";
		$result = $obj->query($mySQL);
		header("Location: lists.php?message=".urlencode(LISTS_30)."");
	}
	else {

		$mySQL="Update ".$idGroup."_lists set listName='$plistname', listDescription='$plistdescription', isPublic=$plistpublic WHERE idList=$idlist";
		$result = $obj->query($mySQL);
		header("Location: lists.php?message=".urlencode(LISTS_34)."");
	}

}

?>
