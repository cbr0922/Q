<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 					= new db_class();
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$self 		 = 	$_SERVER['PHP_SELF'];
(isset($_GET['selectedCampaign']))?$fidCampaign = $_GET['selectedCampaign']:$fidCampaign="";
(isset($_GET['idList']))?$fidlist = $_GET['idList']:$fidlist="";
include('header.php');
?>
<script type="text/javascript" language="javascript">
function clearResults() {$("#terms").hide();if ($("#chart_div")) {$("#chart_div").empty();}if ($("#entriesTable")) {$("#entriesTable").empty();}if ($("#chartTitle")) {$("#chartTitle").empty();}}
function reloadPage(url) {document.location.href=url;}
</script>
<table border="0" width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo ALLSTATS_159; ?></span>
		</td>
		<td align=right>
			<Img alt="" src="./images/geomap.png" width="70" height="63">
		</td>
	</tr>
</table>
<div style="width:960px;">

<div style="float:left;width:250px">
	<input type="radio" id="subs3" name="recipients" value="allSubs" checked onclick="$('#campaigns').hide();$('#lists').hide();reloadPage('<?php echo $self?>?selectedCampaign=0&idList=0');"><?php //echo CAMPAIGNCREATE_29?>All subscribers<br>
	<input type="radio" id="subs1" name="recipients" value="byCampaignStats" <?php if ($fidCampaign) {echo " checked";} ?> onclick="$('#campaigns').show();$('#lists').hide();"><?php //echo CAMPAIGNCREATE_28?>By campaign views/clicks stats<br>
	<input type="radio" id="subs2" name="recipients" value="byList" <?php if ($fidlist) {echo " checked";} ?> onclick="$('#lists').show();$('#campaigns').hide();"><?php //echo CAMPAIGNCREATE_11?>By list<br>
</div>
<div style="float:left">
	<div id="campaigns" style="display:<?php if ($fidCampaign) {echo 'block';} else {echo 'none';} ?>">
	<form name="campaignsForm" id="campaignsForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get" >
		<select id="selectedCampaign" name="selectedCampaign" class="select" onchange='reloadPage("<?php echo $self?>?idList=0&selectedCampaign="+$("#selectedCampaign").val()+"");'>
			<option value=0><?php echo strtoupper(SCHEDULERTASKS_26); ?></option>
			<?php
				$mySQL="SELECT distinct idCampaign, campaignName, dateCompleted  FROM ".$idGroup."_campaigns WHERE mailCounter>0 ORDER by idCampaign desc LIMIT 200";
				$result	= $obj->query($mySQL);
		        $rows 	= $obj->num_rows($result);
				if ($rows){
					while ($row = $obj->fetch_array($result)){?>
						<option value="<?php echo $row['idCampaign'];?>" <?php if ($fidCampaign==$row['idCampaign']) {echo ' selected';} ?>><?php echo $row['idCampaign'].'. '.$row['campaignName'];?> [<?php echo FOLLOWUPLIST_20; ?>: <?php echo addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);?>]</option>
					<?php }
					$obj->data_seek($result,0);
				}
				else {?>
					<option value=0><?php echo ALLSTATS_151; ?></option>
			<?php }?>
		</select>
		<br><br>
	</form>
	</div>
	<div id="lists" style="display:<?php if ($fidlist) {echo 'block';} else {echo 'none';} ?>">
	<form name="listsForm" id="listsForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
		<select id="idList" name="idList" class="select" onchange='reloadPage("<?php echo $self?>?selectedCampaign=0&idList="+$("#idList").val()+"");'>
			<?php
			$mySQL="SELECT idList, listName, listDescription, isPublic, lastDateMailed, idGroup FROM ".$idGroup."_lists WHERE ".$idGroup."_lists.idGroup=$idGroup order by idList desc ";
			$result	= $obj->query($mySQL);
		    $rows 	= $obj->num_rows($result);
			if (!$rows){ echo "<option value=0><?php echo strtoupper(LISTS_21); ?></option>";}
			else {
			?>
			<option value=0><?php echo strtoupper(LISTS_12); ?></option>
			<?php while ($row = $obj->fetch_array($result)){ ?>
				<option value="<?php echo $row['idList'];?>" <?php if ($fidlist==$row['idList']) {echo ' selected';} ?>><?php echo $row['idList'];?>. <?php echo $row['listName'];?></option>
			<?php }
			}?>
		</select>
		<br><br>
	</form>
	</div>
</div>
<div style="clear:both"></div>
<div style="margin-top:10px"><input type="reset" class="submit" id="clear" value=" <?php echo CREATESENDFILTER_32; ?>" onclick="clearResults();"></div>

<?php

$mySQL_camp_stats="select count(t.idEmail) as subs, stateName
FROM  (SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$fidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$fidCampaign) t
LEFT JOIN ".$idGroup."_subscribers on t.idEmail=".$idGroup."_subscribers.idEmail
INNER JOIN ".$idGroup."_states on ".$idGroup."_subscribers.state=".$idGroup."_states.stateCode
GROUP BY stateName order by subs desc";

$mySQL_all="SELECT count(idEmail)as subs, stateName FROM ".$idGroup."_subscribers, ".$idGroup."_states
WHERE ".$idGroup."_subscribers.state=".$idGroup."_states.stateCode AND stateCode<>'' GROUP BY stateName";

$mySQL_list="select count(l.idEmail) as subs, stateName
FROM (SELECT idEmail FROM ".$idGroup."_listRecipients WHERE idList=$fidlist) l
LEFT JOIN ".$idGroup."_subscribers on l.idEmail=".$idGroup."_subscribers.idEmail
INNER JOIN ".$idGroup."_states on ".$idGroup."_subscribers.state=".$idGroup."_states.stateCode
GROUP BY stateName order by subs desc";
//INNER instead of LEFT will return subs that have a state value.


if ($fidCampaign) {
	$mySQL=$mySQL_camp_stats;
	$chartTitle = ALLSTATS_157;
}
elseif ($fidlist) {
	$mySQL=$mySQL_list;
		$chartTitle = ALLSTATS_156;
}

else {
	$mySQL=	$mySQL_all;
		$chartTitle = CREATESENDFILTER_40;
}
//echo $mySQL;
$result = $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if ($rows){
  	echo "<div style='margin-top:15px;'><span id='chartTitle' class='menu'>".$chartTitle."</span>";
	echo "&nbsp;&nbsp;&nbsp;<span id='entriesTable'>".ALLSTATS_160.': '.$rows.'</span></div><br><br>';
?>
  <script type='text/javascript' src='http://www.google.com/jsapi'></script>
  <script type="text/javascript" language="javascript">
   google.load('visualization', '1', {'packages': ['geochart']});
   google.setOnLoadCallback(drawMarkersMap);

    function drawMarkersMap() {
      var data = google.visualization.arrayToDataTable([
		['<?php echo EDITSUBSCRIBER_14;?>', '<?php echo ADMIN_HEADER_2;?>'],
      <?php 
      while ($row = $obj->fetch_array($result)) {?>
	  	['<?php echo $row["stateName"]?>', <?php echo $row["subs"]?>],
        <?php
        } ?>
		 ]);
		var options = {
        region: 'US',
		resolution:'provinces',
        //displayMode: 'auto',
        colorAxis: {colors: ['#4E4F6A','#4E4F6A']},
		width: '900',
		height:'500',
		legend:'none'
      };
	var geochart = new google.visualization.GeoChart(document.getElementById('chart_div'));
  	geochart.draw(data, options);
    };
</script>


    <div id='chart_div' align="center"></div>

<script type="text/javascript" language="javascript">
    showBigLoader("chart_div");
	$('#chart_div').bind('load',
	function() {
		$("#terms").show();
		if ($("#bigLoader")) {$("#bigLoader").remove();}
	}
);
</script>

<div id="terms" style"display:none"><a href="http://code.google.com/intl/en-US/apis/terms/index.html" target="_blank">Google APIs Terms of Service</a></div>
<?php
}
else {
  echo '<br><br><img src="./images/warning.png">&nbsp;'.HOME_22;
}?>
</div>
<?php include('footer.php');
$obj->free_result($result);
$obj->closeDb();
?>