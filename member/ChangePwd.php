<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载产品语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

$Query  = $DB->query(" select true_name from `{$INFO[DBPrefix]}user` where user_id=".$_SESSION['user_id']." limit 0,1");
$Num    = $DB->num_rows($Query);
if ($Num==0){
	$FUNCTIONS->sorry_back("back","");
}

$Rs = $DB->fetch_array($Query);
$Realname  = $Rs['true_name'];

$Result = $DB->fetch_array($Query);
$tpl->assign("Sessid",              $_SESSION['user_id']); //会员ID
$tpl->assign("Username",            $_SESSION['username']);  //用户名称
$tpl->assign("Realname",            $Realname);   //真实名字
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($MemberLanguage_Pack);
$tpl->display("ChangePwd.html");
?>

