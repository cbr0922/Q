<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj=new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName 	 			=	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$today 					= myDatenow();
include('headerXL.php');

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
					echo getlistname($idlist, $idGroup);
					}
					else { echo LISTTRAFFIC_11.'</span>';} ?>
        </td>
		<td align=right>

		</td>
	</tr>
</table>
<br><br>
<?php
$mySQL="SELECT idList, listName, dateCreated, list_ins, list_outs FROM ".$idGroup."_lists WHERE idGroup=$idGroup $AND order by idList desc";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);

	if (!$rows) {
		echo '';
	}
	else {
?>
<table border="0" width="850" cellpadding=2 cellspacing=0>
	<tr>
		<td valign="top">

			<table  style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid" width="650" cellpadding="0" cellspacing="0">
				<tr>

					<td class="headerCell" style="BORDER-left: #999999 0px solid;">ID&nbsp;</td>
					<td class="headerCell"><?php echo LISTS_4; ?></td>
					<td class="headerCell"><?php echo LISTTRAFFIC_4; ?></td>
					<td class="headerCell"><?php echo LISTTRAFFIC_5; ?></td>
					<td class="headerCell"><?php echo LISTTRAFFIC_6; ?></td>
                </tr>

					<?php
					while ($row = $obj->fetch_array($result)){	?>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;<?php echo $row['idList']?></td>
					<td><?php echo $row['listName']?></td>
					<td><?php echo addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
					<td align=center><?php echo $row['list_ins']?></td>
					<td align=center><?php echo $row['list_outs']?></td>
				</tr>
	 				<?php } ?>
			</table>
		</td>
		<td valign="top" width=200>
		</td>
	</tr>

</table>

<?php } ?>