<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj = new db_class();
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/plain; charset='.$groupGlobalCharset.'');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
if (@$pdemomode) {
  forDemo2("demo");
}
$myDay 			= myDatenow();
$pFilterOption 	= $_REQUEST['FilterOption'];
@$plinks	 		= $_REQUEST['linkUrl']; //returns numbers split by commas
$pidCampaign    = $_REQUEST['selectedCampaign'];

//PARSE LINKS
$z="";
$sqlLinks="";
$txtLinks="";
if ($pFilterOption==6 OR $pFilterOption==7) {   //Clicked this link / Did not click this link
	$myLinksArray = explode(",", $plinks);
	$numbLinks = sizeof($myLinksArray);
//echo 'Links#: '.$numbLinks."<br>";
	$pOR= " OR ";
	$pSep= ", ";
	for ($z=0; $z<$numbLinks; $z++)  {
		if ($z==$numbLinks) {$pOR=""; $pSep="";}
		//if ($myLinksArray[$z]!=0) {
            $justLinkIDArr[]=substr($myLinksArray[$z], 0, strpos($myLinksArray[$z], "@"));
            $justLinkTxtArr[]=substr($myLinksArray[$z], strpos($myLinksArray[$z], "@")+1);
        	$sqlLinks.='idLink='.$justLinkIDArr[$z].$pOR;
            $txtLinks.="\r\n".$justLinkTxtArr[$z];
		//}
	}
    $sqlLinks = ' AND ('.rtrim($sqlLinks, " OR ").')';
    $txtLinks = $txtLinks."\r\n";
}
//print_r($sqlLinks);
//die;
$mySQL1="SELECT idList, confirmed, prefers, idSendFilter, mLists, joins FROM ".$idGroup."_campaigns WHERE idCampaign=$pidCampaign";
$result	= $obj->query($mySQL1);
$row = $obj->fetch_array($result);
	$plistname 		    = FOLLOWUPLIST_32.$pidCampaign;
	$pidSendFilter		= $row['idSendFilter'];
	$psendFilterCode	= getSendFilterCode($pidSendFilter, $idGroup);
    $mLists             = $row['mLists'];
    $sqlLists           = listsToSql($mLists, $idGroup);
    $confirmed		    = $row['confirmed'];
    $prefers		    = $row['prefers'];
    switch ($prefers) {
    case 1:
        $prefSQL = ' AND '.$idGroup.'_subscribers.prefersHtml=-1'; break;
    case 2:
        $prefSQL = ' AND '.$idGroup.'_subscribers.prefersHtml=0'; break;
    default:
        $prefSQL='';
    }
    switch ($confirmed) {
    case 1:
	    $confSQL = ' AND '.$idGroup.'_subscribers.confirmed=-1'; break;
	case 2:
		$confSQL = ' AND '.$idGroup.'_subscribers.confirmed=0'; break;
	default:
		$confSQL='';
    }
    $joins  = $row['joins'];
	$JOINSQL ="";
	if ($joins=="y") {
		if ($mLists!="") {
			$JOINSQL = " AND EXISTS (select idEmail FROM ".$idGroup."_listRecipients WHERE idList in ($mLists) AND ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail)";
		}
		else {
		 	$JOINSQL = " AND EXISTS (select idEmail FROM ".$idGroup."_listRecipients WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail)";	
		}
	}
    //later we will add the new filter.
//    $mySQL2="SELECT distinct ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers ".$JOINSQL." WHERE ".$idGroup."_subscribers.idEmail>0 ".$sqlLists .$prefSQL .$confSQL;
	$mySQL2="SELECT distinct ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idEmail>0 ".$prefSQL .$confSQL .$JOINSQL;

    switch ($pFilterOption) {
  	case 1: // Did not click and did not open at all
  		$pfollowUpNotes = ALLSTATS_52 .$pidCampaign;
  		$pReqSendFilterCode = " AND (NOT EXISTS (SELECT distinct idEmail FROM ".$idGroup."_clickStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND idCampaign=$pidCampaign) ";
  		$pReqSendFilterCode .= "AND NOT EXISTS (SELECT distinct idEmail FROM ".$idGroup."_viewStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$pidCampaign))";
  	break;
      case 2: // Did not open at all
  		$pfollowUpNotes = ALLSTATS_53 .$pidCampaign;
          $pReqSendFilterCode = " AND NOT EXISTS (SELECT distinct idEmail FROM ".$idGroup."_viewStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$pidCampaign) ";
  	break;
      case 3: // Did not click at all
  		$pfollowUpNotes = ALLSTATS_54 .$pidCampaign;
          $pReqSendFilterCode = " AND NOT EXISTS  (SELECT distinct idEmail FROM ".$idGroup."_clickStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND idCampaign=$pidCampaign) ";
  	break;
      case 4: // Opened at least once
  		$pfollowUpNotes = ALLSTATS_55 .$pidCampaign;
  		$pReqSendFilterCode = " AND EXISTS (SELECT distinct idEmail FROM ".$idGroup."_viewStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_viewStats.idEmail AND idCampaign=$pidCampaign) ";
  	break;
      case 5: //Clicked at least one link
  		$pfollowUpNotes = ALLSTATS_56 .$pidCampaign;
  		$pReqSendFilterCode = " AND EXISTS (SELECT distinct idEmail FROM ".$idGroup."_clickStats WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_clickStats.idEmail AND idCampaign=$pidCampaign) ";
      break;
      case 6: // Clicked a specific link
  		$pfollowUpNotes = ALLSTATS_57 .$txtLinks .ALLSTATS_58 .$pidCampaign;
        $pReqSendFilterCode = ' AND EXISTS (SELECT distinct idEmail FROM '.$idGroup.'_clickStats WHERE '.$idGroup.'_subscribers.idEmail='.$idGroup.'_clickStats.idEmail AND idCampaign='.$pidCampaign.$sqlLinks.')';
  	break;
      case 7: // Did NOT click a specific link
  		$pfollowUpNotes = ALLSTATS_59 .$txtLinks .ALLSTATS_58 .$pidCampaign;
          $pReqSendFilterCode = ' AND NOT EXISTS (SELECT distinct idEmail FROM '.$idGroup.'_clickStats WHERE '.$idGroup.'_subscribers.idEmail='.$idGroup.'_clickStats.idEmail AND idCampaign='.$pidCampaign.$sqlLinks.')';
      break;
      default:
      $pfollowUpNotes ="";
      $pReqSendFilterCode="";
    }
    $mySQL2= $mySQL2." ".$pReqSendFilterCode." ".$psendFilterCode;
    $subsResult = $obj->query($mySQL2);
    $rows 	    = $obj->num_rows($subsResult);
if ($rows) {
	//create a mew List'
	$mySQL="INSERT INTO ".$idGroup."_lists (listName, listDescription, isPublic, dateCreated, createdBy, idGroup) VALUES ('".$plistname."', '".$pfollowUpNotes."', 0, '".$myDay."', $sesIDAdmin, $idGroup)";
	$result = $obj->query($mySQL);
	$lastId =  $obj->insert_id();

	$mySQLi = str_ireplace("distinct ".$idGroup."_subscribers.idEmail","distinct ".$idGroup."_subscribers.idEmail, ".$idGroup.", ".$lastId,$mySQL2);
	$mySQL4="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idGroup, idList) ".$mySQLi;
	$result4	= $obj->query($mySQL4);
	//$count=$obj->affected_rows($result4);
	echo "ok";
} //for $rows
else {
	echo $rows;
}
$obj->closeDb();
?>