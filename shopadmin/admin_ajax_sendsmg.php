<?php
//include_once "Check_Admin.php";
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$Pub_id        = intval($FUNCTIONS->Value_Manage($_GET['pub_id'],$_POST['pub_id'],'back',''));
//***************************************************************************************************************************
include "sms2.inc.php";
include "sendmsg.php";
$sendmsg = new SendMsg;
//***************************************************************************************************************************
//echo $mailstr = $_GET['mailstr'];
$Query = $DB->query(" select publication_title,publication_start_time,publication_end_time,publication_content  from `{$INFO[DBPrefix]}edmsmg` where publication_id=".intval($Pub_id)." limit 0,1"
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
 $Array_list =  array("mailsubject"=>trim($publication_title),"mailbody"=>trim($publication_content));
 $Query = $DB->query(" select tel from `{$INFO[DBPrefix]}falsesmg` where tel<>'' and no='0' and sendtime='' order by tel limit " . $firstno . "," . $permailnum);
 while($Result= $DB->fetch_array($Query)){
	$mail_array[$i]	= $Result['tel'];
	$mailfalse = $sendmsg->send(trim($Result['tel']),$Array_list,"GroupSend");
	if ($mailfalse!="")
		$Query = $DB->query(" insert into `{$INFO[DBPrefix]}falsesmg` (tel,pid,no,sendtime) values ('" . $mailfalse . "','" . intval($Pub_id) . "','" . intval($_GET['maxno']) . "','" . time() . "')");
	$i++;
 }
 $mailstr = implode(",",$mail_array);



//$mailfalse = $SMTP->MailForsmartshop($mailstr,"","GroupSend",$Array_list);


echo str_replace(",",chr(10) . chr(13),$mailstr). chr(10) . chr(13) . "\r\n";
$DB->my_close();
exit;
?>
