<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);

include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
include('header.php');
showMessageBox();
(isset($_GET['f']))?$pf = $_GET['f']:$pf="";
(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
if ($fidCampaign) {	$strSQL=" AND idCampaign=$fidCampaign";} else {$strSQL="";}
if	($pf=="-1" || $pf=="0") {$strSQL .= " AND completed=$pf";}

$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$rowsPerPage 	= 20;
$offset 		= ($page - 1) * $rowsPerPage;
$urlPaging      = $self.'?f='.$pf;
$range=10;
$offset 	    = ($page - 1) * $rowsPerPage;
?>
<script type="text/javascript" language="javascript">
function doCount($pidCampaign) {
	var $pidCampaign;
	var url="doCount.php?idCampaign="+$pidCampaign;
	$("#indicator"+$pidCampaign).show();
	$("#countResult"+$pidCampaign).hide();
	$("#countLink"+$pidCampaign).hide();
	$.get(url)
	.done(function(data,status) {
		if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3)?>');
			document.location.href="index.php";
		}
		else {
			showResponse(data, status);
		}
		 })
	.fail(function(data, status) {BadResponse(status); });	
	function showResponse(data, status)	{
  			var response = data.split("#");
			var remaining = response[0];
			var sentSoFar = response[1];
			var lastId    = response[2];
			$("#indicator"+$pidCampaign).hide();
			$("#countLink"+$pidCampaign).show();
			$("#countResult"+$pidCampaign).show();
			$("#countResult"+$pidCampaign).html(remaining);
			$("#countResult"+$pidCampaign).effect( "highlight",{color:"#ffff99"}, 3500 );
            $("#sentSoFar"+$pidCampaign).html(sentSoFar);
 			$("#sentSoFar"+$pidCampaign).effect( "highlight",{color:"#ffff99"}, 3500 );
    	    $("#lastId"+$pidCampaign).html(lastId);
    		$("#lastId"+$pidCampaign).effect( "highlight",{color:"#ffff99"}, 3500 );
	}
	function BadResponse(status) {
	    alert('<?php echo fixJSstring(GENERIC_8)?>');
	    $("#indicator"+$pidCampaign).hide();
	    $("#countResult"+$pidCampaign).hide();
	    $("#countLink"+$pidCampaign).show();
	}
}

function startSending(pidCampaign) {
    openWindow('campaignStart.php?idCampaign='+pidCampaign,700,300);
	$("#mailButton"+pidCampaign).prop("disabled",true);
}
function goToSub(idCampaign) {
    id = $("#lastId"+idCampaign).html();
    document.location.href="editSubscriber.php?idEmail="+id;
}
</script>

<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo ALLSTATS_124; ?></span>
		</td>
		<td align=right>
			<img src="images/campaigns.png" width="65" height="51" alt="<?php echo ALLSTATS_124; ?>">
		</td>
	</tr>
 </table><br>

<?php
$limitSQL 		= " LIMIT $offset, $rowsPerPage";
$mySQL="SELECT * FROM ".$idGroup."_campaigns WHERE idGroup=$idGroup $strSQL ORDER by idCampaign desc".$limitSQL;
//echo $mySQL.'<br><br>';
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);

if (!$rows){echo '<br><img src="./images/warning.png"> '.ALLSTATS_17;}
else {

	$countSQL="SELECT count(idCampaign) from ".$idGroup."_campaigns where idGroup=$idGroup ".$strSQL;
	$numrows=$obj->get_rows($countSQL);
	$maxPage = ceil($numrows/$rowsPerPage);
 echo '<div style="text-align:right;margin-right:20px;margin-bottom:20px;"><span class="menu">'.$numrows.'</span></div>';
			include('nav.php');
?>

<table width="950" style="BORDER-RIGHT: #999999 0px  solid; BORDER-TOP: #6666CC 0px  solid; BORDER-LEFT: #999999 0px  solid; BORDER-BOTTOM: #999999 0px  solid" cellpadding="0" cellspacing="0">
<tbody>
    <tr>
        <td class="leftCorner"></td>
        <td class="headerCell" width="420" style="BORDER-left: #999999 0px solid;text-align:left;"><?php echo ALLSTATS_9; ?></td>
        <td class="headerCell" width="260" style="BORDER-LEFT: #999999 0px  solid;"></td>
        <td class="headerCell" width="250" style="BORDER-left: #c9c9c9 0px solid;"></td>
        <td class="rightCorner"></td>
    </tr>
    <?php
    while ($row = $obj->fetch_array($result)){
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
        switch ($pcounter) {
          case 0:
          $linksWrites = ALLSTATS_132;
          break;
          default:
          $linksWrites = ALLSTATS_131;
          }
        $pcompleted		= $row['completed'];
        $pidstopemail		= $row['idStopEmail'];
        $pmailerror		    = $row['mailError'];
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
		$ga_utm_source	= $row['ga_utm_source'];
		$ga_utm_medium	= $row['ga_utm_medium'];
		$ga_utm_campaign= $row['ga_utm_campaign'];
		$ga_utm_term	= $row['ga_utm_term'];
		$ga_utm_content	= $row['ga_utm_content'];
		$GA_array 		= array("ga_utm_source"=>$ga_utm_source, "ga_utm_medium"=>$ga_utm_medium, "ga_utm_campaign"=>$ga_utm_campaign, "ga_utm_term"=>$ga_utm_term, "ga_utm_content"=>$ga_utm_content);
        $pnotes			= $row['notes'];
		$pfromName		= $row['fromName'];
		$pfromEmail		= $row['fromEmail'];
		$preplyToEmail	= $row['replyToEmail'];
    ?>
    <tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
        <!-- CELL 1 -->
        <td colspan="2" class="listingCellStats" valign="top">
			<?php include('campaignLeftBox.php'); ?>
        </td>
        <!-- CELL 2 -->
        <td class="listingCellStats">
            <div>
                <span class="statsLegend"><?php echo ALLSTATS_4; ?>:</span> <?php echo $pidadmin.'. '.getadminname($pidadmin, $idGroup);?>
            </div>
            <div style="margin-top:7px">
                <span class="statsLegend"><?php echo ALLSTATS_85;?>:</span> <?php echo $pdateCreated?>
                <br><span class="statsLegend"><?php echo ALLSTATS_851;?>:</span> <?php if ($pdateStarted) { echo $pdateStarted;} else {echo ALLSTATS_94;}?>
                <br><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span> <?php if ($pdateCompleted) { echo $pdateCompleted;} else {echo ALLSTATS_94;}?>
            </div>
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_119.':</span> '.$pconfirmedIs;?><br />
                <span class="statsLegend"><?php echo ALLSTATS_120.':</span> '.$pprefersIs;?>
            </div>
            <div style="margin-top:7px">
				<?php
				campaignNotes($pnotes, $pidCampaign);
				if ($ga_utm_source<>"") {google_params($pidCampaign, $GA_array);}?>
			    <a href="clickStats.php?idCampaign=<?php echo $pidCampaign?>"><img src="./images/click.png"  width="18" height="18" border="0" title="<?php echo ALLSTATS_130; ?>"  alt="" style="vertical-align:text-top;"></a>
                &nbsp;&nbsp;<a href="summary.php?idCampaign=<?php echo $pidCampaign?>"><img src="./images/pie.png"  width="21" height="20" border="0" title="<?php echo ALLSTATS_128; ?>" alt="" style="vertical-align:text-top;"></a>
                &nbsp;&nbsp;<a href="#" onclick="openConfirmBox('delete.php?action=campaign&amp;idCampaign=<?php echo $pidCampaign?>','<?php echo fixJSstring(CONFIRM_8)?><br><?php echo fixJSstring(GENERIC_2)?>');return false;"><img style="vertical-align:text-top;" src="./images/delete.png" width="18" height="18" border="0" title="<?php echo ALLSTATS_126; ?>" alt=""></a>
            </div>
        </td>
        <!-- CELL 3 -->
        <td colspan=2 class="listingCellStats" style="BORDER-right: #c9c9c9 1px solid;" valign=top>
            <div><span class="statsLegend"><?php echo ALLSTATS_81; ?>:</span>&nbsp;<span class="statsLegendEmph" id="sentSoFar<?php echo  $pidCampaign?>"><?php echo $pcounter; ?></span></div>
            <div style="margin-top:10px;"><?php if ($pcompleted!="-1" && !listDeleted($pidlist, $idGroup)) {?><span class="statsLegend"><?php echo ALLSTATS_133; ?>:</span>&nbsp;&nbsp;<span id="indicator<?php echo $pidCampaign?>" style="display:none;"><img src="./images/waitSmall.gif" alt=""></span><span id="countLink<?php echo $pidCampaign?>"></span><span id="countResult<?php echo $pidCampaign?>" style="display:none;return false;"></span>&nbsp;&nbsp;<a title="<?php echo ALLSTATS_134;?>" href="#" onclick="doCount('<?php echo $pidCampaign?>');return false;"><img style="vertical-align:bottom;" src="./images/refresh.png" width="15" height="15" border=0 alt=""></a><?php }?></div>
            <div style="margin-top:10px;"><span class="statsLegend"><?php echo ALLSTATS_125.':</span>&nbsp;'; if ($pidstopemail!=0) {?><a href="#" onclick="goToSub(<?php echo $pidCampaign?>);"><?php }?><span id="lastId<?php echo $pidCampaign?>"><?php echo $pidstopemail?></span></a></div>
            <div style="margin-top:10px;">
                <?php
                    if ($pcompleted=="-1")  {echo '<span class="statsLegendEmph">'.ALLSTATS_136.'</span>&nbsp;&nbsp;';?><img onmouseout="hide_info_bubble('cl<?php echo $pidCampaign;?>','0')" onmouseover="infoBox('cl<?php echo $pidCampaign;?>', '<?php echo fixJSstring(ALLSTATS_136)?>', '<?php echo fixJSstring(ALLSTATS_137)?>', '20em', '0'); " src="./images/done.png" height="20" width="20" alt="">
                    <?php  } else if (campaignIsScheduled($pidCampaign, $idGroup)) {echo '<span class="statsLegendEmph">'.ALLSTATS_3.'</span>';?>&nbsp;&nbsp;<img onmouseout="hide_info_bubble('cl<?php echo $pidCampaign;?>','0')" onmouseover="infoBox('cl<?php echo $pidCampaign;?>', '<?php echo fixJSstring(ALLSTATS_3)?>', '<?php echo fixJSstring(ALLSTATS_91)?>', '20em', '0'); " src="./images/calendar.png"  width="16" height="14"  alt="">
                    <?php  }  else if (contentDeleted($ptype,$pidHtmlNewsletter,$pidTextNewsletter,$idGroup) || listDeleted($pidlist, $idGroup)) {echo '<span class="statsLegendEmph">'.ALLSTATS_113.'</span>';?>&nbsp;&nbsp;<img onmouseout="hide_info_bubble('cl<?php echo $pidCampaign;?>','0')" onmouseover="infoBox('cl<?php echo $pidCampaign;?>', '<?php echo fixJSstring(ALLSTATS_113)?>', '<?php echo fixJSstring(ALLSTATS_115)?>', '20em', '0'); " src="./images/stopped.png" height="20" width="20" alt="">
                    <?php  }  else if (!campaignIsScheduled($pidCampaign, $idGroup) && !contentDeleted($ptype,$pidHtmlNewsletter,$pidTextNewsletter,$idGroup) && !listDeleted($pidlist, $idGroup) && $pcompleted!="-1") { ?>
                             <input type="submit" class="submit mailButton" id="mailButton<?php echo $pidCampaign;?>" value="<?php echo $linksWrites;?>" onclick="startSending(<?php echo $pidCampaign;?>)"/><?php } ?>
                    <span style="display: none;" id="cl<?php echo $pidCampaign;?>"></span>
            </div>
            <?php
            //if (!campaignIsScheduled($pidCampaign, $idGroup)) {echo 'yes';};

            if ($pmailerror) {echo '<div style="margin-top:2px;">'.ALLSTATS_127.': '.$pmailerror.'</div>';}?>
       </td
    </tr>
   	<tr>
        <td valign=top colspan=5 >&nbsp;</td>
    </tr>
    <?php } ?>
</tbody>
</table><br />
<?php
include('nav.php');
}
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>