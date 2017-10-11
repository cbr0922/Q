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

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
$cur_array = explode("/",$_SERVER["PHP_SELF"]);
$cur_page = $cur_array[count($cur_array)-1];
$tpl->assign("cur_page",$cur_page);
$tpl->assign("login_mode", $_SESSION['login_mode']); //登入方式
$tpl->display("left.html");
?>
