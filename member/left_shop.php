<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include  "check_member.php";
include ("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
$sid = intval($_SESSION['shopid']);
$tpl->assign("sid",       $sid);
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
$tpl->display("left_shop.html");
?>
