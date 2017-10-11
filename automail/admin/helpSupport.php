<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
include('header.php');
showMessageBox();
?>
<table border=0 cellspacing=0 cellpadding=4 width="650px">
	<tr>
		<td valign="top">
			<span class="title"><?php echo HELPSUPPORT_1; ?></span>
			<br><br><b><?php echo HELPSUPPORT_5; ?></b>
			<br><?php echo HELPSUPPORT_6; ?>
			<br><br><b><?php echo HELPSUPPORT_3; ?></b>
			<br><?php echo HELPSUPPORT_4; ?>
			<br><br><b><?php echo HELPSUPPORT_7; ?></b>
			<br><?php echo HELPSUPPORT_8; ?>
			<br><br><b><?php echo HELPSUPPORT_9; ?></b>
			<br><?php echo HELPSUPPORT_10; ?>
		</td>
		<td align="right" valign="top"><img src="./images/help.gif" width="60" height="52" alt=""></td>
	</tr>
</table>

<?php
$obj->closeDb();
include('footer.php');
?>
