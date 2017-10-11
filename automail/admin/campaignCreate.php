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
$groupDoubleOptin       =	$obj->getSetting("groupDoubleOptin", $idGroup);
include('header.php');
showMessageBox();
(isset($_GET['idHtmlNewsletter']))?$idHtmlNewsletter = $_GET['idHtmlNewsletter']:$idHtmlNewsletter=0;
?>
<script type="text/javascript" language="javascript">
function validateMailing(action) {
	var action;
	if (action=="createCampaign") 	{
		if  (($("#idhtmlbody").val()== "0") && ($("#idtextbody").val()== "0") && ($("#urltosend").val()== "")) {
			$("#messageDiv").show();	
			$("#messageDiv").attr('class', 'errormessage'); 
			$("#messageDiv").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_33);?>');
			return false;
		}
		if  ($("#urltosend").val()!="" && ($("#idhtmlbody").val()!="0" || $("#idtextbody").val()!="0"))		{
			$("#messageDiv").attr('class', 'errormessage');  
			$("#messageDiv").show();
			$("#messageDiv").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_32);?>');
			return false;
		}
		if  ($("#urltosend").val()!="" && $("#emailsubject").val()=="")		{
			$("#messageDiv").show();
			$("#messageDiv").attr('class', 'errormessage'); 
			$("#messageDiv").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_36);?>');
			$("#emailsubject").focus();
			return false;
		}
		if ($("#google_check").prop('checked') && ($("#utm_source").val()=="" || $("#utm_medium").val()=="" || $("#utm_campaign").val()=="")) {	//$('input.google_check').is(':checked')
			$("#messageDiv").attr('class', 'errormessage'); 
			$("#messageDiv").show();
	 		$("#messageDiv").html('<img src="./images/warning.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_53).fixJSstring(" Google Analytics")?>');
			$("#utm_source").focus();
			return false;
		}
		if (validateCount()!=true) {return false;}
    }	//for createCampaign & sendnow action
	switch(action) 	{
		case "createCampaign":
			createCampaign(action);
			break;
		case "count":
			countSubcribers(action);
			break;
		default:
			alert('Ooops');
			return false;
	}
}

function createCampaign(action) {
	var url="campaignCreateExec.php"
	var params=$("#newMailing").serialize();
    params= params+"&action="+action;
    $("#messageDiv").hide();
	hideAllButtons();
	$("#loading").show();
   	$.post(url, params)
		 .done(function(data,status) {
			GoodResponse(data,status);
		 })
		 .fail(function(data, status) {
		 	BadResponse(status);
		 })
    function GoodResponse(data,status) {
	    //alert(status);
        if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3)?>');
			document.location.href="index.php";
		}
		else if (data=="demo") {
			$("#loading").hide();
			showAllButtons();
			$("#messageDiv").attr('class', 'okmessage');  
			$("#messageDiv").show();
			$("#messageDiv").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(DEMOMODE_1)?>');
//			new Effect.Highlight($("messageDiv"));
			return false;
		}
		else {
			//alert(data);
            showAllButtons();
			$("#loading").hide();
		   	$("#messageDiv").attr('class', 'okmessage');  
			$("#messageDiv").show();
			$("#messageDiv").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_34)?><a href="campaigns.php"><?php echo fixJSstring(CAMPAIGNCREATE_35)?></a>');
		}
	}	//end showResponse2 function
}		// createCampaign function


function countSubcribers(action) {
	if (validateCount()!=true) {return false;}
	$("#loading").show();
	hideAllButtons();
	$("#messageDiv").hide();
	var params=$("#newMailing").serialize();
    params= params+"&action="+action;
	var url="campaignCreateExec.php?"+params;
   	$.get(url)
	.done(function(data,status) {
		if (data=="sessionexpired") {
			alert('<?php echo fixJSstring(GENERIC_3)?>');
			document.location.href="index.php";
		}
		else {
			$("#loading").hide();
			showAllButtons();
			$("#messageDiv").attr('class', 'okmessage'); 
			$("#messageDiv").show();
			$("#messageDiv").html('<img src="./images/doneOk.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_30)?>'+data+'<?php echo fixJSstring(CAMPAIGNCREATE_31)?>');
		}
		 })
	.fail(function(data, status) {BadResponse(status); });
}
function BadResponse(status) {
	alert('<?php echo fixJSstring(GENERIC_8)?>');
	$("#loading").hide();
	showAllButtons();
}
function hideAllButtons() {$("#countbutton, #createEntry").hide();}
function showAllButtons() {$("#countbutton, #createEntry").show();}

function unCheckLists() {
    var k=document.newMailing.idList.length;
    if(typeof k=="undefined") {
        document.newMailing.idList.checked=false;
        return false;}
    else {
        for (var i=0;i<document.newMailing.idList.length;i++) {
            document.newMailing.idList[i].checked=false;}}
    $("#lists").hide();
}

function someLists() {
//	$("#recipients3").prop('checked')=true;
}

function validateCount(){
	var checkFound=false;
    var k=document.newMailing.idList.length;
    if (typeof k=="undefined" && document.newMailing.idList.checked==true){checkFound = true;}
    for(var i=0; i < k; i++) {
        if(document.newMailing.idList[i].checked==true){checkFound = true;}}
    if 	((!$("#recipients1").prop('checked') && !$("#recipients2").prop('checked') && !$("#recipients3").prop('checked')) || ($("#recipients3").prop('checked') && checkFound!= true)) {
  		$("#messageDiv").attr('class', 'errormessage');
		$("#messageDiv").show();
		$("#messageDiv").html('<img  alt="" src="./images/warning.png">&nbsp;<?php echo fixJSstring(CAMPAIGNCREATE_37);?>'); 
		return false;}
    else {return true;}
}
</script>
<table border="0" cellpadding=4 width="960px">
	<tr>
		<td valign="top">
   			<span class="title"><?php echo CAMPAIGNCREATE_1; ?></span>
		</td>
		<td align=right>
			<img src="./images/newcampaign.png" alt="" width="65" height="51">
   		</td>
	</tr>
</table>
<div style="margin-top:12px;"></div>
<form name="newMailing" id="newMailing" onSubmit="return false;" action="">
<table border="0" cellpadding="6" cellspacing="0" width="100%" id="table1"  style="border: #CCCCCC 0px solid">
	<tr bgcolor="#F1F1F2">
   		<td width="180" valign="top" style="padding-top:10px; padding-bottom:10px;"><?php echo CAMPAIGNCREATE_54; ?>:</td>
		<td valign="top"style="padding-top:10px; padding-bottom:10px;"><input class="fieldbox11" size="80" type="text" id="campaignname" name="campaignname" value=""></td>
        <td valign="top"style="padding-top:10px; padding-bottom:10px;"><img onmouseout="hide_info_bubble('cname','0')" onmouseover="topInfoBox('cname', '<?php echo fixJSstring(CAMPAIGNCREATE_54);?>', '<?php echo fixJSstring(CAMPAIGNCREATE_55)?>', '25em', '0'); " src="./images/i1.gif" alt=""><span style="display: none;" id="cname"></span></td>
	</tr>
<!-- Custom sender settings START -->
	<tr bgcolor="#FAFAFA">
		<td valign="top" style="padding-top:10px; border-top: #CCCCCC 1px solid"><?php echo CAMPAIGNCREATE_61; ?>:</td>
		<td style="padding-top:10px; border-top: #CCCCCC 1px solid"><input type="checkbox" id="customSender" name="customSender" value="-1" onClick="show_hide_many(Array('customSender_1','customSender_2','customSender_3'),'');"></td>
		<td style="padding-top:10px; border-top: #CCCCCC 1px solid"><img onmouseout="hide_info_bubble('csend','0')" onmouseover="topInfoBox('csend', '<?php echo fixJSstring(CAMPAIGNCREATE_61)?>', '<?php echo fixJSstring(CAMPAIGNCREATE_62).'&nbsp;'.fixJSstring(ADMIN_HEADER_81).'> '.fixJSstring(ADMIN_HEADER_60).'> '.fixJSstring(ADMIN_HEADER_61)?>', '25em', '0'); "  src="./images/i1.gif"><span style="display:none;" id="csend"></span></td>
  	</tr>
	<tr bgcolor="#FAFAFA" id="customSender_1" style="display:none">
   		<td style="padding-left:12px;border-left:#999999 0px solid;"><?php echo CAMPAIGNCREATE_58; ?>:</td>
		<td valign="top"><input class="fieldbox11" size="60" type="text" id="fromName" name="fromName" value=""></td>
        <td valign="top"></td>
	</tr>
	<tr bgcolor="#FAFAFA" id="customSender_2" style="display:none">
   		<td style="padding-left:12px;border-left:#999999 0px solid;"><?php echo CAMPAIGNCREATE_59; ?>:</td>
		<td valign="top"><input class="fieldbox11" size="60" type="text" id="fromEmail" name="fromEmail" value=""></td>
        <td valign="top"></td>
	</tr>
	<tr bgcolor="#FAFAFA" id="customSender_3" style="display:none">
   		<td style="padding-left:12px;border-left:#999999 0px solid;"><?php echo CAMPAIGNCREATE_60; ?>:</td>
		<td valign="top"><input class="fieldbox11" size="60" type="text" id="replyToEmail" name="replyToEmail" value=""></td>
        <td valign="top"></td>
	</tr>

<!-- HTML/TEXT NEWSLETTERS -->
   	<tr bgcolor="#F1F1F2">
   		<td valign="top" style="padding-top:10px; border-top: #CCCCCC 1px solid"><?php echo CAMPAIGNCREATE_2; ?></td>
        <td valign="top" style="padding-top:10px; border-top: #CCCCCC 1px solid">
			<?php
            $mySQL1="Select idNewsletter, name, dateCreated, dateSent from ".$idGroup."_newsletters WHERE idGroup=".$idGroup." AND html=-1 order by idNewsletter desc";
		    $result1	= $obj->query($mySQL1);?>
			<SELECT id="idhtmlbody" name="idhtmlbody" class="select">
			<option value="0"><?php echo CAMPAIGNCREATE_4; ?></option>
			<?php	while ($row = $obj->fetch_array($result1)){?>
			<option value="<?php echo $row['idNewsletter'];?>" <?php if ($idHtmlNewsletter==$row['idNewsletter']) {echo ' selected';}?> ><?php echo $row['idNewsletter'];?>. <?php echo $row['name'];?> - (<?php echo CAMPAIGNCREATE_16; ?><?php echo addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat)?>, <?php echo CAMPAIGNCREATE_17; ?><?php if ($row['dateSent']) {echo addOffset($row['dateSent'], $pTimeOffsetFromServer, $groupDateTimeFormat);} else {echo CAMPAIGNCREATE_18;} ?>)</option>
			<?php } ?>
			</SELECT>
		</td>
        <td valign="top" style="padding-top:10px; border-top: #CCCCCC 1px solid"><img onmouseout="hide_info_bubble('htmlnl','0')" onmouseover="topInfoBox('htmlnl', '<?php echo fixJSstring(CAMPAIGNCREATE_44);?>', '<?php echo fixJSstring(CAMPAIGNCREATE_6)?>', '25em', '0'); " src="./images/i1.gif" alt=""><span style="display: none;" id="htmlnl"></span></td>
	</tr>
	<tr bgcolor="#F1F1F2" valign="top">
		<td valign="top"><?php echo CAMPAIGNCREATE_3; ?></td>
		<td valign="top">
			<?php
            $mySQL2="Select idNewsletter, name, dateCreated, dateSent from ".$idGroup."_newsletters where idGroup=".$idGroup." AND html=0 order by idNewsletter desc";
		    $result2	= $obj->query($mySQL2);?>
			<SELECT id="idtextbody" name="idtextbody" class="select">
			<option value="0"><?php echo CAMPAIGNCREATE_4; ?></option>
			<?php	while ($row = $obj->fetch_array($result2)){?>
			<option value="<?php echo $row['idNewsletter'];?>"><?php echo $row['idNewsletter'];?>. <?php echo $row['name'];?> - (<?php echo CAMPAIGNCREATE_16; ?><?php echo addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat)?>, <?php echo CAMPAIGNCREATE_17; ?><?php if ($row['dateSent']) {echo addOffset($row['dateSent'], $pTimeOffsetFromServer, $groupDateTimeFormat);} else {echo CAMPAIGNCREATE_18;} ?>)</option>
			<?php } ?>
			</SELECT>
		</td>
        <td></td>
	</tr>
<!-- URL SEND -->
	<tr bgcolor="#FAFAFA">
		<td valign="top" style="padding-top:10px; border-top: #CCCCCC 1px solid"><?php echo CAMPAIGNCREATE_25; ?></td>
		<td valign="top" style="padding-top:10px; border-top: #CCCCCC 1px solid"><input class="fieldbox11" size="80" type="text" id="urltosend" name="urltosend" value=""></td>
        <td style="padding-top:10px; border-top: #CCCCCC 1px solid"></td>
	</tr>
	<tr bgcolor="#FAFAFA">
   		<td valign="top"><?php echo CAMPAIGNCREATE_26; ?></td>
		<td valign="top"><input class="fieldbox11" size="40" type="text" id="emailsubject" name="emailsubject" value=""></td>
        <td></td>
	</tr>
<!-- RECIPIENTS -->
   <tr bgcolor="#F1F1F2">
		<td valign="top" style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 1px solid"><?php echo CAMPAIGNCREATE_5; ?></td>
		<td valign="top" style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 1px solid">
           <input type="radio" id="recipients1" name="recipients" value="allSubs" onclick="unCheckLists()"><?php echo CAMPAIGNCREATE_28?><br>
 		   <input type="radio" id="recipients2" name="recipients" value="allLists" onclick="unCheckLists()"><?php echo CAMPAIGNCREATE_11?><br>
           <input type="radio" id="recipients3" name="recipients" value="selectedLists"  onclick="show_hide_div('lists','');"><?php echo CAMPAIGNCREATE_29?><br>
            <ul id="lists" style="display:none;">
              <?php
      			 $mySQL3="SELECT idList, listName, isPublic FROM ".$idGroup."_lists WHERE idGroup=$idGroup order by idList desc";
  	    		 $result3	= $obj->query($mySQL3);
                 $lists 	= $obj->num_rows($result3);
  		    	 if ($lists) {
                    while ($row = $obj->fetch_array($result3)){?>
  		            <input type="checkbox" id="idList"  onclick="someLists();" name="idList[]" value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php  echo $row['listName'];?>	(<?php if ($row['isPublic']==-1) {echo CAMPAIGNCREATE_19;} else {echo CAMPAIGNCREATE_20;}?>)<br />
  			        <?php }
                 } else { echo '<input type="hidden" id="idList" name="idList" value="0" />';}?>
            </ul>
		</td>
        <td valign="top" style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 1px solid"><img onmouseout="hide_info_bubble('recip','0')" onmouseover="topInfoBox('recip', '<?php echo fixJSstring(CAMPAIGNCREATE_28);?>', '<?php echo fixJSstring(CAMPAIGNCREATE_23).'<br /><b>'.fixJSstring(CAMPAIGNCREATE_11).'</b><br />'.fixJSstring(CAMPAIGNCREATE_7).'<br /><b>'.fixJSstring(CAMPAIGNCREATE_29).'</b><br />'.fixJSstring(CAMPAIGNCREATE_27).'<br/><br/>'.fixJSstring(CAMPAIGNCREATE_39)?>', '25em', '0'); " src="./images/i1.gif" alt=""><span style="display: none;" id="recip"></span></td>
	</tr>
	<tr bgcolor="#F1F1F2">
		<td valign="top" style="padding-top:10px; border-top: #CCCCCC 0px solid"><?php echo CAMPAIGNCREATE_9; ?></td>
   		<td valign="top" style="padding-top:10px; border-top: #CCCCCC 0px solid">
			<SELECT class="select" name="prefers" id="prefers">
			<option value="3" selected><?php echo CAMPAIGNCREATE_12; ?></OPTION>
			<option value="1"><?php echo CAMPAIGNCREATE_14; ?></OPTION>
			<option value="2"><?php echo CAMPAIGNCREATE_15; ?></OPTION>
			</SELECT>
		</td>
        <td style="padding-top:10px; border-top: #CCCCCC 0px solid"><img onmouseout="hide_info_bubble('prefs','0')" onmouseover="topInfoBox('prefs', '<?php echo fixJSstring(CAMPAIGNCREATE_14).'-'.fixJSstring(CAMPAIGNCREATE_15);?>', '<?php echo fixJSstring(CAMPAIGNCREATE_43)?>', '25em', '0'); " src="./images/i1.gif" alt=""><span style="display: none;" id="prefs"></span></td>
    </tr>

	<tr bgcolor="#F1F1F2">
		<td valign="top"><?php echo CAMPAIGNCREATE_10; ?></td>
   		<td valign="top">
			<SELECT class="select" name="confirmed" id="confirmed">
			<option value="3" selected><?php echo CAMPAIGNCREATE_12; ?></OPTION>
			<option value="1" <?php if ($groupDoubleOptin){echo ' selected';}?>><?php echo CAMPAIGNCREATE_8; ?></OPTION>
			<option value="2"><?php echo CAMPAIGNCREATE_13; ?></OPTION>
			</SELECT>
		</td>
        <td><img onmouseout="hide_info_bubble('conf','0')" onmouseover="topInfoBox('conf', '<?php echo fixJSstring(CAMPAIGNCREATE_10);?>', '<?php echo fixJSstring(CAMPAIGNCREATE_42)?>', '25em', '0'); " src="./images/i1.gif"  alt=""><span style="display: none;" id="conf"></span></td>
	</tr>
	<tr bgcolor="#F1F1F2">
		<td valign="top"><?php echo CAMPAIGNCREATE_22; ?></td>
		<td valign="top">
			<?php
			$mySQL4="SELECT idSendFilter, sendFilterDesc FROM ".$idGroup."_sendFilters WHERE idGroup=$idGroup order by idSendFilter desc";
			$result4	= $obj->query($mySQL4);?>
			<SELECT class="select" id="idSendFilter" name="idSendFilter">
			<OPTION value="0"><?php echo CAMPAIGNCREATE_4; ?></OPTION>
			<?php  while ($row = $obj->fetch_array($result4)){?>
			<option value="<?php echo $row['idSendFilter'];?>"><?php echo $row['idSendFilter'];?>. <?php echo substr($row['sendFilterDesc'],0,100).'...';?></OPTION>
			<?php } ?>
			</SELECT>
		</td>
        <td></td>
	</tr>

	<!-- GOOGLE ANALYTICS -->
	<tr bgcolor="#FAFAFA">
		<td valign="top" style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 1px solid"><?php echo CAMPAIGNCREATE_51; ?> Google Analytics:</td>
		<td style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 1px solid"><input type="checkbox" id="google_check" name="google_check" value="-1" onClick="show_hide_many(Array('google_1','google_2','google_3','google_4','google_5', 'google_6'),'');">
			&nbsp;<span  style="display:none" id="google_6">(* <?php echo CAMPAIGNCREATE_50; ?>)</span>
   		</td>
		<td style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 1px solid"><img onmouseover="topInfoBox('ga', '<?php echo fixJSstring("Google analytics")?>', '<?php echo fixJSstring(CAMPAIGNCREATE_56)?>', '25em', '1'); "  src="./images/i1.gif"><span style="display:none;" id="ga"></span></td>
  	</tr>
	<tr bgcolor="#FAFAFA" id="google_1" style="display:none">
		<td valign="top" style="padding-left:12px;">
			<?php echo CAMPAIGNCREATE_45; ?> (utm_source):
   		</td>
		<td valign="top">
			<input class="fieldbox11" size="40" type="text" id="utm_source" name="utm_source" value=""> *
		</td><td></td>
	</tr>
	<tr bgcolor="#FAFAFA" id="google_2" style="display:none">
		<td valign="top" style="padding-left:12px;">
			<?php echo CAMPAIGNCREATE_46; ?> (utm_medium):
   		</td>
		<td valign="top">
			<input class="fieldbox11" size="40" type="text" id="utm_medium" name="utm_medium" value=""> *
		</td><td></td>
	</tr>
	<tr bgcolor="#FAFAFA" id="google_3" style="display:none">
		<td valign="top" style="padding-left:12px;">
			<?php echo CAMPAIGNCREATE_47; ?> (utm_campaign):
   		</td>
		<td valign="top">
			<input class="fieldbox11" size="40" type="text" id="utm_campaign" name="utm_campaign" value=""> *
		</td><td></td>
	</tr>
	<tr bgcolor="#FAFAFA" id="google_4" style="display:none">
		<td valign="top" style="padding-left:12px;">
			<?php echo CAMPAIGNCREATE_48; ?> (utm_term):
   		</td>
		<td valign="top">
			<input class="fieldbox11" size="40" type="text" id="utm_term" name="utm_term" value="">
		</td><td></td>
	</tr>
	<tr bgcolor="#FAFAFA" id="google_5" style="display:none">
		<td valign="top" style="padding-left:12px;">
			<?php echo CAMPAIGNCREATE_49; ?> (utm_content):
   		</td>
		<td valign="top">
			<input class="fieldbox11" size="40" type="text" id="utm_content" name="utm_content" value="">
		</td><td></td>
	</tr>
<!-- GOOGLE ANALYTICS ENDS -->

	<tr bgcolor="#FFFFFF">
   		<td width="60" height=40 valign="top" style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 0px solid">&nbsp;</td>
		<td style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 0px solid">
			<input id="countbutton" type="submit" class="submit" onclick="validateMailing('count')" name="count" value="<?php echo CAMPAIGNCREATE_24; ?>">
			&nbsp;<input id="createEntry" type="submit" onclick="validateMailing('createCampaign')" class="submit" name="createCampaign" value="<?php echo CAMPAIGNCREATE_21; ?>">
		</td>
        <td valign="top" style="padding-top:10px; padding-bottom:10px; border-top: #CCCCCC 0px solid">
           <img onmouseout="hide_info_bubble('recip2','0')" onmouseover="topInfoBox('recip2', '<?php echo fixJSstring(CAMPAIGNCREATE_24);?>', '<?php echo fixJSstring(CAMPAIGNCREATE_40).'<br />'.fixJSstring(CAMPAIGNCREATE_57).'<br><b>'.fixJSstring(CAMPAIGNCREATE_21).'</b>&nbsp;'.fixJSstring(CAMPAIGNCREATE_41).'&nbsp;'.fixJSstring(ADMIN_HEADER_81).'> '.fixJSstring(ADMIN_HEADER_47).'> '.fixJSstring(ADMIN_HEADER_49)?>', '25em', '0'); " src="./images/i1.gif" alt="">
			<span style="display: none;" id="recip2"></span>
        </td>
	</tr>
</table>
	<!--tr>
		<td colspan="3" align="center" height=65-->
		<div style="width:95%;text-align:center; margin-right:auto;margin-left:auto;margin-top:10px">
			<div id="messageDiv" style="display:none;"></div>
			<span id="loading" style="display:none;text-align:right;"><img src="./images/waitBig.gif" alt="">&nbsp;<?php echo GENERIC_4; ?></span>
		</div>
		<!--/td>
	</tr-->

</form>

<?php
$obj->free_result($result1);
$obj->free_result($result2);
$obj->free_result($result3);
$obj->free_result($result4);
$obj->closeDb();
include('footer.php');
?>