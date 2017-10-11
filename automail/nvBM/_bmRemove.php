<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);

showMessageBox();

$pcounter   = 0;
@$remove	= $_POST["remove"];
@$unconfirm	= $_POST["unconfirm"];
@$inactive	= $_POST["inactive"];
@$reset		= $_GET["reset"];
@$howmany	= $_POST["howmany"];
$strMessage="";
if ($reset==1) {
	$mySQL3="Update ".$idGroup."_subscribers set soft_bounces=0, hard_bounces=0";
	$obj->query($mySQL3);
	header("Location: _bm.php?message=".urlencode(BOUNCEMANAGER_24));
    return false;
}

if (!empty($remove)) {
	if (ceil($howmany)>=0 && is_numeric($howmany)) {
		$bounceType = $_POST["bounceType"];
		if ($bounceType==1) {
			$str1 = "soft_bounces";
		}
	    else if ($bounceType==2) {
			$str1 = "hard_bounces";
		}
		$mySQL1="SELECT idEmail from ".$idGroup."_subscribers where $str1 > $howmany";
	    $mySQL="DELETE FROM ".$idGroup."_listRecipients where idEmail IN ($mySQL1)";
	    $obj->query($mySQL);
		$mySQL2="Delete from ".$idGroup."_subscribers where $str1 > $howmany";
		$result=$obj->query($mySQL2);
	    $pcounter 	= $obj->affected_rows();
	   $strMessage = "<br>" . BOUNCEMANAGER_21 .$pcounter .BOUNCEMANAGER_22 .$str1. BOUNCEMANAGER_23. $howmany.'.<br />';
	}
	else {header("Location: _bm.php?message=".urlencode(BOUNCEMANAGER_20));return false;}
}
if (!empty($unconfirm)) {
	if (ceil($howmany)>=0 && is_numeric($howmany)) {
		$bounceType = $_POST["bounceType"];
		if ($bounceType==1) {
			$str1 = "soft_bounces";
		}
	    else if ($bounceType==2) {
			$str1 = "hard_bounces";
		}
		$mySQL1="UPDATE ".$idGroup."_subscribers SET confirmed=0 where $str1>$howmany";
		$obj->query($mySQL1);
	    $pcounter 	= $obj->affected_rows();
	   	$strMessage = "<br>".$pcounter.'&nbsp;'.BOUNCEMANAGER_3.'&nbsp;'.$str1.'&nbsp;'.BOUNCEMANAGER_16.$howmany.'&nbsp;'.BOUNCEMANAGER_4.'<br>';
	}
	else {header("Location: _bm.php?message=".urlencode(BOUNCEMANAGER_20));return false;}
}
if (!empty($inactive)) {
	if (ceil($howmany)>=0 && is_numeric($howmany)) {
		$bounceType = $_POST["bounceType"];
		if ($bounceType==1) {
			$str1 = "soft_bounces";
		}
	    else if ($bounceType==2) {
			$str1 = "hard_bounces";
		}
		$mySQL1="UPDATE ".$idGroup."_subscribers SET emailIsBanned=-1 where $str1>$howmany";
		$obj->query($mySQL1);
	    $pcounter 	= $obj->affected_rows();
	   	$strMessage = "<br>".$pcounter.'&nbsp;'.BOUNCEMANAGER_3.'&nbsp;'.$str1.'&nbsp;'.BOUNCEMANAGER_16.$howmany.'&nbsp;'.BOUNCEMANAGER_6.'<br>';
	}
	else {header("Location: _bm.php?message=".urlencode(BOUNCEMANAGER_20));return false;}
}
include('header.php');
echo $strMessage;
echo BOUNCEMANAGER_25?><a href="_bm.php"><?php echo BOUNCEMANAGER_11;?></a>

<?php
$obj->closeDb();
include('footer.php');
?>