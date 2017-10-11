<?php
//nuevoMailer v.1.30
//Copyright 2010 Panagiotis Chourmouziadis
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
(isset($_GET['region']))?$fregion = $_GET['region']:$fregion="world";
include('header.php');
?>
<script type="text/javascript" language="javascript">
function clearResults() {$("#terms").hide();if ($("#chart_div")) {$("#chart_div").empty();}if ($("#entriesTable")) {$("#entriesTable").empty();}if ($("#chartTitle")) {$("#chartTitle").empty();}}
function reloadPage(url) {document.location.href=url;}
</script>
<table border="0" width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo ALLSTATS_155; ?></span>
		</td>
		<td align=right>
			<Img alt="" src="./images/geomap.png" width="70" height="63">
		</td>
	</tr>
</table>
<div style="width:960px;">

<div style="float:left;width:250px">
	<input type="radio" id="subs3" name="recipients" value="allSubs" checked onclick="$('#campaigns').hide();$('#lists').hide();reloadPage('<?php echo $self?>?selectedCampaign=0&idList=0');"><?php echo CREATESENDFILTER_40?><br>
	<input type="radio" id="subs1" name="recipients" value="byCampaignStats" <?php if ($fidCampaign) {echo " checked";} ?> onclick="$('#campaigns').show();$('#lists').hide();"><?php echo ALLSTATS_157?><br>
	<input type="radio" id="subs2" name="recipients" value="byList" <?php if ($fidlist) {echo " checked";} ?> onclick="$('#lists').show();$('#campaigns').hide();"><?php echo ALLSTATS_156?><br>
</div>
<div style="float:left">
	<div id="campaigns" style="display:<?php if ($fidCampaign) {echo 'block';} else {echo 'none';} ?>">
	<form name="campaignsForm" id="campaignsForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get" >
		<select id="selectedCampaign" name="selectedCampaign" class="select" onchange='reloadPage("<?php echo $self?>?idList=0&selectedCampaign="+$("#selectedCampaign").val()+"&region="+$("#region").val()+"");'>
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
		<select id="idList" name="idList" class="select" onchange='reloadPage("<?php echo $self?>?selectedCampaign=0&idList="+$("#idList").val()+"&region="+$("#region").val()+"");'>
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
<div style="float:left;margin-left:8px;">
<select id="region" name="region" class="select" onchange='reloadPage("<?php echo $self?>?idList="+$("#idList").val()+"&selectedCampaign="+$("#selectedCampaign").val()+"&region="+$("#region").val()+"");'>
<option value="world" <?php if ($fregion=='world') {echo ' selected';} ?>>World</option>
<option value="019" <?php if ($fregion=='019') {echo ' selected';} ?>>AMERICAS</option>
<option value="021" <?php if ($fregion=='021') {echo ' selected';} ?>>Northern America</option>
<option value="013" <?php if ($fregion=='013') {echo ' selected';} ?>>Central America</option>
<option value="005" <?php if ($fregion=='005') {echo ' selected';} ?>>South America</option>
<option value="029" <?php if ($fregion=='029') {echo ' selected';} ?>>Caribbean</option>
<option value="150" <?php if ($fregion=='150') {echo ' selected';} ?>>EUROPE</option>
<option value="154" <?php if ($fregion=='154') {echo ' selected';} ?>>Northern Europe</option>
<option value="155" <?php if ($fregion=='155') {echo ' selected';} ?>>Western Europe</option>
<option value="039" <?php if ($fregion=='039') {echo ' selected';} ?>>Southern Europe</option>
<option value="151" <?php if ($fregion=='151') {echo ' selected';} ?>>Eastern Europe</option>
<option value="002" <?php if ($fregion=='002') {echo ' selected';} ?>>AFRICA</option>
<option value="015" <?php if ($fregion=='015') {echo ' selected';} ?>>Northern Africa</option>
<option value="017" <?php if ($fregion=='017') {echo ' selected';} ?>>Central Africa</option>
<option value="018" <?php if ($fregion=='018') {echo ' selected';} ?>>Southern Africa</option>
<option value="011" <?php if ($fregion=='011') {echo ' selected';} ?>>Western Africa</option>
<option value="014" <?php if ($fregion=='014') {echo ' selected';} ?>>Eastern Africa</option>
<option value="142" <?php if ($fregion=='142') {echo ' selected';} ?>>ASIA</option>
<option value="143" <?php if ($fregion=='143') {echo ' selected';} ?>>Central Asia</option>
<option value="030" <?php if ($fregion=='030') {echo ' selected';} ?>>Eastern Asia</option>
<option value="034" <?php if ($fregion=='034') {echo ' selected';} ?>>Southern Asia</option>
<option value="035" <?php if ($fregion=='035') {echo ' selected';} ?>>South-Eastern Asia</option>
<option value="145" <?php if ($fregion=='145') {echo ' selected';} ?>>Western Asia</option>
<option value="009" <?php if ($fregion=='009') {echo ' selected';} ?>>Oceania</option>
<option value="053" <?php if ($fregion=='053') {echo ' selected';} ?>>Australia and New Zealand</option>
<option value="054" <?php if ($fregion=='054') {echo ' selected';} ?>>Melanesia</option>
<option value="057" <?php if ($fregion=='057') {echo ' selected';} ?>>Micronesia</option>
<option value="061" <?php if ($fregion=='061') {echo ' selected';} ?>>Polynesia</option>



</select>
</div>
<div style="clear:both"></div>
<div style="margin-top:10px"><input type="reset" class="submit" id="clear" value=" <?php echo CREATESENDFILTER_32; ?>" onclick="clearResults();"></div>

<?php

$mySQL_camp_stats="select count(t.idEmail) as subs, countryName
FROM  (SELECT idEmail FROM ".$idGroup."_clickStats WHERE idCampaign=$fidCampaign UNION SELECT idEmail from ".$idGroup."_viewStats WHERE idCampaign=$fidCampaign) t
LEFT JOIN ".$idGroup."_subscribers on t.idEmail=".$idGroup."_subscribers.idEmail
INNER JOIN ".$idGroup."_countries on ".$idGroup."_subscribers.country=".$idGroup."_countries.countryCode
GROUP BY countryName order by subs desc";

$mySQL_all="SELECT count(idEmail)as subs, countryName FROM ".$idGroup."_subscribers, ".$idGroup."_countries WHERE ".$idGroup."_subscribers.country=".$idGroup."_countries.countryCode AND countryCode<>'' GROUP BY countryName";
//$mySQL_all="SELECT count(idEmail)as subs, country FROM ".$idGroup."_subscribers WHERE country<>'' GROUP BY countryCode";

$mySQL_list="select count(l.idEmail) as subs, countryName
FROM (SELECT idEmail FROM ".$idGroup."_listRecipients WHERE idList=$fidlist) l
LEFT JOIN ".$idGroup."_subscribers on l.idEmail=".$idGroup."_subscribers.idEmail
INNER JOIN ".$idGroup."_countries on ".$idGroup."_subscribers.country=".$idGroup."_countries.countryCode
GROUP BY countryName order by subs desc";
//INNER instead of LEFT will return subs that have a country value.


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
$result = $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if ($rows){
  	echo "<div style='margin-top:15px;'><span id='chartTitle' class='menu'>".$chartTitle."</span></div>";
	if ($fregion=='world') {
		echo "<div><span id='entriesTable'>".ALLSTATS_158.': '.$rows.'</span></div><br>';
	}
?>
  <script type='text/javascript' src='http://www.google.com/jsapi'></script>
  <script type="text/javascript" language="javascript">
   google.load('visualization', '1', {'packages': ['geochart']});
   google.setOnLoadCallback(drawRegionsMap);

    function drawRegionsMap() {
      var data = google.visualization.arrayToDataTable([
    	['<?php echo EDITSUBSCRIBER_15?>', '<?php echo ADMIN_HEADER_2?>'],  
		<?php while ($row = $obj->fetch_array($result)) {?>
        ['<?php echo $row["countryName"]?>', <?php echo $row["subs"]?>],
        <?php } ?>
		 ]);
		var options = {
        region: '<?php echo $fregion?>',
        displayMode: 'auto',
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