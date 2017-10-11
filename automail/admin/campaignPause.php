<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj 		= new db_class();
include('./includes/auxFunctions.php');
$groupName 	            =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupBatchInterval     =	$obj->getSetting("groupBatchInterval", $idGroup);
$groupGlobalCharset     =	$obj->getSetting("groupGlobalCharset", $idGroup);
if (@$pdemomode) {
    forDemo2(DEMOMODE_1);
}
(isset($_GET['idCampaign']))?$idCampaign = $_GET['idCampaign']:$idCampaign="";
$psentSoFar = campaignMailCounter($idCampaign, $idGroup);

//delay time - interval in seconds
//$pinterval	= ceil($groupBatchInterval)*60;
$pinterval	= ceil($groupBatchInterval)*1000*60;
$predirecturl 		= "campaignStart.php?idCampaign=$idCampaign";
?>
<script type="text/javascript" language="javascript">var myCustomEncoding="<?php echo $groupGlobalCharset;?>"</script>
<html>
<head>
<title>Newsletter and mailing list management software by DesignerFreeSolutions</title>
<link href="./includes/site.css" rel=stylesheet type=text/css>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset;?>">
<meta NAME="KEYWORDS" CONTENT="newsletter, newsletter software, mailing list, mailing list manager, mailing lists, newsletter manager, bulk mailing, bulk mail, PHP newsletter, email software, PHP, mailing list, html emails, text emails">
<meta NAME="DESCRIPTION" CONTENT="PHP Newsletter software by designerfreesolutions.com. Send unlimited personalized html and text newsletters to your subscribers.">
<script src="./scripts/jQuery_2.1.0.js" type="text/javascript"></script>
<script language="javascript">
    function nextpage(){
      window.location='<?php echo $predirecturl;?>';
    }
</script>
<style>
html, body {
	background: #ffffff;
	margin:10px;
}
</style>
</head>
<body color="#FFFFFF"  onload="setTimeout('nextpage()', <?php echo $pinterval?>)">
<p align = "center">
    <span class="title"><?php echo CAMPAIGNPAUSE_1; ?></span>
    <br><br><?php echo CAMPAIGNPAUSE_2; ?>
    <br><br>
    <br><br>
    <?php echo CAMPAIGNPAUSE_5; ?><?php echo $psentSoFar?>
    <br><br>
    <?php echo CAMPAIGNPAUSE_3; ?><?php echo $idCampaign?>
    <br><br>
    <input class="submit" type ="button" name = "button" value = "<?php echo CAMPAIGNPAUSE_4; ?>" onclick = "window.close();opener.location='campaigns.php';">
</p>
</body>
</html>