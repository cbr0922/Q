<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupEmailComponent	=	$obj->getSetting("groupEmailComponent", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
$myDay = myDatenow();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html style="background: #fff;">
<head>
<title><?php echo SMTPSRV_1;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset;?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script type="text/javascript" language="javascript">var myCustomEncoding="<?php echo $groupGlobalCharset;?>";</script>
<script src="./scripts/jQuery_2.1.0.js" type="text/javascript"></script>
<script src="./scripts/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="./scripts/scripts.js" type="text/javascript"></script>
<link href="./includes/site.css" rel="stylesheet" type="text/css">
</head>
<body id="body" style="background: #fff;padding:10px"><div id="container"></div>
<div id="confirmBox" style="display: none; z-index: 999;top: 0px; left: 0px;">
	<!-- theparams: Used to store IDs of subscribers when confirm or delete in bulk -->
	<input type="hidden" id="theurl" value=""><input type="hidden" id="theparams" value=""><input type="hidden" id="isAjax" value="">
	<div class="confirmBoxTop">
		<img alt="" src="./images/warning.png" border="0" />&nbsp;&nbsp;<span><?php  echo GENERIC_10; ?></span>
	</div>
  	<div class="confirmBoxBottom">
		<span id="confirmBoxtext" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial"></span>
		<div style="padding-top:10px"><input type="submit" value="<?php echo GENERIC_11; ?>" class="submitSmall" onclick="closeConfirmBox('yes')"/>
		&nbsp;&nbsp;&nbsp;
		<input type="submit" value="<?php echo GENERIC_12; ?>" class="submitSmall" onclick="closeConfirmBox('no')" /></div>
	</div>
</div>
<div id="alertBox" style="display: none; z-index: 999;top: 0px; left: 0px;">
	<input type="hidden" id="alertBoxUrl" value="">
	<div class="confirmBoxTop"><img alt="" src="./images/warning.png" border="0" />&nbsp;&nbsp;<span><?php  echo GENERIC_10; ?></span></div>
  	<div class="confirmBoxBottom">
		<span id="alertBoxtext" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial"></span>
		<div style="padding-top:10px"><br><input type="submit" value="<?php echo GENERIC_13; ?>" class="submitSmall" onclick="closeAlertBox('yes')"/></div>
	</div>
</div>
<?php showMessageBox();
if (strtolower($groupEmailComponent) != "smtp"){echo '<div class="errormessage"><img src="./images/warning.png">&nbsp;'.SMTPSRV_6.'&nbsp;'.SETTINGSMODIFYFORM_22.'&nbsp;'.ADMIN_HEADER_81.'>'.ADMIN_HEADER_60.'>'.ADMIN_HEADER_61.'</div><br>';}?>

<form name="addNew" id="addNew" method="post" action="smtpExec.php" autocomplete="off">
<INPUT TYPE="hidden" Name="action" VALUE="add">
<div style="margin-top:15px">
	<a href="#" onclick="show_hide_div('smtp_1','cross1');return false;" class="title"><span class="title" id="cross1">[+]</span>&nbsp;<span class="title"><?php echo SMTPSRV_2;?></span></a>&nbsp;&nbsp;&nbsp;<img onmouseout="hide_info_bubble('smtpHelp','0')" onmouseover="topInfoBox('smtpHelp', '<?php echo fixJSstring(SMTPSRV_1);?>', '<?php echo fixJSstring(SMTPSRV_5)?>', '30em', '0'); " src="./images/i1.gif" alt=""><span style="display: none;" id="smtpHelp"></span>
</div>
<table id="smtp_1" cellpadding="4" cellspacing="0" style="width:740px;border: #ccc 1px solid;display:none;margin-top:10px">
		<tr bgcolor="#F7F7F7">
			<td valign=top width="400" ><?php echo SETTINGSMODIFYFORM_18; ?></td>
			<td valign=top width="340" >
				<INPUT class=fieldbox11 TYPE="Text" id="groupSmtpServer" Name="groupSmtpServer" VALUE="" SIZE="40">&nbsp;*
			</td>
		</tr>
		<tr bgcolor="#F7F7F7">
			<td valign=top><?php echo SETTINGSMODIFYFORM_21;?></td>
			<td valign=top>
				<INPUT class=fieldbox11 TYPE="Text" id="groupSmtpPort" Name="groupSmtpPort" VALUE="" SIZE="4">&nbsp;*
			</td>
		</tr>

		<tr bgcolor="#F7F7F7">
 			<td valign=top><?php echo SETTINGSMODIFYFORM_54; ?></td>
			<td valign=top>
				<select class="select"  id="groupAuthRequired" Name="groupAuthRequired">
					<option value="-1"><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
					<option value="0"><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
			</td>
		</tr>
		<tr bgcolor="#F7F7F7">
   			<td valign=top><?php echo SETTINGSMODIFYFORM_19; ?></td>
			<td valign=top>
				<INPUT class=fieldbox11 TYPE="Text" id="groupSmtpUsername" Name="groupSmtpUsername" VALUE="" SIZE="40">
			</td>
		</tr>
		<tr bgcolor="#F7F7F7">
			<td valign=top><?php echo SETTINGSMODIFYFORM_20; ?></td>
			<td valign=top>
				<INPUT class=fieldbox11 TYPE="password" id="groupSmtpPassword" Name="groupSmtpPassword" VALUE="" SIZE="40">
			</td>
		</tr>
		<tr bgcolor="#F7F7F7">
			<td valign=top><?php echo SETTINGSMODIFYFORM_75;?></td>
            <td>
				<select class="select" name="groupSmtpSecureConnection" id="groupSmtpSecureConnection">
				<option value="">none</option>
				<option value="ssl">ssl</option>
				<option value="tls">tls</option>
				<!--option value="starttls" <?php //if ($row['groupSmtpSecureConnection']=="starttls" ) {echo " selected";}?>>starttls</option-->
				</select>
			</td>
		</tr>
		<tr bgcolor="#F7F7F7">
			<td valign=top ><?php echo SETTINGSMODIFYFORM_72;?></td>
            <td><INPUT class=fieldbox11 TYPE="Text" id="groupAntiFloodBatch" Name="groupAntiFloodBatch" VALUE="0" SIZE="4">&nbsp;<?php echo SETTINGSMODIFYFORM_73;?>&nbsp;<INPUT class=fieldbox11 TYPE="Text" id="groupAntiFloodPause" Name="groupAntiFloodPause" VALUE="0" SIZE="4">&nbsp;<?php echo SETTINGSMODIFYFORM_74;?>
			</td>
		</tr>
		<tr bgcolor="#F7F7F7">
			<td></td>
			<td valign=top><input class="submitSmall" type="Submit" id="add" name="add" value="<?php echo LISTS_13; ?>">&nbsp;&nbsp;&nbsp;&nbsp;(* <?php echo CAMPAIGNCREATE_50; ?>)</td>
		</tr>
</table>
</form>
<?php
$mySQL="SELECT idSmtp, smtpServer, smtpUsername, smtpPassword, smtpPort, smtpSecureConnection, smtpAuthRequired, smtpAntiFloodBatch, smtpAntiFloodPause, smtpLastUsed, isActive, isPreferred  FROM ".$idGroup."_smtpServers WHERE idGroup=$idGroup ORDER BY idSmtp";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);

if (!$rows){
	echo "<br><img src='./images/warning.png'>"."&nbsp;".SMTPSRV_7;
}
else {
?>
<br><br><span class="title"><?php echo SMTPSRV_1;?></span><br><br>
<?php 
while ($row = $obj->fetch_array($result)){
?>	
<form name="addNew" id="addNew" method="post" action="smtpExec.php" autocomplete="off">
<INPUT TYPE="hidden" Name="action" VALUE="update">
<INPUT TYPE="hidden" Name="idSmtp" VALUE="<?php echo $row['idSmtp'];?>">
<table cellpadding="3" cellspacing="0" style="width:740px; border-top: #ccc 1px solid;border-left: #ccc 1px solid;border-right: #ccc 1px solid; border-collapse: collapse;">
		<TR bgcolor="#ffffe0">
				<TD valign=top width="400"><span class="statsLegend"><?php echo SETTINGSMODIFYFORM_18; ?></span><br><i><?php echo SMTPSRV_8.': ';?> <?php echo addOffset($row['smtpLastUsed'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></i></td>
			<TD valign=top width="340" >
				<INPUT class=fieldbox11 TYPE="Text" id="groupSmtpServer" Name="groupSmtpServer" VALUE="<?php echo $row['smtpServer']; ?>" SIZE="40">&nbsp;*
				
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7">
			<TD valign=top ><?php echo SETTINGSMODIFYFORM_21;?></td>
			<TD valign=top>
				<INPUT class=fieldbox11 TYPE="Text" id="groupSmtpPort" Name="groupSmtpPort" VALUE="<?php echo $row['smtpPort']; ?>" SIZE="4">&nbsp;*
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7">
 			<TD valign=top ><?php echo SETTINGSMODIFYFORM_54; ?></td>
			<td valign=top>
				<select class="select"  id="groupAuthRequired" Name="groupAuthRequired">
					<option value="-1" <?php if ($row['smtpAuthRequired']=="-1"){echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
					<option value="0" <?php if ($row['smtpAuthRequired']=="0"){echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
			</td>
		</TR>
		<TR bgcolor="#F7F7F7">
   			<TD valign=top><?php echo SETTINGSMODIFYFORM_19; ?></td>
			<TD valign=top>
				<INPUT class=fieldbox11 TYPE="Text" id="groupSmtpUsername" Name="groupSmtpUsername" VALUE="<?php echo $row['smtpUsername']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7">
			<TD valign=top><?php echo SETTINGSMODIFYFORM_20; ?></td>
			<TD valign=top>
				<INPUT class=fieldbox11 TYPE="password" id="groupSmtpPassword" Name="groupSmtpPassword" VALUE="<?php echo $row['smtpPassword']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7">
			<TD valign=top><?php echo SETTINGSMODIFYFORM_75;?></td>
            <td>
				<select class="select" name="groupSmtpSecureConnection" id="groupSmtpSecureConnection">
				<option value="" <?php if ($row['smtpSecureConnection']=="" ) {echo " selected";}?>>none</option>
				<option value="ssl" <?php if ($row['smtpSecureConnection']=="ssl" ) {echo " selected";}?>>ssl</option>
				<option value="tls" <?php if ($row['smtpSecureConnection']=="tls" ) {echo " selected";}?>>tls</option>
				<!--option value="starttls" <?php //if ($row['smtpSecureConnection']=="starttls" ) {echo " selected";}?>>starttls</option-->
				</select>
			</TD>
		</TR>

		<TR bgcolor="#F7F7F7">
			<TD valign=top><?php echo SETTINGSMODIFYFORM_72;?></td>
            <td><INPUT class=fieldbox11 TYPE="Text" id="groupAntiFloodBatch" Name="groupAntiFloodBatch" VALUE="<?php echo $row['smtpAntiFloodBatch']; ?>" SIZE="4">&nbsp;<?php echo SETTINGSMODIFYFORM_73;?>&nbsp;<INPUT class=fieldbox11 TYPE="Text" id="groupAntiFloodPause" Name="groupAntiFloodPause" VALUE="<?php echo $row['smtpAntiFloodPause']; ?>" SIZE="4">&nbsp;<?php echo SETTINGSMODIFYFORM_74;?>
			</TD>
		</TR>
		<tr bgcolor="#F7F7F7" >
			<td style="border-bottom: #ccc 1px solid">
				<?php echo SMTPSRV_3;?>:&nbsp;<input type="checkbox" name="isActive" id="isActive" value="-1" <?php if($row['isActive']==-1) {echo ' checked';} ?>>
				&nbsp;
				<?php echo SMTPSRV_4;?>:&nbsp;<input type="checkbox" name="isPreferred" id="isPreferred" value="-1" <?php if($row['isPreferred']==-1) {echo ' checked';} ?>>
				
			</td>
			<td valign=top style="border-bottom: #ccc 1px solid">
   				<input class="submitSmall" type="Submit" id="update" name="update" value="<?php echo SETTINGSMODIFYFORM_5; ?>">
				&nbsp;&nbsp;&nbsp;
				<input class="submitSmall" type="Submit" id="delete" name="delete" onclick="openConfirmBox('smtpExec.php?idSmtp=<?php echo $row['idSmtp']?>&action=delete','<?php echo fixJSstring(SMTPSRV_13).'<br>'.fixJSstring(GENERIC_2)?>');return false;" value="<?php echo EDITCOUNTRIESFORM_8; ?>">
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-left: #fff 1px solid;border-right: #fff 1px solid; border-collapse: collapse;">&nbsp;</td>
		</tr>

</table>
</form>

<?php
} //while

} //when it finds smtp servers

?>
 <div id="screenblur" style="display: none; position: fixed; top: 0pt; left: 0pt; z-index: 998; width: 100%; height:100%; background-image: url(./images/screenblur.png);"></div>
</body>
</html>