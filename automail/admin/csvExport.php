<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj = new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
include('header.php');
?>
<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo CSVSUBSCRIBERSEXPORT_1; ?></span>
		</td>
		<td align=right>
			<img src="./images/exportcsv.png"  alt="" width="60" height="47">
		</td>
	</tr>
</table>

<form name="csvExport" action="csvExportExec.php" method="post">
<table>
	<tr>
		<td colspan=2>
			<?php echo CSVSUBSCRIBERSEXPORT_6; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo SUBSCRIBERSIMPORT_31;?>:</td>
		<td>
			<select name="delimiter" class=select>
 				<option value="comma">, Comma</option>
			    <option value="semicolon">; Semicolon</option>
				<option value="tab"> Tab</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo CSVSUBSCRIBERSEXPORT_2?>
		</td>
		<td>

			<?php
			$mySQL3="SELECT * FROM ".$idGroup."_lists where idGroup=$idGroup order by idList desc";
			$result = $obj->query($mySQL3);	?>
			  <SELECT name="idList" class="select" id="idListOption1">
			  <OPTION value="-1"><?php echo CSVSUBSCRIBERSEXPORT_4; ?></option>
			  <?php
				while ($row = $obj->fetch_array($result)){
			  ?>
			  <option value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php echo $row['listName'];?>
			  <?php }  ?>
			  </OPTION>
			  </SELECT>&nbsp;&nbsp;&nbsp;
			<input type="submit" class="submit" value="<?php echo CSVSUBSCRIBERSEXPORT_5; ?>">

		</td>
	</tr>
</table>
</form>
<?php
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>