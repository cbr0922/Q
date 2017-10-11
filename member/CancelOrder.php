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

$Order_id  = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));
$Query     = $DB->query("select o.order_state,o.order_id,ou.userback_idate,o.bonuspoint,o.totalbonuspoint,o.order_serial from `{$INFO[DBPrefix]}order_table` o  left join  `{$INFO[DBPrefix]}order_userback` ou on  (o.order_id=ou.order_id) where o.user_id='".$_SESSION['user_id']."' and o.order_id=".intval($Order_id)." limit 0,1");
$Num       = $DB->num_rows($Query);

if ( $Num == 0 ){
	$FUNCTIONS->sorry_back("close",$Order_Pack[NoTheOrder]); //不存在的订单！
}
$Rs =  $DB->fetch_array($Query);
$Pay_point      = $Rs['bonuspoint']+$Rs['totalbonuspoint'];
$Order_serial     = $Rs['order_serial'];

if ($Rs['order_state']!=0) {
	$FUNCTIONS->sorry_back("close",$Order_Pack[BadOrderStatu]); //"订单状态不正确!"
}

if ($Rs['userback_idate']!="") {
	$FUNCTIONS->sorry_back("back",$Order_Pack[TheOrderHadCancel]); //本订单已取消！
}

$DB->free_result($Query);

if ($_POST['Action']=='Cancel'){

	$DB->query(" insert into `{$INFO[DBPrefix]}order_userback` (user_id,order_id,userback_type,user_say,userback_idate) values ('".$_SESSION['user_id']."','".$Order_id."','1','".$_POST['isay']."','".time()."')");
	$DB->query("update `{$INFO[DBPrefix]}order_table` set order_state=2 where order_id=".intval($Order_id));
	$DB->query("update `{$INFO[DBPrefix]}order_detail` set detail_order_state=2 where order_id=".intval($Order_id));
	$FUNCTIONS->AddBonuspoint(intval($_SESSION['user_id']),intval($Pay_point),2,"訂單" . $Order_serial,1,$Order_id);
	
	$FUNCTIONS->sorry_back("index.php",$Order_Pack[The_Order_CancelInformation_Had_SendTo_System]); //"订单取消信息已发送给系统！";
}

$tpl->assign("Order_id",                $Order_id);

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);


$tpl->display("CancelOrder.html");
?>

