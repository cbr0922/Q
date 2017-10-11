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

$groupShowUniqueClicks=	$obj->getSetting("groupShowUniqueClicks", $idGroup);
(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";

$mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted, dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers FROM ".$idGroup."_campaigns WHERE ".$idGroup."_campaigns.idCampaign=$fidCampaign";
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
?>
<table border="0" width="100%">
	<tr><td colspan=5><?php echo ALLSTATS_108; ?></td></tr>
</table>
<?php //count the sum of the emails that opened the newsletter
if ($groupShowUniqueClicks=="-1") {
    //UNIQUE VIEWS
    $mySQL4="select count(distinct idEmail) as unique_views from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign";
    $pviews= $obj->get_rows($mySQL4);
} else {
    // ALL VIEWS
    $mySQL5="SELECT count(idEmail) as views FROM ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign";
    $pviews=$obj->get_rows($mySQL5);
}
?>
<table border="0" width="900" cellpadding="6" cellspacing="0">
	<tr><td colspan="4"><strong><?php echo ALLSTATS_88?>&nbsp;<?php echo  $pidCampaign; if ($pcampaignName) {echo '. '.$pcampaignName;}?></strong></td></tr>
	<tr>
		<td valign="top"></td>
        <td valign="top">
            <?php if ($groupShowUniqueClicks=="-1") {echo ALLSTATS_141.'&nbsp;';}?><?php echo ALLSTATS_110;?>:&nbsp;<?php echo formatPercent(($pviews/$pcounter),2)?><br />
    		<?php if ($groupShowUniqueClicks=="-1") {?>
	    	<?php echo ALLSTATS_104; ?><?php echo ALLSTATS_72; ?>.
		    <?php } else {?>
		    <?php echo ALLSTATS_104; ?><?php echo ALLSTATS_73; ?>.
		    <?php }?>
		</td>
		<td></td>
		<td></td>

	</tr>
    <tr>
        <td></td>
        <td valign="top">
            <span ><?php echo ALLSTATS_4; ?>:</span> <?php echo $pidadmin.'. '.getadminname($pidadmin, $idGroup);?>
            <div style="margin-top:7px"><span ><?php echo ALLSTATS_76;?>:</span> <?php  echo $plistname; if (listDeleted($pidlist, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}?></div>
            <div style="margin-top:7px"><span ><?php echo ALLSTATS_89;?>:</span> <?php echo $ptypeIs?></div>
            <div style="margin-top:7px"><span ><?php echo ALLSTATS_75;?>:</span>
                <?php  if ($pidHtmlNewsletter!="0") {echo '<br>'.$pidHtmlNewsletter.'. '.wordwrap(getNewsletterData($pidHtmlNewsletter, $idGroup, 0),50,"<br>\r\n", true).' (Html)';
                        if (newsletterDeleted($pidHtmlNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                    if ($pidTextNewsletter!="0") {echo '<br>'.$pidTextNewsletter.'. '.wordwrap(getNewsletterData($pidTextNewsletter, $idGroup, 0),50,"<br>\r\n", true).' (Text)';
                        if (newsletterDeleted($pidTextNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                    if (!empty($pemailSubject)) {echo '<br>'.$pemailSubject." ($ptypeIs)".'<br>'.wordwrap($purlToSend,50,"<br>\r\n", true);}?></div>

            <div style="margin-top:7px"><span ><?php echo ALLSTATS_103; ?>:</span>&nbsp;
            <?php if ($pidSendFilter!=0) {
                 echo '<br>'.$pidSendFilter .'. '.substr(wordwrap($pSendFilterDesc,50,"<br>\r\n", true), 0,100).'...';
                 if (filterDeleted($pidSendFilter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}} else {echo ALLSTATS_94;} ?></div>
      </td>
        <td valign="top">
            <div style="text-align:left;"><span ><?php echo ALLSTATS_852;?>:</span>&nbsp;<?php if ($pcompleted=="-1") {echo ALLSTATS_93;} else {echo ALLSTATS_94;}?></div>
            <div style="text-align:left;"><span ><?php echo ALLSTATS_81; ?>:</span>&nbsp;<?php echo $pcounter; ?></div>
            <div style="margin-top:1px">
            <span ><?php echo ALLSTATS_85;?>:</span> <?php echo $pdateCreated?>
            <br><span ><?php echo ALLSTATS_851;?>:</span> <?php if ($pdateStarted) { echo $pdateStarted;} else {echo ALLSTATS_94;}?>
            <br><span ><?php echo ALLSTATS_852;?>:</span> <?php if ($pdateCompleted) { echo $pdateCompleted;} else {echo ALLSTATS_94;}?>
            </div>
            <div style="margin-top:10px"><span ><?php echo ALLSTATS_119.':</span> '.$pconfirmedIs;?></span><br />
            <span ><?php echo ALLSTATS_120.':</span> '.$pprefersIs;?></span>
            </div>
        </td><td></td>
    </tr>
</table>
<?php //get subscribers
$limitSQL 		= "";
if ($groupShowUniqueClicks==-1) {
    $mySQL1="SELECT distinct ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email, ipOpened as ipOC, max(dateOpened) as dateOC FROM ".$idGroup."_viewStats, ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$fidCampaign  GROUP BY ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email ORDER BY max(dateOpened) desc ".$limitSQL;
} else {
    $mySQL1="SELECT ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email, ipOpened as ipOC, dateOpened as dateOC FROM ".$idGroup."_viewStats, ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$fidCampaign ORDER BY dateOpened desc ".$limitSQL;
}
$result	= $obj->query($mySQL1);
$rows 	= $obj->num_rows($result);
if (!$rows){
    echo "<br><img src='images/warning.png'>&nbsp;".ALLSTATS_109."</b><br>";
} else {
?>
<?php
include('doSubscribersStatsListXL.php');
?>
<?php
} //we have $rows
$obj->free_result($result);
$obj->closeDb();
?>