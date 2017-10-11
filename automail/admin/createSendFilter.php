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
include('header.php');
showMessageBox();

$vgroupCustomSubField1 = $obj->getSetting("groupCustomSubField1", $idGroup);
$vgroupCustomSubField2 = $obj->getSetting("groupCustomSubField2", $idGroup);
$vgroupCustomSubField3 = $obj->getSetting("groupCustomSubField3", $idGroup);
$vgroupCustomSubField4 = $obj->getSetting("groupCustomSubField4", $idGroup);
$vgroupCustomSubField5 = $obj->getSetting("groupCustomSubField5", $idGroup);
?>
<script type="text/javascript" language="javascript">
function processForm() {
    $postData = $("#newSendFilter").serialize();
	params = $postData+'&processButton=x';
	$('#results').html="";
	$.ajax({
		type: "POST",
		url:"createSendFilterExec.php",
		data: params
		}).done(function(data,status) {
			showResponse(data, status);
			})
	  		.fail(function(data, status) {showException(status); });


    $("#indicator").show();
	$("#processButton").prop("disabled",true);
	return false;
	function showResponse(data, status) {
		if (data=="sessionexpired") {alert('<?php echo fixJSstring(GENERIC_3);?>');document.location.href="index.php";}
    	$("#indicator").hide();
		$("#results").html(data);
		$("#processButton").prop("disabled",false);
	}
    function showException(status) 	{
		alert(status);
    	$("#indicator").hide();
   		$("#processButton").prop("disabled",false);
	}
}	//main function ends
</script>
<table border="0" width="960px">
	<tr>
		<td valign=top>
			<span class="title"><?php echo CREATESENDFILTER_1; ?></span>
			<br><?php echo CREATESENDFILTER_44; ?>
		</td>
		<td align=right>
			<img src="./images/createfilter.png" width="60" height="47">
		</td>
	</tr>
</table>

<form name="newSendFilter" id="newSendFilter" method="post" action="createSendFilterExec.php" autocomplete="off">
<table width="900" cellpadding="2" cellspacing="0" border="0">
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_1; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td><INPUT class="fieldbox11" TYPE="Text" Name="emailcontains" VALUE=""></td>
	</tr>

	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_2; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="namecontains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_16; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="lastnamecontains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_17; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="subcompanycontains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_3; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="addresscontains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_4; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="citycontains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_5; ?></font><?php echo CREATESENDFILTER_12; ?></td>
		<td valign="top">
			<?php
			$mySQLs="SELECT * from ".$idGroup."_states where idGroup=$idGroup order by stateName asc";
			$result2 = $obj->query($mySQLs);?>
			<select name="stateCodeis" class="select">
			<option value=""><?php echo CREATESENDFILTER_48; ?></option>
			<?php while ($row = $obj->fetch_array($result2)){ ?>
			<option value="<?php echo $row['stateCode']?>"><?php echo $row['stateName']?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_6; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="zipcontains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_7; ?></font><?php echo CREATESENDFILTER_12; ?></td>
		<td valign="top">
			<?php
			$mySQLc="SELECT * from ".$idGroup."_countries where idGroup=$idGroup order by countryName asc";
			$result = $obj->query($mySQLc);?>
			<select name="countryCodeis" class="select">
			<option value=""><?php echo CREATESENDFILTER_48; ?></option>
			<?php while ($row = $obj->fetch_array($result)){ ?>
			<option value="<?php echo $row['countryCode']?>"><?php echo $row['countryName'] ?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_18; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="subphone1contains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_19; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="subphone2contains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_20; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="submobilecontains" VALUE=""></td>
	</tr>

	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_8; ?></font>:</td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="dateSubscribed" id="dateSubscribed" VALUE="">&nbsp;<a href="helpDates.php" onclick="window.open('helpDates.php?datefield=dateSubscribed','window','width=600, height=250,resizable=yes,scrollbars=yes');return false"><img width="16" height="14" src="./images/helpSmallWhite.gif" border="0" title="<?php echo CREATESENDFILTER_47; ?>"></a></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_9; ?></font>:</td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="dateLastUpdated" id="dateLastUpdated" VALUE="">&nbsp;<a href="helpDates.php" onclick="window.open('helpDates.php?datefield=dateLastUpdated','window','width=600, height=250,resizable=yes,scrollbars=yes');return false"><img width="16" height="14" src="./images/helpSmallWhite.gif" border="0" title="<?php echo CREATESENDFILTER_47; ?>"></a></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_10; ?></font>:</td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="dateLastEmailed" id="dateLastEmailed" VALUE="">&nbsp;<a href="helpDates.php" onclick="window.open('helpDates.php?datefield=dateLastEmailed','window','width=600, height=250,resizable=yes,scrollbars=yes');return false"><img width="16" height="14" src="./images/helpSmallWhite.gif" border="0" title="<?php echo CREATESENDFILTER_47; ?>"></a></td>
	</tr>
	<tr>
		<td valign="top"><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_11; ?></font></td>
		<td>
			<select name="timesMailedOPR" class="select">
				<option value=""><?php echo CREATESENDFILTER_48; ?></option>
				<option value="<"><</option>
				<option value="<="><=</option>
				<option value="=">=</option>
				<option value=">=">>=</option>
				<option value=">">></option>
			</select>
		</td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" name="timesMailed" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top"><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_13; ?></font></td>
		<td>
			<select name="soft_bouncesOPR" class="select">
				<option value=""><?php echo CREATESENDFILTER_48; ?></option>
				<option value="<" ><</option>
				<option value="<="><=</option>
				<option value="=" >=</option>
				<option value=">=" >>=</option>
				<option value=">" >></option>
			</select>
		</td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="soft_bounces" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top"><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_14; ?></font></td>
		<td>
			<select name="hard_bouncesOPR" class="select">
				<option value=""><?php echo CREATESENDFILTER_48; ?></option>
				<option value="<" ><</option>
				<option value="<=" ><=</option>
				<option value="=" >=</option>
				<option value=">=" >>=</option>
				<option value=">" >></option>
			</select>
		</td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="hard_bounces" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_15; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top"><INPUT class="fieldbox11" TYPE="Text" Name="ipcontains" VALUE=""></td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_21; ?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="internalmemocontains" VALUE="">
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_22; ?></font><?php echo CREATESENDFILTER_12; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="birthdayis" VALUE="">
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_23; ?></font><?php echo CREATESENDFILTER_12; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="birthmonthis" VALUE="">
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo DBFIELD_24; ?></font><?php echo CREATESENDFILTER_12; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="birthyearis" VALUE="">
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_2; ?>&nbsp;<font class="dbfield"><?php echo HOME_16; ?></font>:<?php //echo CREATESENDFILTER_12; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="checkbox" name="emailisvalid" VALUE="-1">
		</td>
	</tr>
	<tr>
		<td valign="top" colspan=2><?php echo ADMIN_HEADER_75; ?>&nbsp;<font class="dbfield">(<?php echo SUPLIST_12;?>)</font>:</td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="checkbox" name="emailisbanned" VALUE="-1">
		</td>
	</tr>


<!-- CUSTOM SUBSCRIBER FIELDS -->
	<?php if (!empty($vgroupCustomSubField1)) { ?>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo $vgroupCustomSubField1?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="pcustomsubfield1" VALUE="">
		</td>
	</tr>
    <?php }?>
   <?php if (!empty($vgroupCustomSubField2)) { ?>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo $vgroupCustomSubField2?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="pcustomsubfield2" VALUE="">
		</td>
	</tr>
	<?php }?>
	<?php if (!empty($vgroupCustomSubField3)) { ?>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo $vgroupCustomSubField3?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="pcustomsubfield3" VALUE="">
		</td>
	</tr>
	<?php }?>
	<?php if (!empty($vgroupCustomSubField4)) { ?>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo $vgroupCustomSubField4?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="pcustomsubfield4" VALUE="">
		</td>
	</tr>
	<?php }?>
	<?php if (!empty($vgroupCustomSubField5)) { ?>
	<tr>
		<td valign="top" colspan=2><?php echo CREATESENDFILTER_3; ?><font class="dbfield"><?php echo $vgroupCustomSubField5?></font><?php echo CREATESENDFILTER_5; ?></td>
		<td valign="top">
			<INPUT class="fieldbox11" TYPE="Text" name="pcustomsubfield5" VALUE="">
		</td>
	</tr>
	<?php }?>

<!-- end custom subscriber fields -->


	<tr bgcolor="#ededed">
		<td valign="top" colspan=3>
			<?php echo CREATESENDFILTER_24; ?>
		</td>
	</tr>
   <tr bgcolor="#ededed">
		<td valign="top" colspan=3>
			<?php echo CREATESENDFILTER_25; ?>&nbsp;
			<?php
			   	$SQL="SELECT idList, listName, isPublic FROM ".$idGroup."_lists WHERE idGroup=$idGroup order by idList desc";
				$result3	= $obj->query($SQL);?>
				<SELECT class="select" id="idList" name="idList">
 				 <OPTION value="-1"><?php echo CREATESENDFILTER_45; ?></OPTION>
				<OPTION value="-2" ><?php echo CREATESENDFILTER_39; ?>
					<?php while ($row = $obj->fetch_array($result3)){?>
					<option value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php echo $row['listName'];?></option>
					<?php }?>
				</SELECT>
		</td>
	</tr>
	<tr bgcolor="#ededed">
		<td valign="top" colspan=2>
   			<?php echo CREATESENDFILTER_28; ?>
   		</td>
   		<td valign="top">
			<SELECT class="select" name="pconfirmed" id="pconfirmed">
			<option value="3"><?php echo CREATESENDFILTER_27; ?></OPTION>
			<option value="1"><?php echo CREATESENDFILTER_29; ?></OPTION>
			<option value="2"><?php echo CREATESENDFILTER_30; ?></OPTION>
			</SELECT>
		</td>
	</tr>

	<tr bgcolor="#ededed">
		<td valign="top" colspan=2>
   			<?php echo CREATESENDFILTER_26; ?>
   		</td>
   		<td valign="top">
			<SELECT class="select" name="prefers" id="prefers">
			<option value="3"><?php echo CREATESENDFILTER_27; ?></OPTION>
			<option value="1">Html</OPTION>
			<option value="2">Text</OPTION>
			</SELECT>
		</td>
	</tr>
	<tr>
		<td colspan="3" height="30" valign="bottom" align=center>
			<input class="submit" type="Submit" onclick="return processForm();return false;" id="processButton" name="processButton" value="<?php echo CREATESENDFILTER_31; ?>">
			&nbsp;&nbsp;<input class="submit" type="reset" name="reset" value="<?php echo CREATESENDFILTER_32; ?>">
		</td>
	</tr>
</table>
</form>

<div id=results></div>
<div id="indicator" style="display:none" align=center><img src="./images/waitBig.gif" border=0 title="<?php echo GENERIC_18; ?>"><br><?php echo GENERIC_18; ?></div>

<?php
$obj->closeDb();
include('footer.php');
?>