<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php $groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);?>
<html>
<head>
<title>Smartshop網路開店</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.5; user-scalable=1;"/-->
<script type="text/javascript" language="javascript">var myCustomEncoding="<?php echo $groupGlobalCharset;?>";</script>
<link href="./includes/site.css" rel="stylesheet" type="text/css">
<script src="./scripts/jQuery_2.1.0.js" type="text/javascript"></script>
<script src="./scripts/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="./scripts/jqtablekit.js" type="text/javascript"></script>
<script src="./scripts/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="./scripts/scripts.js" type="text/javascript"></script>
</head>
<?php
isset($_GET['idNewsletterEdit'])?$idnewsletterEdit = $_GET['idNewsletterEdit']:$idnewsletterEdit="";
if ($idnewsletterEdit) {
//1 minute: 60*1000
?><body id="body" onload="setInterval('autosave()', 180000)"> 
<?php } else { ?><body id="body"><?php } //echo 'idAdmin: '.$sesIDAdmin.',&nbsp;&nbsp;idGroup: '.$idGroup;?>
<div id="confirmBox" style="display: none; top: 0px; left: 0px;">
	<!-- theparams: Used to store IDs of subscribers when confirm or delete in bulk -->
	<input type="hidden" id="theurl" value=""><input type="hidden" id="theparams" value=""><input type="hidden" id="isAjax" value="">
	<div class="confirmBoxTop">
		<img alt="" src="./images/warning.png" border="0" />&nbsp;&nbsp;<span><?php  echo GENERIC_10; ?></span>
	</div>
  	<div class="confirmBoxBottom">
		<span id="confirmBoxtext" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial"></span>
		<div style="padding-top:10px"><input type="submit" value="<?php echo GENERIC_11; ?>" class="submitSmall" onclick="closeConfirmBox('yes')"/>
		&nbsp;&nbsp;&nbsp;
		<input type="submit" value="<?php echo GENERIC_12; ?>" class="submitSmall" onclick="closeConfirmBox('no')" /></div>
	</div>
</div>
<div id="alertBox" style="display: none; z-index: 999;top: 0px; left: 0px;">
	<input type="hidden" id="alertBoxUrl" value="">
	<div class="confirmBoxTop"><img alt="" src="./images/warning.png" border="0" />&nbsp;&nbsp;<span><?php  echo GENERIC_10; ?></span></div>
  	<div class="confirmBoxBottom">
		<span id="alertBoxtext" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial"></span>
		<div style="padding-top:10px"><br><input type="submit" value="<?php echo GENERIC_13; ?>" class="submitSmall" onclick="closeAlertBox('yes')"/></div>
	</div>
</div>

<div id="pageHeader" class="pageHeader">
	<div style="margin-left: auto;margin-right:auto;width:1000px; ">
	<div>	
		<!--img style="position:absolute;top:0;left:0;margin:0px 0px 0px 0px;" alt="<?php echo $obj->getSetting("groupName", $idGroup); ?>" src="../assets/<?php echo  $obj->getSetting("groupLogo", $idGroup);?>"-->
		<span class="company"  style="position:absolute;top:0;left:20px;margin-top:5px;margin-right:20px"><?php echo $obj->getSetting("groupName", $idGroup); ?></span>
	</div>
	<div>&nbsp;</div>

	<div style="float:left;margin-top:30px;margin-left:0px;"><?php include('./includes/menu.php');?></div>
	<div style="float:right;margin-top:40px;margin-right:0px;">
		<a href="help.php" onclick="window.open(this.href,'window','width=600,height=400,resizable=yes,scrollbars=yes');return false"><img src="./images/helpHeader.png" border="0" width="20" height="19" alt="<?php echo ADMIN_HEADER_13; ?>" title="<?php echo ADMIN_HEADER_13; ?>"></a>
		&nbsp;<a href="logOff.php"><img style="margin-left:5px;" src="./images/logout.png" border="0" width="20" height="19" alt="<?php echo ADMIN_HEADER_8; ?>" title="<?php echo ADMIN_HEADER_8; ?>"></a>
	</div>
	<div style="clear:both;"></div>
</div>
</div><!-- "header"-->
<div id="pageContainer" class="pageContainer">
	<div id="pageWrapper" class="pageWrapper">
		<div id="pageCenter" class="pageCenter">