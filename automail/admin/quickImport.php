<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj 		= new db_class();
$groupName	= $obj->getSetting("groupName", $idGroup);
include('header.php');
?>
<script type="text/javascript" language="javascript">
function doImport() {
	$('#results').html();
	if ($("#subform").blank()) {
		openAlertBox('<?php echo fixJSstring(SUBSCRIBERSIMPORT_30)?>','');
		return false;
	}
    $("#indicator").show();
	$("#processButton").prop("disabled",true);
	$.ajax({
		type: "POST",
		url:"quickImportExec.php",
		data: $('#quickImport2').serialize()
		}).done(function(data,status) {
			showResponse(data, status);
			})
	  		.fail(function(data, status) {showException(status); });
	return false;
	
	function showResponse(data) {
		if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3);?>');
			document.location.href="index.php";
		}
		else {
			$("#results").html(data);
	    	$("#indicator").hide();
			$("#processButton").prop("disabled",false);
		}
	}
    function showException(status) {
   		alert('<?php echo fixJSstring(GENERIC_8);?>');
    	$("#indicator").hide();
		$("#processButton").prop("disabled",false);
	}
}	
</script>
<table width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td>
			<span class="title"><?php echo SUBSCRIBERSIMPORT_18; ?></span>
		</td>
		<td align="right"><img src="./images/importemails.png" alt="" width="60" height="47">
		</td>
	</tr>
</table>
<form action=quickImportExec.php id="quickImport2" name="quickImport2" method="post">
<table border=0 cellpadding=4 cellspacing=0>
	<tr>
		<td colspan=3 valign=top>
			<?php echo SUBSCRIBERSIMPORT_19; ?>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<TEXTAREA id="subform" NAME="subform" COLS="40" ROWS="20" class="textarea">first@somemail.com<?php echo"\n"?>second@email.com</TEXTAREA>
		</td>
		<td width=40>&nbsp;</td>
		<td align=left valign=top>
			<div style="margin-bottom:5px;"><b><?php echo SUBSCRIBERSIMPORT_22; ?></b></div>
			<div><input type="checkbox" checked name="confirmed" value="-1"><?php echo SUBSCRIBERSIMPORT_23; ?></div>
			<div><input type="checkbox" checked name="prefers" value="-1"><?php echo SUBSCRIBERSIMPORT_24; ?></div>
			<div style="margin-top:15px;margin-bottom:5px;"><b><?php echo SUBSCRIBERSIMPORT_34; ?></b></div>
			<div><input type="checkbox" checked name="excludeGlobalOpts" value="-1"><?php echo SUBSCRIBERSIMPORT_32; ?></div>
			<div><input type="checkbox" checked name="excludeListOpts" value="-1"><?php echo SUBSCRIBERSIMPORT_33; ?></div>
			<div style="margin-top:15px;margin-bottom:5px;">
				<b><?php echo SUBSCRIBERSIMPORT_21; ?></b>&nbsp;<img onmouseout="hide_info_bubble('qi_3','0')" onmouseover="infoBox('qi_3', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(SUBSCRIBERSIMPORT_29)?>', '25em', '0'); " alt="" src="./images/i1.gif"><span style="display: none;" id="qi_3"></span>
			</div>
			<div>
				<input type="checkbox" checked name="updateduplicates" value="-1"><?php echo SUBSCRIBERSIMPORT_25;?>&nbsp;
				<img onmouseout="hide_info_bubble('qi_2','0')" onmouseover="infoBox('qi_2', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(SUBSCRIBERSIMPORT_16)?>', '25em', '0'); " alt="" src="./images/i1.gif"><span style="display: none;" id="qi_2"></span>
			</div>

			<div>
				<?php
					$mySQL3="SELECT idList, listName FROM ".$idGroup."_lists where idGroup=$idGroup";
					$result	= $obj->query($mySQL3);
					while ($row = $obj->fetch_array($result)){?>
						<input name="idList[]" type="checkbox" value="<?php echo $row['idList'];?>"><?php echo $row['idList'].'. '.$row['listName'].'<br>';?>
					<?php } ?>
			</div>
		</td>
	</tr>
	<tr>
		<td></td><td></td>
		<td>
			<input type="submit" onclick="return doImport();return false;" id="processButton" class="submit" name="processButton" value="<?php echo SUBSCRIBERSIMPORT_20; ?>">
		</td>
	</tr>
<tr><td colspan=3><div id=results></div><div id="indicator" style="display:none" align=center><img alt="" src="./images/waitBig.gif" border=0 title="<?php echo GENERIC_18; ?>"><br><?php echo GENERIC_18; ?></div></td></tr>
</table>
</form>
<?php
include('footer.php');
?>