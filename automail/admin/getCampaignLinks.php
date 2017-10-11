<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');

$obj = new db_class();
$groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/plain;charset=".$groupGlobalCharset."");

$pidCampaign = $_REQUEST['selectedCampaign'];
$mySQL="SELECT distinct idLink, linkUrl FROM ".$idGroup."_clickStats WHERE idGroup=$idGroup AND idCampaign=$pidCampaign";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if ($rows){
?>
	<select multiple id="links" name="links" class="select">
		<!--option value=""><?php //echo FOLLOWUPLIST_30; ?></option-->
		<?php 	while ($row = $obj->fetch_array($result)){ ?>
			<option value="<?php echo $row['idLink'].'@'.$row['linkUrl']; ?>"><?php echo $row['linkUrl']?></option>
		<?php }?>
	</select>
<?php } else { echo "0";?>
<?php }

$obj->free_result($result);
$obj->closeDb();