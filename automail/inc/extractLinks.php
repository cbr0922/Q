<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
function extractLinks($tempStr, $mailData, $trackMails) {
    $obj2 = new db_class();
    $i=0;
    $k=0;
	$tempStr =  str_ireplace("<a", "<a", $tempStr);
	$tempStr =  str_ireplace("href", "href", $tempStr);
    $linkToAdd = $mailData["groupScriptUrl"].'/inc/rdr.php?r=#subID#c'.$mailData["idCampaign"].'c';
    $Pattern = '/(href=")(.+?)(")/';

    if (preg_match_all($Pattern, $tempStr, $matches)) {

    foreach($matches[0] as $link){
        $matchHref[]=$matches[1][$i].$matches[2][$i].$matches[3][$i];
        $matchLink[]=$matches[2][$i];
        $i=$i+1;
    }
    $entries= sizeof($matchLink);
    } else {return $tempStr;die;}
//print_r($matchLink);
    for ($k=0; $k<$entries; $k++)  {
           if ((stripos($matchLink[$k], "#"))!==0 && stripos($matchLink[$k], "fonts.googleapis.com")===false && stripos($matchLink[$k], "java")===false && stripos($matchLink[$k], "subconfirmationlink")===false && stripos($matchLink[$k], "fblinkfb")===false && stripos($matchLink[$k], "webpagelink")===false && stripos($matchLink[$k], "ratelink")===false && stripos($matchLink[$k], ".css")===false && stripos($matchLink[$k], "friendforwardlink")===false  && stripos($matchLink[$k], "optoutlink")===false) {
              $mySQL="SELECT idLink FROM ".$mailData["idGroup"]."_links WHERE linkUrl=\"$matchLink[$k]\"";
              $result	= $obj2->query($mySQL);
              $row = $obj2->fetch_array($result);
              $idLink = $row['idLink'];
              if (!$idLink) {
                  $mySQL2="insert into ".$mailData["idGroup"]."_links (linkUrl, idGroup) VALUES ('".dbQuotes($matchLink[$k])."', ".$mailData["idGroup"].")";
                  $result2	= $obj2->query($mySQL2);
                  $idLink=$obj2->insert_id();
              }
              if ($trackMails=="0" && stripos($matchLink[$k], "mailto:")!==false) {
                    $tempStr = $tempStr;
              }
              else {
                    $tempStr =  str_ireplace($matchHref[$k], 'href="'.$linkToAdd.$idLink.'"', $tempStr);
              }

        } //if for excluded links
    }
return $tempStr;
}

// Used without link tracking --> For GA tracking
function addGoogleTracking($tempStr, $mailData) {
    $i=0;
    $k=0;
	$tempStr =  str_ireplace("<a", "<a", $tempStr);
	$tempStr =  str_ireplace("href", "href", $tempStr);
    $Pattern = '/(href=")(.+?)(")/';

    if (preg_match_all($Pattern, $tempStr, $matches)) {

    foreach($matches[0] as $link){
        $matchHref[]=$matches[1][$i].$matches[2][$i].$matches[3][$i];
        $matchLink[]=$matches[2][$i];
        $i=$i+1;
    }
    $entries= sizeof($matchLink);
    } else {return $tempStr;die;}
	$gtracking=createGoogleString($mailData);	//build the GA string
	for ($k=0; $k<$entries; $k++)  {
           if ((stripos($matchLink[$k], "#"))!==0 && stripos($matchLink[$k], "mailto:")===false && stripos($matchLink[$k], "java")===false && stripos($matchLink[$k], "subconfirmationlink")===false && stripos($matchLink[$k], "fblinkfb")===false && stripos($matchLink[$k], "webpagelink")===false && stripos($matchLink[$k], "ratelink")===false && stripos($matchLink[$k], ".css")===false && stripos($matchLink[$k], "friendforwardlink")===false  && stripos($matchLink[$k], "optoutlink")===false) {

              if (!empty($gtracking) && stripos($matchLink[$k], "?")!==false) {
                    $tempStr =  str_ireplace($matchHref[$k], 'href="'.$matchLink[$k]."&".$gtracking.'"', $tempStr);
              }
              else if (!empty($gtracking) && stripos($matchLink[$k], "?")===false) {
                    $tempStr =  str_ireplace($matchHref[$k], 'href="'.$matchLink[$k]."?".$gtracking.'"', $tempStr);
              }

              else { $tempStr =  $tempStr;}

        } //if for excluded links
    }
return $tempStr;
}
?>