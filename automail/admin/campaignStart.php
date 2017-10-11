<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/languages.php');

(isset($_GET['idCampaign']))?$idCampaign = $_GET['idCampaign']:$idCampaign="";
//header("Location: campaignSend.php?idCampaign=$idCampaign");

$pinterval 		= 20;
$predirecturl 	= "campaignSend.php?idCampaign=$idCampaign";
?>
<html>
<head>
<title><?php echo CAMPAIGNSEND_8?></title>
<link href="./includes/site.css" rel=stylesheet type=text/css>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $obj->getSetting("groupGlobalCharset", $idGroup); ?>">
<meta NAME="KEYWORDS" CONTENT="newsletter, newsletter software, mailing list, mailing list manager, mailing lists, newsletter manager, bulk mailing, bulk mail, PHP newsletter, email software, PHP, mailing list, html emails, text emails">
<meta NAME="DESCRIPTION" CONTENT="nuevoMailer by Designerfreesolutions.com. Design and send unlimited personalized html and text newsletters to your subscribers. Manage mailing lists.  Newsletter personalization & merging. Supports all email components. Send statistics and reporting clicks and views.">
<script language='javascript' type='text/javascript'>opener.$("#mailButton<?php echo $idCampaign?>").prop("disabled",true);</script>
<script language="javascript">
    function nextpage(){
      window.location='<?php echo $predirecturl?>';
    }
</script>
<style>
html {background: #ffffff;min-height:100%;height: 100%;}body {background: #ffffff;padding:10px;}
</style>
</head>
<body onload="setTimeout('nextpage()', <?php echo $pinterval?>)">
<p align=center><span class="title"><?php echo CAMPAIGNSEND_8?></span>
<br /><br /><img src="./images/waitBig.gif" width="32" height="32"></p>
<?php
$obj->closeDb();
?>
</body></html>