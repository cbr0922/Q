<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
$dateFormatsorter=dateSorter($groupDateTimeFormat);
?>
<table class="sortable" width="900" border=0  cellpadding="2" cellspacing="0" style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid">
<thead>
	<tr>
		<td class="nosort leftCorner"></td>
		<td class="number headerCell" style="BORDER-left: #999999 0px solid;">ID</td>
		<td class="text headerCell" width=200><?php echo DOSUBSCRIBERSLIST_14; ?></td>
		<td class="text headerCell" width=200><?php echo DOSUBSCRIBERSLIST_15; ?></td>
		<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_23; ?></td>
		<td class="nosort headerCell" align=center><?php echo 'IP'; ?></td>
        <td class="<?php echo $dateFormatsorter?> headerCell" align=center><?php echo DOSUBSCRIBERSLIST_11;?></td>
		<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_17; ?></td>
		<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_26; ?></td>
		<td class="nosort rightCorner"></td>
	</tr>
</thead>
<tbody>
	<?php
 	while ($row = $obj->fetch_array($result)){
	?>
	<tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
		<td  class="listingCell"></td>	
		<td  class="listingCell" style="BORDER-left:0px;"><?php echo $row['idEmail'];?></td>
		<td  class="listingCell"><?php echo $row['lastName'];?>, <?php echo $row['name'];?></td>
		<td  class="listingCell"><?php echo $row['email'];?></td>
		<td  class="listingCell" align=center><a href="statsPerSubscriber.php?idEmail=<?php echo $row['idEmail'];?>"><img src="./images/pie.png"  width="21" height="20" border="0" title="<?php echo DOSUBSCRIBERSLIST_18; ?>"></a></td>
		<td class="listingCell"><?php echo $row['ipOC']?></td>
        <td  class="listingCell"><?php echo addOffset($row['dateOC'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
		<td  class="listingCell" align=center><?php echo subscribedIn($row['idEmail'], $idGroup) .' / ' .$plists?></td>
		<td class="listingCell" align=center style="BORDER-right:0px;">
			<a href="editSubscriber.php?idEmail=<?php echo $row['idEmail'];?>"><img src="images/edit.png" width="22" height="22" border="0" title="<?php echo DOSUBSCRIBERSLIST_26; ?>"></a>
		</td>
		<td style="BORDER-right: #c9c9c9 1px solid;BORDER-bottom: #c9c9c9 1px solid;"></td>
	</tr>
	<?php } ?>
</tbody>
</table>