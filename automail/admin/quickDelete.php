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
?>
<script type="text/javascript" language="javascript">
function doDelete() {
	$('#results').html();
	if ($("#subsToDelete").blank()) {
		openAlertBox('<?php echo fixJSstring(SUBSCRIBERSIMPORT_30)?>','');
		return false;
	}
    $("#indicator").show();
	$("#processButton").prop("disabled",true);
	$.ajax({
		type: "POST",
		url:"quickDeleteExec.php",
		data: $('#quickDeleteExec').serialize()
		}).done(function(data,status) {
			showResponse(data, status);
			})
	  		.fail(function(data, status) {showException(status); });	
	return false;
	function showResponse(data) {
		if (data=="sessionexpired")		{
			alert('<?php echo fixJSstring(GENERIC_3);?>');
			document.location.href="index.php";
		}
		else {
    		$("#results").html(data);
			$("#indicator").hide();
	   		$("#processButton").prop("disabled",false);
		}
	}
    function showException(status) 	{
		alert('<?php echo fixJSstring(GENERIC_8);?>');
    	$("#indicator").hide();
	   	$("#processButton").prop("disabled",false);
	}
}	//main function ends
</script>

<table width="960px">
	<tr>
		<td valign="top">
			<span class="title"><?php echo DELETEQUICKREMOVE_1; ?></span>
		</td>
		<td align="right">
			<img src="./images/quickdelete.png" alt="">
		</td>
	</tr>
</table>
<form action=quickDeleteExec.php name="quickDeleteExec" id="quickDeleteExec" method="post">
<table border=0 cellpadding=4 cellspacing=0>
	<tr>
		<td colspan=2 valign=top>
			<?php echo DELETEQUICKREMOVE_2; ?>
		</td>
	</tr>
	<tr>
		<td>
			<TEXTAREA name="subsToDelete" id="subsToDelete" COLS="40" ROWS="20" class="textarea">jojo@somemail.com</TEXTAREA>
		</td>
	</tr>
	<tr>
		<td align=right>
			<input type="submit" class="submit" onclick="return doDelete();return false;" id="processButton" name="processButton" value="<?php echo DELETEQUICKREMOVE_3; ?>">
		</td>
	</tr>
	<tr><td><div id=results></div><div id="indicator" style="display:none" align=center><img src="./images/waitBig.gif" border=0 title="<?php echo GENERIC_18; ?>"><br><?php echo GENERIC_18; ?></div></td></tr>
</table>
</form>
<?php
$obj->closeDb();
include("footer.php");
?>