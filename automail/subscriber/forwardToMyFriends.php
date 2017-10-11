<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

function randomNumber($limit) {return rand(1, 15000);}
$pverificationcode = randomNumber(999999);
session_start();
$_SESSION['verificationcode'] = $pverificationcode;
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../inc/languages.php');
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupName              =	$obj->getSetting("groupName", $idGroup);
$password               =	$obj->getSetting("groupEncryptionPassword", $idGroup);
$groupGlobalCharset     =	$obj->getSetting("groupGlobalCharset", $idGroup);
?>
<html>
<head>
	<title><?php echo $groupName.' - '.SUBACCOUNT_37;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset;?>">
<style>
body {FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px; COLOR: #565656; MARGIN: 12px; PADDING: 0px; }
.title 		{ FONT-FAMILY: Arial, helvetica; FONT-SIZE: 18px; FONT-WEIGHT: bold; color:#6666CC }
.top 		{ FONT-FAMILY: Arial, helvetica; FONT-SIZE: 20px; FONT-WEIGHT: bold; color:#565656 }
TD { COLOR: #565656; FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px }
.submit {COLOR: #646464; FONT-FAMILY: Arial, Tahoma, Verdana, Helvetica, sans-serif; FONT-SIZE: 12px; FONT-WEIGHT: BOLD }
.inputbox {position: relative; background: #eeeeee; padding: 0px 0px 0px 0px; color: #565656; BORDER-BOTTOM: #c9c9c9 1px solid; BORDER-LEFT: #c9c9c9 1px solid; BORDER-RIGHT: #c9c9c9 1px solid; BORDER-TOP: #c9c9c9 1px solid; margin: 0px 0px 0px 0px; text-decoration: none; text-align:left; width:200px; FONT-SIZE: 12px;}
.textarea {BACKGROUND: #eeeeee; BORDER-BOTTOM: #565656 1px solid; BORDER-LEFT: #565656 1px solid; BORDER-RIGHT: #565656 1px solid; BORDER-TOP: #565656 1px solid; COLOR: #565656; FONT-FAMILY: Arial, Tahoma, Verdana, Helvetica, sans-serif; FONT-SIZE: 12px;}
</style>
<script Language="Javascript" type="text/javascript">
<!--
function $Npro(field){var element =  document.getElementById(field);return element;return false;}
function emailvalidation(field)
{
	if ($Npro(field).value!="")	{
		var goodEmail = $Npro(field).value.match(/[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?/);
		apos=$Npro(field).value.indexOf("@");dotpos=$Npro(field).value.lastIndexOf(".");lastpos=$Npro(field).value.length-1;tldLen = lastpos-dotpos;dmLen=dotpos-apos-1;var badEmail= (tldLen<2 || dmLen<2 || apos<1);
		if (goodEmail && !badEmail) {return true;}
		else {return false;}
	}
	else {return true;}
}
function checkIt()
{
	var canPass = true;
	var canPass2 = true;
	if ($Npro("fromName").value=="" || $Npro("fromEmail").value==""  || $Npro("verificationcode").value=="")
	{canPass2=false;}
	if ($Npro("toEmail1").value=="" && $Npro("toEmail2").value=="" && $Npro("toEmail3").value=="" && $Npro("toEmail4").value=="" && $Npro("toEmail5").value=="")
	{canPass = false;}
	if (emailvalidation("toEmail1")==false || emailvalidation("toEmail2")==false || emailvalidation("toEmail3")==false || emailvalidation("toEmail4")==false || emailvalidation("toEmail5")==false)
	{canPass = false;}
	alert (canPass);
	if (canPass2 != true)
	{alert("<?php echo SUBACCOUNT_68;?>");	return false;}
	if (canPass != true)
	{alert("<?php echo SUBACCOUNT_69;?>");	return false;}
	else {return true;}
}
//-->
</script>
</head>
<body>
<?php

$subEmail = myDecrypt(dbProtect($_GET['e2'],200), $password);

(isset($_GET['c']))?$idCampaign = $_GET['c']:$idCampaign="";
$idCampaign	= dbQuotes(dbProtect($idCampaign,25));

(isset($_GET['h']))?$idHtmlNewsletter = $_GET['h']:$idHtmlNewsletter="";
$idHtmlNewsletter	= dbQuotes(dbProtect($idHtmlNewsletter,25));

(isset($_GET['t']))?$idTextNewsletter = $_GET['t']:$idTextNewsletter="";
$idTextNewsletter	= dbQuotes(dbProtect($idTextNewsletter,25));

if (!$idCampaign && !$idHtmlNewsletter && !$idTextNewsletter) {echo SUBACCOUNT_67;die;}

if ($idHtmlNewsletter) {$idNewsletter=$idHtmlNewsletter;}
elseif (!$idHtmlNewsletter && $idTextNewsletter){$idNewsletter=$idTextNewsletter;}
elseif (!$idTextNewsletter && !$idHtmlNewsletter){
    $mySQL="SELECT emailSubject FROM ".$idGroup."_campaigns WHERE idGroup=$idGroup AND idCampaign=$idCampaign";
    $result	= $obj->query($mySQL);
    $row    = $obj->fetch_array($result);
    $urlEmailSubject    = $row['emailSubject'];
    $newsletterName=$urlEmailSubject ;
    $idNewsletter="";
}
if ($idNewsletter) {    //cover the case when there is no campaign id
$mySQLn="SELECT name FROM ".$idGroup."_newsletters WHERE idNewsletter=$idNewsletter";
$result1	    = $obj->query($mySQLn);
$row1           = $obj->fetch_array($result1);
$newsletterName	= $row1['name'];
}
?>
<form action="forwardToMyFriendsExec.php" method="post" onsubmit="return checkIt();">
<table align="center" width="500" cellspacing="0" cellpadding="6" border="0">
<tr>
	<td colspan="2">
		<span class="top"><?php echo $groupName ?></span>
	</td>
</tr>

<tr>
	<td colspan="2">
		<span class="title"><?php echo SUBACCOUNT_37?></span>
	</td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td><?php echo SUBACCOUNT_38?>: </td>
	<td><?php  echo $newsletterName?></td>
</tr>
<tr>
	<td><?php echo SUBACCOUNT_39?>:</td>
	<td><input type="text" name="fromName" id="fromName" value=""  class="inputbox"></td>
</tr>
<tr>
	<td><?php echo SUBACCOUNT_40?>:</td>
	<td><input type="text" name="fromEmail" id="fromEmail" value="<?php echo $subEmail?>" class="inputbox"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan=2><strong><?php echo SUBACCOUNT_41?>:</strong></td>
</tr>

<tr>
	<td class=""><?php echo SUBACCOUNT_42?>:&nbsp;<input type="text" name="toName1" id="toName1" value=""  class="inputbox"></td>
	<td class=""><?php echo SUBACCOUNT_43?>:&nbsp;<input type="text" name="toEmail1" id="toEmail1" value="" class="inputbox"></td>
</tr>
<tr>
	<td class=""><?php echo SUBACCOUNT_42?>:&nbsp;<input type="text" name="toName2" id="toName2" value=""  class="inputbox"></td>
	<td class=""><?php echo SUBACCOUNT_43?>:&nbsp;<input type="text" name="toEmail2" id="toEmail2" value="" class="inputbox"></td>
</tr>
<tr>
	<td class=""><?php echo SUBACCOUNT_42?>:&nbsp;<input type="text" name="toName3" id="toName3" value=""  class="inputbox"></td>
	<td class=""><?php echo SUBACCOUNT_43?>:&nbsp;<input type="text" name="toEmail3" id="toEmail3" value="" class="inputbox"></td>
</tr>
<tr>
	<td class=""><?php echo SUBACCOUNT_42?>:&nbsp;<input type="text" name="toName4" id="toName4" value=""  class="inputbox"></td>
	<td class=""><?php echo SUBACCOUNT_43?>:&nbsp;<input type="text" name="toEmail4" id="toEmail4" value="" class="inputbox"></td>
</tr>
<tr>
	<td class=""><?php echo SUBACCOUNT_42?>:&nbsp;<input type="text" name="toName5" id="toName5" value=""  class="inputbox"></td>
	<td class=""><?php echo SUBACCOUNT_43?>:&nbsp;<input type="text" name="toEmail5" id="toEmail5" value="" class="inputbox"></td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td align=right><?php echo SUBACCOUNT_44?>:</td>
	<td ><input type="text" name="verificationcode" id="verificationcode" value="" class="inputbox">&nbsp;<?php echo $pverificationcode?></td>
</tr>

<tr>
	<td align=right>
		<input type="submit" value="<?php echo SUBACCOUNT_45?>" name="btnSend" class="submit">
	</td>
</tr>
<tr>
	<td colspan="2">
		<div class="privacy"><strong><?php echo SUBACCOUNT_46?>:</strong> <?php echo SUBACCOUNT_47?></div>
	</td>
</tr>


</table>
<input type="hidden" name="idCampaign" value="<?php echo $idCampaign?>">
<input type="hidden" name="idNewsletter" value="<?php echo $idNewsletter?>">
</form>
</body>
</html>