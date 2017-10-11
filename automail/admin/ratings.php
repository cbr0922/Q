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
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$dateFormatsorter=dateSorter($groupDateTimeFormat);
include('header.php');
showMessageBox();
?>
<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo ALLNEWSLETTERS_30; ?></span>
			&nbsp;&nbsp;&nbsp;
			<img onclick="infoBox('rating_1', '<?php echo fixJSstring(ALLNEWSLETTERS_30)?>', '<?php echo fixJSstring(ALLNEWSLETTERS_37)?>', '15em','1'); "  title="<?php echo GENERIC_6; ?>" src="./images/i1.gif"><span style="display: none;" id="rating_1"></span>
		</td>
		<td align="right" valign="top">
			<img src="./images/ratings.png" width="65" height="51">
		</td>
	</tr>

</table>
<br>
<?php
//get newsletters
$mySQL="SELECT idNewsletter, name, sent, dateSent,  rate1, rate2, rate3, rate4, rate5 FROM ".$idGroup."_newsletters WHERE idGroup=".$idGroup." AND html=-1 order by dateSent desc";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows){
	echo "<br><img src='./images/warning.png'>" ." ". ALLNEWSLETTERS_17;
}
else {	?>

<table class="sortable"  width="100%" border=0 cellpadding="2" cellspacing="0" style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid">
<thead>
<tr>
	<td class="nosort leftCorner"></td>
	<td class="number headerCell" style="BORDER-left: #999999 0px solid;">ID</td>
	<td class="text headerCell"><?php echo ALLNEWSLETTERS_5; ?></td>
	<td class="<?php echo $dateFormatsorter?> sortfirstdesc headerCell" align=left><?php echo ALLNEWSLETTERS_12; ?></td>
	<td class="nosort headerCell" align=center><?php echo ALLNEWSLETTERS_9; ?></td>
	<td class="nosort headerCell" align=center><?php echo ALLNEWSLETTERS_10; ?></td>
	<td class="number headerCell"><?php echo ALLNEWSLETTERS_31; ?></td>
	<td class="number headerCell" align=center><?php echo ALLNEWSLETTERS_32; ?></td>
	<td class="number headerCell" align=center><?php echo ALLNEWSLETTERS_33; ?></td>
	<td class="number headerCell" align=center><?php echo ALLNEWSLETTERS_34; ?></td>
	<td class="number headerCell" align=center><?php echo ALLNEWSLETTERS_35; ?></td>
	<td class="number headerCell" align="center"><?php echo ALLNEWSLETTERS_36; ?></td>
	<td class="nosort headerCell" align="center"><?php echo ALLNEWSLETTERS_38; ?></td>
	<td class="nosort rightCorner"></td>

</tr>
</thead>
<tbody>
<?php
while ($row = $obj->fetch_array($result)){
$pidNewsletter	= $row['idNewsletter'];
$pname			= $row['name'];
$psent			= $row['sent'];
$pdatesent	 	= addOffset($row['dateSent'], $pTimeOffsetFromServer, $groupDateTimeFormat);
$prate1			= $row['rate1'];
$prate2			= $row['rate2'];
$prate3			= $row['rate3'];
$prate4			= $row['rate4'];
$prate5			= $row['rate5'];
$ptotal=$prate1+$prate2+$prate3+$prate4+$prate5;
?>
<tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
	<td class="listingCell"></td>
	<td class="listingCell" style="BORDER-left:0px;"><?php echo $pidNewsletter?></td>
	<td class="listingCell"><div style="width:220px"><?php echo $pname?></div></td>
	<td class="listingCell"><?php $psent==-1 ? print $pdatesent : print ALLNEWSLETTERS_19; ?></td>
	<td class="listingCell" align="center"><a href="javascript:openWindow('previewHtmlNewsletter.php?idNewsletter=<?php echo $pidNewsletter?>',950,700)"><img src="./images/magnifier.png"  width="22" height="22" alt="" border=0 title="<?php echo ALLNEWSLETTERS_15; ?>"></a></td>
	<td class="listingCell" align="center"><a href="sendNewsletterForm.php?idNewsletterEdit=<?php echo $pidNewsletter; ?>"><img src="./images/edit.png" width="22" height="22" border=0 title="<?php echo ALLNEWSLETTERS_16; ?>"></a></td>
	<td class="listingCell" align=center><b><?php echo $ptotal?></b></td>
	<td class="listingCell" align="center"><b><?php echo $prate1?></b> <span style="BACKGROUND-COLOR: #fbfbad;margin-left:7px"> <?php if ($prate1!=0) {echo formatPercent(($prate1/$ptotal),2);}?></span></td>
	<td class="listingCell" align="center"><b><?php echo $prate2?></b><span style="BACKGROUND-COLOR: #fbfbad;margin-left:7px"> <?php if ($prate2!=0) {echo formatPercent(($prate2/$ptotal),2) ;}?></span></td>
	<td class="listingCell" align="center"><b><?php echo $prate3?></b><span style="BACKGROUND-COLOR: #fbfbad;margin-left:7px"> <?php if ($prate3!=0) {echo formatPercent(($prate3/$ptotal),2); }?></span></td>
	<td class="listingCell" align="center"><b><?php echo $prate4?></b><span style="BACKGROUND-COLOR: #fbfbad;margin-left:7px"> <?php if ($prate4!=0) {echo formatPercent(($prate4/$ptotal),2); }?></span></td>
	<td class="listingCell" align="center"><b><?php echo $prate5?></b><span style="BACKGROUND-COLOR: #fbfbad;margin-left:7px"> <?php if ($prate5!=0) {echo formatPercent(($prate5/$ptotal),2); }?></span></td>
	<td class="listingCell" align=center><a href=# onclick="openConfirmBox('delete.php?rating=<?php echo $pidNewsletter?>','<?php echo fixJSstring(ALLNEWSLETTERS_39)?><br><?php echo fixJSstring(GENERIC_2)?>');return false;"><img border=0 src="./images/cancel.gif"></a></td>
	<td class="listingCell" style="BORDER-left:0px; BORDER-right: #c9c9c9 1px solid;"></td>
</tr>
<?php } //looping ?>
</tbody>
</table>
<br><br>
<?php
} //we have rows
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>