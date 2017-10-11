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
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
$today = date("Y-m-d");
//$mySQL="SELECT max(idCampaign) FROM ".$idGroup."_campaigns WHERE mailCounter>0";
// this is a better query since it allows for one day to elapse since the start of the campaign: questions date>=1 or >1 ?
$mySQL="SELECT idCampaign, date(dateStarted), date(now()) FROM ".$idGroup."_campaigns WHERE mailCounter>0
	AND (date(now())-date(dateStarted))>=1 ORDER BY idCampaign desc LIMIT 1";
$rc	= $obj->query($mySQL);
$row = $obj->fetch_row($rc);
$lastCampaign = $row['0'];
(isset($_GET['idCampaign']))?$thisCampaign = $_GET['idCampaign']:$thisCampaign=$lastCampaign;
(isset($_GET['reload']))?$reload=$_GET['reload']:$reload="y";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>nuevoMailer - <?php echo ALLSTATS_88.' '.$thisCampaign.': '. ALLSTATS_143;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset;?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--[if IE]><script language="javascript" type="text/javascript" src="./jqplot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="./jqplot/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.highlighter.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.cursor.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.dateAxisRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.horizontalLegendRenderer.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.categoryAxisRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.canvasAxisTickRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.canvasTextRenderer.min.js"></script>
<link rel="stylesheet" type="text/css" href="./jqplot/jquery.jqplot.css">
</head>
<body style="background-COLOR: #ffffff; COLOR: #333333; MARGIN: 5px 5px 5px 5px; FONT-FAMILY: Verdana, Arial, Tahoma; TEXT-DECORATION: none;">
<!--div align="center" style="margin-top:35px;"-->
<div align="center" style="margin-top:10px;BACKGROUND:#fff;padding-top:10px;padding-bottom:10px;border: #999 0px solid; -moz-border-radius: 15px;border-radius:15px;">
<!--span class="chart_title"><?php echo ALLSTATS_88.' '.$thisCampaign.': '. ALLSTATS_143;?></span-->
<?php if ($reload=="y") { ?><a href="#" onclick="parent.trendReport();return false;"><img src="./images/refresh.png" border="0" title="<?php echo ALLSTATS_144?>" alt="<?php echo ALLSTATS_144?>" style="float:right;padding-right:20px;padding-bottom:0px;margin-top:0px;"></a><?php } ?>
<!--onclick="window.location.href='chart_jq_trend.php?idCampaign=<?php echo $thisCampaign?>';-->
<?php
if (!$thisCampaign) {
	die('<br>'.HOME_22.'</div></body></html>');
}


$line1="";$line2="";$line3="";
$counter=0;
//select all distinct days from views and clicks stats
$mySQL2="SELECT distinct date(dateOpened) as everyDay FROM ".$idGroup."_viewStats WHERE idCampaign=$thisCampaign
	UNION (SELECT distinct date(dateClicked) as everyDay FROM ".$idGroup."_clickStats WHERE idCampaign=$thisCampaign)
	UNION (SELECT distinct date(dateOptedOut) as everyDay FROM ".$idGroup."_optOutReasons WHERE idCampaign=$thisCampaign) order by everyDay  asc";
//echo $mySQL2;
$result2 	= $obj->query($mySQL2);
$numDays 	= $obj->num_rows($result2);
//echo $numDays;
//die;
if ($numDays) {
	// for each day calculate uclicks, uviews, opts
	while ($row2 = $obj->fetch_array($result2)) {
		$daysArray[$counter]	= $row2["everyDay"];
		$counter++;
	} // loop that picks days and puts them in array.
/*$minDay=$daysArray[0];
$maxDate=$daysArray[$numDays-1];

if ($numDays>20) {	//start plotting 1 day before if we have too many days
	$minDay = strtotime ('-1 day' , strtotime($minDay));
	$minDay = date ('Y-m-d', $minDay);
}
if ($minDay===$maxDate) {
	$maxDate = strtotime ('+1 day' , strtotime($maxDate));
	$maxDate = date ('Y-m-d', $maxDate);
}*/
foreach ($daysArray as $value) {
	$myViews="SELECT count(distinct idEmail) as uviews  FROM ".$idGroup."_viewStats WHERE idCampaign=$thisCampaign AND date(dateOpened)='".$value."'";
	$Vresult 	= $obj->query($myViews);
	while ($Vrow = $obj->fetch_array($Vresult)){
		//$date = date("Y", strtotime($row2["everyDay"])).'-' .(date("m",strtotime($row2["everyDay"]))).'-' .date("d",strtotime($row2["everyDay"]));
		if ($Vrow["uviews"]!=0){$line1.= '[\''.$value.'\','.$Vrow["uviews"].'],';}
	}
	$myClicks="SELECT count(distinct idEmail) as uclicks  FROM ".$idGroup."_clickStats WHERE idCampaign=$thisCampaign AND date(dateClicked)='".$value."'";
	$Cresult 	= $obj->query($myClicks);
	while ($Crow = $obj->fetch_array($Cresult)){
		if ($Crow["uclicks"]!=0){$line2.= '[\''.$value.'\','.$Crow["uclicks"].'],';}
	}
	$myOpts="SELECT count(distinct subscriberEmail) as day_opts FROM ".$idGroup."_optOutReasons WHERE idCampaign=$thisCampaign AND date(dateOptedOut)='".$value."'";
	$Oresult 	= $obj->query($myOpts);
	while ($Orow = $obj->fetch_array($Oresult)){
		if ($Orow["day_opts"]!=0){$line3.= '[\''.$value.'\','.$Orow["day_opts"].'],';}
	}
}
$line1=rtrim($line1, ", ");
$line2=rtrim($line2, ", ");
$line3=rtrim($line3, ", ");
/*
echo "<br>";
echo $line1=rtrim($line1, ", ")."<br>";
echo $line2=rtrim($line2, ", ")."<br>";
echo $line3=rtrim($line3, ", ")."<br>";
die();
*/
?>
 <script  type="text/javascript">
	$(document).ready(function()	{
    $.jqplot.config.enablePlugins = true;
	line1 = [<?php echo $line1;?>];	//open rate
	line2 = [<?php echo $line2;?>];	//clicks
	line3 = [<?php echo $line3;?>];	//opts
	plot3c = $.jqplot('chart1', [line1, line2,line3], {
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


     legend: {renderer: $.jqplot.horizontalLegendRenderer,rendererOptions:{xoffset:10, yoffset:220}/*location: 'se', placement:'outside', show:true */},
        //title: '',
        seriesDefaults: {
			shadow: false,
			lineWidth:0.9,
			markerOptions: {
            show: true,             // wether to show data point markers.
            style: 'circle',  // circle, diamond, square, filledCircle.
                                    // filledDiamond or filledSquare.
            lineWidth: 2,       // width of the stroke drawing the marker.
            size: 2,            // size (diameter, edge length, etc.) of the marker.
            color: '#666666',    // color of marker, set to color of line by default.
            shadow: false,       // wether to draw shadow on marker or not.
            shadowAngle: 45,    // angle of the shadow.  Clockwise from x axis.
            shadowOffset: 1,    // offset from the line of the shadow,
            shadowDepth: 3,     // Number of strokes to make when drawing shadow.  Each stroke
                                // offset by shadowOffset from the last.
            shadowAlpha: 0.07   // Opacity of the shadow
        },

         },
		seriesColors: ["#3366cc", "#109618","#DC3912" ],
		series:[
        	{label:'<?php echo fixJSstring(ALLSTATS_105)?>'},
        	{label:'<?php echo fixJSstring(ALLSTATS_80)?>'},
        	{label:'<?php echo fixJSstring(ALLSTATS_62)?>'}
    	],
		highlighter:{tooltipFadeSpeed:'slow', tooltipLocation:'ne', formatString:'%s - #seriesLabel#: %s'},
		cursor:{zoom:true, showTooltip:false},
      	axes:{
      		xaxis:{
      			renderer:$.jqplot.DateAxisRenderer,
				rendererOptions:{
					tickRenderer:$.jqplot.CanvasAxisTickRenderer
				},
				tickOptions:{
					formatString:'%b %#d, %y',
					//formatString:'%b %#d, %#I %p'
					showGridline:false,
					angle:-30,
					//min:'May 30, 2008',	//loads without it but not good.
			   		tickInterval:'1 week',	//does not load without it
					//autoscale:true
			   	}
			}
			,yaxis: {tickOptions:{formatString:'%.0f'},renderer: $.jqplot.LogAxisRenderer, min:0/*, label:'Subscribers', max: 100, numberTicks:10, ticks:[0, 25, 50, 75, 100], */}
		},


//         axes: {
//             xaxis: {
//				renderer: $.jqplot.DateAxisRenderer,
//		 		rendererOptions:{tickRenderer:$.jqplot.CanvasAxisTickRenderer},
//		 		tickOptions:{showGridline:false, formatString:'%b %#d, %y',angle:-30},
//	            /*min:'<?php //echo $minDay?>',
//				max:'<?php //echo $maxDate?>'*/
//    	        /*tickInterval:'1 week'*/
//
//			   /*	ticks: [
//					php foreach ($ticksArray as $value) {
//					echo '\'Campaign '.$value.'\',';}?>
//				]*/
//             },
//             yaxis: {tickOptions:{formatString:'%.0f'},renderer: $.jqplot.LogAxisRenderer, min:0/*, label:'Subscribers', max: 100, numberTicks:10, ticks:[0, 25, 50, 75, 100], */}
//         }
     });
	 $('.jqplot-highlighter-tooltip').addClass('ui-corner-all');
	   });




</script>

<div id='chart1' style='width: 600px; height: 200px;border:#999 0px solid'></div>
<div style="float:right;border:0px solid;margin-top:15px;margin-right:100px"><button class="reset_button" onclick="plot3c.resetZoom()"><?php echo ALLSTATS_146?></button></div>
<?php
} //rows to make chart
else {
	//echo '<br>['.HOME_22.']';
  	echo "<br><br><img src='images/warning.png'>&nbsp;".HOME_22."</b><br>";
}
$obj->closeDb();
?>
</div>
<script  type="text/javascript">
  $('#loader').hide();
</script>
</body>
</html>