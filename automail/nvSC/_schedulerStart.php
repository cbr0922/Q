<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$sBase1="../data_files/";
$admin=$_GET["admin"];
$admin=dbQuotes(dbProtect($admin, 250));
if (!$admin)	{
    schedulerLog(myDatenow()."-->Missing admin credentials. Cannot login-->STOP", $idGroup, "X", $sBase1);
    $obj->closeDb();
 	die;
}
$posOf_             = stripos($admin, "_");
$adminLen           = strlen($admin);
$padminName 		= substr($admin,0,$posOf_);
$padminPassword 	= substr($admin,$posOf_+1);

$mySQL="SELECT idAdmin, adminName, idGroup, adminPassword FROM ".$idGroup."_admins WHERE adminName='".$padminName."' AND adminPassword='".$padminPassword."'";
$result = $obj->query($mySQL);
if ($obj->num_rows($result)!=1) {
    schedulerLog(myDatenow()."-->Wrong admin credentials.-->STOP", $idGroup, "X", $sBase1);
	die;
}
else {
    $row 	= $obj->fetch_row($result);
    $idGroupL = $row[2];
}
schedulerLog(myDatenow()."-->Cron started", $idGroup, "X", $sBase1);

$pTimeOffsetFromServer 	= $obj->getSetting("groupTimeOffsetFromServer", $idGroup);
//$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupDateTimeFormat 	=	"Y-m-d H:i:s";
$dateNow                = myDatenow();
$dateNow	            = addOffset($dateNow, $pTimeOffsetFromServer, $groupDateTimeFormat);

//### CHECK IF THERE ARE TASKS RECURRENT AND IF YES DUPLICATE THE CAMPAIGN AND UPDATE THE TASK TO POINT TO THE NEW CAMPAIGN.
// This check is performed also when closing the campaign.
$mySQL_0="SELECT idTask, ".$idGroup."_tasks.idCampaign, activationDateTime, reactivateAfterXSeconds, notes, pLog 
	FROM ".$idGroup."_tasks INNER JOIN ".$idGroup."_campaigns on ".$idGroup."_tasks.idCampaign=".$idGroup."_campaigns.idCampaign
	WHERE  taskCompleted=-1 AND taskRecurring=-1 AND completed=-1 AND dateTaskCompleted is not null AND dateCompleted is not null";
$result0 = $obj->query($mySQL_0);
if ($obj->num_rows($result0)>0) {
while ($row0 = $obj->fetch_array($result0)) {
    $idTask				        = $row0["idTask"];
    $activationDateTime		    = $row0["activationDateTime"];
    $reactivateAfterXSeconds    = $row0["reactivateAfterXSeconds"];
    $idCampaign				    = $row0["idCampaign"];
    $oldLog                     = $row0["pLog"];
    $oldNotes                   = $row0["notes"];

	$pNewActivationDateTime=date("Y-m-d H:i:s" , strtotime(+$reactivateAfterXSeconds.' seconds', strtotime($activationDateTime)));
    $pNewNotes="";
	$pNewNotes.=SCHEDULERTASKS_59.' '.$idCampaign."\r\n";
	$pNewNotes.=SCHEDULERTASKS_60.' '.$idTask.' '.SCHEDULERTASKS_61.' '.$pNewActivationDateTime;
    $pNewNotes = $pNewNotes."\r\n".$oldNotes;
    $mySQLm1="INSERT INTO ".$idGroup."_campaigns
    (idGroup, fromName, fromEmail, replyToEmail, campaignName, joins, idList, listName, idHtmlNewsletter, htmlNewsletterName, textNewsletterName, dateCreated, idAdmin, type, idTextNewsletter, confirmed, prefers, idSendFilter, urlToSend, emailSubject, sendFilterDesc, mLists, notes)
    SELECT
     idGroup, fromName, fromEmail, replyToEmail, campaignName, joins, idList, listName, idHtmlNewsletter, htmlNewsletterName, textNewsletterName, '".$dateNow."', idAdmin, type, idTextNewsletter, confirmed, prefers, idSendFilter, urlToSend, emailSubject, sendFilterDesc, mLists, '".$pNewNotes."'
    FROM ".$idGroup."_campaigns WHERE idCampaign=$idCampaign";
    $obj->query($mySQLm1);
    // get last insert id
    $last =  $obj->insert_id();
	//point old task to new mailing
    $pLog=SCHEDULERTASKS_62.$idCampaign." / ".SCHEDULERTASKS_63.$last;
    $pNewLog=$oldLog."\r\n".$pLog;
	$mySQLu="UPDATE ".$idGroup."_tasks SET idCampaign=".$last.", lastExecutionFromScheduler=null, dateTaskCompleted=null, activationDateTime='".$pNewActivationDateTime."',  pLog='".$pNewLog."', taskCompleted=0, taskCounter=0, timesExecuted=0 WHERE idTask=$idTask";
	$obj->query($mySQLu);
    }
}

function sendtask($idTask) {
	 header("Location: _schedulerSend.php?idTask=$idTask");
}

//Pick 10 tasks
$mySQL3="SELECT idTask, idCampaign, activationDateTime, numberOfMessagesToSend, repeatEveryXseconds, lastExecutionFromScheduler, timesExecuted, taskCounter FROM ".$idGroup."_tasks WHERE taskCompleted=0 ORDER BY activationDateTime, lastExecutionFromScheduler asc LIMIT 10";
$result3 = $obj->query($mySQL3);
if ($obj->num_rows($result3)==0) {
    schedulerLog(myDatenow()."-->No tasks found.-->STOP", $idGroup, "X", $sBase1);
	$obj->closeDb();
    die;
}
else {
      while ($row = $obj->fetch_array($result3)) {
      $idTask				        = $row["idTask"];
      $idCampaign				    = $row["idCampaign"];
      $lastExecutionFromScheduler	= $row["lastExecutionFromScheduler"];
      $lastExecutionFromScheduler	= addOffset($lastExecutionFromScheduler, $pTimeOffsetFromServer, $groupDateTimeFormat);
      $activationDateTime		    = $row["activationDateTime"];
      $activationDateTime	        = addOffset($activationDateTime, $pTimeOffsetFromServer, $groupDateTimeFormat);
      $numberOfMessagesToSend      = $row["numberOfMessagesToSend"];
      $repeatEveryXseconds         = $row["repeatEveryXseconds"];
      $timesExecuted               = $row["timesExecuted"];
      $taskCounter                 = $row["taskCounter"];



      //if activation falls in the past==>task is candidate.
      if (new DateTime($activationDateTime)<new DateTime($dateNow)) {
          if (campaignMailCounter($idCampaign, $idGroup)==0) {        // =>NO EMAILS SENT, EXECUTE THE TASK
//              header("Location: _schedulerSend.php?idTask=$idTask");
				sendtask($idTask);
              	return false;
          }
          else if (campaignMailCounter($idCampaign, $idGroup)>0) {    // =>SOME EMAILS WERE SENT ALREADY
              /*if ($numberOfMessagesToSend>0 && strtotime($lastExecutionFromScheduler)!=false && $timesExecuted>0 && ((strtotime($dateNow)-strtotime($lastExecutionFromScheduler))>$repeatEveryXseconds)) {
              header("Location: _schedulerSend.php?idTask=$idTask");
              */
              if ($taskCounter==0 || $taskCounter<campaignMailCounter($idCampaign, $idGroup)){
                    schedulerLog(myDatenow()."-->Check-point-1.-->STOP.", $idGroup, $idTask, $sBase1);
                    $mySQL4="Update ".$idGroup."_tasks SET taskCounter=".campaignMailCounter($idCampaign, $idGroup)." WHERE idTask=".$idTask;
                    $obj->query($mySQL4);
              }
              else if ($taskCounter==campaignMailCounter($idCampaign, $idGroup) && $numberOfMessagesToSend==0) {
                    //applies when NOT using batches
                    schedulerLog(myDatenow()."-->Check-point-2.-->CONTINUE", $idGroup, $idTask, $sBase1);
                    //header("Location: _schedulerSend.php?idTask=$idTask");
					sendtask($idTask);
                    return false;
              }
              else if ($taskCounter==campaignMailCounter($idCampaign, $idGroup) && $numberOfMessagesToSend>0 && strtotime($lastExecutionFromScheduler)!=false
                    && $timesExecuted>0 && ((strtotime($dateNow)-strtotime($lastExecutionFromScheduler))>$repeatEveryXseconds)) {
                    //means that a batch completed, was at least once executed and batch interval elapsed==>process
                    schedulerLog(myDatenow()."-->Check-point-3.-->CONTINUE", $idGroup, $idTask, $sBase1);
                    //header("Location: _schedulerSend.php?idTask=$idTask");
					sendtask($idTask);
                    return false;
              }
			  else if ($taskCounter==campaignMailCounter($idCampaign, $idGroup) && $numberOfMessagesToSend>0 && strtotime($lastExecutionFromScheduler)==false
                    && $timesExecuted==0 && ((strtotime($dateNow)-strtotime($lastExecutionFromScheduler))>$repeatEveryXseconds)) {
                    //some mails sent, counter equalized, task cycle did not complete, execution and batch interval elapsed==>process
					//covers case where campaign was running manually
                    schedulerLog(myDatenow()."-->Check-point-4.-->CONTINUE", $idGroup, $idTask, $sBase1);
                    //header("Location: _schedulerSend.php?idTask=$idTask");
					sendtask($idTask);
                    return false;
              }
              else {
                    schedulerLog(myDatenow()."-->Check-point-5.-->STOP. Time since last execution: ".(strtotime($dateNow)-strtotime($lastExecutionFromScheduler))."  Batch pause: ".$repeatEveryXseconds, $idGroup, $idTask, $sBase1);
              }
          }
      }
      else {
          schedulerLog(myDatenow()."-->Activation in the future.-->STOP", $idGroup, $idTask, $sBase1);
      }
      } //while
      }
?>