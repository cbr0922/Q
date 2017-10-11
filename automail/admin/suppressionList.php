<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 					= new db_class();
$groupName 				=	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
include('header.php');
showMessageBox();

$bannedSubscribers 	= $obj->tableCount_condition($idGroup."_subscribers", " where emailIsBanned=-1 AND idGroup=".$idGroup."");

?>
<script type="text/javascript" language="javascript">
function doSuppress(action) {
	$('#results').html();
	if ($("#subsToBan").blank()) {
		openAlertBox('<?php echo fixJSstring(SUBSCRIBERSIMPORT_30)?>','');
		return false;
	}
    $("#indicator").show();
	$("#processButton").prop("disabled",true);
	params=$('#suppressionListExec').serialize();
	params=params+"&action="+action;
	var url="suppressionListExec.php?"+params;
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
}	//main function ends

function showResponse(data) {
		if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3);?>');
			document.location.href="index.php";
		}
		$('#results').html(data);
    	$("#indicator").hide();
		$("#processButton").prop("disabled",false);
		
//		new Effect.Highlight($("results"));
	}
    function showException(status) {
		alert('<?php echo fixJSstring(GENERIC_8);?>');
	    $("#indicator").hide();
		$("#processButton").prop("disabled",false);
	}
</script>

<table width="960px" cellpadding="2" cellspacing="0" border="0">
	<tr>
		<td width="45%" valign="top">
			<span class="title"><?php echo ADMIN_HEADER_75;?></span>&nbsp;&nbsp;<img onclick="infoBox('about', '<?php echo fixJSstring(ADMIN_HEADER_75.' - '.SUPLIST_12)?>', '<?php echo fixJSstring(SUPLIST_1)?>', '30em', '1');"  title="<?php echo GENERIC_6; ?>" src="./images/i1.gif" alt="<?php echo GENERIC_6; ?>"><span style="display:none;" id="about"></span>
			<div style="width:380px;margin-top:10px">
				<a href="#" onclick="show_hide_div('actions','cross1');return false;" class="menuSmall"><span id="cross1">[+]</span>&nbsp;<?php echo SUPLIST_11;?></a>
				<ul id="actions" style="display:none">
				<li><a href="quickView.php?flag=inactive" target="blank"><?php echo ADMIN_HEADER_39;?></a></li>
				<li><a href="#" onclick="doSuppress('removeAll');return false;"><?php echo SUPLIST_8;?></a></li>
				<li><a href="#" onclick="doSuppress('deleteAll');return false;"><?php echo SUPLIST_9;?></a></li>
				<li><a href="#" onclick="doSuppress('activateAll');return false;"><?php echo SUPLIST_10;?></a></li>
				</ul>
			</div>
		</td>
		<td  width="55%" align="right" valign="top"><img src="./images/stopL.png" width="48" height="46" alt=""></td>
	</tr>
</table>

<div style="margin-top:20px">
<form action=suppressionListExec.php name="suppressionListExec" id="suppressionListExec" method="post">
<table border="0" cellpadding=2 cellspacing=0>
	<tr>
		<td colspan=2 valign=top>
			<?php echo DELETEQUICKREMOVE_2; ?>
		</td>
	</tr>
	<tr>
		<td>
			<TEXTAREA name="subsToBan" id="subsToBan" COLS="40" ROWS="20" class="textarea">jojo@somemail.com</TEXTAREA>

		</td>
		<td valign="top"><div id=results><span class="menuSmall"><?php echo SUPLIST_2.'&nbsp;'.$bannedSubscribers.'&nbsp;'.SUPLIST_3;?></span></div><div id="indicator" style="display:none" align=center><img src="./images/waitBig.gif" border="0" alt="" title="<?php echo GENERIC_18; ?>"><br><?php echo GENERIC_18; ?></div></td>
	</tr>
	<tr>
		<td align=right>
			<input type="submit" class="submit" onclick="return doSuppress('suppress');return false;" id="processButton" name="processButton" value="<?php echo HOME_4; ?>">
		</td>
		<td></td>
	</tr>
</table>
</form>
</div>
<?php
$obj->closeDb();
include("footer.php");
?>