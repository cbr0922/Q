<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer = $obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$dateFormatsorter=dateSorter($groupDateTimeFormat);
include('header.php');
showMessageBox();
?>
<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo LISTADMINS_1; ?></span>
			<br><a href="modifyAdmin.php"><?php echo LISTADMINS_2; ?></a>
		</td>
		<td align="right" valign="top">
			<img src="./images/admins.png" width="55" height="62">
		</td>
	</tr>

</table>
<br><br>
<?php
$mySQL="SELECT * FROM ".$idGroup."_admins WHERE idGroup=$idGroup order by idAdmin desc";
//echo $mySQL .'<br>';
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows){
	echo "<br><img src='./images/warning.png'>" ."No admins settings found";
}
else {
?>
<br>

<table class="sortable" cellpadding="2" cellspacing="0" width="800" style="BORDER-RIGHT: #999999 0px  solid; BORDER-TOP: #6666CC 0px  solid; BORDER-LEFT: #999999 0px  solid; BORDER-BOTTOM: #999999 0px  solid">
<thead>
<tr>
	<td class="nosort leftCorner"></td>
	<td class="number headerCell" style="BORDER-left: #999999 0px solid;">ID</td>
	<td class="text headerCell"><?php echo LISTADMINS_4; ?></td>
	<td class="text headerCell"><?php echo LISTADMINS_6; ?></td>
	<td class="text headerCell"><?php echo LISTADMINS_8; ?></td>
	<td class="text headerCell"><?php echo LISTADMINS_25; ?></td>
	<td class="<?php echo $dateFormatsorter?> headerCell"><?php echo LISTADMINS_7; ?></td>
	<td class="nosort headerCell"><?php echo LISTADMINS_5; ?></td>
	<td class="nosort headerCell" align=center><?php echo LISTADMINS_21; ?></td>
	<td class="nosort rightCorner"></td>
</tr>
</thead>
<tbody>
<?php
while ($row = $obj->fetch_array($result)){
?>
	<tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
	   	<td class="listingCell"></td>	
		<td class="listingCell" style="BORDER-left:0px;"><?php echo $row['idAdmin']; ?></td>
		<td class="listingCell"><?php echo $row['adminFullName']; ?></td>
		<td class="listingCell"><?php echo $row['adminEmail']; ?></td>
		<td class="listingCell"><?php if ($row['emailAlert']==-1) {echo LISTADMINS_9;} else {echo LISTADMINS_10;}?></td>
		<td class="listingCell"><?php if ($row['active']==-1) {echo LISTADMINS_9;} else {echo LISTADMINS_10;}?></td>
		<td class="listingCell"><?php echo addOffset($row['adminLastLogin'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
		<td class="listingCell"><?php if ($row['idAdmin']==1 && $sesIDAdmin!=1 ) {?><?php } else {?><a href='modifyAdmin.php?idAdminM=<?php echo $row['idAdmin']; ?>'><?php echo LISTADMINS_5; ?></a><?php }?></td>
        <td class="listingCell" align=center style="BORDER-right:0px;">&nbsp;<?php if ($row['idAdmin']!=1) {?><A HREF=# onclick="openConfirmBox('delete.php?idAdminD=<?php echo $row['idAdmin']?>&action=delete','<?php echo fixJSstring(LISTADMINS_22).'<br>'.fixJSstring(GENERIC_2)?>');return false;"><img src="./images/delete.png" width="18" height="18" border=0></a><?php }?></td>
		<td class="listingCell" style="BORDER-left:0px; BORDER-right: #c9c9c9 1px solid;"></td>


	</tr>
<?php
}
?>
</tbody>
</table>
<?php
}
include('footer.php');
?>