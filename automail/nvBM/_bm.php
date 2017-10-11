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
?>
<table border=0 cellspacing=0 cellpadding=4 width="960px">
	<tr>
		<td>
			<span class="title"><?php echo BOUNCEMANAGER_11?></span>
		</td>
		<td align="right">
			<img src="./images/bounces.png" width="65" height="51">
		</td>
	</tr>
	<tr>
		<td colspan=2>
				<a href="_bmProcessAdmin.php"><?php echo BOUNCEMANAGER_12?></a>
				<br><br><br>
				<form method="post" action="_bmRemove.php">
				    <?php echo BOUNCEMANAGER_13?>
				    <select name="bounceType" class="select">
				    <option value="2" name="hards" selected><?php echo BOUNCEMANAGER_14?></option>
				    <option value="1" name="softs"><?php echo BOUNCEMANAGER_15?></option>
				    </select><?php echo BOUNCEMANAGER_16?><input type="number" class="fieldbox11" size="3" name="howmany"> <input type="submit" class="submit" name="remove" value="<?php echo BOUNCEMANAGER_19?>">
				</form>
				<br><br>
				<form method="post" action="_bmRemove.php">
				    <?php echo BOUNCEMANAGER_1?>
				    <select name="bounceType" class="select">
				    <option value="2" name="hards" selected><?php echo BOUNCEMANAGER_14?></option>
				    <option value="1" name="softs"><?php echo BOUNCEMANAGER_15?></option>
				    </select>&nbsp;<?php echo BOUNCEMANAGER_16?>&nbsp;<input type="number" class="fieldbox11" size="3" name="howmany">&nbsp;<?php echo BOUNCEMANAGER_2?>&nbsp;<input type="submit" class="submit" name="unconfirm" value="<?php echo ALLSTATS_39?>">
				</form>
				<br><br>
				<form method="post" action="_bmRemove.php">
				    <?php echo BOUNCEMANAGER_5?>
				    <select name="bounceType" class="select">
				    <option value="2" name="hards" selected><?php echo BOUNCEMANAGER_14?></option>
				    <option value="1" name="softs"><?php echo BOUNCEMANAGER_15?></option>
				    </select>&nbsp;<?php echo BOUNCEMANAGER_16?>&nbsp;<input type="number" class="fieldbox11" size="3" name="howmany">&nbsp;<input type="submit" class="submit" name="inactive" value="<?php echo ALLSTATS_39?>">
				</form>
                <br><br>
				<?php echo BOUNCEMANAGER_17?><a href="_bmRemove.php?reset=1"><?php echo BOUNCEMANAGER_18?></a>
		</td>
	</tr>
</table>
<?php
$obj->closeDb();
include('footer.php');
?>