<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 					= 	new db_class();
$groupName 				=	$obj->getSetting("groupName", $idGroup);
$groupSite 				=	$obj->getSetting("groupSite", $idGroup);
$groupContactEmail 		=	$obj->getSetting("groupContactEmail", $idGroup);
include('header.php');
showMessageBox();
$groupShowWelcomeScreen		= $obj->getSetting("groupShowWelcomeScreen", $idGroup);		//also editable in settings page
$groupWelcomeScreen			= $obj->getSetting("groupWelcomeScreen", $idGroup);
$groupWelcomeUrl			= $obj->getSetting("groupWelcomeUrl", $idGroup);
$groupSendWelcomeEmail		= $obj->getSetting("groupSendWelcomeEmail", $idGroup);		//also editable in settings page
$groupWelcomeEmailBody		= $obj->getSetting("groupWelcomeEmailBody", $idGroup);
$groupWelcomeEmailBodyT		= $obj->getSetting("groupWelcomeEmailBodyT", $idGroup);
$groupWelcomeEmailSubject	= $obj->getSetting("groupWelcomeEmailSubject", $idGroup);

$groupShowConfReqScreen		= $obj->getSetting("groupShowConfReqScreen", $idGroup);		//also editable in settings page
$groupConfReqScreen			= $obj->getSetting("groupConfReqScreen", $idGroup);
$groupConfReqUrl			= $obj->getSetting("groupConfReqUrl", $idGroup);
$groupConfReqEmailBody		= $obj->getSetting("groupConfReqEmailBody", $idGroup);
$groupConfReqEmailBodyT		= $obj->getSetting("groupConfReqEmailBodyT", $idGroup);
$groupConfReqEmailSubject	= $obj->getSetting("groupConfReqEmailSubject", $idGroup);

$groupShowGoodbyeScreen		= $obj->getSetting("groupShowGoodbyeScreen", $idGroup);		//also editable in settings page
$groupGoodbyeScreen			= $obj->getSetting("groupGoodbyeScreen", $idGroup);
$groupGoodbyeUrl			= $obj->getSetting("groupGoodbyeUrl", $idGroup);
$groupSendGoodbyeEmail		= $obj->getSetting("groupSendGoodbyeEmail", $idGroup);		//also editable in settings page
$groupGoodbyeEmailBody		= $obj->getSetting("groupGoodbyeEmailBody", $idGroup);
$groupGoodbyeEmailBodyT		= $obj->getSetting("groupGoodbyeEmailBodyT", $idGroup);
$groupGoodbyeEmailSubject	= $obj->getSetting("groupGoodbyeEmailSubject", $idGroup);
$groupRequestOptOutReason	= $obj->getSetting("groupRequestOptOutReason", $idGroup);
$groupAlreadyInAction		= $obj->getSetting("groupAlreadyInAction", $idGroup);		//also editable in settings page
$groupAlreadyInScreen		= $obj->getSetting("groupAlreadyInScreen", $idGroup);
$groupAlreadyInUrl			= $obj->getSetting("groupAlreadyInUrl", $idGroup);

$groupDoubleOptin 			= $obj->getSetting("groupDoubleOptin", $idGroup);

If ($groupSendWelcomeEmail==-1) {
	$groupSendWelcomeEmailStatus= EDITMESSAGES_16;
} else {
	$groupSendWelcomeEmailStatus= EDITMESSAGES_17;
}
if ($groupShowWelcomeScreen==-1) {
	$groupShowWelcomeScreenU= EDITMESSAGES_15;
	$groupShowWelcomeScreenS= EDITMESSAGES_14;
} else {
	$groupShowWelcomeScreenU= EDITMESSAGES_14;
	$groupShowWelcomeScreenS= EDITMESSAGES_15;
}
If ($groupSendGoodbyeEmail==-1) {
	$groupSendGoodbyeEmailStatus= EDITMESSAGES_18;
} else {
	$groupSendGoodbyeEmailStatus= EDITMESSAGES_19;
}
if ($groupShowGoodbyeScreen==-1) {
	$groupShowGoodbyeScreenU= EDITMESSAGES_15;
	$groupShowGoodbyeScreenS= EDITMESSAGES_14;
} else {
	$groupShowGoodbyeScreenU= EDITMESSAGES_14;
	$groupShowGoodbyeScreenS= EDITMESSAGES_15;
}
if ($groupShowConfReqScreen==-1) {
	$groupShowConfReqScreenU= EDITMESSAGES_15;
	$groupShowConfReqScreenS= EDITMESSAGES_14;
} else {
	$groupShowConfReqScreenU= EDITMESSAGES_14;
	$groupShowConfReqScreenS= EDITMESSAGES_15;
}
if ($groupAlreadyInAction==1) {
	$groupShowAlreadyInMessage = "";
	$groupShowAlreadyInScreenU= EDITMESSAGES_15;
	$groupShowAlreadyInScreenS= EDITMESSAGES_14;
} else if ($groupAlreadyInAction==2) {
	$groupShowAlreadyInMessage = "";
	$groupShowAlreadyInScreenU= EDITMESSAGES_14;
	$groupShowAlreadyInScreenS= EDITMESSAGES_15;
} else if ($groupAlreadyInAction==3) {
	$groupShowAlreadyInMessage = ' - ' .EDITMESSAGES_13;
	$groupShowAlreadyInScreenU= EDITMESSAGES_15;
	$groupShowAlreadyInScreenS= EDITMESSAGES_15;
}
?>
<script language="JavaScript" type="text/javascript" src='./editor/innovaeditor.js'></script>
<script type="text/javascript" language="javascript">
function swapConf() {
	if ($("#groupShowConfReqScreen").val()=="-1") {
		$("#confScreen").show();
		$("#confUrl").hide();
	}
	else {
		$("#confUrl").show();
		$("#confScreen").hide();
	}
}
function swapWelc() {
	if ($("#groupShowWelcomeScreen").val()=="-1") {
		$("#welcScreen").show();
		$("#welcUrl").hide();
	}
	else {
		$("#welcUrl").show();
		$("#welcScreen").hide();
	}
}
/*function swapWelcEmail() {
	if ($("#groupSendWelcomeEmail").val()=="-1") {
		$("#welcTable_1, #welcTable_2, #welcTable_3").show();
	}
	else {
		$("#welcTable_1, #welcTable_2, #welcTable_3").hide();
	}
}*/
/*function swapGoodByeEmail() {
	if ($("#groupSendGoodbyeEmail").val()=="-1") {
		$("#goodByeTable_1, #goodByeTable_2, #goodByeTable_3").show();
	}
	else {
		$("#goodByeTable_1, #goodByeTable_2, #goodByeTable_3").hide();
	}
}*/
function swapGoodbye() {
	if ($("#groupShowGoodbyeScreen").val()=="-1") {
		$("#GoodbyeScreen").show();
		$("#GoodbyeUrl").hide();
	}
	else {
		$("#GoodbyeUrl").show();
		$("#GoodbyeScreen").hide();
	}
}
function swapAlrdin() {
	if ($("#groupAlreadyInAction").val()=="1") {
		$("#AlreadyInScreen").show();
		$("#AlreadyInUrl").hide();
		$("#ainAccountRdr").hide();
	}
	else if ($("#groupAlreadyInAction").val()=="2"){
		$("#AlreadyInUrl").show();
		$("#AlreadyInScreen").hide();
		$("#ainAccountRdr").hide();
	}
	else {
		$("#AlreadyInUrl").hide();
		$("#AlreadyInScreen").hide();
		$("#ainAccountRdr").show();
	}
}
</script>
<table width="960px" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top"><span class="title"><?php echo EDITMESSAGES_1; ?></span></td>
		<td valign="top" align="right"><img src="./images/messages.png" width="65" height="51" alt=""></td>
	</tr>
	<tr>
		<td valign=top width="70%"><div style="width:500px"><?php echo EDITMESSAGES_10;?></div></td>
		<td valign=top width="30%">
			<img alt="" border=0 src="./images/i2.gif">&nbsp;<a href="#" onclick="infoBox('textnotes', '<?php echo fixJSstring(EDITMESSAGES_29)?>', '<?php echo fixJSstring(EDITMESSAGES_30)?>', '30em', '1');return false;" title="<?php echo GENERIC_6; ?>"><?php echo EDITMESSAGES_29.'...'; ?></a>
		   <div style="display:none;" id="textnotes"></div>
		</td>
	</tr>
</table>
<br><br>


<!-- WELCOME MESSAGES START	-->
<?php
if ($groupSendWelcomeEmail==-1){$welcTableDisplay="table-row";} else {$welcTableDisplay="none";};
?>
<div style="background-color:#FFFFCC;border: #CCC 1px solid;border-radius:2px;padding:20px">
	<span  onclick="show_hide_div('welcome','cross1');return false;" class="menuSmall" style="cursor: pointer;"><strong><span id="cross1">[+]</span>&nbsp;<?php echo EDITMESSAGES_7; ?></strong></span>&nbsp;&nbsp;&nbsp;<span class="submitSmall" style="float:right" onclick="show_hide_div('welcome','cross1');"><?php echo HOME_3;?></span>
	<div id="welcome" style="display:none;padding-top:15px;padding-bottom:25px;">
		<form action="editMessagesExec.php" method="post" id="Form1">
		<table  border="0" cellpadding="6" cellspacing="0" width="100%">
			<tr style="background-color:#6B78B4;">
				<td colspan="2">			
					<div><span class="option"><?php echo SETTINGSMODIFYFORM_49; ?></span>&nbsp;
						<select class="select"  name="groupSendWelcomeEmail" id="groupSendWelcomeEmail" onchange="expand_many(Array('welcTable_1','welcTable_2','welcTable_3'));">
							<option value="-1" <?php if ($groupSendWelcomeEmail==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
							<option value="0" <?php if ($groupSendWelcomeEmail==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
						</select>
					</div>
				</td>
			</tr>
			<tr id="welcTable_1" style="display:<?php echo $welcTableDisplay;?>;">
				<td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_3;?>:</strong>
					<input name="groupWelcomeEmailSubject" type="text" class="fieldbox11" size="60" value="<?php echo strForInput($groupWelcomeEmailSubject)?>">&nbsp;(<?php echo $groupSendWelcomeEmailStatus?>)
				</td>
			</tr>
			<tr id="welcTable_2" style="display:<?php echo $welcTableDisplay;?>;">
				<td valign="top"  colspan="2">
					<strong><?php echo EDITMESSAGES_4; ?>:</strong><br><br>
					<textarea id="groupWelcomeEmailBody" name="groupWelcomeEmailBody" rows=4 cols=30><?php echo encodeHTML($groupWelcomeEmailBody);?></textarea>
					<script language="JavaScript" type="text/javascript">
						var oEdit3 = new InnovaEditor("oEdit3");
						oEdit3.mode="XHTML";
								oEdit3.enableFlickr = false;
								oEdit3.enableCssButtons = false;
								oEdit3.enableLightbox = false;
								oEdit3.fileBrowser = "../assetmanager/asset.php";	//supposed to be in the common folder.
								oEdit3.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
						    	oEdit3.groups = [
						        	["group1", "", [
										/*DO NOT USE: "TextDialog","FontDialog","Quote","FlashDialog", "YoutubeDialog","InternalLink","CustomObject", "MyCustomButton", */
										"FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
										"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
										"smartlinks2","CustomTag",
										"BRK",
										"CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
										"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL"]]
			        			];
								oEdit3.arrCustomButtons = [
									["smartlinks2","modelessDialogShow('smartLinks2.php',400,200)","<?php echo fixJSstring(SMARTLINKS_1)?>","smartLinks.gif", "85"]
								 ];
						oEdit3.arrCustomTag=[
						["<?php echo TAGCHOSENLISTS?>","#listlisting#"],["<?php echo TAGSUBSCRIBERNAME?>","#subname#"],["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"],["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"],
						["<?php echo TAGSUBSCRIBERPASSWORD?>","#subpasscode#"],["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"], ["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"], ["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"],
						["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"], ["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"], ["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"], ["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"],
						["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"], ["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"], ["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"], ["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"],
		                ["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"], ["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"], ["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"],
						["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"],["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"],["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"],
						["<?php echo TAGDATETIME?>","#date_time#"],["<?php echo $groupName?>","<?php echo $groupName?>"],["<?php echo $groupSite?>","<?php echo $groupSite?>"],
						["<?php echo $groupContactEmail?>","<?php echo $groupContactEmail?>"]];
						oEdit3.width="850";
						oEdit3.height="300";
						oEdit3.REPLACE("groupWelcomeEmailBody");
					</script>
				</td>
			</tr>
			<tr id="welcTable_3" style="display:<?php echo $welcTableDisplay;?>;">
				<td valign="top"  colspan="2">
					<strong><?php echo EDITMESSAGES_23; ?>:</strong><br><br>
					<textarea rows='15' cols='100' class="textarea" name="groupWelcomeEmailBodyT" wrap="hard"><?php echo $groupWelcomeEmailBodyT?></textarea>
				</td>
			</tr>
			<tr style="background-color:#6B78B4;border-top:#FFFFFF 1px solid">
				<td colspan="2" style="border-top:#FFFFFF 1px solid">
					<div><span class="option"><?php echo SETTINGSMODIFYFORM_51; ?></span></div>
					<select class="select" onchange="swapWelc();" name="groupShowWelcomeScreen" id="groupShowWelcomeScreen">
						<option value="-1" <?php if ($groupShowWelcomeScreen==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_60; ?></OPTION>
						<option value="0" <?php if ($groupShowWelcomeScreen==0) { echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_61; ?></OPTION>
					</select>
				</td>
			</tr>	
				<?php 
				$welcUrlOption="table-row";
				$welcScreenOption="table-row";
				if ($groupShowWelcomeScreen=="-1") {$welcScreenOption="table-row";$welcUrlOption="none";}else {$welcScreenOption="none";$welcUrlOption="table-row";}
				?>	
			<tr id="welcUrl" style="display:<?php echo $welcUrlOption;?>;">
				<td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_20;?>:</strong>
					<input name="groupWelcomeUrl" type="text" class="fieldbox11" size="60" value="<?php echo $groupWelcomeUrl?>">&nbsp; (<?php echo $groupShowWelcomeScreenU?>)
				</td>
			</tr>
			<tr id="welcScreen" style="display:<?php echo $welcScreenOption;?>">
				<td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_6;?></strong>&nbsp;(<?php echo $groupShowWelcomeScreenS?>):
					<br><br>
					<textarea id="groupWelcomeScreen" name="groupWelcomeScreen" rows=4 cols=30><?php echo encodeHTML($groupWelcomeScreen);?></textarea>
					<script language="JavaScript" type="text/javascript">
						var oEdit4 = new InnovaEditor("oEdit4");
						oEdit4.mode="XHTML";
						oEdit4.enableFlickr = false;
						oEdit4.enableCssButtons = false;
						oEdit4.enableLightbox = false;
						oEdit4.fileBrowser = "../assetmanager/asset.php";	//supposed to be in the common folder.
						oEdit4.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
				    	oEdit4.groups = [
				        	["group1", "", ["FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
							"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
							"CustomTag","BRK","CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
							"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL"]]
	        			];
						oEdit4.arrCustomTag=[
						["<?php echo TAGCHOSENLISTS?>","#listlisting#"],["<?php echo TAGSUBSCRIBERNAME?>","#subname#"],["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"],["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"],
						["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"],["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"],["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"],["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"],
						["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"],["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"],["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"],["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"],
						["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"],["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"],["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"],["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"],
						["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"],["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"],["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"],["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"],
						["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"],["<?php echo TAGDATETIME?>","#date_time#"],["<?php echo $groupName?>","<?php echo $groupName?>"],["<?php echo $groupSite?>","<?php echo $groupSite?>"],
						["<?php echo $groupContactEmail?>","<?php echo $groupContactEmail?>"]];
						oEdit4.width="850";
						oEdit4.height="350";
						oEdit4.REPLACE("groupWelcomeScreen");
					</script>
				</td>
			</tr>
		</table>
		<div align="center"><input type="submit" class="submit" id="savewelcome" name="savewelcome" value="<?php echo EDITMESSAGES_9; ?>"></div>
		</form>
	</div>
</div>
<br><br>

<!-- CONFIRNATION REQUIRED MESSAGES-->	
<?php
if ($groupDoubleOptin==-1){$confTableDisplay="inline";} else {$confTableDisplay="none";};
?>
<div style="background-color:#FFFFCC;border: #CCC 1px solid;border-radius:2px;padding:20px">
	<span onclick="show_hide_div('confRequired','cross2');return false;" class="menuSmall" style="cursor: pointer;"><strong><span id="cross2">[+]</span>&nbsp;<?php echo EDITMESSAGES_2; ?></strong></span>&nbsp;&nbsp;&nbsp;<?php if ($groupDoubleOptin=="-1") {echo EDITMESSAGES_14;}else {echo EDITMESSAGES_15;}?>&nbsp;&nbsp;&nbsp;<span class="submitSmall" style="float:right" onclick="show_hide_div('confRequired','cross2');"><?php echo HOME_3;?></span>
	<div  id="confRequired" name="confRequired" style="display:none">
		<form action="editMessagesExec.php" method="post" id="Form2">
		<div style="padding:6px;margin-top:15px;margin-bottom:15px;background-color:#6B78B4;">
			<span class="option"><?php echo SETTINGSMODIFYFORM_40?>&nbsp;</span>
			<select class="select"  name="groupDoubleOptin" id="groupDoubleOptin" onchange="show_hide_div('confTable','')">
				<option value="-1" <?php if ($groupDoubleOptin==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
				<option value="0" <?php if ($groupDoubleOptin==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
			</select>
		</div>
		 <table id="confTable" border="0" cellpadding="6" cellspacing="0" width="100%" style="display:<?php echo $confTableDisplay;?>">
			<tr>
				<td width="50" valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_3?>:</strong>
					<input name="groupConfReqEmailSubject" type="text" class="fieldbox11" size="60" value="<?php echo strForInput($groupConfReqEmailSubject)?>" >
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_4?>:</strong>
					<textarea id="groupConfReqEmailBody" name="groupConfReqEmailBody" rows=4 cols=30><?php echo encodeHTML($groupConfReqEmailBody);?></textarea>
					<script language="JavaScript" type="text/javascript">
						var oEdit1 = new InnovaEditor("oEdit1");
						oEdit1.mode="XHTML";
						oEdit1.enableFlickr = false;oEdit1.enableCssButtons = false;oEdit1.enableLightbox = false;oEdit1.fileBrowser = "../assetmanager/asset.php";	//supposed to be in the common folder.
						oEdit1.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
				    	oEdit1.groups = [
				        	["group2", "", [
								"FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
								"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
								"smlinks","CustomTag","BRK",
								"CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
								"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL"]]
	        			];
						oEdit1.arrCustomButtons = [
							["smlinks","modelessDialogShow('smartLinks2.php',400,200)","<?php echo fixJSstring(SMARTLINKS_1)?>","smartLinks.gif", "85"]
						 ];
						oEdit1.arrCustomTag=[
						["<?php echo TAGCHOSENLISTS?>","#listlisting#"], ["<?php echo TAGSUBSCRIBERNAME?>","#subname#"], ["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"],
						["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"], ["<?php echo TAGSUBSCRIBERPASSWORD?>","#subpasscode#"], ["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"],
						["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"], ["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"], ["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"],
						["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"], ["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"], ["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"],
						["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"],["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"], ["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"],
		                ["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"], ["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"], ["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"],
		  				["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"], ["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"], ["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"],
						["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"], ["<?php echo TAGDATETIME?>","#date_time#"], ["<?php echo $groupName?>","<?php echo $groupName?>"],
						["<?php echo $groupSite?>","<?php echo $groupSite?>"], ["<?php echo $groupContactEmail?>","<?php echo $groupContactEmail?>"]];
						oEdit1.width="850";
						oEdit1.height="300";
						oEdit1.REPLACE("groupConfReqEmailBody");
					</script>
				</td>
			</tr>
			<tr>
				<td valign="top"  colspan="2">
					<strong><?php echo EDITMESSAGES_23?>:</strong><br><br>
				   <textarea rows='15' cols='100' class="textarea" name="groupConfReqEmailBodyT" wrap="hard"><?php echo $groupConfReqEmailBodyT?></textarea>
				</td>
			</tr>
			
			<tr style="background-color:#6B78B4;">
				<td colspan="2">
					<div><span class="option"><?php echo SETTINGSMODIFYFORM_53; ?></span></div>
					<select class="select" name="groupShowConfReqScreen" id="groupShowConfReqScreen" onchange="swapConf()" >
						<option value="-1" <?php if ($groupShowConfReqScreen==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_60; ?></OPTION>
						<option value="0" <?php if ($groupShowConfReqScreen==0) { echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_61; ?></OPTION>
					</select>
				</td>
			</tr>	
			<?php 
			$confUrlOption="table-row";
			$confScreenOption="table-row";
			if ($groupShowConfReqScreen=="-1") {$confScreenOption="table-row";$confUrlOption="none";}else {$confScreenOption="none";$confUrlOption="table-row";}
			?>	
			<tr id="confUrl" style="display:<?php echo $confUrlOption;?>;">
				<td valign="top" style="padding-top:15px;" colspan="2">
					<strong><?php echo EDITMESSAGES_20?>:</strong>
					<input name="groupConfReqUrl" type="text" class="fieldbox11" size="60" value="<?php echo $groupConfReqUrl?>" >&nbsp;(<?php echo $groupShowConfReqScreenU?>)
				</td>
			</tr>

			<tr id="confScreen" style="display:<?php echo $confScreenOption;?>;">
				<td valign="top" style="padding-top:15px;"  colspan="2">
					<strong><?php echo EDITMESSAGES_21?></strong>&nbsp;(<?php echo $groupShowConfReqScreenS?>):
					<textarea id="groupConfReqScreen" name="groupConfReqScreen" rows=4 cols=30><?php echo encodeHTML($groupConfReqScreen);?></textarea>
					<script language="JavaScript" type="text/javascript">
						var oEdit2 = new InnovaEditor("oEdit2");
						oEdit2.mode="XHTML";
						oEdit2.enableFlickr = false;oEdit2.enableCssButtons = false;oEdit2.enableLightbox = false;oEdit2.fileBrowser = "../assetmanager/asset.php";	//supposed to be in the common folder.
						oEdit2.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
						oEdit2.groups = [
				        	["group2", "", [
								"FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
								"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
								"CustomTag","BRK","CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
								"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL"]]
	        			];
						oEdit2.arrCustomTag=[
						["<?php echo TAGCHOSENLISTS?>","#listlisting#"], ["<?php echo TAGSUBSCRIBERNAME?>","#subname#"], ["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"],
						["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"], ["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"],["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"], 
						["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"], ["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"], ["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"],
						["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"], ["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"], ["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"],
						["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"], ["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"], ["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"],
		                ["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"], ["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"], ["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"],
						["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"], ["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"], ["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"],
						["<?php echo TAGDATETIME?>","#date_time#"], ["<?php echo $groupName?>","<?php echo $groupName?>"], ["<?php echo $groupSite?>","<?php echo $groupSite?>"],
						["<?php echo $groupContactEmail?>","<?php echo $groupContactEmail?>"]];
						oEdit2.width="850";
						oEdit2.height="300";
						oEdit2.REPLACE("groupConfReqScreen");
					</script>
				</td>
			</tr>
		</table>
		<div align="center"><input type="submit" class="submit" id="saveconfreq" name="saveconfreq" value="<?php echo EDITMESSAGES_9; ?>"></div>
		</form>
	</div>
</div>
<br><br>


<!-- GOODBYE MESSAGES START	-->
<?php
if ($groupSendGoodbyeEmail==-1){$goodbyeTableDisplay="table-row";} else {$goodbyeTableDisplay="none";};
?>
<div style="background-color:#FFFFCC;border: #CCC 1px solid;border-radius:2px;padding:20px">
	<span onclick="show_hide_div('goodbye','cross3');return false;" class="menuSmall" style="cursor: pointer;"><strong><span id="cross3">[+]</span>&nbsp;<?php echo EDITMESSAGES_8; ?></strong></span>&nbsp;&nbsp;&nbsp;<span class="submitSmall" style="float:right"  onclick="show_hide_div('goodbye','cross3');"><?php echo HOME_3;?></span>
	<div  id="goodbye" style="display:none;padding-top:15px;padding-bottom:25px;">
		<form action="editMessagesExec.php" method="post" id="Form3">
		<table  border="0" cellpadding="6" cellspacing="0" width="100%">
			<tr style="background-color:#6B78B4;">
				<td colspan="2">			
					<div><span class="option"><?php echo SETTINGSMODIFYFORM_66; ?></span>&nbsp;
						<select class="select"  name="groupRequestOptOutReason" id="groupRequestOptOutReason">
							<option value="-1" <?php if ($groupRequestOptOutReason==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
							<option value="0" <?php if ($groupRequestOptOutReason==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
						</select>
					</div>
				</td>
			</tr>		

			<tr style="background-color:#6B78B4;">
				<td colspan="2">			
					<div><span class="option"><?php echo SETTINGSMODIFYFORM_50; ?></span>&nbsp;
						<select class="select"  name="groupSendGoodbyeEmail" id="groupSendGoodbyeEmail" onchange="expand_many(Array('goodByeTable_1','goodByeTable_2','goodByeTable_3'));">
							<option value="-1" <?php if ($groupSendGoodbyeEmail==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_41; ?></OPTION>
							<option value="0" <?php if ($groupSendGoodbyeEmail==0) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_42; ?></OPTION>
						</select>
					</div>
				</td>
			</tr>		
		   <tr id="goodByeTable_1" style="display:<?php echo $goodbyeTableDisplay;?>;">
				<td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_3?></strong>:
					<input name="groupGoodbyeEmailSubject" type="text" class="fieldbox11" size="60" value="<?php echo strForInput($groupGoodbyeEmailSubject)?>">&nbsp;(<?php echo $groupSendGoodbyeEmailStatus?>)
				</td>
			</tr>
			<tr id="goodByeTable_2" style="display:<?php echo $goodbyeTableDisplay;?>;">
				<td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_4?></strong>:<br><br>
					<textarea id="groupGoodbyeEmailBody" name="groupGoodbyeEmailBody" rows=4 cols=30><?php echo encodeHTML($groupGoodbyeEmailBody)?></textarea>
					<script language="JavaScript" type="text/javascript">
						var oEdit5 = new InnovaEditor("oEdit5");
						oEdit5.mode="XHTML";
								oEdit5.enableFlickr = false;
								oEdit5.enableCssButtons = false;
								oEdit5.enableLightbox = false;
								oEdit5.fileBrowser = "../assetmanager/asset.php";	//supposed to be in the common folder.
								oEdit5.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
						    	oEdit5.groups = [
						        	["group2", "", [
										/*DO NOT USE: "TextDialog","FontDialog","Quote","FlashDialog", "YoutubeDialog","InternalLink","CustomObject", "MyCustomButton", */
										"FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
										"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
										"CustomTag",
										"BRK",
										"CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
										"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL"]]
			        			];
						oEdit5.arrCustomTag=[
						["<?php echo TAGSUBSCRIBERNAME?>","#subname#"], ["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"], ["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"],
						["<?php echo TAGSUBSCRIBERPASSWORD?>","#subpasscode#"], ["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"], ["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"],
						["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"], ["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"], ["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"],
						["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"], ["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"], ["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"],
						["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"], ["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"], ["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"],
		                ["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"], ["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"], ["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"],
						["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"], ["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"], ["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"],
						["<?php echo TAGDATETIME?>","#date_time#"], ["<?php echo $groupName?>","<?php echo $groupName?>"], ["<?php echo $groupSite?>","<?php echo $groupSite?>"],
						["<?php echo $groupContactEmail?>","<?php echo $groupContactEmail?>"]];
						oEdit5.width="850";
						oEdit5.height="300";
						oEdit5.REPLACE("groupGoodbyeEmailBody");
					</script>
		 		</td>
			</tr>
			<tr id="goodByeTable_3" style="display:<?php echo $goodbyeTableDisplay;?>;">
			   	<td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_23?></strong>:<br><br>
					<textarea rows='15' cols='100' class="textarea"  name="groupGoodbyeEmailBodyT" wrap="hard"><?php echo $groupGoodbyeEmailBodyT?></textarea>
				</td>
			</tr>
			<tr style="background-color:#6B78B4;border-top:#FFFFFF 1px solid">
				<td colspan="2" style="border-top:#FFFFFF 1px solid">
					<div><span class="option"><?php echo SETTINGSMODIFYFORM_52; ?></span></div>
					<select class="select" onchange="swapGoodbye();" name="groupShowGoodbyeScreen" id="groupShowGoodbyeScreen">
						<option value="-1" <?php if ($groupShowGoodbyeScreen==-1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_60; ?></OPTION>
						<option value="0" <?php if ($groupShowGoodbyeScreen==0) { echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_61; ?></OPTION>
					</select>
				</td>
			</tr>	
			<?php 
				$goodByeUrlOption="table-row";
				$goodByeScreenOption="table-row";
				if ($groupShowGoodbyeScreen=="-1") {$goodByeScreenOption="table-row";$goodByeUrlOption="none";}else {$goodByeScreenOption="none";$goodByeUrlOption="table-row";}
				?>				
		   <tr id="GoodbyeUrl" name="GoodbyeUrl" style="display:<?php echo $goodByeUrlOption;?>;">
			   <td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_20?></strong>:
					<input id="groupGoodbyeUrl" name="groupGoodbyeUrl" type="text" class="fieldbox11" size="60" value="<?php echo $groupGoodbyeUrl?>">&nbsp; (<?php echo $groupShowGoodbyeScreenU?>)
				</td>
			</tr>
			<tr id="GoodbyeScreen" name="GoodbyeScreen" style="display:<?php echo $goodByeScreenOption;?>">
				 <td valign="top" colspan="2">
					<strong><?php echo EDITMESSAGES_22?></strong>&nbsp;&nbsp;(<?php echo $groupShowGoodbyeScreenS?>):
					<br><br>
					<textarea id="groupGoodbyeScreen" name="groupGoodbyeScreen" rows=4 cols=30><?php echo encodeHTML($groupGoodbyeScreen);?></textarea>
					<script language="JavaScript" type="text/javascript">
						var oEdit6 = new InnovaEditor("oEdit6");
						oEdit6.mode="XHTML";
								oEdit6.enableFlickr = false;
								oEdit6.enableCssButtons = false;
								oEdit6.enableLightbox = false;
								oEdit6.fileBrowser = "../assetmanager/asset.php";	//supposed to be in the common folder.
								oEdit6.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
						    	oEdit6.groups = [
						        	["group2", "", [
										/*DO NOT USE: "TextDialog","FontDialog","Quote","FlashDialog", "YoutubeDialog","InternalLink","CustomObject", "MyCustomButton", */
										"FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
										"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
										"CustomTag", "BRK", "CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
										"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL"]]
			        			];

						oEdit6.arrCustomTag=[
						["<?php echo TAGSUBSCRIBERNAME?>","#subname#"], ["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"], ["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"],
						["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"], ["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"], ["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"],
						["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"], ["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"], ["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"],
						["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"], ["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"], ["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"],
		                ["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"],  ["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"], ["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"],
						["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"], ["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"], ["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"],
						["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"], ["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"], ["<?php echo TAGDATETIME?>","#date_time#"],
						["<?php echo $groupName?>","<?php echo $groupName?>"], ["<?php echo $groupSite?>","<?php echo $groupSite?>"], ["<?php echo $groupContactEmail?>","<?php echo $groupContactEmail?>"]];
						oEdit6.width="850";
						oEdit6.height="300";
						oEdit6.REPLACE("groupGoodbyeScreen");
					</script>
				</td>
			</tr>
		</table>
		<div align="center"><input type="submit" class="submit" id="savegoodbye" name="savegoodbye" value="<?php echo EDITMESSAGES_9; ?>"></div>
		</form>
	</div>
</div>
<br><br>

<!-- ALREADY IN RELATED -->
				<?php 
				$ainUrlOption="table-row";
				$ainScreenOption="table-row";
				$ainAccountOption="table-row";
				if ($groupAlreadyInAction=="1") {$ainScreenOption="table-row";$ainUrlOption="none";	$ainAccountOption="none";}
				else if ($groupAlreadyInAction=="2") {$ainScreenOption="none";$ainUrlOption="table-row";$ainAccountOption="none";}
				else {$ainScreenOption="none";$ainUrlOption="none";$ainAccountOption="table-row";}
				?>	

<div style="background-color:#FFFFCC;border: #CCC 1px solid;border-radius:2px;padding:20px">
	<span  onclick="show_hide_div('alreadyin','cross4');return false;" class="menuSmall" style="cursor: pointer;"><strong><span id="cross4">[+]</span>&nbsp;<?php echo EDITMESSAGES_5; ?></strong></span>&nbsp;&nbsp;&nbsp;<span class="submitSmall" style="float:right"  onclick="show_hide_div('alreadyin','cross4');"><?php echo HOME_3;?></span>
	<div id="alreadyin" style="display:none;padding-top:15px;padding-bottom:25px;">
		<form action="editMessagesExec.php" method="post" id="Form4"> 
			<table  border="0" cellpadding="6" cellspacing="0" width="100%">
				<tr style="background-color:#6B78B4;">
					<td colspan="2">			
					<div><span class="option"><?php echo SETTINGSMODIFYFORM_44; ?></span>&nbsp;
						<select class="select" name="groupAlreadyInAction" id="groupAlreadyInAction" onchange="swapAlrdin();return false;">
						<option value="1" <?php if ($groupAlreadyInAction==1) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_45; ?></OPTION>
						<option value="2" <?php if ($groupAlreadyInAction==2) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_46; ?></OPTION>
						<option value="3" <?php if ($groupAlreadyInAction==3) {echo " SELECTED";} ?>><?php echo SETTINGSMODIFYFORM_47; ?></OPTION>
						</select>
					</div>
					</TD>
			   </TR>
			   <tr id="AlreadyInUrl" style="display:<?php echo $ainUrlOption;?>;">
					<td valign="top" colspan="2">
						<strong><?php echo EDITMESSAGES_20?>:</strong>
						<input name="groupAlreadyInUrl" type="text" class="fieldbox11" size="60" value="<?php echo $groupAlreadyInUrl?>">&nbsp; (<?php echo $groupShowAlreadyInScreenU?>)
					</td>
				</tr>
				<tr id="AlreadyInScreen" style="display:<?php echo $ainScreenOption;?>;">
			   		<td valign="top" colspan="2">
						<strong><?php echo EDITMESSAGES_12; ?></strong>&nbsp;(<?php echo $groupShowAlreadyInScreenS?>):
						<br><br>
						<textarea id="groupAlreadyInScreen" name="groupAlreadyInScreen" rows=4 cols=30><?php echo encodeHTML($groupAlreadyInScreen);?></textarea>
						<script language="JavaScript" type="text/javascript">
							var oEdit7 = new InnovaEditor("oEdit7");
							oEdit7.mode="XHTML";
									oEdit7.enableFlickr = false;
									oEdit7.enableCssButtons = false;
									oEdit7.enableLightbox = false;
									oEdit7.fileBrowser = "../assetmanager/asset.php";	//supposed to be in the common folder.
									oEdit7.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
							    	oEdit7.groups = [
							        	["group2", "", [
											/*DO NOT USE: "TextDialog","FontDialog","Quote","FlashDialog", "YoutubeDialog","InternalLink","CustomObject", "MyCustomButton", */
											"FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
											"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
											"CustomTag", "BRK", "CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
											"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL"]]
				        			];
						oEdit7.arrCustomTag=[
							["<?php echo TAGCHOSENLISTS?>","#listlisting#"], ["<?php echo TAGSUBSCRIBERNAME?>","#subname#"], ["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"],
							["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"], ["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"], ["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"],
							["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"], ["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"], ["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"],
							["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"], ["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"], ["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"],
							["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"], ["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"],  ["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"],
			                ["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"], ["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"], ["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"],
							["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"], ["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"], ["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"],
							["<?php echo TAGDATETIME?>","#date_time#"], ["<?php echo $groupName?>","<?php echo $groupName?>"], ["<?php echo $groupSite?>","<?php echo $groupSite?>"],
							["<?php echo $groupContactEmail?>","<?php echo $groupContactEmail?>"]];
							oEdit7.width="850";
							oEdit7.height="300";
							oEdit7.REPLACE("groupAlreadyInScreen");
						</script>
					</td>	
				</tr>
			   <tr id="ainAccountRdr" style="display:<?php echo $ainAccountOption;?>;">
					<td valign="top" colspan="2">
						<?php echo EDITMESSAGES_13?>
					</td>
				</tr>

			</table>
			<div align="center"><input type="submit" class="submit" id="savealreadyin" name="savealreadyin" value="<?php echo EDITMESSAGES_9; ?>"></div>
		</form>
	</div>
</div>
<?php include("footer.php"); ?>