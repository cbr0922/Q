<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com


if (isset($_GET['thisCampaign'])) {
	include('adminVerify2.php');
	include('../inc/dbFunctions.php');
	$obj 		= new db_class();
	include('../inc/stringFormat.php');
	include('./includes/auxFunctions.php');
	include('./includes/languages.php');
	$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
	header('Content-type: text/html; charset='.$groupGlobalCharset.'');
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
	$groupName 	 =	$obj->getSetting("groupName", $idGroup);
	$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
	$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
	$pidCampaign = $_GET['thisCampaign'];
	$mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted, dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers, optedOut, forwarded, bounced, notes, ga_utm_source, ga_utm_medium, ga_utm_campaign, ga_utm_term, ga_utm_content FROM ".$idGroup."_campaigns WHERE idCampaign=".$pidCampaign;
	//echo $mySQL.'<br><br>';
	$result	= $obj->query($mySQL);
	$row = $obj->fetch_array($result);
}

$pcampaignName		= $row['campaignName'];
$pidadmin		    = $row['idAdmin'];
$pidgroup		    = $row['idGroup'];
$pidlist		    = $row['idList'];
$plistname			= $row['listName'];
$pidHtmlNewsletter	= $row['idHtmlNewsletter'];
$pidTextNewsletter	= $row['idTextNewsletter'];
$purlToSend       	= $row['urlToSend'];
$pemailSubject    	= $row['emailSubject'];
$pdateCreated		= addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);
$pdateStarted		= addOffset($row['dateStarted'], $pTimeOffsetFromServer, $groupDateTimeFormat);
$pdateCompleted		= addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);
$pcounter		    = $row['mailCounter'];
$pcompleted			= $row['completed'];
$ptype				= $row['type'];
switch ($ptype) {
case 1:
$ptypeIs = 'Html'; break;
case 2:
$ptypeIs = 'Text'; break;
case 3:
$ptypeIs = 'Multipart'; break;
case 4:
$ptypeIs = 'URL'; break;
default:
$ptypeIs='Undefined';
}
$pidSendFilter		= $row['idSendFilter'];
$pSendFilterDesc	= getSendFilterDesc($pidSendFilter, $idGroup);
$pconfirmed 		= $row['confirmed'];    //1:yes, 2: no, 3:all
switch ($pconfirmed) {
case 1:
$pconfirmedIs 		= ALLSTATS_93; break;
case 2:
$pconfirmedIs 		= ALLSTATS_94; break;
default:
$pconfirmedIs		= ALLSTATS_123;
}
$pprefers   		= $row['prefers'];      //1:html, 2: text, 3:all
switch ($pprefers) {
case 1:
$pprefersIs 		= ALLSTATS_121; break;
case 2:
$pprefersIs 		= ALLSTATS_122; break;
default:
$pprefersIs		= ALLSTATS_123;
}
$poptedOut 		= $row['optedOut'];
$pforwarded		= $row['forwarded'];
$pbounced        = $row['bounced'];
$pnotes	        = $row['notes'];
$ga_utm_source	= $row['ga_utm_source'];
$ga_utm_medium	= $row['ga_utm_medium'];
$ga_utm_campaign= $row['ga_utm_campaign'];
$ga_utm_term	= $row['ga_utm_term'];
$ga_utm_content	= $row['ga_utm_content'];
$GA_array 		= array("ga_utm_source"=>$ga_utm_source, "ga_utm_medium"=>$ga_utm_medium, "ga_utm_campaign"=>$ga_utm_campaign, "ga_utm_term"=>$ga_utm_term, "ga_utm_content"=>$ga_utm_content);

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

<tr id="reload<?php echo $pidCampaign?>" onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
	<td colspan="2" class="listingCellStats" valign="top"><!-- left box -->
				<div style="float:left"><span class="statsLegendEmph"><?php echo ALLSTATS_88?>&nbsp;<?php echo  $pidCampaign?></span></div>
				<div style="float:right;padding-right:7px;"><?php 
				if (campaignIsScheduled($pidCampaign, $idGroup)) {?><img onmouseout="hide_info_bubble('cl<?php echo $pidCampaign;?>','0')" onmouseover="infoBox('cl<?php echo $pidCampaign;?>', '<?php echo fixJSstring(ALLSTATS_3)?>', '', '15em', '0'); " src="./images/calendar.png" style="vertical-align:bottom;" width="16" height="14"  alt=""><span style="display: none;" id="cl<?php echo $pidCampaign;?>"></span>&nbsp;<?php }	
				if ($ga_utm_source<>"") {google_params($pidCampaign, $GA_array);} 
				campaignNotes($pnotes, $pidCampaign);
				echo '</div><div style="clear:both"></div>';
				if ($pcampaignName) {echo '<div style="margin-top:7px"><span class="statsLegend">'.CAMPAIGNCREATE_54.': </span>'.$pcampaignName.'</div>';}
				 ?>
				<!--subscribers-->
				<div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_76;?>:</span> <?php  echo $plistname; if (listDeleted($pidlist, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}?></div>
				<!--content-->
				<div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_75;?>:</span>
				    <?php  if ($pidHtmlNewsletter!="0") {echo '<br>'.$pidHtmlNewsletter.'. '.wordwrap(getNewsletterData($pidHtmlNewsletter, $idGroup, 0),60,"<br>\r\n", true).' (Html)';
				    if (newsletterDeleted($pidHtmlNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
				    if ($pidTextNewsletter!="0") {echo '<br>'.$pidTextNewsletter.'. '.wordwrap(getNewsletterData($pidTextNewsletter, $idGroup, 0),60,"<br>\r\n", true).' (Text)';
				    if (newsletterDeleted($pidTextNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
				    if (!empty($pemailSubject)) {echo '<br>'.$pemailSubject." ($ptypeIs)".'<br>'.wordwrap($purlToSend,60,"<br>\r\n", true);}?></div>
				<!--format-->
				<div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_89;?>:</span> <?php echo $ptypeIs?></div>
				<!--filter-->
				<div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_103; ?>:</span>&nbsp;
				<?php if ($pidSendFilter!=0) {
				 echo '<br>'.$pidSendFilter .'. '.substr(wordwrap($pSendFilterDesc,60,"<br>\r\n", true), 0,100).'...';
				 if (filterDeleted($pidSendFilter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}} else {echo ALLSTATS_94;} ?></div>
	</td>
    <td class="listingCellStats"  valign="top"> <!-- middle box -->
                    <div><span class="statsLegend"><?php echo ALLSTATS_4; ?>:</span> <?php echo $pidadmin.'. '.getadminname($pidadmin, $idGroup);?></div>
                    <div style="margin-top:7px">
                    <span class="statsLegend"><?php echo ALLSTATS_85;?>:</span> <?php echo $pdateCreated?>
                    <br><span class="statsLegend"><?php echo ALLSTATS_851;?>:</span> <?php if ($pdateStarted) { echo $pdateStarted;} else {echo ALLSTATS_94;}?>
                    <br><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span> <?php if ($pdateCompleted) { echo $pdateCompleted;} else {echo ALLSTATS_94;}?>
                    </div>
                     <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_119.':</span> '.$pconfirmedIs;?><br />
                        <span class="statsLegend"><?php echo ALLSTATS_120.':</span> '.$pprefersIs;?>
                     </div>
                     <div style="margin-top:7px"><?php echo ALLSTATS_68; ?>: <a href="summaryXL.php?idCampaign=<?php echo  $pidCampaign?>"><img src="./images/excel.png" border="0" alt="" width="18" height="18"></a></div>
                     <div style="margin-top:7px"><?php echo ALLSTATS_102; ?> <a href="#" onclick="openConfirmBox('delete.php?action=campaignviews&idCampaign=<?php echo $pidCampaign?>','<?php echo CONFIRM_9?><br><?php echo GENERIC_2?>');return false;"><img src="./images/delete.png" width="18" height="18" border="0" alt=""></a></div>
	</td>
    <td colspan=2 class="listingCellStats" style="BORDER-right: #c9c9c9 1px solid;" valign=top><!-- right box starts -->
		<div style="float:left;text-align:left;margin-bottom:10px;margin-right:13px"><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span>&nbsp;<?php if ($pcompleted=="-1") {echo ALLSTATS_93;} else {echo ALLSTATS_94;}?></div>
	    <div style="float:right;text-align:right;margin-bottom:10px;margin-right:13px"><span class="statsLegend"><?php echo ALLSTATS_81; ?>:</span>&nbsp;<span class="statsLegendEmph"><?php echo $pcounter; ?></span></div>
		<div style="clear:both"></div>
	   	<div>
   		<!--table with ratios starts here-->
		<table style="BORDER-RIGHT:#dcdcdc 0px solid;BORDER-LEFT:#dcdcdc 1px solid;BORDER-TOP:#dcdcdc 0px solid;BORDER-BOTTOM: #dcdcdc 1px solid" width="100%" cellpadding=10 cellspacing=0>
			<tr>
				<td class='linkStats' width=150><span class="statsLegendEmph"><?php echo ALLSTATS_64; ?></span></td>
				<td class="linkStats" align="right"><span class="statsLegendEmph"><?php echo $punique_views_clicks;?></span></td>
				<td class="linkStats" align="right"><span class="statsLegendEmph"><?php if ($pcounter!=0) {echo formatPercent(($punique_views_clicks/$pcounter),2);} else {echo 'n/a';}?></span></td>
				<td class="linkStats" align="center"><?php  if ($punique_views_clicks>0) {?><a title="<?php echo ALLSTATS_118?>" href="countUniqueClicks.php?turn=-1&redirect=uvc&idCampaign=<?php echo $pidCampaign;?>"><?php echo ALLSTATS_86; ?></a><?php }?></td>
			</tr>
			<tr>
				<td class='linkStats' width=150><span><?php echo ALLSTATS_161; ?></span></td>
				<td class="linkStats" align="right" style="border-right:0px;"></td>
				<td class="linkStats" align="right" style="border-left:0px;"><?php  if ($punique_views>0) {echo formatPercent(($punique_clicks/$punique_views),2);} else {echo 'n/a';}?></td>
				<td class="linkStats" align="right" style="border-left:0px;"></td>
			</tr>

			<tr valign=top>
				<td class='linkStats'><span><?php echo ALLSTATS_105; ?></span></td>
				<td class="linkStats" align="right"><?php echo $punique_views;?></td>
				<td class="linkStats" align="right"><?php if ($pcounter!=0) {echo formatPercent(($punique_views/$pcounter),2);} else {echo 'n/a';}?></td>
				<td class="linkStats" align="center"><?php if ($punique_views>0) {?><a title="<?php echo ALLSTATS_118?>" href="countUniqueClicks.php?turn=-1&redirect=views&idCampaign=<?php echo $pidCampaign;?>"><?php echo ALLSTATS_86; ?></a><?php } ?></td>
			</tr>
			<tr valign=top>
				<td class='linkStats'><span><?php echo ALLSTATS_106; ?></span></td>
				<td class="linkStats" align="right"><?php echo $pviews;?></td>
				<td class="linkStats" align="right"><?php if ($pcounter!=0) {echo formatPercent(($pviews/$pcounter),2);} else {echo 'n/a';}?></td>
				<td class="linkStats" align="center"><?php if ($pviews>0) {?><a title="<?php echo ALLSTATS_118?>" href="countUniqueClicks.php?turn=0&redirect=views&idCampaign=<?php echo $pidCampaign;?>"><?php echo ALLSTATS_86; ?></a><?php } ?></td>
			</tr>
			<tr valign=top>
				<td class='linkStats'><span><?php echo ALLSTATS_80; ?></span></td>
				<td class="linkStats" align="right"><?php echo $punique_clicks;?></td>
				<td class="linkStats" align="right"><?php if ($pcounter!=0) {echo formatPercent(($punique_clicks/$pcounter),2);} else {echo 'n/a';}?></td>
				<td class="linkStats" align="center"><?php if ($punique_clicks>0) {?><a title="<?php echo ALLSTATS_118?>" href="countUniqueClicks.php?turn=-1&redirect=clicks&idCampaign=<?php echo $pidCampaign;?>"><?php echo ALLSTATS_86; ?></a><?php } ?></td>
			</tr>
			<tr valign=top>
				<td class='linkStats'><span><?php echo ALLSTATS_79; ?></span></td>
				<td class="linkStats" align="right"><?php echo $pall_clicks;?></td>
				<td class="linkStats" align="right"><?php if ($pcounter!=0) {echo formatPercent(($pall_clicks/$pcounter),2);} else {echo 'n/a';}?></td>
				<td class="linkStats" align="center"><?php if ($pall_clicks>0) {?><a title="<?php echo ALLSTATS_118?>" href="countUniqueClicks.php?turn=0&redirect=clicks&idCampaign=<?php echo $pidCampaign;?>"><?php echo ALLSTATS_86; ?></a><?php } ?></td>
			</tr>
			<tr>
				<td class='linkStats'><span><?php echo ALLSTATS_62; ?></span></td>
				<td class="linkStats" align="right"><?php echo $poptedOut;?></td>
				<td class="linkStats" align="right"><?php if ($pcounter!=0) {echo formatPercent(($poptedOut/$pcounter),2);} else {echo 'n/a';}?></td>
				<td class="linkStats" align="center"><?php if ($poptedOut>0) {?><a href="optOuts.php?idCampaign=<?php echo $pidCampaign?>"><?php echo ALLSTATS_86; ?></a><?php }?></td>
			</tr>
			<tr>
				<td class='linkStats'><span><?php echo ALLSTATS_10; ?></span></td>
				<td class="linkStats" align="right"><?php echo $pbounced;?></td>
				<td class="linkStats" align="right"><?php if ($pcounter!=0) {echo formatPercent(($pbounced/$pcounter),2);} else {echo 'n/a';}?></td>
				<td class="linkStats" align="center"></td>
			</tr>
			<tr>
				<td class='linkStats'><span><?php echo ALLSTATS_63; ?></span></td>
				<td class="linkStats" align="right"><?php echo $pforwarded;?></td>
				<td class="linkStats" align="right"></td>
				<td class="linkStats" align="center">&nbsp;</td>
			</tr>
		</table>
		</div>
		<div align="right" style="margin-top:3px">
		<?php
		$daysPassed = intval((strtotime(date("Y-m-d H:i:s"))-strtotime($row['dateStarted'])) / (60 * 60 * 24));
		if ($daysPassed>=1 && $row['dateStarted']) {?>
		<a onclick="popUpLayer('<?php echo ALLSTATS_88.' '.$pidCampaign.': '. ALLSTATS_143;?>', 'chart_jq_trend.php?idCampaign=<?php echo $pidCampaign?>&reload=n',880,350);return false;" href="#"><img src="./images/trend.png" style="vertical-align:top" alt="" width="19" height="18" border=0 title="<?php echo ALLSTATS_88.' '.ALLSTATS_143;?>"></a>
		&nbsp;&nbsp;
		<?php }
		if ($showGoogleApiReports=="-1" && $row['dateStarted']) { ?>
		<a onclick="popUpLayer('<?php echo ALLSTATS_88.'&nbsp;'.$pidCampaign.': ' .ALLSTATS_154;?>', 'chart_gva_emailClients.php?idCampaign=<?php echo $pidCampaign?>',600,400);return false;" href="#"><img src="./images/emailClientsGapi.png" style="vertical-align:top" alt="" width="21" height="20" border=0 title="<?php echo ALLSTATS_154. ' [Google API]';?>"></a>
		&nbsp;&nbsp;
		<?php }
		if ($showGoogleApiReports!="-1" && $row['dateStarted']) { ?>
		<a onclick="popUpLayer('<?php echo ALLSTATS_88.'&nbsp;'.$pidCampaign.': ' .ALLSTATS_154;?>', 'chart_jq_emailClients.php?idCampaign=<?php echo $pidCampaign?>',600,450);return false;" href="#"><img src="./images/emailClients.png" style="vertical-align:top" alt="" width="21" height="20" border=0 title="<?php echo ALLSTATS_154;?>"></a>
		&nbsp;&nbsp;
		<?php }?>
		<a href="#" onclick="reloadCampaign(<?php echo  $pidCampaign?>);return false;"><img src="./images/refresh.png" style="vertical-align:top" width="20" height="20" border="0" alt=""></a>
		</div>
	</td><!-- closing far right cell -->
</tr>
<tr id="indicator<?php echo $pidCampaign?>" style="display:none"><td style="BORDER: #c9c9c9 1px solid;" colspan="5" valign="middle" align="center"><img alt="" src="./images/waitBig.gif" border=0 title="<?php echo GENERIC_4; ?>"><br><?php echo GENERIC_4; ?></td></tr>

