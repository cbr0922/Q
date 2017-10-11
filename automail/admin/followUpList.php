<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
include('header.php');
showMessageBox();
?>
<script type="text/javascript" language="javascript">
<!--
function  showLinks(idCampaign) {
	$("#statusmessage").hide();
	if ($("#selectedCampaign").val() == "0" || $("#selectedCampaign").val() == "") 	{
		$("#statusmessage").attr('class', 'errormessage');
		$("#statusmessage").show();
		$("#statusmessage").html = '<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(FOLLOWUPLIST_14)?>';
		$("#selectedCampaign").focus();
		return false;
	}
	if ($("#FilterOption").val() == "0" || $("#FilterOption").val() == "1" || $("#FilterOption").val() == "2" || $("#FilterOption").val() == "3" || $("#FilterOption").val() == "4" || $("#FilterOption").val() == "5")	{
		$("#linksRow").hide();
	}
	else if ($("#FilterOption").val() == "6" || $("#FilterOption").val() == "7") 	{
		$.get("getCampaignLinks.php?selectedCampaign="+idCampaign)
			.done(function(data,status) {//alert(data);
				if (data=="sessionexpired") {
					alert('<?php echo fixJSstring(GENERIC_3)?>');
					document.location.href="index.php";
				}
				else if (data=="0"){
					//alert("not possible");
					$("#FilterOption").val(0);
					$("#statusmessage").attr('class', 'errormessage');
					$("#statusmessage").show();
					$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(FOLLOWUPLIST_31)?>');
				}
				else {
					$("#linksDiv").html(data);	
					$("#linksRow").show();
					
				}
			 })
			.fail(function(data, status) {showException(status); });
	}
}

function createFollowUp() {
	if ($("#selectedCampaign").val()=="0" || $("#selectedCampaign").val() == "") 	{
		$("#statusmessage").attr('class', 'errormessage');
		$("#statusmessage").show();
		$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(FOLLOWUPLIST_14)?>');
		$("#selectedCampaign").focus();
		return false;
	}
	if ($("#FilterOption").val() == "0" || $("#FilterOption").val() == "")	{	
		$("#statusmessage").attr('class', 'errormessage');
		$("#statusmessage").show();
		$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(FOLLOWUPLIST_15)?>');
		$("#FilterOption").focus();
		return false;
	}
	if ( ($("#FilterOption").val() == "6" || $("#FilterOption").val() == "7") && ($("#links").val()=="0" || $("#links").val()=="")) 	{
		$("#statusmessage").attr('class', 'errormessage');
		$("#statusmessage").show();
		$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(FOLLOWUPLIST_16)?>');
		$("#links").focus();
		return false;
	}
	else {
		$("#statusmessage").attr('class', 'waitmessage');
		$("#statusmessage").show();
		$("#statusmessage").html('<img src="./images/waitSmall.gif">&nbsp;<?php echo fixJSstring(GENERIC_4)?>');


		var url="followUpListExec.php";
		if ($("#links")) {links = "&linkUrl="+encodeURIComponent($('#links').val());} else {links="";}
		var params="FilterOption="+$('#FilterOption').val()+links+"&selectedCampaign="+encodeURIComponent($('#selectedCampaign').val());
		$("#buttons").hide();
		$.ajax({
			type: "POST",
			url:url,
			data: params
			}).done(function(data,status) {
				showResponse(data, status);//alert(data);
				})
		  		.fail(function(data, status) {showException(status); });
	}
	function showResponse(data, status) 	{
		$("#buttons").show();
		if (data=="-1") {
			$("#statusmessage").show();
			$("#statusmessage").html('<?php echo fixJSstring(GENERIC_8)?>');
		}
		else if (data=="sessionexpired") 		{
			alert('<?php echo fixJSstring(GENERIC_3)?>');
			$("#statusmessage").hide();
		}
		else if (data=="0") {
			$("#statusmessage").attr('class', 'errormessage');
			$("#statusmessage").show();
			$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(FOLLOWUPLIST_17)?>');
		}
		else if (data=="ok") 		{
			$("#statusmessage").attr('class', 'okmessage');  
			$("#statusmessage").show();
			$("#statusmessage").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(FOLLOWUPLIST_1)?>');
			$("#statusmessage").effect( "highlight",{color:"#ffff99"}, 3000 );
		}
		else if (data=="demo") {
			$("#statusmessage").attr('class', 'okmessage');
			$("#statusmessage").show();
			$("#statusmessage").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(DEMOMODE_1)?>');
			$("#statusmessage").effect( "highlight",{color:"#ffff99"}, 3000 );
		}

		else{	//some general error
			$("#statusmessage").attr('class', 'errormessage');
			$("#statusmessage").show();
			$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(GENERIC_7)?>'+data);
		}
	}
}
function  clearAll() {
	$("#buttons").show();
	$("#FilterOption").val(0);
	$("#selectedCampaign").val(0);
	$("#linksRow, #statusmessage").hide();
}
function  clearRest() {
	$("#buttons").show();
	$("#FilterOption").val(0);
	$("#linksRow, #statusmessage").hide();
}
function showException() 	{
	$("#buttons").show();
	alert('<?php echo fixJSstring(GENERIC_8); ?>');
	$("#statusmessage").attr('class', 'errormessage'); 
	$("#statusmessage").show();
	$("#statusmessage").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(GENERIC_7)?>');
}

</script>
<table border="0" width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo FOLLOWUPLIST_8; ?></span>
		</td>
		<td align=right>
			<Img alt="" src="./images/flist.png" width="50" height="63">
		</td>
	</tr>
</table>
<br>
<table  border="0" cellpadding="4" cellspacing="0" id="followupoptionsTable">
	<tr id="mailLogsRow">
		<td valign="top">
			<?php echo FOLLOWUPLIST_9; ?>:
		</td>
		<td>
			<select id="selectedCampaign" name="selectedCampaign" class=select onchange="clearRest();return false;">
			<option value="0"><?php echo FOLLOWUPLIST_10; ?></option>
			<?php
			$mySQL6="SELECT distinct idCampaign, campaignName, ".$idGroup."_campaigns.idList, ".$idGroup."_campaigns.listName, dateCompleted
                FROM ".$idGroup."_campaigns WHERE completed=-1
                AND (".$idGroup."_campaigns.idList=0 OR ".$idGroup."_campaigns.idList=-1
                OR ".$idGroup."_campaigns.idList IN (SELECT idList from ".$idGroup."_lists)) ORDER by idCampaign desc";
			$result	= $obj->query($mySQL6);
            $rows 	= $obj->num_rows($result);
			if ($rows){
				while ($row = $obj->fetch_array($result)){?>
					<option value="<?php echo $row['idCampaign'];?>"><?php echo $row['idCampaign'].'. '.$row['campaignName'];?> [<?php echo FOLLOWUPLIST_20; ?>: <?php echo addOffset($row['dateCompleted'], $pTimeOffsetFromServer, $groupDateTimeFormat);?>, <?php echo FOLLOWUPLIST_21;?><?php echo $row['listName'];?>]</option>
				<?php }
			}
            else {?>
				<option value=0><?php echo FOLLOWUPLIST_22; ?></option>
			<?php }?>
			</select>
		</td>
	</tr>

   	<tr id="followupoptionsRow">
   		<td valign="top">
			<?php echo FOLLOWUPLIST_13; ?>:
		</td>
		<td>
			<select id="FilterOption" name="FilterOption" class="select" onchange="showLinks($('#selectedCampaign').val());">
			<option value="0"><?php echo FOLLOWUPLIST_10; ?></option>
			<option value="1"><?php echo FOLLOWUPLIST_23; ?></option>
			<option value="2"><?php echo FOLLOWUPLIST_24; ?></option>
			<option value="3"><?php echo FOLLOWUPLIST_25; ?></option>
			<option value="4"><?php echo FOLLOWUPLIST_26; ?></option>
			<option value="5"><?php echo FOLLOWUPLIST_27; ?></option>
			<option value="6"><?php echo FOLLOWUPLIST_28; ?></option>
			<option value="7"><?php echo FOLLOWUPLIST_29; ?></option>
			</select>
		</td>
	</tr>
	<tr id="linksRow" style="display:none;">
		<td valign="top"><?php echo FOLLOWUPLIST_30; ?>: </td>
		<td>
			<div id="linksDiv"></div><!--input type=hidden name="links" id="links" value="0"-->
		</td>
	</tr>
	<tr id="buttons">
		<td></td>
		<td>
			<input type="submit" class="submit" value=" <?php echo FOLLOWUPLIST_18; ?> " onclick="createFollowUp();">
			<input type="submit" class="submit" value=" <?php echo FOLLOWUPLIST_19; ?> " onclick="clearAll();return false;">
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center><div id="statusmessage" style="display:none;"></div></td>
	</tr>
</table>
<?php
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>
