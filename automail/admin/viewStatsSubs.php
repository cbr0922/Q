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

$groupShowUniqueClicks=	$obj->getSetting("groupShowUniqueClicks", $idGroup);
$groupNumPerPage 	  =	$obj->getSetting("groupNumPerPage", $idGroup);

(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
if (!$fidCampaign) {
	echo "No campaign specified.";
}
else {
$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['records']))?$rowsPerPage = $_GET['records']:$rowsPerPage = $groupNumPerPage;
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$offset 		= ($page - 1) * $rowsPerPage;
$urlPaging      = "$self?idCampaign=$fidCampaign&records=$rowsPerPage";
$range=10;

$obj->query("UPDATE ".$idGroup."_groupSettings SET groupNumPerPage=$rowsPerPage WHERE idGroup=$idGroup");

$mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted, dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers, notes, ga_utm_source, ga_utm_medium, ga_utm_campaign, ga_utm_term, ga_utm_content FROM ".$idGroup."_campaigns WHERE ".$idGroup."_campaigns.idCampaign=$fidCampaign";
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
$pnotes			= $row['notes'];
$ga_utm_source	= $row['ga_utm_source'];
$ga_utm_medium	= $row['ga_utm_medium'];
$ga_utm_campaign= $row['ga_utm_campaign'];
$ga_utm_term	= $row['ga_utm_term'];
$ga_utm_content	= $row['ga_utm_content'];
$GA_array 		= array("ga_utm_source"=>$ga_utm_source, "ga_utm_medium"=>$ga_utm_medium, "ga_utm_campaign"=>$ga_utm_campaign, "ga_utm_term"=>$ga_utm_term, "ga_utm_content"=>$ga_utm_content);
?>
<script type="text/javascript" language="javascript">
function reloadPage() {
	document.location.href='<?php echo $self?>?records='+$("#records").val()+'&idCampaign=<?php echo $pidCampaign?>';
}
</script>
<table border="0" width="960px"  cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top"><span class="title"><?php echo ALLSTATS_108; ?></span></td>
		<td align=right><img src="./images/viewstatsusers.png" alt=""></td>
	</tr>
</table>
<?php //COUNT TOTAL WITHOUT CHECKING IF SUBSCRIBERS ACTUALLY EXIST=> STATISTICAL COUNT
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
<table border="0" width="850" cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top" width="40%" colspan="2">
            <span class="statsLegendEmph"><?php echo ALLSTATS_88; ?>:&nbsp;<?php echo $fidCampaign?></span>&nbsp;
            <?php
			campaignNotes($pnotes, $pidCampaign);
			if ($ga_utm_source<>"") {google_params($pidCampaign, $GA_array);}
			?>
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="cross" onclick="show_hide_div('details','cross');return false;"><span id="cross" >[+]</span>&nbsp;<?php echo ALLSTATS_30?></a>
        </td>
        <td valign="top" align="right">
			<?php include('changeRecs.php');?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<?php include('campaignStatsBox.php');?>
		</td>
	</tr>
	<tr>
		<td align="left" valign="top">
			<?php echo ALLSTATS_140; ?>: <a href="viewStatsSubsXL.php?idCampaign=<?php echo $fidCampaign?>"><img src="./images/excel.png" border="0" width="18" height="18" alt="<?php echo ALLSTATS_92; ?>"></a>
        </td>
        <td align="right" colspan="2">
		    <?php if ($pcounter) {echo '<strong>'; if ($groupShowUniqueClicks=="-1") {echo ALLSTATS_141.'&nbsp;';}?><?php echo ALLSTATS_110;?>:</strong>&nbsp;<?php echo formatPercent(($pviews/$pcounter),2);}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    		<?php if ($groupShowUniqueClicks=="-1") {?>
	    	<?php echo ALLSTATS_104; ?>&nbsp;<?php echo ALLSTATS_72; ?>.&nbsp;<?php echo ALLSTATS_74; ?>&nbsp;<a href="countUniqueClicks.php?showMsg=0&turn=0&redirect=views&idCampaign=<?php echo $fidCampaign?>"><?php echo ALLSTATS_73; ?></a>
		    <?php } else {?>
		    <?php echo ALLSTATS_104; ?>&nbsp;<?php echo ALLSTATS_73; ?>.&nbsp;<?php echo ALLSTATS_74; ?>&nbsp;<a href="countUniqueClicks.php?showMsg=-1&turn=-1&redirect=views&idCampaign=<?php echo $fidCampaign?>"><?php echo ALLSTATS_72; ?></a>
		    <?php }?>
		</td>
</tr>
</table>
<br>
<?php
//COUNT TOTAL WITH A CHECKI IF SUBSCRIBERS ACTUALLY EXIST=> COUNT USED FOR PAGING
if ($groupShowUniqueClicks==-1) {
	$mySQLcount="SELECT count(distinct ".$idGroup."_viewStats.idEmail) FROM ".$idGroup."_viewStats
	INNER JOIN ".$idGroup."_subscribers ON ".$idGroup."_viewStats.idEmail=".$idGroup."_subscribers.idEmail WHERE idCampaign=$fidCampaign";
} else {
	$mySQLcount="SELECT count(".$idGroup."_viewStats.idEmail) FROM ".$idGroup."_viewStats
	INNER JOIN ".$idGroup."_subscribers ON ".$idGroup."_viewStats.idEmail=".$idGroup."_subscribers.idEmail WHERE idCampaign=$fidCampaign";
}
$rows 	= $obj->get_rows($mySQLcount);
if (!$rows){
    echo "<br><img src='images/warning.png'>&nbsp;".ALLSTATS_109."</b><br>";
}
else {
	 //get subscribers data
	$limitSQL 		= " LIMIT $offset, $rowsPerPage";
	if ($groupShowUniqueClicks==-1) {
	    $mySQL1="SELECT distinct ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email, ipOpened as ipOC, max(dateOpened) as dateOC FROM ".$idGroup."_viewStats, ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$fidCampaign  GROUP BY ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email ORDER BY max(dateOpened) desc ".$limitSQL;
		//removed from GROUP BY: , ipOpened
	} else {
	    $mySQL1="SELECT ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email, ipOpened as ipOC, dateOpened as dateOC FROM ".$idGroup."_viewStats, ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$fidCampaign ORDER BY dateOpened desc ".$limitSQL;
		//removed distinct  from SELECT
	}
	$result	= $obj->query($mySQL1);
	echo '<div align="right" style="margin-right:3px;width:850px"><span class="menu">'.$rows.' '.LISTNEWSLETTERSUBSCRIBERS_12.'</span></div>';
	$maxPage = ceil($rows/$rowsPerPage);
	include('nav.php');
?>
<br><br>
<?php
include('doSubscribersStatsList.php');
?>
<br><br>
<?php
include('nav.php');
} //we have $rows
$obj->free_result($result);
} //when no campaign id.
$obj->closeDb();
include('footer.php');
?>