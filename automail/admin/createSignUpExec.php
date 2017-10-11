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
$groupName 	 	=	$obj->getSetting("groupName", $idGroup);
$groupScriptUrl =	$obj->getSetting("groupScriptUrl", $idGroup);
include('header.php');
showMessageBox();
$vgroupCustomSubField1 = $obj->getSetting("groupCustomSubField1", $idGroup);
$vgroupCustomSubField2 = $obj->getSetting("groupCustomSubField2", $idGroup);
$vgroupCustomSubField3 = $obj->getSetting("groupCustomSubField3", $idGroup);
$vgroupCustomSubField4 = $obj->getSetting("groupCustomSubField4", $idGroup);
$vgroupCustomSubField5 = $obj->getSetting("groupCustomSubField5", $idGroup);
$thisYear=date("Y"); 
?>
<script language="Javascript">
<!--
function getForm() {
	var cont = $('#previewTestForm').html();$('#code').val(cont);
}
function selectField(field) {
	$('#'+field).focus();
	$('#'+field).select();
}
//-->
</script>
<table width="960px" cellpadding=3 cellspacing=0 border=0>
	<tr>
		<td valign="top">
			<span class="title"><?php echo CREATESIGNUPEXEC_1; ?></span>
			<br><a href='javascript:window.history.back(1)'><?php echo CREATESIGNUPEXEC_19; ?></a>
		</td>
		<td align="right">
		<img src="./images/inform.png" alt="" width="55" height="65">
		</td>
	</tr>
</table>
<hr>

<!--preview and test form-->

<div id=previewTestForm>
<script Language="Javascript" type="text/javascript">
<!--
function trim(str){str = str.replace(/^\s*$/, '');return str;}
function $Npro(field){var element =  document.getElementById(field);return element;return false;}
function emailvalidation(field, errorMessage) {
	var goodEmail = field.value.match(/[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?/);
	apos=field.value.indexOf("@");dotpos=field.value.lastIndexOf(".");lastpos=field.value.length-1;tldLen = lastpos-dotpos;dmLen=dotpos-apos-1;var badEmail= (tldLen<2 || dmLen<2 || apos<1);
	if (!goodEmail || badEmail) {$Npro("Error").innerHTML=errorMessage;$Npro("Error").style.display="inline";field.focus();field.select();return false;}
	else {return true;}
}
function emptyvalidation(entered, errorMessage) {
	$Npro("Error").innerHTML="";
	with (entered) {
	if (trim(value)==null || trim(value)=="") {/*alert(errorMessage);*/$Npro("Error").innerHTML=errorMessage;$Npro("Error").style.display="inline";return false;}
	else {return true;}}//with
}//emptyvalidation
function formvalidation(thisform) {
with (thisform) {
if (emailvalidation(email,"<?php echo CREATESIGNUPEXEC_22; ?>")==false) {email.focus(); return false;};
<?php if (@$_POST['name']== "-1" AND @$_POST['nameRequired']== "-1") {?>
if (emptyvalidation(name,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_3);?>")==false) {name.focus(); return false;};
<?php }?>
<?php if (@$_POST['lastname']=="-1"  AND @$_POST['lastnameRequired']== "-1") {?>
if (emptyvalidation(lastname,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_23)?>")==false) {lastname.focus(); return false;};
<?php }?>
<?php if (@$_POST['subcompany']=="-1" AND @$_POST['subcompanyRequired']=="-1") {?>
if (emptyvalidation(subcompany,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_24)?>")==false) {subcompany.focus(); return false;};
<?php }?>
<?php if (@$_POST['subphone1']=="-1" AND @$_POST['subphone1Required']=="-1") {?>
if (emptyvalidation(subphone1,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_32)?>")==false) {subphone1.focus(); return false;};
<?php }?>
<?php if (@$_POST['subphone2']=="-1" AND @$_POST['subphone2Required']=="-1") {?>
if (emptyvalidation(subphone2,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_32)?>")==false) {subphone2.focus(); return false;};
<?php }?>
<?php if (@$_POST['submobile']=="-1" AND @$_POST['submobileRequired']=="-1") {?>
if (emptyvalidation(submobile,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_27)?>")==false) {submobile.focus(); return false;};
<?php }?>
<?php if (@$_POST['password']=="-1" AND @$_POST['passwordRequired']=="-1") {?>
if (emptyvalidation(password,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_4)?>")==false) {password.focus(); return false;};
<?php }?>
<?php if (@$_POST['address']=="-1" AND @$_POST['addressRequired']=="-1") {?>
if (emptyvalidation(address,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_5)?>")==false) {address.focus(); return false;};
<?php }?>
<?php if (@$_POST['city']=="-1" AND @$_POST['cityRequired']=="-1") {?>
if (emptyvalidation(city,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_6)?>")==false) {city.focus(); return false;};
<?php }?>
<?php if (@$_POST['zip']=="-1" AND @$_POST['zipRequired']=="-1") {?>
if (emptyvalidation(zip,"<?php echo CREATESIGNUPEXEC_20.' '.strtolower(CREATESIGNUPEXEC_7)?>")==false) {zip.focus(); return false;};
<?php }?>
<?php if (@$_POST['pcustomsubfield1']=="-1" AND @$_POST['pcustomsubfield1Required']=="-1") {?>
if (emptyvalidation(pcustomsubfield1,"<?php echo CREATESIGNUPEXEC_20?><?php echo $vgroupCustomSubField1?>")==false) {pcustomsubfield1.focus(); return false;};
<?php }?>
<?php if (@$_POST['pcustomsubfield2']=="-1" AND @$_POST['pcustomsubfield2Required']=="-1") {?>
if (emptyvalidation(pcustomsubfield2,"<?php echo CREATESIGNUPEXEC_20?><?php echo $vgroupCustomSubField2?>")==false) {pcustomsubfield2.focus(); return false;};
<?php }?>
<?php if (@$_POST['pcustomsubfield3']=="-1" AND @$_POST['pcustomsubfield3Required']=="-1") {?>
if (emptyvalidation(pcustomsubfield3,"<?php echo CREATESIGNUPEXEC_20?><?php echo $vgroupCustomSubField3?>")==false) {pcustomsubfield3.focus(); return false;};
<?php }?>
<?php if (@$_POST['pcustomsubfield4']=="-1" AND @$_POST['pcustomsubfield4Required']=="-1") {?>
if (emptyvalidation(pcustomsubfield4,"<?php echo CREATESIGNUPEXEC_20?><?php echo $vgroupCustomSubField4?>")==false) {pcustomsubfield4.focus(); return false;};
<?php }?>
<?php if (@$_POST['pcustomsubfield5']=="-1" AND @$_POST['pcustomsubfield5Required']=="-1") {?>
if (emptyvalidation(pcustomsubfield5,"<?php echo CREATESIGNUPEXEC_20?><?php echo $vgroupCustomSubField5?>")==false) {pcustomsubfield5.focus(); return false;};
<?php }?>
<?php if (@$_POST['statelist']=="-1" AND @$_POST['statelistRequired']=="-1") {?>
if (emptyvalidation(statecode,"<?php echo CREATESIGNUPEXEC_33.' '.strtolower(CREATESIGNUPEXEC_8)?>")==false) {statecode.focus(); return false;};
<?php }?>
<?php if (@$_POST['countrylist']=="-1" AND  @$_POST['countrylistRequired']=="-1") {?>
if (emptyvalidation(countrycode,"<?php echo CREATESIGNUPEXEC_33.' '.strtolower(CREATESIGNUPEXEC_9)?>")==false) {countrycode.focus(); return false;};
<?php }?>
<?php if (@$_POST['birthday']=="-1" AND  @$_POST['birthdayRequired']=="-1") {?>
if (emptyvalidation(subbirthday,"<?php echo CREATESIGNUPEXEC_29; ?>")==false) {subbirthday.focus(); return false;};
if (emptyvalidation(subbirthmonth,"<?php echo CREATESIGNUPEXEC_29; ?>")==false) {subbirthmonth.focus(); return false;};
<?php }?>
<?php if (@$_POST['birthyear']=="-1" AND  @$_POST['birthyearRequired']=="-1") {?>
if (emptyvalidation(subbirthyear,"<?php echo CREATESIGNUPEXEC_29; ?>")==false) {subbirthyear.focus(); return false;};
<?php }?>

}
<?php if (@$_POST['mailinglists']=="-1" AND @$_POST['mailinglistsRequired']=="-1") {?>
var checkFound=false; var k=document.subform.idlist.length;
if (typeof k=="undefined" && document.subform.idlist.checked==false){checkFound = false;}
if (typeof k=="undefined" && document.subform.idlist.checked==true){checkFound = true;}
for(var i=0; i < document.subform.idlist.length; i++)if(document.subform.idlist[i].checked==true){checkFound = true;}
if (checkFound != true) {/*alert ("<?php echo CREATESIGNUPEXEC_21; ?>");*/$Npro("Error").innerHTML="<?php echo CREATESIGNUPEXEC_21; ?>";$Npro("Error").style.display="inline";return false;}
else {return true;}
<?php }?>
}
//-->
</script>
<div>
<form onsubmit="return formvalidation(this)"  method="post" target="_blank" name="subform" action="<?php echo $groupScriptUrl?>/subscriber/optIn.php" autocomplete="off">
<input type="hidden" name="idGroup" value="<?php echo $idGroup?>">
<table border="0" cellpadding="5" cellspacing="0">
<tr><td><?php echo CREATESIGNUPEXEC_2; ?>:</td><td><input type="text" name="email" value="" onChange="emailvalidation(this,'Invalid Email');"></td></tr>
<?php if (@$_POST['name']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_3; ?>:</td><td><input type="text" id="name" name="name" value=""></td></tr>
<?php }
if (@$_POST['lastname']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_23; ?>:</td><td><input type="text" name="lastname" value=""></td></tr>
<?php }
if (@$_POST['subcompany']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_24; ?>:</td><td><input type="text" name="subcompany" value=""></td></tr>
<?php }
if (@$_POST['subphone1']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_25; ?>:</td><td><input type="text" name="subphone1" value=""></td></tr>
<?php }
if (@$_POST['subphone2']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_26; ?>:</td><td><input type="text" name="subphone2" value=""></td></tr>
<?php }
if (@$_POST['submobile']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_27; ?>:</td><td><input type="text" name="submobile" value=""></td></tr>
<?php }
if (@$_POST['password']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_4; ?>:</td><td><input type="password" name="password" value=""></td></tr>
<?php	}
if (@$_POST['address']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_5; ?>:</td><td><input type="text" name="address" value="" size=30></td></tr>
<?php }
if (@$_POST['city']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_6; ?>:</td><td><input type="text" name="city" value=""></td></tr>
<?php }
if (@$_POST['zip']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_7; ?>:</td><td><input type="text" name="zip" value=""></td></tr>
<?php }
if (@$_POST['pcustomsubfield1']=="-1") {?><tr><td><?php echo $vgroupCustomSubField1?>:</td><td><input type="text" name="pcustomsubfield1" id="pcustomsubfield1" value=""></td></tr>
<?php }
if (@$_POST['pcustomsubfield2']=="-1") {?><tr><td><?php echo $vgroupCustomSubField2?>:</td><td><input type="text" name="pcustomsubfield2" id="pcustomsubfield2" value=""></td></tr>
<?php }
if (@$_POST['pcustomsubfield3']=="-1") {?><tr><td><?php echo $vgroupCustomSubField3?>:</td><td><input type="text" name="pcustomsubfield3" id="pcustomsubfield3" value=""></td></tr>
<?php }
if (@$_POST['pcustomsubfield4']=="-1") {?><tr><td><?php echo $vgroupCustomSubField4?>:</td><td><input type="text" name="pcustomsubfield4" id="pcustomsubfield4" value=""></td></tr>
<?php }
if (@$_POST['pcustomsubfield5']=="-1") {?><tr><td><?php echo $vgroupCustomSubField5?>:</td><td><input type="text" name="pcustomsubfield5" id="pcustomsubfield5" value=""></td></tr>
<?php }
if (@$_POST['statelist']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_8; ?>:</td><td>
<?php
$mySQLs="SELECT stateCode, stateName from ".$idGroup."_states WHERE idGroup=$idGroup order by stateName asc";
$result	= $obj->query($mySQLs);?>
<select name="statecode" class="select">
<option value=""><?php echo CREATESIGNUPEXEC_10; ?></option>
<?php while ($row = $obj->fetch_array($result)){?>
<option value="<?php echo $row['stateCode']?>"><?php echo $row['stateName']?></option>
<?php }?></select>
</td></tr>
<?php }
if (@$_POST['countrylist']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_9; ?>:</td><td>
<?php
$mySQLc="SELECT countryCode, countryName from ".$idGroup."_countries WHERE idGroup=$idGroup order by countryName asc";
$result2	= $obj->query($mySQLc);?>
<select name="countrycode" class="select">
<option value=""><?php echo CREATESIGNUPEXEC_10; ?></option>
<?php while ($row = $obj->fetch_array($result2)){?>
<option value="<?php echo $row['countryCode']?>"><?php echo $row['countryName']?></option>
<?php } ?></select>
</td></tr>
<?php }
if (@$_POST['birthday']=="-1") {?><tr><td><?php echo CREATESIGNUPEXEC_28; ?>:</td><td>
<select name="subbirthday" class="select">
<option value=""><?php echo CREATESIGNUPEXEC_30; ?></option>
<?php for ($i = 1; $i<=31; $i++) {?>
<option value="<?php echo $i?>"><?php echo $i?></option>
<?php }?>
</select>&nbsp;
<select name="subbirthmonth" class="select">
<option value=""><?php echo CREATESIGNUPEXEC_31; ?></option>
<option value="1"><?php echo MONTH_1; ?></option>
<option value="2"><?php echo MONTH_2; ?></option>
<option value="3"><?php echo MONTH_3; ?></option>
<option value="4"><?php echo MONTH_4; ?></option>
<option value="5"><?php echo MONTH_5; ?></option>
<option value="6"><?php echo MONTH_6; ?></option>
<option value="7"><?php echo MONTH_7; ?></option>
<option value="8"><?php echo MONTH_8; ?></option>
<option value="9"><?php echo MONTH_9; ?></option>
<option value="10"><?php echo MONTH_10; ?></option>
<option value="11"><?php echo MONTH_11; ?></option>
<option value="12"><?php echo MONTH_12; ?></option>
</select>&nbsp;

</td></tr>
<?php }
if (@$_POST['birthyear']=="-1") {?><tr><td>
<?php echo CREATESIGNUPFORM_28?>:</td><td><select name="subbirthyear" id="subbirthyear" class="select">
<option value=""><?php echo CREATESIGNUPFORM_28; ?></option>
<?php 
for ($y=1920; $y<$thisYear+1; $y++) {?>
<option value="<?php echo $y?>"><?php echo $y?></option>
<?php }?></select></td></tr><?php }
if (@$_POST['mailinglists']=="-1") {?><tr><td valign=top><?php echo CREATESIGNUPEXEC_14; ?>:</td><td>
<?php
$SQL="SELECT idList, listName, listDescription FROM ".$idGroup."_lists WHERE isPublic=-1 AND idGroup=$idGroup order by idList desc";
$result3	= $obj->query($SQL);
while ($row = $obj->fetch_array($result3)){?>
<input type="checkbox" id="idlist" name="idlist[]" value="<?php echo $row['idList']?>"><strong><?php echo $row['listName']?></strong>
<?php if (@$_POST['extListDesc']=="-1") {echo '<br>' .str_replace(chr(10), "<br>", $row['listDescription']);}
echo '<br>'; }?></td></tr><?php }
if (@$_POST['htmlOrText']=="-1") {?><tr><td valign=top><?php echo CREATESIGNUPEXEC_11; ?>:</td><td><input type="radio" name="prefers" value="-1" checked><?php echo CREATESIGNUPEXEC_12; ?><br><input type="radio" name="prefers" value="0"><?php echo CREATESIGNUPEXEC_13; ?></td></tr><?php }
if (@$_POST['linktoprivacy']=="-1") {?><tr><td valign=top></td><td><a target="blank" href="<?php echo $groupScriptUrl?>/subscriber/privacy.php"><?php echo SMARTLINKS_7; ?></a></td></tr><?php }
if (@$_POST['hiddenListID']!=0) {?><input type="hidden" name="idlist[]" value="<?php echo @$_POST['hiddenListID']?>"><?php } ?>
<tr><td>&nbsp;</td><td><span id="Error" style="color:red;display:none;"></span></td></tr>
<tr><td>&nbsp;</td><td align=left><input type="submit" value="<?php echo CREATESIGNUPEXEC_15; ?>"></td></tr>
</table>
</form></div>
</div><!--previewTestForm ended-->

<br><br>
<span class="title"><?php echo CREATESIGNUPEXEC_16; ?></span>&nbsp;&nbsp;&nbsp;
<a href=# onclick="selectField('code');return false;"><?php echo CREATESIGNUPEXEC_17.'</a> '.CREATESIGNUPEXEC_18; ?><br><br>
<textarea rows=20 cols=140 name="code" id="code" class=textarea></textarea>
<script language='javascript' type='text/javascript'>getForm()</script>
<?php
$obj->closeDb();
include('footer.php');
?>