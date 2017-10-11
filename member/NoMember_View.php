<?php
error_reporting(0);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/NoMember_Order_Pack.php";

if ($_SESSION['user_id']!=""){
@header("location:".$INFO[site_url]."/member/MyOrder.php");
}

if ($_GET['wrong']!=""){
	$tpl->assign("Wrong",       $Basic_Command['Null']); //并不存在您的资料
}


/*
$tpl->assign("NoformatEmail",         $TPL_TAGS["ttag_1945"]); //请输入正确格式的邮件地址
$tpl->assign("MustbeNum",             $TPL_TAGS["ttag_3124"]); //定单编号必须是数字组成

$tpl->assign("NOorderNum",            $TPL_TAGS["ttag_3125"]); //订单编号并不存在
$tpl->assign("PassorderNum",          $TPL_TAGS["ttag_3126"]); //订单编号通过
$tpl->assign("NOEmail",               $TPL_TAGS["ttag_3127"]); //此Email并不存在订单资料中
$tpl->assign("PassEmail",             $TPL_TAGS["ttag_3128"]); //Email资料通过



$tpl->assign("JsSerial",      $TPL_TAGS["ttag_1875"].$PROG_TAGS["ptag_1631"]); //JS定单编号
$tpl->assign("JsEmail",       $PROG_TAGS["ptag_1327"].$PROG_TAGS["ptag_1245"]); //JS输入EMIAL

$tpl->assign("Title_say",     $TPL_TAGS["ttag_3123"]); //非會員查詢定單

$tpl->assign("Serial",        $PROG_TAGS["ptag_1631"]); //定单编号
$tpl->assign("Email",         $PROG_TAGS["ptag_1640"]); //email
$tpl->assign("Submit",        $TPL_TAGS["ttag_1086"]); //提交
*/


$tpl->assign($NoMember_Order_Pack);   
$tpl->assign($Basic_Command);   
$tpl->display("NoMember_View.html");
?>