<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 		= new db_class();
$groupName	= $obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
isset($_GET['idEmail'])?$pidemail = $_GET['idEmail']:$pidemail ='';

if ($pidemail) {
		$mySQL="SELECT email, name, lastName, subCompany, timesMailed, address, city, state, zip, country, subPhone1, subPhone2, subMobile, subPassword, prefersHtml, confirmed, dateSubscribed, dateLastUpdated, emailIsValid, emailIsBanned, customSubField1, customSubField2, customSubField3,  customSubField4,  customSubField5,  customSubField6,  customSubField7,  customSubField8,  customSubField9,  customSubField10, soft_bounces, hard_bounces, optOutReason, dateLastEmailed, ipSubscribed, internalMemo, subBirthDay, subBirthMonth, subBirthYear FROM ".$idGroup."_subscribers WHERE idEmail=$pidemail";
		$result	= $obj->query($mySQL);
		$row = $obj->fetch_array($result);
		if (!$row['email']) {header("Location: message.php?message=".urlencode(EDITSUBSCRIBER_30)."");die;}	//invalid id==>get out
		else {
			$pemail 			= $row['email'];
			$pname 				= $row['name'];
			$plastName			= $row['lastName'];
			$psubCompany		= $row['subCompany'];
			$ptimesMailed   	= $row['timesMailed'];
			$paddress			= $row['address'];
			$pcity				= $row['city'];
			$pstate				= $row['state'];
			$pzip				= $row['zip'];
			$pcountry			= $row['country'];
			$psubPhone1			= $row['subPhone1'];
			$psubPhone2			= $row['subPhone2'];
			$psubMobile			= $row['subMobile'];
			$psubPassword		= $row['subPassword'];
			$pprefersHtml		= $row['prefersHtml'];
			$pconfirmed			= $row['confirmed'];
			$pdateSubscribed 	= $row['dateSubscribed'];
			$pdateLastUpdated 	= $row['dateLastUpdated'];
			$emailIsValid	 	= $row['emailIsValid'];
			$emailIsBanned 		= $row['emailIsBanned'];
            $dcustomSubField1 	= $row['customSubField1'];
			$dcustomSubField2	= $row['customSubField2'];
			$dcustomSubField3	= $row['customSubField3'];
			$dcustomSubField4	= $row['customSubField4'];
			$dcustomSubField5	= $row['customSubField5'];
			$dcustomSubField6	= $row['customSubField6'];
			$dcustomSubField7	= $row['customSubField7'];
			$dcustomSubField8	= $row['customSubField8'];
			$dcustomSubField9	= $row['customSubField9'];
			$dcustomSubField10	= $row['customSubField10'];
			$psoft_bounces 		= $row['soft_bounces'];
			$phard_bounces 		= $row['hard_bounces'];
			$poptOutReason 		= $row['optOutReason'];
			$pdateLastEmailed	= $row['dateLastEmailed'];
			$pipSubscribed  	= $row['ipSubscribed'];
			$pinternalMemo		= $row['internalMemo'];
			$psubBirthDay		= $row['subBirthDay'];
			$psubBirthMonth		= $row['subBirthMonth'];
			$psubBirthYear		= $row['subBirthYear'];
			} // the sub id is valid
}	//we have an sub id
else {		//new sub
			$pemail 			= "";
			$pname 				= "";
			$plastName			= "";
			$psubCompany		= "";
			$paddress		   	= "";
			$pcity			  	= "";
			$pstate				= "";
			$pcountry			= "";
			$pzip				= "";
			$psubPhone1			= "";
			$psubPhone2			= "";
			$psubMobile			= "";
			$psubPassword		= "";
			$pprefersHtml		= "";
			$pconfirmed			= "";
			$pinternalMemo		= "";
			$psubBirthDay		= "";
			$psubBirthMonth		= "";
			$psubBirthYear		= "";
			$emailIsValid	 	= "-1";
			$emailIsBanned 		= "";
	        $dcustomSubField1 	= "";
			$dcustomSubField2	= "";
			$dcustomSubField3	= "";
			$dcustomSubField4	= "";
			$dcustomSubField5	= "";
			$dcustomSubField6	= "";
			$dcustomSubField7	= "";
			$dcustomSubField8  	= "";
			$dcustomSubField9	= "";
			$dcustomSubField10	= "";
}
include('header.php');
showMessageBox();
?>
<script type="text/javascript" language="javascript">
function showStop() {
	$("#stopImg").toggle();
}
function addEditSub(action) {
	if (action=="update" || action=="insert")	{
		if (isGoodEmail("email")==false) {
			openAlertBox('<?php echo fixJSstring(UPDATESUBSCRIBER_6)?>','');
			$("#email").focus();
			return false;
		}
		var url="updateSubscriber.php";
		var params =  $('#updatesub').serialize()+"&action="+action;
		//alert(params);return false;
		$.post(url, params, function(data){showResponse(data);})
		$("#buttons").hide();
		$("#wait").show();
	}
	if (action=="delete") {
		openConfirmBox('delete.php?idEmail=<?php echo $pidemail?>','<?php echo fixJSstring(CONFIRM_7)?><br><?php echo fixJSstring(GENERIC_2)?>');
		return false;
	}
	function showResponse(response) {//alert(req.responseText);
		var response = response.split("#");
		var part_one = response[0];
		var part_two = response[1];
		switch (part_one) {
			case "updated":
				openmessageBox("<?php echo fixJSstring(UPDATESUBSCRIBER_1);?>");
		  		break
		  	case "exists":
				openConfirmBox("editSubscriber.php?idEmail="+part_two, "<?php echo fixJSstring(UPDATESUBSCRIBER_2)."<br>".fixJSstring(UPDATESUBSCRIBER_7);?>");
			 	 break
		  	case "exists2":
				openConfirmBox("editSubscriber.php?idEmail="+part_two, "<?php echo fixJSstring(UPDATESUBSCRIBER_3)."<br>".fixJSstring(UPDATESUBSCRIBER_7);?>");
			 	 break
		  	case "added":
				document.location.href="editSubscriber.php?idEmail="+part_two+"&message=<?php echo fixJSstring(UPDATESUBSCRIBER_4)?>";
 			 	 break
			case "sessionexpired":
				openAlertBox("<?php echo fixJSstring(GENERIC_3)?>","index.php");
		  		break
			case "demo":
				openAlertBox("<?php echo fixJSstring(DEMOMODE_1)?>","");
		  		break
		  	default:
		  		openAlertBox("<?php echo fixJSstring(GENERIC_8)?>","");
		}
		$("#buttons").show();
		$("#wait").hide();
	}
	function showException() {
		openAlertBox("<?php echo fixJSstring(GENERIC_8)?>","");
		$("#buttons").show();
		$("#wait").hide();
	}
}

</script>
<table width="960px" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td width="450"><span class="title"><?php if ($pidemail) {echo EDITSUBSCRIBER_1;} else {echo EDITSUBSCRIBER_23;} ?></span> <font size="2"><?php echo $plastName. '&nbsp;'.$pname.'&nbsp;&nbsp;&nbsp;'.$pemail;?></font></td>
		<td align="left" width="415"><?php //if($emailIsBanned==-1){echo '<img src="./images/stop.png" alt="">&nbsp;'.SUPLIST_6;}?></td>
		<td align="right" width="35"><img src="images/editsubscriber.png" width="55" height="49"></td>
	</tr>
	<tr><td colspan="3"><hr></td></tr>
</table>

	<form name="updatesub" id="updatesub" autocomplete="off">
		<input type="hidden" id="idEmail" name="idEmail" value="<?php echo $pidemail?>">
			<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<?php if ($pidemail) { ?> <!--START PROPERTIES THAT CANNOT BE CHANGED -->
				<tr>
					<td><?php echo EDITSUBSCRIBER_27; ?></td><td><?php echo addOffset($pdateSubscribed, $pTimeOffsetFromServer, $groupDateTimeFormat)?></td>
					<td><?php echo EDITSUBSCRIBER_18; ?></td><td><?php echo subscriberuniqueopenrate($pidemail, $idGroup, $ptimesMailed);?></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_5; ?></td><td><?php echo $pipSubscribed?></td>
					<td><?php echo EDITSUBSCRIBER_32; ?></td><td><a href="statsPerSubscriber.php?idEmail=<?php echo $pidemail?>"><?php echo EDITSUBSCRIBER_33; ?></a></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_36; ?></td><td><?php echo addOffset($pdateLastEmailed, $pTimeOffsetFromServer, $groupDateTimeFormat)?></td>
					<td><?php echo EDITSUBSCRIBER_35; ?></td><td><?php echo $phard_bounces?></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_31; ?></td><td><?php echo addOffset($pdateLastUpdated, $pTimeOffsetFromServer, $groupDateTimeFormat)?></td>
					<td><?php echo EDITSUBSCRIBER_34; ?></td><td><?php echo $psoft_bounces?></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_10; ?></td><td><?php echo $ptimesMailed?></td>
					<td valign=top><?php echo EDITSUBSCRIBER_2; ?></td><td valign=top><?php echo wordwrap($poptOutReason,50,"<br>\r\n", true);?>&nbsp;</td>
				</tr>
				<tr><td colspan=4><hr></td></tr>
 	<?php } ?><!--END PROPERTIES THAT CANNOT BE CHANGED-->

				<tr>
					<td><?php echo EDITSUBSCRIBER_7;?></td><td><input class="fieldbox11" size="30"  type="text" id="email" name="email" value="<?php echo $pemail?>"></td>
					<td><?php echo EDITSUBSCRIBER_24; ?>&nbsp;<input type="checkbox" name="confirmed" value="-1" <?php if ($pconfirmed==-1) {echo " checked";}?>></td>
					<td><?php echo EDITSUBSCRIBER_9; ?>&nbsp;<input type="checkbox" name="prefersHtml" value="-1" <?php if ($pprefersHtml==-1) {echo "checked";}?>></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_19; ?></td><td><input class="fieldbox11" size="30" type="text" name="name" value="<?php echo strForInput($pname)?>"></td>
					<td><?php echo EDITSUBSCRIBER_8; ?></td><td><input class="fieldbox11" size="10"  type="password" name="subPassword" value="<?php echo $psubPassword?>"></td>
				</tr>

				<tr>
					<td><?php echo EDITSUBSCRIBER_37; ?></td><td><input class="fieldbox11" size="30" type="text" name="lastName" value="<?php echo strForInput($plastName)?>"></td>
					<td><?php echo EDITSUBSCRIBER_39; ?></td><td><input class="fieldbox11" size="30"  type="text" name="subPhone1" value="<?php echo $psubPhone1?>"></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_38; ?></td><td><input class="fieldbox11" size="30"  type="text" name="subCompany" value="<?php echo strForInput($psubCompany)?>"></td>
					<td><?php echo EDITSUBSCRIBER_40; ?></td><td><input class="fieldbox11" size="30"  type="text" name="subPhone2" value="<?php echo $psubPhone2?>"></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_11; ?></td><td><input class="fieldbox11" size="30"  type="text" name="address" value="<?php echo strForInput($paddress)?>"></td>
					<td><?php echo EDITSUBSCRIBER_41; ?></td><td><input class="fieldbox11" size="30"  type="text" name="subMobile" value="<?php echo $psubMobile?>"></td>
 		   		</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_13; ?></td><td><input class="fieldbox11" size="10"  type="text" name="zip" value="<?php echo strForInput($pzip)?>"></td>
					<td><?php echo EDITSUBSCRIBER_43; ?></td><td><input class="fieldbox11" size="2"  type="text" name="subBirthDay" value="<?php echo $psubBirthDay?>"></td>
				</tr>
				<tr>
					<td><?php echo EDITSUBSCRIBER_12; ?></td><td><input class="fieldbox11" size="30"  type="text" name="city" value="<?php echo strForInput($pcity)?>"></td>
					<td><?php echo EDITSUBSCRIBER_44; ?></td><td><input class="fieldbox11" size="2"  type="text" name="subBirthMonth" value="<?php echo $psubBirthMonth?>"></td>
				</tr>
				<tr>
                    <td><?php echo EDITSUBSCRIBER_14; ?></td><td><?php
						$mySQLs="SELECT * from ".$idGroup."_states where idGroup=$idGroup order by stateName asc ";
						$states	= $obj->query($mySQLs);?>
						<select name="stateCode" class="select">
							<option value=""><?php echo EDITSUBSCRIBER_22;?></option>
							<?php while ($row = $obj->fetch_array($states)){ ?>
							<option value="<?php echo $row['stateCode'].'"'; if ($row['stateCode']==strtoupper($pstate)){echo ' selected';} echo '>' .$row['stateName'];?></option>
						<?php } ?></select></td>
					<td><?php echo EDITSUBSCRIBER_45; ?></td><td><input class="fieldbox11" size="5"  type="text" name="subBirthYear" value="<?php echo $psubBirthYear?>"></td>
				</tr>
				<tr>
                   	<td valign=top><?php echo EDITSUBSCRIBER_15; ?></td>
					<td valign=top colspan=2><?php
						$mySQLs="SELECT * from ".$idGroup."_countries where idGroup=$idGroup order by countryName asc";
						$countries	= $obj->query($mySQLs);?>
						<select name="countryCode" class="select">
							<option value=""><?php echo EDITSUBSCRIBER_22;?></option>
							<?php while ($row = $obj->fetch_array($countries)){ ?>
							<option value="<?php echo $row['countryCode'].'"'; if ($row['countryCode']==strtoupper($pcountry)){echo ' selected';} echo '>' .$row['countryName'];?></option>
						<?php } ?></select><!--<input class="fieldbox11" size="30"  type="text" name="country" value="<?php //echo $pcountry?>">-->
					</td>
					<td rowspan=2 valign=top><?php echo EDITSUBSCRIBER_42; ?><br><TEXTAREA NAME="internalMemo" COLS="30" ROWS=5 class="textarea"><?php echo $pinternalMemo?></TEXTAREA></td>
				</tr>
				<tr>
					<td colspan="3">
						<img id="stopImg" alt="" src="./images/stop.png" <?php if ($emailIsBanned!=-1) {echo ' style="display:none"';}?>>&nbsp;<?php echo SUPLIST_6;?>&nbsp;&nbsp;<input onclick="showStop();" type="checkbox" name="banned" value="-1" <?php if ($emailIsBanned==-1) {echo " checked";}?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo EDITSUBSCRIBER_28;?>:&nbsp;&nbsp;<input type="checkbox" name="invalid" value="-1" <?php if ($emailIsValid==0) {echo " checked";}?>>
					</td>
				</tr>
				<tr>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField2", $idGroup)!="") { ?><?php echo $obj->getSetting("groupCustomSubField2", $idGroup);?>:<?php } ?></td>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField2", $idGroup)!="") { ?><input class="fieldbox11" size="30"  type="text" name="pcustomsubfield2" value="<?php echo strForInput($dcustomSubField2)?>"><?php } ?></td>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField4", $idGroup)!="") { ?><?php echo $obj->getSetting("groupCustomSubField4", $idGroup);?>:<?php } ?></td>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField4", $idGroup)!="") { ?><input class="fieldbox11" size="30"  type="text" name="pcustomsubfield4" value="<?php echo strForInput($dcustomSubField4)?>"><?php } ?></td>
			</tr>
				<tr>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField3", $idGroup)!="") { ?><?php echo $obj->getSetting("groupCustomSubField3", $idGroup);?>:<?php } ?></td>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField3", $idGroup)!="") { ?><input class="fieldbox11" size="30"  type="text" name="pcustomsubfield3" value="<?php echo strForInput($dcustomSubField3)?>"><?php } ?></td>
					<td valign=bottom><?php if ($obj->getSetting("groupCustomSubField1", $idGroup)!="") { ?><?php echo $obj->getSetting("groupCustomSubField1", $idGroup);?>:<?php } ?></td>
					<td valign=bottom><?php if ($obj->getSetting("groupCustomSubField1", $idGroup)!="") { ?><input class="fieldbox11" size="30"  type="text" name="pcustomsubfield1" value="<?php echo strForInput($dcustomSubField1)?>"><?php } ?></td>
				</tr>

			<tr>
					<td valign="top" colspan="2">
						<a class="cross" href="#" onclick="show_hide_div('listsCell','cross1');return false;"><strong><span id="cross1">[+]</span>&nbsp;<?php echo EDITSUBSCRIBER_4; ?></strong></a>
						<div  id="listsCell" style="display:none;"><br>
						  <?php
							$mySQL3="SELECT idList, listName FROM ".$idGroup."_lists where idGroup=$idGroup ORDER BY idList desc";
							$result3	= $obj->query($mySQL3);
						  while ($row = $obj->fetch_array($result3)){?>
						  <input id="idList" name="idList[]" type="checkbox" value="<?php echo $row['idList'];?>"
							<?php if ($pidemail) {
							$mySQL4="SELECT * from ".$idGroup."_listRecipients WHERE idList=".$row['idList']." AND idEmail=".$pidemail;
							$checked	= $obj->query($mySQL4);
							$isIn = $obj->fetch_array($checked);
							if ($isIn) {echo ' checked';}}?>
						  ><?php echo $row['idList'].'. '.$row['listName'].'<br>';?>
						  <?php } ?>
						</div>
					</td>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField5", $idGroup)!="") { ?><?php echo $obj->getSetting("groupCustomSubField5", $idGroup);?>:<?php } ?></td>
					<td valign=top><?php if ($obj->getSetting("groupCustomSubField5", $idGroup)!="") { ?><input class="fieldbox11" size="30"  type="text" name="pcustomsubfield5" value="<?php echo strForInput($dcustomSubField5)?>"><?php } ?></td>
				</tr>

				<tr>
					<td colspan="4" align="center"><div  id="buttons">
						<?php if ($pidemail) { ?>
							<input class="submit" type="button" id="update" name="update" value="<?php echo EDITSUBSCRIBER_16; ?>" onclick="addEditSub('update');return false;">
							&nbsp;&nbsp;
							<input class="submit" type="button" id="delete" name="delete" value="<?php echo EDITSUBSCRIBER_17; ?>" onclick="addEditSub('delete');return false;">
						<?php } else { ?>
							<input class="submit" type="button" id="insert" name="insert" value="<?php echo EDITSUBSCRIBER_21; ?>" onclick="addEditSub('insert');return false;">
						<?php } ?></div><div id="wait" style="display:none;"><img src="./images/waitSmall.gif"></div>
					</td>
				</tr>

</table>
</form>
<div align="center" style="margin-top:20px">
<?php
if ($pidemail) {
    $previousSQL="SELECT idEmail FROM ".$idGroup."_subscribers WHERE idEmail=(Select Max(idEmail) from ".$idGroup."_subscribers  where idEmail<$pidemail)";
    $previous	= $obj->query($previousSQL);
    $previousRow = $obj->fetch_row($previous);
    if ($previousRow['0']) {echo '<a href="editSubscriber.php?idEmail='.$previousRow['0'].'"><img src="./images/l_arrow.png" border="0" title="Previous subscriber"></a>';}

    $nextSQL="SELECT idEmail FROM ".$idGroup."_subscribers WHERE idEmail=(Select Min(idEmail) from ".$idGroup."_subscribers  where idEmail>$pidemail)";
    $next	= $obj->query($nextSQL);
    $nextRow = $obj->fetch_row($next);
    if ($nextRow['0']) {echo '&nbsp;&nbsp;<a href="editSubscriber.php?idEmail='.$nextRow['0'].'"><img src="./images/r_arrow.png" border="0" title="Next subscriber"></a>';}

    $obj->free_result($result);
}
?>
</div>

<?php
 //when it cannot find id
include("footer.php");

$obj->closeDb();

?>