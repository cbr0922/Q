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
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName 	 			=	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$today 					= myDatenow();
include('headerXL.php');

(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
if ($fidCampaign) {$strSQL=" WHERE ".$idGroup."_campaigns.idCampaign=$fidCampaign";}
else {$strSQL="";}
?>
<table border="0" width="100%">
	<tr>
		<td valign="top" colspan=8>
			<strong><?php echo ALLSTATS_1; ?></strong>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if (!$fidCampaign) {echo ALLSTATS_90;}?>
		</td>
	</tr>

    <?php
    $mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted, dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers, optedOut, forwarded, bounced FROM ".$idGroup."_campaigns $strSQL ORDER by ".$idGroup."_campaigns.idCampaign desc";
    $result	= $obj->query($mySQL);
    $rows 	= $obj->num_rows($result);

    if (!$rows){
        echo '<tr><td colspan=2><br><img src="./images/warning.png"> '.ALLSTATS_17;
        if (!$rows && $fidCampaign) {echo '&nbsp;'.ALLSTATS_87.'<br />';}
        echo '</td></tr></table>';
    } else {
    ?>
</table>
<br />
<table width="1000" cellpadding="0" cellspacing="0">
		<tbody>
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
               $poptedOut 		= $row['optedOut'];
               $pforwarded		= $row['forwarded'];
               $pbounced        = $row['bounced'];
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

              $SQL="SELECT count(distinct linkUrl) FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign";
              $linkscount=$obj->get_rows($SQL);
              $mySQL1="SELECT distinct linkUrl FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign";
              $result1=$obj->query($mySQL1);
              $linkRows 	= $obj->num_rows($result1);

            ?>
        <tr  bgcolor=#ededed style="border-top: #999 1px solid;border-bottom: #999 1px solid;">
			<td colspan="2"><strong><?php echo ALLSTATS_9; ?></strong></td>
            <td colspan="2"></td>
            <td><strong><?php echo ALLSTATS_8;?></strong></td>
		</tr>
		<tr><td colspan="5"><strong><?php echo ALLSTATS_88?>&nbsp;<?php echo  $pidCampaign; if ($pcampaignName) {echo '. '.$pcampaignName;}?></strong></td></tr>
        <tr>
		    <td valign="top" width=300>

                <div style="margin-top:7px"><span><?php echo ALLSTATS_4; ?>:</span> <?php echo $pidadmin.'. '.getadminname($pidadmin, $idGroup);?></div>
                <div style="margin-top:7px"><span><?php echo ALLSTATS_76;?>:</span> <?php  echo $plistname; if (listDeleted($pidlist, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}?></div>
                <div style="margin-top:7px"><span><?php echo ALLSTATS_89;?>:</span> <?php echo $ptypeIs?></div>
                <div style="margin-top:7px"><span><?php echo ALLSTATS_75;?>:</span>
                    <?php  if ($pidHtmlNewsletter!="0") {echo '<br>'.$pidHtmlNewsletter.'. '.wordwrap(getNewsletterData($pidHtmlNewsletter, $idGroup, 0),50,"<br>\r\n", true).' (Html)';
                            if (newsletterDeleted($pidHtmlNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                        if ($pidTextNewsletter!="0") {echo '<br>'.$pidTextNewsletter.'. '.wordwrap(getNewsletterData($pidTextNewsletter, $idGroup, 0),50,"<br>\r\n", true).' (Text)';
                            if (newsletterDeleted($pidTextNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                        if (!empty($pemailSubject)) {echo '<br>'.$pemailSubject." ($ptypeIs)".'<br>'.wordwrap($purlToSend,50,"<br>\r\n", true);}?></div>

                <div style="margin-top:7px"><span><?php echo ALLSTATS_103; ?>:</span>&nbsp;
                <?php if ($pidSendFilter!=0) {
                     echo '<br>'.$pidSendFilter .'. '.substr(wordwrap($pSendFilterDesc,50,"<br>\r\n", true), 0,100).'...';
                     if (filterDeleted($pidSendFilter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}} else {echo ALLSTATS_94;} ?></div>
                </td>
                <td></td>
                <td>
                    <div style="margin-top:1px">
                    <span><?php echo ALLSTATS_85;?>:</span> <?php echo $pdateCreated?>
                    <br><span><?php echo ALLSTATS_851;?>:</span> <?php if ($pdateStarted) { echo $pdateStarted;} else {echo ALLSTATS_94;}?>
                    <br><span><?php echo ALLSTATS_852;?>:</span> <?php if ($pdateCompleted) { echo $pdateCompleted;} else {echo ALLSTATS_94;}?>
                    </div>
                     <div style="margin-top:10px"><span><?php echo ALLSTATS_119.':</span> '.$pconfirmedIs;?></span><br />
                        <span><?php echo ALLSTATS_120.':</span> '.$pprefersIs;?></span>
                     </div>
               </td>
               <td></td>
                <td valign=top>
                    <table  cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="50%">
                                <div style="text-align:left;margin-bottom:10px;margin-right:13px"><span><?php echo ALLSTATS_852;?>:</span>&nbsp;<?php if ($pcompleted=="-1") {echo ALLSTATS_93;} else {echo ALLSTATS_94;}?></div>
                            </td>
                            <td>
                                <div style="text-align:right;margin-bottom:10px;margin-right:13px"><span><?php echo ALLSTATS_81; ?>:</span>&nbsp;<?php echo $pcounter; ?></span></div>
                            </td>
                        </tr>
                    </table>
                    <!--table with ratios starts here-->
					<table width="100%" cellpadding=10 cellspacing=0>
                        <tr>
                    		<td width=150><?php echo ALLSTATS_64; ?></td>
                            <td align="right"><?php echo $punique_views_clicks;?></td>
                    		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($punique_views_clicks/$pcounter),2);} else {echo 'n/a';}?></td>
                             <td align="middle"></td>
                        </tr>
                        <tr>
                    		<td width=150><?php echo ALLSTATS_161; ?></td>
                            <td align="right"></td>
                    		<td align="right"><?php if ($punique_views!=0) {echo formatPercent(($punique_clicks/$punique_views),2);} else {echo 'n/a';}?></td>
                             <td align="middle"></td>
                        </tr>

                        <tr valign=top>
							<td><span><?php echo ALLSTATS_105; ?></span></td>
                    		<td align="right"><?php echo $punique_views;?></td>
                     		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($punique_views/$pcounter),2);} else {echo 'n/a';}?></td>
                           <td align="middle"></td>
    					</tr>

                        <tr valign=top>
							<td><span><?php echo ALLSTATS_106; ?></span></td>
                    		<td align="right"><?php echo $pviews;?></td>
                     		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($pviews/$pcounter),2);} else {echo 'n/a';}?></td>
                           <td align="middle"></td>
    					</tr>
						<tr valign=top>
							<td><span><?php echo ALLSTATS_80; ?></span></td>
                    		<td align="right"><?php echo $punique_clicks;?></td>
                    		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($punique_clicks/$pcounter),2);} else {echo 'n/a';}?></td>
                            <td align="middle"></td>
    					</tr>
						<tr valign=top>
							<td><span><?php echo ALLSTATS_79; ?></span></td>
                    		<td align="right"><?php echo $pall_clicks;?></td>
                    		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($pall_clicks/$pcounter),2);} else {echo 'n/a';}?></td>
                            <td align="middle"></td>
    					</tr>

                        <tr>
                    	    <td><span><?php echo ALLSTATS_62; ?></span></td>
                            <td align="right"><?php echo $poptedOut;?></td>
                     		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($poptedOut/$pcounter),2);} else {echo 'n/a';}?></td>
                           <td align="middle"></td>
                        </tr>
                        <tr>
                    	    <td><span><?php echo ALLSTATS_63; ?></span></td>
                            <td align="right"><?php echo $pforwarded;?></td>
                    		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($pforwarded/$pcounter),2);} else {echo 'n/a';}?></td>
                            <td align="middle"></td>
                        </tr>
                        <tr>
                    	    <td><span><?php echo ALLSTATS_10; ?></span></td>
                            <td align="right"><?php echo $pbounced;?></td>
                    		<td align="right"><?php if ($pcounter!=0) {echo formatPercent(($pbounced/$pcounter),2);} else {echo 'n/a';}?></td>
                            <td align="middle"></td>
                        </tr>

					</table>
				</td
			</tr>
           <?php if ($linkRows){?>
           <!--TABLE WITH LINKS AND RATIOS STARTS HERE-->
    		<tr valign=top bgcolor=#ededed>
    			<td><span><strong><?php echo ALLSTATS_78; ?></strong></span></td>
    			<td colspan=2><span><strong><?php echo ALLSTATS_80; ?></strong></span></td>
    			<td  colspan=2><span><strong><?php echo ALLSTATS_79; ?></strong></span></td>
    		</tr>
    			<?php
    		    while ($row1 = $obj->fetch_array($result1)){
    				$pLinkUrl = $row1['linkUrl'];
    					       //UNIQUE CLICKS
                                $mySQL4="select count(distinct idEmail) as unique_clicks from ".$idGroup."_clickStats WHERE linkUrl=\"$pLinkUrl\" AND idCampaign=$pidCampaign";
                                $punique_clicks =$obj->get_rows($mySQL4);
                                //ALL CLICKS
                                $mySQL5="select count(idEmail) as all_clicks from ".$idGroup."_clickStats  WHERE linkUrl=\"$pLinkUrl\" AND idCampaign=$pidCampaign";
                                $pall_clicks =$obj->get_rows($mySQL5);
    				echo "<tr>",
                                "<td width=600>", $pLinkUrl,"</td>",
    				"<td><span style='color:#6666CC'>".$punique_clicks."</span></td>",
    				"<td>";
    				    if ($punique_clicks!=0 && $pcounter!=0) {echo formatPercent(($punique_clicks/$pcounter),2);} else {echo 'n/a';}
    				echo "</td>",
                                "<td><span style='color:#6666CC'>".$pall_clicks ."</span></td>",
    				"<td>";
    				    if ($pall_clicks!=0 && $pcounter!=0) {echo formatPercent(($pall_clicks/$pcounter),2);} else {echo 'n/a';}
    				echo "</td></TR>";
    			}
    			   	?>
                    <?php } else {echo '<tr><td colspan=5><span style="color:red">'.ALLSTATS_70.'</span></td></tr>';}?>
                    <tr><td colspan=5>&nbsp;</td></tr>
			<?php } ?>
		</tbody>
		</table><br />
<?php
} //we have $rows
//$obj->free_result($result);
//$obj->closeDb();
?>


