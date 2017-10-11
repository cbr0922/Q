<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj=new db_class();

if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}

if (!empty($_POST['savewelcome'])) {
	$groupWelcomeScreen			= dbQuotes($_POST['groupWelcomeScreen']);
	$groupWelcomeUrl			= dbQuotes(trim($_POST['groupWelcomeUrl']));
	$groupWelcomeEmailBody		= dbQuotes($_POST['groupWelcomeEmailBody']);
	$groupWelcomeEmailBodyT		= dbQuotes($_POST['groupWelcomeEmailBodyT']);
	$groupWelcomeEmailSubject	= dbQuotes(trim($_POST['groupWelcomeEmailSubject']));
	$groupSendWelcomeEmail	= dbQuotes(trim($_POST['groupSendWelcomeEmail']));
	$groupShowWelcomeScreen	= dbQuotes(trim($_POST['groupShowWelcomeScreen']));
	
	$mySQL="UPDATE ".$idGroup."_groupSettings set groupShowWelcomeScreen=".$groupShowWelcomeScreen.", groupSendWelcomeEmail=".$groupSendWelcomeEmail.", groupWelcomeScreen='".$groupWelcomeScreen."', groupWelcomeUrl='".$groupWelcomeUrl."', groupWelcomeEmailBody='".$groupWelcomeEmailBody."', groupWelcomeEmailBodyT='".$groupWelcomeEmailBodyT."', groupWelcomeEmailSubject='".$groupWelcomeEmailSubject."' where idGroup=$idGroup";
	$message = urlencode(EDITMESSAGES_25. ' '.EDITMESSAGES_24 );
	$obj->query($mySQL);
}

if (!empty($_POST['saveconfreq'])) {
	$groupConfReqScreen			= dbQuotes($_POST['groupConfReqScreen']);
	$groupConfReqUrl			= dbQuotes(trim($_POST['groupConfReqUrl']));
	$groupConfReqEmailBody		= dbQuotes($_POST['groupConfReqEmailBody']);
	$groupConfReqEmailBodyT		= dbQuotes($_POST['groupConfReqEmailBodyT']);
	$groupConfReqEmailSubject	= dbQuotes(trim($_POST['groupConfReqEmailSubject']));
	$groupDoubleOptin			= dbQuotes(trim($_POST['groupDoubleOptin']));
	$groupShowConfReqScreen			= dbQuotes(trim($_POST['groupShowConfReqScreen']));
	$mySQL="UPDATE ".$idGroup."_groupSettings set groupShowConfReqScreen=".$groupShowConfReqScreen.", groupDoubleOptin=".$groupDoubleOptin.", groupConfReqScreen='".$groupConfReqScreen."', groupConfReqUrl='".$groupConfReqUrl."', groupConfReqEmailBody='".$groupConfReqEmailBody."', groupConfReqEmailBodyT='".$groupConfReqEmailBodyT."', groupConfReqEmailSubject='".$groupConfReqEmailSubject."' where idGroup=$idGroup";
	$message = urlencode(EDITMESSAGES_26. ' '.EDITMESSAGES_24 );
	$obj->query($mySQL);
}

if (!empty($_POST['savegoodbye'])) {
	$groupGoodbyeScreen			= dbQuotes($_POST['groupGoodbyeScreen']);
	$groupGoodbyeUrl			= dbQuotes(trim($_POST['groupGoodbyeUrl']));
	$groupGoodbyeEmailBody		= dbQuotes($_POST['groupGoodbyeEmailBody']);
	$groupGoodbyeEmailBodyT		= dbQuotes($_POST['groupGoodbyeEmailBodyT']);
	$groupGoodbyeEmailSubject	= dbQuotes(trim($_POST['groupGoodbyeEmailSubject']));
	$groupSendGoodbyeEmail		= dbQuotes(trim($_POST['groupSendGoodbyeEmail']));
	$groupShowGoodbyeScreen		= dbQuotes(trim($_POST['groupShowGoodbyeScreen']));
	$groupRequestOptOutReason		= dbQuotes(trim($_POST['groupRequestOptOutReason']));
	
	$mySQL="UPDATE ".$idGroup."_groupSettings set groupRequestOptOutReason=".$groupRequestOptOutReason.",groupSendGoodbyeEmail=".$groupSendGoodbyeEmail.",groupShowGoodbyeScreen=".$groupShowGoodbyeScreen.", groupGoodbyeScreen='".$groupGoodbyeScreen."', groupGoodbyeUrl='".$groupGoodbyeUrl."', groupGoodbyeEmailBody='".$groupGoodbyeEmailBody."', groupGoodbyeEmailBodyT='".$groupGoodbyeEmailBodyT."', groupGoodbyeEmailSubject='".$groupGoodbyeEmailSubject."' where idGroup=$idGroup";
	$message = urlencode(EDITMESSAGES_27. ' '.EDITMESSAGES_24 );
	$obj->query($mySQL);
}

if (!empty($_POST['savealreadyin'])) {
    $groupAlreadyInScreen		= dbQuotes($_POST['groupAlreadyInScreen']);
	$groupAlreadyInUrl			= dbQuotes(trim($_POST['groupAlreadyInUrl']));
	$groupAlreadyInAction			= dbQuotes(trim($_POST['groupAlreadyInAction']));
	$mySQL="UPDATE ".$idGroup."_groupSettings set groupAlreadyInAction=".$groupAlreadyInAction.", groupAlreadyInScreen='".$groupAlreadyInScreen."', groupAlreadyInUrl='".$groupAlreadyInUrl."' where idGroup=$idGroup";
	$message = urlencode(EDITMESSAGES_28. ' '.EDITMESSAGES_24 );
	$obj->query($mySQL);
}
$obj->closeDb();
header("Location: editMessages.php?message=$message");
?>
