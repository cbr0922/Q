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
$plists =  $obj->tableCount_condition($idGroup."_lists", " WHERE idGroup=".$idGroup."");
include('header.php');
showMessageBox();
(isset($_GET['idEmail']))?$pidemail = $_GET['idEmail']:$pidemail=0;
$dateFormatsorter=dateSorter($groupDateTimeFormat);
?>
<table border="0" width="960px">
	<tr>
		<td valign="top">
			<span class="title"><?php echo ALLSTATS_111; ?></span>
		</td>
		<td align=right>
			<img src="./images/clickstatsusers1.png">
		</td>
	</tr>
</table>

<?php
//get subscriber name and email
$mySQL="SELECT idEmail, name, lastName, email, timesMailed from ".$idGroup."_subscribers WHERE idEmail=$pidemail";
$result = $obj->query($mySQL);
$row = $obj->fetch_array($result);
$pname	        = $row['name'].'&nbsp;'.$row['lastName'];
$pemail	        = $row['email'];
$ptimesMailed 	= $row['timesMailed'];
?>
<b><?php echo ALLSTATS_112; ?></b><?php echo $pname?>, <?php echo $pemail?> <a href='editSubscriber.php?idEmail=<?php echo $pidemail?>'><?php echo ALLSTATS_114; ?></a>
<br><br>
<b><?php echo EDITSUBSCRIBER_18; ?></b><?php echo subscriberuniqueopenrate($pidemail, $idGroup, $ptimesMailed)?>
<br><br>

<?php //get newsletters from stats
$mySQL1="SELECT distinct linkUrl, idCampaign, dateClicked FROM ".$idGroup."_clickStats WHERE idEmail=$pidemail order by idCampaign desc, dateClicked desc";
$result1 = $obj->query($mySQL1);
$rows1 	= $obj->num_rows($result1);
    if (!$rows1){
        echo '<br><img src="./images/warning.png"> '.ALLSTATS_116;
	} else {
?>
<br>
	<table class="sortable" width="100%"  cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td class="nosort leftCorner"></td>
				<td class="text headerCell" width="530" style="BORDER-left:0px;"><?php echo ALLSTATS_78; ?></td>
				<td class="number headerCell" width="100"><?php echo ALLSTATS_88; ?></td>
				<td class="<?php echo $dateFormatsorter?> headerCell" width="200"><?php echo ALLSTATS_98; ?></td>
				<td class="nosort rightCorner"></td>
			</tr>
		</thead>
		<tbody>
			<?php
			while ($row1 = $obj->fetch_array($result1)){
			  $idCampaign = $row1['idCampaign'];
			?>
			<tr valign=top>
				<td class="listingCell"></td>
				<td  class="listingCell" style="BORDER-left:0px;"><div style="width:500px;word-wrap:break-word;"><a style="text-decoration:none;" href="<?php echo $row1['linkUrl']?>" target="blank"><?php echo $row1['linkUrl']?></a></div></td>
				<td class="listingCell"><span style="padding-left:15px"><?php echo $idCampaign?></span></td>
				<td class="listingCell"><?php echo addOffset($row1['dateClicked'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
			   	<td class="listingCell" style="BORDER-left:0px; BORDER-right: #c9c9c9 1px solid;"></td>
			</tr>
 			<?php } ?>
		</tbody>
	</table>
<?php
} //we have $rows
$obj->free_result($result);
$obj->free_result($result1);
$obj->closeDb();
include('footer.php');
?>