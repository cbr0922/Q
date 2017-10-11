<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
$obj 		= new db_class();
$groupName	= $obj->getSetting("groupName", $idGroup);
$myDay 		= myDatenow();
$pidemail	= $_REQUEST['idEmail'];
//DEMO
if (@$pdemomode) {
 forDemo2("demo");
}

$pemail 			= dbQuotes(trim($_POST['email']));
$pname 				= dbQuotes(trim($_POST['name']));
$plastName			= dbQuotes(trim($_POST['lastName']));
$psubCompany		= dbQuotes(trim($_POST['subCompany']));
$paddress			= dbQuotes(trim($_POST['address']));
$pcity				= dbQuotes(trim($_POST['city']));
$pstate				= dbQuotes(trim($_POST['stateCode']));
$pzip				= dbQuotes(trim($_POST['zip']));
$pcountry			= dbQuotes(trim($_POST['countryCode']));
$psubPhone1			= dbQuotes(trim($_POST['subPhone1']));
$psubPhone2			= dbQuotes(trim($_POST['subPhone2']));
$psubMobile			= dbQuotes(trim($_POST['subMobile']));
$psubPassword		= dbQuotes(trim($_POST['subPassword']));
if (empty($psubPassword)){$psubPassword=rand(1, 15000);}
@$pprefersHtml		= dbQuotes($_POST['prefersHtml']);
if ($pprefersHtml!=-1) { $pprefersHtml=0;}
@$pconfirmed			= dbQuotes($_POST['confirmed']);
if ($pconfirmed!=-1) { $pconfirmed=0;}

@$pbanned			= dbQuotes($_POST['banned']);
if ($pbanned!=-1) { $pbanned=0;}

$invalidSQL="";
@$pinvalid		= dbQuotes($_POST['invalid']);
//echo $pinvalid;
//die;
if ($pinvalid==-1) {$invalidSQL=" emailIsValid=0, ";} else {$invalidSQL=" emailIsValid=-1, ";}

$pinternalMemo		= dbQuotes(trim($_POST['internalMemo']));
$psubBirthDay		= dbQuotes(trim($_POST['subBirthDay']));
$psubBirthMonth		= dbQuotes(trim($_POST['subBirthMonth']));
$psubBirthYear		= dbQuotes(trim($_POST['subBirthYear']));
if (isset($_POST['idList'])) {$listsTicked=count($_POST['idList']);} else {$listsTicked=0;}

if (isset($_POST['pcustomsubfield1'])) {$dcustomSubField1=dbQuotes(trim($_POST['pcustomsubfield1']));} else {$dcustomSubField1='';}
if (isset($_POST['pcustomsubfield2'])) {$dcustomSubField2=dbQuotes(trim($_POST['pcustomsubfield2']));} else {$dcustomSubField2='';}
if (isset($_POST['pcustomsubfield3'])) {$dcustomSubField3=dbQuotes(trim($_POST['pcustomsubfield3']));} else {$dcustomSubField3='';}
if (isset($_POST['pcustomsubfield4'])) {$dcustomSubField4=dbQuotes(trim($_POST['pcustomsubfield4']));} else {$dcustomSubField4='';}
if (isset($_POST['pcustomsubfield5'])) {$dcustomSubField5=dbQuotes(trim($_POST['pcustomsubfield5']));} else {$dcustomSubField5='';}

if ($_POST['action']=="update") { 
	//check if entered email already exists in database (for another subscriber of the same group)
	$mySQL1="SELECT count(*) FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND email='$pemail' AND idEmail<>$pidemail";
	if (($obj->get_rows("$mySQL1"))==0) {
		$mySQL2="update ".$idGroup."_subscribers set email='$pemail', name='$pname', lastName='$plastName', subCompany='$psubCompany', address='$paddress', city='$pcity', state='$pstate', zip='$pzip', country='$pcountry', subPhone1='$psubPhone1', subPhone2='$psubPhone2',  subMobile='$psubMobile',  subPassword='$psubPassword', prefersHtml=$pprefersHtml, confirmed=$pconfirmed, emailIsBanned=$pbanned, $invalidSQL internalMemo='$pinternalMemo', subbirthday='$psubBirthDay', subbirthmonth='$psubBirthMonth', subBirthYear='$psubBirthYear', customSubField1='$dcustomSubField1', customSubField2='$dcustomSubField2', customSubField3='$dcustomSubField3', customSubField4='$dcustomSubField4', customSubField5='$dcustomSubField5' WHERE idEmail=$pidemail";
		$obj->query($mySQL2);
		$mySQL3="SELECT idList FROM ".$idGroup."_lists where idGroup=$idGroup";
		$result3	= $obj->query($mySQL3);
		while( $row = $result3->fetch_assoc()){$allLists_array[] = $row['idList'];}
		if ($listsTicked!=0) {
			for ($z=0; $z<$listsTicked; $z++)  {
		 		$checkedListsArray[]=$_POST['idList'][$z];
				$mySQL4="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$pidemail.", ".$_POST['idList'][$z].", $idGroup)";
				$result	= $obj->query($mySQL4);
			}
			$notChecked = array_diff($allLists_array,$checkedListsArray );	//finds those that were not checked.
			foreach ($notChecked as $key => $value) { //and we delete them
				$mySQLd="DELETE from ".$idGroup."_listRecipients WHERE idEmail=$pidemail AND idList=$value";
				$obj->query($mySQLd);
			}
		}
		else {
			$mySQL3="DELETE from ".$idGroup."_listRecipients WHERE idEmail=$pidemail";
			$obj->query($mySQL3);
		}
		echo "updated#";
		die;
	}
	else {
		$mySQL5="SELECT idEmail FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND email='$pemail'";
		$result = $obj->query($mySQL5);
		$row = $obj->fetch_array($result);
		$emailIs = $row[0];
		echo "exists#".$emailIs;
		return false;
	}
}	//update ends


if ($_POST['action']=="insert") {
	//check if entered email already exists in database (for another subscriber of the same group)
	$mySQL1="SELECT count(*) FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND email='$pemail'";
	if (($obj->get_rows("$mySQL1"))==0) {
		$canAdd='yes';
		$mySQL5="INSERT INTO ".$idGroup."_subscribers (idGroup, email, name, lastName, subCompany, address, zip, state, country, city, subPassword, subPhone1, subPhone2, subMobile, dateSubscribed, prefersHtml, internalMemo, subBirthDay, subBirthMonth, subBirthYear, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5) VALUES ($idGroup, '$pemail', '$pname', '$plastName', '$psubCompany', '$paddress',  '$pzip', '$pstate', '$pcountry', '$pcity', '$psubPassword', '$psubPhone1', '$psubPhone2', '$psubMobile', '$myDay', $pprefersHtml, '$pinternalMemo', '$psubBirthDay', '$psubBirthMonth', '$psubBirthYear', '$dcustomSubField1', '$dcustomSubField2', '$dcustomSubField3', '$dcustomSubField4', '$dcustomSubField5')";
		$obj->query($mySQL5);
		$lastId =  $obj->insert_id();
		if ($listsTicked!=0) {
			for ($z=0; $z<$listsTicked; $z++)  {
				$mySQL4="INSERT INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$lastId.", ".$_POST['idList'][$z].", $idGroup)";
				$result	= $obj->query($mySQL4);
			}
		}
	echo "added#$lastId";
	die;
	}
	else {
		$mySQL5="SELECT idEmail FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND email='$pemail'";
		$result = $obj->query($mySQL5);
		$row = $obj->fetch_array($result);
		$emailIs = $row[0];
		echo "exists2#".$emailIs;
		return false;
	}
}
?>
