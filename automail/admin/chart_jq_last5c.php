<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include ('adminVerify2.php');
include ('../inc/dbFunctions.php');
include ('../inc/stringFormat.php');
include ('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();
$groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);
@$lines =  $_GET['lines'];
if (!$lines) {$lines='false';}
//echo $lines;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>nuevoMailer - <?php echo HOME_21?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset;?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--[if IE]><script language="javascript" type="text/javascript" src="./jqplot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="./jqplot/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.barRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.categoryAxisRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.pointLabels.min.js"></script><!-- ??? -->
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.horizontalLegendRenderer.js"></script>
<link rel="stylesheet" type="text/css" href="./jqplot/jquery.jqplot.css">
</head>
<body>
<div align="center" style="margin-top:15px;"><!--752F8E-->
<span class="chart_title"><?php echo HOME_21?></span>
<a href="#" onclick="window.location.href='chart_jq_last5c.php?lines=true';return false;"><img src="./images/reload2.png" border="0" title="<?php echo ALLSTATS_144?>" alt="<?php echo ALLSTATS_144?>" style="float:right;padding-right:20px;padding-bottom:0px;margin-top:0px;"></a>
<a href="#" onclick="window.location.href='chart_jq_last5c.php?lines=false';return false;"><img src="./images/reload.png" border="0" title="<?php echo ALLSTATS_144?>" alt="<?php echo ALLSTATS_144?>" style="float:right;padding-right:15px;padding-bottom:0px;margin-top:0px;"></a>

<?php
$myCmps="SELECT * FROM (SELECT idCampaign, mailCounter, optedOut, bounced FROM ".$idGroup."_campaigns WHERE mailCounter>0 order by idCampaign desc LIMIT 5) as t order by idCampaign asc";
$resultC	= $obj->query($myCmps);
$noRows 	= $obj->num_rows($resultC);
//$rowCounter = 0;
if ($noRows) {
while ($row = $obj->fetch_array($resultC)){
	$pidCampaign		= $row['idCampaign'];
	//$pcampaignName		= $row['campaignName'];
	$pcounter		    = $row['mailCounter'];
	$poptedOut 			= $row['optedOut'];
	$pbounced        	= $row['bounced'];
	//UNIQUE CLICKS
	$mySQLc="select count(distinct idEmail) as unique_clicks from ".$idGroup."_clickStats WHERE idCampaign=$pidCampaign";
	$punique_clicks =$obj->get_rows($mySQLc);
	//UNIQUE VIEWS
	$mySQL4="select count(distinct idEmail) as unique_views from ".$idGroup."_viewStats WHERE idCampaign=$pidCampaign";
	$punique_views= $obj->get_rows($mySQL4);
	$ticksArray[$pidCampaign]	= $pidCampaign;
	$viewsArray[$pidCampaign]	= formatDecimals($punique_views/$pcounter);
	$clicksArray[$pidCampaign]	= formatDecimals($punique_clicks/$pcounter);
	$optsArray[$pidCampaign]	= formatDecimals($poptedOut/$pcounter);
	$bounceArray[$pidCampaign]	= formatDecimals($pbounced/$pcounter);
//	$rowCounter=$rowCounter+1;
	$l1="";
	foreach ($viewsArray as $value) {$l1.=$value.', ';}
	$l1=rtrim($l1, ", ");
	$l2="";
	foreach ($clicksArray as $value) {$l2.=$value.', ';}
	$l2=rtrim($l2, ", ");
	$l3="";
	foreach ($optsArray as $value) {$l3.=$value.', ';}
	$l3=rtrim($l3, ", ");
	$l4="";
	foreach ($bounceArray as $value) {$l4.=$value.', ';}
	$l4=rtrim($l4, ", ");
 }?>
 <script  type="text/javascript">
	$(document).ready(function()	{
    $.jqplot.config.enablePlugins = true;
	line1 = [<?php echo $l1;?>];	//open rate
	line2 = [<?php echo $l2;?>];	//clicks
	line3 = [<?php echo $l3;?>];	//opts
	line4 = [<?php echo $l4;?>];	//bounced
	plot5c = $.jqplot('last5c', [line1, line2,line3, line4], {
		grid: {
        drawGridlines: true,        // wether to draw lines across the grid or not.
        gridLineColor: '#E7E7E7',    // *Color of the grid lines.
        background: '#fff',      // CSS color spec for background color of grid.
        borderColor: '#999999',     // CSS color spec for border around grid.
        borderWidth: 0,           // pixel width of border around grid.
        shadow: false,               // draw a shadow for grid.
        shadowAngle: 45,            // angle of the shadow.  Clockwise from x axis.
        shadowOffset: 1.5,          // offset from the line of the shadow.
        shadowWidth: 3,             // width of the stroke for the shadow.
        shadowDepth: 3,             // Number of strokes to make when drawing shadow.// Each stroke offset by shadowOffset from the last.
        shadowAlpha: 0.07           // Opacity of the shadow
		//renderer: $.jqplot.CanvasGridRenderer,               // options to pass to the renderer.  Note, the default
		//rendererOptions: {}                                      // CanvasGridRenderer takes no additional options.
    	},
		/*gridPadding: {top:20, right:10, bottom:10, left:10},*/
    	legend: {renderer: $.jqplot.horizontalLegendRenderer,rendererOptions:{xoffset:0, yoffset:230}/*location: 'se', placement:'outside', show:true */},
        //legend: {location:'se', placement:'outside', show:true},
		//title: '<?php echo HOME_21?>',
 		seriesDefaults: {
			lineWidth:0.9,
			markerOptions: {
	            show: true,       // wether to show data point markers.
	            style: 'circle',  // circle, diamond, square, filledCircle.filledDiamond or filledSquare
	            lineWidth: 2,     // width of the stroke drawing the marker.
	            size: 2,          // size (diameter, edge length, etc.) of the marker.
	            color: '#666666', // color of marker, set to color of line by default.
	            shadow: false,    // wether to draw shadow on marker or not.
	            shadowAngle: 45,  // angle of the shadow.  Clockwise from x axis.
	            shadowOffset: 1,  // offset from the line of the shadow,
	            shadowDepth: 3,   // Number of strokes to make when drawing shadow.  Each stroke
	                              // offset by shadowOffset from the last.
	            shadowAlpha: 0.07 // Opacity of the shadow
			},
        	<?php if ($lines!=='true') { ?>   renderer: $.jqplot.BarRenderer, <?php }?>
			shadow: true,
            rendererOptions: {barWidth:10,barPadding:2,barMargin: 15},
         },
		seriesColors: ["#3366cc","#109618","#DC3912","#FF9900"],
		series:[
        	{label:'<?php echo fixJSstring(ALLSTATS_36)?>'},
        	{label:'<?php echo fixJSstring(ALLSTATS_37)?>'},
        	{label:'<?php echo fixJSstring(ALLSTATS_147)?>'},
			{label:'<?php echo fixJSstring(ALLSTATS_148)?>'}
    	],
         axes: {
             xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				tickOptions:{showGridline:false},
			   	ticks: [<?php foreach ($ticksArray as $value) {echo '\''.fixJSstring(ALLSTATS_88). ' '.$value.'\',';}?>]
             },
             yaxis: {
             	tickOptions: {formatString: '%.0d%n'},	// '%.1f%n' can switch f with d or use %d, or instead of 1 use 0 for no decimals: same as %d
				/*ticks:[0, 25, 50, 75, 100],*/
				label:'%',
				min: 0/*, max: 100, numberTicks:10, */
			 }
         }
     });

});
</script>
<div id="last5c" align="center" style="background:#fff;margin-top:10px;height:250px;width:700px"></div>
<?php
} //rows to make chart
else {
	//echo '<br>['.HOME_22.']';
	echo "<div align='center'><img src='images/warning.png' alt=''>&nbsp;<span style='font-size: 9pt;font-family:Arial,Verdana;'>".HOME_22."</span></div>";
}
$obj->closeDb();
?>
</div>
</body>
</html>