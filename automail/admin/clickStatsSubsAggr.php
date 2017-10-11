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

$groupName 	            =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupShowUniqueClicks  =	$obj->getSetting("groupShowUniqueClicks", $idGroup);
$groupNumPerPage 	    =	$obj->getSetting("groupNumPerPage", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$today 					= myDatenow();
$plists                 =  $obj->tableCount_condition($idGroup."_lists", " WHERE idGroup=".$idGroup."");
@$typeOf 				= $_GET['typeOf'];
if ($typeOf=="xl") { include('headerXL.php');}
else {include('header.php');
showMessageBox();
}

$selectedCampaigns 		= $_GET['selectedCampaigns'];
$campaigns 				= explode(",", $selectedCampaigns);
$campaigns 				= array_reverse($campaigns);

$Ticked 				= sizeof($campaigns);
//$thatMany 				= min($Ticked,5);
function campaignsOR($selectedCampaigns, $idGroup) {
    $ORcampaigns="";
    if (!empty($selectedCampaigns)) {
        $campsArray = explode(",", $selectedCampaigns);
        $campaignsCount = sizeof($campsArray);
        if ($campaignsCount!=0) {
            for ($z=0; $z<$campaignsCount; $z++)  {
                $ORcampaigns.="idCampaign=".$campsArray[$z]. ' OR ';
            }
            $ORcampaigns = ' ('.rtrim($ORcampaigns, " OR ").')';
        }
    }
    return $ORcampaigns;
}

$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['records']))?$rowsPerPage = $_GET['records']:$rowsPerPage = $groupNumPerPage;
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
(isset($_GET['linkUrl']))?$plinkurl = base64_decode($_GET['linkUrl']):$plinkurl="";
(isset($plinkurl))?$encodedUrl = base64_encode($plinkurl):$encodedUrl="";

$offset 		= ($page - 1) * $rowsPerPage;
$urlPaging      = "$self?selectedCampaigns=$selectedCampaigns&records=$rowsPerPage&linkUrl=$encodedUrl";
$range=10;

$obj->query("UPDATE ".$idGroup."_groupSettings SET groupNumPerPage=$rowsPerPage WHERE idGroup=$idGroup");

if ($plinkurl) {
    $sqlLink = " AND linkUrl=\"$plinkurl\"";
    $strLink = '<div style="margin-top:7px;margin-bottom:10px;width:650px;"><span class="menu">'.ALLSTATS_78.'</span>:&nbsp;<span style="BACKGROUND: #ffffe0;">'.$plinkurl.'</span></div>';
}
else {
    $sqlLink="";
    $strLink = '<div style="margin-top:7px;margin-bottom:10px;width:650px;"><span class="menu">&nbsp;'.ALLSTATS_117.'</span></div>';
}
?>
 <?php if ($typeOf!="xl") {?>
<script type="text/javascript" language="javascript">
function reloadPage() {
	document.location.href='<?php echo $self?>?records='+$("#records").val()+'&selectedCampaigns=<?php echo $selectedCampaigns?>&linkUrl=<?php echo urlencode($encodedUrl)?>';
}
</script>
<?php } ?>
<div style="float:left;">
	<div><span class="title"><?php echo ALLSTATS_163; ?></span></div>
	<div style="margin-top:10px;"><span class="menu"><?php echo ADMIN_HEADER_47; ?>:</span>&nbsp;<span class="statsLegendEmph"><?php echo $selectedCampaigns?></span><br></div>
	<?php if ($strLink) {?><?php echo $strLink?><?php }?>
</div>
<?php if ($typeOf!="xl") {?><div style="float:right;"><img alt="" src="images/clickstatsusers.png"></div><?php } ?>
<div style="clear:both;"></div>
<?php 

//count the sum of the emails that clicked the newsletter
if ($groupShowUniqueClicks=="-1") {
    //UNIQUE clicks
    $mySQL4="select count(distinct idEmail) as unique_clicks from ".$idGroup."_clickStats WHERE ".campaignsOR($selectedCampaigns, $idGroup) .$sqlLink;
    $clicks= $obj->get_rows($mySQL4);
} else {
    // ALL clicks
    $mySQL5="SELECT count(idEmail) as clicks FROM ".$idGroup."_clickStats WHERE ".campaignsOR($selectedCampaigns, $idGroup) .$sqlLink;
    $clicks=$obj->get_rows($mySQL5);
}
if ($typeOf!="xl") {
?>
<table border="0" width="850" cellpadding="4" cellspacing="0">
    <tr>
        <td valign="top" colspan="3" align="right">
			<?php include('changeRecs.php');?>
		</td>
	</tr>
	<tr>
		<td align="left" valign="top">
			 <?php echo ALLSTATS_140; ?>: <a href="clickStatsSubsAggr.php?typeOf=xl&selectedCampaigns=<?php echo $selectedCampaigns?>&linkUrl=<?php echo urlencode($encodedUrl)?>"><img src="./images/excel.png" border="0" alt="<?php echo ALLSTATS_92; ?>" width="18" height="18"></a>
        </td>
        <td valign="top" align="right" colspan="2">
    		<?php if ($groupShowUniqueClicks=="-1") {?>
	    	<?php echo ALLSTATS_71; ?>&nbsp;<?php echo ALLSTATS_72; ?>.&nbsp;<?php echo ALLSTATS_74; ?>&nbsp;<a href="countUniqueClicks.php?showMsg=0&turn=0&redirect=clicksAggr&selectedCampaigns=<?php echo $selectedCampaigns?>&linkUrl=<?php echo urlencode($encodedUrl)?>"><?php echo ALLSTATS_73; ?></a>
		    <?php } else {?>
		    <?php echo ALLSTATS_71; ?>&nbsp;<?php echo ALLSTATS_73; ?>.&nbsp;<?php echo ALLSTATS_74; ?>&nbsp;<a href="countUniqueClicks.php?showMsg=-1&turn=-1&redirect=clicksAggr&selectedCampaigns=<?php echo $selectedCampaigns?>&linkUrl=<?php echo urlencode($encodedUrl)?>"><?php echo ALLSTATS_72; ?></a>
		    <?php }?>
		</td>
	</tr>
</table>
<br>
<?php
}
//COUNT TOTAL WITH A CHECKI IF SUBSCRIBERS ACTUALLY EXIST=> COUNT USED FOR PAGING
if ($groupShowUniqueClicks==-1) {
	$mySQLcount="SELECT count(distinct ".$idGroup."_clickStats.idEmail) FROM ".$idGroup."_clickStats
	INNER JOIN ".$idGroup."_subscribers ON ".$idGroup."_clickStats.idEmail=".$idGroup."_subscribers.idEmail WHERE ".campaignsOR($selectedCampaigns, $idGroup) .$sqlLink;
} else {
	$mySQLcount="SELECT count(".$idGroup."_clickStats.idEmail) FROM ".$idGroup."_clickStats
	INNER JOIN ".$idGroup."_subscribers ON ".$idGroup."_clickStats.idEmail=".$idGroup."_subscribers.idEmail WHERE ".campaignsOR($selectedCampaigns, $idGroup) .$sqlLink;
}
$rows 	= $obj->get_rows($mySQLcount);
if (!$rows){
    echo "<br><img src='images/warning.png'>&nbsp;".ALLSTATS_109."</b><br>";
}
else {
	 //get subscribers data
	if ($typeOf!="xl") {
	$limitSQL 		= " LIMIT $offset, $rowsPerPage";
	} else {$limitSQL="";}
	if ($groupShowUniqueClicks==-1) {
	    $mySQL1="SELECT distinct ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email, ipClicked as ipOC, max(dateClicked) as dateOC FROM ".$idGroup."_clickStats, ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND ".campaignsOR($selectedCampaigns, $idGroup) .$sqlLink." GROUP BY ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email ORDER BY max(dateClicked) desc ".$limitSQL;
		//removed from GROUP BY: , ipOpened
	} else {
	    $mySQL1="SELECT ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.email, ipClicked as ipOC, dateClicked as dateOC FROM ".$idGroup."_clickStats, ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND ".campaignsOR($selectedCampaigns, $idGroup) .$sqlLink." ORDER BY dateClicked desc ".$limitSQL;
		//removed distinct  from SELECT
	}
	$result	= $obj->query($mySQL1);
	echo '<div align="right" style="margin-right:3px;width:850px"><span class="menu">'.$rows.' '.LISTNEWSLETTERSUBSCRIBERS_12.'</span></div>';
	$maxPage = ceil($rows/$rowsPerPage);
	if ($typeOf!="xl") {
		include('nav.php');
	}
?>
<br><br>
<?php
if ($typeOf!="xl") {
	include('doSubscribersStatsList.php');
} else {
	include('doSubscribersStatsListXL.php');
}	
?>
<br><br>
<?php
	if ($typeOf!="xl") {
		include('nav.php');
	}	
} //we have $rows
//$obj->free_result($result);

$obj->closeDb();
if ($typeOf!="xl") {
	include('footer.php');
}	
?>