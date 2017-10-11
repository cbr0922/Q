<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../inc/sendMail.php');
include('../inc/sendLast.php');
include('../admin/includes/auxFunctions.php');
include('../inc/languages.php');

$groupGlobalCharset         = $obj->getSetting("groupGlobalCharset", $idGroup);
$groupEncryptionPassword    = $obj->getSetting("groupEncryptionPassword", $idGroup);
$groupDoubleOptin           = $obj->getSetting("groupDoubleOptin", $idGroup);
$groupShowWelcomeScreen		= $obj->getSetting("groupShowWelcomeScreen", $idGroup);
$groupWelcomeScreen			= $obj->getSetting("groupWelcomeScreen", $idGroup);
$groupWelcomeUrl			= $obj->getSetting("groupWelcomeUrl", $idGroup);
$groupSendWelcomeEmail		= $obj->getSetting("groupSendWelcomeEmail", $idGroup);
$groupWelcomeEmailBody		= $obj->getSetting("groupWelcomeEmailBody", $idGroup);
$groupWelcomeEmailBodyT		= $obj->getSetting("groupWelcomeEmailBodyT", $idGroup);
$groupWelcomeEmailSubject	= $obj->getSetting("groupWelcomeEmailSubject", $idGroup);

$groupShowConfReqScreen		= $obj->getSetting("groupShowConfReqScreen", $idGroup);		//also editable in settings page
$groupConfReqScreen			= $obj->getSetting("groupConfReqScreen", $idGroup);
$groupConfReqUrl			= $obj->getSetting("groupConfReqUrl", $idGroup);
$groupConfReqEmailBody		= $obj->getSetting("groupConfReqEmailBody", $idGroup);
$groupConfReqEmailBodyT		= $obj->getSetting("groupConfReqEmailBodyT", $idGroup);
$groupConfReqEmailSubject	= $obj->getSetting("groupConfReqEmailSubject", $idGroup);

$groupAlreadyInAction        = $obj->getSetting("groupAlreadyInAction", $idGroup);
$groupAlreadyInScreen        = $obj->getSetting("groupAlreadyInScreen", $idGroup);
$groupAlreadyInUrl           = $obj->getSetting("groupAlreadyInUrl", $idGroup);

$mailData["groupName"]          =	$obj->getSetting("groupName", $idGroup);
$mailData["groupContactEmail"]  =	$obj->getSetting("groupContactEmail", $idGroup);
$mailData["groupScriptUrl"]     =	$obj->getSetting("groupScriptUrl", $idGroup);
$mailData["groupSite"]          =	$obj->getSetting("groupSite", $idGroup);

$myDay  = myDatenow();
$pTimeOffsetFromServer		=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$mailData["date_time"]			= date("M d Y, H:i" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));

$sub["dateSubscribed"] = $myDay;
$pIP    = $_SERVER['REMOTE_ADDR'];

//collect form data
(isset($_POST['email']))?$sub["email"] = dbQuotes(dbProtect($_POST['email'],500)):$sub["email"]="";
if (!$sub["email"] || !strstr($sub["email"],"@") || !strstr($sub["email"],".")) {
  die(SUBACCOUNT_36);}  //stop, there is no email

$sub["email2"]   = myEncrypt($sub["email"], $groupEncryptionPassword);
if (isset($_POST['name']))		{$sub["name"] = my_stripslashes(ucwords($_POST['name']));} 		   		else {$sub["name"]="";}
if (isset($_POST['lastname']))	{$sub["lastName"] = my_stripslashes(ucwords($_POST['lastname']));} 		else {$sub["lastName"]="";}
if (isset($_POST['name']))		{$sub["nameForDB"] = dbQuotes(dbProtect(ucwords($_POST['name']),500));$sqlName="name='".$sub["nameForDB"]."', ";} 	else {$sub["nameForDB"]="";$sqlName="";}
if (isset($_POST['lastname']))	{$sub["lastNameForDB"] = dbQuotes(dbProtect(ucwords($_POST['lastname']),500));$sqlLastName="lastName='".$sub["lastNameForDB"]."', ";} else {$sub["lastNameForDB"]="";$sqlLastName="";}
if ($sub["name"] || $sub["lastName"]) {
    $pfullName = $sub["name"].' '.$sub["lastName"];
}
else {$pfullName=$sub["email"];}
if (isset($_POST['subcompany']))	{$sub["subCompany"] = my_stripslashes($_POST['subcompany']);	$sub["subCompanyForDB"] = dbQuotes(dbProtect($_POST['subcompany'],500));$sqlCompany="subCompany='".$sub["subCompanyForDB"]."', ";}	else {$sub["subCompany"]="";$sub["subCompanyForDB"]="";$sqlCompany="";}
if (isset($_POST['subphone1']))		{$sub["subPhone1"] = my_stripslashes($_POST['subphone1']);		$sub["subPhone1ForDB"] = dbQuotes(dbProtect($_POST['subphone1'],500));	$sqlPhone1 = "subPhone1='".$sub["subPhone1ForDB"]."', ";}	else {$sub["subPhone1"]="";$sub["subPhone1ForDB"]="";$sqlPhone1="";}
if (isset($_POST['subphone2']))		{$sub["subPhone2"] = my_stripslashes($_POST['subphone2']);		$sub["subPhone2ForDB"] = dbQuotes(dbProtect($_POST['subphone2'],500));	$sqlPhone2 = "subPhone2='".$sub["subPhone2ForDB"]."', ";}	else {$sub["subPhone2"]="";$sub["subPhone2ForDB"]="";$sqlPhone2="";}
if (isset($_POST['submobile']))		{$sub["subMobile"] = my_stripslashes($_POST['submobile']);		$sub["subMobileForDB"] = dbQuotes(dbProtect($_POST['submobile'],500));	$sqlMobile = "subMobile='".$sub["subMobileForDB"]."', ";}	else {$sub["subMobile"]="";$sub["subMobileForDB"]="";$sqlMobile="";}
if (isset($_POST['address']))		{$sub["address"] = my_stripslashes($_POST['address']);			$sub["addressForDB"] = dbQuotes(dbProtect($_POST['address'],500));		$sqlAddress="address='".$sub["addressForDB"]."', ";}   		else {$sub["address"]="";	$sub["addressForDB"]="";$sqlAddress="";}
if (isset($_POST['city']))			{$sub["city"] = my_stripslashes($_POST['city']);				$sub["cityForDB"] = dbQuotes(dbProtect($_POST['city'],500));$sqlCity = "city='".$sub["cityForDB"]."', ";}	else {$sub["city"]="";$sub["cityForDB"]="";$sqlCity="";}
if (isset($_POST['zip']))			{$sub["zip"] = my_stripslashes($_POST['zip']);				   		$sub["zipForDB"] = dbQuotes(dbProtect($_POST['zip'],500));$sqlZip="zip='".$sub["zipForDB"]."', ";}	else {$sub["zip"]="";$sub["zipForDB"]="";$sqlZip="";}
if (isset($_POST['subbirthday']))	{$sub["subBirthDay"] = my_stripslashes($_POST['subbirthday']);		$sub["subBirthDayForDB"] = dbQuotes(dbProtect($_POST['subbirthday'],500));$sqlDay="subBirthDay='".$sub["subBirthDayForDB"]."', ";}	else {$sub["subBirthDay"]="";$sub["subBirthDayForDB"]="";$sqlDay="";}
if (isset($_POST['subbirthmonth']))	{$sub["subBirthMonth"] = my_stripslashes($_POST['subbirthmonth']);	$sub["subBirthMonthForDB"] = dbQuotes(dbProtect($_POST['subbirthmonth'],500));$sqlMonth="subBirthMonth='".$sub["subBirthMonthForDB"]."', ";}	else {$sub["subBirthMonth"]="";$sub["subBirthMonthForDB"]="";$sqlMonth="";}
if (isset($_POST['subbirthyear']))	{$sub["subBirthYear"] = my_stripslashes($_POST['subbirthyear']);	$sub["subBirthYearForDB"] = dbQuotes(dbProtect($_POST['subbirthyear'],500));$sqlYear="subBirthYear='".$sub["subBirthYearForDB"]."', ";}	else {$sub["subBirthYear"]="";$sub["subBirthYearForDB"]="";$sqlYear="";}
if (isset($_POST['statecode']))		{$sub["state"] 		= my_stripslashes($_POST['statecode']);					$sub["stateForDB"] = dbQuotes(dbProtect($_POST['statecode'],500));		$sqlState = "state='".$sub["stateForDB"]."',  ";}		else {$sub["state"]="";$sub["stateForDB"]="";$sqlState="";}
if (isset($_POST['countrycode']))	{$sub["country"] 	= my_stripslashes($_POST['countrycode']);				$sub["countryForDB"] = dbQuotes(dbProtect($_POST['countrycode'],500));	$sqlCountry = "country='".$sub["countryForDB"]."', ";}	else {$sub["country"]="";$sub["countryForDB"]="";$sqlCountry="";}
if (isset($_POST['pcustomsubfield1']))	{$sub["customSubField1"] = my_stripslashes($_POST['pcustomsubfield1']);$sub["customSubField1ForDB"] = dbQuotes(dbProtect($_POST['pcustomsubfield1'],500));$sqlCustom1 = "customSubField1='".$sub["customSubField1ForDB"]."', ";}	else {$sub["customSubField1"]="";$sub["customSubField1ForDB"]="";$sqlCustom1="";}
if (isset($_POST['pcustomsubfield2']))	{$sub["customSubField2"] = my_stripslashes($_POST['pcustomsubfield2']);$sub["customSubField2ForDB"] = dbQuotes(dbProtect($_POST['pcustomsubfield2'],500));$sqlCustom2 = "customSubField2='".$sub["customSubField2ForDB"]."', ";}	else {$sub["customSubField2"]="";$sub["customSubField2ForDB"]="";$sqlCustom2="";}
if (isset($_POST['pcustomsubfield3']))	{$sub["customSubField3"] = my_stripslashes($_POST['pcustomsubfield3']);$sub["customSubField3ForDB"] = dbQuotes(dbProtect($_POST['pcustomsubfield3'],500));$sqlCustom3 = "customSubField3='".$sub["customSubField3ForDB"]."', ";}	else {$sub["customSubField3"]="";$sub["customSubField3ForDB"]="";$sqlCustom3="";}
if (isset($_POST['pcustomsubfield4']))	{$sub["customSubField4"] = my_stripslashes($_POST['pcustomsubfield4']);$sub["customSubField4ForDB"] = dbQuotes(dbProtect($_POST['pcustomsubfield4'],500));$sqlCustom4 = "customSubField4='".$sub["customSubField4ForDB"]."', ";}	else {$sub["customSubField4"]="";$sub["customSubField4ForDB"]="";$sqlCustom4="";}
if (isset($_POST['pcustomsubfield5']))	{$sub["customSubField5"] = my_stripslashes($_POST['pcustomsubfield5']);$sub["customSubField5ForDB"] = dbQuotes(dbProtect($_POST['pcustomsubfield5'],500));$sqlCustom5 = "customSubField5='".$sub["customSubField5ForDB"]."', ";}	else {$sub["customSubField5"]="";$sub["customSubField5ForDB"]="";$sqlCustom5="";}
(isset($_POST['prefers']))?$sub["prefers"] = dbQuotes(dbProtect($_POST['prefers'],500)):$sub["prefers"]="-1";
if (isset($_POST['password']))	{$sub["subPassword"] = dbQuotes(dbProtect($_POST['password'],500));} else {$sub["subPassword"]=rand(1, 15000);}
$sqlPassword="subPassword='".$sub["subPassword"]."', ";

@$listsTicked   = count($_POST['idlist']);
$listNamesDivider = "<br>";
$listNamesDividerT = "\r\n";
$listListing  = "";
$listListingT = "";
$mailData["listListing"]="";
$mailData["listListingT"]="";
if ($listsTicked>0) {
	For ($z=0; $z<$listsTicked; $z++)  {
	    $zList = dbQuotes(dbProtect($_POST['idlist'][$z],500));
		$addList = getlistname($zList, $idGroup);
		$listListing = $listListing.$addList.$listNamesDivider;
        $listListingT = $listListingT.$addList.$listNamesDividerT;
	}
    $mailData["listListing"]=$listListing;
    $mailData["listListingT"]=$listListingT;
}
/*
	$pCanUpdate
	With -1 lists are always updated.
	With 0 then list assignments for existing subscribers will be updated if and only you asked for a password in the form 
	and it matches the one already in the db. 
*/
$pCanUpdate="-1"; 
//Check if this subscriber is already in the subscribers list
$mySQL1="SELECT idEmail, email, name, prefersHtml, subPassword, confirmed FROM ".$idGroup."_subscribers WHERE email='".$sub["email"]."'";
$result	= $obj->query($mySQL1);
$row = $obj->fetch_array($result);
if ($row['idEmail']) {						// HE IS ALREADY IN THE SUBSCRIBERS LIST
    $sub["idEmail"] = $row['idEmail'];
	if ($row['subPassword']==$sub["subPassword"]) {	//'### Password match => Do the related updates
		$pCanUpdate="-1";
	}
	//if ($row['confirmed']=="0") {echo "not verified";} 
    $listNamesDivider = "<br>";
    $listListing = "";
	
	if ($groupAlreadyInAction==3) {	//REDIRECT TO UPDATE HIS SUBSCRIPTIONS
	    $obj->closeDb();
		header('Location:subLogin.php?subemail='.$sub["email"].'');
        die;
    }
	if ($groupAlreadyInAction==1 || $groupAlreadyInAction==2) { //update profile and lists
	    if ($pCanUpdate=="-1") {    
			assignToLists($sub["idEmail"], $sub["email"], $listsTicked, $idGroup);
		    $aSQL="UPDATE ".$idGroup."_subscribers set $sqlName $sqlLastName $sqlCompany $sqlAddress $sqlZip $sqlState $sqlCountry  $sqlCity  $sqlPhone1 $sqlPhone2
		        $sqlMobile $sqlPassword $sqlCustom1 $sqlCustom2 $sqlCustom3 $sqlCustom4 $sqlCustom5 $sqlDay $sqlMonth $sqlYear
	    	    emailIsBanned=0 WHERE idEmail=".$sub["idEmail"];
	    	$obj->query($aSQL);
		}
		if ($groupDoubleOptin==-1 && $row['confirmed']=="0") {	//since double opt-in is active and sub ecists as un-confirmed we send him an email to confirm
			$pEmailSubject	= confirmationdata($groupConfReqEmailSubject, $sub, $mailData);
    	    $pEmailBody	    = confirmationdata($groupConfReqEmailBody, $sub, $mailData);
        	$pEmailBodyT    = confirmationdata($groupConfReqEmailBodyT, $sub, $mailData);
	        sendMail($idGroup, $sub["email"], $pfullName,  $pEmailSubject, $pEmailBody, $pEmailBodyT, $attachments="", $groupGlobalCharset, "m");

		}
	}
	if ($groupAlreadyInAction==1) {			//JUST SHOW THE ALREADY SUBSCRIBED SCREEN
        $sub["prefers"] = "-1";
		$pmessage	= confirmationdata($groupAlreadyInScreen, $sub, $mailData);
		echo $pmessage;
		$obj->closeDb();
		die;
    }
    else if ($groupAlreadyInAction==2) {	//REDIRECT TO OWN URL/PAGE
        $obj->closeDb();
        header("Location:$groupAlreadyInUrl");
        die;
    }
}
else {	// ********* NEW SUBSCRIBER  ********* //
    $mySQL2="UPDATE ".$idGroup."_adminStatistics set newSubscribers=newSubscribers+1 WHERE idGroup=$idGroup";
	$obj->query($mySQL2);
    //EMAIL ALERTS TO ADMINS
    $mySQLal="SELECT adminFullName, adminEmail FROM ".$idGroup."_admins WHERE emailAlert=-1 AND active=-1";
    $result	= $obj->query($mySQLal);
    $pEmailBody=SUBACCOUNT_53.' '.$sub["email"];
    $pEmailSubject=SUBACCOUNT_54;
    $pEmailBodyT=$pEmailBody;
    while ($row=$obj->fetch_array($result)) {
        $adminEmail=$row['adminEmail'];
        $adminName=$row['adminFullName'];
        sendMail($idGroup, $adminEmail, $adminName,  $pEmailSubject, $pEmailBody, $pEmailBodyT, $attachments="", $groupGlobalCharset, "h");
    }

	if ($groupDoubleOptin==-1) {$pverstatus=0;}	//INSERT UN-VERIFIED
    else {$pverstatus=-1;}						//INSERT VERIFIED

    // INSERT SUBSCRIBER
    $mySQL2="INSERT INTO ".$idGroup."_subscribers (email, name, lastName, subCompany, address, zip, state, country, city, subPhone1, subPhone2, subMobile,
    subPassword, prefersHtml, confirmed, dateSubscribed, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5,
    ipSubscribed, subBirthDay, subBirthMonth, subBirthYear, idGroup) VALUES
    ('".$sub["email"]."', '".$sub["nameForDB"]."', '".$sub["lastNameForDB"]."', '".$sub["subCompanyForDB"]."', '".$sub["addressForDB"]."',  '".$sub["zipForDB"]."', '".$sub["stateForDB"]."', '".$sub["countryForDB"]."', '".$sub["cityForDB"]."', '".$sub["subPhone1ForDB"]."', '".$sub["subPhone2ForDB"]."', '".$sub["subMobileForDB"]."',
    '".$sub["subPassword"]."', ".$sub["prefers"].", ".$pverstatus.", '".$myDay."', '".$sub["customSubField1ForDB"]."', '".$sub["customSubField2ForDB"]."',
    '".$sub["customSubField3ForDB"]."',  '".$sub["customSubField4ForDB"]."', '".$sub["customSubField5ForDB"]."', '".$pIP."', '".$sub["subBirthDayForDB"]."', '".$sub["subBirthMonthForDB"]."', '".$sub["subBirthYearForDB"]."', ".$idGroup.")";
    $obj->query($mySQL2);
    $last =  $obj->insert_id();
    $sub["idEmail"] = $last;
    if ($listsTicked) {
        for ($z=0; $z<$listsTicked; $z++)  {
            $zList = dbQuotes(dbProtect($_POST['idlist'][$z],500));
            $mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$last.", ".$zList.", ".$idGroup.")";
            $obj->query($mySQL3);
			$mySQL4="Update ".$idGroup."_lists set list_ins=list_ins+1 WHERE idList=".$zList;
			$obj->query($mySQL4);
		}
	}
	//## REMOVE HIM FROM THE OPT-OUT TABLE FOR all types
	$mySQL5="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$sub["email"]."' AND idGroup=$idGroup";
	$obj->query($mySQL5);

    if ($groupDoubleOptin==-1) {        //DOUBLE OPTIN IS ACTIVE => SEND CONFIRMATION-REQUIRED EMAIL
		$pEmailSubject	= confirmationdata($groupConfReqEmailSubject, $sub, $mailData);
        $pEmailBody	    = confirmationdata($groupConfReqEmailBody, $sub, $mailData);
        $pEmailBodyT    = confirmationdata($groupConfReqEmailBodyT, $sub, $mailData);
        sendMail($idGroup, $sub["email"], $pfullName,  $pEmailSubject, $pEmailBody, $pEmailBodyT, $attachments="", $groupGlobalCharset, "m");
        if ($groupShowConfReqScreen==-1) {      // show conf-required built in screen
            $pScreenMessage = confirmationdata($groupConfReqScreen, $sub, $mailData);
            echo $pScreenMessage;}
        else {                            //redirect to the URL defined
            $obj->closeDb();
            header("Location:$groupConfReqUrl");die;}
    }
    else {   //DOUBLE OPTIN NOT ACTIVE
        if ($groupSendWelcomeEmail==-1) {       // SEND THE WELCOME EMAIL
            $pEmailSubject	=	confirmationdata($groupWelcomeEmailSubject, $sub, $mailData);
            $pEmailBody     =	confirmationdata($groupWelcomeEmailBody, $sub, $mailData);
            $pEmailBodyT	= 	confirmationdata($groupWelcomeEmailBodyT, $sub, $mailData);
            $attachments="";
            sendMail($idGroup, $sub["email"], $pfullName,  $pEmailSubject, $pEmailBody, $pEmailBodyT, $attachments, $groupGlobalCharset, "m");
        }
	    // SEND A PRE-SELECTED NEWSLETTER (IF THERE IS ONE)
		sendLast($sub["idEmail"], $mailData, $idGroup);
        if ($groupShowWelcomeScreen==-1) {
		    $pScreenMessage = confirmationdata($groupWelcomeScreen, $sub, $mailData);
			echo  $pScreenMessage;
		}
        else {header("Location:$groupWelcomeUrl");die;}
    }	//for confirmation system

}
?>