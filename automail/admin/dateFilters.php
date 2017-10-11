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
<script type="text/javascript" language="javascript">
<!--
function validateNumber(){
	if ($("#daysafter").blank() || isNaN($("#daysafter").val())==true) {
		openAlertBox('<?php echo DATEFILTERS_11; ?>','');
		$("#daysafter").clear();
		$("#daysafter").focus();
		return false;
	}
}
-->
</script>

<table border="0" width="960px">
	<tr>
		<td valign=top>
			<span class="title"><?php echo DATEFILTERS_1; ?></span>
			<br><br>
			<?php echo DATEFILTERS_2; ?>
		</td>
		<td align=right>
			<img src="./images/createfilter.png" width="60" height="47">
		</td>
	</tr>
</table>

<form name="xDaysAfterFilter" method="get" action="editSendFiltersExec.php" onsubmit="return validateNumber(this)">
<table width="650" cellpadding="4" cellspacing="0" border="0">
	<tr>
		<td width=150><?php echo DATEFILTERS_3; ?>:</td>
		<td width=150><INPUT class="fieldbox11" size=5 TYPE="Text" Name="daysafter" id="daysafter" VALUE="" >&nbsp;<?php echo DATEFILTERS_4; ?></td>
		<td width=350><input class="submit" type="Submit" name="xDaysAfterFilter" value="<?php echo DATEFILTERS_5; ?>"></td>
	</tr>
	<tr>
		<td colspan=3><hr></td>
	</tr>
</table>
</form>

<table width="650" cellpadding="4" cellspacing="0" border="0">
	<tr>
		<td>
			<a href="#"  class="cross" onclick="show_hide_div('dfSteps','cross1');return false;"><span id="cross1">[+]</span>&nbsp;<?php echo DATEFILTERS_6; ?></a>
			<ol id="dfSteps" style="display:none;LINE-HEIGHT:1.5;">
			<li><?php echo DATEFILTERS_7; ?></li>
			<li><?php echo DATEFILTERS_8; ?></li>
			</ol>
		</td>
	</tr>

	<tr>
		<td>
			<a href="#"  class="cross" onclick="show_hide_div('dfMore','cross2');return false;"><span id="cross2">[+]</span>&nbsp;<?php echo DATEFILTERS_9; ?></a>
			<div id="dfMore" style="display:none;padding-top:15px"><?php echo DATEFILTERS_10; ?>
			<ol style="LINE-HEIGHT:1.5;">
				<li><?php echo DATEFILTERS_12; ?></li>
				<li><?php echo DATEFILTERS_13; ?></li>
				<li><?php echo DATEFILTERS_14; ?></li>
				<li><?php echo DATEFILTERS_15; ?></li>
				<li><?php echo DATEFILTERS_16; ?></li>
				<li><?php echo DATEFILTERS_17; ?></li>
			</ol></div>
		</td>
	</tr>

</table>


<?php
$obj->closeDb();
include('footer.php');
?>
