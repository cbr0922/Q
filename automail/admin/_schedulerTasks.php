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

<?php
//filter for a specific campaign
(isset($_GET['idTask']))?$fidTask = $_GET['idTask']:$fidTask="";
if ($fidTask) {	$strSQL=" AND idTask=$fidTask";} else {$strSQL="";}
$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$rowsPerPage 	= 20;
$offset 		= ($page - 1) * $rowsPerPage;
$urlPaging      = $self.'?';
$range=10;
?>
<table width="960px" border="0" cellspacing=0 cellpadding=4>
	<tr>
		<td valign="top">
			<span class="title"><?php echo SCHEDULERTASKS_1;?></span>
			<br><a href="_schedulerCreateTaskForm.php"><?php echo SCHEDULERTASKS_2;?></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="../data_files/scLog_<?php echo $idGroup?>.txt"><?php echo SCHEDULERTASKS_13;?></a>
		</td>
		<td align="right"  valign="top">
			<img src="./images/tasks.png" width="65" height="51" alt="">
		</td>
	</tr>
</table>
<br>
<?php
//get tasks
$limitSQL 		= " LIMIT $offset, $rowsPerPage";
$mySQL="SELECT idTask, idCampaign, idAdmin, idGroup, dateTaskCreated, activationDateTime, numberOfMessagesToSend, repeatEveryXseconds, taskCompleted,
dateTaskCompleted, lastExecutionFromScheduler, repeatDetailsMemo, taskRecurring, reactivateAfterXSeconds, reactivateDetailsMemo, plog, timesExecuted
FROM ".$idGroup."_tasks WHERE idGroup=$idGroup" .$strSQL.' ORDER by idTask desc'.$limitSQL;
//echo $mySQL.'<br />';
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows){echo '<br><img src="./images/warning.png" alt=""> '.SCHEDULERTASKS_15;}
else {
	$countSQL="SELECT count(idTask) from ".$idGroup."_tasks where idGroup=$idGroup ".$strSQL;
	$numrows=$obj->get_rows($countSQL);
	$maxPage = ceil($numrows/$rowsPerPage);
    include('nav.php');
    ?>
    <table style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #999999 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid"  border=0  width="98%" cellpadding="4" cellspacing="0">
    <?php
    while ($row = $obj->fetch_array($result)){
	    $idTask				    = $row["idTask"];
	    $idCampaign				= $row["idCampaign"];

	    $idAdmin				= $row["idAdmin"];
	    $dateTaskCreated		= $row["dateTaskCreated"];
        $dateTaskCreated	    = addOffset($dateTaskCreated, $pTimeOffsetFromServer, $groupDateTimeFormat);
	    $activationDateTime		= $row["activationDateTime"];
	    $activationDateTime	    = addOffset($activationDateTime, $pTimeOffsetFromServer, $groupDateTimeFormat);
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
	    $log		  			= $row["plog"];
        $log = str_ireplace("\r", "", $log);
        $log = str_ireplace("\n", "<br>", $log);

        $timesExecuted			= $row["timesExecuted"];
		$mySQLc="SELECT campaignName, notes from ".$idGroup."_campaigns where idCampaign=".$idCampaign;
		$rc	= $obj->query($mySQLc);
		$row2 	= $obj->fetch_array($rc);
		$campaignName	= $row2["campaignName"];
		$campaignNotes	= $row2["notes"];
?>
	<tr>
		<td  height="30px" style="BORDER-RIGHT: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid" bgcolor="#ffffff" colspan=8>
            <b><?php echo SCHEDULERTASKS_3;?>:&nbsp;<?php echo $idTask?></b>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo SCHEDULERTASKS_4;?>:&nbsp;<a title="<?php echo SCHEDULERTASKS_20?>" href="campaigns.php?idCampaign=<?php echo $idCampaign?>"><?php echo $idCampaign?><?php if ($campaignName) {echo '. '.$campaignName;}?></a>
             <?php campaignNotes($campaignNotes, $idCampaign);?>
        </td>
	</tr>

	<tr>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_19;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php echo $idAdmin.'.'.getadminname($idAdmin, $idGroup)?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_5;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php echo $dateTaskCreated?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_7;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php echo $numberOfMessagesToSend?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_22;?></span></td>
		<td  valign="top" style="BORDER-RIGHT: #999999 1px solid" bgcolor="#ffffff"><?php if ($taskRecurring=="-1") {echo  SCHEDULERTASKS_17;}else{echo  SCHEDULERTASKS_18;}?></td>
	</tr>
	<tr>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_21;?></span></td>
		<td bgcolor="#ffffff" valign="top"><a href="_schedulerEditTaskForm.php?idTask=<?php echo $idTask?>"><img alt="" border="0" src="./images/edit.png" width="22" height="22" title="<?php echo SCHEDULERTASKS_21?>"></a></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_6;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php echo $activationDateTime?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_16;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php echo $repeatDetailsMemo?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_23;?></span></td>
		<td valign="top" style="BORDER-RIGHT: #999999 1px solid"  bgcolor="#ffffff"><?php echo $reactivateDetailsMemo?></td>
	</tr>
	<tr>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_9;?></span></td>
		<td bgcolor="#ffffff" valign="top">
        <a href="javascript:openWindow('_schedulerTaskNotes.php?idTask=<?php echo $idTask?>',450,300)"
        <?php if ($log){?>
        onmouseover="infoBox('t<?php echo $idTask?>', '<?php echo fixJSstring(SCHEDULERTASKS_9);?>', '<?php echo fixJSstring($log);?>','30em', 0);"
        onmouseout="hide_info_bubble('t<?php echo $idTask;?>','1');"
        <?php }?>><img src='./images/notes.png'  width="20" height="20" border="0" alt=""></a>
        &nbsp;&nbsp;<span style="display:none;text-align:justify;" id="t<?php echo $idTask?>"></span>

        </td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_11;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php echo $lastExecutionFromScheduler?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_8;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php echo $repeatEveryXseconds?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_14;?></span></td>
		<td valign="top" style="BORDER-RIGHT: #999999 1px solid"  bgcolor="#ffffff"><?php echo $reactivateAfterXSeconds?></td>
	</tr>
	<tr >
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_12;?></span></td>
		<td bgcolor="#ffffff" valign="top"><a href="#" onclick="openConfirmBox('delete.php?action=task&amp;idTask=<?php echo $idTask?>','<?php echo fixJSstring(CONFIRM_13)?>');return false;"><img alt="" border="0" src="./images/delete.png" width="18" height="18" title="<?php echo SCHEDULERTASKS_12;?>"></a></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"><?php echo SCHEDULERTASKS_10;?></span></td>
		<td bgcolor="#ffffff" valign="top"><?php if ($taskCompleted!=-1) {echo ' ';} else{echo $dateTaskCompleted;}?></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"></span></td>
		<td bgcolor="#ffffff" valign="top"></td>
		<td class="settingsRow" valign="top"><span style="color:#ffffff"></span></td>
		<td valign="top" style="BORDER-RIGHT: #999999 1px solid" bgcolor="#ffffff"></td>
	</tr>

	<tr>
		<td  style="BORDER-RIGHT: #ffffff 0px solid; BORDER-LEFT: #ffffff 0px solid; BORDER-TOP: #999999 1px solid; BORDER-BOTTOM: #ffffff 1px solid" colspan=8>&nbsp;</td>
	</tr>

 <?php
} ?>
</table>
<br><br>

<?php
include('nav.php');
}
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>
