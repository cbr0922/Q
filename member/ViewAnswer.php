<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";

$order_id  = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));
$Sql         = "select user_say,sys_say from `{$INFO[DBPrefix]}order_userback` where order_id=".$order_id." order by userback_idate desc limit 0,1";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);

$tpl->assign("Sessid",              $_SESSION['user_id']); //会员ID

$TalkHistory = array();

if ($Num>0){
	$i=0;
	while ($Result    = $DB->fetch_array($Query)){
		$TalkHistory[$i][user_say]    = nl2br($Result['user_say']);
		$TalkHistory[$i][sys_say]     = nl2br($Result['sys_say']);
		$i++;
	}
}
$tpl->assign("TalkHistory",              $TalkHistory); //会员会话资料


$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);

$tpl->display("ViewAnswer.html");




?>

