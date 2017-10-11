<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/languages.php');
$groupName 	 		= $obj->getSetting("groupName", $idGroup);
$groupSite 			= $obj->getSetting("groupSite", $idGroup);
$groupContactEmail	= $obj->getSetting("groupContactEmail", $idGroup);
include('header.php');
?>
<table border="0" cellpadding=2 cellspacing=0 width="960px">
	<tr>
		<td>
			<span class="title"><?php echo SENDCONFIRMREQUEST_1; ?></span>
		</td>
	</tr>
	<tr>
		<td valign="top">
            <ol>
            <li><span class=menuSmall><?php echo SENDCONFIRMREQUEST_2;?></span>&nbsp;<img onmouseover="infoBox('conf_1', '<?php //echo fixJSstring(GENERIC_17);?>', '<?php echo "<img src=\'./images/confLink.png\'>";?>', '40em','0')" onmouseout="hide_info_bubble('conf_1','0')" src="./images/i1.gif"><span style="display: none;" id="conf_1"></span></li>
            <li><span class=menuSmall><?php echo SENDCONFIRMREQUEST_3;?></span>&nbsp;<img onmouseover="infoBox('conf_2', '<?php //echo fixJSstring(GENERIC_17);?>', '<?php echo "<img src=\'./images/confLink2.png\'>";?>', '30em','0')" onmouseout="hide_info_bubble('conf_2','0')" src="./images/i1.gif"><span style="display: none;" id="conf_2"></span></li>
            <li><span class=menuSmall><?php echo SENDCONFIRMREQUEST_4; ?></span></li>
            </ol>
		</td>
	</tr>

</table>

<?php
$obj->closeDb();
include("footer.php");
?>

