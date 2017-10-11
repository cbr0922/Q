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
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName 	 			=	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$today 					= myDatenow();
include('headerXL.php');
(isset($_GET['idCampaign']))?$fidCampaign = $_GET['idCampaign']:$fidCampaign="";
if ($fidCampaign) {$strSQL=" WHERE ".$idGroup."_optOutReasons.idCampaign=$fidCampaign";}
else {$strSQL="";}
?>
<table border="0" width="960px" cellspacing="0" cellpadding="4">
	<tr>
		<td width=60% valign="top">
			<span class="title"><?php echo LISTREASONS_2;if ($fidCampaign) {echo '&nbsp;'.LISTREASONS_15.'&nbsp;'.$fidCampaign;}?></span>
		</td>
		<td valign="top">
		</td>
	</tr>
	<?php
	$mySQL="SELECT subscriberEmail, optOutReason, dateOptedOut, optOutType FROM ".$idGroup."_optOutReasons $strSQL";
    $result	= $obj->query($mySQL);
    $rows 	= $obj->num_rows($result);
    if (!$rows){
        echo "<tr><td colspan=2>".LISTREASONS_1."</td></tr></table>";
	} else {
      ?>
      <tr>
		<td>
        </td>
        <td>
		</td>
	</tr>
</table>
<table width="870" cellpadding="0" cellspacing="0">
	<tr>
		<td  width=200><?php echo LISTREASONS_4; ?></td>
		<td  width=150><?php echo LISTREASONS_8; ?></td>
        <td  width=170><?php echo LISTREASONS_7; ?></td>
		<td  width=350><?php echo LISTREASONS_5; ?></td>

	</tr>
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
	<tr>
		<td ><?php echo $psubscriberEmail?></td>
		<td >
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
        <td ><?php echo $pDate?></td>
		<td  valign="top"><?php echo $pReason?></td>

	</tr>
	<?php
        }
	?>
</table>

<br><br>
<?php
} //we have $rows
$obj->free_result($result);
$obj->closeDb();
?>
