<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/sendMail.php');
include('../inc/encryption.php');
include('../inc/languages.php');
$groupName                  =	$obj->getSetting("groupName", $idGroup);
$groupSite                  =	$obj->getSetting("groupSite", $idGroup);
$groupContactEmail          =	$obj->getSetting("groupContactEmail", $idGroup);
$mailData["groupScriptUrl"] =	$obj->getSetting("groupScriptUrl", $idGroup);
$groupGlobalCharset             =	$obj->getSetting("groupGlobalCharset", $idGroup);
session_start();
$pverificationcodeEntered = dbQuotes(dbProtect($_REQUEST['verificationcode'],150));
$pSessionVerificationCode = $_SESSION['verificationcode'];
?>
<html>
<head>
	<title><?php echo $groupName.' - '.SUBACCOUNT_37;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset;?>">
<style>
body {FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px; COLOR: #565656; MARGIN: 12px; PADDING: 0px; }
.title 		{ FONT-FAMILY: Arial, helvetica; FONT-SIZE: 18px; FONT-WEIGHT: bold; color:#6666CC }
TD { COLOR: #565656; FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px }
.submit {COLOR: #646464; FONT-FAMILY: Arial, Tahoma, Verdana, Helvetica, sans-serif; FONT-SIZE: 12px; FONT-WEIGHT: BOLD }
.inputbox {position: relative; background: #eeeeee; padding: 0px 0px 0px 0px; color: #565656; BORDER-BOTTOM: #c9c9c9 1px solid; BORDER-LEFT: #c9c9c9 1px solid; BORDER-RIGHT: #c9c9c9 1px solid; BORDER-TOP: #c9c9c9 1px solid; margin: 0px 0px 0px 0px; text-decoration: none; text-align:left; width:200px; FONT-SIZE: 12px;}
.textarea {BACKGROUND: #eeeeee; BORDER-BOTTOM: #565656 1px solid; BORDER-LEFT: #565656 1px solid; BORDER-RIGHT: #565656 1px solid; BORDER-TOP: #565656 1px solid; COLOR: #565656; FONT-FAMILY: Arial, Tahoma, Verdana, Helvetica, sans-serif; FONT-SIZE: 12px;}
.top 		{ FONT-FAMILY: Arial, helvetica; FONT-SIZE: 20px; FONT-WEIGHT: bold; color:#565656 }
</style>
</head>
<body>
<?php
if ($pverificationcodeEntered!=$pSessionVerificationCode) {
	echo  "Mismatch between session and form verification code. Execution cancelled";
	die;
}
$sub["email2"]="";
$sub["idEmail"]="";
$sub["subPassword"]="";

$idCampaign  	        = dbQuotes(dbProtect($_REQUEST['idCampaign'], 120));
$mailData["idCampaign"] = $idCampaign;
$idNewsletter 	= dbQuotes(dbProtect($_REQUEST['idNewsletter'], 120));

$pfromName		= dbQuotes(dbProtect($_REQUEST['fromName'], 120));
$pfromEmail		= dbQuotes(dbProtect($_REQUEST['fromEmail'], 120));

$toName1		= dbQuotes(dbProtect($_REQUEST['toName1'], 120));
$toEmail1		= dbQuotes(dbProtect($_REQUEST['toEmail1'], 120));
$toName2		= dbQuotes(dbProtect($_REQUEST['toName2'], 120));
$toEmail2		= dbQuotes(dbProtect($_REQUEST['toEmail2'], 120));
$toName3		= dbQuotes(dbProtect($_REQUEST['toName3'], 120));
$toEmail3		= dbQuotes(dbProtect($_REQUEST['toEmail3'], 120));
$toName4		= dbQuotes(dbProtect($_REQUEST['toName4'], 120));
$toEmail4		= dbQuotes(dbProtect($_REQUEST['toEmail4'], 120));
$toName5		= dbQuotes(dbProtect($_REQUEST['toName5'], 120));
$toEmail5		= dbQuotes(dbProtect($_REQUEST['toEmail5'], 120));

$flag="h";
if ($idNewsletter) {    //we have a newsletter, get it
    $mailData["idHtmlNewsletter"]=$idNewsletter;
    $mailData["idTextNewsletter"]=$idNewsletter;

    $mySQL1="SELECT name, body, html, charset, attachments FROM ".$idGroup."_newsletters WHERE idNewsletter=$idNewsletter";
    $result	        = $obj->query($mySQL1);
    $row            = $obj->fetch_array($result);
    $psubject	    = $row['name'];
    $pbody 			= $row['body'];
    $phtml 			= $row['html'];
    $pcharset		= $row['charset'];
    $attachments    = $row['attachments'];
    if ($phtml==0) {$flag="t";}
    if ($phtml=="-1") {
        $pbody = otherHtmlTags($pbody, $sub, $mailData);
    }
    else {  //text newsletter
        $pbody	= str_ireplace("#companyname#", $groupName, $pbody);
        $pbody	= str_ireplace("#contactemail#", $groupContactEmail, $pbody);
        $pbody	= str_ireplace("#companysite#", $groupSite, $pbody);
        $pbody  = otherTextTags($pbody, $sub, $mailData);
    }
} elseif (!$idNewsletter && $idCampaign) {    //get url from campaign
    $mailData["idHtmlNewsletter"]="";
    $mailData["idTextNewsletter"]="";

    $mySQL="SELECT urlToSend, emailSubject FROM ".$idGroup."_campaigns WHERE idGroup=$idGroup AND idCampaign=$idCampaign";
    $result	        = $obj->query($mySQL);
    $row            = $obj->fetch_array($result);
    $urlToSend      = $row['urlToSend'];
    $pbody          = getBodyFromUrl($urlToSend, $groupGlobalCharset);
    $psubject       = $row['emailSubject'];
    $attachments="";
    $pcharset = $groupGlobalCharset;
}
$psubject		= $psubject .' ['.SUBACCOUNT_48.' '.$pfromName.'-'.$pfromEmail.']';
$forwards=0;

//START SENDING EMAILS
IF ($toEmail1) {
	//call sendMail($pfromEmail, $groupSenderEmail, $toName1, $toEmail1, $psubject, $pbody )
    $psubject	= str_ireplace("#subname#", $toName1, $psubject);
    sendMail($idGroup, $toEmail1, $toName1, $psubject, $pbody, $pbody, $attachments, $pcharset, $flag);
	$forwards=$forwards+1;
}
IF ($toEmail2) {
    $psubject	= str_ireplace("#subname#", $toName2, $psubject);
	sendMail($idGroup, $toEmail2, $toName2, $psubject, $pbody, $pbody, $attachments, $pcharset, $flag);
	$forwards=$forwards+1;
}
IF ($toEmail3) {
	$psubject	= str_ireplace("#subname#", $toName3, $psubject);
    sendMail($idGroup, $toEmail3, $toName3, $psubject, $pbody, $pbody, $attachments, $pcharset, $flag);
	$forwards=$forwards+1;
}
IF ($toEmail4) {
	$psubject	= str_ireplace("#subname#", $toName4, $psubject);
    sendMail($idGroup, $toEmail4, $toName4, $psubject, $pbody, $pbody, $attachments, $pcharset, $flag);
	$forwards=$forwards+1;
}
IF ($toEmail5) {
	$psubject	= str_ireplace("#subname#", $toName5, $psubject);
    sendMail($idGroup, $toEmail5, $toName5, $psubject, $pbody, $pbody, $attachments, $pcharset, $flag);
	$forwards=$forwards+1;
}
$pNewforwards = ceil($forwards);
if ($idCampaign) {
    $mySQL9="UPDATE ".$idGroup."_campaigns set forwarded=forwarded+".$pNewforwards." WHERE idCampaign=$idCampaign";
    $obj->query($mySQL9);
}
?>

<table align="center" width="500" cellspacing="0" cellpadding="6" border="0">
<tr>
	<td align=center>
		<span class="top"><?php echo $groupName?></span>
	</td>
</tr>

<tr>
	<td align=center>
		<strong><?php echo SUBACCOUNT_49?></strong><br><?php echo SUBACCOUNT_50?>
	</td>
</tr>
</table>
</body>
</html>