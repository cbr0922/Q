<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

// $check: 0 only checks. 1 full count
function mailCount($pprefers, $pconfirmed, $justLists, $pidSendFilter, $pidEmailToStart, $check, $idGroup, $joins) {
    $obj = new db_class();
    $pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
    $pBday 	    = date("j", strtotime("+$pTimeOffsetFromServer hours"));
    $pBmonth 	= date("n", strtotime("+$pTimeOffsetFromServer hours"));
    $pNOW       = date("Y-m-d H:i:s", strtotime("+$pTimeOffsetFromServer hours"));

switch ($pprefers) {
    case 1:
        $prefSQL = ' AND '.$idGroup.'_subscribers.prefersHtml=-1';
        break;
    case 2:
        $prefSQL = ' AND '.$idGroup.'_subscribers.prefersHtml=0';
        break;
    default:
        $prefSQL='';
}
switch ($pconfirmed) {
    case 1:
	    $confSQL = ' AND '.$idGroup.'_subscribers.confirmed=-1';
	    break;
	case 2:
		$confSQL = ' AND '.$idGroup.'_subscribers.confirmed=0';
	    break;
	default:
		$confSQL='';
}

$filterSQL="";
if ($pidSendFilter!=0) {
	$filterSQL  = getSendFilterCode($pidSendFilter, $idGroup);
    $filterSQL  = str_replace("##pBday##", $pBday, $filterSQL);
    $filterSQL  = str_replace("##pBmonth##", $pBmonth, $filterSQL);
    $filterSQL  = str_replace("##now##", "'".$pNOW."'", $filterSQL);
}
$mLists=$justLists;

$JOINSQL ="";
if ($joins=="y") {
	if ($mLists!="") {
		$JOINSQL = " AND EXISTS (select idEmail FROM ".$idGroup."_listRecipients WHERE idList in ($mLists) AND ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail)";
	}
	else {
	 	$JOINSQL = " AND EXISTS (select idEmail FROM ".$idGroup."_listRecipients WHERE ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail)";	
	}
}

If (ceil($check)==1) {			//FULL COUNT, all OR one list
	//echo ("<br><br>full<br><br>");
    $mySQL2="SELECT count(".$idGroup."_subscribers.idEmail) as recs FROM ".$idGroup."_subscribers 
	WHERE emailIsBanned=0 AND emailIsValid=-1 AND ".$idGroup."_subscribers.idEmail>$pidEmailToStart ".$prefSQL .$confSQL .$JOINSQL ." ".$filterSQL;
	//$result = $obj->query($mySQL2);
	//$rows 	= $obj->num_rows($result);
	$rows= $obj->get_rows($mySQL2);	
}
if (ceil($check)==0) {			//JUST CHECK FOR RECORDS	
	//echo ("<br><br>check<br><br>");
    $mySQL2="SELECT (".$idGroup."_subscribers.idEmail) FROM ".$idGroup."_subscribers 
	WHERE emailIsBanned=0 AND emailIsValid=-1 AND ".$idGroup."_subscribers.idEmail>$pidEmailToStart ".$prefSQL .$confSQL .$JOINSQL ." ".$filterSQL." LIMIT 1";
	$result = $obj->query($mySQL2);
	$rows 	= $obj->num_rows($result);	// returns 0 or 1.
}
//echo "<br>".$mySQL2 ."<br><br>";
return $rows;
}
?>