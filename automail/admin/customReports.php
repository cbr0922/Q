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
?>
<script type="text/javascript" language="javascript">
function hideAll() {
	$("#errors").hide();
	$("#results1").empty();
	$("#results2").empty();
	$("#chart").empty();
	hideLoader("loader");
	hideLoader("loader2");
}
function  compareCampaigns(charttype) {
   	var selectedC = $("#selectedCampaign").val() || [];
	var howMany = selectedC.length;
	if ($("#selectedCampaign").val()=="" || howMany<2) {
		hideAll();
		$("#errors").show();
		$("#errors").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(ALLSTATS_153)?>');
		return false;
	}
	if (!$('#reportType1').is(':checked') && !$('#reportType2').is(':checked')) {
		hideAll();
		$("#errors").show();
		$("#errors").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(ALLSTATS_167)?>');
		return false;
	}
	if ($('#reportType1').is(':checked')) {url="customCompare.php";}
	if ($('#reportType2').is(':checked')) { url="customAggregate.php";}
  	hideAll();
   	showBigLoader("loader");
	
	if($('#reportType1').is(':checked')) { 
	   $("#compare").prop("disabled",true);
//		if ($("#mergeFrame")) {$("#mergeFrame").remove();}
		$.get(url+"?typeOf=table&selectedCampaigns="+selectedC)
			.done(function(data,status) {
		   		showResponse1(data, status);
		    })
			.fail(function(data, status) {showException(status); });
		$('<iframe>', {src: 'chart_jq_compareCampaigns.php?selectedCampaigns='+selectedC+"&lines="+charttype, id:'chart_jq_compareCampaigns',frameborder:0,scrolling: 'no'}).appendTo('#chart');
		$('#chart_jq_compareCampaigns').bind('load',
			function() {
				$("#chart_jq_compareCampaigns").attr('width', '960');
				$("#chart_jq_compareCampaigns").attr('height', '420');
			}
		);		

	}
	if($('#reportType2').is(':checked')) { 
	$.get(url+"?typeOf=table&selectedCampaigns="+selectedC)
			.done(function(data,status) {
		   		showResponse2(data, status);
		    })
			.fail(function(data, status) {showException(status); });
	}
}

function showResponse1(data, status) {
	if (data=="sessionexpired") {openAlertBox('<?php echo fixJSstring(GENERIC_3)?>','index.php');}
	hideLoader("loader");
	$("#compare").prop("disabled",false);
	$("#results1").html(data);
	$('#comparisonTable').jqTableKit();
}

function showResponse2(data) {
	if (data=="sessionexpired") {openAlertBox('<?php echo fixJSstring(GENERIC_3)?>','index.php');}
	if (data.indexOf("no-links-found") == -1) {//alert("got links");
	}
	hideLoader("loader");
	$("#compare").prop("disabled",false);
	$("#results2").html(data);
	$('#linksTable').jqTableKit();
}

function showException(req) {
	$("#compare").prop("disabled",false);
	alert(req);
}


// **********	 2nd CHART: TREND REPORT **********
function trendReport() {
	if ($("#thisCampaign").val()==0) {return false;}
	url="chart_jq_trend.php";
   	if($('#chart_jq_trend')) {$('#chart_jq_trend').remove();}
   	showBigLoader("loader2");
	$('<iframe>', {src: url+'?idCampaign='+$("#thisCampaign").val(), id:'chart_jq_trend',frameborder:0,scrolling: 'no'}).appendTo('#trendChart');
	$('#chart_jq_trend').bind('load',
			function() {
				$("#chart_jq_trend").attr('width', '850');
				$("#chart_jq_trend").attr('height', '350');
				hideLoader("loader2");
			}
		);		
}
function clearClickResults() {$("#trendChart").empty();$("#bigLoader").remove();}
</script>
<table border="0" width="960px">
	<tr>
		<td valign="top" width="50%">
			<span class="title"><?php echo ADMIN_HEADER_53; ?></span>
		</td>
		<td width="50%"align=right>
			<img src="./images/customreports.png" alt="">
		</td>
	</tr>
</table>
<br><br>
<form name="campaignsForm" id="campaignsForm" onSubmit="return false;" action="">
<table  border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top">
			<span class="menuSmall"><?php echo ALLSTATS_164; ?>:</span>
		</td>
		<td valign="top">
			<select multiple id="selectedCampaign" name="selectedCampaign[]" class=select onchange="return false;">
				<?php
					$mySQL="SELECT distinct idCampaign, campaignName, dateCompleted  FROM ".$idGroup."_campaigns WHERE mailCounter>0 ORDER by idCampaign desc";
					$result	= $obj->query($mySQL);
			        $rows 	= $obj->num_rows($result);
					if ($rows){
						while ($row = $obj->fetch_array($result)){?>
							<option value="<?php echo $row['idCampaign'];?>"><?php echo $row['idCampaign'].'. '.$row['campaignName'];?> [<?php echo FOLLOWUPLIST_20; ?>: <?php echo addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);?>]</option>
						<?php }
						$obj->data_seek($result,0);
					}
					else {?>
						<option value=0><?php echo ALLSTATS_151; ?></option>
				<?php }?>
			</select>
		</td>
		<td valign="top"><img alt="" onmouseover="infoBox('compare_1', '<?php echo fixJSstring(ALLSTATS_153)?>', '<?php echo fixJSstring(ALLSTATS_150)?>', '20em', '0');" onmouseout="hide_info_bubble('compare_1','0')" src="./images/helpSmallWhite.gif"><span style="display: none;" id="compare_1"></span></td>
	</tr>
	<tr>
		<td>
			<span class="menuSmall"><?php echo ALLSTATS_166;?>:</span>
		</td>
		<td colspan="2">
			<input type="radio" id="reportType1" name="customReport" value="comp">&nbsp;	<?php echo ALLSTATS_165;?><br>
			<input type="radio" id="reportType2" name="customReport" value="agr">&nbsp;	<?php echo ALLSTATS_163;?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right">
			<input type="submit" class="submit" id="compare" value=" <?php echo ALLSTATS_39; ?> " onclick="compareCampaigns();return false;">
			<input type="reset" class="submit" id="clear" value=" <?php echo CREATESENDFILTER_32; ?> " onclick="hideAll();">
		</td>
		<td></td>
	</tr>
</table>	
</form>
<div id="errors" class="errormessage" style="display:none;width:450px;margin-top:15px;"></div>
<div id="loader" align="center"></div>
<div id="results1"></div>
<div id="chart" align="center"></div>
<div id="results2"></div>


<!-- SECOND MENU AND CHARTS -->
<br><br><br>
<span class="menu"><?php echo ALLSTATS_88.' '.ALLSTATS_143;?></span>
<br><br>
<form name="trendReportForm" id="trendReportForm" onSubmit="return false;" action="">
<select id="thisCampaign" name="thisCampaign" class="select" onchange="trendReport();return false;">
	<?php
		$mySQL="SELECT distinct idCampaign, campaignName, dateCompleted  FROM ".$idGroup."_campaigns WHERE mailCounter>0 ORDER by idCampaign desc LIMIT 20";
		$result	= $obj->query($mySQL);
        $rows 	= $obj->num_rows($result);
		if ($rows){?>
		<option value="0"><?php echo FOLLOWUPLIST_9?></option>
		<?php	while ($row = $obj->fetch_array($result)){?>
				<option value="<?php echo $row['idCampaign'];?>"><?php echo $row['idCampaign'].'. '.$row['campaignName'];?> [<?php echo FOLLOWUPLIST_20; ?>: <?php echo addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);?>]</option>
			<?php }
		}
		else {?>
			<option value=0><?php echo ALLSTATS_151; ?></option>
	<?php }?>
</select>
<?php //echo $rows;?>
<br><br>
<input type="reset" class="submit" id="clear2" value=" <?php echo CREATESENDFILTER_32; ?> " onclick="clearClickResults();">&nbsp;&nbsp;&nbsp;
</form>
<div id="loader2" align="center"></div>
<div id="trendChart" align="center" style="border: #999 0px solid; -moz-border-radius: 15px;border-radius:15px;"></div>
<?php
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>