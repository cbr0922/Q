<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj 		= new db_class();
$groupName	= $obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/html; charset='.$groupGlobalCharset.'');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
if (@$pdemomode) {
	forDemo2(DEMOMODE_1);
}
?>
<span class="title"><?php echo DELETEQUICKREMOVEEXEC_1; ?></span>
<?php
$psubscribers 	= $_POST['subsToDelete'];
$psubscribers	= explode("\n", $psubscribers);
$subs 			= sizeof($psubscribers);

$pCounter=0;
$pNotFound=0;

for ($i=0; $i<$subs; $i++)  {
	$mySQL1="SELECT idEmail FROM ".$idGroup."_subscribers where idGroup=$idGroup AND email='".trim(dbQuotes($psubscribers[$i]))."'";
	$result	= $obj->query($mySQL1);
	$row = $obj->fetch_array($result);
	if (!$row) {	//HE DOES NOT EXIST by this emal
		$pNotFound=$pNotFound+1;
	}
	else {
		$pCounter=$pCounter+1;
		$nidemail	= $row['0'];
		$mySQLn="delete from ".$idGroup."_listRecipients where idEmail=$nidemail";
		$obj->query($mySQLn);
		$mySQL3="delete from ".$idGroup."_subscribers where idEmail=$nidemail";
		$obj->query($mySQL3);
	}
}

?>
<br><?php echo $pCounter?><?php echo DELETEQUICKREMOVEEXEC_2 .'<br>';
if ($pNotFound>0) {
	echo $pNotFound.' '.DELETEQUICKREMOVEEXEC_3;
}
?>

<?php
$obj->closeDb();
?>