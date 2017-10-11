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
$plists =  $obj->tableCount_condition($idGroup."_lists", " WHERE idGroup=".$idGroup."");
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName 	 			=	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$today 					= myDatenow();
include('headerXL.php');


(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";

$mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted, dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers, optedOut, forwarded, bounced FROM ".$idGroup."_campaigns WHERE ".$idGroup."_campaigns.idCampaign=$fidCampaign";
$result	= $obj->query($mySQL);

$row = $obj->fetch_array($result);
$pidCampaign		= $row['idCampaign'];
$pcampaignName		= $row['campaignName'];
$pidadmin		    = $row['idAdmin'];
$pidgroup		    = $row['idGroup'];
$pidlist		    = $row['idList'];
$plistname		    = $row['listName'];
$pidHtmlNewsletter  = $row['idHtmlNewsletter'];
$pidTextNewsletter  = $row['idTextNewsletter'];
$purlToSend         = $row['urlToSend'];
$pemailSubject      = $row['emailSubject'];
$pdateCreated		= addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);
$pdateStarted		= addOffset($row['dateStarted'], $pTimeOffsetFromServer, $groupDateTimeFormat);
$pdateCompleted	    = addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);
$pcounter		    = $row['mailCounter'];
$pcompleted		    = $row['completed'];
$ptype			    = $row['type'];
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
$campaignNotes = campaignNotes($pidCampaign, $idGroup);
?>
<table border="0" width="900">
	<tr>
		<td valign="top" colspan=3><?php echo ALLSTATS_142; ?></td>
    </tr>
	<tr>
		<td valign="top" colspan=3><span class="statsLegendEmph"><?php echo ALLSTATS_88; ?>:&nbsp;<?php echo $fidCampaign; if ($pcampaignName) {echo '. '.$pcampaignName;}?></span></td>
	</tr>
    <tr id="details">
        <td valign="top">
            <span class="statsLegend"><?php echo ALLSTATS_4; ?>:</span> <?php echo $pidadmin.'. '.getadminname($pidadmin, $idGroup);?>
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_76;?>:</span> <?php  echo $plistname; if (listDeleted($pidlist, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}?></div>
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_89;?>:</span> <?php echo $ptypeIs?></div>
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_75;?>:</span>
                <?php  if ($pidHtmlNewsletter!="0") {echo '<br>'.$pidHtmlNewsletter.'. '.wordwrap(getNewsletterData($pidHtmlNewsletter, $idGroup, 0),50,"<br>\r\n", true).' (Html)';
                        if (newsletterDeleted($pidHtmlNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                    if ($pidTextNewsletter!="0") {echo '<br>'.$pidTextNewsletter.'. '.wordwrap(getNewsletterData($pidTextNewsletter, $idGroup, 0),50,"<br>\r\n", true).' (Text)';
                        if (newsletterDeleted($pidTextNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                    if (!empty($pemailSubject)) {echo '<br>'.$pemailSubject." ($ptypeIs)".'<br>'.wordwrap($purlToSend,50,"<br>\r\n", true);}?></div>

            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_103; ?>:</span>&nbsp;
            <?php if ($pidSendFilter!=0) {
                 echo '<br>'.$pidSendFilter .'. '.substr(wordwrap($pSendFilterDesc,50,"<br>\r\n", true), 0,100).'...';
                 if (filterDeleted($pidSendFilter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}} else {echo ALLSTATS_94;} ?></div>
      </td>
        <td valign="top">
            <div style="text-align:left;"><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span>&nbsp;<?php if ($pcompleted=="-1") {echo ALLSTATS_93;} else {echo ALLSTATS_94;}?></div>
            <div style="text-align:left;"><span class="statsLegend"><?php echo ALLSTATS_81; ?>:</span>&nbsp;<?php echo $pcounter; ?></div>
            <div style="margin-top:1px">
            <span class="statsLegend"><?php echo ALLSTATS_85;?>:</span> <?php echo $pdateCreated?>
            <br><span class="statsLegend"><?php echo ALLSTATS_851;?>:</span> <?php if ($pdateStarted) { echo $pdateStarted;} else {echo ALLSTATS_94;}?>
            <br><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span> <?php if ($pdateCompleted) { echo $pdateCompleted;} else {echo ALLSTATS_94;}?>
            </div>
            <div style="margin-top:10px"><span class="statsLegend"><?php echo ALLSTATS_119.':</span> '.$pconfirmedIs;?></span><br />
            <span class="statsLegend"><?php echo ALLSTATS_120.':</span> '.$pprefersIs;?></span>
            </div>
        </td>
    </tr>
</table>

<?php //get subscribers that actually exist.
$mySQL1="select t.idEmail, email, name, lastName   FROM
	(SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign) t
	INNER JOIN ".$idGroup."_subscribers on t.idEmail=".$idGroup."_subscribers.idEmail";
$result	= $obj->query($mySQL1);
$rows 	= $obj->num_rows($result);
if (!$rows){
    echo ALLSTATS_109;
} else { ?>
	<br>
	<table border="1">
		<tr>

			<td class="headerCell" style="BORDER-left: #999999 0px solid;">ID</td>
			<td class="headerCell" width=200><?php echo DOSUBSCRIBERSLIST_14; ?></td>
			<td class="headerCell" width=200><?php echo DOSUBSCRIBERSLIST_15; ?></td>

		</tr>
		<?php
	 	while ($row = $obj->fetch_array($result)){
		?>
		<tr>
			<td  class="listingCell" ><?php echo $row['idEmail'];?>&nbsp;</td>
			<td  class="listingCell"><?php echo $row['lastName'];?>, <?php echo $row['name'];?></td>
			<td  class="listingCell"><?php echo $row['email'];?></td>
		</tr>
		<?php } ?>

	</table>
	<br><br>
	<?php
	$obj->free_result($result);
} //we have $rows
$obj->closeDb();
?>