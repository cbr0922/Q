<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName 	 			=	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$today 					= 	myDatenow();
@$typeOf 		= $_GET['typeOf'];
if ($typeOf=="xl") { include('headerXL.php');
} else {
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header('Content-type: text/html; charset='.$groupGlobalCharset.'');
}
$selectedCampaigns 		= $_GET['selectedCampaigns'];
$campaigns 				= explode(",", $selectedCampaigns);
$campaigns 				= array_reverse($campaigns);

$Ticked 				= sizeof($campaigns);
$thatMany 				= $Ticked	//min($Ticked,5);
?>
<br>
 <?php if ($typeOf!="xl") {?>
<table id="comparisonTable" class="sortable" style="width:960px;BORDER-RIGHT: #c9c9c9 0px  solid; BORDER-TOP: #6666CC 0px  solid; BORDER-LEFT: #c9c9c9 0px  solid; BORDER-BOTTOM: #c9c9c9 0px  solid" cellpadding="0" cellspacing="0">
<?php } else {?>
<div><span style="FONT-SIZE:16pt;"><?php echo ALLSTATS_165;?></span></div>
<table id="comparisonTable" border="1">	
<?php } ?>
	<thead>
        <tr>
            <?php if ($typeOf!="xl") {?>
				<td class="nosort leftCorner"></td>
				<td class="nosort headerCell" style="BORDER-left:0px;"><?php echo ALLSTATS_9; ?></td>
			<?php } else {?>
				<td colspan="2"><?php echo ALLSTATS_9; ?></td>
			<?php }?>
			<td class='number headerCell'><?php echo ALLSTATS_64;?></td><!--uvc-->
			<td class='number headerCell'>%</td>
			<td class='number headerCell'><?php echo ALLSTATS_161; ?></td><!--c T v-->
            <td class='number headerCell'><?php echo ALLSTATS_105;?></td><!--uv-->
			<td class='number headerCell'>%</td>
			<td class='number headerCell'><?php echo ALLSTATS_106; ?></td><!--av-->
			<td class='number headerCell'>%</td>
			<td class='number headerCell'><?php echo ALLSTATS_80; ?></td><!--uc-->
			<td class='number headerCell'>%</td>
			<td class='number headerCell'><?php echo ALLSTATS_79; ?></td><!--ac-->
			<td class='number headerCell'>%</td>
			<td class='number headerCell'><?php echo ALLSTATS_62; ?></td><!--opt-->
			<td class='number headerCell'>%</td>
			<td class='number headerCell'><?php echo ALLSTATS_10; ?></td><!--bnc-->
			<td class='number headerCell'>%</td>
			<?php if ($typeOf!="xl") {?>
				<td class='number headerCell'><?php echo ALLSTATS_63; ?></td><!--fw-->
				<td class="nosort rightCorner"></td>
			<?php } else {?>
				<td class='headerCell' colspan="2" align="center"><?php echo ALLSTATS_63;?></td>
			<?php }?>
		</tr>
	</thead>
	<tbody>
		<?php
		for ($i=0; $i<$thatMany; $i++)  {
			//start looping
		 	//echo $campaigns[$i]."<br>";
			$pidCampaign = $campaigns[$i];
		    $mySQL="SELECT idCampaign, campaignName, idGroup, listName, dateCompleted, mailCounter, completed, optedOut, forwarded, bounced FROM ".$idGroup."_campaigns WHERE idCampaign=".$pidCampaign;
		    $result	= $obj->query($mySQL);
			$row = $obj->fetch_array($result);
            $pidCampaign		= $row['idCampaign'];
			$pcampaignName		= $row['campaignName'];
			$pidgroup		    = $row['idGroup'];
			$plistname			= $row['listName'];
			$pdateCompleted		= addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);
			$pcounter		    = $row['mailCounter'];
			$pcompleted			= $row['completed'];
			$poptedOut 			= $row['optedOut'];
			$pforwarded			= $row['forwarded'];
			$pbounced       	= $row['bounced'];
			
			//UNIQUE VIEWS_CLICKS
			$mySQL6="select count(*) as unique_views_clicks FROM (SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign) as bam";
			$punique_views_clicks = $obj->get_rows($mySQL6);
			//UNIQUE CLICKS
			$mySQLc="select count(distinct idEmail) as unique_clicks from ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign";
			$punique_clicks =$obj->get_rows($mySQLc);
			//ALL CLICKS
			$mySQLac="select count(idEmail) as all_clicks from ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign";
			$pall_clicks =$obj->get_rows($mySQLac);

			//UNIQUE VIEWS
			$mySQL4="select count(distinct idEmail) as unique_views from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign";
			$punique_views= $obj->get_rows($mySQL4);
			// ALL VIEWS
			$mySQL5="SELECT count(idEmail) as views FROM ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign";
			$pviews=$obj->get_rows($mySQL5);
			?>
			<tr>
				<td class="listingCell"></td>
				<td class="listingCell" valign="top" style="BORDER-left:0px;">
						<div>
							<span class="statsLegendEmph"><?php echo ALLSTATS_88?>&nbsp;<?php echo  $pidCampaign?></span><?php if ($typeOf!="xl") {?>&nbsp;<img src="./images/magnifier.png" width="11" height="11" alt="" onmouseover="infoBox('pop<?php echo $pidCampaign?>', '<?php echo fixjsstring(ALLSTATS_88).' '.$pidCampaign;?>', '<?php campaignQuickInfo($pidCampaign, $pcampaignName, $plistname, $pdateCompleted);?>','20em', 0);" onmouseout="hide_info_bubble('pop<?php echo $pidCampaign;?>',0);" /><?php } ?>
						</div>
						<div><span class="statsLegend"><?php echo ALLSTATS_81; ?>:</span>&nbsp;<span><?php echo $pcounter; ?></span></div>
						<div style="margin-top:10px"><span style="display:none" id="<?php echo 'pop'.$pidCampaign?>"></span></div>
				</td>

				<td class="listingCell" align="center"><?php echo $punique_views_clicks;?></td>
				<td class="listingCell"><?php if ($pcounter!=0) {echo formatPercent(($punique_views_clicks/$pcounter),0);} else {echo 'n/a';}?></td>
				<td class="listingCell" align="center"><?php  if ($punique_views>0) {echo formatPercent(($punique_clicks/$punique_views),0);} else {echo 'n/a';}?></td>
				<td class="listingCell" align="center"><?php echo $punique_views;?></td>
				<td class="listingCell"><?php if ($pcounter!=0) {echo formatPercent(($punique_views/$pcounter),0);} else {echo 'n/a';}?></td>
				<td class="listingCell" align="center"><?php echo $pviews;?></td>
				<td class="listingCell"><?php if ($pcounter!=0) {echo formatPercent(($pviews/$pcounter),0);} else {echo 'n/a';}?></td>
				<td class="listingCell" align="center"><?php echo $punique_clicks;?></td>
				<td class="listingCell"><?php if ($pcounter!=0) {echo formatPercent(($punique_clicks/$pcounter),0);} else {echo 'n/a';}?></td>
				<td class="listingCell" align="center"><?php echo $pall_clicks;?></td>
				<td class="listingCell"><?php if ($pcounter!=0) {echo formatPercent(($pall_clicks/$pcounter),0);} else {echo 'n/a';}?></td>
				<td class="listingCell" align="center"><?php echo $poptedOut;?></td>
				<td class="listingCell"><?php if ($pcounter!=0) {echo formatPercent(($poptedOut/$pcounter),0);} else {echo 'n/a';}?></td>
				<td class="listingCell" align="center"><?php echo $pbounced;?></td>
				<td class="listingCell"><?php if ($pcounter!=0) {echo formatPercent(($pbounced/$pcounter),0);} else {echo 'n/a';}?></td>
				<?php if ($typeOf!="xl") {?>
				<td class="listingCell" align="center"><?php echo $pforwarded;?></td>
				<td class="listingCell" style="BORDER-left:0px;BORDER-right: #c9c9c9 1px solid;"></td>
				<?php } else {?>
				<td colspan="2" align="center"><?php echo $pforwarded;?></td>
				<?php }?>	
					
			</tr>
		<?php }	// for ?>
		
	</tbody>
</table>
<div style="margin-top:7px"><?php if ($typeOf!="xl") {echo ALLSTATS_140.': ';?><a href="customCompare.php?typeOf=xl&selectedCampaigns=<?php echo  $selectedCampaigns?>"><img src="./images/excel.png" border="0" alt="" width="18" height="18"></a></div><?php } ?>
<?php
$obj->free_result($result);
$obj->closeDb();
?>