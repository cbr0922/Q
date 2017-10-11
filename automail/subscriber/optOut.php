<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../inc/languages.php');
include('../inc/sendMail.php');

$mailData["groupName"]          =	$obj->getSetting("groupName", $idGroup);
$mailData["groupContactEmail"]  =	$obj->getSetting("groupContactEmail", $idGroup);
$mailData["groupScriptUrl"]     =	$obj->getSetting("groupScriptUrl", $idGroup);
$mailData["groupSite"]          =	$obj->getSetting("groupSite", $idGroup);
$pTimeOffsetFromServer			=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupEncryptionPassword = $obj->getSetting("groupEncryptionPassword", $idGroup);
$groupSenderEmail        =	$obj->getSetting("groupSenderEmail", $idGroup);
$groupReplyToEmail       =	$obj->getSetting("groupReplyToEmail", $idGroup);
$groupEmailComponent =	$obj->getSetting("groupEmailComponent", $idGroup);
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);
$groupRequestOptOutReason=	$obj->getSetting("groupRequestOptOutReason", $idGroup);
$myDay  = myDatenow();
$mailData["date_time"]	 = date("M d Y, H:i" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));
$pIP    = $_SERVER['REMOTE_ADDR'];

(isset($_GET['c']))?$mailData["idCampaign"]=dbQuotes(dbProtect($_GET['c'],500)):$mailData["idCampaign"]="0"; //0 to cover the global opt-out case.
(isset($_GET['e2']))?$sub["email2"] = dbQuotes(dbProtect($_GET['e2'],500)):$sub["email2"]="";
$sub["email"]   = myDecrypt($sub["email2"], $groupEncryptionPassword);
if (!$sub["email"] && isset($_GET['email'])) {$sub["email"]=dbQuotes(dbProtect($_GET['email'],500));$sub["email2"]=myEncrypt($sub["email"], $groupEncryptionPassword);}
(isset($_GET['sid']))?$sub["idEmail"] = dbQuotes(dbProtect($_GET['sid'],500)):$sub["idEmail"]="";	/* dont need this*/
(isset($_GET['a']))?$pAction = dbQuotes(dbProtect($_GET['a'],5)):$pAction="";
(isset($_GET['reason']))?$preason = dbQuotes(dbProtect($_GET['reason'],1000)):$preason="";
$mailData["listListing"]="";
$mailData["listListingT"]="";

(isset($_GET['submit']))?$submit = dbQuotes(dbProtect($_GET['submit'],50)):$submit="xx";

if ($mailData["idCampaign"]==-1 && strlen($sub["email2"])>3 && ($pAction==1 || $pAction==2 || $pAction==3) ) {
	echo SUBACCOUNT_70;
	return false;
}
// to make the reason optional, add this below: && $submit=="xx" 
IF ($groupRequestOptOutReason==-1 && trim($preason)=="" && $pAction!=3 && $sub["email"]) { //show the form ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $mailData["groupName"];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset; ?>">
</head>
<body>
	<font size=16px><?php echo $mailData["groupName"]?></font><br>
	<h1><?php echo SUBACCOUNT_29?></h1>
	<form method=get id="reasonForm" action="optOut.php">
	<?php echo SUBACCOUNT_30?>:<br />
	<textarea name=reason id=reason cols=40 rows=7></textarea>
	<input type=hidden name=e2 id=e2 value="<?php echo $sub["email2"]?>">
	<input type=hidden name=a id=a value="<?php echo $pAction?>">
	<input type=hidden name=sid id=sid value="<?php echo $sub["idEmail"]?>"> <!--dont need this-->
	<input type=hidden name=c id=c value="<?php echo $mailData["idCampaign"]?>"><br />
	<input type=submit name=submit id=submit value="<?php echo SUBACCOUNT_55?>">
	</form>

</body>	
</html>
<?php
die;
} //request reason

//check for missing data
if (!$sub["email"]  || !$pAction) {
   echo SUBACCOUNT_31;
   die;
} else {
    //Check if this email exists in the subscribers list
    $mySQL="SELECT idEmail, email, name, lastName, subCompany, address, zip, state, country, city, subPhone1, subPhone2, subMobile, subPassword, prefersHtml,
	customSubField1, customSubField2, customSubField3, customSubField4, customSubField5, subBirthDay, subBirthMonth, subBirthYear FROM ".$idGroup."_subscribers WHERE email='".$sub["email"]."'";
	$result	= $obj->query($mySQL);
    $rows 	= $obj->num_rows($result);
    //$row = $obj->fetch_array($result);
    if ($rows==1) {
        $row = $obj->fetch_array($result);
        //When it exists continue
        $pidEmail=$row["idEmail"];
		$pname 		= $row["name"];
        $sub["name"]=$pname ;
		$plastName 	= $row["lastName"];
        $sub["lastName"]=$plastName;
        $pfullName 	=  $pname.' '.$plastName;
		$sub["subCompany"]	= $row["subCompany"];
		$sub["address"]	= $row["address"];
		$sub["city"]	= $row["city"];
		$sub["state"]	= $row["state"];
		$sub["zip"]	= $row["zip"];
		$sub["country"]	= $row["country"];
		$sub["subPhone1"]	= $row["subPhone1"];
		$sub["subPhone2"]	= $row["subPhone2"];
		$sub["subMobile"]	= $row["subMobile"];
		$sub["subBirthDay"]	= $row["subBirthDay"];
		$sub["subBirthMonth"]	= $row["subBirthMonth"];
		$sub["subBirthYear"]	= $row["subBirthYear"];
		$sub["customSubField1"]	= $row["customSubField1"];
		$sub["customSubField2"]	= $row["customSubField2"];
		$sub["customSubField3"]	= $row["customSubField3"];
		$sub["customSubField4"]	= $row["customSubField4"];
		$sub["customSubField5"]	= $row["customSubField5"];

        if (strlen($pfullName)<=1) {$pfullName = $sub["email"];}
		$ppass	 	= $row["subPassword"];
        $sub["subPassword"]=$ppass;
		$prefersHtml = $row["prefersHtml"];
        $sub["prefers"]=$prefersHtml;

		//Update acount redirect
		if ($pAction==3) {
		    header("Location:subAccount.php?email=".$sub["email"]."&idEmail=".$pidEmail."&password=".$ppass."");
            die;
		}
    if ($mailData["idCampaign"]>0) {
        $mySQL = "SELECT mLists FROM ".$idGroup."_campaigns WHERE idCampaign=".$mailData["idCampaign"];
    	$result	= $obj->query($mySQL);
        $row = $obj->fetch_array($result);
        $mLists = $row["mLists"];
        if ($mLists) {
            $lists      = explode(",", $mLists);
            $listCount  = sizeof($lists);
        }
    }
    //INCREASE OPTED-OUT FOR CAMPAIGN
    if ($mailData["idCampaign"]>0) {
        $mySQL9="UPDATE ".$idGroup."_campaigns set optedOut=optedOut+1 WHERE idCampaign=".$mailData["idCampaign"];
        $obj->query($mySQL9);
    }
    //UPDATE ADMIN STATISTICS
	$mySQL7="UPDATE ".$idGroup."_adminStatistics set minusSubscribers=minusSubscribers-1";
    $obj->query($mySQL7);

    if ($pAction==1 && $mLists) {       //LIST OPT-OUT AND the campaign was sent to 1 or more selected lists
      	//Update date-last-updated for subscriber
    	$mySQL4="UPDATE ".$idGroup."_subscribers set dateLastUpdated='".$myDay."', optOutReason='".$preason."'  WHERE idGroup=$idGroup AND idEmail=".$pidEmail;
    	$obj->query($mySQL4);
     	$optOutDesc = SUBACCOUNT_32;
            for ($i=0; $i<$listCount; $i++)  {
                //REMOVE FROM SELECTED LISTS
                $mySQL2="DELETE FROM ".$idGroup."_listRecipients WHERE idGroup=$idGroup AND idEmail=".$pidEmail." AND idList=".$lists[$i];
                $obj->query($mySQL2);
            	//UPDATE LIST TRAFFIC FOR SELECTED LISTS
    	        $mySQL3="Update ".$idGroup."_lists set list_outs=list_outs+1  WHERE idList=".$lists[$i];
                $obj->query($mySQL3);
    	        // UPDATE OPT-OUTS TABLE FOR SELECTED LISTS
                $mySQL5="REPLACE INTO ".$idGroup."_optOutReasons (subscriberEmail, optOutReason, optOutType, idGroup, idCampaign, dateOptedOut) values ('".$sub["email"]."', '".$preason."', '".$lists[$i]."', $idGroup, ".$mailData["idCampaign"].", '".$myDay."')";
    	        $obj->query($mySQL5);
            }
      }
      else {            //GLOBAL OPT-OUT OR the campaign was sent either to ALL lists OR ALL subs
                        // it will work as a global opt-out, like $pAction==2
      	    $optOutDesc = SUBACCOUNT_33;
            //UPDATE LIST TRAFFIC FOR THE LISTS HE WAS ASSIGNED
  	        $mySQL6="Update ".$idGroup."_lists set list_outs=list_outs+1 WHERE idGroup=$idGroup AND idList IN (select idList from ".$idGroup."_listRecipients where idEmail=".$pidEmail.")";
            $obj->query($mySQL6);
            //REMOVE FROM ALL LISTS
            $mySQL2="DELETE FROM ".$idGroup."_listRecipients WHERE idGroup=$idGroup AND idEmail=".$pidEmail;
            $obj->query($mySQL2);
  	        //Delete from subscribers
  	        $mySQL4="DELETE FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND idEmail=".$pidEmail;
      	    $obj->query($mySQL4);
  	        // UPDATE OPT-OUTS TABLE
            $mySQL5="REPLACE INTO ".$idGroup."_optOutReasons (subscriberEmail, optOutReason, optOutType, idGroup, idCampaign, dateOptedOut) values ('".$sub["email"]."', '".$preason."', 'g', $idGroup, ".$mailData["idCampaign"].", '".$myDay."')";
            $obj->query($mySQL5);
        }
    $groupShowGoodbyeScreen		= $obj->getSetting("groupShowGoodbyeScreen", $idGroup);
    $groupGoodbyeScreen			= $obj->getSetting("groupGoodbyeScreen", $idGroup);
    $groupGoodbyeUrl			= $obj->getSetting("groupGoodbyeUrl", $idGroup);
    $groupSendGoodbyeEmail		= $obj->getSetting("groupSendGoodbyeEmail", $idGroup);
    $groupGoodbyeEmailBody		= $obj->getSetting("groupGoodbyeEmailBody", $idGroup);
    $groupGoodbyeEmailBodyT		= $obj->getSetting("groupGoodbyeEmailBodyT", $idGroup);
    $groupGoodbyeEmailSubject	= $obj->getSetting("groupGoodbyeEmailSubject", $idGroup);

    //Send the Goodbye email
    if ($groupSendGoodbyeEmail==-1) {
    	$pEmailSubject  = confirmationdata($groupGoodbyeEmailSubject, $sub, $mailData);
    	$pEmailBody     = confirmationdata($groupGoodbyeEmailBody, $sub, $mailData);
        $pEmailBodyT    = confirmationdata($groupGoodbyeEmailBodyT, $sub, $mailData);
        //$attachments="";
        sendMail($idGroup, $sub["email"], $pfullName,  $pEmailSubject, $pEmailBody, $pEmailBodyT, $attachments="", $groupGlobalCharset, "m");
    }

    //Email alerts to admins
    $mySQLal="SELECT adminFullName, adminEmail FROM ".$idGroup."_admins WHERE emailAlert=-1 AND active=-1";
    $result	= $obj->query($mySQLal);
    $pEmailBody=SUBACCOUNT_51.' '.$sub["email"].'<br>'.$optOutDesc;
    $pEmailSubject=SUBACCOUNT_52;
    $pEmailBodyT=$pEmailBody;
    while ($row=$obj->fetch_array($result)) {
        $adminEmail=$row['adminEmail'];
        $adminName=$row['adminFullName'];
        sendMail($idGroup, $adminEmail, $adminName,  $pEmailSubject, $pEmailBody, $pEmailBodyT, $attachments="", $groupGlobalCharset, "h");
    }
    if ($groupShowGoodbyeScreen==-1) {
    	$prefersHtml = "-1";
    	$pmessage 		= confirmationdata($groupGoodbyeScreen, $sub, $mailData);
    	echo $pmessage;
    }
    else {header("Location:".$groupGoodbyeUrl."");die;}

} else {echo SUBACCOUNT_21;}
$obj->closeDb();
}?>
