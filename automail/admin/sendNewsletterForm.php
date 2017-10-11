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
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
include('header.php');
?>
<script language=JavaScript src="./scripts/jquery.playSound.js"></script>
<script type="text/javascript" language="javascript">
<!--
function autosave(){saveNewsletter("Save");}

function saveNewsletter(action) {
	if (action=="sendtest")	{
    	if (isGoodEmail("selectedemail")==false){
			$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(SENDNEWSLETTERFORM_34)?>');	
			$("#statusmessage").addClass("errormessage");
            $("#statusmessage").show();
			$("#selectedemail").focus();
			$("#selectedemail").select();
			$("#sendtest").prop("disabled",false);
			return false;
		}
	}
	var url="saveNewsletter.php";
	if (action=="SaveTextCopy") {
		var bodyContent = encodeURIComponent(oEdit1.getTextBody());
	}
	else {
		var bodyContent = encodeURIComponent(oEdit1.getXHTML());
	}
	var params="type=-1&pbody="+bodyContent+"&charset="+$('#charset').val()+"&selectedname="+$('#selectedname').val()+"&selectedemail="+$('#selectedemail').val()+"&subject="+encodeURIComponent($('#subject').val())+"&idNewsletter="+$('#idNewsletter').val()+"&action="+action+"&inlineImages="+encodeURIComponent($('#inlineImages').val())+"&attachments="+encodeURIComponent($('#attachments').val());
	$("#statusmessage").hide();
	//when it goes well	: data=ok, status=success, //when it fails 	: data=, status=error
	$.post(url, params)
		 .done(function(data,status) {
			showResponse(data,status);
		 })
		 .fail(function(data, status) {
		 	showException(status);
		 })
		 //.always(function(data,status) {
		   //	alert("ALW: "+data+'-'+status );
			//showResponse(data,status);
		 //})	//ok-success OR [object-error]
	$("#loading").show();
	disableAllButtons();

	function showResponse(data, status) { // ok, success
		function playSaveSound(){
				if (navigator.userAgent.toLowerCase().indexOf('safari')!=-1){autostart='true'}else {autostart='false'};
				$.playSound("wpc",autostart);
		}
		function statusmessage() {
			$("#loading").hide();
			$("#statusmessage").show();
			$("#statusmessage").toggle("highlight", { duration: 3000});
			enableAllButtons();
		}
	
		if (data=="sessionexpired") 	{
			openAlertBox('<?php echo fixJSstring(SENDNEWSLETTERFORM_1)?>','');
			$("#loading, #statusmessage").hide();
			enableAllButtons();
		}
		else {	
			if (data=="ok")	{
				var naction=action.toLowerCase();
				switch(naction) {
					case "save":
		  				playSaveSound();
						$("#statusmessage").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>');
						statusmessage();
		  				break;
					case "saveexit":
		  				document.location.href="htmlNewsletters.php?message=<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>";
		  				break;
					case "savefirsttimeexit":
		  				document.location.href="htmlNewsletters.php?message=<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>";
		  				break;
					case "savecopy":
		  				document.location.href="htmlNewsletters.php?message=<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>";
		  				break;
					case "savetextcopy":
		  				document.location.href="textNewsletters.php?message=<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>";
		  				break;
					/*case "savetemplate":
		  				$("#statusmessage").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(FILEMANAGER_14)?>'+data);
						$("#loading").hide();
						$("#statusmessage").show();
						enableAllButtons();
		  				break;*/
					case "sendtest":
		  				$("#statusmessage").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(SENDNEWSLETTERFORM_5)?>&nbsp;'+$("#selectedemail").val());
						statusmessage();
		  				break;
					default:
		  				$("#statusmessage").html('<img src="./images/warning.png">&nbsp;'+data);
						$("#loading").hide();
						$("#statusmessage").show();
						enableAllButtons();
				}
			}	//ok
			else {
				$("#statusmessage").html(data);
				$("#loading").hide();
				$("#statusmessage").show();
				enableAllButtons();
			}
		}

	}
	function showException(status) {
		alert('<?php echo fixJSstring(GENERIC_8)?>');
		$("#loading").hide();
		enableAllButtons();
		$("#statusmessage").addClass("errormessage");
	    $("#statusmessage").show();
		$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<font color=red><?php echo fixJSstring(GENERIC_7)?>, Status: '+status+'</font>');
	}
	function disableAllButtons() {
		if (action=="Save" || action=="SaveExit" || action=="SaveCopy" || action=="SaveTextCopy" || action=="sendtest")	{
			$("#sendtest, #Save, #SaveExit, #SaveCopy, #SaveTextCopy, #savetemplate").prop( "disabled", true );
		}
	}
	function enableAllButtons() {
		if (action=="Save" || action=="SaveExit" || action=="SaveCopy" || action=="SaveTextCopy" || action=="sendtest")	{
			$("#sendtest, #Save, #SaveExit, #SaveCopy, #SaveTextCopy, #savetemplate").prop( "disabled", false );
		}
	}
}
// done hiding -->
</script>

<?php
if ($idnewsletterEdit) {
	$mySQL="Select * from ".$idGroup."_newsletters where idNewsletter=$idnewsletterEdit AND idGroup=$idGroup";
	$result			= $obj->query($mySQL);
	$row 			= $obj->fetch_array($result);
	$subject		= $row['name'];
	$pbody			= $row['body'];
	$attachments	= $row['attachments'];
	$inlineimages	= $row['inlineImages'];
	$charset		= $row['charset'];
}
else {
	$subject		= SENDNEWSLETTERFORM_41;
	$pbody			= "";
	$attachments	= "";
	$inlineimages	= "";
	$charset		= $groupGlobalCharset;
}
?>
<script language=JavaScript src="./editor/innovaeditor.js"></script>

<form name="Newsletterform[]" id="Newsletterform" method="" action="">
			<input type="hidden" id="idNewsletter" name="idNewsletter" value="<?php echo $idnewsletterEdit;?>">
			<input type="hidden" id="type" name="type" value="-1">
			<table  width="100%" border="0"  style="BORDER-COLLAPSE: collapse;" cellpadding="4" cellspacing="0">
				<tr>
					<td>
						<b><font color="#646464"><?php echo SENDNEWSLETTERFORM_10; ?></font></b>
					</td>
					<td>
						<input class="nslSubject"  type="text" id="subject" name="subject" value="<?php echo strForInput($subject);?>" size=50>
					</td>
				</tr>
				<tr>
					<td>
						<b><font color="#646464"><?php echo SENDNEWSLETTERFORM_11;?></font></b>
					</td>
					<td>
						&nbsp;&nbsp;<input class="fieldbox11"  type="text" id="attachments" name="attachments" value="<?php echo $attachments?>" size=50>&nbsp;&nbsp;<a onclick="modelessDialogShow('./editor/assetmanager/attachmanager.php',700,500);return false;" href="#"><img src="./images/folder.png" style="vertical-align:bottom" alt="" width="20" height="20" border=0 title="<?php echo SENDNEWSLETTERFORM_17;?>"></a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo SENDNEWSLETTERFORM_42;?>:</b> &nbsp;
						<select class="select" name="charset" id="charset">
							<option value="iso-8859-1" <?php if ($charset=="iso-8859-1" ) {echo " selected";}?>>		Western European (iso-8859-1)</option>
							<option value="utf-8" <?php if ($charset=="utf-8" ) {echo " selected";}?>>					Unicode - utf-8</option>
							<!--option value="US-ASCII" <?php if ($charset=="US-ASCII" ) {echo " selected";}?>>			US-ASCII</option-->
							<!--option value="windows-1252" <?php if ($charset=="windows-1252" ) {echo " selected";}?>>	Western European (windows-1252)</option-->
							<option value="iso-8859-3" <?php if ($charset=="iso-8859-3" ) {echo " selected";}?>>		Western European (iso-8859-3</option>
							<option value="iso-8859-2" <?php if ($charset=="iso-8859-2" ) {echo " selected";}?>>		Central European (iso-8859-2)</option>
							<!--option value="windows-1250" <?php if ($charset=="windows-1250" ) {echo " selected";}?>>	Central European (windows-1250)</option-->
							<option value="iso-8859-5" <?php if ($charset=="iso-8859-5" ) {echo " selected";}?>>		Cyrillic (iso-8859-5)</option>
							<!--option value="windows-1251" <?php if ($charset=="windows-1251" ) {echo " selected";}?>>	Cyrillic (windows-1251)</option-->
							<option value="iso-8859-6" <?php if ($charset=="iso-8859-6" ) {echo " selected";}?>>		Arabic (iso-8859-6)</option>
							<!--option value="windows-1256" <?php if ($charset=="windows-1256" ) {echo " selected";}?>>	Arabic (windows-1256)</option-->
							<!--option value="windows-1253" <?php if ($charset=="windows-1253" ) {echo " selected";}?>>	Greek (windows-1253)</option-->
							<option value="iso-8859-7" <?php if ($charset=="iso-8859-7" ) {echo " selected";}?>>		Greek (iso-8859-7)</option>
							<option value="iso-8859-8" <?php if ($charset=="iso-8859-8" ) {echo " selected";}?>>		Hebrew (iso-8859-8)</option>
							<!--option value="windows-1255" <?php if ($charset=="windows-1255" ) {echo " selected";}?>>	Hebrew (windows-1255)</option-->
							<!--option value="IL-ASCII" <?php if ($charset=="IL-ASCII" ) {echo " selected";}?>>			Hebrew (IL-ASCII)</option-->
							<option value="iso-8859-9" <?php if ($charset=="iso-8859-9" ) {echo " selected";}?>>		Turkish (iso-8859-9)</option>
							<!--option value="windows-1254" <?php if ($charset=="windows-1254" ) {echo " selected";}?>>	Turkish (windows-1254)</option-->
							<!--option value="windows-1257" <?php if ($charset=="windows-1257" ) {echo " selected";}?>>	Baltic (windows-1257)</option-->
							<option value="iso-8859-4" <?php if ($charset=="iso-8859-4" ) {echo " selected";}?>>		Baltic (iso-8859-4)</option>
							<option value="iso-8859-13" <?php if ($charset=="iso-8859-13" ) {echo " selected";}?>>		Latvian (iso-8859-13)</option>
							<option value="iso-8859-15" <?php if ($charset=="iso-8859-15" ) {echo " selected";}?>>		Estonian (iso-8859-15)</option>
							<option value="gb2312" <?php if ($charset=="gb2312" ) {echo " selected";}?>>				Chinese Simplified (gb2312)</option>
							<option value="big5" <?php if ($charset=="big5" ) {echo " selected";}?>>					Chinese Traditional - (big5)</option>
							<option value="x-euc-tw" <?php if ($charset=="x-euc-tw" ) {echo " selected";}?>>			Chinese Traditional (x-euc-tw)</option>
							<option value="shift_jis" <?php if ($charset=="shift_jis" ) {echo " selected";}?>>			Japanese (shift_jis)</option>
							<option value="euc-jp" <?php if ($charset=="euc-jp" ) {echo " selected";}?>>				Japanese (euc-jp)</option>
							<option value="iso-2022-jp" <?php if ($charset=="iso-2022-jp" ) {echo " selected";}?>>			Japanese (iso-2022-jp)</option>
							<option value="ks_c_5601-1987" <?php if ($charset=="ks_c_5601-1987" ) {echo " selected";}?>>	Korean (ks_c_5601-1987)</option>
							<option value="euc-kr" <?php if ($charset=="euc-kr" ) {echo " selected";}?>>				Korean (euc-kr)</option>
							<option value="windows-874" <?php if ($charset=="windows-874" ) {echo " selected";}?>>		Thai (windows-874)</option>
							<option value="tis-620" <?php if ($charset=="tis-620" ) {echo " selected";}?>>				Thai (tis-620)</option>
							<option value="windows-1258" <?php if ($charset=="windows-1258" ) {echo " selected";}?>>	Vietnamese (windows-1258)</option>
							<option value="cp1258" <?php if ($charset=="cp1258" ) {echo " selected";}?>>				Vietnamese (cp1258)</option>
							<option value="none"><?php echo SENDNEWSLETTERFORM_39; ?></option>
						</select>
					</td>
				</tr>
                <input type="hidden" id="inlineImages" name="inlineImages" value="">
				<tr>
					<td valign="top" rowspan=6>
						<b><font color="#646464"><?php echo SENDNEWSLETTERFORM_12;?></font></b>
					</td>
					<td>
					<textarea id="pbody" name="pbody" rows=4 cols=30><?php echo $pbody;?></textarea>
					<script type="text/javascript" language="javascript">
						var oEdit1 = new InnovaEditor("oEdit1");
						oEdit1.mode="XHTML";
						oEdit1.enableFlickr = false;
						oEdit1.enableCssButtons = false;
						oEdit1.enableLightbox = false;
						oEdit1.enableImageStyles = false;
						oEdit1.fileBrowser = "../assetmanager/asset.php";
						oEdit1.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
				    	oEdit1.groups = [
				        	["group1", "", [
								/*DO NOT USE: 	"TextDialog","FontDialog","Table",			"CustomObject", "MyCustomButton", */
								"CustomSave", "FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
								"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","YoutubeDialog","FlashDialog","SourceDialog",
								"smartlinks","smartlinks2","optoutlinks","templates",
								"BRK",
								"CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles","Quote",
								"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL",
								"facebook", "CustomTag"]]
	        			];
						oEdit1.arrCustomButtons = [
							<?php if ($idnewsletterEdit) {?>
							["customsave","saveNewsletter('Save');","<?php echo fixJSstring(SENDNEWSLETTERFORM_13)?>","btnSave.gif"], 	<?php }?>
							<?php if (!$idnewsletterEdit) {?>
							["customsave","saveNewsletter('SavefirsttimeExit');","<?php echo fixJSstring(SENDNEWSLETTERFORM_13)?>","btnSave.gif"], 	<?php }?>
							["facebook","oEdit1.insertHTML('#fblikefb#')","<?php echo fixJSstring(TAGFACEBOOK)?>","btnFacebook.gif"],
						 	["smartlinks","modelessDialogShow('smartLinks.php',500,600)","<?php echo fixJSstring(SMARTLINKS_1)?>","smartLinks.gif", "85"],
							["optoutlinks","modelessDialogShow('optOutLinks.php',600,370)","<?php echo fixJSstring(INSERTULINK_1)?>","optOut.gif", "85"],
							["templates","modelessDialogShow('./editor/assetmanager/templatemanager.php',900,500)","<?php echo fixJSstring(FILEMANAGER_6)?>","btnTemplate.gif", "85"]
						 ];
						oEdit1.arrCustomTag=[["<?php echo TAGSUBSCRIBEREMAIL?>","#subemail#"],
						["<?php echo TAGSUBSCRIBERID?>","#subID#"],
						["<?php echo TAGSUBSCRIBERNAME?>","#subname#"],
						["<?php echo TAGSUBSCRIBERLASTNAME?>","#sublastname#"],
						["<?php echo TAGSUBSCRIBERCOMPANY?>","#subcompany#"],
						["<?php echo TAGSUBSCRIBERPASSWORD?>","#subpasscode#"],
						["<?php echo TAGSUBSCRIBERDATESUBSCRIBED?>","#subdatesubscribed#"],
						["<?php echo TAGSUBSCRIBERDATELASTUPDATED?>","#subdatelastupdated#"],
						["<?php echo TAGSUBSCRIBERDATELASTEMAILED?>","#subdatelastemailed#"],
						["<?php echo TAGSUBSCRIBERADDRESS?>","#subaddress#"],
						["<?php echo TAGSUBSCRIBERCITY?>","#subcity#"],
						["<?php echo TAGSUBSCRIBERSTATE?>","#substate#"],
						["<?php echo TAGSUBSCRIBERZIP?>","#subzip#"],
						["<?php echo TAGSUBSCRIBERCOUNTRY?>","#subcountry#"],
						["<?php echo TAGSUBSCRIBERPHONE1?>","#subphone1#"],
						["<?php echo TAGSUBSCRIBERPHONE2?>","#subphone2#"],
						["<?php echo TAGSUBSCRIBERMOBILE?>","#submobile#"],
                        ["<?php echo TAGSUBSCRIBERBDAY?>","#subBirthday#"],
                        ["<?php echo TAGSUBSCRIBERBMONTH?>","#subBirthmonth#"],
                        ["<?php echo TAGSUBSCRIBERBYEAR?>","#subBirthyear#"],
						["<?php echo TAGDATETIME?>","#date_time#"],
						["<?php echo TAGDATETIME2?>","#full_date#"],
						["<?php echo TAGSUBSCRIBERCUSTOM1?>","#subcustomsubfield1#"],
		  				["<?php echo TAGSUBSCRIBERCUSTOM2?>","#subcustomsubfield2#"],
						["<?php echo TAGSUBSCRIBERCUSTOM3?>","#subcustomsubfield3#"],
						["<?php echo TAGSUBSCRIBERCUSTOM4?>","#subcustomsubfield4#"],
						["<?php echo TAGSUBSCRIBERCUSTOM5?>","#subcustomsubfield5#"]];
						oEdit1.width='100%';
						oEdit1.height=500;
						oEdit1.REPLACE("pbody");
						</script>
					</td>
				</tr>
				<tr>
					<td valign="center" align="center" height="35"><div id=sessionexpired style="display:none"></div>
						<?php if ($idnewsletterEdit) { ?>
							<input class="submit" type="submit" id="Save" name="Save[]" onclick="saveNewsletter('Save');return false;" value="<?php echo SENDNEWSLETTERFORM_13; ?>" >
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input class="submit" type="submit" id="SaveExit" name="SaveExit" onclick="saveNewsletter('SaveExit');return false;" value="<?php echo SENDNEWSLETTERFORM_14; ?>" >
						   &nbsp;&nbsp;&nbsp;&nbsp;
							<input class="submit" type="submit" id="SaveCopy" name="SaveCopy" onclick="saveNewsletter('SaveCopy');return false;" value="<?php echo SENDNEWSLETTERFORM_4; ?>" >
						   &nbsp;&nbsp;&nbsp;&nbsp;
							<input class="submit" type="submit" id="SaveTextCopy" name="SaveTextCopy" onclick="saveNewsletter('SaveTextCopy');return false;" value="<?php echo SENDNEWSLETTERFORM_33; ?>">
						   &nbsp;&nbsp;&nbsp;&nbsp;
							<input class="submit" type="submit" id="savetemplate" name="savetemplate" onclick="saveNewsletter('savetemplate');return false;" value="<?php echo FILEMANAGER_13; ?>">
							
						<?php } ?>
						<?php if (!$idnewsletterEdit) { ?>
							<input class="submit" type="submit" id="SavefirsttimeExit" name="SavefirsttimeExit"  onclick="saveNewsletter('SavefirsttimeExit');return false;" value="<?php echo SENDNEWSLETTERFORM_13; ?>" >
							<input type="hidden" id="selectedemail" value="">
							<input type="hidden" id="selectedname" value="">
						<?php } ?>
					</td>
    			</tr>
			<tr>
				<td valign="center" align="center">
					<div id="loading" style="display:none;"><img src="./images/waitBig.gif"><?php echo GENERIC_4; ?></div>
					<span id="statusmessage" class="okmessage" style="display:none;"></span>
				</td>
			</tr>
			<?php if ($idnewsletterEdit) {	//SEND OPTIONS
			?>
			<tr>
				<td colspan=2 align=center>
					<font color="#333333"><?php echo SENDNEWSLETTERFORM_35;?></font>
					<input size=30 class="fieldbox11" type="text" name="selectedemail" id="selectedemail" value="<?php echo getadminemail($sesIDAdmin,$idGroup);?>">
					&nbsp;&nbsp;
					<input size=30 class="fieldbox11"  type="text" name="selectedname" id="selectedname" value="<?php echo getadminname($sesIDAdmin,$idGroup);?>">
					&nbsp;&nbsp;
					<input class="submit" type="submit" id="sendtest" name="sendtest" onclick="saveNewsletter('sendtest');return false;" value="<?php echo SENDNEWSLETTERFORM_16; ?>">
					&nbsp;&nbsp;<img onmouseover="infoBox('tip_1', '<?php echo fixJSstring(GENERIC_17)?>', '<?php echo fixJSstring(SENDNEWSLETTERFORM_40)?>', '20em','0'); " onmouseout="hide_info_bubble('tip_1','0')" src="./images/helpSmallWhite.gif"><span style="display:none;align:left;" id="tip_1"></span>
				</td>
			</tr>
			<tr>
				<td valign=top colspan=2 align=center>
					<a href="#" onclick="show_hide_div('readytosend',''); return false;"><b><?php echo SENDNEWSLETTERFORM_36;?></b></a>
					<div id="readytosend"  style="display:none;"><span style="Font-size:14px;"><?php echo SENDNEWSLETTERFORM_37; ?></span>&nbsp;<a href="campaignCreate.php?idHtmlNewsletter=<?php echo $idnewsletterEdit;?>"><span style="Font-size:14px;"><?php echo SENDNEWSLETTERFORM_38;?></span></a></div>
				</td>
			</tr>
			<?php } ?>
			</table>
</form>
<?php
include('footer.php');
?>