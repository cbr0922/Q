<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj= new db_class();
include('header.php');
showMessageBox();
?>
<script type="text/javascript" language="javascript">
function showConnections() {
	if($("#dbType").val()==3) {
		$("#dsnNamerow").show();
		$("#dsnNamerow").effect( "highlight",{color:"#ffff99"}, 3000 );
		$("#dbNamerow, #dbHostrow").hide();
	}
	else {
		$("#dsnNamerow").hide();
		$("#dbNamerow, #dbHostrow").show();
	}
	/*if($("#dbType").val()==5) {$("#accesstip").show();}
	else {$("#accesstip").hide();}*/
}

function processimportform(action) {
	if ($("#dbType").val()==0) {
		openAlertBox('<?php echo fixJSstring(EXTERNALDBIMPORTFORM_25)?>','');
		return false;
	}
	if (($("#dbType").val()==1 || $("#dbType").val()==6 || $("#dbType").val()==2 || $("#dbType").val()==4 || $("#dbType").val()==5) && ($("#dbName").blank() || $("#dbHost").blank() || $("#dbUserName").blank() || $("#dbPassword").blank())) {
		openAlertBox('<?php echo fixJSstring(EXTERNALDBIMPORTFORM_29).'<br>'.fixJSstring(EXTERNALDBIMPORTFORM_3).'<br>'.fixJSstring(EXTERNALDBIMPORTFORM_7).'<br>'.fixJSstring(EXTERNALDBIMPORTFORM_8).'<br>'.fixJSstring(EXTERNALDBIMPORTFORM_9)?>','');
		return false;
	}
	if (($("#dbType").val()==3) && ($("#dsnName").blank() || $("#dbUserName").blank() || $("#dbPassword").blank())) {
		openAlertBox('<?php echo fixJSstring(EXTERNALDBIMPORTFORM_29).'<br>'.fixJSstring(EXTERNALDBIMPORTFORM_10).'<br>'.fixJSstring(EXTERNALDBIMPORTFORM_8).'<br>'.fixJSstring(EXTERNALDBIMPORTFORM_9)?>','');
		return false;
	}
	if ($("#email").blank() || $("#tbName").blank()) {
		openAlertBox('<?php echo fixJSstring(EXTERNALDBIMPORTFORM_22)?>','');
		return false;
	}
    if ((action=="saveDataSource" || action=="updateDataSource" || action=="doImport") && $("#dataSourceFriendlyName").blank() ) {
        openAlertBox('<?php echo fixJSstring(EXTERNALDBIMPORTFORM_32)?>','');
		return false;
	}
	params = $('#importform').serialize();
    params = params+'&action='+action
	$('#results').html();
	$("#indicator").show();
	$.ajax({
		type: "POST",
		url:"externalDbImportExec.php",
		data: params
		}).done(function(data,status) {
			showResponse(data, status);
			})
	  		.fail(function(data, status) {showException(status); });    
  	if ($("#testCount")) {$("#testCount").prop("disabled",true);}
	if ($("#doImport")) {$("#doImport").prop("disabled",true);}
	if ($("#updateDataSourceBtn")) {$("#updateDataSourceBtn").prop("disabled",true);}
	function showResponse(data, status) {
		//return false;
		if (data=="sessionexpired")  {
			alert('<?php echo fixJSstring(GENERIC_3);?>');
			document.location.href="index.php";
		}
        if (action=="saveDataSource") {
			document.location.href="externalDbImport.php?message=<?php echo fixJSstring(EXTERNALDBIMPORTFORM_43)?>";
        }
    	$("#indicator").hide();
		if ($("#testCount")) {$("#testCount").prop("disabled",false);}
		if ($("#doImport") && $("#idDataSource").val()!=0) {$("#doImport").prop("disabled",false);}
		if ($("#updateDataSourceBtn")) {$("#updateDataSourceBtn").prop("disabled",false);}
		$("#results").html(data);
	}
    function showException(status) 	{
		alert('<?php echo fixJSstring(GENERIC_8);?>');
    	$("#indicator").hide();
		if ($("#testCount")) {$("#testCount").prop("disabled",false);}
		if ($("#doImport")  && $("#idDataSource")!=0) {$("doImport").prop("disabled",false);}
		if ($("#updateDataSourceBtn")) {$("#updateDataSourceBtn").prop("disabled",false);}
	}
}
function loadDataSource(idDataSource) {
    var idDataSource = $("#idDataSource").val();
    document.location.href="externalDbImport.php?loadDS="+idDataSource;

}
</script>

<table border="0" cellpadding="4" cellspacing="0" width="960px">
	<tr>
		<td valign=top><span class="title"><?php echo EXTERNALDBIMPORTFORM_1; ?></span></td>
        <td align="right">	<img src="./images/importdb.png" alt="" width="60" height="47"></td>
	</tr>
    <tr><td colspan="2">&nbsp;</td></tr>
</table>

<form name="importform" id="importform"  autocomplete="off">
<INPUT type="hidden" name="idGroup" id="idGroup" value="<?php echo $idGroup?>" />
<table border="0" width=950 cellspacing=0 cellpadding=3>
    <?php
        $loadSQL="Select idDataSource, dataSourceFriendlyName from ".$idGroup."_dataSources where idGroup=$idGroup";
		$result	= $obj->query($loadSQL);
        $rows 	= $obj->num_rows($result);

        isset($_GET['loadDS'])?$idDS = $_GET['loadDS']:$idDS =0;
        if ($idDS) {
            $sql=' AND idDataSource='.$idDS;
        $loadSQL2 = "Select * from ".$idGroup."_dataSources where idGroup=$idGroup $sql"; //echo $loadSQL2;
        $result2  = $obj->query($loadSQL2);
        $load 	  = $obj->fetch_array($result2);
        $idDataSource       = $load['idDataSource'];
        $dbType				= $load['dbType'];
        $dbName				= $load['dbName'];
        $dbHost				= $load['dbHost'];
        $dsnName			= $load['dsnName'];
        $dbUserName			= $load['dbUserName'];
        $dbPassword			= $load['dbPassword'];
        $tbName				= $load['tbName'];
        $name			= $load['name'];
        $email			= $load['email'];
        $lastName    	= $load['lastName'];
        $subCompany			= $load['subCompany'];
        $address			= $load['address'];
        $city			= $load['city'];
        $zip				= $load['zip'];
        $state			= $load['state'];
        $country			= $load['country'];
        $subPhone1			= $load['subPhone1'];
        $subPhone2			= $load['subPhone2'];
        $subMobile			= $load['subMobile'];
        $subPassword	    = $load['subPassword'];
        $subBirthDay		= $load['subBirthDay'];
        $subBirthMonth		= $load['subBirthMonth'];
        $subBirthYear		= $load['subBirthYear'];
        $customSubField1	= $load['customSubField1'];
        $customSubField2	= $load['customSubField2'];
        $customSubField3	= $load['customSubField3'];
        $customSubField4	= $load['customSubField4'];
        $customSubField5	= $load['customSubField5'];
        $customSQL			= $load['customSQL'];
        $confirmed          = $load['confirmed'];
        $prefersHtml          = $load['prefersHtml'];
        $subUpdateDuplicates = $load['subUpdateDuplicates'];
        $dataSourceFriendlyName		= $load['dataSourceFriendlyName'];
		$excludeGlobalOpts	= $load['excludeGlobalOpts'];
		$excludeListOpts          = $load['excludeListOpts'];
		$idList= $load['idList'];
        }
        else {
            $idDataSource       = "";
            $sql="";
            $dbType				= "";
            $dbName				= "";
            $dbHost				= "";
            $dsnName			= "";
            $dbUserName			= "";
            $dbPassword			= "";
            $tbName				= "";
            $name			= "";
            $email			= "";
            $lastName    	= "";
            $subCompany			= "";
            $address			= "";
            $city			= "";
            $zip				= "";
            $state			= "";
            $country			= "";
            $subPhone1			= "";
            $subPhone2			= "";
            $subMobile			= "";
            $subPassword	    = "";
            $subBirthDay		= "";
            $subBirthMonth		= "";
            $subBirthYear		= "";
            $customSubField1	= "";
            $customSubField2	= "";
            $customSubField3	= "";
            $customSubField4	= "";
            $customSubField5	= "";
            $customSQL			="";
            $confirmed       	=0;
            $prefersHtml        =0;
            $subUpdateDuplicates =0;
            $dataSourceFriendlyName	= "";
			$excludeGlobalOpts	=0;
			$excludeListOpts    =0;
			$idList= 0;

            } ;
     ?>
	<tr>
		<td valign="top" width="300">
			<?php echo EXTERNALDBIMPORTFORM_30;?>:
		</td>
		<td valign="top"  width="600">
            <?php if (!$rows) {?>
            <!--input type=hidden name="idDataSource" id="idDataSource" value="-1"-->No saved data sources found
            <?php } else { ?>
			<SELECT id="idDataSource" name="idDataSource" class="select" onchange="loadDataSource(idDataSource);return false;">
				<option value="0" selected><?php echo EXTERNALDBIMPORTFORM_31;?></option>
               	<?php while ($row = $obj->fetch_array($result)) {?>
				<option value="<?php echo $row['idDataSource'];?>" <?php if ($row['idDataSource']==$idDS){echo ' selected';}?>><?php echo $row['idDataSource'].'. '.$row['dataSourceFriendlyName'];?></option>
               <?php }?>
   			</select><?php if ($idDS) {?>&nbsp;&nbsp;<a href=# onclick="openConfirmBox('delete.php?idDataSource=<?php echo $idDataSource?>','<?php echo fixJSstring(CONFIRM_15) .'<br>'. fixJSstring(GENERIC_2);?>');return false;"><?php echo EXTERNALDBIMPORTFORM_44; ?></a>
            <?php }?>&nbsp;<img onmouseout="hide_info_bubble('ds1','0')" onmouseover="infoBox('ds1', '<?php echo fixJSstring(EXTERNALDBIMPORTFORM_39);?>', '<?php echo fixJSstring(EXTERNALDBIMPORTFORM_38)?>', '20em', '0'); " src="./images/i1.gif">
            <span style="display: none;" id="ds1"></span>
	    </td>
	</tr>
    <?php } ?>
	<tr>
		<td valign="top" colspan="2"><hr>
		</td>
	</tr>

	<tr>
		<td valign="top" width="300">
			<?php echo EXTERNALDBIMPORTFORM_2; ?>
		</td>
		<td valign="top"  width="600">
			<SELECT id="dbType" name="dbType" class="select" onchange="showConnections();return false;">
				<option value="0" <?php if ($dbType==0 || !$dbType) {echo ' selected';}?>>Choose database type</option>
				<option value="1" <?php if ($dbType==1) {echo ' selected';}?>>mySQL</option>
				<option value="2" <?php if ($dbType==2) {echo ' selected';}?>>MS SQL Server (mssql)</option>
				<option value="6" <?php if ($dbType==6) {echo ' selected';}?>>MS SQL Server (sqlsrv, php 5.3, Windows)</option>
				<!--option value="7" <?php if ($dbType==7) {echo ' selected';}?>>PostgreSQL</option-->
				<!--option value="3">MS SQL Server via ODBC/DSN</option>
				<option value="4">MS SQL Server via ODBC/DSN less</option>
				<option value="5">MS Access via ODBC/DSN less</option-->
			</select>
		</td>
	</tr>

	<tr id="dbNamerow">
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_3; ?>
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="dbName" name="dbName" value="<?php echo $dbName?>">
		</td>
	</tr>

	<tr id="dbHostrow">
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_7; ?>
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="dbHost" name="dbHost" value="<?php echo $dbHost?>">
		</td>
	</tr>
	<tr id="dsnNamerow" style="display:none;">
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_10; ?><br>
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="dsnName" name="dsnName" value="<?php echo $dsnName?>">
		</td>
	</tr>
	<tr id="dbUserNamerow">
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_8; ?>
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="dbUserName" name="dbUserName" value="<?php echo $dbUserName?>">
		</td>
	</tr>
	<tr id="dbPasswordrow">
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_9; ?>
		</td>
		<td valign="top">
			<input class="fieldbox11" type="password" id="dbPassword" name="dbPassword" value="<?php echo $dbPassword?>">
		</td>
	</tr>
	<tr>
		<td valign="top" colspan="2"><hr>
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2 height="20">
			<?php echo EXTERNALDBIMPORTFORM_13; ?>
		</td>
	</tr>
    <tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_12; ?>
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="tbName" name="tbName" value="<?php echo $tbName?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_1; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" name="email" id="email" value="<?php echo $email?>">
		</td>
	</tr>

	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_2 ;?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="name" name="name" value="<?php echo $name?>">
		</td>
	</tr>

	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_16; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="lastName" name="lastName" value="<?php echo $lastName?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_17; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subCompany" name="subCompany" value="<?php echo $subCompany?>">
		</td>
	</tr>

	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_3; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="address" name="address" value="<?php echo $address?>">
		</td>
	</tr>

	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_4; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="city" name="city" value="<?php echo $city?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_6; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="zip" name="zip" value="<?php echo $zip?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_5; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="state" name="state" value="<?php echo $state?>"> <span  onmouseover="infoBox('tip1', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(EXTERNALDBIMPORTFORM_5)?>', '30em', '1');">...</span><span name="tip1" id="tip1" style="display:none;"></span>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_7; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="country" name="country" value="<?php echo $country?>"> <span onmouseover="infoBox('tip2', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(EXTERNALDBIMPORTFORM_6)?>', '30em', '1');">...</span><span name="tip2" id="tip2" style="display:none;"></span>
		</td>
	</tr>

	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_18; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subPhone1" name="subPhone1" value="<?php echo $subPhone1?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_19; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subPhone2" name="subPhone2" value="<?php echo $subPhone2?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_20; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subMobile" name="subMobile" value="<?php echo $subMobile?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_25; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subPassword" name="subPassword" value="<?php echo $subPassword?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_22; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subBirthDay" name="subBirthDay" value="<?php echo $subBirthDay?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_23; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subBirthMonth" name="subBirthMonth" value="<?php echo $subBirthMonth?>">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo EXTERNALDBIMPORTFORM_35 .DBFIELD_24; ?>:
		</td>
		<td valign="top">
			<input type="text" class="fieldbox11" id="subBirthYear" name="subBirthYear" value="<?php echo $subBirthYear?>">
		</td>
	</tr>
<!-- custom fields START -->
	<?php if ($obj->getSetting("groupCustomSubField1", $idGroup)!="") {?>
	<tr>
		<td><?php echo EXTERNALDBIMPORTFORM_35; ?>
			<?php echo $obj->getSetting("groupCustomSubField1", $idGroup);?>:
		</td>
		<td>
			<input class="fieldbox11" type="text" id="customSubField1" name="customSubField1" value="<?php echo $customSubField1?>">
		</td>
	</tr>
	<?php } ?>

	<?php if ($obj->getSetting("groupCustomSubField2", $idGroup)!="") {?>
	<tr>
		<td><?php echo EXTERNALDBIMPORTFORM_35; ?>
			<?php echo$obj->getSetting("groupCustomSubField2", $idGroup)?>:
		</td>
		<td>
			<input class="fieldbox11" type="text" id="customSubField2" name="customSubField2" value="<?php echo $customSubField2?>">
		</td>
	</tr>
	<?php }?>

	<?php if ($obj->getSetting("groupCustomSubField3", $idGroup)!="") {?>
	<tr>
		<td><?php echo EXTERNALDBIMPORTFORM_35; ?>
			<?php echo $obj->getSetting("groupCustomSubField3", $idGroup)?>:
		</td>
		<td>
			<input class="fieldbox11" type="text" id="customSubField3" name="customSubField3" value="<?php echo $customSubField3?>">
		</td>
	</tr>
	<?php }?>
	<?php if ($obj->getSetting("groupCustomSubField4", $idGroup)!="") {?>
	<tr>
		<td><?php echo EXTERNALDBIMPORTFORM_35; ?>
			<?php echo$obj->getSetting("groupCustomSubField4", $idGroup)?>:
		</td>
		<td>
			<input class="fieldbox11" type="text" id="customSubField4" name="customSubField4" value="<?php echo $customSubField4?>">
		</td>
	</tr>
	<?php }?>

	<?php if ($obj->getSetting("groupCustomSubField5", $idGroup)!="") {?>
	<tr>
		<td><?php echo EXTERNALDBIMPORTFORM_35; ?>
			<?php echo $obj->getSetting("groupCustomSubField5", $idGroup)?>:
		</td>
		<td>
			<input class="fieldbox11" type="text" id="customSubField5" name="customSubField5" value="<?php echo $customSubField5?>">
		</td>
	</tr>
	<?php }?>
<!-- custom fields END -->
	<tr>
		<td valign="top" colspan="2"><hr>
		</td>
	</tr>

	<tr>
		<td valign=top><b><?php echo EXTERNALDBIMPORTFORM_14; ?></b> (<?php echo EXTERNALDBIMPORTFORM_15; ?>):
		<br><?php echo EXTERNALDBIMPORTFORM_16; ?>
		<br><?php echo EXTERNALDBIMPORTFORM_18; ?>
		</td>
		<td valign=top>
			<textarea class="textarea" id="customSQL" name="customSQL" rows=5 cols=80><?php echo $customSQL;?></textarea>
		</td>
	</tr>

	<tr>
		<td valign="top" colspan=2>
			&nbsp;
		</td>
	</tr>

	<tr>
		<td align=left valign=top colspan=2>
			<a href="#" class="cross" onclick="show_hide_div('listsCell','cross1');return false;"><strong><span id="cross1">[+]</span>&nbsp;<span><?php echo EXTERNALDBIMPORTFORM_17; ?></span></strong></a>
			<div  id="listsCell" style="display:none;"><br>
			<?php
			$lists =explode(",", $idList);
			$mySQL3="SELECT idList, listName FROM ".$idGroup."_lists where idGroup=$idGroup";
			$result	= $obj->query($mySQL3);
			while ($row = $obj->fetch_array($result)){?>
			<input id="idList" name="idList[]" type="checkbox" value="<?php echo $row['idList'];?>"
			<?php if (in_array($row['idList'], $lists)) {echo ' checked';}?>
			><?php echo $row['idList'].'. '.$row['listName'].'<br>';?>
			<?php } ?></div>
		</td>
	</tr>

    <tr>
		<td valign="top">
			<div style="margin-top:15px;margin-bottom:5px;"><strong><?php echo EXTERNALDBIMPORTFORM_19; ?></strong></div>
			<div><input type="checkbox" id="confirmed" name="confirmed" value="-1" <?php if ($confirmed==-1){echo ' checked';} ?>><?php echo EXTERNALDBIMPORTFORM_20; ?></div>
			<div><input type="checkbox" id="prefersHtml" name="prefersHtml" value="-1" <?php if ($prefersHtml==-1){echo ' checked';}?> ><?php echo EXTERNALDBIMPORTFORM_21; ?></div>
			<div><input type="checkbox" id="subUpdateDuplicates" name="subUpdateDuplicates" value="-1" <?php if ($subUpdateDuplicates==-1){echo ' checked';}?> ><?php echo EXTERNALDBIMPORTFORM_36; ?>&nbsp;<img onmouseout="hide_info_bubble('qi_3','0')" onmouseover="infoBox('qi_3', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(EXTERNALDBIMPORTFORM_11)?>', '35em', '0'); " src="./images/i1.gif" alt="">
			<span style="display: none;" id="qi_3"></span></div>
		</td>
		<td align=left valign=top>
			<div style="margin-top:15px;margin-bottom:5px;"><b><?php echo SUBSCRIBERSIMPORT_34; ?></b></div>
			<div><input type="checkbox" id="excludeGlobalOpts" name="excludeGlobalOpts" value="-1" <?php if ($excludeGlobalOpts==-1){echo ' checked';} ?>><?php echo SUBSCRIBERSIMPORT_32; ?></div>
			<div><input type="checkbox" id="excludeListOpts" name="excludeListOpts" value="-1" <?php if ($excludeListOpts==-1){echo ' checked';} ?>><?php echo SUBSCRIBERSIMPORT_33; ?></div>

		</td>
	</tr>
	<!--input type="hidden" name="idDataSource" id="idDataSource" value="<?php echo $idDataSource?>"-->
    <?php if ($idDS==0) {?>
	<tr>
		<td valign="top" colspan=2>
			<strong><?php echo EXTERNALDBIMPORTFORM_32;?></strong>
            &nbsp;&nbsp;<input class="fieldbox11" type="text" name="dataSourceFriendlyName" id="dataSourceFriendlyName" value="<?php echo $dataSourceFriendlyName?>" />
			&nbsp;&nbsp;<input onclick="processimportform('saveDataSource');return false;" id="saveDataSourceBtn" name="saveDataSourceBtn" value="<?php echo EXTERNALDBIMPORTFORM_33;?>" class="submit" type="submit">
		</td>
		<td align=left valign=top></td>
	</tr><?php } else {?>
	<tr>
		<td valign="top" colspan=2>
			<strong><?php echo EXTERNALDBIMPORTFORM_37;?></strong>
            &nbsp;&nbsp;<input class="fieldbox11" type="text" name="dataSourceFriendlyName" id="dataSourceFriendlyName" value="<?php echo $dataSourceFriendlyName?>" />
			&nbsp;&nbsp;<input onclick="processimportform('updateDataSource');return false;"id="updateDataSourceBtn" name="updateDataSourceBtn" value="<?php echo EXTERNALDBIMPORTFORM_34;?>" class="submit" type="submit">

		</td>
		<td align=left valign=top></td>
	</tr>

     <?php } ?>

	<tr>
		<td valign="top" colspan=2>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td align="right">
			<input onclick="processimportform('doImport');return false;" type="submit" <?php if (!$idDS) {echo ' disabled ';}?> name="doImport" id="doImport"  class="submit" value="<?php echo EXTERNALDBIMPORTFORM_24; ?>">
			<input onclick="processimportform('testCount');return false;" type="submit" <?php if ($idDS==0) {/*echo ' disabled ';*/}?> name="testCount" id="testCount" class="submit" value="<?php echo EXTERNALDBIMPORTFORM_23; ?>">
		</td>
		<td align="left">
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2>
			&nbsp;<div id="results"></div><div id="indicator" style="display:none" align=center><img src="./images/waitBig.gif" border=0 title="<?php echo GENERIC_18; ?>"><br><?php echo GENERIC_18; ?></div>
		</td>
	</tr>

</table>
</form>

<?php
include("footer.php");
?>