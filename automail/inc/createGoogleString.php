<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
function createGoogleString($mailData) {
    $gtrackingSTR="";$strTerm="";$strContent="";
	$obj2 = new db_class();
	$mySQL="Select ga_utm_source, ga_utm_medium, ga_utm_campaign, ga_utm_term, ga_utm_content FROM ".$mailData["idGroup"]."_campaigns WHERE idCampaign=".$mailData["idCampaign"];
	$result	= $obj2->query($mySQL);
	$row = $obj2->fetch_array($result);
	$ga_utm_source	= $row['ga_utm_source'];
	$ga_utm_medium	= $row['ga_utm_medium'];
	$ga_utm_campaign= $row['ga_utm_campaign'];
	$ga_utm_term	= $row['ga_utm_term'];
	$ga_utm_content	= $row['ga_utm_content'];

	if ($ga_utm_term<>"") {
		$strTerm = 	"&utm_term=".$ga_utm_term;
	}
	if ($ga_utm_content<>"") {
		$strContent = "&utm_content=".$ga_utm_content;
	}

	if ($ga_utm_source<>"") {	//we have google tracking
		$gtrackingSTR="utm_source=".$ga_utm_source."&utm_medium=".$ga_utm_medium."&utm_campaign=".$ga_utm_campaign.$strTerm.$strContent;
	}
	else {
		$gtrackingSTR="";
	}
	return $gtrackingSTR;
}
?>