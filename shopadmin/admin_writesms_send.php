<?php
error_reporting(7);
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';


if ($_POST['uid']!="" ){
	$User_id = intval($_POST['uid']);
	$Query = $DB->query("select other_tel from `{$INFO[DBPrefix]}user` where user_id=".intval($User_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$other_tel          =  MD5Crypt::Decrypt ($Result['other_tel'], $INFO['mcrypt'] );
		include "sms2.inc.php";
		include "sendmsg.php";
					
		$sendmsg = new SendMsg;
		$Array_list =  array("mailsubject"=>trim($_POST['title']),"mailbody"=>trim($_POST['body']));
		 $mailfalse=$sendmsg->send(trim($other_tel),$Array_list,"GroupSend");

		if (!$mailfalse){
			echo "<script language=javascript>alert('發送成功');history.back(-1);</script>";
			exit;
		}else{
			echo "<script language=javascript>alert('發送失敗');location.href='admin_member_list.php';</script>";
			exit;	
		}
	}

}
echo "<script language=javascript>alert('會員不存在');history.back(-1);</script>";
		exit;
?>
