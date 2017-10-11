<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
$_SESSION['user_id']     = '';
		$_SESSION['username']    = '';
		$_SESSION['true_name']    = '';
		$_SESSION['user_level']  = '';
		$_SESSION['userlevelname'] ='';
		$_SESSION['Member_Volid'] = '';
if ($_GET['wrong']!=""){
	$tpl->assign("Wrong_say",       $MemberLanguage_Pack[Wrong]); //並不存在您的資料
}

if ($_POST['Action']=='SendEmail'){
	
	//校验验证码
	if (trim($_POST['inputcode'])!=$_SESSION['Code_Reg']){
		$FUNCTIONS->sorry_back("back",$MemberLanguage_Pack[CodeIsBad_say]); //驗證碼錯誤
	}

	$Query = $DB->query("select user_id,other_tel,true_name from `{$INFO[DBPrefix]}user` where email='".trim($_POST['email'])."' limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num){
		//$Char = array('a','b','c','d','e','f','g','h','i','g','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

		$Result   = $DB->fetch_array($Query);
		$User_id  = $Result['user_id'];
		$other_tel   =  trim($Result['other_tel']);
		$truename   =  trim($Result['true_name']);
		$One   = rand(1,10);
		$Two   = rand(11,80);
		$Three = rand(10,40);
		$Four  = rand(5,9);
		$Five  = rand(6,16);
		$Six   = rand(100,1000);

		$NewPassword = $One.$Two.$Three.$Four.$Five.$Six;

		$DB->query("update `{$INFO[DBPrefix]}user` set password='".md5($NewPassword)."' where user_id=".intval($User_id));

	}else{

		//$FUNCTIONS->sorry_back("forget_password.php?wrong=wrong","");
		$FUNCTIONS->sorry_back("back","很抱歉，系統查無此會員資料，請與客服中心連絡，謝謝。");
	}

	$Array = array("username"=>trim($_POST['email']),"truename"=>trim($truename),"f_pwd"=>$NewPassword);
	include "SMTP.Class.inc.php";
	include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	$SMTP->MailForsmartshop(trim($_POST['email']),"",3,$Array);
	
	include "sms2.inc.php";
	include "sendmsg.php";
			
	$sendmsg = new SendMsg;
	$sendmsg->send(trim($other_tel),$Array,3);


	$FUNCTIONS->sorry_back("forget_password.php",$MemberLanguage_Pack[ThePassHadSendYourEmail_say]);
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
$tpl->display("forget_pwd.html");
?>
