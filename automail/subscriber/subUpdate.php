<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../inc/languages.php');

$groupEncryptionPassword = $obj->getSetting("groupEncryptionPassword", $idGroup);
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);
$mailData["groupName"]   =	$obj->getSetting("groupName", $idGroup);
$myDay  = myDatenow();

(isset($_GET['email']))?$sub["email"] = dbQuotes(dbProtect($_GET['email'],100)):$sub["email"]="";
(isset($_GET['password']))?$sub["password"] = dbQuotes(dbProtect($_GET['password'],50)):$sub["password"]="";
(isset($_GET['idEmail']))?$sub["idEmail"] = dbQuotes(dbProtect($_GET['idEmail'],20)):$sub["idEmail"]="";
(isset($_GET['oldEmail']))?$sub["oldEmail"] = dbQuotes(dbProtect($_GET['oldEmail'],100)):$sub["oldEmail"]="";
(isset($_GET['oldPass']))?$sub["oldPass"] = dbQuotes(dbProtect($_GET['oldPass'],50)):$sub["oldPass"]="";
@$listsTicked   = count($_GET['idList']);
//new lines in v.200
if (!$sub["email"]) {
	header("Location:subAccount.php?email=".$sub["oldEmail"]."&idEmail=".$sub["idEmail"]."&password=".$sub["password"]."&message=".SUBACCOUNT_36);
	return false;
}
(isset($_GET['subname']))?$sub["subname"] = dbQuotes(dbProtect($_GET['subname'],100)):$sub["subname"]="";
(isset($_GET['sublastname']))?$sub["sublastname"] = dbQuotes(dbProtect($_GET['sublastname'],100)):$sub["sublastname"]="";
(isset($_GET['subCompany']))?$sub["subCompany"] = dbQuotes(dbProtect($_GET['subCompany'],100)):$sub["subCompany"]="";
(isset($_GET['address']))?$sub["address"] = dbQuotes(dbProtect($_GET['address'],150)):$sub["address"]="";
(isset($_GET['city']))?$sub["city"] = dbQuotes(dbProtect($_GET['city'],50)):$sub["city"]="";
(isset($_GET['zip']))?$sub["zip"] = dbQuotes(dbProtect($_GET['zip'],10)):$sub["zip"]="";
(isset($_GET['stateCode']))?$sub["stateCode"] = dbQuotes(dbProtect($_GET['stateCode'],50)):$sub["stateCode"]="";
(isset($_GET['countryCode']))?$sub["countryCode"] = dbQuotes(dbProtect($_GET['countryCode'],50)):$sub["countryCode"]="";
(isset($_GET['subPhone1']))?$sub["subPhone1"] = dbQuotes(dbProtect($_GET['subPhone1'],50)):$sub["subPhone1"]="";
(isset($_GET['subPhone2']))?$sub["subPhone2"] = dbQuotes(dbProtect($_GET['subPhone2'],50)):$sub["subPhone2"]="";
(isset($_GET['subMobile']))?$sub["subMobile"] = dbQuotes(dbProtect($_GET['subMobile'],50)):$sub["subMobile"]="";
(isset($_GET['subBirthDay']))?$sub["subBirthDay"] = dbQuotes(dbProtect($_GET['subBirthDay'],5)):$sub["subBirthDay"]="";
(isset($_GET['subBirthMonth']))?$sub["subBirthMonth"] = dbQuotes(dbProtect($_GET['subBirthMonth'],5)):$sub["subBirthMonth"]="";
(isset($_GET['subBirthYear']))?$sub["subBirthYear"] = dbQuotes(dbProtect($_GET['subBirthYear'],4)):$sub["subBirthYear"]="";
(isset($_GET['customSubField1']))?$sub["customSubField1"] = dbQuotes(dbProtect($_GET['customSubField1'],100)):$sub["customSubField1"]="";
(isset($_GET['customSubField2']))?$sub["customSubField2"] = dbQuotes(dbProtect($_GET['customSubField2'],100)):$sub["customSubField2"]="";
(isset($_GET['customSubField3']))?$sub["customSubField3"] = dbQuotes(dbProtect($_GET['customSubField3'],100)):$sub["customSubField3"]="";
(isset($_GET['customSubField4']))?$sub["customSubField4"] = dbQuotes(dbProtect($_GET['customSubField4'],100)):$sub["customSubField4"]="";
(isset($_GET['customSubField5']))?$sub["customSubField5"] = dbQuotes(dbProtect($_GET['customSubField5'],100)):$sub["customSubField5"]="";
// new lines end
if ($sub["oldPass"])  {
	$pSQL = " AND subPassword='".$sub["oldPass"]."'";
} else {
	$pSQL = "";
}

if ($sub["idEmail"])  {
	$eSQL = " AND idEmail='".$sub["idEmail"]."'";
} else {
	$eSQL = "";
}
if (!$sub["idEmail"] && !$sub["password"])  {
    $message=SUBACCOUNT_27;
    header("Location:subLogin.php?subemail=".$sub["email"]."&message=".$message."");
    die;
}

//DO AN ID CHECK
//Get name and id of subscriber based on the old email entered
$mySQL2="SELECT idEmail, email, name, lastname FROM ".$idGroup."_subscribers WHERE email='".$sub["oldEmail"]."'".$pSQL.$eSQL;
$result	= $obj->query($mySQL2);
$row = $obj->fetch_array($result);
if (!$row["idEmail"]) { //EXIT
    $message=SUBACCOUNT_13;
    header("Location:subLogin.php?subemail=".$sub["email"]."&message=".$message."");
    die;
}
else {
	//delete only from public lists because perhaps he may be  assigned to hidden lists.
	$mySQL1="DELETE from ".$idGroup."_listRecipients WHERE idEmail=".$sub["idEmail"]." AND idList NOT IN (SELECT idList FROM ".$idGroup."_lists WHERE isPublic=0 AND idGroup=$idGroup)";
	$obj->query($mySQL1);
    //Now start inserting
	For ($z=0; $z<$listsTicked; $z++)  {
        $mySQL3="insert into ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$sub["idEmail"].", ".$_GET['idList'][$z].", $idGroup)";
		$obj->query($mySQL3);
		// REMOVE HIM FROM THE OPT-OUT TABLE FOR THE LISTS HE SELECTED for both emails
		$mySQL8="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$sub["oldEmail"]."' AND optOutType='".$_GET['idList'][$z]."'";
		$obj->query($mySQL8);
		$mySQL8c="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$sub["email"]."' AND optOutType='".$_GET['idList'][$z]."'";
		$obj->query($mySQL8c);
	}
	// REMOVE HIM FROM THE OPT-OUT TABLE FOR GLOBAL OPT-OUT and for both old . new email
	$mySQL8a="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$sub["oldEmail"]."' AND optOutType='g'";
	$obj->query($mySQL8a);
	$mySQL8b="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$sub["email"]."' AND optOutType='g'";
	$obj->query($mySQL8b);

	$mySQL4="UPDATE ".$idGroup."_subscribers set name='".$sub["subname"]."', lastName='".$sub["sublastname"]."', email='".$sub["email"]."', subPassword='".$sub["password"]."', confirmed=-1, dateLastUpdated='".$myDay."',
	subCompany='".$sub["subCompany"]."', address='".$sub["address"]."', city='".$sub["city"]."', state='".$sub["stateCode"]."', zip='".$sub["zip"]."', country='".$sub["countryCode"]."',
	subPhone1='".$sub["subPhone1"]."', subPhone2='".$sub["subPhone2"]."',  subMobile='".$sub["subMobile"]."',
	subbirthday='".$sub["subBirthDay"]."', subbirthmonth='".$sub["subBirthMonth"]."', subBirthYear='".$sub["subBirthYear"]."', customSubField1='".$sub["customSubField1"]."',
	customSubField2='".$sub["customSubField2"]."', customSubField3='".$sub["customSubField3"]."', customSubField4='".$sub["customSubField4"]."', customSubField5='".$sub["customSubField5"]."'
	 WHERE idEmail=".$sub["idEmail"];
	$obj->query($mySQL4);
	$obj->closeDb();
    header("Location:subAccount.php?email=".$sub["email"]."&idEmail=".$sub["idEmail"]."&password=".$sub["password"]."&message=".SUBACCOUNT_15."");
    //header("Location:subAccount.php");
}

?>