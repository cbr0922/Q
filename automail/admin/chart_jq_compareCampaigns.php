<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include ('adminVerify2.php');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
include ('../inc/dbFunctions.php');
include ('../inc/stringFormat.php');
include ('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();
$groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);
@$lines =  $_GET['lines'];
if (!$lines) {$lines='false';}
@$selectedCampaigns 		= $_GET['selectedCampaigns'];
if (empty($selectedCampaigns)) {return false;}
@$campaigns				= explode(",", $selectedCampaigns);
$campaigns 				= array_reverse($campaigns);
@$Ticked 				= sizeof($campaigns);
//@$thatMany 				= min($Ticked,5);
@$thatMany=$Ticked;
//$noRows=$thatMany;
	$barWidth=10;
	$barPadding=2;
	$barMargin=15;	
if ($thatMany>7) {
	$barWidth=8;
	$barPadding=1;
	$barMargin=8;
}

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
<script language="javascript" type="text/javascript" src="./jqplot/export-jqplot-to-png.js"></script>
<link rel="stylesheet" type="text/css" href="./jqplot/jquery.jqplot.css">
</head>
<body>
<script type="text/javascript" language="javascript">
function putImage() {
 var img = $('#last5c').jqplotToImage(0, 0); 
      if (img) { 
       //open(img.toDataURL("image/png")); 
	   var myImage = img.toDataURL("image/png");
	   document.getElementById("pix").src=myImage;
	   document.getElementById("last5c").style.display='none';
	   document.getElementById("makePic").style.visibility='hidden';
	   document.getElementById("pix").style.display='inline';
	   
      } 
}
</script>
<a href="#" onclick="putImage();return false;"><img id="makePic" border="0" src="./images/makePic.gif" title="<?php echo ALLSTATS_168?>" alt="<?php echo ALLSTATS_168?>" style="float:right;padding-right:20px;"></a>
<a href="#" onclick="window.location.href='chart_jq_compareCampaigns.php?selectedCampaigns=<?php echo $selectedCampaigns?>&lines=true';return false;"><img src="./images/reload2.png" border="0" title="<?php echo ALLSTATS_144?>" alt="<?php echo ALLSTATS_144?>" style="float:right;padding-right:20px;"></a>
<a href="#" onclick="window.location.href='chart_jq_compareCampaigns.php?selectedCampaigns=<?php echo $selectedCampaigns?>&lines=false';return false;"><img src="./images/reload.png" border="0" title="<?php echo ALLSTATS_144?>" alt="<?php echo ALLSTATS_144?>" style="float:right;padding-right:15px;"></a>
<div style="clear:both;"></div>
<div align="center" style="margin-top:10px;BACKGROUND:#fff;padding-top:10px;padding-bottom:10px;border: #999 0px solid; -moz-border-radius: 15px;border-radius:15px;">
<img id="pix" alt="" style="display:none;border:#999 1px solid;margin-top:10px;">
<?php
for ($i=0; $i<$thatMany; $i++)  {
	$pidCampaign = $campaigns[$i];

	$myCmps="SELECT idCampaign, campaignName, idGroup, mailCounter, completed, optedOut, forwarded, bounced FROM ".$idGroup."_campaigns WHERE idCampaign=".$pidCampaign;
	$resultC	= $obj->query($myCmps);
	$noRows 	= $obj->num_rows($resultC);
	//$pidCampaign		= $row['idCampaign'];
	$row = $obj->fetch_array($resultC);
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
    	},
    	legend: {renderer: $.jqplot.horizontalLegendRenderer,rendererOptions:{xoffset:50, yoffset:300}/*location: 'se', placement:'outside', show:true */},
		//legend: {rendererOptions:{xoffset:50, yoffset:30}, location: 'nw', placement:'outside', show:true},
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
            //rendererOptions: {barWidth:18,barPadding:10,barMargin: 22},
			rendererOptions: {barWidth:<?php echo $barWidth?>,barPadding:<?php echo $barPadding?>,barMargin: <?php echo $barMargin?>},
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
			   	//ticks: [<?php foreach ($ticksArray as $value) {echo '\''.fixJSstring(ALLSTATS_88). ' '.$value.'\',';}?>]
				ticks: [<?php foreach ($ticksArray as $value) {echo '\''.$value.'\',';}?>]
             },
              yaxis: {
             	tickOptions: {formatString: '%.0d%n'},	//can switch f with d or use %d, or instead of 1 use 0 for no decimals: same as %d
				/*ticks:[0, 25, 50, 75, 100],*/
				label:'%',
				min: 0/*, max: 100, numberTicks:10, */
			 }
         }
     });

});
</script>
<div id="last5c" align="center" style="background:#fff;margin-top:10px;height:280px;width:860px"></div>

<?php
$obj->closeDb();
?>
</div>

</body>
</html>