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
		<td valign="top" width="40%">
			<span class="title"><?php echo CREATESIGNUPEXEC_34; ?></span>
            <br /><div><?php echo CREATESIGNUPEXEC_36; ?></div>
		</td>
		<td align="right"  width="60%">
			<img src="./images/outform.png" alt="" width="55" height="65">
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
	if (!goodEmail || badEmail) {
		$Npro("Error").innerHTML=errorMessage;$Npro("Error").style.display="inline";field.focus();field.select();
		return false;
		}
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
}
}
//-->
</script>
<div>
<form onsubmit="return formvalidation(this)"  method="get" target="_blank" name="subform" action="<?php echo $groupScriptUrl?>/subscriber/optOut.php" autocomplete="off">
<input type="hidden" name="idGroup" value="<?php echo $idGroup?>"><input type="hidden" name="a" value="2">
<table border="0" cellpadding="5" cellspacing="0">
<tr><td><?php echo CREATESIGNUPEXEC_2; ?>:</td><td><input type="text" name="email" value="" onChange="emailvalidation(this,'Invalid Email');"></td></tr>
<tr><td>&nbsp;</td><td><span id="Error" style="color:red;display:none;"><span></td></tr>
<tr><td>&nbsp;</td><td align=left><input type="submit" value="<?php echo CREATESIGNUPEXEC_35; ?>"></td></tr>
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