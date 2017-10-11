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
<style type="text/css" media="screen,projection">
	@import url(./datepicker/default.css);
</style>
<SCRIPT language="Javascript" type="text/javascript">
<!--
$(function() {
		$( "#activationDate" ).datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "button",
			buttonImage: "./datepicker/calendar.gif",
			buttonImageOnly: true
		});
	});
	
function formvalidation() {
    if ($("#activationDate").val()=="" || $("#activationTimeH").val()=="hh" || $("#activationTimeM").val()=="mm") {
        openAlertBox('<?php echo fixJSstring(SCHEDULERTASKS_55);?>','');
        return false;
    }
    if ($("#numberOfMessagesToSend").val()!=0 && ($("#RepeatEveryPeriodValue").val()=="" || $("#RepeatEveryPeriod").val()=="0") ) {
        openAlertBox('<?php echo fixJSstring(SCHEDULERTASKS_29);?>','');
        return false;
    }
    if ($('#recurringEvent').is(':checked') && ($("#reactivatePeriodValue").blank() || $("#reactivatePeriod").val()==0)) 	{
      openAlertBox('<?php echo fixJSstring(SCHEDULERTASKS_53);?>','');
      return false;
   }
}
//-->
</SCRIPT>
<?php
(isset($_GET['idTask']))?$fidTask = $_GET['idTask']:$fidTask="";
$mySQL="SELECT idTask, idCampaign, idAdmin, idGroup, dateTaskCreated, activationDateTime, numberOfMessagesToSend, repeatEveryXseconds, taskCompleted,
dateTaskCompleted, lastExecutionFromScheduler, repeatDetailsMemo, taskRecurring, reactivateAfterXSeconds, reactivateDetailsMemo, plog, timesExecuted
FROM ".$idGroup."_tasks WHERE idGroup=$idGroup AND idTask=$fidTask";
$result	= $obj->query($mySQL);
$row = $obj->fetch_array($result);

$idAdmin				= $row["idAdmin"];
$idCampaign				= $row["idCampaign"];
$idGroup				= $row["idGroup"];
$dateTaskCreated		= $row["dateTaskCreated"];
$dateTaskCreated	    = addOffset($dateTaskCreated, $pTimeOffsetFromServer, $groupDateTimeFormat);
$activationDateTime		= $row["activationDateTime"];
$activationDateTime	    = addOffset($activationDateTime, $pTimeOffsetFromServer, $groupDateTimeFormat);
$pOldActivationDateTime  = addOffset($row["activationDateTime"], $pTimeOffsetFromServer, "Y-m-d H:i:s");

$numberOfMessagesToSend	= $row["numberOfMessagesToSend"];
$repeatEveryXseconds	= $row["repeatEveryXseconds"];
$taskCompleted			= $row["taskCompleted"];
$dateTaskCompleted		= $row["dateTaskCompleted"];
$dateTaskCompleted	    = addOffset($dateTaskCompleted, $pTimeOffsetFromServer, $groupDateTimeFormat);
$lastExecutionFromScheduler		= $row["lastExecutionFromScheduler"];
$lastExecutionFromScheduler	    = addOffset($lastExecutionFromScheduler, $pTimeOffsetFromServer, $groupDateTimeFormat);
$repeatDetailsMemo		= $row["repeatDetailsMemo"];
$taskRecurring			= $row["taskRecurring"];
$reactivateAfterXSeconds= $row["reactivateAfterXSeconds"];
$reactivateDetailsMemo	= $row["reactivateDetailsMemo"];

$mySQLc="SELECT campaignName from ".$idGroup."_campaigns where idCampaign=".$idCampaign;
$rc	= $obj->query($mySQLc);
$row2 	= $obj->fetch_array($rc);
$campaignName	= $row2["campaignName"];
?>

<table border="0" cellpadding=0 width="960px">
	<tr>
		<td valign=top>
   			<span class="title"><?php echo SCHEDULERTASKS_44?></span>
   			<br><a href="_schedulerTasks.php"><?php echo SCHEDULERTASKS_25?></a>
		</td>
		<td align=right>
			<img src="./images/edittask.png" width="65" height="51" alt="">
   		</td>
	</tr>
</table>

<form name="editTaskForm" method="post" action="_schedulerEditTaskExec.php" onsubmit="return formvalidation();">
<input type="hidden" name="idTask" value="<?php echo $fidTask?>">
<input type="hidden" name="idCampaign" value="<?php echo $idCampaign?>">
<table border="1" width="700" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #6A5ACD 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;BORDER-COLLAPSE: collapse;" cellpadding="4" cellspacing="0">
	<TBODY>
		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_51?></span></td>
			<td width=150  class="settingsRow">&nbsp;<span style="color:#ffffff"><?php echo SCHEDULERTASKS_52?></span></td>
			<td  class="settingsRow">&nbsp;<span style="color:#ffffff"><?php echo SCHEDULERTASKS_56?></span></td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_3?></span></td>
			<td  colspan="2">&nbsp;<?php echo $fidTask?></td>
		</tr>

		<tr>
			<td width=200 class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_4?></span></td>
			<td colspan="2">&nbsp;<a title="<?php echo SCHEDULERTASKS_20?>" href="campaigns.php?idCampaign=<?php echo $idCampaign?>"><?php echo $idCampaign; if ($campaignName) {echo '. '.$campaignName;}?></a></td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_19?></span></td>
			<td colspan="2">&nbsp;<?php echo $idAdmin.'. '.getadminname($idAdmin, $idGroup);?></td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_5?></span></td>
			<td colspan="2">&nbsp;<?php echo $dateTaskCreated?></td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_6?></span></td>
			<td>&nbsp;<?php echo $activationDateTime?><input type="hidden" name="pOldActivationDateTime" value="<?php echo $pOldActivationDateTime?>"></td>
			<?php
            $activationDateTime1= date("Y-m-d" , strtotime($pOldActivationDateTime));
            $activationTimeH	= date("H"  , strtotime($pOldActivationDateTime));
            $activationTimeM    = date("i"  , strtotime($pOldActivationDateTime));
            ?>
            <td>
				&nbsp;<input class="fieldbox11" style="margin-right:7px" size="20" type="text"  id="activationDate" name="activationDate" value="<?php echo $activationDateTime1?>">
				
				<select class="select" id="activationTimeH" name="activationTimeH">
					<option value="hh">hh</option>
					<option value="00" <?php if ($activationTimeH=="00") {echo " selected";}?>>00</option>
					<option value="01" <?php if ($activationTimeH=="01") {echo " selected";}?>>01</option>
					<option value="02" <?php if ($activationTimeH=="02") {echo " selected";}?>>02</option>
					<option value="03" <?php if ($activationTimeH=="03") {echo " selected";}?>>03</option>
					<option value="04" <?php if ($activationTimeH=="04") {echo " selected";}?>>04</option>
					<option value="05" <?php if ($activationTimeH=="05") {echo " selected";}?>>05</option>
					<option value="06" <?php if ($activationTimeH=="06") {echo " selected";}?>>06</option>
					<option value="07" <?php if ($activationTimeH=="07") {echo " selected";}?>>07</option>
					<option value="08" <?php if ($activationTimeH=="08") {echo " selected";}?>>08</option>
					<option value="09" <?php if ($activationTimeH=="09") {echo " selected";}?>>09</option>
					<option value="10" <?php if ($activationTimeH=="10") {echo " selected";}?>>10</option>
					<option value="11" <?php if ($activationTimeH=="11") {echo " selected";}?>>11</option>
					<option value="12" <?php if ($activationTimeH=="12") {echo " selected";}?>>12</option>
					<option value="13" <?php if ($activationTimeH=="13") {echo " selected";}?>>13</option>
					<option value="14" <?php if ($activationTimeH=="14") {echo " selected";}?>>14</option>
					<option value="15" <?php if ($activationTimeH=="15") {echo " selected";}?>>15</option>
					<option value="16" <?php if ($activationTimeH=="16") {echo " selected";}?>>16</option>
					<option value="17" <?php if ($activationTimeH=="17") {echo " selected";}?>>17</option>
					<option value="18" <?php if ($activationTimeH=="18") {echo " selected";}?>>18</option>
					<option value="19" <?php if ($activationTimeH=="19") {echo " selected";}?>>19</option>
					<option value="20" <?php if ($activationTimeH=="20") {echo " selected";}?>>20</option>
					<option value="21" <?php if ($activationTimeH=="21") {echo " selected";}?>>21</option>
					<option value="22" <?php if ($activationTimeH=="22") {echo " selected";}?>>22</option>
					<option value="23" <?php if ($activationTimeH=="23") {echo " selected";}?>>23</option>
				</select>&nbsp;:
				<select class="select" id="activationTimeM" name="activationTimeM">
					<option value="mm">mm</option>
					<option value="00" <?php if ($activationTimeM=="00") {echo " selected";}?>>00</option>
					<option value="05" <?php if ($activationTimeM=="05") {echo " selected";}?>>05</option>
					<option value="10" <?php if ($activationTimeM=="10") {echo " selected";}?>>10</option>
					<option value="15" <?php if ($activationTimeM=="15") {echo " selected";}?>>15</option>
					<option value="20" <?php if ($activationTimeM=="20") {echo " selected";}?>>20</option>
					<option value="25" <?php if ($activationTimeM=="25") {echo " selected";}?>>25</option>
					<option value="30" <?php if ($activationTimeM=="30") {echo " selected";}?>>30</option>
					<option value="35" <?php if ($activationTimeM=="35") {echo " selected";}?>>35</option>
					<option value="40" <?php if ($activationTimeM=="40") {echo " selected";}?>>40</option>
					<option value="45" <?php if ($activationTimeM=="45") {echo " selected";}?>>45</option>
					<option value="50" <?php if ($activationTimeM=="50") {echo " selected";}?>>50</option>
					<option value="55" <?php if ($activationTimeM=="55") {echo " selected";}?>>55</option>
				</select>
			</td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_11?></span></td>
			<td>&nbsp;<?php echo $lastExecutionFromScheduler?></td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_10?></span></td>
			<td>&nbsp;<?php if ($taskCompleted!=-1) {echo "";} else {echo $dateTaskCompleted;}?></td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_7?></span></td>
			<td>&nbsp;<?php echo $numberOfMessagesToSend?></td>
			<td>&nbsp;<input class="fieldbox11" type="text" id="numberOfMessagesToSend" name="numberOfMessagesToSend" size="6"  value="<?php echo $numberOfMessagesToSend?>" />&nbsp;<img  alt="" onmouseover="infoBox('setf_2', '<?php echo fixjsstring(GENERIC_17)?>', '<?php echo fixjsstring(SCHEDULERTASKS_54)?>', '15em','0'); " onmouseout="hide_info_bubble('setf_2','0')" src="./images/helpSmallWhite.gif"><span style="display: none;" id="setf_2"></span></td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_16?></span></td>
			<td>&nbsp;<?php echo $repeatDetailsMemo?></td>
			<td>&nbsp;
				<?php
				$repeatDetailsMemo 	= trim($repeatDetailsMemo);
				$posOfSlash		    = strpos($repeatDetailsMemo, '/');
				$pRepeatEveryPeriodValue = substr($repeatDetailsMemo,0,$posOfSlash);
				//$pRepeatEveryPeriodValue = str_ireplace($pRepeatEveryPeriodValue, "/", "");
				$pRepeatEveryPeriodValue = trim($pRepeatEveryPeriodValue);
				$pRepeatEveryPeriod 	= trim(substr(strtolower($repeatDetailsMemo),$posOfSlash+1)); //ok
				//echo '<b>-'.stristr($pRepeatEveryPeriod , "minutes").'-</b>';
                ?>
				<input class="fieldbox11" size="4" type="text" id="RepeatEveryPeriodValue" name="RepeatEveryPeriodValue" value="<?php echo $pRepeatEveryPeriodValue?>">
				<select id="RepeatEveryPeriod" name="RepeatEveryPeriod" class=select>
				<!--<option value="0"><?php echo SCHEDULERTASKS_37?>
				<option value="we" <?php if (stristr($pRepeatEveryPeriod , "weeks")){echo " selected";}?>><?php echo SCHEDULERTASKS_39?>
				<option value="da" <?php if (stristr($pRepeatEveryPeriod , "days")) {echo " selected";}?>><?php echo SCHEDULERTASKS_40?>
				<option value="ho" <?php if (stristr($pRepeatEveryPeriod , "hours")) {echo " selected";}?>><?php echo SCHEDULERTASKS_41?>-->
				<option value="mi" <?php if (stristr($pRepeatEveryPeriod , 'minutes')) {echo  " selected";}?>><?php echo SCHEDULERTASKS_42?></option>
				</select>
			</td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_22?></span></td>
			<td><?php if ($taskRecurring=="-1") {echo SCHEDULERTASKS_49;} else {echo SCHEDULERTASKS_57;}?></td>
			<td>&nbsp;<input size="4" type="checkbox" id="recurringEvent" name="recurringEvent" value="-1"<?php if ($taskRecurring=="-1") {echo " checked";}?>>&nbsp;<img alt="" onmouseover="infoBox('setf_3', '<?php echo fixjsstring(GENERIC_17)?>', '<?php echo fixjsstring(SCHEDULERTASKS_53)?>', '15em','0'); " onmouseout="hide_info_bubble('setf_3','0')" src="./images/helpSmallWhite.gif"><span style="display: none;" id="setf_3"></span></td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_23?></span></td>
			<td>&nbsp;<?php echo $reactivateDetailsMemo?></td>
			<td>
			&nbsp;
			<?php
			$reactivateDetailsMemo 	= trim($reactivateDetailsMemo);
			$posOfSlash		        = strpos($reactivateDetailsMemo, '/');
			$preactivatePeriodValue 	= substr($reactivateDetailsMemo,0,$posOfSlash);
			//$preactivatePeriodValue 	= replace($preactivatePeriodValue, "/", "");
			$preactivatePeriodValue 	= trim($preactivatePeriodValue);
			$preactivatePeriod          = trim(substr(strtolower($reactivateDetailsMemo),$posOfSlash+1));
			?>
			<input class="fieldbox11" size="4" type="text" id="reactivatePeriodValue" name="reactivatePeriodValue" value="<?php echo $preactivatePeriodValue?>">
			<select id="reactivatePeriod" name="reactivatePeriod" class=select>
			<option value="0"><?php echo SCHEDULERTASKS_37?></option>
			<option value="mo" <?php if (stristr($preactivatePeriod, "30d")) {echo  " selected";}?>><?php echo SCHEDULERTASKS_38?></option>
			<option value="we" <?php if (stristr($preactivatePeriod, "weeks")) {echo  " selected";}?>><?php echo SCHEDULERTASKS_39?></option>
			<option value="da" <?php if (stristr($preactivatePeriod, "days")) {echo  " selected";}?>><?php echo SCHEDULERTASKS_40?></option>
			<option value="ho" <?php if (stristr($preactivatePeriod, "hours")) {echo  " selected";}?>><?php echo SCHEDULERTASKS_41?></option>
			<!--<option value="mi" <?php if (stristr($preactivatePeriod, "minutes")) {echo  " selected";}?>><?php echo SCHEDULERTASKS_42?>-->
			</select>
			</td>
		</tr>

		<tr>
			<td width=200  class="settingsRow"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_12?></span></td>
			<td><a href="#" onclick="openConfirmBox('delete.php?action=task&amp;idTask=<?php echo $fidTask?>','<?php echo fixJSstring(CONFIRM_13)?>');return false;"><img border="0" src="./images/delete.png" width="18" height="18" alt=""></a></td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td  colspan=3 align="center" style="BORDER-TOP: #999999 1px solid;">
				<input type="submit" class="submit" id="update" name="update" value="<?php echo SCHEDULERTASKS_50?>"></td>
		</tr>
</TBODY>
</table>
</form>

<?php
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>