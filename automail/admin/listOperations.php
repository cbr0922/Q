<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj = new db_class();
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/plain; charset='.$groupGlobalCharset.'');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
@$paction = strtolower($_GET['action']);
IF ($paction=="add") {
	@$pidlist	= $_GET['idListOption1'];
	//check first
	$total1 = $obj->tableCount_condition($idGroup."_listRecipients", " where idGroup=$idGroup AND idList=$pidlist");
	$total2 = $obj->tableCount_condition($idGroup."_subscribers", " where idGroup=$idGroup");
	if (ceil($total1)==ceil($total2)) {	//they are already all in this list.
		echo $total1.'#';			//we return the total number and stop here
	}
	else {	//this query also good for sql server. we do not use the list clean up
		$mySQL="INSERT into ".$idGroup."_listRecipients (idEmail, idGroup, idList) select idEmail, $idGroup, $pidlist FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND idEmail NOT IN (SELECT idEmail from ".$idGroup."_listRecipients where idList=$pidlist AND idGroup=$idGroup)";
		$obj->query($mySQL);
	    $total = $obj->tableCount_condition($idGroup."_listRecipients", " where idGroup=$idGroup AND idList=$pidlist");
	    echo $total.'#';
	}
}
//REMOVE ACTION
If ($paction=="remove") {
	@$pidlist	= $_GET['idListOption2'];
	$mySQL="Delete from ".$idGroup."_listRecipients WHERE idList=$pidlist AND idGroup=$idGroup";
	$obj->query($mySQL);
    $total = $obj->tableCount_condition($idGroup."_listRecipients", " where idGroup=$idGroup AND idList=$pidlist");
	echo $total.'#';

}
//COPY ACTION
If ($paction=="copy") {
	@$pidlist1	= $_GET['idListOption3'];
	@$pidlist2	= $_GET['idListOption4'];
	//check if the source list is empty...
	$mySQL="SELECT count(idEmail) as totalToCopyFrom FROM ".$idGroup."_listRecipients WHERE idList=$pidlist1 AND idGroup=$idGroup";
	$obj->query($mySQL);
	$totalToCopyFrom = $obj->get_rows($mySQL);

	$totalToCopyTo = $obj->tableCount_condition($idGroup."_listRecipients", " where idGroup=$idGroup AND idList=$pidlist2");
	$totalSubscribers = $obj->tableCount_condition($idGroup."_subscribers", " where idGroup=$idGroup");
	if (ceil($totalToCopyFrom)==ceil($totalSubscribers) AND ceil($totalToCopyTo)==ceil($totalSubscribers)) {
		//all subs belong both to source & target ==> stop
		echo $totalToCopyTo.'#';
		DIE;
	}
	if (ceil($totalToCopyFrom)==0) {	//source list is empty
		echo $totalToCopyTo.'#'.LISTS_39;
	}
	else {
		$mySQL2="INSERT into ".$idGroup."_listRecipients (idEmail, idGroup, idList) select idEmail, $idGroup, $pidlist2 from  ".$idGroup."_listRecipients where idGroup=$idGroup AND idList=$pidlist1 AND idEmail not in  (select idEmail from ".$idGroup."_listRecipients where idlist=$pidlist2 AND idGroup=$idGroup)";
		$obj->query($mySQL2);
    	$total4 = $obj->tableCount_condition($idGroup."_listRecipients", " where idGroup=$idGroup AND idList=$pidlist2");
		echo $total4.'#';
	}

}
?>