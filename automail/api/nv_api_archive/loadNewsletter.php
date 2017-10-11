<?php 
//nuevoMailer v.3.60
//Copyright 2013 Panagiotis Chourmouziadis
//http://www.designerfreesolutions.com
/* 
	For CROSS-DOMAIN REQUESTS, uncomment this line: header("Access-Control-Allow-Origin 
	and change with the URL from which you are triggering the subscriber insertion/removal (the external URL: http://www.external-url.com).
	You may add several such lines for all domains where you want to trigger the api.
*/
//header("Access-Control-Allow-Origin: http://www.external-url.com");
?>
<?php
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName               =	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);
$groupScriptUrl			=	$obj->getSetting("groupScriptUrl", $idGroup);
$groupContactEmail      =	$obj->getSetting("groupContactEmail", $idGroup);
$groupSite              =	$obj->getSetting("groupSite", $idGroup);
(isset($_GET['idNewsletter']))?$pidnewsletter = $_GET['idNewsletter']:$pidnewsletter="";
$pidnewsletter	= dbQuotes(dbProtect($pidnewsletter,25));
$pcharset="";
$fb="0";
// select latest one or a specific one
if ($pidnewsletter) {
	$mySQL2="SELECT idNewsletter, name, body, html, dateCreated, charset FROM ".$idGroup."_newsletters WHERE idNewsletter=$pidnewsletter";
} else {
	$mySQL2="SELECT idNewsletter, name, body, html, dateCreated, charset FROM ".$idGroup."_newsletters WHERE idNewsletter=(SELECT Max(idNewsletter) from ".$idGroup."_newsletters where  isPublic=-1 and sent=-1)";
}
$result2 = $obj->query($mySQL2);
$rows 	= $obj->num_rows($result2);
$row = $obj->fetch_array($result2);
if ($rows){
	$pidnewsletter 	= $row['idNewsletter'];
	$pidHtmlnewsletter = $pidnewsletter;
	$pname			= $row['name'];
	$pbody			= $row['body'];
	$phtml			= $row['html'];
	$dateCreated	= addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);
	$pcharset		= $row['charset'];
}
if (!$pcharset) {
	$pcharset=$groupGlobalCharset;
}
?>
<?php
if ($phtml==-1) {
	$exclude = array("<a href=\"optOutlink1\">", "<a href=\"optOutlink2\">", "<a href=\"optOutlink3\">", "<a href=\"friendforwardlink\">", "<a href=\"ratelink1\">", "<a href=\"ratelink2\">", "<a href=\"ratelink3\">", "<a href=\"ratelink4\">", "<a href=\"ratelink5\">", "<a href=\"webpagelink\">", "<a href=\"optOutlink3\">", "<a href=\"subconfirmationlink\">"); 
   	$pbody = str_ireplace($exclude, "", $pbody);
	// FB render
	if ((stripos($pbody, "#fblikefb#"))!=0) {	// there is an FB like button in the newsletter body.
		$pbody		= str_ireplace("#fblikefb#", "", $pbody);	//no rendering at this point.
		$fb=1;
		// FB render: prepare string
		$urlToLike=$groupScriptUrl.'/subscriber/newsletterArchive.php?idNewsletter='.$pidnewsletter;
		$fbStr='<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="'.$urlToLike.'" show_faces="false" width="450"></fb:like>';
	}
}
else {
	$pbody	= str_ireplace("#companyname#", $groupName, $pbody); 
   	$pbody	= str_ireplace("#contactemail#", $groupContactEmail, $pbody); 
   	$pbody	= str_ireplace("#companysite#", $groupSite, $pbody); 
	$pattern = '/(#)(.*?)(#)/'; //removes smartTags
	$pbody = preg_replace($pattern, "", $pbody);
	$pbody = "<PRE>".$pbody."</PRE>";
	}
?>
<div style="margin-left:100px;margin-top:40px;">
	<span style=" FONT-FAMILY: Tahoma, Arial, helvetica; FONT-SIZE: 16px; FONT-WEIGHT: bold; color:#565656;"><?php echo $pname.'&nbsp;&nbsp;</span><span>('.$dateCreated.')' ?></span>
	<?php if ($fb==1) {?>
		<!--div id="fblayer" style="padding:10px; display:inline; position:absolute; top:0px; right:0px; border:#000 1px solid; background:#ffffe0;"><?php echo $fbStr;?></div-->
		<div style="margin-top:10px;"><?php echo $fbStr ?></div>
	<?php } ?>
</div>

<div style="margin-left:100px;margin-top:5px;">
	<?php echo $pbody; ?>	
</div>