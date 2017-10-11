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
if ($fidCampaign) {$strSQL=" WHERE ".$idGroup."_campaigns.idCampaign=$fidCampaign";}
else {$strSQL="";}

$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$rowsPerPage 	= 10;
$urlPaging      = "$self?";
$range=10;
$offset 	    = ($page - 1) * $rowsPerPage;
?>
<script type="text/javascript" language="javascript">
function reloadCampaign(idCampaign) {
	var idCampaign;
	var reportDiv = "#reload"+idCampaign;
	var reportDivHeight=$(reportDiv).height();
	$(reportDiv).hide();
	$("#indicator"+idCampaign).show();
	$("#indicator"+idCampaign).css({height:reportDivHeight})
   	var params="";
	$.get("summaryDetail.php?thisCampaign="+idCampaign)
	.done(function(data,status) {
		if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3)?>');
			document.location.href="index.php";
		}
		else {showRe(data, status);}
	})
	.fail(function(data, status) {showEx(status); });
	function showRe(data,status) 	{
		$("#indicator"+idCampaign).hide();
		$(reportDiv).replaceWith(data);
		$(reportDiv).show();
	}
	function showEx(status)   {alert('<?php echo fixJSstring(GENERIC_8);?>');}
}



function  checkLinkOptions(idCampaign) {
	var idCampaign;
	if ($("#FilterOption"+idCampaign).val()== "6" || $("#FilterOption"+idCampaign).val()== "7") {$("#links"+idCampaign).show();	}
	else {$("#links"+idCampaign).hide();}
	if ($("#FilterOption"+idCampaign).val()!="0") 	{$("#statusmessage"+idCampaign).hide();}
	if ($("#links"+idCampaign).val()!="0") 	{$("#statusmessage"+idCampaign).hide();}
}


function createFollowUp(idCampaign) {
	var idCampaign;
	if ($("#FilterOption"+idCampaign).val()== "0") {
		$("#statusmessage").attr('class', 'errormessage');
		$("#statusmessage"+idCampaign).show();
		$("#statusmessage"+idCampaign).html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(ALLSTATS_33)?>');
		$("#FilterOption"+idCampaign).focus();
		return false;
	}
	else if ( ($("#FilterOption"+idCampaign).val()=="6" || $("#FilterOption"+idCampaign).val()== "7") && $("#links"+idCampaign).val()=="-1") {
		$("#statusmessage").attr('class', 'errormessage');
		$("#statusmessage"+idCampaign).show();
		$("#statusmessage"+idCampaign).html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(ALLSTATS_34)?>');
		$("#links"+idCampaign).focus();
		return false;
	}
	else if ( ($("#FilterOption"+idCampaign).val()== "6" || $("#FilterOption"+idCampaign).val()== "7") && $("#links"+idCampaign).val()=="0") {
		$("#statusmessage").attr('class', 'errormessage');
		$("#statusmessage"+idCampaign).show();
		$("#statusmessage"+idCampaign).html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(ALLSTATS_35)?>');
		$("#links"+idCampaign).focus();
		return false;
	}
	else {
		$("#statusmessage").attr('class', 'waitmessage');
		$("#statusmessage"+idCampaign).show();
		$("#statusmessage"+idCampaign).html('<img src="./images/waitSmall.gif">&nbsp;<?php echo fixJSstring(GENERIC_4)?>');
		var url="summaryExec.php";
		var params="idCampaign="+idCampaign+"&FilterOption="+$('#FilterOption'+idCampaign).val()+"&links="+encodeURIComponent($('#links'+idCampaign).val());
   		$.ajax({
		type: "POST",
		url:url,
		data: params
		}).done(function(data,status) {
			showResponse(data, status);
			})
	  		.fail(function(data, status) {showException(status); });

	}
	function showResponse(data,status) 	{
		if (data=="demo") 		{
			$("#statusmessage").attr('class', 'okmessage'); 
			$("#statusmessage"+idCampaign).show();
			$("#statusmessage"+idCampaign).html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(DEMOMODE_1)?>');
			$("#statusmessage"+idCampaign).effect( "highlight",{color:"#ffff99"}, 3500 );
		}
		else if (data=="sessionexpired") 		{
			alert('<?php echo fixJSstring(GENERIC_3)?>');
			document.location.href="index.php";
		}
		else if (data=="ok") {
			$("#statusmessage").attr('class', 'okmessage');
			$("#statusmessage"+idCampaign).show();
			$("#statusmessage"+idCampaign).html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_34)?>&nbsp;<a href="campaigns.php"><?php echo fixJSstring(CAMPAIGNCREATE_35)?></a>');
			$("#statusmessage"+idCampaign).effect( "highlight",{color:"#ffff99"}, 3500 );
		}
		/*else if (data=="-1")	{	//this option is never used
			$("#statusmessage").attr('class', 'errormessage'); 
			$("#statusmessage"+idCampaign).show();
			$("#statusmessage"+idCampaign).html('<?php echo fixJSstring(GENERIC_8)?>)');
		}

		else if (data=="norecords") {	//this option is never used
			$("#statusmessage").attr('class', 'errormessage'); 
			$("#statusmessage"+idCampaign).show();
			$("#statusmessage"+idCampaign).html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(ALLSTATS_38)?>');
			$("#statusmessage"+idCampaign).effect( "highlight",{color:"#ffff99"}, 3500 );
		}
		else {	//some general error
			$("#statusmessage").attr('class', 'errormessage'); 
			$("#statusmessage"+idCampaign).show();
			$("#statusmessage"+idCampaign).html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(GENERIC_7)?>'+data+'');
		}*/
	}
	function showException(status) 	{
		alert('<?php echo fixJSstring(GENERIC_8)?>');
		$("#statusmessage").attr('class', 'errormessage'); 
		$("#statusmessage"+idCampaign).show();
		$("#statusmessage"+idCampaign).html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(GENERIC_7)?>');
	}
}

</script>
<table border="0" width="960px">
	<tr>
		<td valign="top" width="50%" colspan=2>
			<span class="title"><?php echo ALLSTATS_1; ?></span>
            <span style="vertical-align:bottom;"><?php if ($fidCampaign) { echo '&nbsp;&nbsp;&nbsp;'.ALLSTATS_88.': '.$fidCampaign;} else {echo '&nbsp;&nbsp;&nbsp;'.ALLSTATS_90;}?></span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img style="vertical-align:bottom;" onclick="infoBox('summary_0', '<?php echo fixJSstring(ALLSTATS_31)?>', '<?php echo fixJSstring(ALLSTATS_32)?>', '50em', '1'); "  title="<?php echo GENERIC_6; ?>" src="./images/helpSmallWhite.gif" alt=""><span style="display:none;" id="summary_0"></span>
            <br />

		</td>
		<td width="50%" colspan="2" align=right>
			<img src="./images/summary.png" alt="">
		</td>
	</tr>

    <?php
    $limitSQL 		= " LIMIT $offset, $rowsPerPage";
    $mySQL="SELECT idCampaign, campaignName, idAdmin, idGroup, idList, listName, idHtmlNewsletter, idTextNewsletter, urlToSend, emailSubject, dateCreated, dateStarted, dateCompleted, mailCounter, completed, type, idSendFilter, confirmed, prefers, optedOut, forwarded, bounced, notes, ga_utm_source, ga_utm_medium, ga_utm_campaign, ga_utm_term, ga_utm_content FROM ".$idGroup."_campaigns $strSQL ORDER by ".$idGroup."_campaigns.idCampaign desc".$limitSQL;
    //echo $mySQL.'<br><br>';
    $result	= $obj->query($mySQL);
    $rows 	= $obj->num_rows($result);

    if (!$rows){
        echo '<tr><td colspan=2><br><img src="./images/warning.png"> '.ALLSTATS_17;
        if (!$rows && $fidCampaign) {echo '&nbsp;'.ALLSTATS_87.'<br />';}
        echo '</td></tr></table>';
    } else {
    ?>
	<tr>
		<td colspan=2>
            <a href="#" class="cross" onclick="show_hide_div('ratios','cross');return false;"><span id="cross" >[+]</span>&nbsp;<?php echo ALLSTATS_8;?></a>
        </td>
		<td colspan=2 align=left></td>

    </tr>
    <tr id="ratios" style="display:none;" valign=middle>
        <td colspan=4>
        <table width="100%">
        <tr>
            <td width="17%"><a href="#"  class="cross" onclick="show_hide_div('uViewsClicks', 'scross1');return false;"><span id="scross1">[+]</span>&nbsp;<?php echo fixJSstring(ALLSTATS_64)?></a></td>
            <td width="17%"><a href="#"  class="cross" onclick="show_hide_div('ClicksViews', 'scross6');return false;"><span id="scross6">[+]</span>&nbsp;<?php echo fixJSstring(ALLSTATS_161)?></a></td>
			<td width="17%"><a href="#"  class="cross" onclick="show_hide_div('uViews', 'scross2');return false;"><span id="scross2">[+]</span>&nbsp;<?php echo fixJSstring(ALLSTATS_105)?></a></td>
            <td width="17%"><a href="#"  class="cross" onclick="show_hide_div('uClicks', 'scross3');return false;"><span id="scross3">[+]</span>&nbsp;<?php echo fixJSstring(ALLSTATS_80)?></a></td>
            <td width="16%"><a href="#"  class="cross" onclick="show_hide_div('tViews', 'scross4');return false;"><span id="scross4">[+]</span>&nbsp;<?php echo fixJSstring(ALLSTATS_106)?></a></td>
            <td width="16%"><a href="#"  class="cross" onclick="show_hide_div('tClicks', 'scross5');return false;"><span id="scross5">[+]</span>&nbsp;<?php echo fixJSstring(ALLSTATS_79)?></a></td>
       </tr>
       <tr>
            <td valign="top"><div id="uViewsClicks" style="padding-top:10px; display:none;"><?php echo fixJSstring(ALLSTATS_65)?></div></td>
			<td valign="top"><div id="ClicksViews" style="padding-top:10px; display:none;"><?php echo fixJSstring(ALLSTATS_162)?></div></td>
            <td valign="top"><div id="uViews" style="padding-top:10px;padding-left:7px; BORDER-left: #c9c9c9 1px dashed;display: none;"><?php echo fixJSstring(ALLSTATS_14)?></div></td>
            <td valign="top"><div id="uClicks" style="padding-top:10px;padding-left:7px; BORDER-left: #c9c9c9 1px dashed;display: none;" ><?php echo fixJSstring(ALLSTATS_12)?></div></td>
            <td valign="top"><div id="tViews"style="padding-top:10px;padding-left:7px; BORDER-left: #c9c9c9 1px dashed;display: none;"><?php echo fixJSstring(ALLSTATS_20)?></div></td>
            <td valign="top"><div id="tClicks"style="padding-top:10px;padding-left:7px; BORDER-left: #c9c9c9 1px dashed;display: none;"><?php echo fixJSstring(ALLSTATS_67)?></div></td>
       </tr>
       </table>
       </td>
   </tr>
</table>
<br />
<?php
	$countSQL="SELECT count(idCampaign) from ".$idGroup."_campaigns ".$strSQL;
	$numrows=$obj->get_rows($countSQL);
	$maxPage = ceil($numrows/$rowsPerPage);
    include('nav.php');
?>
<br>
<table width="950" style="BORDER-RIGHT: #c9c9c9 0px  solid; BORDER-TOP: #c9c9c9 0px  solid; BORDER-LEFT: #c9c9c9 0px  solid; BORDER-BOTTOM: #c9c9c9 0px  solid" cellpadding="0" cellspacing="0">
	<tbody>
        <tr>
            <td class="leftCorner"></td>
			<td class="headerCell" width="310" style="BORDER-left: #c9c9c9 0px solid;text-align:left;"><?php echo ALLSTATS_9; ?></td>
            <td class="headerCell" width="260" style="BORDER-LEFT: #c9c9c9 0px  solid;"></td>
            <td class="headerCell" width="360" ><?php echo ALLSTATS_8;?></td>
			<td class="rightCorner"></td>
		</tr>
			<?php
            while ($row = $obj->fetch_array($result)){
                $pidCampaign		= $row['idCampaign'];
				 include('summaryDetail.php'); ?>

<!-- OPTIONS ROW STARTS-->
	   	    <tr>
    			<td  height="40" valign="top" colspan="5" class="listingCellStats" style="BORDER-bottom: #c9c9c9 0px solid;BORDER-top: #c9c9c9 0px solid;BORDER-right: #c9c9c9 0px solid;BORDER-left: #c9c9c9 0px solid;">
          	        <!-- FOLLOW UP MENUS START-->
                    <?php if ($pcompleted=="-1" && !listDeleted($pidlist, $idGroup) && !contentDeleted($ptype,$pidHtmlNewsletter,$pidTextNewsletter,$idGroup)) {
                            //==>show the follow-up menu  ?>
			        <div id="campaignRowFU<?php echo $pidCampaign?>">
                        <a href="#" class="cross" onclick="show_hide_div('followupoptions<?php echo $pidCampaign?>','cross<?php echo $pidCampaign?>');return false;"><span id="cross<?php echo $pidCampaign?>">[+]</span>&nbsp;<?php echo ALLSTATS_40.' - '.ALLSTATS_88.'&nbsp;'.$pidCampaign;?></a>
				        <div id="followupoptions<?php echo $pidCampaign?>" style="display:none">
        					<?php echo ALLSTATS_51; ?>&nbsp;
        					<select id="FilterOption<?php echo $pidCampaign?>" name="FilterOption" class=select onchange="checkLinkOptions('<?php echo $pidCampaign?>');">
        					<option value=0><?php echo ALLSTATS_41; ?></option>
        					<option value=1><?php echo ALLSTATS_42; ?></option>
        					<option value=2><?php echo ALLSTATS_43; ?></option>
        					<option value=3><?php echo ALLSTATS_44; ?></option>
        					<option value=4><?php echo ALLSTATS_45; ?></option>
        					<option value=5><?php echo ALLSTATS_46; ?></option>
        					<option value=6><?php echo ALLSTATS_47; ?></option>
        					<option value=7><?php echo ALLSTATS_48; ?></option>
        					</select> &nbsp;<input type="submit" class="submit" value="<?php echo ALLSTATS_39; ?>" onclick="createFollowUp('<?php echo $pidCampaign?>');">
        					<?php   //get campaign clicked links
        					$mySQL6="SELECT distinct idLink, linkUrl FROM ".$idGroup."_clickStats WHERE idGroup=$idGroup AND idCampaign=$pidCampaign";
                            $result6	= $obj->query($mySQL6);
                            $rows6 	= $obj->num_rows($result6);
                            if ($rows6){
        					?>	<br />
		    				<select id="links<?php echo $pidCampaign?>" name="links" class=select style="display:none;margin-top:10px;" onchange="checkLinkOptions('<?php echo $pidCampaign?>');">
			        			<option value=0><?php echo ALLSTATS_49; ?></option>
					        	<?php  while ($row6 = $obj->fetch_array($result6)){ ?>
						        <option value="<?php echo $row6['idLink'].'@'.$row6['linkUrl']; ?>"><?php echo $row6['linkUrl']; ?></option>
						        <?php } ?>
						    </select>
					        <?php } else {?><br />
						    <select id="links<?php echo $pidCampaign?>" name="links" class=select style="display:none" onchange="checkLinkOptions('<?php echo $pidCampaign?>');">
						        <option value=-1><?php echo ALLSTATS_50; ?></option>
						    </select>
					        <?php } ?>
    				        <div id="statusmessage<?php echo $pidCampaign?>" class="okmessage" style="display:none;text-align:left;"></div>
				        </div>
                    </div><!--script type="text/javascript" language="javascript">//new Draggable($("campaignRowFU<?php echo $pidCampaign?>"), {revert:false} );</script-->
                <?php } //==>END the follow-up menu?>
			    </td>
		    </tr>
<!-- OPTIONS ROW ENDED-->




<?php			 } ?>
	</tbody>
</table>
<?php
include('nav.php');
} //we have $rows
echo "<br>";
$obj->free_result($result);
//$obj->free_result($result1);
$obj->closeDb();
include('footer.php');
?>