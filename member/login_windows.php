<?php
error_reporting(7);
@session_start();
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
if (intval($_SESSION['user_id'])>0){
	if ($_GET['shop']=="shop"){
	if ($_GET['type']=="group")
		$FUNCTIONS->header_location("../shopping/shopping2_g.php?key=" . $_GET['key']);
   else
  	 	$FUNCTIONS->header_location("../shopping/flight-orderday.php?key=" . $_GET['key']);
}
	$FUNCTIONS->header_location("index.php?hometype=" . $_GET['hometype']);
}

if ($_GET['wrong']!=""){
	$tpl->assign("Wrong_say",       $MemberLanguage_Pack[IsWrong]); //错误的用户名和密码
}

$tpl->assign("Remind",      $MemberLanguage_Pack[IsRemind]); //如果您想在本站购物，请先登陆！如果还没有注册请先免费注册！

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关

if (trim($_GET[Url])!=""){
	$en_url_From = trim($_GET[Url]);
	$tpl->assign("Url",      base64_encode(str_replace("&amp;","&",$en_url_From)));
	$tpl->assign("en_url_From", $en_url_From); //获得来自何方的URL
}else {
	$en_url_From = $_SERVER['HTTP_REFERER'];
	$en_url_From=strpos($en_url_From, "member_login.php")?"../index.php":$en_url_From;
	$en_url_From=strpos($en_url_From, "login_windows.php")?"../index.php":$en_url_From;
	$en_url_From=strpos($en_url_From, "forget_password.php")?"../index.php":$en_url_From;
	$en_url_From=strpos($en_url_From, "transfercode.php")?"../index.php":$en_url_From;
	$tpl->assign("Url",      base64_encode(str_replace("&amp;","&",$en_url_From)));
	$tpl->assign("en_url_From", $en_url_From); //获得来自何方的URL
}

$tpl->assign("app_id", $INFO['mod.login.fb.app_id']);
//$tpl->assign("sid",time());
$tpl->display("login_windows.html");
?>
