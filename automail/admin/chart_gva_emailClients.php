<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/clients.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);
(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>nuevoMailer - <?php echo ALLSTATS_88.' '.$fidCampaign.': '.ALLSTATS_154?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset;?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body style="background-COLOR: #ffffff; COLOR: #333333; MARGIN: 5px 5px 5px 5px; FONT-FAMILY: Verdana, Arial, Tahoma; TEXT-DECORATION: none;">
<?php
if (!$fidCampaign) {
	echo "No campaign specified.";
}
else {
?>
<div align="center" style="margin-top:0px;BACKGROUND:#fff;padding-top:0px;padding-bottom:0px;border: #999 0px solid; -moz-border-radius: 15px;border-radius:15px;">
<!--span class="title"><?php echo ALLSTATS_88.'&nbsp;'.$fidCampaign.': ' .ALLSTATS_154;?></span-->
<?php
//$emailClients defined in inc/clients.php
$AppleMailHits=0;
$hits=0;
$sum=0;
$logs="";

$mainSQL = "SELECT distinct idEmail, emailClient, dateOpened FROM ".$idGroup."_viewStats WHERE idCampaign=$fidCampaign AND emailClient<>''";
$result	= $obj->query($mainSQL);
$noRows	= $obj->num_rows($result);
if ($noRows<=0){
    echo "<br><br><img src='images/warning.png'>&nbsp;".HOME_22."</b><br>";
}
else {
	while ($row = $obj->fetch_array($result)){	//Apple mail
		if (  strripos($row['emailClient'], "Macintosh") && strripos($row['emailClient'], "AppleWebKit") && strripos($row['emailClient'], "iPhone")===false && strripos($row['emailClient'], "iPad")===false && strripos($row['emailClient'], "iPod")===false && strripos($row['emailClient'], "Safari")===false && strripos($row['emailClient'], "Chrome")===false && strripos($row['emailClient'], "Sparrow")===false && strripos($row['emailClient'], "Thunderbird")===false) {
			$AppleMailHits=intval($AppleMailHits)+1;
		   //echo $row['emailClient']."<br>";
		}
		$logs.=$row['emailClient'];
	}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
//http://code.google.com/apis/chart/interactive/docs/gallery/piechart.html
google.load('visualization', '1.0', {'packages':['corechart']});
google.setOnLoadCallback(drawChart);
function drawChart() {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Email client');
  data.addColumn('number', 'Slices');
  data.addRows([
  <?php
  	foreach ($emailClients as $index=>$value) {
		$hits=substr_count($logs, $value)
  	?>
  	['<?php echo $index?>', <?php echo $hits?>],
	<?php
		$sum=$sum+intval($hits);
	 } 
	 
	 ?>
	 ['Apple Mail', <?php echo $AppleMailHits?>],
	 ['Browser/Other', <?php echo abs($noRows-$sum-$AppleMailHits)?>]
  ]);

  // Set chart options
  var options = {//'title':'<?php echo ALLSTATS_154?>',
                 'width':600,
                 'height':450};
  var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}
</script>
<div id="chart_div"></div>
<br><a href="http://code.google.com/intl/el-GR/apis/terms/index.html" target="_blank" style="font-size:9px;">Google APIs Terms of Service</a>
<?php
} //we have $rows
$obj->free_result($result);
} //when no campaign id.
$obj->closeDb();
?>
</div>

</body></html>