<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<script src="./scripts/doSubscribersList.js" type="text/javascript"></script>
<form name="updatesubsbulk" id="updatesubsbulk">
<input type='hidden' name='redirectUrl' value='<?php echo $redirectUrl;?>'>
<?php if ($idlist){?>
<input type='hidden' name='idList' value='<?php echo $idlist?>'>
<?php } ?>
<table class="sortable" width="930" border=0  cellpadding="2" cellspacing="0" style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid">
<thead>
	<tr>
		<td class="nosort leftCorner"></td>
		<td class="number sortfirstdesc headerCell" style="BORDER-left:0px;">ID</td>
		<td class="text headerCell" width=200><?php echo DOSUBSCRIBERSLIST_14; ?></td>
		<td class="text headerCell" width=200><?php echo DOSUBSCRIBERSLIST_15; ?></td>
		<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_23; ?></td>
		<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_16; ?>&nbsp;<input type="checkbox" name="checkall" value="" onClick="checkAll(checked)"></td>
		<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_17; ?></td>
		<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_26; ?></td>
		<td class="nosort rightCorner"></td>
	</tr>
</thead>	
<tbody>
	<?php
 	while ($row = $obj->fetch_array($result)){	?>
	<tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
		<td  class="listingCell"></td>
		<td  class="listingCell" style="BORDER-left:0px;"><?php echo $row['idEmail'];?></td>
		<td  class="listingCell"><?php echo $row['lastName'];?>, <?php echo $row['name'];?></td>
		<td  class="listingCell"><?php echo $row['email'];?></td>
		<td  class="listingCell" align=center>&nbsp;<a href="statsPerSubscriber.php?idEmail=<?php echo $row['idEmail'];?>"><img src="./images/pie.png"  width="21" height="20" border="0" title="<?php echo DOSUBSCRIBERSLIST_18; ?>"></a></font></td>
		<td  class="listingCell" align=center><input type="checkbox" value="<?php echo $row['idEmail'];?>" id="idEmail" name="idEmail[]"></td>
		<td  class="listingCell" align=center><?php echo subscribedIn($row['idEmail'], $idGroup) .' / ' .$plists?></td>
		<td class="listingCell" colspan=2 align=center style="BORDER-right: #c9c9c9 1px solid;">
			<a href="editSubscriber.php?idEmail=<?php echo $row['idEmail'];?>"><img src="images/edit.png" width="22" height="22" border="0" title="<?php echo DOSUBSCRIBERSLIST_26; ?>"></a>
		</td>
	</tr>
	<?php } ?>
</tbody>
</table>
	<div align="center">
			<input class="submit" onclick="doSubscribersList('delete');return false;" type="button" value="<?php echo DOSUBSCRIBERSLIST_22; ?>" name="delete" id="delete" >
			<input class="submit" onclick="doSubscribersList('confirm');return false;" type="button" value="<?php echo DOSUBSCRIBERSLIST_24; ?>" name="confirm" id="confirm">
			<?php
			if ($idlist==-1 || $idlist==0){?>
			<input class="submit" onclick="doSubscribersList('remove');return false;" type="hidden" value="<?php echo DOSUBSCRIBERSLIST_10; ?>" name="remove" id="remove">
			<?php } else { ?>
				<input class="submit" onclick="doSubscribersList('remove');return false;" type="button" value="<?php echo DOSUBSCRIBERSLIST_10; ?>" name="remove" id="remove">
			<?php } ?>
			<div id="loading" style="display:none;"><img src="./images/waitBig.gif"><?php echo GENERIC_4; ?></div>
	</div>
	<div align="center">
			<span id="noneselected" style="display:none;width:400px;margin-top:10px;" class="errormessage"><img src="./images/warning.png">&nbsp;<?php echo DOSUBSCRIBERSLIST_19; ?></span>
			<input type=hidden id="deleteText" style="display:none;" value="<?php echo DOSUBSCRIBERSLIST_13; ?> <?php echo GENERIC_2; ?>">
			<input type=hidden id="confirmText" style="display:none;" value="<?php echo DOSUBSCRIBERSLIST_12; ?> <?php echo GENERIC_2; ?>">
 			<input type=hidden id="removeText" style="display:none;" value="<?php echo DOSUBSCRIBERSLIST_8; ?> <?php echo GENERIC_2; ?>">
 			<!--input type=hidden id="removeText" style="display:none;" value="<?php //echo DOSUBSCRIBERSLIST_8; ?> <?php //echo GENERIC_2; ?>"-->
	</div></form>