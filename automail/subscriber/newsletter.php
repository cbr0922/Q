<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../inc/extractLinks.php');

(isset($_GET['sid']))?$subId = dbQuotes(dbProtect($_GET['sid'],15)):$subId="";
(isset($_GET['c']))?$idCampaign = dbQuotes(dbProtect($_GET['c'],10)):$idCampaign="";
(isset($_GET['fb']))?$fb = dbQuotes(dbProtect($_GET['fb'],1)):$fb=0;
(isset($_GET['h']))?$idHtmlNewsletter = dbQuotes(dbProtect($_GET['h'],5)):$idHtmlNewsletter="";
(isset($_GET['t']))?$idTextNewsletter = dbQuotes(dbProtect($_GET['t'],5)):$idTextNewsletter="";

$groupGlobalCharset             =	$obj->getSetting("groupGlobalCharset", $idGroup);
$groupEncryptionPassword        =	$obj->getSetting("groupEncryptionPassword", $idGroup);
$groupActiveLinkTrackingText    =	$obj->getSetting("groupActiveLinkTrackingText", $idGroup);
$groupActiveLinkTrackingHtml    =	$obj->getSetting("groupActiveLinkTrackingHtml", $idGroup);
$trackMails                     =   $obj->getSetting("groupTrackMailTo", $idGroup);
$pTimeOffsetFromServer 			=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$myDay  = myDatenow();
$pIP    = $_SERVER['REMOTE_ADDR'];

$mailData["groupScriptUrl"]     =	$obj->getSetting("groupScriptUrl", $idGroup);
$mailData["groupSite"]          =	$obj->getSetting("groupSite", $idGroup);
$mailData["groupName"]          =	$obj->getSetting("groupName", $idGroup);
$mailData["groupContactEmail"]  =   $obj->getSetting("groupContactEmail", $idGroup);
$mailData["idGroup"]            =   $idGroup;
$mailData["idHtmlNewsletter"]   = 	$idHtmlNewsletter;
$mailData["idTextNewsletter"]   = 	$idTextNewsletter;
$mailData["idCampaign"]         = 	$idCampaign;

$sub["email2"]="";
$sub["idEmail"]="";
$sub["subPassword"]="";

if ($subId) {
	$mySQL="SELECT * FROM ".$idGroup."_subscribers WHERE idEmail=$subId";
	$result = $obj->query($mySQL);
    $rowSub = $obj->fetch_array($result);
    $sub["idEmail"]         = $rowSub['idEmail'];
    $sub["email"]           = $rowSub['email'];
    $sub["email2"]          = myEncrypt($rowSub['email'], $groupEncryptionPassword);
    $sub["name"]            = $rowSub['name'];
    $sub["lastName"]        = $rowSub['lastName'];
    $sub["subCompany"]      =  $rowSub['subCompany'];
    $sub["address"]         =  $rowSub['address'];
    $sub["city"]            =  $rowSub['city'];
    $sub["state"]           =  $rowSub['state'];
    $sub["zip"]             =  $rowSub['zip'];
    $sub["country"]         =  $rowSub['country'];
    $sub["subPhone1"]       =  $rowSub['subPhone1'];
    $sub["subPhone2"]       =  $rowSub['subPhone2'];
    $sub["subMobile"]       =  $rowSub['subMobile'];
    $sub["subPassword"]     =  $rowSub['subPassword'];
    $sub["prefersHtml"]     =  $rowSub['prefersHtml'];  //?
    $sub["dateSubscribed"]  =  $rowSub['dateSubscribed'];
    $sub["subBirthDay"]     =  $rowSub['subBirthDay'];
    $sub["subBirthMonth"]   =  $rowSub['subBirthMonth'];
    $sub["subBirthYear"]    =  $rowSub['subBirthYear'];
    $sub["dateLastUpdated"] =  $rowSub['dateLastUpdated'];
    $sub["dateLastEmailed"] =  $rowSub['dateLastEmailed'];
    $sub["customSubField1"] =  $rowSub['customSubField1'];
    $sub["customSubField2"] =  $rowSub['customSubField2'];
    $sub["customSubField3"] =  $rowSub['customSubField3'];
    $sub["customSubField4"] =  $rowSub['customSubField4'];
    $sub["customSubField5"] =  $rowSub['customSubField5'];
}
else {
    $sub["idEmail"]         = "";
    $sub["email"]           = "";
    $sub["email2"]          = "";
    $sub["name"]            = "";
    $sub["lastName"]        = "";
    $sub["subCompany"]      = "";
    $sub["address"]         = "";
    $sub["city"]            = "";
    $sub["state"]           = "";
    $sub["zip"]             = "";
    $sub["country"]         = "";
    $sub["subPhone1"]       = "";
    $sub["subPhone2"]       = "";
    $sub["subMobile"]       = "";
    $sub["subPassword"]     = "";
    $sub["prefersHtml"]     = "";
    $sub["dateSubscribed"]  = "";
    $sub["subBirthDay"]     = "";
    $sub["subBirthMonth"]   = "";
    $sub["subBirthYear"]    = "";
    $sub["dateLastUpdated"] = "";
    $sub["dateLastEmailed"] = "";
    $sub["customSubField1"] = "";
    $sub["customSubField2"] = "";
    $sub["customSubField3"] = "";
    $sub["customSubField4"] = "";
    $sub["customSubField5"] = "";
}
//update openRate table
if ($idCampaign && $subId) {
    $mySQL="INSERT into ".$idGroup."_viewStats (idEmail, idCampaign, ipOpened, dateOpened, idGroup) VALUES ($subId, $idCampaign, '".$pIP."', '".$myDay."', $idGroup)";
    $obj->query($mySQL);
}
// update clickStats table with fb link
if ($subId && $fb==1 && $idCampaign ) {
	$mySQLf="Insert into ".$idGroup."_clickStats (idEmail, idCampaign, idLink, linkUrl, ipClicked, dateClicked, idGroup) VALUES ($subId, $idCampaign, 0, 'Facebook like', '$pIP', '$myDay', $idGroup)";
	$obj->query($mySQLf);
}

// campaign data
if ($idCampaign) {
    $mySQL="SELECT emailSubject, urlToSend, idList FROM ".$idGroup."_campaigns WHERE idGroup=$idGroup AND idCampaign=$idCampaign";
    $result	= $obj->query($mySQL);
    $row    = $obj->fetch_array($result);
    $mailData["idList"]=$row['idList'];
}
$ptype="";
if ($idHtmlNewsletter) {$idNewsletter=$idHtmlNewsletter;}
elseif (!$idHtmlNewsletter && $idTextNewsletter){$idNewsletter=$idTextNewsletter;}
elseif (!$idTextNewsletter && !$idHtmlNewsletter && $idCampaign){ //url
    $psubject    = $row['emailSubject'];
    $pBody      = getBodyFromUrl($row['urlToSend'], $groupGlobalCharset);
    $idNewsletter="";
    $ptype=-1;
}
if ($idNewsletter) {
    $mySQLn="SELECT name, body, html, charset, dateSent FROM ".$idGroup."_newsletters WHERE idNewsletter=$idNewsletter";
    $result1   	= $obj->query($mySQLn);
    $row1      	= $obj->fetch_array($result1);
    $psubject  	= $row1['name'];
    $pBody     	= $row1['body'];
    $ptype     	= $row1['html'];
    $pcharset  	= $row1['charset'];
	$dateSent	= $row1['dateSent'];
}

$mailData["date_time_2"]		= date("l d, F Y" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));

if (!empty($dateSent)) {
	$mailData["date_time"]=$dateSent;
	$mailData["date_time_2"]		= date("l d, F Y" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($dateSent)));
} else {
	$mailData["date_time"]=$myDay;
	$mailData["date_time_2"]		= date("l d, F Y" , strtotime(+$pTimeOffsetFromServer.' hours', strtotime($myDay)));
}

if ($subId) {
    $psubject	= str_ireplace("#subname#",     $sub["name"], $psubject);
    $psubject	= str_ireplace("#sublastname#", $sub["lastName"], $psubject);
}
if ($ptype==-1) {       //html newsletter or URL
	// FB render
	if ((stripos($pBody, "#fblikefb#"))!=0) {	// there is an FB like button in the newsletter body.
		$pBody		= str_ireplace("#fblikefb#", "", $pBody);	//no rendering at this point.
		$fb=1;
		// FB render: prepare string
		$urlToLike=$mailData["groupScriptUrl"].'/subscriber/newsletter.php?sid=0&c='.$idCampaign.'&t='.$idTextNewsletter.'&h='.$idNewsletter;
		$fbStr='<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="'.$urlToLike.'" show_faces="false" width="450"></fb:like>';
	}
    if ($subId && $groupActiveLinkTrackingHtml=="-1" && $idCampaign) {
        $pBody  = extractLinks($pBody, $mailData, $trackMails);
    }
    $pBody  = subscriberTags($pBody, $sub, $mailData);
    $pBody 	= otherHtmlTags($pBody, $sub, $mailData);
	$pBody		= str_ireplace('nvhide=""', 'style="display:none"', $pBody);
}
?>
<div style="margin-left:200px;border:#000000 0px solid;background:#fff;">
	<span style=" FONT-FAMILY: Arial, helvetica; FONT-SIZE: 20px; FONT-WEIGHT: bold; color:#565656;"><?php echo $mailData["groupName"] ?></span>
</div>
<div style="margin-left:200px;margin-top:20px;border:#000000 0px solid;background:#fff;">
	<font size=4 face=tahoma><?php echo $psubject ?></font>
	<?php if ($fb==1) {?>
		<div id="fblayer" style="padding:10px; display:inline; position:absolute; top:0px; right:0px; border:#000 1px solid; background:#ffffe0;"><?php echo $fbStr;?></div>
		<div style="margin-top:10px;">
			<?php echo $fbStr ?>
		</div>
	<?php } ?>
</div>
<div style="margin-left:200px;margin-top:20px;">
	<?php echo $pBody; ?>
</div>

<?php if (@$pdemomode) { ?>
	<div align="center" style="margin-top:50px; border-top:#888 1px solid">
		<span style="color:#000;font-size:12px;font-family:Verdana, Arial">This is a demonstration of nuevoMailer.</span>
		<br><a target="blank" href="http://www.nuevomailer.com?demo"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">Click here to learn more.</span></a>
		<div style="margin-top:12px"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">&copy; <?php echo date('Y')?> - DesignerFreeSolutions.com</span></div>
	</div>
<?php   } ?>

<!--
</body>
</html>
-->