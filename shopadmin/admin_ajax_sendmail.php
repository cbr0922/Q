<?php
//include_once "Check_Admin.php";
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$Pub_id        = intval($FUNCTIONS->Value_Manage($_GET['pub_id'],$_POST['pub_id'],'back',''));
//***************************************************************************************************************************
include_once "SMTP.Class.inc.php";
include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
//***************************************************************************************************************************
//echo $mailstr = $_GET['mailstr'];
$Query = $DB->query(" select publication_title,publication_start_time,publication_end_time,publication_content  from `{$INFO[DBPrefix]}publication` where publication_id=".intval($Pub_id)." limit 0,1"
);
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$publication_title        =  $Result['publication_title'];
	$publication_start_time   =  $Result['publication_start_time'];
	$publication_end_time     =  $Result['publication_end_time'];
	$publication_content      =  $Result['publication_content'];
	$publication_alreadyread  =  $Result['publication_alreadyread'];
	
}else{
	exit;
}
$DB->free_result($Query);
unset ($Query);
unset ($Num);
unset ($Result);

$mailno = $_GET['mailno'];
$permailnum = $_GET['persendnum'];
$firstno = ($mailno-1)*$permailnum;
$mail_array = array();
$j=0;
 $i = 0;
 $mail_array = array();
 $Query = $DB->query(" select mail from `{$INFO[DBPrefix]}falsemail` where mail<>'' and no='0' and sendtime='' order by mail limit " . $firstno . "," . $permailnum);
 while($Result= $DB->fetch_array($Query)){
	$mail_array[$i]	= $Result['mail'];
	$i++;
 }
 $mailstr = implode(",",$mail_array);

$Array_list =  array("mailsubject"=>trim($publication_title),"mailbody"=>trim($publication_content));

$mailfalse = $SMTP->MailForsmartshop($mailstr,"","GroupSend",$Array_list);
if ($mailfalse!=""){
	$mailarray = explode(",",$mailfalse);
	if (is_array($mailarray)){
		foreach($mailarray as $v){
			$Query = $DB->query(" insert into `{$INFO[DBPrefix]}falsemail` (mail,pid,no,sendtime) values ('" . $v . "','" . intval($Pub_id) . "','" . intval($_GET['maxno']) . "','" . time() . "')");
		}
	}
}

echo str_replace(",",chr(10) . chr(13),$mailstr). chr(10) . chr(13) . "\r\n";
$DB->my_close();
exit;
?>