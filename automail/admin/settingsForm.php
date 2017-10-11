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
include('header.php');
showMessageBox();

$mySQL="SELECT * FROM ".$idGroup."_groupSettings WHERE idGroup=$idGroup";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);

if (!$rows){
	echo "<br><img src='./images/warning.png'>" ."No settings found";
    die;
}
else {
$row = $obj->fetch_array($result);
}
if (@$pdemomode) {
echo "<font color=red>DEMO MODE - Changes will not be saved</font>";
}
$display="";?>
<script type="text/javascript" language="javascript">
function showSmtpData() {
	if($("#groupEmailComponent").val()=="smtp") {
		$("#smtp_1").show();
		//$('#smtp_1').css({backgroundColor:'#fbfbad'});
		$("#smtp_1").effect( "highlight",{color:"#ffff99"}, 5000 );
	}
	else {
		$("#smtp_1").hide();
	}
}
</script>
<table border="0" cellpadding="2" cellspacing="0" width="960px">
		<TR>
			<TD colspan=2 valign="top"><span class="title"><?php echo SETTINGSMODIFYFORM_1; ?></span></td>
			<td align=right><img src="./images/settings.png" width="65" height="51"></td>
		</TR>
</table>
<form name="groupSettings" id="groupSettings" method="post" action="settingsExec.php" autocomplete="off">
	<table border="0" cellpadding="4" cellspacing="0" style="border: #fff 0px solid;width:850px">
		<TR >
		<TD colspan="2" class="settingsRow">
			<a href="#" onClick="expand_many(Array('se_1','se_2','se_3','se_4','se_5','se_6','se_7','se_8','se_9','se_10','se_11','se_12','se_13','se_14'), 'se');return false;"><span class="settingsTab"   id="se"><?php echo SETTINGSMODIFYFORM_2; ?></span></a>
		</td>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_1" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_13; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupName" VALUE="<?php echo $row['groupName']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_2" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_14; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupSenderEmail" VALUE="<?php echo $row['groupSenderEmail']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_3" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_15; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupReplyToEmail" VALUE="<?php echo $row['groupReplyToEmail']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_4" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_69; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupBounceToEmail" VALUE="<?php echo $row['groupBounceToEmail']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_5" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_28; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupContactEmail" VALUE="<?php echo $row['groupContactEmail']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_6" style="display:none">
			<TD valign=top width="450" ><?php echo (SETTINGSMODIFYFORM_17) ; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupSite" VALUE="<?php echo $row['groupSite']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_7" style="display:none">
			<TD valign=top width="450" ><?php echo (SETTINGSMODIFYFORM_16) ; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupScriptUrl" VALUE="<?php echo $row['groupScriptUrl']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_8" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_22; ?></td>
			<td valign=top>
				<select class="select" id="groupEmailComponent" Name="groupEmailComponent" onchange="showSmtpData();return false;">
					<option value="smtp" <?php if (strtolower($row['groupEmailComponent']) == "smtp"){echo " SELECTED";}?>>SMTP</OPTION>
                    <option value="phpmail" <?php if (strtolower($row['groupEmailComponent']) == "phpmail"){echo " SELECTED";}?>>PHP mail</OPTION>
					<option value="sendmail" <?php if (strtolower($row['groupEmailComponent']) == "sendmail"){echo " SELECTED";}?>>SendMail</OPTION>
                    <!--option value="qmail" <?php if (strtolower($row['groupEmailComponent']) == "qmail"){echo " SELECTED";}?>>Q mail</OPTION-->
			  	</select><?php if (strtolower($row['groupEmailComponent']) != "smtp") { $display="none";}?>&nbsp;&nbsp;<a onclick="popUpLayer('<?php echo SMTPSRV_1;?>', 'smtpForm.php',780,600);return false;" href="#"><span id="smtp_1" style="display:<?php echo $display?>"><?php echo SMTPSRV_1;?></span></a>
			</td>
		</tr>

		<!--TR bgcolor="#F7F7F7">
			<TD valign=top width="450" ><?php /*echo SETTINGSMODIFYFORM_23; ?></td>
			<td valign=top>
				<select class="select"  Name="groupDebugSendMail">
					<option value="2" <?php if ($row['groupDebugSendMail']=="2"){echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
					<option value="0" <?php if ($row['groupDebugSendMail']=="0"){echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; */?></OPTION>
				</select>
			</td>
		</TR-->
		<TR bgcolor="#F7F7F7" id="se_9" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_26; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupEncryptionPassword" VALUE="<?php echo $row['groupEncryptionPassword']; ?>" SIZE="12">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_10" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_67; ?></td>
						<TD valign=top width="330" >
				<select class="select" name="groupGlobalCharset" id="groupGlobalCharset">
				<option value="iso-8859-1" <?php if ($row['groupGlobalCharset']=="iso-8859-1" ) {echo " selected";}?>>		Western European (iso-8859-1)</option>
				<option value="utf-8" <?php if ($row['groupGlobalCharset']=="utf-8" ) {echo " selected";}?>>		Unicode - utf-8</option>
				<!--option value="US-ASCII" <?php if ($row['groupGlobalCharset']=="US-ASCII" ) {echo " selected";}?>>	US-ASCII</option-->
				<!--option value="windows-1252" <?php if ($row['groupGlobalCharset']=="windows-1252" ) {echo " selected";}?>>	Western European (windows-1252)</option-->
				<option value="iso-8859-3" <?php if ($row['groupGlobalCharset']=="iso-8859-3" ) {echo " selected";}?>>		Western European (iso-8859-3</option>
				<option value="iso-8859-2" <?php if ($row['groupGlobalCharset']=="iso-8859-2" ) {echo " selected";}?>>		Central European (iso-8859-2)</option>
				<!--option value="windows-1250" <?php if ($row['groupGlobalCharset']=="windows-1250" ) {echo " selected";}?>>	Central European (windows-1250)</option-->
				<option value="iso-8859-5" <?php if ($row['groupGlobalCharset']=="iso-8859-5" ) {echo " selected";}?>>		Cyrillic (iso-8859-5)</option>
				<!--option value="windows-1251" <?php if ($row['groupGlobalCharset']=="windows-1251" ) {echo " selected";}?>>	Cyrillic (windows-1251)</option-->
				<option value="iso-8859-6" <?php if ($row['groupGlobalCharset']=="iso-8859-6" ) {echo " selected";}?>>		Arabic (iso-8859-6)</option>
				<!--option value="windows-1256" <?php if ($row['groupGlobalCharset']=="windows-1256" ) {echo " selected";}?>>	Arabic (windows-1256)</option-->
				<!--option value="windows-1253" <?php if ($row['groupGlobalCharset']=="windows-1253" ) {echo " selected";}?>>	Greek (windows-1253)</option-->
				<option value="iso-8859-7" <?php if ($row['groupGlobalCharset']=="iso-8859-7" ) {echo " selected";}?>>		Greek (iso-8859-7)</option>
				<option value="iso-8859-8" <?php if ($row['groupGlobalCharset']=="iso-8859-8" ) {echo " selected";}?>>		Hebrew (iso-8859-8)</option>
				<!--option value="windows-1255" <?php if ($row['groupGlobalCharset']=="windows-1255" ) {echo " selected";}?>>	Hebrew (windows-1255)</option-->
				<!--option value="IL-ASCII" <?php if ($row['groupGlobalCharset']=="IL-ASCII" ) {echo " selected";}?>>	Hebrew (IL-ASCII)</option-->
				<option value="iso-8859-9" <?php if ($row['groupGlobalCharset']=="iso-8859-9" ) {echo " selected";}?>>		Turkish (iso-8859-9)</option>
				<!--option value="windows-1254" <?php if ($row['groupGlobalCharset']=="windows-1254" ) {echo " selected";}?>>	Turkish (windows-1254)</option-->
				<!--option value="windows-1257" <?php if ($row['groupGlobalCharset']=="windows-1257" ) {echo " selected";}?>>	Baltic (windows-1257)</option-->
				<option value="iso-8859-4" <?php if ($row['groupGlobalCharset']=="iso-8859-4" ) {echo " selected";}?>>		Baltic (iso-8859-4)</option>
				<option value="iso-8859-13" <?php if ($row['groupGlobalCharset']=="iso-8859-13" ) {echo " selected";}?>>	Latvian (iso-8859-13)</option>
				<option value="iso-8859-15" <?php if ($row['groupGlobalCharset']=="iso-8859-15" ) {echo " selected";}?>>	Estonian (iso-8859-15)</option>
				<option value="gb2312" <?php if ($row['groupGlobalCharset']=="gb2312" ) {echo " selected";}?>>			Chinese Simplified (gb2312)</option>
				<option value="big5" <?php if ($row['groupGlobalCharset']=="big5" ) {echo " selected";}?>>			Chinese Traditional - (big5)</option>
				<option value="x-euc-tw" <?php if ($row['groupGlobalCharset']=="x-euc-tw" ) {echo " selected";}?>>		Chinese Traditional (x-euc-tw)</option>
				<option value="shift_jis" <?php if ($row['groupGlobalCharset']=="shift_jis" ) {echo " selected";}?>>		Japanese (shift_jis)</option>
				<option value="euc-jp" <?php if ($row['groupGlobalCharset']=="euc-jp" ) {echo " selected";}?>>			Japanese (euc-jp)</option>
				<option value="iso-2022-jp" <?php if ($row['groupGlobalCharset']=="iso-2022-jp" ) {echo " selected";}?>>		Japanese (iso-2022-jp)</option>
				<option value="ks_c_5601-1987" <?php if ($row['groupGlobalCharset']=="ks_c_5601-1987" ) {echo " selected";}?>>	Korean (ks_c_5601-1987)</option>
				<option value="euc-kr" <?php if ($row['groupGlobalCharset']=="euc-kr" ) {echo " selected";}?>>			Korean (euc-kr)</option>
				<option value="windows-874" <?php if ($row['groupGlobalCharset']=="windows-874" ) {echo " selected";}?>>	Thai (windows-874)</option>
				<option value="tis-620" <?php if ($row['groupGlobalCharset']=="tis-620" ) {echo " selected";}?>>		Thai (tis-620)</option>
				<option value="windows-1258" <?php if ($row['groupGlobalCharset']=="windows-1258" ) {echo " selected";}?>>	Vietnamese (windows-1258)</option>
				<option value="cp1258" <?php if ($row['groupGlobalCharset']=="cp1258" ) {echo " selected";}?>>			Vietnamese (cp1258)</option>
				</select>
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_11" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_64; ?> <?php echo date("Y-m-d H:i:s");?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupTimeOffsetFromServer" VALUE="<?php echo $row['groupTimeOffsetFromServer']; ?>" SIZE="4" title="<?php echo SETTINGSMODIFYFORM_65; ?>">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_12" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_70; ?></td>
			<TD valign=top width="330" >
				<select class="select"  Name="groupDateTimeFormat">
			   		<option value="m/d/Y	g:i a" <?php if ($row['groupDateTimeFormat']=="m/d/Y	g:i a"){echo " SELECTED";} ?>><?php echo date("m/d/Y	g:i a")?></OPTION><!--"date-us"	12/18/2012 05:30:00 PM-->
					<option value="d/m/Y	g:i a" <?php if ($row['groupDateTimeFormat']=="d/m/Y	g:i a"){echo " SELECTED";} ?>><?php echo date("d/m/Y	g:i a")?></OPTION><!--"date-au"	25/12/2006 05:30:00 PM-->
					<option value="d/m/Y	H:i:s" <?php if ($row['groupDateTimeFormat']=="d/m/Y	H:i:s"){echo " SELECTED";} ?>><?php echo date("d/m/Y	H:i:s")?></OPTION><!--"date-eu"	18/12/2012 12:07:26 -->
					<option value="d.m.Y	H:i:s" <?php if ($row['groupDateTimeFormat']=="d.m.Y	H:i:s"){echo " SELECTED";} ?>><?php echo date("d.m.Y	H:i:s")?></OPTION><!--"date-de"	18.12.2012 12:07:26-->
					<option value="d-m-Y	H:i:s" <?php if ($row['groupDateTimeFormat']=="d-m-Y	H:i:s"){echo " SELECTED";} ?>><?php echo date("d-m-Y	H:i:s")?></OPTION><!--"date-eu2"	18-12-2012 12:07:26 -->
					<option value="Y-m-d	H:i:s" <?php if ($row['groupDateTimeFormat']=="Y-m-d	H:i:s"){echo " SELECTED";} ?>><?php echo date("Y-m-d	H:i:s")?></OPTION><!-- "date-iso"	2005-03-26 19:51:34-->
					<option value="Y.m.d	H:i:s" <?php if ($row['groupDateTimeFormat']=="Y.m.d	H:i:s"){echo " SELECTED";} ?>><?php echo date("Y.m.d	H:i:s")?></OPTION><!-- "date-hu"	2005.03.26 19:51:34-->
					<option value="Y/m/d	H:i:s" <?php if ($row['groupDateTimeFormat']=="Y/m/d	H:i:s"){echo " SELECTED";} ?>><?php echo date("Y/m/d	H:i:s")?></OPTION><!-- "date-jp"	2005.03.26 19:51:34-->
				</select>

			</TD>
		</TR>

		<TR bgcolor="#F7F7F7" id="se_13" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_23; ?>&nbsp;<img onmouseover="infoBox('settings_3', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(SETTINGSMODIFYFORM_25);?>', '25em','0')" onmouseout="hide_info_bubble('settings_3','0')" src="./images/helpSmallWhite.gif"><span style="display: none;" id="settings_3"></span></td>
			<td valign=top>
				<select class="select"  Name="groupUseInlineImages">
					<option value="-1" <?php if ($row['groupUseInlineImages']==-1){echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
					<option value="0" <?php if ($row['groupUseInlineImages']==0){echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="se_14" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_8; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupLogo" VALUE="<?php echo $row['groupLogo']; ?>" SIZE="40">
			</TD>
		</TR>
		<tr><td colspan="2" height="5"></td></tr>		
		
<!--BATCH SENDING-->
		<TR >
			<TD colspan=2 class="settingsRow"><a href="#" onClick="expand_many(Array('batch_1','batch_2','batch_3'), 'ba');return false;"><span class="settingsTab" id="ba"><?php echo SETTINGSMODIFYFORM_6; ?></span></a></td>
		</TR>
		<TR bgcolor="#F7F7F7" id="batch_1" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_30; ?></td>
			<td valign=top>
				<select class="select"  Name="groupEnableBatchSending">
				<option value="-1" <?php if ($row['groupEnableBatchSending']==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
				<option value="0" <?php if ($row['groupEnableBatchSending']==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
			</TD>
		</TR>
   		<TR bgcolor="#F7F7F7" id="batch_2" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_31; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupBatchSize" VALUE="<?php echo $row['groupBatchSize']; ?>" SIZE="4">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="batch_3" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_32; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupBatchInterval" VALUE="<?php echo $row['groupBatchInterval']; ?>" SIZE="4">
			</TD>
		</TR>
		<tr><td colspan="2" height="5"></td></tr>
<!--Subscriber options
	Moved to editMessages since v.3.70
-->


<!--TRACKING OPTIONS-->
		<TR >
			<TD colspan="2" class="settingsRow"><a href="#" onClick="expand_many(Array('track_1','track_2','track_3','track_4'), 'tra');return false;"><span id="tra"class="settingsTab"><?php echo SETTINGSMODIFYFORM_3; ?></span></a></td>
		</TR>
		<TR bgcolor="#F7F7F7" id="track_1" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_43; ?></td>
			<td valign=top>
				<select class="select"  Name="groupActiveLinkTrackingHtml">
				<option value="-1" <?php if ($row['groupActiveLinkTrackingHtml']==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
				<option value="0" <?php if ($row['groupActiveLinkTrackingHtml'] ==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
			</TD>
		</TR>
  		<TR bgcolor="#F7F7F7" id="track_2" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_29; ?></td>
			<td valign=top>
				<select class="select"  Name="groupActiveViewsTracking">
				<option value="-1" <?php if ($row['groupActiveViewsTracking']==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
				<option value="0" <?php if ($row['groupActiveViewsTracking']==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
				</TD>
		</TR>
  		<TR bgcolor="#F7F7F7" id="track_3" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_68; ?></td>
			<td valign=top>
				<select class="select"  Name="groupTrackMailTo">
				<option value="-1" <?php if ($row['groupTrackMailTo']==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
				<option value="0" <?php if ($row['groupTrackMailTo']==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
				</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="track_4" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_27; ?></td>
			<td valign=top>
				<select class="select"  Name="groupActiveLinkTrackingText">
				<option value="-1" <?php if ($row['groupActiveLinkTrackingText']==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
				<option value="0" <?php if ($row['groupActiveLinkTrackingText'] ==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
			</TD>
		</TR>
		<tr><td colspan="2" height="5"></td></tr>

<!-- CUSTOM SUBSCRIBER FIELDS-->
		<TR >
			<TD colspan="2" class="settingsRow"><a href="#" onClick="expand_many(Array('csf_1','csf_2','csf_3','csf_4','csf_5'), 'csf');return false;"><span id="csf" class="settingsTab"><?php echo SETTINGSMODIFYFORM_9; ?></span></a></td>
			<!--<font color=#ffffff><?php echo SETTINGSMODIFYFORM_11; ?></font>-->
		</TR>
		<TR bgcolor="#F7F7F7" id="csf_1" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_4;?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupCustomSubField1" VALUE="<?php echo $row['groupCustomSubField1']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="csf_2" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_24;?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupCustomSubField2" VALUE="<?php echo $row['groupCustomSubField2']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="csf_3" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_33;?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupCustomSubField3" VALUE="<?php echo $row['groupCustomSubField3']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="csf_4" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_34;?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupCustomSubField4" VALUE="<?php echo $row['groupCustomSubField4']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="csf_5" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_35;?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupCustomSubField5" VALUE="<?php echo $row['groupCustomSubField5']; ?>" SIZE="40">
			</TD>
		</TR>
		<tr><td colspan="2" height="5"></td></tr>

<!-- POP3 RELATED -->
		<TR>
			<TD colspan="2" class="settingsRow"><a href="#" onClick="expand_many(Array('bm_1','bm_2','bm_3','bm_4','bm_5','bm_6'), 'bm');return false;"><span id="bm" class="settingsTab"><?php echo SETTINGSMODIFYFORM_55; ?></span></a></td>
		</TR>
		<TR bgcolor="#F7F7F7" id="bm_1" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_56; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupPop3Server" VALUE="<?php echo $row['groupPop3Server']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="bm_2" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_63; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupPop3Port" VALUE="<?php echo $row['groupPop3Port']; ?>" SIZE="4">
			</TD>
		</TR>

		<TR bgcolor="#F7F7F7" id="bm_3" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_57; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="Text" Name="groupPop3Username" VALUE="<?php echo $row['groupPop3Username']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="bm_4" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_58; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="password" Name="groupPop3Password" VALUE="<?php echo $row['groupPop3Password']; ?>" SIZE="40">
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="bm_5" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_59; ?></td>
			<TD valign=top width="330" >
				<select class="select"  Name="groupDeleteAutoResponders">
				<option value="-1" <?php if ($row['groupDeleteAutoResponders']==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
				<option value="0" <?php if ($row['groupDeleteAutoResponders']==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
				</select>
			</TD>
		</TR>
		<TR bgcolor="#F7F7F7" id="bm_6" style="display:none">
			<TD valign=top width="450" ><?php echo SETTINGSMODIFYFORM_71; ?></td>
			<TD valign=top width="330" >
				<INPUT class=fieldbox11 TYPE="text" Name="groupPop3Batch" VALUE="<?php echo $row['groupPop3Batch']; ?>" SIZE="4">
			</TD>
		</TR>
		<tr><td colspan="2" height="5"></td></tr>
		<TR >
		<TD></td>
		<td valign=top align="right">
    			<input class="submit" type="Submit" id="save" name="save" value="<?php echo SETTINGSMODIFYFORM_5; ?>">
      			&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="submit" type="Submit" id="cancel" name="cancel" value="<?php echo SETTINGSMODIFYFORM_12; ?>">
		</TD>
		</TR>
		</table>

		</form>
<?php
include('footer.php');
?>