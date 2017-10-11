<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../inc/languages.php');
include('../inc/sendMail.php');
(isset($_GET['subemail']))?$sub["email"] = dbQuotes(dbProtect($_GET['subemail'],500)):$sub["email"]="";
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);
$mailData["groupName"]   =	$obj->getSetting("groupName", $idGroup);
@$message =dbQuotes(dbProtect($_GET['message'],500));
if (!empty($_GET["emailreminder"])) {
	$mySQL="SELECT idEmail, name, lastName, subPassword from ".$idGroup."_subscribers WHERE email='".$sub["email"]."'";
	$result	= $obj->query($mySQL);
    $row = $obj->fetch_array($result);
    if (!$row['idEmail']) {
      $message = SUBACCOUNT_21;
	}
    else {
		$pidemail		=	$row['idEmail'];
		$pName 			=	$row['name'];
        $plastName		=	$row['lastName'];
		$pSubPassword	=	$row['subPassword'];
		$pBody			= SUBACCOUNT_16 .$pSubPassword;
		$pEmailSubject	= SUBACCOUNT_20 .$mailData["groupName"];
		$message        = SUBACCOUNT_22;
        if ($row['name'] || $row['lastName']) {$pfullName = $row['lastName'].' '.$row['name'];}else {$pfullName=$sub["email"];}
        $attachments="";
        sendMail($idGroup, $sub["email"], $pfullName,  $pEmailSubject, $pBody, $pBody, $attachments, $groupGlobalCharset, "m");
	}
    $obj->closeDb();
    header("Location:subLogin.php?subemail=".$sub["email"]."&message=".$message."");
}
?>
<html>
<head>
<title><?php echo $mailData["groupName"].' - '.SUBACCOUNT_2;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset?>">
<style type="text/css">
	body {FONT-FAMILY: Arial, Verdana, Helvetica, sans-serif; FONT-SIZE: 12px;}
</style>
</head>
<body style="padding:30px;">
<script Language="Javascript" type="text/javascript">
<!--
function emailvalidation(field, alertbox) {
var goodEmail = field.value.match(/[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?/);
apos=field.value.indexOf("@");dotpos=field.value.lastIndexOf(".");lastpos=field.value.length-1;tldLen = lastpos-dotpos;dmLen=dotpos-apos-1;var badEmail= (tldLen<2 || dmLen<2 || apos<1);
if (goodEmail && !badEmail) {
return true;
}
else {
alert(alertbox);
field.focus();
field.select();
return false;
   }
}
function emptyvalidation(entered, alertbox)
{
with (entered)
{
if (value==null || value=="")
{if (alertbox!="") {alert(alertbox);} return false;}
else {return true;}
}
}
function formvalidation(thisform)
{
with (thisform)
{
if (emailvalidation(email,"<?php echo SUBACCOUNT_18?>")==false) {email.focus(); return false;};
if (emptyvalidation(password,"<?php echo SUBACCOUNT_19?>")==false) {password.focus(); return false;};
}
}
//-->
</script>

<div align=center>
    <span style=" FONT-FAMILY: Arial, helvetica; FONT-SIZE: 20px; FONT-WEIGHT: bold; color:#565656;"><?php echo $mailData["groupName"]?></span>
</div>
<hr /><br><br>
<div align=center>
<form onsubmit="return formvalidation(this)" method="get" name="subform" action="subAccount.php">
	<table border="0" cellspacing=0 cellpadding=4 width=650>
		<tr bgcolor="#eeeeee">
			<td colspan=2>
				<div align=center><b><?php echo SUBACCOUNT_2?></b>
				<br><?php echo SUBACCOUNT_3?></div>
        	</td>
       	</tr>
		<tr>
			<td colspan=2><font color=red><?php echo $message?>&nbsp;</font></td>
		</tr>

       	<tr>
			<td><?php echo SUBACCOUNT_4?>:</td>
			<td>
				<input class=button type="text" name="email" value="<?php echo $sub["email"]?>" >
			</td>
		</tr>
		<tr>
			<td><?php echo SUBACCOUNT_17?>:</td>
			<td>
				<input class=button type="password" name="password" value="" >
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center>
				<input type="submit" class=button name="Submit" value="<?php echo SUBACCOUNT_5?>">
			</td>
		</tr>
</table>
</form>
<br><br>

<form method="get" name="reminder" action="subLogin.php">
	<table border="0" cellspacing=0 cellpadding=4 width=650>
		<tr bgcolor=#eeeeee>
			<td colspan=2>
				<div align=center><b><?php echo SUBACCOUNT_10?></b>
				<br><?php echo SUBACCOUNT_11?></div>
        	</td>
       	</tr>
       	<tr>
			<td>
				<?php echo SUBACCOUNT_4?>:
			</td>
			<td>
				<input class=button type=textbox name="subemail" value="" class="fieldbox11" autocomplete="off">
				&nbsp;&nbsp;<input type="submit" class=button name="emailreminder" value="<?php echo SUBACCOUNT_12?>">
			</td>
		</tr>
		<tr>
			<td colspan=2 style="padding-top:20px; text-align:right;"><a target="_blank" href="privacy.php"><?php echo SUBACCOUNT_26?></a></td>
		</tr>
    </table>
</form>
</div>
<?php if (@$pdemomode) { ?>
	<div align="center" style="margin-top:50px; border-top:#888 1px solid">
		<span style="color:#000;font-size:12px;font-family:Verdana, Arial">This is a demonstration of nuevoMailer.</span>
		<br><a target="blank" href="http://www.nuevomailer.com?demo"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">Click here to learn more.</span></a>
		<div style="margin-top:12px"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">&copy; <?php echo date('Y')?> - DesignerFreeSolutions.com</span></div>
	</div>
<?php   } ?>

</body>
</html>