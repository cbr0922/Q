<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/html");

include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');

$obj = new db_class();

@$idNewsletter	= $_REQUEST['idNewsletter'];
@$p				= $_REQUEST['p'];
@$idlist 		= $_REQUEST['idList'];

if (!empty($idNewsletter)) {
	$mysQL1="UPDATE ".$idGroup."_newsletters set isPublic=".$p." WHERE idNewsletter=".$idNewsletter;
	$obj->query($mysQL1);
	if ($p=="0") {
		ECHO ("<a href=# onclick=\"switchPublic($idNewsletter,-1);return false;\"><img src=\"./images/notpublic.png\" width=\"15\" height=\"15\" border=\"0\" ></a>");
	}
	else if ($p=="-1") {
		ECHO ("<a href=# onclick=\"switchPublic($idNewsletter,0);return false;\"><img src=\"./images/public.png\" width=\"15\" height=\"15\" border=\"0\" ></a>");
	}
}

if (!empty($idlist)) {
	$mysQL1="UPDATE ".$idGroup."_lists set isPublic=".$p." WHERE idList=".$idlist;
	$obj->query($mysQL1);
	if ($p==0) { 
		ECHO ("<a href=# onclick=\"switchPublic($idlist,-1);return false;\"><img src=\"./images/notpublic.png\" width=\"15\" height=\"15\" border=\"0\" ></a>");
	} else if ($p==-1) {
		echo ("<a href=# onclick=\"switchPublic($idlist,0);return false;\"><img src=\"./images/public.png\" width=\"15\" height=\"15\" border=\"0\" ></a>");
	} 	
}

?>







