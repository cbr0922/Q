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
include('header.php');
showMessageBox();
?>
<script language=JavaScript src='./editor/innovaeditor.js'></script>

<?php
$mySQL="SELECT details, timesVisited from ".$idGroup."_privacyPage where idGroup=$idGroup";
	$result	= $obj->query($mySQL);
	$row = $obj->fetch_array($result);
	$pDetails		= $row['details'];
	$pTimesVisited	= $row['timesVisited'];
?>
<table border="0" cellpadding="3" cellspacing="0" width="960px">
		<TR>
			<td valign="top">
				<span class="title"><?php echo MODIFYPRIVACYFORM_1; ?></span>
				<br><?php echo HOME_29.': '.$pTimesVisited?>
				<br><a href='../subscriber/privacy.php' target='_blank'><?php echo MODIFYPRIVACYFORM_3; ?></a>
			</td>
			<td align=right>
				<img src="./images/privacy.png" width="60" height="60">
			</td>
		</TR>
</table>

<form method="post" name="modifyPrivacyform" action="modifyPrivacyExec.php" id="Form1">
  <br>

  <table width="533" border="0">

      <tr><td width="342">
					<textarea id="pdetails" name="pdetails" rows=4 cols=30><?php echo encodeHTML($pDetails);?></textarea>
					<script>
						var oEdit1 = new InnovaEditor("oEdit1");
						oEdit1.mode="XHTML";
						oEdit1.enableFlickr = false;
						oEdit1.enableCssButtons = false;
						oEdit1.enableLightbox = false;
						oEdit1.fileBrowser = "../assetmanager/asset.php";
						oEdit1.returnKeyMode = 0; //0:browser's default, 1:div, 2:BR, 3:P
				    	oEdit1.groups = [
				        	["group1", "", [
								/*DO NOT USE: "TextDialog","FontDialog","Quote","FlashDialog", "YoutubeDialog","InternalLink","CustomObject", "MyCustomButton", */
								"FullScreen","Print","Preview","SearchDialog","Cut","Copy","Paste","Undo", "Redo", "RemoveFormat","ClearAll",
								"LinkDialog", "BookmarkDialog","Emoticons", "Guidelines","Absolute", "CharsDialog", "Line",	"TableDialog", "ImageDialog","SourceDialog",
								"BRK",
								"CompleteTextDialog","ForeColor", "BackColor", "FontName", "FontSize", "Bold", "Italic", "Underline", "Strikethrough", "Superscript","Subscript", "Styles",    
								"JustifyLeft", "JustifyCenter", "JustifyRight","JustifyFull", "Paragraph", "Bullets", "Numbering", "Indent", "Outdent","LTR","RTL","templates"]]
	        			];
						oEdit1.arrCustomButtons.push (
							["templates","modelessDialogShow('./editor/assetmanager/templatemanager.php',900,500)","<?php echo fixJSstring(FILEMANAGER_6)?>","btnTemplate.gif", "85"]
						 );
						 oEdit1.width="800";
						oEdit1.height="500";
						oEdit1.REPLACE("pdetails");
						</script>

      </td>
    </tr>

    <tr>
    	<td align=right>
        	<input class="submit" type="submit" name="Submit" value="<?php echo MODIFYPRIVACYFORM_2; ?>">
      </td>
    </tr>
  </table>
</form>

<?php
include('footer.php');
?>