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
$tpl->assign("sid",        time());
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
$tpl->display("reg_resend.html");
?>
