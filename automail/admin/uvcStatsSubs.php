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
$offset 		= ($page-1)*$rowsPerPage;
$urlPaging      = "$self?idCampaign=$fidCampaign&amp;records=$rowsPerPage";
$range=10;

$obj->query("UPDATE ".$idGroup."_groupSettings SET groupNumPerPage=$rowsPerPage WHERE idGroup=$idGroup");

//get campaign details
$mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted,
	dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers, notes, ga_utm_source, ga_utm_medium, ga_utm_campaign,
	ga_utm_term, ga_utm_content FROM ".$idGroup."_campaigns WHERE ".$idGroup."_campaigns.idCampaign=$fidCampaign";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
$row 	= $obj->fetch_array($result);
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
		<td valign="top"><span class="title"><?php echo ALLSTATS_142; ?></span></td>
		<td align=right><img alt="0" src="./images/viewstatsusers.png"></td>
	</tr>
</table>
<?php
//COUNT TOTAL WITHOUT CHECKING IF SUBSCRIBERS ACTUALLY EXIST=> STATISTICAL COUNT
$mySQL4="select count(distinct idEmail) as unique_views FROM (SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign) as bam";
$punique_views_clicks= $obj->get_rows($mySQL4);
?>
<table border="0" width="850" cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top" width="40%">
            <span class="statsLegendEmph"><?php echo ALLSTATS_88; ?>:&nbsp;<?php echo $fidCampaign?></span>&nbsp;
		    <?php campaignNotes($pnotes, $pidCampaign);
			if ($ga_utm_source<>"") {google_params($pidCampaign, $GA_array);}
			?>
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="cross" onclick="show_hide_div('details','cross');return false;"><span id="cross" >[+]</span>&nbsp;<?php echo ALLSTATS_30?></a>
        </td>
        <td valign="top" align="right">
			<?php include('changeRecs.php');?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php include('campaignStatsBox.php');?>
		</td>
	</tr>
	<tr>
		<td align="left" valign="top">
            <?php echo ALLSTATS_140; ?>: <a href="uvcStatsSubsXL.php?idCampaign=<?php echo $fidCampaign?>"><img src="./images/excel.png" border="0" width="18" height="18" alt="<?php echo ALLSTATS_92; ?>"></a>
        </td>
        <td align="right">
			<?php echo '<span class="menu">'.formatPercent(($punique_views_clicks/$pcounter),2).'</span>';?>
		</td>
	</tr>
</table>
<br>
<?php
//COUNT TOTAL WITH A CHECKI IF SUBSCRIBERS ACTUALLY EXIST=> COUNT USED FOR PAGING
$mySQLcount="select count(*) FROM
	(SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign) t
	INNER JOIN ".$idGroup."_subscribers on t.idEmail=".$idGroup."_subscribers.idEmail";
$rows 	= $obj->get_rows($mySQLcount);
if (!$rows){
    echo "<br><img src='images/warning.png'>&nbsp;".ALLSTATS_109."</b><br>";
}
else {
	// GET ALSO SUBSCRIBER DETAILS
	$limitSQL 		= " LIMIT $offset, $rowsPerPage";
	$mySQL1="select t.idEmail, email, name, lastName   FROM
	(SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign) t
	INNER JOIN ".$idGroup."_subscribers on t.idEmail=".$idGroup."_subscribers.idEmail".$limitSQL;
	$result	= $obj->query($mySQL1);
	$maxPage = ceil($rows/$rowsPerPage);

	include('nav.php');
	?>
	<br><br>
	<table class="sortable" width="900" border=0  cellpadding="2" cellspacing="0" style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid">
	<thead>
		<tr>
			<td class="nosort leftCorner"></td>
			<td class="number headerCell" style="BORDER-left: #999999 0px solid;">ID</td>
			<td class="text headerCell" width=250><?php echo DOSUBSCRIBERSLIST_14; ?></td>
			<td class="text headerCell" width=250><?php echo DOSUBSCRIBERSLIST_15; ?></td>
			<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_23; ?></td>
			<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_17; ?></td>
			<td class="nosort headerCell" align=center><?php echo DOSUBSCRIBERSLIST_26; ?></td>
			<td class="nosort rightCorner"></td>
		</tr>
	</thead>
	<tbody>
	<?php
 	while ($row = $obj->fetch_array($result)){ ?>
	<tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
		<td  class="listingCell"></td>
		<td  class="listingCell" style="BORDER-left:0px;"><?php echo $row['idEmail'];?></td>
		<td  class="listingCell"><?php echo $row['lastName'];?>, <?php echo $row['name'];?></td>
		<td  class="listingCell"><?php if ($row['email']) {echo $row['email'];} else {echo '<font color=red>'.GENERIC_1.'</font>';}?></td>
		<td  class="listingCell" align=center><a href="statsPerSubscriber.php?idEmail=<?php echo $row['idEmail'];?>"><img src="./images/pie.png"  width="21" height="20" border="0" alt="<?php echo DOSUBSCRIBERSLIST_18; ?>"></a></td>
		<td  class="listingCell" align=center><?php echo subscribedIn($row['idEmail'], $idGroup) .' / ' .$plists?></td>
		<td class="listingCell" align=center style="BORDER-right:0px;"><a href="editSubscriber.php?idEmail=<?php echo $row['idEmail'];?>"><img src="images/edit.png" width="22" height="22" border="0" alt="<?php echo DOSUBSCRIBERSLIST_26; ?>"></a></td>
		<td class="listingCell" style="BORDER-left: #c9c9c9 0px solid; BORDER-right: #c9c9c9 1px solid;"></td> 
	</tr>
	<?php
    }
    ?>
</tbody>
</table>
<br><br>
<?php
include('nav.php');

$obj->free_result($result);
} //we have $rows
} //when no campaign defined
$obj->closeDb();
include('footer.php');
?>