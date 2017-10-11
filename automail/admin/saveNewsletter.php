<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
include('../inc/sendMail.php');
include('../inc/extractLinks.php');
include('../inc/encryption.php');
include('sendNewsletter.php');
$obj = new db_class();
$groupScriptUrl		=	$obj->getSetting("groupScriptUrl", $idGroup);
$groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-Type: text/html; charset="'.$groupGlobalCharset.'"'); 
@$pcharset			=	$_REQUEST['charset'];
$myDay 				= myDatenow();
@$ptype	 			= $_REQUEST['type']; //html :-1, 'text 0
@$pSubject 	   		= $_REQUEST['subject'];
$pSubject	   		= dbQuotes($pSubject);
@$pbody				= $_REQUEST['pbody'];
$pbody				= dbQuotes($pbody);
@$pidNewsletter 	= $_REQUEST['idNewsletter'];
@$paction			= $_REQUEST['action'];
@$pattachments	    = $_REQUEST['attachments'];
@$pinlineimages		= $_REQUEST['inlineImages'];
@$pselectedemail	= $_REQUEST['selectedemail'];
@$pselectedname		= $_REQUEST['selectedname'];

// Save request for an existing newsletter
if (strtolower($paction)=="save") {
	$mySQL="UPDATE ".$idGroup."_newsletters SET name='$pSubject', body='$pbody', attachments='$pattachments', inlineImages='$pinlineimages', charset='$pcharset' WHERE idNewsletter=$pidNewsletter";
	$obj->query($mySQL);
	echo "ok";
}
//When Save and exit for an existing newsletter
if (strtolower($paction)=="saveexit") {
	//$pbody=wordwrap($pbody, 70); no difference for html ones. must try with text ones.
	$mySQL="UPDATE ".$idGroup."_newsletters SET name='$pSubject', body='$pbody', attachments='$pattachments', inlineImages='$pinlineimages', charset='$pcharset' WHERE idNewsletter=$pidNewsletter";
	$obj->query($mySQL);
	echo "ok";
}
// When save for first time for a new newsletter
if (strtolower($paction)=="savefirsttimeexit") {
	$mySQL="INSERT INTO ".$idGroup."_newsletters (idGroup, name, body, html, isPublic, dateCreated, attachments, inlineImages, createdBy, charset) VALUES ($idGroup, '$pSubject', '$pbody', $ptype, 0, '$myDay', '$pattachments', '$pinlineimages', $sesIDAdmin, '$pcharset')";
	$obj->query($mySQL);
	echo "ok";
}
// When Save a copy for an existing newsletter
if (strtolower($paction)=="savecopy") {
	$pre=SENDNEWSLETTERFORM_43;
	$mySQL="INSERT INTO ".$idGroup."_newsletters (idGroup, name, body, html, isPublic, dateCreated, attachments, inlineImages, charset, createdBy) VALUES ($idGroup, '$pre $pSubject', '$pbody', $ptype, 0, '$myDay', '$pattachments', '$pinlineimages','$pcharset', $sesIDAdmin)";
	$obj->query($mySQL);
	echo "ok";
}
// When creating a text version
if (strtolower($paction)=="savetextcopy") {
	//$pbody 	= strip_tags($pbody);
	//$pbody = str_ireplace("\r\n", "", $pbody);
	$pSubject = SENDNEWSLETTERFORM_3 .$pSubject;
	$mySQL="INSERT INTO ".$idGroup."_newsletters (idGroup, name, body, html, attachments, dateCreated, isPublic, createdBy, charset) VALUES ($idGroup, '$pSubject', '$pbody', 0, '$pattachments', '$myDay', 0, $sesIDAdmin, '$pcharset')";
	$obj->query($mySQL);
	echo "ok";
}
// When creating a TEMPLATE
if (strtolower($paction)=="savetemplate") {
	$pfileExt = '.html';
	$pFileName = base64_encode($_REQUEST['subject']);	//$pSubject
/*	if(preg_match('/[^\x20-\x7f]/', $pFileName)) { //string has non ascii chars
		$tmpl_date=date("d").date("H").date("i").date("s");
   		$pFileName="Template_".$tmpl_date; }*/ 
	$MyFile = $pFileName.$pfileExt;
  	$handle 	= fopen("../assets/".$MyFile, "w");	
 	fwrite($handle, $_REQUEST['pbody']);	//$pbody
	fclose($handle);
	echo '<img src="./images/doneOk.png">&nbsp;'.fixJSstring(FILEMANAGER_14);
	echo '&nbsp;<a target="blank" href="'.$groupScriptUrl.'/assets/'.$pFileName.$pfileExt.'">'.base64_decode($pFileName).$pfileExt.'</a>';
}
if (strtolower($paction)=="sendtest") {
	//first save the newsletter
	$mySQL="UPDATE ".$idGroup."_newsletters SET name='$pSubject', body='$pbody', attachments='$pattachments', inlineImages='$pinlineimages', charset='$pcharset' WHERE idNewsletter=$pidNewsletter";
	$obj->query($mySQL);
	sendTestCampaign($pselectedemail, $pselectedname, $pidNewsletter, $ptype,$idGroup);
	echo "ok";
}
?>