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
$dateFormatsorter=dateSorter($groupDateTimeFormat);
include('header.php');
showMessageBox();
(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
if ($fidCampaign) {$strSQL=" WHERE ".$idGroup."_optOutReasons.idCampaign=$fidCampaign";}
else {$strSQL="";}

$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
(isset($_GET['records']))?$rowsPerPage = $_GET['records']:$rowsPerPage = $obj->getSetting("groupNumPerPage", $idGroup);
$obj->query("UPDATE ".$idGroup."_groupSettings SET groupNumPerPage=$rowsPerPage WHERE idGroup=$idGroup");

$urlPaging      = "$self?idCampaign=$fidCampaign";
$range=10;
$offset 	    = ($page - 1) * $rowsPerPage;
?>
<script type="text/javascript" language="javascript">
function reloadPage() {
	document.location.href='<?php echo $self?>?records='+$("#records").val()+'&idCampaign=<?php echo $fidCampaign?>';
}
</script>
<table border="0" width="960px" cellspacing="0" cellpadding="4">
	<tr>
		<td width=60% valign="top">
			<span class="title"><?php echo LISTREASONS_2;if ($fidCampaign) {echo '&nbsp;'.LISTREASONS_15.'&nbsp;'.$fidCampaign;}?></span>
			<br><br>
			<a href="#" class="cross" onclick="show_hide_div('about','cross1'); return false;"><span id="cross1">[+]</span>&nbsp;<?php echo GENERIC_15; ?></a>
			<div id="about" style="display:none;margin-top:12px;margin-bottom:12px;"><?php echo LISTREASONS_3; ?><br></div>
		</td>
		<td valign="top">
			<div align=right><img src="./images/angry.png" width="65" height="51"></div>
		</td>
	</tr>
	<?php
    $limitSQL 		= " LIMIT $offset, $rowsPerPage";
	$mySQL="SELECT subscriberEmail, optOutReason, dateOptedOut, optOutType FROM ".$idGroup."_optOutReasons $strSQL ORDER BY dateOptedOut desc $limitSQL";
    //echo $mySQL.'<br><br>';
    $result	= $obj->query($mySQL);
    $rows 	= $obj->num_rows($result);
    if (!$rows){
        echo "<tr><td colspan=2><img src='./images/warning.png'>".LISTREASONS_1."</td></tr></table>";
	} else {
		$countSQL="SELECT count(*) from ".$idGroup."_optOutReasons ".$strSQL;
    	$numrows=$obj->get_rows($countSQL);
	    $maxPage = ceil($numrows/$rowsPerPage);
      ?>
      <tr>
		<td>
			<?php echo LISTREASONS_11; ?>: <a href=# onclick="openConfirmBox('delete.php?action=optouts','<?php echo fixJSstring(LISTREASONS_14)?><br><?php echo fixJSstring(GENERIC_2)?>');return false;"><img border=0 src="./images/delete.png" width="18" height="18"></a>
			&nbsp;&nbsp;<?php echo LISTREASONS_12; ?>: <a href="optOutsXL.php?idCampaign=<?php echo $fidCampaign?>"><img src="./images/excel.png" border="0" alt="" width="18" height="18"></a>
        </td>
        <td>
			<?php include('changeRecs.php');?>
		</td>
	</tr>
    <tr  height=50 valign="bottom"><td><?php include('nav.php');?></td><td align="right"><span class="menu"><?php echo $numrows;?></span></td></tr>
</table>
<table class="sortable" style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid" width="870" cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<td class="nosort leftCorner"></td>
		<td class="text headerCell" style="BORDER-left: #999999 0px solid;" width=200><?php echo LISTREASONS_4; ?></td>
		<td class="text headerCell" width=150><?php echo LISTREASONS_8; ?></td>
        <td class="<?php echo $dateFormatsorter?> headerCell" width=170><?php echo LISTREASONS_7; ?></td>
		<td class="text headerCell" width=350><?php echo LISTREASONS_5; ?></td>
		<td class="nosort headerCell" width=80 align=center><?php echo LISTREASONS_6; ?></td>
		<td class="nosort rightCorner"></td>
	</tr>
</thead>
<tbody>
	<?php
 	 while ($row = $obj->fetch_array($result)){

		$psubscriberEmail   = $row['subscriberEmail'];
		$pReason		    = $row['optOutReason'];
		$poptOutType		= $row['optOutType'];
        $pDate              = addOffset($row['dateOptedOut'], $pTimeOffsetFromServer, $groupDateTimeFormat);

		$mySQL2="SELECT idEmail FROM ".$idGroup."_subscribers WHERE email='$psubscriberEmail'";
        //echo $mySQL2;
        $result2	= $obj->query($mySQL2);
		if ($result2) {
         		$sub = $obj->fetch_array($result2);
			$pidemail = $sub['idEmail'];
		} else {
			$pidemail = "";
		}

	?>
	<tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
		<td class="listingCell"><a href=# onclick="openConfirmBox('delete.php?action=optoutemail&email=<?php echo $psubscriberEmail?>','<?php echo fixJSstring(LISTREASONS_16).': '.$psubscriberEmail;?><br><?php echo fixJSstring(GENERIC_2)?>');return false;"><img border=0 src="./images/delete.png" width="12" height="12"></a></td>
		<td class="listingCell"><?php echo $psubscriberEmail?></td>
		<td class="listingCell">
			<?php
			if ($poptOutType) {
				switch ($poptOutType) {
				case "g":
					$pWriteOptOut = LISTREASONS_9;break;
				//case else
                 default:
					$pWriteOptOut = LISTREASONS_10.": ".$poptOutType;
				}
				echo $pWriteOptOut;
			}
			?>
        </td>
        <td class="listingCell"><?php echo $pDate?></td>
		<td class="listingCell" valign="top"><?php echo $pReason?></td>
		<td class="listingCell" align="center">&nbsp;<?php if ($pidemail) {?><a href="editSubscriber.php?idEmail=<?php echo $pidemail?>"><img src="./images/edit.png" border="0" title="<?php echo LISTREASONS_6; ?>"></a><?php }?></td>
		<td class="listingCell" style="BORDER-left:0px; BORDER-right: #c9c9c9 1px solid;"></td>
	</tr>
	<?php
        }
	?>
</tbody>
</table>

<br><br>
<?php
include('nav.php');
} //we have $rows
$obj->free_result($result);
//$obj->free_result($result1);
$obj->closeDb();
include('footer.php');
?>
