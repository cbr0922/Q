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
//$thatMany 				= min($Ticked,5);
function campaignsOR($selectedCampaigns, $idGroup) {
    $ORcampaigns="";
    if (!empty($selectedCampaigns)) {
        $campsArray = explode(",", $selectedCampaigns);
        $campaignsCount = sizeof($campsArray);
        if ($campaignsCount!=0) {
            for ($z=0; $z<$campaignsCount; $z++)  {
                $ORcampaigns.="idCampaign=".$campsArray[$z]. ' OR ';
            }
            $ORcampaigns = ' ('.rtrim($ORcampaigns, " OR ").')';
        }
    }
    return $ORcampaigns;
}
//die (campaignsOR($selectedCampaigns, $idGroup));
?>
<br>
 <?php if ($typeOf!="xl") {?>
<table width="98%" style="BORDER-RIGHT: #999999 0px  solid; BORDER-TOP: #6666CC 0px  solid; BORDER-LEFT: #999999 0px  solid; BORDER-BOTTOM: #999999 0px  solid" cellpadding="0" cellspacing="0">
<?php } else {?>
<div><span style="FONT-SIZE:16pt;"><?php echo ALLSTATS_163;?></span></div>
<table id="comparisonTable" border="1">	
<?php } ?>
	<tbody>
        <tr>
            <?php if ($typeOf!="xl") {?>
			<td class="leftCorner" width="10"></td>
			<td class="headerCell" style="BORDER-left: #999999 0px solid;text-align:left;"><?php echo ALLSTATS_9; ?></td>
			<?php } else {?>
			<td colspan="2" class="headerCell" style="BORDER-left: #999999 0px solid;text-align:left;"><span style="color:#fff;"><?php echo ALLSTATS_9;?></span></td>
			<?php }?>
			<td class='headerCell' colspan="2"><span style="color:#fff;"><?php echo ALLSTATS_64; ?></span></td>
			<td class='headerCell' ><span style="color:#fff;"><?php echo ALLSTATS_161; ?></span></td>
            <td class='headerCell' colspan="2"><span style="color:#fff;"><?php echo ALLSTATS_105; ?></span></td>
			<td class='headerCell' colspan="2"><span style="color:#fff;"><?php echo ALLSTATS_106; ?></span></td>
			<td class='headerCell' colspan="2"><span style="color:#fff;"><?php echo ALLSTATS_80; ?></span></td>
			<td class='headerCell' colspan="2"><span style="color:#fff;"><?php echo ALLSTATS_79; ?></span></td>
			<td class='headerCell' colspan="2"><span style="color:#fff;"><?php echo ALLSTATS_62; ?></span></td>
			<td class='headerCell' colspan="2"><span style="color:#fff;"><?php echo ALLSTATS_10; ?></span></td>
			<?php if ($typeOf!="xl") {?>
			<td class='headerCell'><span style="color:#fff;"><?php echo ALLSTATS_63; ?></span></td>
			<td class="rightCorner" width="10"></td>
			<?php } else {?>
			<td class='headerCell' colspan="2" align="center"><span style="color:#fff;"><?php echo ALLSTATS_63; ?></span></td>
			<?php }?>
		</tr>
		<?php
		$pTotalCounter=0;
		$pTotalOptedOut=0;
		$pTotalForwarded=0;
		$pTotalBounced=0;
		for ($i=0; $i<$Ticked; $i++)  {
			//start looping
		 	//echo $campaigns[$i]."<br>";
			$pidCampaign = $campaigns[$i];
		    $mySQL="SELECT idCampaign, campaignName, idGroup, mailCounter, completed, optedOut, forwarded, bounced FROM ".$idGroup."_campaigns WHERE idCampaign=".$pidCampaign;
		    $result	= $obj->query($mySQL);
			$row = $obj->fetch_array($result);
            $pidCampaign		= $row['idCampaign'];
			$pcampaignName		= $row['campaignName'];
			$pidgroup		    = $row['idGroup'];
			$pcounter		    = $row['mailCounter'];
			$pcompleted			= $row['completed'];
			$poptedOut 			= $row['optedOut'];
			$pforwarded			= $row['forwarded'];
			$pbounced       	= $row['bounced'];
			$pTotalCounter		= $pTotalCounter+intval($pcounter);
			$pTotalOptedOut		= $pTotalOptedOut+intval($poptedOut);
			$pTotalForwarded	= $pTotalForwarded+intval($pforwarded);	
			$pTotalBounced		= $pTotalBounced+intval($pbounced);
		}	// for
			//UNIQUE VIEWS_CLICKS
			//$mySQL6="select count(*) as unique_views_clicks FROM (SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign) as bam";
			$mySQL6="select count(*) as unique_views_clicks FROM (
				(SELECT idEmail FROM ".$idGroup."_clickStats WHERE ".campaignsOR($selectedCampaigns, $idGroup).") 
					UNION 
				(SELECT idEmail from ".$idGroup."_viewStats WHERE ".campaignsOR($selectedCampaigns, $idGroup).")
					) as bam";
			//echo($mySQL6."<br>");
			$punique_views_clicks = $obj->get_rows($mySQL6);
			//echo ($punique_views_clicks);
			//UNIQUE CLICKS
			$mySQLc="select count(distinct idEmail) as unique_clicks from ".$idGroup."_clickStats WHERE ".campaignsOR($selectedCampaigns, $idGroup)."";
			$punique_clicks =$obj->get_rows($mySQLc);
			//ALL CLICKS
			$mySQLac="select count(idEmail) as all_clicks from ".$idGroup."_clickStats WHERE ".campaignsOR($selectedCampaigns, $idGroup)."";
			$pall_clicks =$obj->get_rows($mySQLac);

			//UNIQUE VIEWS
			$mySQL4="select count(distinct idEmail) as unique_views from ".$idGroup."_viewStats WHERE".campaignsOR($selectedCampaigns, $idGroup)."";
			$punique_views= $obj->get_rows($mySQL4);
			// ALL VIEWS
			$mySQL5="SELECT count(idEmail) as views FROM ".$idGroup."_viewStats WHERE ".campaignsOR($selectedCampaigns, $idGroup)."";
			$pviews=$obj->get_rows($mySQL5);
			?>
			<tr>
				<td colspan="2" class="listingCellStats" valign="top" style="BORDER-right: #999999 0px solid;">
					<div  align="left"><span class="statsLegendEmph"><?php echo ADMIN_HEADER_47?>:<br><?php echo  $selectedCampaigns?></span></div>
				   <div><span class="statsLegend"><?php echo ALLSTATS_81; ?>:</span>&nbsp;<span><?php echo $pTotalCounter; ?></span></div>
				</td>
				<td class="listingCellStats" align="center"><span><?php echo $punique_views_clicks;?></span></td>
				<td class="listingCellStats" align="center"><span><?php if ($pTotalCounter!=0) {echo formatPercent(($punique_views_clicks/$pTotalCounter),2);} else {echo 'n/a';}?></span></td>
				<td class="listingCellStats" align="center"><span><?php  if ($punique_views>0) {echo formatPercent(($punique_clicks/$punique_views),2);} else {echo 'n/a';}?></span></td>
				<td class="listingCellStats" align="center"><?php echo $punique_views;?></td>
				<td class="listingCellStats" align="center"><?php if ($pTotalCounter!=0) {echo formatPercent(($punique_views/$pTotalCounter),2);} else {echo 'n/a';}?></td>
				<td class="listingCellStats" align="center"><?php echo $pviews;?></td>
				<td class="listingCellStats" align="center"><?php if ($pTotalCounter!=0) {echo formatPercent(($pviews/$pTotalCounter),2);} else {echo 'n/a';}?></td>
				<td class="listingCellStats" align="center"><?php echo $punique_clicks;?></td>
				<td class="listingCellStats" align="center"><?php if ($pTotalCounter!=0) {echo formatPercent(($punique_clicks/$pTotalCounter),2);} else {echo 'n/a';}?></td>
				<td class="listingCellStats" align="center"><?php echo $pall_clicks;?></td>
				<td class="listingCellStats" align="center"><?php if ($pTotalCounter!=0) {echo formatPercent(($pall_clicks/$pTotalCounter),2);} else {echo 'n/a';}?></td>
				<td class="listingCellStats" align="center"><?php echo $pTotalOptedOut;?></td>
				<td class="listingCellStats" align="center"><?php if ($pTotalCounter!=0) {echo formatPercent(($pTotalOptedOut/$pTotalCounter),2);} else {echo 'n/a';}?></td>
				<td class="listingCellStats" align="center"><?php echo $pTotalBounced;?></td>
				<td class="listingCellStats" align="center"><?php if ($pTotalCounter!=0) {echo formatPercent(($pTotalBounced/$pTotalCounter),2);} else {echo 'n/a';}?></td>
				<td class="listingCellStats" align="center" colspan="2" <?php if ($typeOf!="xl") {?>style="BORDER-right: #c9c9c9 1px solid"<?php }?>><?php echo $pTotalForwarded;?></td>
			</tr>
 </tbody>
</table>
<div style="margin-top:7px"><?php if ($typeOf!="xl") {echo ALLSTATS_140.': ';?><a href="customAggregate.php?typeOf=xl&selectedCampaigns=<?php echo  $selectedCampaigns?>"><img src="./images/excel.png" border="0" alt="" width="18" height="18"></a></div><?php } ?>
<br>
<?php
$mySQL="SELECT linkUrl, count(distinct idEmail) as uniqueClicks, count(idEmail) as allClicks FROM ".$idGroup."_clickStats where ".campaignsOR($selectedCampaigns, $idGroup)." group by linkUrl order by uniqueClicks desc";
$result2	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result2);
if (!$rows){
	echo '<div id="results3">no-links-found</div>';
}
else {?>
	<!--TABLE WITH LINKS AND RATIOS STARTS HERE-->
 <?php if ($typeOf!="xl") {?>	
	<table class="sortable" id="linksTable" width="100%" cellpadding="2" cellspacing="0" style="BORDER-RIGHT:#dcdcdc 0px solid;BORDER-LEFT:#dcdcdc 1px solid;BORDER-TOP:#dcdcdc 1px solid;BORDER-BOTTOM: #dcdcdc 1px solid">
<?php } else {?>	
	<table border="1">
	<?php }?>
		<thead>
		<tr valign=top>
			<td	class="text headerCell" width="400" style="border-left:#999 1px solid;"><div style="color: #ffffff; padding:5px 5px 5px 7px;"><?php echo ALLSTATS_78; ?></div></td>
			<td	class='number headerCell sortfirstdesc' style="border-left:#999 1px solid;"><div style="color: #ffffff; padding:5px 5px 5px 15px;"><?php echo ALLSTATS_80; ?></div></td>
			<td	class='number headerCell' style="border-left:#999 1px solid;"><div style="color: #ffffff; padding:5px 5px 5px 15px;">%</div></td>
			<td class='number headerCell' style="border-left:#999 1px solid;"><div style="color: #ffffff; padding:5px 5px 5px 15px;"><?php echo ALLSTATS_79;?></div></td>
			<td	class='number headerCell'><div style="color: #ffffff; padding:5px 5px 5px 15px;">%</div></td>
			<?php if ($typeOf!="xl") {?><td	class='headerCell nosort'><div style="color: #ffffff; padding:5px 5px 5px 5px;"><?php echo ADMIN_HEADER_2?></div></td>	<?php }?>
		</tr>
		</thead>
		<tbody>
			<?php 
				while ($row2 = $obj->fetch_array($result2)){
				$pLinkUrl = $row2['linkUrl'];
			   	$punique_clicks =$row2['uniqueClicks'];
			       $pall_clicks =$row2['allClicks'];
				echo "<tr>",
			    "<td align='left' class='linkStats' style='padding-left:7px'>", wordwrap($pLinkUrl, 60, "<br>\r\n", true),"</td>",
				"<td class='linkStats' style='padding-left:15px'>".$punique_clicks	."</td>",
				"<td class='linkStats' style='padding-left:15px'>";
			    if ($punique_clicks!=0 && $pTotalCounter!=0) {echo formatPercent(($punique_clicks/$pTotalCounter),2);} else {echo 'n/a';}
				echo "</td>",
				"<td  class='linkStats' style='padding-left:15px'>".$pall_clicks ."</td>",
				"<td class='linkStats' style='padding-left:15px'>";
			    if ($pall_clicks!=0 && $pTotalCounter!=0) {echo formatPercent(($pall_clicks/$pTotalCounter),2);} else {echo 'n/a';}
				if ($typeOf!="xl") {echo "</td><td class='linkStats' style='padding-left:10px;'><a title='".ALLSTATS_118."' href='countUniqueClicks.php?redirect=clicksAggr&turn=-1&selectedCampaigns=".$selectedCampaigns."&linkUrl=".base64_encode($pLinkUrl)."'>".ALLSTATS_86."</a></td></tr>";}
			}
			?>
	</tbody>
</table>
<?php }
$obj->free_result($result);
$obj->closeDb();
?>