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
<!--[if IE]><script language="javascript" type="text/javascript" src="./jqplot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="./jqplot/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplot/jqplot.pieRenderer.min.js"></script>
<link rel="stylesheet" type="text/css" href="./jqplot/jquery.jqplot.css">
</head>
<body style="background-COLOR: #ffffff; FONT-SIZE: 13px; COLOR: #333333; MARGIN: 5px 5px 5px 5px; FONT-FAMILY: Verdana, Arial, Tahoma; TEXT-DECORATION: none;">
<?php
if (!$fidCampaign) {
	echo "No campaign specified.";
}
else {
?>
<div align="center" style="margin-top:0px;BACKGROUND:#fff;padding-top:0px;padding-bottom:0px;border: #999 0px solid; -moz-border-radius: 15px;border-radius:15px;">
<!--span class=chart_title><?php echo ALLSTATS_88.'&nbsp;'.$fidCampaign.': ' .ALLSTATS_154;?></span-->

<?php
//$emailClients defined in inc/clients.php
$AppleMailHits=0;
$logs="";
$entries=0;
$hits=0;
$s1="";
$sum=0;

// Removing distinc gives the same number as all view in the summary report
//$mainSQL = "SELECT idEmail, emailClient, dateOpened FROM ".$idGroup."_viewStats WHERE idCampaign=$fidCampaign";
$mainSQL = "SELECT distinct idEmail, emailClient, dateOpened FROM ".$idGroup."_viewStats WHERE idCampaign=$fidCampaign AND emailClient<>''";
$result	= $obj->query($mainSQL);
$noRows	= $obj->num_rows($result);

if ($noRows<=0){
    echo "<br><br><img src='images/warning.png'>&nbsp;".HOME_22."</b><br>";
}
else {
	while ($row = $obj->fetch_array($result)){
		if (  strripos($row['emailClient'], "Macintosh") && strripos($row['emailClient'], "AppleWebKit") && strripos($row['emailClient'], "iPhone")===false && strripos($row['emailClient'], "iPad")===false && strripos($row['emailClient'], "iPod")===false && strripos($row['emailClient'], "Safari")===false && strripos($row['emailClient'], "Chrome")===false && strripos($row['emailClient'], "Sparrow")===false && strripos($row['emailClient'], "Thunderbird")===false) {
			$AppleMailHits=intval($AppleMailHits)+1;
		}
		$logs.=$row['emailClient'];
	}
?>
<div id="info1b_" style="padding-bottom:20px;padding-top:10px;height:25px;border:#333 0px solid"></div>
<script type="text/javascript">
$(document).ready(function(){
  <?php
  	foreach ($emailClients as $index=>$value) {
		$hits=substr_count($logs, $value);
		if ($hits>0) {
			$s1 .="['".$index."',".$hits."],";
			$entries=$entries+1;
		}
		$sum=$sum+intval($hits);
	 }
	 if ($AppleMailHits>0) {
	 	$s1=$s1."['Apple Mail', ".$AppleMailHits."],";
	 }
	 $s1=$s1."['Browser/Other', ".abs($noRows-$sum-$AppleMailHits)."]";
?>
    s1 = [<?php echo $s1?>];
    plot1 = $.jqplot('chart_div', [s1], {
    	seriesColors:['#FF0000', '#6600FF', '#66CC00', '#FFFF00', '#FF00FF', '#00CCFF',	'#99FF99', '#FFCC00', '#996633', '#FF9999',	'#993300', '#CCCCCC', '#9966CC', '#000000',	'#336633', '#33CCFF'],
        grid: {
            drawBorder: false,
            drawGridlines: false,
            background: '#ffffff',
            shadow:false
        },
        axesDefaults: {

        },
        seriesDefaults:{
            renderer:$.jqplot.PieRenderer,
            rendererOptions: {
            	dataLabelNudge:80 ,
				/*	fill: false, lineWidth: 2, 	*/
                showDataLabels: true,
				dataLabelThreshold: 1,
				sliceMargin: 1,
				diameter:'250'
            }
        },
        legend: {
            show: true,
			placement:"outside",
            rendererOptions: {
                numberRows: 3
            },
            location: 's'
        }
    });

/*	$('#chart_div').bind('jqplotDataHighlight',
		function (ev, seriesIndex, pointIndex, data) {
			//alert(data);return false;
			//$('#info1b').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
			ndata=data. toString();
			var response = ndata.split(",");
			var emclient = response[0];
			var emHits 	 = response[1];
			$('#info1b').html(''+emclient+': '+emHits+' <?php echo LISTNEWSLETTERSUBSCRIBERS_12?>');
		}
	);
	$('#chart_div').bind('jqplotDataUnhighlight',
		function (ev) {
			$('#info1b').html(' ');
		}
	);*/

   $('#chart_div').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, data ) {
            var mouseX = ev.pageX; //these are going to be how jquery knows where to put the div that will be our tooltip
            var mouseY = ev.pageY;
			//$('#info1b').html(ticks_array[pointIndex] + ', ' + data[1]);
			//$('#info1b').show();
			ndata=data. toString(); var response = ndata.split(",");var emclient = response[0];var emHits 	 = response[1];
            $("body").append("<span id='tooltip'></span>");
			$('#tooltip').html(''+emclient+': '+emHits+' <?php echo LISTNEWSLETTERSUBSCRIBERS_12?>');
			var cssObj = {
                  		'position' : 'absolute', 'left' : mouseX + 'px', 'top' : mouseY + 'px', 'background' : '#333', 'color' : '#ffffe0', 'font-weight' : 'normal', 'padding' : '4px'
			};
            $('#tooltip').css(cssObj);
		}
    );

    $('#chart_div').bind('jqplotDataUnhighlight',
        function (ev) {
            //$('#tooltip').html('');
		   $('#tooltip').remove();
        }
    );

});
</script>
<div id="chart_div"></div>
<?php
//echo "<br><br><b>Array sum: </b>".$sum;
} //we have $rows
$obj->free_result($result);
} //when no campaign id.
$obj->closeDb();
?>
</div>
</body></html>