<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/languages.php');
$mailData["groupName"]          =	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);
?>

<html>
<head>
<title><?php echo $mailData["groupName"].' - '.SUBACCOUNT_2;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset?>">
<style>
	body {FONT-FAMILY: Arial, Verdana, Helvetica, sans-serif; FONT-SIZE: 12px;}
</style>
</head>
<body>
<!-- if you want to resize the window use the following <body>.
Handy if you used a direct unsubscribe link to come here.
<body onLoad="resizeTo(700,500);"> -->
<?php
(isset($_GET['c']))?$idCampaign=dbQuotes(dbProtect($_GET['c'],10)):$idCampaign="";
(isset($_GET['e2']))?$sub["email2"] = dbQuotes(dbProtect($_GET['e2'],500)):$sub["email2"]="";
(isset($_GET['r']))?$r = dbQuotes(dbProtect($_GET['r'],500)):$r="";
(isset($_GET['h']))?$idHtmlNewsletter = dbQuotes(dbProtect($_GET['h'],500)):$idHtmlNewsletter="";
(isset($_GET['t']))?$idTextNewsletter = dbQuotes(dbProtect($_GET['t'],500)):$idTextNewsletter="";

$idNewsletter="";
if ($idTextNewsletter) {
	$idNewsletter = $idTextNewsletter;
}
if ($idHtmlNewsletter) {
	$idNewsletter = $idHtmlNewsletter;
}
switch ($r) {
  case 1: $field = "rate1";
  break;
  case 2: $field = "rate2";
  break;
  case 3: $field = "rate3";
  break;
  case 4: $field = "rate4";
  break;
  case 5: $field = "rate5";
  break;
  default: $field ="" ;
}
//do a check to AVOID SPAMMING....
IF ($idNewsletter) {
    if ($field) {
	    $mySQL="UPDATE ".$idGroup."_newsletters SET ".$field."=".$field."+1 WHERE idNewsletter=".$idNewsletter;
	    $obj->query($mySQL);
    }
}

$obj->closeDb();
?>
<p align=center>
<table align="center" width="500" cellspacing="0" cellpadding="6" border="0">
<tr>
	<td align=center>
		<span style=" FONT-FAMILY: Arial, helvetica; FONT-SIZE: 20px; FONT-WEIGHT: bold; color:#565656;"><?php echo $mailData["groupName"] ?></span>
	</td>
</tr>
<tr>
	<td align=center>
		<strong><br><?php echo SUBACCOUNT_34?></strong>
	</td>
</tr>
</table>
</p>
</body>
</html>