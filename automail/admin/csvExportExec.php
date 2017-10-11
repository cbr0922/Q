<?php
set_time_limit(0);
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj 			= new db_class();
$groupName 		=	$obj->getSetting("groupName", $idGroup);
$groupScriptUrl =	$obj->getSetting("groupScriptUrl", $idGroup);
if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}
include('header.php');

?>
<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo CSVSUBSCRIBERSEXPORTEXEC_1; ?></span>
		</td>
		<td align=right>
			<img src="./images/exportcsvdone.png"  alt="" width="60" height="47">
		</td>
	</tr>
</table>

<?php
$pidlist = $_POST['idList'];
$delimiter=$_POST['delimiter'];
switch ($delimiter) {
  case "semicolon":
	$delimiter=";";
  break;
  case "comma":
	$delimiter=",";
  break;
  case "tab":
  	$delimiter="\t";
  break;
  default:
	$delimiter=";";
}

if ($pidlist==-1) {
	$plistname = CSVSUBSCRIBERSEXPORT_4;
	$mySQL="SELECT idEmail, email, name, lastName, subCompany, timesMailed, address, city, state, zip, country, subPhone1, subPhone2, subMobile, subPassword, prefersHtml, confirmed, dateSubscribed, dateLastUpdated, dateLastEmailed, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5, soft_bounces, hard_bounces, optOutReason, ipSubscribed, internalMemo, subBirthDay, subBirthMonth, subBirthYear FROM ".$idGroup."_subscribers where idGroup=$idGroup";
 } else {
	$plistname = getlistname($pidlist, $idGroup);
	$mySQL="SELECT ".$idGroup."_subscribers.idEmail, email, name, lastName, subCompany, timesMailed, address, city, state, zip, country, subPhone1, subPhone2, subMobile, subPassword, prefersHtml, confirmed, dateSubscribed, dateLastUpdated, dateLastEmailed, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5, soft_bounces, hard_bounces, optOutReason, ipSubscribed, internalMemo, subBirthDay, subBirthMonth, subBirthYear FROM ".$idGroup."_subscribers, ".$idGroup."_listRecipients WHERE ".$idGroup."_listRecipients.idGroup=$idGroup AND ".$idGroup."_listRecipients.idList=$pidlist  AND ".$idGroup."_listRecipients.idEmail=".$idGroup."_subscribers.idEmail";
}
//echo($mySQL);
//die;
$result 	= $obj->query($mySQL);
$rows 		= $obj->num_rows($result);
if ($rows) {
$fieldCount	= $obj->field_count();
$line="";
$f=0;
$pfileName	= "Subscribers_export_".$idGroup.".csv";
$MyFile 	= fopen("../data_files/".$pfileName, "w");

while ($row = $obj->fetch_array($result)){
	for ($i=0; $i<$fieldCount; $i++) {
		$line .=$delimiter.$row[$i];
	}
	$line=$line."\r\n";
	$line=ltrim($line, $delimiter);
	fwrite($MyFile, $line);
	$line="";
}
?>
<br><br>
<?php echo CSVSUBSCRIBERSEXPORTEXEC_2 .' '.$plistname;?>
<br>
<br><br><?php echo $rows.' '.CSVSUBSCRIBERSEXPORTEXEC_3; ?>
<br><br>
<?php echo CSVSUBSCRIBERSEXPORTEXEC_4; ?><a target=blank href='<?php echo "$groupScriptUrl/data_files/$pfileName";?>'><?php echo CSVSUBSCRIBERSEXPORTEXEC_5; ?></a>
<?php
} //have rows
else
{echo '<br><br>'.CSVSUBSCRIBERSEXPORTEXEC_6;}
fclose($MyFile);
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>
