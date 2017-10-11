<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj 		= new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
include('header.php');
$message = $_REQUEST['message'];
?>
<br><br>
<table  cellpadding=10 cellspacing=0 align="center" valign="top" style="BORDER-RIGHT: #4E4F6A 4px solid; BORDER-TOP: #4E4F6A 2px solid; BORDER-LEFT: #4E4F6A 2px solid; BORDER-BOTTOM: #4E4F6A 4px solid">
   	<tr valign="top">
        <td valign="top" align="center">
			<div style="padding:10px"><span class="message"><?php echo $message; ?></span></div>
		</td>
	</tr>
</table>
<?php
include('footer.php');
?>