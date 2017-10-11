<?php
session_start();
include("../configs.inc.php");
include("global.php");
/**
 * 这里是得到会员的基本资料
 */
$Sql   =  " select * from `{$INFO[DBPrefix]}receiver` where user_id=".$_SESSION['user_id']." order by reid desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['receiver_name'] = $Rs['receiver_name'];
		$OrderList[$i]['addr']   = $Rs['addr'];
		$OrderList[$i]['receiver_email']  = $Rs['receiver_email'];
		$OrderList[$i]['county']   = $Rs['county'];
		$OrderList[$i]['province']   = $Rs['province'];
		$OrderList[$i]['city']     = $Rs['city'];
		$OrderList[$i]['reid']     = $Rs['reid'];
		$OrderList[$i]['receiver_tele']     = $Rs['receiver_tele'];
		$i++;
	}
}

$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）

$tpl->display("shopping_g_ajax_receiver.html");
?>