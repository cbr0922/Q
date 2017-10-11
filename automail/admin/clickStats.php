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
include('header.php');
showMessageBox();

(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
if ($fidCampaign) {
	$strSQL=" WHERE ".$idGroup."_campaigns.idCampaign=$fidCampaign";
} else {$strSQL="";}

$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$rowsPerPage 	= 20;
$offset 		= ($page - 1) * $rowsPerPage;
$urlPaging      = "$self?";
$range=10;
$offset 	    = ($page - 1) * $rowsPerPage;
?>

<table border="0" width="960px">
	<tr>
		<td valign="top" width=50%>
			<span class="title"><?php echo ALLSTATS_66; ?></span><span style="vertical-align:middle"><?php if ($fidCampaign) { echo '&nbsp;&nbsp;&nbsp;'.ALLSTATS_88.': '.$fidCampaign;} else {echo '&nbsp;&nbsp;&nbsp;'.ALLSTATS_90;}?></span>
			&nbsp;<a href="#" onclick="infoBox('stats_2', '<?php echo  fixJSstring(ALLSTATS_2)?>', '<?php echo  fixJSstring(ALLSTATS_13)?><br><br><?php echo  fixJSstring(ALLSTATS_5)?>', '40em','1');return false;" ><img src="./images/helpSmallWhite.gif" border="0"></a><span style="display: none;text-align:left;" id="stats_2"></span>
		</td>
		<td align=right>
			<img src="./images/clickstats.png">
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right"></td>
	</tr>
</table>
<br>
    <?php
    $limitSQL 		= " LIMIT $offset, $rowsPerPage";
    $mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted, dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers, notes, ga_utm_source, ga_utm_medium, ga_utm_campaign, ga_utm_term, ga_utm_content FROM ".$idGroup."_campaigns $strSQL ORDER by ".$idGroup."_campaigns.idCampaign desc".$limitSQL;
//    echo $mySQL.'<br><br>';
    $result	= $obj->query($mySQL);
    $rows 	= $obj->num_rows($result);

    if (!$rows){
        if ($fidCampaign) {
            echo '<br><img src="./images/warning.png"> '.ALLSTATS_87.'<br />';
        }
        else {
            echo '<br><img src="./images/warning.png"> '.ALLSTATS_84.'<br />';
        }
    }
    else {
   	$countSQL="SELECT count(idCampaign) from ".$idGroup."_campaigns ".$strSQL;
	$numrows=$obj->get_rows($countSQL);
	$maxPage = ceil($numrows/$rowsPerPage);
    include('nav.php');
    ?>
		<table width="960px" style="margin-top:8px;BORDER-RIGHT: #999999 0px  solid; BORDER-TOP: #6666CC 0px  solid; BORDER-LEFT: #999999 0px  solid; BORDER-BOTTOM: #999999 0px  solid" cellpadding="0" cellspacing="0">
        <tr>
            <td class="leftCorner"></td>
			<td class="headerCell" width="230" style="BORDER-left: #999999 0px solid;text-align:left;"><?php echo ALLSTATS_9;?></td>
            <td class="headerCell" width="700"><?php echo ALLSTATS_77;?></td>
			<td class="rightCorner"></td>
		</tr>
			<?php
            while ($row = $obj->fetch_array($result)){
                $pidCampaign		= $row['idCampaign'];
				$pcampaignName		= $row['campaignName'];
                $pidadmin		    = $row['idAdmin'];
                $pidgroup		    = $row['idGroup'];
                $pidlist		    = $row['idList'];
                $plistname		= $row['listName'];
                $pidHtmlNewsletter= $row['idHtmlNewsletter'];
                $pidTextNewsletter= $row['idTextNewsletter'];
                $purlToSend       = $row['urlToSend'];
                $pemailSubject    = $row['emailSubject'];
                $pdateCreated		= addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);
                $pdateStarted		= addOffset($row['dateStarted'], $pTimeOffsetFromServer, $groupDateTimeFormat);
                $pdateCompleted	= addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);
                $pcounter		    = $row['mailCounter'];
                $pcompleted		= $row['completed'];
                $ptype			= $row['type'];
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
                  $pidSendFilter	= $row['idSendFilter'];
                $pSendFilterDesc	= getSendFilterDesc($pidSendFilter, $idGroup);
				$pconfirmed = $row['confirmed'];    //1:yes, 2: no, 3:all
                 switch ($pconfirmed) {
                   case 1:
                  $pconfirmedIs = ALLSTATS_93; break;
                  case 2:
                  $pconfirmedIs = ALLSTATS_94; break;
                  default:
                  $pconfirmedIs=ALLSTATS_123;
                  }
               $pprefers   = $row['prefers'];      //1:html, 2: text, 3:all
                switch ($pprefers) {
                    case 1:
                    $pprefersIs = ALLSTATS_121; break;
                    case 2:
                    $pprefersIs = ALLSTATS_122; break;
                    default:
                    $pprefersIs=ALLSTATS_123;
                  }
				  $pnotes			= $row['notes'];
				  $ga_utm_source	= $row['ga_utm_source'];
				  $ga_utm_medium	= $row['ga_utm_medium'];
				  $ga_utm_campaign= $row['ga_utm_campaign'];
				  $ga_utm_term	= $row['ga_utm_term'];
				  $ga_utm_content	= $row['ga_utm_content'];
				  $GA_array 		= array("ga_utm_source"=>$ga_utm_source, "ga_utm_medium"=>$ga_utm_medium, "ga_utm_campaign"=>$ga_utm_campaign, "ga_utm_term"=>$ga_utm_term, "ga_utm_content"=>$ga_utm_content);
               //TOTAL UNIQUE CLICKS
              $mySQLc="select count(distinct idEmail) as unique_clicks from ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign";
              $punique =$obj->get_rows($mySQLc);
              //TOTAL ALL CLICKS
              $mySQLac="select count(idEmail) as all_clicks from ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign";
              $pall =$obj->get_rows($mySQLac);

                //CHECK FOR LINKS
                $mySQL1="SELECT distinct linkUrl FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign LIMIT 1";
                $result1=$obj->query($mySQL1);
                $linkRows 	= $obj->num_rows($result1);

            ?>
        <tr valign="top">
		    <td class="listingCellStats" colspan="2" valign="top">
                <div style="float:left"><span class="statsLegendEmph"><?php echo ALLSTATS_88?>&nbsp;<?php echo  $pidCampaign?></span></div>
				<div style="float:right;padding-right:7px">
				<?php campaignNotes($pnotes, $pidCampaign);
					if ($ga_utm_source<>"") {google_params($pidCampaign, $GA_array);}
					 echo '</div><div style="clear:both"></div>';
					if ($pcampaignName) {echo '<div style="margin-top:7px"><span class="statsLegend">'.CAMPAIGNCREATE_54.': </span>'.$pcampaignName.'</div>';}
					?>
                    <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_4; ?>:</span> <?php echo $pidadmin.'. '.getadminname($pidadmin, $idGroup);?></div>

                <?php if ($linkRows) {?>
                <div style="margin-top:7px">
                    <span class="statsLegend"><?php echo ALLSTATS_85;?>:</span> <?php echo $pdateCreated?>
                    <br><span class="statsLegend"><?php echo ALLSTATS_851;?>:</span> <?php if ($pdateStarted) { echo $pdateStarted;} else {echo ALLSTATS_94;}?>
                    <br><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span> <?php if ($pdateCompleted) { echo $pdateCompleted;} else {echo ALLSTATS_94;}?>
                </div>

                <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_76;?>:</span> <?php  echo $plistname; if (listDeleted($pidlist, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}?></div>

                <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_75;?>:</span>
                    <?php  if ($pidHtmlNewsletter!="0") {echo '<br>'.$pidHtmlNewsletter.'. '.getNewsletterData($pidHtmlNewsletter, $idGroup, 0).' (Html)';
                            if (newsletterDeleted($pidHtmlNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                        if ($pidTextNewsletter!="0") {echo '<br>'.$pidTextNewsletter.'. '.getNewsletterData($pidTextNewsletter, $idGroup, 0).' (Text)';
                            if (newsletterDeleted($pidTextNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                        if (!empty($pemailSubject)) {echo '<br>'.$pemailSubject." ($ptypeIs)".'<br>'.wordwrap($purlToSend,50,"<br>\r\n", true);}?></div>
                            <div style="margin-top:7px"><a href="#" class="cross" onclick="show_hide_div('details<?php echo $pidCampaign?>','cross<?php echo $pidCampaign?>');return false;"><span id="cross<?php echo $pidCampaign?>">[+]</span>&nbsp;<?php echo ALLSTATS_11?></a></div>
                       <div id="details<?php echo $pidCampaign?>" style="display:none;">
                            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_103; ?>:</span>&nbsp;
                                <?php if ($pidSendFilter!=0) {
                                echo '<br>'.$pidSendFilter .'. '.substr(wordwrap($pSendFilterDesc,50,"<br>\r\n", true), 0,100).'...';
                                if (filterDeleted($pidSendFilter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}} else {echo ALLSTATS_94;} ?>
                            </div>
                            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_89;?>:</span> <?php echo $ptypeIs?></div>
                            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_119.':</span> '.$pconfirmedIs;?></span><br />
                                <span class="statsLegend"><?php echo ALLSTATS_120.':</span> '.$pprefersIs;?></span>
                             </div>
                             <div style="margin-top:7px"><?php echo ALLSTATS_68; ?>: <a href="summaryXL.php?idCampaign=<?php echo  $pidCampaign?>"><img src="./images/excel.png" border="0" alt="" width="18" height="18"></a></div>
                             <br><?php echo ALLSTATS_69; ?> <a href=# onclick="openConfirmBox('delete.php?action=campaignclicks&idCampaign=<?php echo $pidCampaign?>','<?php echo  fixJSstring(CONFIRM_10)?><br><?php echo  fixJSstring(GENERIC_2)?>');return false;"><img src="./images/delete.png" width="18" height="18" border=0></a></div>
                       </div>
			   <?php }?>
               </td>
              <td colspan=2 class="listingCellStats" style="BORDER-right: #c9c9c9 1px solid;" valign=top>
                    <div style="float:left;margin-bottom:10px;"><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span>&nbsp;<span class="statsLegendEmph"><?php if ($pcompleted=="-1") {echo ALLSTATS_93;} else {echo ALLSTATS_94;}?></span></div>
                    <div style="float:right;margin-bottom:10px;margin-right:13px"><span class="statsLegend"><?php echo ALLSTATS_81; ?>:</span>&nbsp;<span class="statsLegendEmph"><?php echo $pcounter; ?></span></div>
					<div style="clear:both;"></div>
					<?php if ($linkRows){?>
                    <!--TABLE WITH LINKS AND RATIOS STARTS HERE-->
					<table class="sortable"  width="100%" cellpadding="0" cellspacing="0" style="BORDER-RIGHT:#dcdcdc 0px solid;BORDER-LEFT:#dcdcdc 1px solid;BORDER-TOP:#dcdcdc 1px solid;BORDER-BOTTOM: #dcdcdc 1px solid">
						<thead>
						<tr >
							<td class='text headerCell' style="padding: 4px 3px 0px 3px;"><span><?php echo ALLSTATS_78; ?></span></td>
							<td	width="100" class="number sortfirstdesc headerCell" style="padding: 4px 15px 5px 3px;border-left:#999 1px solid;"><span><?php echo ALLSTATS_80; ?></span></td>
							<td	width="65" class="number headerCell" align="center" style="padding: 4px 15px 5px 3px;border-left:#999 1px solid;"><span>%</span></td>
							<td width="100" class="number headerCell" align="left" style="padding: 4px 15px 5px 3px;border-left:#999 1px solid;"><span><?php echo ALLSTATS_79; ?></span></td>
							<td	width="65" class="number headerCell" align="center" style="padding: 4px 15px 5px 3px;border-left:#999 1px solid;"><span>%</span></td>
						</tr>
						</thead>
						<tbody>
							<?php
							$mySQL="SELECT linkUrl, count(distinct idEmail) as uniqueClicks, count(idEmail) as allClicks FROM ".$idGroup."_clickStats where idCampaign=$pidCampaign group by linkUrl order by uniqueClicks desc";
							$result2	= $obj->query($mySQL);
							while ($row2 = $obj->fetch_array($result2)){
								$pLinkUrl = $row2['linkUrl'];
						    	$punique_clicks =$row2['uniqueClicks'];
						        $pall_clicks =$row2['allClicks'];
								echo '<tr onMouseOver=this.bgColor="#f4f4f9"; onMouseOut=this.bgColor="#ffffff";>',
						        "<td align='left' class='linkStats'>", wordwrap($pLinkUrl, 60, "<br>\r\n", true),"</td>",
								"<td  class='linkStats'>".$punique_clicks." <a title='".ALLSTATS_118."' href='countUniqueClicks.php?redirect=clicks&turn=-1&idCampaign=".$pidCampaign."&linkUrl=".base64_encode($pLinkUrl)."'>".ALLSTATS_86."</a></td>",
								"<td class='linkStats'>";
							    if ($punique_clicks!=0 && $pcounter!=0) {echo formatPercent(($punique_clicks/$pcounter),2);} else {echo 'n/a';}
								echo "</td>",
								"<td  class='linkStats'>".$pall_clicks ." <a title='".ALLSTATS_118."' href='countUniqueClicks.php?redirect=clicks&turn=0&idCampaign=".$pidCampaign."&linkUrl=".base64_encode($pLinkUrl)."'>".ALLSTATS_86."</a></td>",
								"<td class='linkStats'>";
							    if ($pall_clicks!=0 && $pcounter!=0) {echo formatPercent(($pall_clicks/$pcounter),2);} else {echo 'n/a';}
							echo "</td></tr>";
							}
 						?>
					</tbody>	
					</table>
                    <?php } else {echo ALLSTATS_70;}?>
				</td
			</tr>
			<?php
			}
			?>
		</table><br />
<?php
include('nav.php');
}
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>