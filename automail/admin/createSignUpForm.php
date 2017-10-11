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

$vgroupCustomSubField1 = $obj->getSetting("groupCustomSubField1", $idGroup);
$vgroupCustomSubField2 = $obj->getSetting("groupCustomSubField2", $idGroup);
$vgroupCustomSubField3 = $obj->getSetting("groupCustomSubField3", $idGroup);
$vgroupCustomSubField4 = $obj->getSetting("groupCustomSubField4", $idGroup);
$vgroupCustomSubField5 = $obj->getSetting("groupCustomSubField5", $idGroup);
?>
<script Language="Javascript" type="text/javascript">
function validate() {
    if ( ($("#mailinglists").prop('checked')==true) && ($("#hiddenListID").val()!=="0")  ) {
		openAlertBox('<?php echo fixJSstring(CREATESIGNUPFORM_27);?>','');return false;}}
</script>
<form action=createSignUpExec.php name="subform" method="post" onsubmit="return validate(this);" autocomplete="off">
<table width="960px" cellpadding=3 cellspacing=0 border=0>
	<tr>
		<td valign="top">
			<span class="title"><?php echo CREATESIGNUPFORM_1; ?></span>

		</td>
		<td align="right">
			<img src="./images/inform.png" alt="" width="55" height="65">
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<a href="#" class="cross" onclick="show_hide_div('notes','cross1');return false;"><span id='cross1'>[+]</span>&nbsp;<?php echo GENERIC_25;?></a>
			<div id="notes" style="display:none;"><?php echo CREATESIGNUPFORM_13?>
			</div>
		</td>
	</tr>
</table>
<br>

<table border="0" cellpadding=2 cellspacing=0 width="600px">
	<tr>
		<td valign="top"><b><?php echo CREATESIGNUPFORM_23; ?></b></td>
		<td valign="top"><b><?php echo CREATESIGNUPFORM_12; ?></b></td>
		<td valign="top"><b><?php echo CREATESIGNUPFORM_22; ?></b></td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_2; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="name" value="-1"></td><td><input type="checkbox" name="nameRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_16; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="lastname" value="-1"> </td><td><input type="checkbox" name="lastnameRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_17; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="subcompany" value="-1"></td><td><input type="checkbox" name="subcompanyRequired" value="-1" style="margin-left:3px">
		</td>
</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_18; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="subphone1" value="-1"></td><td><input type="checkbox" name="subphone1Required" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_19; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="subphone2" value="-1"></td><td><input type="checkbox" name="subphone2Required" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_20; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="submobile" value="-1"></td><td><input type="checkbox" name="submobileRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_4; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="password" value="-1"></td><td><input type="checkbox" name="passwordRequired" value="-1" style="margin-left:3px">&nbsp;
			<img onmouseout="hide_info_bubble('qi_2','0')" onmouseover="infoBox('qi_2', '<?php echo fixJSstring(CREATESIGNUPFORM_4);?>', '<?php echo fixJSstring(CREATESIGNUPFORM_24)?>', '20em', '0');" src="./images/i1.gif">
			<span style="display: none;" id="qi_2"></span>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_5; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="address" value="-1"></td><td><input type="checkbox" name="addressRequired" value="-1" style="margin-left:3px">
		</td>
 	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_6; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="city" value="-1"></td><td><input type="checkbox" name="cityRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_7; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="zip" value="-1"></td><td><input type="checkbox" name="zipRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>

<!-- start custom subscriber fields -->

	<?php if ($vgroupCustomSubField1) {?>
	<tr>
		<td><?php echo $vgroupCustomSubField1?></td>
		<td>
			<input type="checkbox" name="pcustomsubfield1" value="-1"></td><td><input type="checkbox" name="pcustomsubfield1Required" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<?php }
	if ($vgroupCustomSubField2) {?>
	<tr>
		<td><?php echo $vgroupCustomSubField2?></td>
		<td>
			<input type="checkbox" name="pcustomsubfield2" value="-1"></td><td><input type="checkbox" name="pcustomsubfield2Required" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<?php }
	if ($vgroupCustomSubField3) {?>
	<tr>
		<td><?php echo $vgroupCustomSubField3?></td>
		<td>
			<input type="checkbox" name="pcustomsubfield3" value="-1"></td><td><input type="checkbox" name="pcustomsubfield3Required" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<?php }
	if ($vgroupCustomSubField4) {?>
	<tr>
		<td><?php echo $vgroupCustomSubField4?></td>
		<td>
			<input type="checkbox" name="pcustomsubfield4" value="-1"></td><td><input type="checkbox" name="pcustomsubfield4Required" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<?php }
	if ($vgroupCustomSubField5) {?>
	<tr>
		<td><?php echo $vgroupCustomSubField5?></td>
		<td>
			<input type="checkbox" name="pcustomsubfield5" value="-1"></td><td><input type="checkbox" name="pcustomsubfield5Required" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<?php }?>
<!-- end custom subscriber fields -->

	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_21; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="birthday" value="-1"></td><td><input type="checkbox" name="birthdayRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_28; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="birthyear" value="-1"></td><td><input type="checkbox" name="birthyearRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>

	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_8; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="statelist" value="-1"></td><td><input type="checkbox" name="statelistRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_9; ?>
		</td>
		<td valign="top">
			<input type="checkbox" name="countrylist" value="-1"></td><td><input type="checkbox" name="countrylistRequired" value="-1" style="margin-left:3px">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_10; ?>
		</td>
		<td valign="top">
			<input type="checkbox" id="mailinglists" name="mailinglists" value="-1"></td><td><input type="checkbox" name="mailinglistsRequired" value="-1" style="margin-left:3px">
            <img onmouseout="hide_info_bubble('mLists','0')" onmouseover="infoBox('mLists', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(CREATESIGNUPFORM_26)?>', '20em', '0');" src="./images/i1.gif">
			<span style="display: none;" id="mLists"></span>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_14; ?>
		</td>
		<td valign="top" colspan="2">
			<input type="checkbox" name="extListDesc" value="-1">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_3; ?>
		</td>
		<td valign="top" colspan="2">
			<input type="checkbox" name="htmlOrText" value="-1">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo SMARTLINKS_8; ?>
		</td>
		<td valign="top" colspan="2">
			<input type="checkbox" name="linktoprivacy" value="-1">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo CREATESIGNUPFORM_15; ?>
		</td>
		<td valign="top"  colspan="2">
				<?php
			   	$SQL="SELECT idList, listName, isPublic FROM ".$idGroup."_lists WHERE idGroup=$idGroup order by idList desc";
				$result3	= $obj->query($SQL);?>
				<SELECT class="select" id="hiddenListID" name="hiddenListID">
					<OPTION value="0"><?php echo LISTS_12; ?>
					<?php while ($row = $obj->fetch_array($result3)){?>
					<option value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php echo $row['listName'];?></option>
					<?php }?>
				</SELECT>&nbsp;<img onmouseout="hide_info_bubble('hList','0')" onmouseover="infoBox('hList', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(CREATESIGNUPFORM_25)?>', '50em', '0');" src="./images/i1.gif">
			<span style="display: none;" id="hList"></span>
		</td>
	</tr>

	<tr>
		<td align=center colspan=3>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
		</td>
		<td  colspan="2">
			<input type="submit" value="<?php echo CREATESIGNUPFORM_11; ?>" class="submit" id="formWizard" name="formWizard">
		</td>
	</tr>
</table>
</form>
<?php
$obj->closeDb();
include('footer.php');
?>