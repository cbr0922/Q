<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj = new db_class();

if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}
@$paction	   		= $_REQUEST['action'];
@$pltfList	   		= $_REQUEST['ltfList'];
@$pidNewsletter		= $_REQUEST['idNewsletter'];
@$pidlist	   		= $_REQUEST['idList'];
@$pidDroplist  		= $_REQUEST['idDropList'];
@$idAdminD	   		= $_REQUEST['idAdminD'];
@$pidemail			= $_REQUEST['idEmail'];
@$pidCampaign   	= $_REQUEST['idCampaign'];
@$predirecturl		= $_REQUEST['redirecturl'];
@$pmailLogcode		= $_REQUEST['mailLogCode'];
@$pidSendFilter		= $_REQUEST['idSendFilter'];
@$pidTask	        = $_REQUEST['idTask'];
@$prating			= $_REQUEST['rating'];
@$pidDataSource	    = $_REQUEST['idDataSource'];
@$pemail		    = $_REQUEST['email'];

// DELETING A DATA SOURCE
if ($pidDataSource) {
	$mySQL="DELETE FROM ".$idGroup."_dataSources WHERE idDataSource=$pidDataSource";
	$obj->query("$mySQL");
	header("Location: externalDbImport.php?message=".urlencode(EXTERNALDBIMPORTFORM_45));
	die;
}

//DELETING subscriber
IF ($pidemail!="") {
	$mySQL="DELETE FROM ".$idGroup."_listRecipients WHERE idEmail=$pidemail";
	$obj->query("$mySQL");
//	$mySQL="DELETE FROM ".$idGroup."_viewStats WHERE idEmail=$pidemail";
//	$obj->query("$mySQL");
//	$mySQL="DELETE FROM ".$idGroup."_clickStats WHERE idEmail=$pidemail";
//	$obj->query("$mySQL");
	$mySQL="DELETE FROM ".$idGroup."_subscribers WHERE idEmail=$pidemail";
	$obj->query("$mySQL");
	header("Location: editSubscriber.php?message=".urlencode(UPDATESUBSCRIBER_5));
	die;
}


//DROPPING A  LIST => delete both list and subscribers.
IF ($pidDroplist!="") {
	$mySQL5="DELETE ".$idGroup."_subscribers, ".$idGroup."_listRecipients FROM ".$idGroup."_subscribers, ".$idGroup."_listRecipients WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail AND idList=".$pidDroplist;
	$obj->query($mySQL5);
	$mySQL4="DELETE FROM ".$idGroup."_lists WHERE idList=".$pidDroplist;
	$obj->query($mySQL4);
	$obj->closeDb();
	header("Location: lists.php?message=".LISTS_43);
}


//DELETING LIST
IF ($pidlist!="") {
	$mySQL5="DELETE FROM ".$idGroup."_listRecipients WHERE idList=".$pidlist;
	$obj->query($mySQL5);
	$mySQL4="DELETE FROM ".$idGroup."_lists WHERE idList=".$pidlist;
	$obj->query($mySQL4);
	$obj->closeDb();
	header("Location: lists.php?message=".LISTS_31);
}
//DELETING NEWSLETTER
IF ($pidNewsletter!="") {
	@$message1 	= DELETENEWSLETTEREXEC_1;
	$message1	= urlencode($message1);
	//delete from the newsletter table
	$mySQL3="delete from ".$idGroup."_newsletters where idNewsletter=".$pidNewsletter;
	$obj->query($mySQL3);
	$obj->closeDb();
	if ($predirecturl=="html") {
	header("Location: htmlNewsletters.php?message=$message1");
	}
	if ($predirecturl=="text") {
	header("Location: textNewsletters.php?message=$message1");
	}

}
//DELETING ADMIN
IF ($idAdminD!="") {
    $mySQL="Delete from ".$idGroup."_admins where idAdmin=$idAdminD";
	$obj->query($mySQL);
	$mySQL1="Delete from ".$idGroup."_adminStatistics where idAdmin=$idAdminD";
	$obj->query($mySQL1);
	$obj->closeDb();
	header("Location: admins.php?message=".LISTADMINS_23);
}
//'DELETING mailing filter
IF ($pidSendFilter) {
	$mySQL="DELETE from ".$idGroup."_sendFilters WHERE idSendFilter=$pidSendFilter AND idGroup=$idGroup";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: filters.php?message=".EDITSENDFILTERSEXEC_2);
}
//DELETING ALL SUBSCRIBERS
if ($paction=="allsubs") {
	//delete from recipients list
	$mySQL="DELETE FROM ".$idGroup."_listRecipients";
	$obj->query($mySQL);
	//delete from subscribers list
	$mySQL1="DELETE FROM ".$idGroup."_subscribers";
	$obj->query($mySQL1);
	$obj->closeDb();
	header("Location: message.php?message=".DELETEALLNEWSLETTERSUBSCRIBERS_1."");
}

//DELETING ALL UNASSIGNED SUBSCRIBERS
if ($paction=="unassigned") {
    $mySQL="DELETE FROM ".$idGroup."_subscribers WHERE NOT EXISTS (SELECT idEmail FROM ".$idGroup."_listRecipients WHERE ".$idGroup."_listRecipients.idEmail=".$idGroup."_subscribers.idEmail)";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: listNewsletterSubscribers.php?message=".LISTNEWSLETTERSUBSCRIBERS_17);
}

//DELETING A CAMPAIGN
if ($paction=="campaign") {
	$mySQL7="DELETE FROM ".$idGroup."_viewStats where idCampaign=$pidCampaign";
    $obj->query($mySQL7);
	$mySQL8="DELETE FROM ".$idGroup."_clickStats where idCampaign=$pidCampaign";
    $obj->query($mySQL8);
	$mySQL10="DELETE from ".$idGroup."_tasks WHERE idCampaign=$pidCampaign";
    $obj->query($mySQL10);
	$mySQL9="DELETE FROM ".$idGroup."_campaigns WHERE idCampaign=$pidCampaign";
    $obj->query($mySQL9);
	$obj->closeDb();
   	header("Location: campaigns.php?message=".ALLSTATS_135);
}
if ($paction=="campaignviews") {
	$mySQL7="DELETE FROM ".$idGroup."_viewStats where idCampaign=$pidCampaign";
    $obj->query($mySQL7);
    header("Location: summary.php?message=".ALLSTATS_139);
}

if ($paction=="campaignclicks") {
	$mySQL8="DELETE FROM ".$idGroup."_clickStats where idCampaign=$pidCampaign";
    $obj->query($mySQL8);
    header("Location: clickStats.php?message=".ALLSTATS_138);
}

//RESET NEWSLETTER RATINGS
if ($prating) {
	$mySQL="update ".$idGroup."_newsletters set rate1=0, rate2=0, rate3=0, rate4=0, rate5=0 where idNewsletter=$prating";
    $obj->query($mySQL);
    header("Location: ratings.php?message=".ALLNEWSLETTERS_40);
}
//DELETE OPT-OUTS
if ($paction=="optouts") {
	$mySQL="DELETE FROM ".$idGroup."_optOutReasons";
    $obj->query($mySQL);
	header("Location: optOuts.php?message=".LISTREASONS_13);
}
//DELETE SINGLE EMAIL FROM OPT-OUTS
if ($paction=="optoutemail") {
	$mySQL="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$pemail."'";
    $obj->query($mySQL);
	header("Location: optOuts.php?message=".LISTREASONS_17);
}

// deleting list traffic
if ($paction=="ltf") {
	if ($pltfList) {
		$mySQL="UPDATE ".$idGroup."_lists set list_ins=0, list_outs=0 where idList=$pltfList";
	} else {
		$mySQL="UPDATE ".$idGroup."_lists set list_ins=0, list_outs=0";
	}
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: listTraffic.php?message=".LISTTRAFFIC_10);
}

//DELETING Scheduler task
if ($paction=="task") {
	$mySQL="DELETE from ".$idGroup."_tasks WHERE idTask=$pidTask";
	$obj->query($mySQL);
	$obj->closeDb();
	header("Location: _schedulerTasks.php?message=".CONFIRM_14);
}
?>
