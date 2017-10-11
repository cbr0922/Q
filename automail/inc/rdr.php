<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('dbFunctions.php');
include('../inc/createGoogleString.php');
$obj 		= new db_class();
include('stringFormat.php');
$groupSite  =	$obj->getSetting("groupSite", $idGroup);

$pAction = dbProtect($_GET['r'], 500);
//$_GET;   //gives an array
$pArrayAction	= explode("c", $pAction);
$idemail	= $pArrayAction[0];
$idCampaign	= $pArrayAction[1];
$idLink		= $pArrayAction[2];	    //the actual link
$pIP        = $_SERVER['REMOTE_ADDR'];
$pDate 	    = myDatenow();

$mySQL="SELECT linkUrl FROM ".$idGroup."_links WHERE idLink=$idLink";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows) {
    header("Location:$groupSite");
    exit;
}
$row = $obj->fetch_array($result);
$linkUrl = $row['linkUrl'];

if ($idLink) {
    $mySQL="Insert into ".$idGroup."_clickStats (idEmail, idCampaign, idLink, linkUrl, ipClicked, dateClicked, idGroup) VALUES ($idemail, $idCampaign, $idLink, '".dbQuotes($linkUrl)."', '$pIP', '$pDate', $idGroup)";
    $obj->query($mySQL);


// START REPLACING URL SMART TAGS
/*
if ($idemail) {
    $mySQLs="SELECT idEmail, email, name, lastName, customSubField1, customSubField2, customSubField3 FROM ".$idGroup."_subscribers WHERE idEmail=$idemail";
    $subsResult	        = $obj->query($mySQLs);
    $rowSub             = $obj->fetch_array($subsResult);
    //replace
    $linkUrl	= str_ireplace("subemail",    $rowSub['email'], $linkUrl);
	$linkUrl	= str_ireplace("subID",    $rowSub['idEmail'], $linkUrl);
    $linkUrl	= str_ireplace("subname",    $rowSub["name"], $linkUrl);
    $linkUrl	= str_ireplace("sublastname",    $rowSub["lastName"], $linkUrl);
    $linkUrl	= str_ireplace("subcustomsubfield1",    $rowSub["customSubField1"], $linkUrl);
    $linkUrl	= str_ireplace("subcustomsubfield2",    $rowSub["customSubField2"], $linkUrl);
    $linkUrl	= str_ireplace("subcustomsubfield3",    $rowSub["customSubField3"], $linkUrl);
}
*/
// END REPLACING URL SMART TAGS



    $obj->closeDb();

	$mailData["idGroup"] 	= $idGroup;
	$mailData["idCampaign"] = $idCampaign;
	$gtracking=createGoogleString($mailData);	//build the GA string
    if (!empty($gtracking) && stripos($linkUrl, "?")!==false && stripos($linkUrl, "mailto:")===false) {
          $linkUrl =  $linkUrl."&".$gtracking;
    }
    else if (!empty($gtracking) && stripos($linkUrl, "?")===false && stripos($linkUrl, "mailto:")===false) {
          $linkUrl =  $linkUrl."?".$gtracking;
    }
    else { $linkUrl =  $linkUrl;}
    $linkUrl = htmlspecialchars_decode($linkUrl);
    header("Location: $linkUrl");
    exit;
}
else {header("Location:$groupSite");}
?>