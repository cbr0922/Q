<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>

<table width="900" border=1  cellpadding="2" cellspacing="0">
	<tr>
		<td>ID&nbsp;</td>
		<td><b><?php echo DOSUBSCRIBERSLIST_14;?></b></td>
		<td><b><?php echo DOSUBSCRIBERSLIST_15;?></b></td>
		<td><b><?php echo 'IP';?></b></td>
        <td><b><?php echo DOSUBSCRIBERSLIST_11;?></b></td>
	</tr>
	<?php
 	while ($row = $obj->fetch_array($result)){
	?>
	<tr>
		<td><?php echo $row['idEmail'];?></td>
		<td><?php echo $row['lastName'];?>, <?php echo $row['name'];?></td>
		<td><?php echo $row['email'];?></td>
		<td><?php echo $row['ipOC']?></td>
        <td><?php echo addOffset($row['dateOC'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
	</tr>
	<?php } ?>

</table>