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
	var bodyContent = encodeURIComponent($('#pbody').val());
	var params="type=0&pbody="+bodyContent+"&charset="+$('#charset').val()+"&selectedname="+$('#selectedname').val()+"&selectedemail="+$('#selectedemail').val()+"&subject="+encodeURIComponent($('#subject').val())+"&idNewsletter="+$('#idNewsletter').val()+"&action="+action+"&inlineImages="+encodeURIComponent($('#inlineImages').val())+"&attachments="+encodeURIComponent($('#attachments').val());
   	$("#statusmessage").hide();
	$.post(url, params)
		 .done(function(data,status) {
			showResponse(data,status);
		 })
		 .fail(function(data, status) {
		 	showException(status);
		 })
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
		  				document.location.href="textNewsletters.php?message=<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>";
		  				break;
					case "savefirsttimeexit":
		  				document.location.href="textNewsletters.php?message=<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>";
		  				break;
					case "savecopy":
		  				document.location.href="textNewsletters.php?message=<?php echo fixJSstring(SENDNEWSLETTERFORM_2)?>";
		  				break;
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
	$subject		= SENDNEWSLETTERFORM_44;
	$pbody			= "";
	$attachments	= "";
	$inlineimages	= "";
	$charset		= $groupGlobalCharset;
}
?>
<script language=JavaScript src="./editor/innovaeditor.js"></script>
<form name="Newsletterform[]" id="Newsletterform" method="" action="">
			<input type="hidden" id="idNewsletter" name="idNewsletter" value="<?php echo $idnewsletterEdit;?>">
			<input type="hidden" id="type" name="type" value="0">
			<table  width="920" border="0"  style="BORDER-COLLAPSE: collapse;" cellpadding="4" cellspacing="0">
				<tr>
					<td>
						<b><font color="#646464"><?php echo SENDNEWSLETTERFORM_10; ?></font></b>
					</td>
					<td>
						<input class="nslSubject"  type="text" id="subject" name="subject" value="<?php echo strForInput($subject);?>" size=50>
                    </td>
				</tr>
				<tr>
					<td></td>
					<td>
                        <?php echo SENDNEWSLETTERFORM_45;?>&nbsp;<img border="0" src="./images/i1.gif" onclick="infoBox('textnotes', '<?php echo fixJSstring(SENDNEWSLETTERFORM_45)?>', '<?php echo fixJSstring(SENDNEWSLETTERFORM_46).fixJSstring(SENDNEWSLETTERFORM_47);?>', '40em','1');" title="<?php echo GENERIC_6; ?>">&nbsp;<span style="display:none;" id="textnotes"></span>
                    </td>
				</tr>

				<tr>
					<td>
						<b><font color="#646464"><?php echo SENDNEWSLETTERFORM_11;?></font></b>
					</td>
					<td>
						&nbsp;&nbsp;<input class="fieldbox11"  type="text" id="attachments" name="attachments" value="<?php echo $attachments?>" size=50>&nbsp;&nbsp;<a onclick="modelessDialogShow('./editor/assetmanager/attachmanager.php',700,500);return false;" href="#"><img src="./images/folder.png" style="vertical-align:bottom" alt="" width="20" height="20" border=0 title="<?php echo SENDNEWSLETTERFORM_17;?>"></a>
						<input type="hidden" name="charset" id="charset" value="<?php echo $charset?>">
					</td>
				</tr>
				<tr>
					<td valign="top">
						<b><font color="#646464"><?php echo SENDNEWSLETTERFORM_12;?></font></b>
					</td>
					<td>
					<textarea class="textEditor" id="pbody" name="pbody" rows="40" cols="140" style="word-wrap:break-word;" wrap=hard><?php echo wordwrap($pbody,75);?></textarea>

					</td>
				</tr>
				<tr>
                    <!--td  bgcolor="#ffc"></td-->
					<td colspan="2" valign="center" align="center" height="35"><div id=sessionexpired style="display:none"></div>
						<?php if ($idnewsletterEdit) { ?>
							<input class="submit" type="submit" id="Save" name="Save[]" onclick="saveNewsletter('Save');return false;" value="<?php echo SENDNEWSLETTERFORM_13; ?>" >
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input class="submit" type="submit" id="SaveExit" name="SaveExit" onclick="saveNewsletter('SaveExit');return false;" value="<?php echo SENDNEWSLETTERFORM_14; ?>" >
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input class="submit" type="submit" id="SaveCopy" name="SaveCopy" onclick="saveNewsletter('SaveCopy');return false;" value="<?php echo SENDNEWSLETTERFORM_4; ?>" >
						<?php } ?>
						<?php if (!$idnewsletterEdit) { ?>
							<input class="submit" type="submit" id="SavefirsttimeExit" name="SavefirsttimeExit"  onclick="saveNewsletter('SavefirsttimeExit');return false;" value="<?php echo SENDNEWSLETTERFORM_13; ?>" >
							<input type="hidden" id="selectedemail" value="">
							<input type="hidden" id="selectedname" value="">
						<?php } ?>
					</td>
    			</tr>
			<tr>
                <!--td  bgcolor="#ffc"></td-->
				<td colspan=2 valign="center" align="center" style="BORDER-BOTTOM: #6d92f5 0px solid; BORDER-TOP: #6d92f5 0px solid;">
					<div id="loading" style="display:none;"><img src="./images/waitBig.gif"><?php echo GENERIC_4; ?></div>
					<span id="statusmessage" class="okmessage" style="display:none;"></span>
				</td>
			</tr>
			<?php if ($idnewsletterEdit) {	//SEND OPTIONS
			?>
			<tr>
                <!--td  bgcolor="#ffc"></td-->
				<td colspan=2 align=center>
					<font color="#333333"><?php echo SENDNEWSLETTERFORM_35;?></font>
					<input size=30 class="fieldbox11" type="text" name="selectedemail" id="selectedemail" value="<?php echo getadminemail($sesIDAdmin, $idGroup);?>">
					&nbsp;&nbsp;
					<input size=30 class="fieldbox11"  type="text" name="selectedname" id="selectedname" value="<?php echo getadminname($sesIDAdmin, $idGroup);?>">
					&nbsp;&nbsp;
					<input class="submit" type="submit" id="sendtest" name="sendtest" onclick="saveNewsletter('sendtest');return false;" value="<?php echo SENDNEWSLETTERFORM_16; ?>">
					&nbsp;&nbsp;<img onmouseover="infoBox('tip_1', '<?php echo fixJSstring(GENERIC_17)?>', '<?php echo fixJSstring(SENDNEWSLETTERFORM_40)?>', '20em','0'); " onmouseout="hide_info_bubble('tip_1','0')" src="./images/helpSmallWhite.gif"><span style="display:none;align:left;" id="tip_1"></span>
				</td>
			</tr>
			<tr>
                <!--td  bgcolor="#ffc"></td-->
				<td valign=top colspan=2 align=center>
					<a href="#" onclick="show_hide_div('readytosend',''); return false;"><b><?php echo SENDNEWSLETTERFORM_36;?></b></a>
					<div id="readytosend"  style="display:none;"><span style="Font-size:14px;"><?php echo SENDNEWSLETTERFORM_37; ?></span>&nbsp;<a href="campaignCreate.php"><span style="Font-size:14px;"><?php echo SENDNEWSLETTERFORM_38;?></span></a></div>
				</td>
			</tr>
			<?php } ?>
			</table>
</form>
<?php
include('footer.php');
?>