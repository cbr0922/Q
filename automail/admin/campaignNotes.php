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

(isset($_GET['idCampaign']))?$pidCampaign = $_GET['idCampaign']:$pidCampaign="";
$mySQL="SELECT notes from ".$idGroup."_campaigns where idCampaign=$pidCampaign";
$result	= $obj->query($mySQL);
while ($row = $obj->fetch_array($result)){
    $pnotes = $row['notes'];
}
if (!empty($_GET['update'])) {
	$newNotes = dbQuotes($_GET['notes']);

	if (@$pdemomode) {
	   header("Location: campaignNotes.php?message=".DEMOMODE_1."&idCampaign=$pidCampaign");
	   die;
	}

	$mySQLn="UPDATE ".$idGroup."_campaigns SET notes='$newNotes' WHERE idCampaign=$pidCampaign";
	$obj->query($mySQLn);

?>
<script type="text/javascript" language="javascript">window.close();opener.location.reload();</script>
<?php } ?>
<html>
<head>
<title><?php echo CAMPAIGNNOTES_1; ?>&nbsp;<?php echo $pidCampaign?></title>
<link href="./includes/site.css" rel=stylesheet type=text/css>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $obj->getSetting("groupGlobalCharset", $idGroup); ?>">
<style>html {background: #DDDEEA;min-height:100%;height: 100%;}body {background: #DDDEEA;padding:10px;}</style>
</head>
<body>
<b><?php echo CAMPAIGNNOTES_1; ?>&nbsp;<?php echo $pidCampaign?></b>
<br>
<form action="campaignNotes.php" method="get" name="mailLog">
<input type="hidden" name="idCampaign" value="<?php echo $pidCampaign?>">
<TEXTAREA NAME="notes" COLS="60" ROWS=10 class="textarea"><?php echo $pnotes?></TEXTAREA>
<br><br>
<input type="submit" class="submit" name="update" value="<?php echo CAMPAIGNNOTES_3; ?>">
<input class="submit" type ="submit" name = "button" value = "<?php echo CAMPAIGNNOTES_4; ?>" onclick = "window.close();">
</form>
<?php
$obj->closeDb();
?>
</body>
</html>