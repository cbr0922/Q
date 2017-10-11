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
include('header.php');
showMessageBox();

if (!empty($_POST['save'])) {
	$SQL = "Update ".$idGroup."_groupSettings set groupIdWelcomeNewsletter=".$_POST['idNewsletter']." WHERE idGroup=$idGroup";
	$obj->query($SQL);
}

?>
<table border="0" cellpadding=5 width="960px">
	<tr>
		<td  valign="top">
   			<span class="title"><?php echo SENDTONEW_1; ?></span>
			<br><br><a class="cross" href="#" title="<?php echo GENERIC_6?>" onclick="show_hide_div('details', 'cross');return false;"><span id='cross'>[+]</span>&nbsp;<?php echo GENERIC_25; ?></a>
		</td>
		<td valign="top" align="right"><img src="./images/messages.png" width="65" height="51" alt=""></td>
	</tr>
</table>

<form name="quicksendform" method="post" action="sendToNew.php">
<table border="0" cellpadding="2" cellspacing="0" width="450">
	<tr>
		<td colspan="2">
			<div id="details" style="display:none;"><?php echo SENDTONEW_2; ?></div>
			<?php
			$mySQL="Select groupIdWelcomeNewsletter from ".$idGroup."_groupSettings where idGroup=$idGroup";
			$result	= $obj->query($mySQL);
			$row = $obj->fetch_row($result);
			echo '<br>'.SENDTONEW_7;
			if  ($row[0]!=0) {
				$groupIdWelcomeNewsletter = $row[0];
				$mySQL1="Select name, dateCreated, dateSent from ".$idGroup."_newsletters where idNewsletter=$groupIdWelcomeNewsletter";
				$result	= $obj->query($mySQL1);
				if ($result) {
					$rowN = $obj->fetch_array($result);
					echo ' <font color=blue>'.$rowN['name'].'</font><br>'.SENDTONEW_16 .$rowN['dateCreated'].', '.SENDTONEW_17; if ($rowN['dateSent']) {echo $rowN['dateSent'];} else {echo SENDTONEW_18;}
				} else {
					echo ' <font color=blue>'.SENDTONEW_8.'</font>';
				}
			} else {
				echo ' <font color=blue>'.SENDTONEW_8.'</font>';
			}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
   	<tr>
   		<td valign="top" width=200>
   			<b><?php echo SENDTONEW_3; ?></b>
   		</td>
        <td valign=top>
			<?php
			$mySQL1 ="Select idNewsletter, name, dateCreated, dateSent from ".$idGroup."_newsletters where html=-1 AND idGroup=$idGroup order by idNewsletter desc";
            $result	= $obj->query($mySQL1);
			$mySQL2 ="Select idNewsletter, name, dateCreated, dateSent from ".$idGroup."_newsletters where html=0 AND idGroup=$idGroup order by idNewsletter desc";
			$result2 = $obj->query($mySQL2);
			 ?>
			<SELECT name="idNewsletter" class="select">
				<option value="0"><?php echo SENDTONEW_9; ?></option>
				<option value="0" selected><?php echo SENDTONEW_4; ?></option>
				<?php	while ($row = $obj->fetch_array($result)) {?>
				<option value="<?php echo $row['idNewsletter']?>"><?php echo $row['name']?> - (<?php echo SENDTONEW_16; ?><?php echo $row['dateCreated']; ?>,<?php echo SENDTONEW_17?><?php if ($row['dateSent']) {echo $row['dateSent'];} else {echo SENDTONEW_18;} ?>)</option>
				<?php  } ?>
 				<option value="0"><?php echo SENDTONEW_5; ?></option>
 				<?php	while ($row = $obj->fetch_array($result2)){?>
				<option value="<?php echo $row['idNewsletter']?>"><?php echo $row['name']?> - (<?php echo SENDTONEW_16; ?><?php echo $row['dateCreated']; ?>,<?php echo SENDTONEW_17?><?php if ($row['dateSent']) {echo $row['dateSent'];} else {echo SENDTONEW_18;} ?>)</option>
				<?php  } ?>
		</SELECT>
		</td>
   </tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
   		<td colspan=2 align=right>
			<input type="submit" class="submit" name="save" value="<?php echo SENDTONEW_6; ?>">
		</td>
	</tr>
</table>
</form>

<?php
$obj->closeDb();
include('footer.php');
?>