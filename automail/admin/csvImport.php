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
?>
<script type="text/javascript" language="javascript">
function processCSV() {
	$('#results').html();
	var colsNotSelected=0;
	for (var i=1; i<=20; i++) {
		if ($('#col'+i).val()=="ignore" || $('#col'+i).val()!="email") {
			colsNotSelected=colsNotSelected+1;
		}
	}
	if ($("#fileName").blank() || colsNotSelected==20) {
		openAlertBox('<?php echo fixJSstring(SUBSCRIBERSIMPORT_12)?>','');
		return false;
	}
    $("#indicator").show();
	$("#processButton").prop("disabled",true);
	params=$('#csvImport').serialize();
	var url="csvImportExec.php?"+params;
	$.get(url)
	.done(function(data,status) {
		if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3)?>');
			document.location.href="index.php";
		}
		else {
			showResponse(data, status);
		}
		 }
	) .fail(function(data, status) {showException(status);});
	return false;
	}


	function showResponse(data) {
		if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3);?>');
			document.location.href="index.php";
		}
		$('#results').html(data);
    	$("#indicator").hide();
		$("#processButton").prop("disabled",false);
	}
    function showException(status) {
		alert('<?php echo fixJSstring(GENERIC_8);?>');
    	$("#indicator").hide();
		$("#processButton").prop("disabled",false);
		$('#results').html();
	}
</script>
<script type="text/javascript" language=JavaScript src="./editor/innovaeditor.js"></script>
<table  border="0" width="960px">
	<tr>
		<td width="70%">
			<span class="title"><?php echo SUBSCRIBERSIMPORT_1;?></span>
		</td>
		<td align=right>
			<img src="./images/importcsv.png" alt="" width="60" height="47">
		</td>
	</tr>
	<tr>
		<td>
			<div style="width:850px">
			<a href="#"  class="cross" onclick="show_hide_div('what','cross1');return false;"><span id='cross1'>[+]</span>&nbsp;<?php echo SUBSCRIBERSIMPORT_2;?></a>
			<div id="what" style="display:none;">
				<ol>
				  <li><?php echo SUBSCRIBERSIMPORT_3;?></li>
				  <li><?php echo SUBSCRIBERSIMPORT_4;?></li>
				</ol>
			</div>
			</div>
		</td>
        <td></td>
	</tr>
</table>
<br>
<form action="csvImportExec.php" name="csvImport" id="csvImport" method="get">
<table border=0 cellpadding=2 cellspacing=0 width="800">
	<tr>
		<td width="120" valign="top"><?php echo SUBSCRIBERSIMPORT_6;?>:</td>
		<td width="280" valign="top"><input type=text class=fieldbox11 id="fileName" name="fileName" value="">&nbsp;
        	<a onclick="modelessDialogShow('./editor/assetmanager/dataFileManager.php?targetField=fileName',700,500);return false;" href="#"><img style="vertical-align:bottom" alt="" width="20" height="20" src="./images/folder.png" border=0 title="<?php echo SUBSCRIBERSIMPORT_7;?>"></a>
		</td>


        <td rowspan=22 valign="top" width=20>&nbsp;</td>
		<td width="380" rowspan=22 valign="top">
			<div style="margin-bottom:5px;"><b><?php echo SUBSCRIBERSIMPORT_22; ?></b></div>
			<div><input type="checkbox" checked name="confirmed" value="-1"><?php echo SUBSCRIBERSIMPORT_23; ?></div>
			<div><input type="checkbox" checked name="prefers" value="-1"><?php echo SUBSCRIBERSIMPORT_24; ?></div>
 			<div style="margin-top:15px;margin-bottom:5px;"><?php echo SUBSCRIBERSIMPORT_31;?>:
			<select name="delimiter" class=select>
 			    <option value="comma">, Comma</option>
			    <option value="semicolon">; Semicolon</option>
				<option value="tab"> Tab</option>
			</select>
			</div>
			<div><input type="checkbox" name="useQualifiers" value="-1"><?php echo SUBSCRIBERSIMPORT_35; ?></div>
			<div style="margin-top:15px;margin-bottom:5px;"><b><?php echo SUBSCRIBERSIMPORT_34; ?></b></div>
			<div><input type="checkbox" checked name="excludeGlobalOpts" value="-1"><?php echo SUBSCRIBERSIMPORT_32; ?></div>
			<div><input type="checkbox" checked name="excludeListOpts" value="-1"><?php echo SUBSCRIBERSIMPORT_33; ?></div>



			<div style="margin-top:15px;margin-bottom:5px;">
				<b><?php echo SUBSCRIBERSIMPORT_21; ?></b>&nbsp;<img onmouseout="hide_info_bubble('qi_3','0')" onmouseover="infoBox('qi_3', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(SUBSCRIBERSIMPORT_29)?>', '20em', '0'); " alt="" src="./images/i1.gif"><span style="display: none;" id="qi_3"></span>
			</div>
			<div>
				<input type="checkbox" name="updateduplicates" checked value="-1"><?php echo EXTERNALDBIMPORTFORM_36;?>
				<img onmouseout="hide_info_bubble('qi_2','0')" onmouseover="infoBox('qi_2', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(EXTERNALDBIMPORTFORM_11)?>', '20em', '0'); " alt="" src="./images/i1.gif"><span style="display: none;" id="qi_2"></span>
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
	<?php
	for ($i=1; $i<21; $i++) { ?>
	<tr>
		<td><?php echo SUBSCRIBERSIMPORT_9;?> <?php echo $i?>:</td>
		<td>
			<select id="col<?php echo $i?>" name="col<?php echo $i?>" class="select">
				<option value="ignore"><?php echo SUBSCRIBERSIMPORT_10;?></option>
				<option value="email"><?php echo DBFIELD_1;?></option>
				<option value="name"><?php echo DBFIELD_2;?></option>
				<option value="lastName"><?php echo DBFIELD_16;?></option>
				<option value="subCompany"><?php echo DBFIELD_17;?></option>
				<option value="address"><?php echo DBFIELD_3;?></option>
				<option value="city"><?php echo DBFIELD_4;?></option>
				<option value="state"><?php echo DBFIELD_5;?></option>
				<option value="zip"><?php echo DBFIELD_6;?></option>
				<option value="country"><?php echo DBFIELD_7;?></option>
				<option value="subPhone1"><?php echo DBFIELD_18;?></option>
				<option value="subPhone2"><?php echo DBFIELD_19;?></option>
				<option value="subMobile"><?php echo DBFIELD_20;?></option>
				<option value="subBirthDay"><?php echo DBFIELD_22;?></option>
				<option value="subBirthMonth"><?php echo DBFIELD_23;?></option>
				<option value="subBirthYear"><?php echo DBFIELD_24;?></option>
				<option value="customSubField1"><?php echo $obj->getSetting("groupCustomSubField1", $idGroup);?></option>
				<option value="customSubField2"><?php echo $obj->getSetting("groupCustomSubField2", $idGroup);?></option>
				<option value="customSubField3"><?php echo $obj->getSetting("groupCustomSubField3", $idGroup);?></option>
				<option value="customSubField4"><?php echo $obj->getSetting("groupCustomSubField4", $idGroup);?></option>
				<option value="customSubField5"><?php echo $obj->getSetting("groupCustomSubField5", $idGroup);?></option>
		</select>

		</td>
	</tr>
<?php } ?>
<tr><td></td><td align=right><input onclick="return processCSV();return false;" type="submit" id="processButton" class="submit" value="<?php echo SUBSCRIBERSIMPORT_8;?>"></td></tr>
<tr><td colspan=4><div id=results></div><div id="indicator" style="display:none" align=center><img alt="" src="./images/waitBig.gif" border=0 title="<?php echo GENERIC_18; ?>"><br><?php echo GENERIC_18; ?></div></td></tr>
</table>
</form>

<?php
include ("footer.php");
?>