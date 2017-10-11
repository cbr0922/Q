<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";

$Order_id  = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));
$Query     = $DB->query("select o.order_state,o.order_id,ou.userback_idate from `{$INFO[DBPrefix]}order_table` o  left join  `{$INFO[DBPrefix]}order_userback` ou on  (o.order_id=ou.order_id) where o.user_id='0' and o.order_id=".intval($Order_id)." limit 0,1");
$Num       = $DB->num_rows($Query);


if ( $Num == 0 ){
	$FUNCTIONS->sorry_back("close",$Order_Pack[NoTheOrder]); //不存在的订单！
}
$Rs =  $DB->fetch_array($Query);

if ($Rs['order_state']!=0) {
	$FUNCTIONS->sorry_back("close",$Order_Pack[BadOrderStatu]); //"订单状态不正确!"
}


if ($Rs['order_state']==5) {
	$FUNCTIONS->sorry_back("close",$Order_Pack[TheOrderHadCancel]); //本订单已取消！
}


$DB->free_result($Query);
$tpl->assign("Order_id",                $Order_id);



if ($_POST['Action']=='Cancel'){

	$DB->query(" insert into `{$INFO[DBPrefix]}order_userback` (user_id,order_id,userback_type,user_say,userback_idate) values ('".$_SESSION['user_id']."','".$Order_id."','1','".$_POST['isay']."','".time()."')");
	$DB->query("update `{$INFO[DBPrefix]}order_table` set order_state=5 where order_id=".intval($Order_id));
	$DB->query("update `{$INFO[DBPrefix]}order_detail` set detail_order_state=5 where order_id=".intval($Order_id));
	$FUNCTIONS->sorry_back("NoMember_View.php",$Order_Pack[CancelOrderInfoSendedSystem]); //"订单取消信息已发送给系统！";
}


$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->display("CancelNomemberOrder.html");
?>

