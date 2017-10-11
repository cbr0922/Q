<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
session_start();
include( dirname( __FILE__ )."/"."../configs.inc.php");
include("global.php");

$sid = intval($_GET['sid']);
$Query = $DB->query("select * from `{$INFO[DBPrefix]}shopinfo` where sid=".intval($sid)." and state=1 limit 0,1");
$Num   = $DB->num_rows($Query);

$Result= $DB->fetch_array($Query);
$shop_description     =  $Result['shop_description'];
$shopname       =  $Result['shopname'];
$logo     =  $Result['logo'];

$C_Sql = "select count(*) as counts from `{$INFO[DBPrefix]}shopcomment` as sc inner join `{$INFO[DBPrefix]}user` as u on sc.uid=u.user_id where sc.shopid='" . $sid . "' and sc.topid=0 ";
$Query = $DB->query($C_Sql);
$Result= $DB->fetch_array($Query);

$tpl->assign("shop_comment_count",  $Result['counts']);
$tpl->assign("shop_description",  $shop_description);
$tpl->assign("shopname",  $shopname);
$tpl->assign("logo",   $logo);
$tpl->display("include_shop_tab.html");

?>
