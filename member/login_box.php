<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include("global.php");

/**
 *  装载客户服务语言包
 */
include RootDocument."/language/".$INFO['IS']."/MemberLanguage_Pack.php";



$MemberState =  !empty($_SESSION['user_id']) ? 1 : 0 ;


$Query_old = $DB->query("select  c.companyname from `{$INFO[DBPrefix]}user` as u left join `{$INFO[DBPrefix]}company` as c on u.companyid=c.id where u.user_id='".intval($_SESSION['user_id'])."' limit 0,1");
$Num_old   = $DB->num_rows($Query_old);
$Result = $DB->fetch_array($Query_old);

$tpl->assign("companyname", $Result['companyname']); //登陆后用户名

$tpl->assign("Session_truename", $_SESSION['true_name']); //登陆后用户名
$tpl->assign("Session_userlevel",$_SESSION['userlevelname']); //登陆后用户等级
$tpl->assign("MemberState", $MemberState); //用户状态

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);

if (trim($_GET[url])!=""){
	$en_url_From = trim($_GET[url]);
	$tpl->assign("en_url_From", $en_url_From); //获得来自何方的URL
}

$tpl->display("login_box.html");
?>
