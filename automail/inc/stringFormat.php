<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

function dbProtect($input, $stringLength) {
	$input		= substr($input,0,$stringLength);
    $dirt = array("*", "=", "#", ";", "<", ">", "+", "%", "--", "<script>", "<SCRIPT>", "[SCRIPT]");
	$input = str_ireplace($dirt, "", $input);
	return $input;
}
function dbQuotes($input) {
    if (!get_magic_quotes_gpc()) {
        $input = addslashes($input);
    }
	return $input;
}
function dbQuotesArr($input) {
    if (!get_magic_quotes_runtime()) {
		$input = addslashes($input);
	}
	return $input;
}
function my_stripslashes($str) {
    if (get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return $str;
}
function fixJSstring($input) {
   $input = str_ireplace("'", "\'", $input);
   return	$input;
}
function addOffset($date, $shift, $groupDateTimeFormat) {
    //can be used to change dateTime format.
	if ($date){
		return date($groupDateTimeFormat, strtotime(+$shift.' hours', strtotime($date)));
		//return date("Y-m-d H:i:s" , strtotime(+$shift.' hours', strtotime($date)));	//originally
		//return date("m/d/y - h:i a" , strtotime(+$shift.' hours', strtotime($date)));
		//usage: addOffset($row['adminLastLogin'], $pTimeOffsetFromServer)
	}
}
function myDatenow() {
		return date("Y-m-d	H:i:s");
		//usage: $today = myDatenow();
}
function encodeHTML($sHTML) {
	$sHTML=str_ireplace("&","&amp;",$sHTML);
	$sHTML=str_ireplace("<","&lt;",$sHTML);
	$sHTML=str_ireplace(">","&gt;",$sHTML);
	return $sHTML;
}
function strForInput($strinput) {
	$strinput=str_replace('"', "&quot;", $strinput);
	return $strinput;
}

function subscriberTags($tempStr, $sub, $mailData) {
    $tempStr	= str_ireplace("#viewstracker#", '<img src="'.$mailData["groupScriptUrl"].'/inc/or.php?sid='.$sub["idEmail"].'&c='.$mailData["idCampaign"].'" width="1" height="1"></body></html>', $tempStr);
    $tempStr	= str_ireplace("#subID#",       $sub["idEmail"], $tempStr);
    $tempStr	= str_ireplace("#subemail#",    $sub["email"], $tempStr);
    $tempStr	= str_ireplace("#subname#",     $sub["name"], $tempStr);
    $tempStr	= str_ireplace("#sublastname#", $sub["lastName"], $tempStr);
    $tempStr	= str_ireplace("#subcompany#",  $sub["subCompany"], $tempStr);
	$tempStr	= str_ireplace("#subaddress#",  $sub["address"], $tempStr);
	$tempStr	= str_ireplace("#subcity#",     $sub["city"], $tempStr);
	$tempStr	= str_ireplace("#substate#",    $sub["state"], $tempStr);
	$tempStr	= str_ireplace("#subzip#",      $sub["zip"], $tempStr);
    $tempStr	= str_ireplace("#subcountry#",  $sub["country"], $tempStr);
    $tempStr	= str_ireplace("#subphone1#",   $sub["subPhone1"], $tempStr);
    $tempStr	= str_ireplace("#subphone2#",   $sub["subPhone2"], $tempStr);
    $tempStr	= str_ireplace("#submobile#",   $sub["subMobile"], $tempStr);
    $tempStr	= str_ireplace("#subpasscode#", $sub["subPassword"], $tempStr);
    $tempStr	= str_ireplace("#subdatesubscribed#",$sub["dateSubscribed"], $tempStr);
    $tempStr	= str_ireplace("#subBirthday#",     $sub["subBirthDay"], $tempStr);
    $tempStr	= str_ireplace("#subBirthmonth#",   $sub["subBirthMonth"], $tempStr);
    $tempStr	= str_ireplace("#subBirthyear#",    $sub["subBirthYear"], $tempStr);
    $tempStr	= str_ireplace("#subdatelastupdated#",$sub["dateLastUpdated"], $tempStr);
    $tempStr	= str_ireplace("#subdatelastemailed#",$sub["dateLastEmailed"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield1#",$sub["customSubField1"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield2#",$sub["customSubField2"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield3#",$sub["customSubField3"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield4#",$sub["customSubField4"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield5#",$sub["customSubField5"], $tempStr);
    $tempStr	= str_ireplace("#companyname#",$mailData["groupName"], $tempStr);
    $tempStr	= str_ireplace("#companysite#", $mailData["groupSite"], $tempStr);
	$tempStr	= str_ireplace("#date_time#", $mailData["date_time"], $tempStr);
	$tempStr	= str_ireplace("#full_date#", $mailData["date_time_2"], $tempStr);
    $tempStr	= str_ireplace("#contactemail#",'mailto:'.$mailData["groupContactEmail"], $tempStr);
    return $tempStr;
}
function otherHtmlTags($tempStr, $sub, $mailData) {
	//if ($sub && $mailData) {
    $tempStr	= str_ireplace("friendforwardlink",$mailData["groupScriptUrl"].'/subscriber/forwardToMyFriends.php?c='.$mailData["idCampaign"].'&e2='.$sub["email2"].'&h='.$mailData["idHtmlNewsletter"].'&t='.$mailData["idTextNewsletter"], $tempStr);
    $tempStr	= str_ireplace("ratelink",$mailData["groupScriptUrl"].'/subscriber/rating.php?c='.$mailData["idCampaign"].'&e2='.$sub["email2"].'&h='.$mailData["idHtmlNewsletter"].'&t='.$mailData["idTextNewsletter"].'&r=', $tempStr);
	$tempStr	= str_ireplace("webpagelink", $mailData["groupScriptUrl"].'/subscriber/newsletter.php?sid='.$sub["idEmail"].'&c='.$mailData["idCampaign"].'&h='.$mailData["idHtmlNewsletter"].'&t='.$mailData["idTextNewsletter"], $tempStr);
    $tempStr	= str_ireplace("subconfirmationlink",$mailData["groupScriptUrl"].'/subscriber/confirm.php?c='.$mailData["idCampaign"].'&e2='.$sub["email2"].'&psw='.$sub["subPassword"], $tempStr);
    $tempStr	= str_ireplace("optoutlink", $mailData["groupScriptUrl"].'/subscriber/optOut.php?c='.$mailData["idCampaign"].'&e2='.$sub["email2"].'&a=', $tempStr);
	$tempStr	= str_ireplace("#fblikefb#", '<a href="'.$mailData["groupScriptUrl"].'/subscriber/newsletter.php?fb=1&sid='.$sub["idEmail"].'&c='.$mailData["idCampaign"].'&h='.$mailData["idHtmlNewsletter"].'&t='.$mailData["idTextNewsletter"].'"><img src="'.$mailData["groupScriptUrl"].'/inc/facebook.png" width="36" height="36" border="0"></a>', $tempStr);
	//}
return $tempStr;
}
function otherTextTags($tempStr, $sub, $mailData) {
    $tempStr	= str_ireplace("#friendforwardlink#",$mailData["groupScriptUrl"].'/subscriber/forwardToMyFriends.php?c='.$mailData["idCampaign"].'&e2='.$sub["email2"].'&h='.$mailData["idHtmlNewsletter"].'&t='.$mailData["idTextNewsletter"], $tempStr);
    $tempStr	= str_ireplace("#subconfirmationlink#",$mailData["groupScriptUrl"].'/subscriber/confirm.php?c='.$mailData["idCampaign"].'&e2='.$sub["email2"].'&psw='.$sub["subPassword"], $tempStr);
    $tempStr	= str_ireplace("optoutlink", $mailData["groupScriptUrl"].'/subscriber/optOut.php?c='.$mailData["idCampaign"].'&e2='.$sub["email2"].'&a=', $tempStr);
    $tempStr	= str_ireplace("#webpagelink#", $mailData["groupScriptUrl"].'/subscriber/newsletter.php?sid='.$sub["idEmail"].'&c='.$mailData["idCampaign"].'&h='.$mailData["idHtmlNewsletter"].'&t='.$mailData["idTextNewsletter"], $tempStr);
    return $tempStr;
}
function tracklinkstext($tempStr, $sub, $mailData) {
    $tempStr	= str_ireplace("tracklink",$mailData["groupScriptUrl"].'/inc/rdr.php?r='.$sub["idEmail"].'c'.$mailData["idCampaign"].'c', $tempStr);
    return $tempStr;
}

function confirmationdata($tempStr, $sub, $mailData) {
    $tempStr	= str_ireplace("#subemail#",    $sub["email"], $tempStr);
    $tempStr	= str_ireplace("#subname#",     $sub["name"], $tempStr);
    $tempStr	= str_ireplace("#sublastname#", $sub["lastName"], $tempStr);
    $tempStr	= str_ireplace("#subpasscode#", $sub["subPassword"], $tempStr);
    $tempStr	= str_ireplace("#subconfirmationlink#", "subconfirmationlink", $tempStr);
    $tempStr	= str_ireplace("#unsubscribelink#", "unsubscribelink", $tempStr);
    $tempStr	= str_ireplace("subconfirmationlink",$mailData["groupScriptUrl"].'/subscriber/confirm.php?e2='.$sub["email2"].'&psw='.$sub["subPassword"], $tempStr);
    $tempStr	= str_ireplace("unsubscribelink",$mailData["groupScriptUrl"].'/subscriber/optOut.php?e2='.$sub["email2"]."&a=2", $tempStr);
    $tempStr	= str_ireplace("#companyname#",$mailData["groupName"], $tempStr);
    $tempStr	= str_ireplace("#companysite#", $mailData["groupSite"], $tempStr);
    $tempStr	= str_ireplace("#contactemail#",'mailto:'.$mailData["groupContactEmail"] , $tempStr);
    $tempStr	= str_ireplace("#listlisting#",$mailData["listListing"], $tempStr);
    $tempStr	= str_ireplace("#listlistingT#",$mailData["listListingT"], $tempStr);
	//added in v.300
    $tempStr	= str_ireplace("#subcompany#",  $sub["subCompany"], $tempStr);
	$tempStr	= str_ireplace("#subaddress#",  $sub["address"], $tempStr);
	$tempStr	= str_ireplace("#subcity#",     $sub["city"], $tempStr);
	$tempStr	= str_ireplace("#substate#",    $sub["state"], $tempStr);
	$tempStr	= str_ireplace("#subzip#",      $sub["zip"], $tempStr);
    $tempStr	= str_ireplace("#subcountry#",  $sub["country"], $tempStr);
    $tempStr	= str_ireplace("#subphone1#",   $sub["subPhone1"], $tempStr);
    $tempStr	= str_ireplace("#subphone2#",   $sub["subPhone2"], $tempStr);
    $tempStr	= str_ireplace("#submobile#",   $sub["subMobile"], $tempStr);
    $tempStr	= str_ireplace("#subBirthday#",     $sub["subBirthDay"], $tempStr);
    $tempStr	= str_ireplace("#subBirthmonth#",   $sub["subBirthMonth"], $tempStr);
    $tempStr	= str_ireplace("#subBirthyear#",    $sub["subBirthYear"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield1#",$sub["customSubField1"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield2#",$sub["customSubField2"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield3#",$sub["customSubField3"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield4#",$sub["customSubField4"], $tempStr);
    $tempStr	= str_ireplace("#subcustomsubfield5#",$sub["customSubField5"], $tempStr);
	$tempStr	= str_ireplace("#date_time#", $mailData["date_time"], $tempStr);



return $tempStr;
}
function getBodyFromUrl($url, $charset) {
 /*   $handle      = fopen($url, "b");
    // HAS BUGS / NOT SUPPORTED YET:
    $handle      = stream_encoding($handle, $charset);
    $pHtmlBody   = fread($handle, 80000);  //GOOD line */
    //file_get_contents is better. Defaults to uf-8. Can be fixed with stream_default_encoding (later in PHP 6)
    // or by adding context params
    $contextParams = array(
        'http'=>array(
        'header'=>"Content-type: html/multipart; charset=$charset"
        )
        );
    $context = stream_context_create($contextParams);
    $pHtmlBody = file_get_contents($url, false, $context);
    //mbstring MUST BE ENABLED AT SERVER. if not check: http://nl3.php.net/manual/en/function.mb-convert-encoding.php#89029
    //$pHtmlBody = mb_convert_encoding($pHtmlBody, "UTF-8", "UTF-7");

    return $pHtmlBody;
}

function assignToLists($pidemail, $pemail, $listsTicked, $idGroup){
    $obj = new db_class();
    if ($listsTicked) {
        for ($z=0; $z<$listsTicked; $z++)  {
            $zList = dbQuotes(dbProtect($_POST['idlist'][$z],10));
            //## update also his list assignments
            $mySQL2="SELECT idEmail FROM ".$idGroup."_listRecipients WHERE idEmail=".$pidemail." AND idList=".$zList;
            $result = $obj->query($mySQL2);
            if ($obj->num_rows($result)!=1) {
                $mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$pidemail.", ".$zList.", ".$idGroup.")";
                $obj->query($mySQL3);
            }
        //## REMOVE HIM FROM THE OPT-OUT TABLE FOR THE LISTS HE SELECTED
        $mySQL4="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$pemail."' AND optOutType='".$zList."'";
        $obj->query($mySQL4);
        }
    }
    //## REMOVE HIM FROM THE OPT-OUT TABLE FOR ALL OPT-OUTS / GLOBAL
    $mySQL5="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$pemail."' AND optOutType='g'";
    $obj->query($mySQL5);
}
function extractPicNames ($string) {
    $inlineImages="";
    $pattern="((/assets/)+[0-9A-Za-z_\-\ ]+(.[jJ][pP][gG]|.[gG][iI][fF]|.[pP][nN][gG]|.[jJ][pP][eE][gG]))";
    preg_match_all($pattern, $string, $matches,  PREG_SET_ORDER);
    foreach ($matches as $key) {
    	@$inlineImages .= $key[0].',';
      }
    @$inlineImages=rtrim($inlineImages, ",");
    return $inlineImages;   //=>  /assets/pic1.ext,assets/pic2.ext
}
function forDemo($url, $message) {
}
function forDemo2($message) {
}
?>