<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
function campaignMailCounter($idCampaign, $idGroup) {
    $mySQL="SELECT mailCounter FROM ".$idGroup."_campaigns WHERE idCampaign=$idCampaign";
	$obj1 = new db_class();
    $re	        = $obj1->query($mySQL);
    $rC         = $obj1->fetch_array($re);
    return $rC['mailCounter'];
	//return $pcounter;
}
function campaignQuickInfo($pidCampaign, $pcampaignName, $plistname, $pdateCompleted) {
	if (!empty($pdateCompleted)) {$completeOn=$pdateCompleted;} else {$completeOn=ALLSTATS_94;}
	$contents="";
	if ($pcampaignName) {$contents= "<div><span class=statsLegend>".fixjsstring($pcampaignName)."</span></div>";}
	$contents.="<div style=margin-top:2px><span class=statsLegend>".ALLSTATS_76.": </span>".fixjsstring($plistname)."</div>";
	$contents.="<div style=margin-top:2px><span class=statsLegend>".ALLSTATS_852.": </span>".$completeOn."</div>";
	echo ($contents);
}


function schedulerFlag($idCampaign, $idGroup) {
		$SQLc="SELECT idTask, activationDateTime, numberOfMessagesToSend, lastExecutionFromScheduler, repeatDetailsMemo, pLog from ".$idGroup."_tasks where idCampaign=$idCampaign";
        $obj1 = new db_class();
        $resultC=$obj1->query($SQLc);
        $rowC = $obj1->fetch_array($resultC);
		if ($rowC["idTask"]) {
			$pTimeOffsetFromServer 	=	$obj1->getSetting("groupTimeOffsetFromServer", $idGroup);
			$groupDateTimeFormat 	=	$obj1->getSetting("groupDateTimeFormat", $idGroup);
		    $activationDateTime				= $rowC["activationDateTime"];
		    $activationDateTime	    		= addOffset($activationDateTime, $pTimeOffsetFromServer, $groupDateTimeFormat);
		    $numberOfMessagesToSend			= $rowC["numberOfMessagesToSend"];
		    $lastExecutionFromScheduler		= $rowC["lastExecutionFromScheduler"];
		    $lastExecutionFromScheduler	    = addOffset($lastExecutionFromScheduler, $pTimeOffsetFromServer, $groupDateTimeFormat);
		    $repeatDetailsMemo				= $rowC["repeatDetailsMemo"];
			$pLog							= $rowC["pLog"];
			$pLog = str_ireplace("\r", "", $pLog);
        	$pLog = str_ireplace("\n", "<br>", $pLog);
 			$Content= fixjsstring(ALLSTATS_29)."<br><br>";
			$Content.= fixjsstring(SCHEDULERTASKS_6).": ".$activationDateTime."<br>";
			$Content.= fixjsstring(SCHEDULERTASKS_7).": ".$numberOfMessagesToSend."<br>";
			$Content.= fixjsstring(SCHEDULERTASKS_16).": ".$repeatDetailsMemo."<br>";
			$Content.= fixjsstring(SCHEDULERTASKS_11).": ".$lastExecutionFromScheduler."<br><br>";
			$Content.= "<b>".fixjsstring(SCHEDULERTASKS_9)."</b><br>".$pLog;
			
		?>
			&nbsp;&nbsp;<a href="_schedulerTasks.php?idTask=<?php echo $rowC["idTask"]?>"><img border="0" onmouseover="infoBox('ta<?php echo $idCampaign?>', '<?php echo fixjsstring(SCHEDULERTASKS_3.': ' .$rowC["idTask"])?>', '<?php echo fixjsstring($Content)?>', '25em', '0'); "  onmouseout="hide_info_bubble('ta<?php echo $idCampaign?>','0')" alt="<?php echo fixJSstring(ALLSTATS_29)?>" src="./images/calendargo.png"></a><span style="display:none;" id="ta<?php echo $idCampaign?>"></span>
		<?php }
}
function campaignIsScheduled($idCampaign, $idGroup) {
		$SQLc="SELECT count(*) from ".$idGroup."_tasks where idCampaign=$idCampaign";
        $obj1 = new db_class();
        //$obj1->get_rows("$SQLc");
		if (($obj1->get_rows("$SQLc"))>0) {return true;} else {return false;}
}
function schedulerLog($line, $idGroup, $idTask, $sBase){
    $pfileName	= "scLog_".$idGroup.".txt";
    $MyFile 	= fopen($sBase."/".$pfileName, "a");
	if (!$MyFile) {die("\r\nCannot create scheduler log file. Data_files folder must have write permissions.");}
    $nline='Task '.$idTask.'-->'.$line."\r\n";
    fwrite($MyFile, $nline);
    fclose($MyFile);
}

function subscribedIn($idemail, $idGroup){
	$obj2 = new db_class();
	echo $obj2->tableCount_condition($idGroup."_listRecipients", " WHERE ".$idGroup."_listRecipients.idEmail=".$idemail);
}
Function showMessageBox(){
   	isset($_GET['message'])?$pmessage = $_GET['message']:$pmessage ='';
	if ($pmessage) {
		echo "<script language='javascript' type='text/javascript'>openmessageBox('".fixJSstring($pmessage)."')</script>";
    }
}
function getlistname($idlist, $idGroup) {
    if ($idlist!=0) {
    	$mySQL="SELECT listName FROM ".$idGroup."_lists WHERE idList=$idlist";
    	$obj1 = new db_class();
    	$result	= $obj1->query($mySQL);
    	$row = $obj1->fetch_array($result);
    	return $row['listName'];
    }
}
function campaignNotes($notes, $pidCampaign) {
    $notes = str_ireplace("\r", "", $notes);
    $notes = str_ireplace("\n", "<br>", $notes);?>
	<a href="javascript:openWindow('campaignNotes.php?idCampaign=<?php echo $pidCampaign?>',450,300)" <?php if ($notes) {?> onmouseover="infoBox('n<?php echo $pidCampaign?>', '<?php echo fixjsstring(ALLSTATS_88).' '.$pidCampaign.": ".fixJSstring(CAMPAIGNNOTES_2);?>', '<?php echo fixJSstring($notes);?>','20em', '0');" onmouseout="hide_info_bubble('n<?php echo $pidCampaign;?>','0');" <?php } ?>><img style="vertical-align:text-top;" src="images/notes.png"  width="21" height="21" alt="" title="<?php //echo ALLSTATS_15;?>" border="0"></a><span style="display:none;text-align:justify;" id="n<?php echo $pidCampaign?>"></span>
<?php
}

function getNewsletterData($idNewsletter, $idGroup, $i) {
if ($idNewsletter!=0) {
	$mySQL="SELECT name, body, attachments, charset, inlineImages FROM ".$idGroup."_newsletters WHERE idNewsletter=$idNewsletter";
	$obj1 = new db_class();
	$result	= $obj1->query($mySQL);
	$row = $obj1->fetch_array($result);
	$nslDataArray[0]=$row['name'];
    $nslDataArray[1]=$row['body'];
    //$nslDataArray["b"]=$row['body'];
    $nslDataArray[2]=$row['attachments'];
    $nslDataArray[3]=$row['charset'];
    $nslDataArray[4]=$row['inlineImages'];
    return $nslDataArray[$i];
}
}
function getnewslettername($idNewsletter, $idGroup) {
    if ($idNewsletter!=0) {
    	$mySQL="SELECT name FROM ".$idGroup."_newsletters WHERE idNewsletter=$idNewsletter";
    	$obj1 = new db_class();
    	$result	= $obj1->query($mySQL);
    	$row = $obj1->fetch_array($result);
    	return $row['name'];
    }
}
function getadminname($idAdmin, $idGroup){
	$obj2 = new db_class();
	$r = $obj2->query("SELECT adminFullName FROM ".$idGroup."_admins WHERE idAdmin=".$idAdmin);
	$row = $obj2->fetch_array($r);
	return $row['adminFullName'];
}
function getadminemail($idAdmin, $idGroup){
	$obj2 = new db_class();
	$r = $obj2->query("SELECT adminEmail FROM ".$idGroup."_admins WHERE idAdmin=".$idAdmin);
	$row = $obj2->fetch_array($r);
	return $row['adminEmail'];
}
function getSendFilterCode($pidSendFilter, $idGroup) {
if ($pidSendFilter!=0) {
	$mySQLf="SELECT sendFilterCode FROM ".$idGroup."_sendFilters WHERE idSendFilter=$pidSendFilter";
	$obj1 = new db_class();
	$result	= $obj1->query($mySQLf);
	$row = $obj1->fetch_array($result);
	return $row['sendFilterCode'];
    }
}
function getSendFilterDesc($pidSendFilter, $idGroup) {
if ($pidSendFilter!=0) {
	$mySQLf="SELECT sendFilterDesc FROM ".$idGroup."_sendFilters WHERE idSendFilter=$pidSendFilter";
	$obj1 = new db_class();
	$result	= $obj1->query($mySQLf);
	$row = $obj1->fetch_array($result);
	return $row['sendFilterDesc'];
    }
}
function listDeleted($pidlist, $idGroup) {
    $obj1 = new db_class();
    $lists =  $obj1->tableCount_condition($idGroup."_lists", " WHERE idGroup=".$idGroup."");
    if ($pidlist==0 && $lists==0) {
        return true;
    }
    elseif ($pidlist!=0 && $pidlist!=-1) {  //applies when I have just a single list
        $mySQL="SELECT idList FROM ".$idGroup."_lists WHERE idList=$pidlist";
        $result = $obj1->query($mySQL);
        if ($obj1->num_rows($result)!=1) {
            return true;
        }
     }
     else {return false;}
}
function newsletterDeleted($pidnewsletter, $idGroup) {
    if ($pidnewsletter!=0) {
      $mySQL="SELECT idNewsletter FROM ".$idGroup."_newsletters WHERE idNewsletter=$pidnewsletter";
      $obj1 = new db_class();
      $result = $obj1->query($mySQL);
      if ($obj1->num_rows($result)!=1) {
      return true;
      } else {return false;}
    }
}
function contentDeleted($ptype,$pidHtmlNewsletter,$pidTextNewsletter,$idGroup) {
  if ( ($ptype==3) &&   (newsletterDeleted($pidHtmlNewsletter,$idGroup) || newsletterDeleted($pidTextNewsletter,$idGroup))) {return true;}
  if ( $ptype==1 && newsletterDeleted($pidHtmlNewsletter,$idGroup)) {return true;}
  if ( $ptype==2 && newsletterDeleted($pidTextNewsletter,$idGroup)) {return true;}
}

function filterDeleted($pidSendFilter, $idGroup) {
    if ($pidSendFilter!=0) {
      $mySQL="SELECT idSendFilter FROM ".$idGroup."_sendFilters WHERE idSendFilter=$pidSendFilter";
      $obj1 = new db_class();
      $result = $obj1->query($mySQL);
      if ($obj1->num_rows($result)!=1) {
      return true;
      } else {return false;}
    }
}
function listsToSql($justLists, $idGroup) {
    $sqlLists="";
    if (!empty($justLists)) {
        $listsAr = explode(", ", $justLists);
        $listsCount = sizeof($listsAr);
        if ($listsCount!=0) {
            for ($z=0; $z<$listsCount; $z++)  {
                $sqlLists.=$idGroup."_listRecipients.idList=".$listsAr[$z]. ' OR ';
            }
            $sqlLists = ' AND ('.rtrim($sqlLists, " OR ").')';
        }

    }
    return $sqlLists;
}
function listsWhere($justLists, $idGroup) {
    $whereLists="";
    if (!empty($justLists)) {
        $listsAr = explode(", ", $justLists);
        $listsCount = sizeof($listsAr);
        if ($listsCount!=0) {
            for ($z=0; $z<$listsCount; $z++)  {
                $whereLists.="idList=".$listsAr[$z]. ' OR ';
            }
            $whereLists = ' WHERE ('.rtrim($whereLists, " OR ").')';
        }

    }
    return $whereLists;
}

function formatDecimals($number) {
    $val1 = 100*$number;
	return number_format($val1,2);
}

function formatPercent($number, $decs) {
    $val1 = 100*$number;
	return number_format($val1,$decs).'%';
	//return str_ireplace(",", "", $returnVal);
}
function subscriberuniqueopenrate($pidemail, $idGroup, $ptimesMailed) {
	//count only unique openings
		$mySQLuor="SELECT count(distinct idCampaign) as timesOpened, count(idCampaign) as timesOpened2 from ".$idGroup."_viewStats WHERE idEmail=$pidemail AND idGroup=$idGroup";
		$obj1 = new db_class();
		$result	= $obj1->query($mySQLuor);
		$row = $obj1->fetch_array($result);
		if ($ptimesMailed>0) {
			$val1 = 100*($row['timesOpened']/$ptimesMailed);
			$val2 = 100*($row['timesOpened2']/$ptimesMailed);
		} else { $val1 = 0;$val2 = 0;}
		echo $r1 = number_format($val1,2).'%'	.' / ';
		echo $r2 = number_format($val2,2).'%';
}
function addViewsTracker($string) {
    $string = str_ireplace("</body>", "", $string);
    $string = str_ireplace("</html>", "", $string);
    $string = $string.'#viewstracker#';
    return $string;
}

//google_params: creates a pop-up showing google parameters
function google_params($idCampaign, $GA_array)	{
	$pHeader		= fixjsstring(ALLSTATS_88).' '.$idCampaign.": Google analytics ".CAMPAIGNCREATE_52;
	$Content		= fixjsstring(CAMPAIGNCREATE_45).": ".$GA_array["ga_utm_source"]."<br>";
	$Content		= $Content.fixjsstring(CAMPAIGNCREATE_46).": ".$GA_array["ga_utm_medium"]."<br>";
	$Content		= $Content.fixjsstring(CAMPAIGNCREATE_47).": ".$GA_array["ga_utm_campaign"]."<br>";
	$Content		= $Content.fixjsstring(CAMPAIGNCREATE_48).": ".$GA_array["ga_utm_term"]."<br>";
	$Content		= $Content.fixjsstring(CAMPAIGNCREATE_49).": ".$GA_array["ga_utm_content"];
	?>
	<img onmouseover="infoBox('ga<?php echo $idCampaign?>', '<?php echo fixjsstring($pHeader)?>', '<?php echo fixjsstring($Content)?>', '25em', '0'); "  onmouseout="hide_info_bubble('ga<?php echo $idCampaign?>','0')" alt="<?php echo 'Google Analytics'.fixJSstring(CAMPAIGNCREATE_52)?>" style="vertical-align:text-top;" src="./images/ga.png"><span style="display:none;" id="ga<?php echo $idCampaign?>"></span>&nbsp;
<?php }

function dateSorter($groupDateTimeFormat) {
	if (!empty($groupDateTimeFormat)) {	
		switch ($groupDateTimeFormat) {
		  case  "m/d/Y	g:i a":
		  	return "date-us";
		  break;
		  case  "d/m/Y	g:i a":
			return "date-au";
		  break;
		  case  "d.m.Y	H:i:s":
		  	return "date-de";
		  break;
		  case  "d/m/Y	H:i:s":
		  	return "date-eu";
		  break;
		  case  "d-m-Y	H:i:s":
			  return "date-eu2";
		  break;
		  case  "Y-m-d	H:i:s":
			  return "date-iso";
		  break;
		  case  "Y.m.d	H:i:s":
			  return "date-hu";
		  break;
		  case  "Y/m/d	H:i:s":
			  return "date-jp";
		  break;
		  default:
			return "nosort";
		}  
	}
	else {
		return "nosort";
	}  
}


?>