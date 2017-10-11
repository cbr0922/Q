<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 					= new db_class();
$groupName 				=	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$dateFormatsorter=dateSorter($groupDateTimeFormat);
include('header.php');
showMessageBox();
(isset($_GET['idList']))?$idlist = $_GET['idList']:$idlist=0;
if ($idlist) {
	$AND = " AND idList=$idlist";
}
else {$AND="";}

?>

<table border="0" width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td>
			<span class="title"><?php echo ADMIN_HEADER_56; ?>:</span>
			<span class=menu>
			<?php 	if ($idlist) {
					echo getlistname($idlist, $idGroup).'</span>&nbsp;&nbsp;&nbsp;<a href="listTraffic.php">'.LISTTRAFFIC_9.'</a>';
					}
					else { echo LISTTRAFFIC_11.'</span>';} ?>
        			<br><br><?php echo LISTTRAFFIC_12; ?>:&nbsp;<a href="listTrafficXL.php<?php if ($idlist) {echo'?idList='.$idlist;}?>"><img border="0" src="./images/excel.png" alt="" width="18" height="18"></a>
				    &nbsp;&nbsp;&nbsp;<?php echo LISTTRAFFIC_13; ?>:&nbsp;<a href=# onclick="openConfirmBox('delete.php?action=ltf&ltfList=<?php echo $idlist;?>','<?php echo fixJSstring(CONFIRM_12)."<br>".fixJSstring(GENERIC_2);?>');return false;"><img border=0 src="./images/delete.png"></a>
		</td>
		<td align=right>
			<Img src="./images/listtraffic.png" width="50" height="63">
		</td>
	</tr>
</table>
<br><br>
<?php
$mySQL="SELECT idList, listName, dateCreated, list_ins, list_outs FROM ".$idGroup."_lists WHERE idGroup=$idGroup $AND order by idList desc";
$result	= $obj->query($mySQL);
//echo $mySQL;
$rows 	= $obj->num_rows($result);

if (!$rows) {
	echo '<br><img src="./images/warning.png">' .LISTTRAFFIC_2;
}
else { ?>
	<div style="float:left;">
	<table class="sortable"  style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid" width="800" cellpadding="0" cellspacing="0">		
	<thead>
		<tr>
			<td class="nosort leftCorner"></td>
			<td class="number headerCell" style="BORDER-left:0px;">ID</td>
			<td class="text headerCell"><?php echo LISTS_4; ?></td>
			<td class="<?php echo $dateFormatsorter?> headerCell"><?php echo LISTTRAFFIC_4; ?></td>
			<td class="number headerCell"><?php echo LISTTRAFFIC_5; ?></td>
			<td class="number headerCell"><?php echo LISTTRAFFIC_6; ?></td>
			<td class="nosort rightCorner"></td>
		</tr>
		</thead>
		<tbody>
			<?php
			while ($row = $obj->fetch_array($result)){	?>
		<tr valign=top onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
			<td class="listingCell"></td>
			<td  class="listingCell"  style="BORDER-left:0px;"><?php echo $row['idList']?></td>
			<td class="listingCell"><?php echo $row['listName']?></td>
			<td class="listingCell"><?php echo addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
			<td class="listingCell" align=center><?php echo $row['list_ins']?></td>
			<td class="listingCell" align="center"><?php echo $row['list_outs']?></td>
			<td class="listingCell" style="BORDER-left:0px; BORDER-right: #c9c9c9 1px solid;"></td>
		</tr>
				<?php } ?>
		</tbody>
	</table>
	</div>
	<div style="float:left;">&nbsp;&nbsp;<img onmouseover="infoBox('LISTTRAFFIC_2', '<?php echo fixJSstring(ADMIN_HEADER_56)?>', '<?php echo fixJSstring(LISTTRAFFIC_7).'<br>'.fixJSstring(LISTTRAFFIC_8)?>', '20em', '0');" onmouseout="hide_info_bubble('LISTTRAFFIC_2','0')" src="./images/i1.gif"><span style="display: none;" id="LISTTRAFFIC_2"></span></div>
	<div style="clear:both;"></div>
<?php
}

include('footer.php');

?>