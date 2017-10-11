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
isset($_GET['idAdminM'])?$idAdminM = $_GET['idAdminM']:$idAdminM ='';

if ($idAdminM==1 && $sesIDAdmin!=1) {header("Location: message.php?message=".urlencode(LISTADMINS_27));return false;}
include('header.php');

if ($idAdminM) {
	$mySQL="SELECT adminName, adminFullName, adminPassword, adminEmail, emailAlert, active from ".$idGroup."_admins where idAdmin=$idAdminM AND idGroup=$idGroup";
	$result = $obj->query($mySQL);
	if ($obj->num_rows($result)==0) {
		echo 'Invalid admin id';die;
	}
	else {
	$row = $obj->fetch_array($result);
	$adminFullNameM = $row['adminFullName'];
	$adminEmailM 	= $row['adminEmail'];
	$emailAlertM 	= $row['emailAlert'];
	$activeM 		= $row['active'];
	$adminNameM 	= $row['adminName'];
	$adminPasswordM = $row['adminPassword'];
	$pageTitle		= LISTADMINS_19;
	$loadicon		= 'editadmin';
	}
}
else {
	$idAdminM		="";
	$adminFullNameM = "";
	$adminEmailM 	= "";
	$emailAlertM 	= "";
	$activeM 		= "";
	$adminNameM 	= "";
	$adminPasswordM = "";
	$pageTitle		= LISTADMINS_11;
	$loadicon		= 'addadmin';
}
?>
<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo $pageTitle; ?></span>
		</td>
		<td align="right" valign="top">
			<img src="./images/<?php echo $loadicon?>.png" width="55" height="62">
		</td>
	</tr>
</table>

<script type="text/javascript" language="javascript">
function checkEntries() {
	if ($("#adminFullNameF").blank() || $("#adminEmailF").blank() || $("#adminPasswordF").blank() || $("#adminNameF").blank()) {
	   openAlertBox('<?php echo fixJSstring(LISTADMINS_16)?>','');
	   return false;
	}
	else {
		$("#modify").hide();
		$("#wait").show();
		var url="modifyAdminExec.php";
		var params= $('#newAdmin').serialize();
		$.post(url, params)
		 .done(function(data,status) {
			showResponse(data,status);
		 })
		 .fail(function(data, status) {
		 	showException(status);
		 })
	}
}
	function showResponse(data,status) {
		$("#modify").show();
		$("#wait").hide();
		switch (data) {
			case "1":
				openAlertBox("<?php echo fixJSstring(LISTADMINS_18);?>","");
		  		break
			case "demo":
				openAlertBox("<?php echo fixJSstring(DEMOMODE_1);?>","");
		  		break
			case "added":
				document.location.href="admins.php?message=<?php echo fixJSstring(LISTADMINS_17)?>";
		  		break
			case "updated":
				document.location.href="admins.php?message=<?php echo fixJSstring(LISTADMINS_24)?>";
		  		break
			case "sessionexpired":
				openAlertBox("<?php echo fixJSstring(GENERIC_3)?>","index.php");
		  		break
		  	default:
		  		openAlertBox('<?php echo fixJSstring(GENERIC_8)?>','');
		}
	}
	function showException(status) {$("#modify").show();$("#wait").hide();alert('<?php echo fixJSstring(GENERIC_8); ?>');return false;}

</script>

<form method="post" id="newAdmin" name="newAdmin[]" onsubmit="checkEntries();return false;" action="modifyAdminExec.php" autocomplete="off">
<input type="hidden" name="idAdminF" value="<?php echo $idAdminM?>">
<input type="hidden" name="action" value="modify">
<table width="400" border="0">
    <tr>
      <td width="100"><?php echo LISTADMINS_4; ?></td>
      <td width="200">
        <input class=fieldbox11 type="text" id="adminFullNameF" name="adminFullNameF" value="<?php echo strForInput($adminFullNameM);?>" size=30 autocomplete="off">
	  </td>
    </tr>

    <tr>
      <td width="100"><?php echo LISTADMINS_6; ?></td>
      <td width="200">
        <input class=fieldbox11 type="text" id="adminEmailF" name="adminEmailF" value="<?php echo $adminEmailM?>" size=30 autocomplete="off">
	  </td>
    </tr>
    <tr>
      <td width="100"><?php echo LISTADMINS_8; ?></td>
      <td width="200">
        <input type="checkbox" name="emailAlert" value="-1" <?php if($emailAlertM==-1){echo ' checked';}?>>
        &nbsp;<img onmouseover="infoBox('admin_1', '<?php echo fixJSstring(LISTADMINS_8)?>', '<?php echo fixJSstring(LISTADMINS_15)?>', '15em','0'); " onmouseout="hide_info_bubble('admin_1','0')" src="./images/i1.gif"><span style="display: none;" id="admin_1"></span>
	  </td>
    </tr>
<?php if ($idAdminM==1) { ?>
        <input type="hidden" name="activeAdmin" value="-1">
<?php } else { ?>
    <tr>
      <td width="100"><?php echo LISTADMINS_25; ?></td>
      <td width="200">
        <input type="checkbox" name="activeAdmin" value="-1" <?php if($activeM==-1){echo ' checked';} ?>>
        &nbsp;<img onmouseover="infoBox('admin_2', '<?php echo fixJSstring(LISTADMINS_25)?>', '<?php echo fixJSstring(LISTADMINS_26)?>', '15em','0'); " onmouseout="hide_info_bubble('admin_2','0')" src="./images/i1.gif"><span style="display: none;" id="admin_2"></span>
	  </td>
    </tr>
<?php }?>
    <tr>
      <td colspan="2" width="100"><br>
      	<b><?php echo LISTADMINS_14; ?></b>
	  </td>
    </tr>

    <tr>
      <td width="100"><?php echo LISTADMINS_12; ?></td>
      <td width="200">
        <input class=fieldbox11 type="text" id="adminNameF" name="adminNameF" value="<?php echo $adminNameM?>" size=20 autocomplete="off">
	  </td>
    </tr>
    <tr>
      <td width="100"><?php echo LISTADMINS_13; ?></td>
      <td width="200">
		<input class=fieldbox11 type="password" id="adminPasswordF" name="adminPasswordF" value="<?php echo $adminPasswordM?>" size=20 autocomplete="off">
      </td>
    </tr>

    <tr>
      <td width="100">&nbsp;</td>
      <td width="200">&nbsp;</td>
    </tr>
    <tr>
      <td>
	</td>
      <td>
            <input class="submit" type="submit" id="modify" name="modify" value="<?php echo LISTADMINS_20?>">
			<div id="wait" style="display:none;"><img src="./images/waitSmall.gif"></div>
      </td>
    </tr>
  </table>
</form>
<?php
include('footer.php');
?>