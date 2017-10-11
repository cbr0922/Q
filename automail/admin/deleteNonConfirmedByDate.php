<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/languages.php');
$groupName 	 		= $obj->getSetting("groupName", $idGroup);
$groupSite 			= $obj->getSetting("groupSite", $idGroup);
$groupContactEmail	= $obj->getSetting("groupContactEmail", $idGroup);
if (@$pdemomode) {
	forDemo("message.php", DEMOMODE_1);
}
include('header.php');
?>
<table width="960px">
	<tr>
		<td valign="top">
			<span class="title"><?php echo DELETENONCONFIRMEDBYDATE_1; ?></span>
		</td>
		<td align="right">
			<img src="./images/quickdelete.png" alt="">
		</td>
	</tr>
</table>

<form method="post" action="deleteNonConfirmedByDate.php" name="deletenonconfirmedbydate">
<table>
	<tr>
		<td>
			<?php echo DELETENONCONFIRMEDBYDATE_2; ?>
			<input type="text" size="5" class="fieldbox11" name="days"><?php echo DELETENONCONFIRMEDBYDATE_3; ?>
			<input type="submit" class="submit" name="delete" value="<?php echo DELETENONCONFIRMEDBYDATE_4; ?>">

		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;
		</td>
	</tr>
</table>
</form>
<?php
$counter = 0;
if (!empty ($_POST['delete']) AND !empty ($_POST['days'])) {
	$pdays	= ceil($_POST['days']);
	$ptoday = myDatenow();
	$dSQL = " AND DATEDIFF(CURDATE(), dateSubscribed)>($pdays-1)";
	$mySQL="SELECT idEmail, confirmed, dateSubscribed from ".$idGroup."_subscribers WHERE confirmed=0".$dSQL;
	$result	= $obj->query($mySQL);
	$rows 	= $obj->num_rows($result);
	if (!$rows) {
		echo DELETENONCONFIRMEDBYDATE_5;
	}
	else {
	   while ($row = $obj->fetch_array($result)){
			$pidemail		= $row['idEmail'];
			$counter = $counter+1;
			$mySQL1="DELETE from ".$idGroup."_listRecipients WHERE idEmail=$pidemail";
			$obj->query($mySQL1);
			$mySQL2="DELETE from ".$idGroup."_subscribers WHERE idEmail=$pidemail";
			$obj->query($mySQL2);
		}
		echo $counter .DELETENONCONFIRMEDBYDATE_6;
		echo "<br>";
		echo DELETENONCONFIRMEDBYDATE_7;
		$obj->free_result($result);

   }

}
$obj->closeDb();
include("footer.php");
?>