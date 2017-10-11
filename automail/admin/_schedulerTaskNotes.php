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
(isset($_GET['idTask']))?$idTask = $_GET['idTask']:$idTask="";
$mySQL="SELECT pLog from ".$idGroup."_tasks where idTask=$idTask";
$result	= $obj->query($mySQL);
$row = $obj->fetch_array($result);
$pnotes = $row['pLog'];

if (!empty($_GET['update'])) {
    $newNotes = dbQuotes($_GET['notes']);
	if (@$pdemomode) {
	   header("Location: _schedulerTaskNotes.php?message=".DEMOMODE_1."&idTask=$idTask");
       die;
	}
	$mySQLn="UPDATE ".$idGroup."_tasks SET pLog='$newNotes' WHERE idTask=$idTask";
	$obj->query($mySQLn);
?>
<script type="text/javascript" language="javascript">window.close();opener.location.reload();</script>
<?php } ?>

<html>
<head>
<title><?php echo SCHEDULERTASKS_46?>&nbsp;<?php echo $idTask?></title>
<link href="./includes/site.css" rel=stylesheet type=text/css>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $obj->getSetting("groupGlobalCharset", $idGroup); ?>">
<style>html {background: #DDDEEA;min-height:100%;height: 100%;}body {background: #DDDEEA;padding:10px;}</style></head>
<body>
<b><?php echo SCHEDULERTASKS_46?>&nbsp;<?php echo $idTask?></b>
<br>
<form action="_schedulerTaskNotes.php" method="get" name="scTaskNotes">
<input type="hidden" name="idTask" value="<?php echo $idTask?>">
<TEXTAREA class="textarea" NAME="notes" COLS="60" ROWS=10><?php echo $pnotes?></TEXTAREA>
<br><br>
<input type="submit" class="submit" name="update" value="<?php echo SCHEDULERTASKS_47?>">
<input class="submit" type ="submit" name = "button" value = "<?php echo SCHEDULERTASKS_48?>" onclick = "window.close()">
</form>
</body>
</html>