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

include_once Classes . "/orderClass.php";
$orderClass = new orderClass;

//装载翻页函数
include ("pagenav_stard.php");
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
include ("pagenav_ex.php");
$Build_nav = new NavFunction();

$Sql   =  " select * from `{$INFO[DBPrefix]}order_table` where user_id=".$_SESSION['user_id']." order by createtime desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$tot =  0;
		$monthprice = 0;
		$OrderList[$i]['shopid'] = intval($Rs['shopid']);
		if (intval($Rs['order_state'])==4 && intval($Rs['shopid'])>0){
			$s_Sql   =  " select * from `{$INFO[DBPrefix]}score` where user_id=".$_SESSION['user_id']." and order_id='" . $Rs['order_id'] . "' ";
			$s_Query =  $DB->query($s_Sql);
			$s_Num   =  $DB->num_rows($s_Query);
			if ($s_Num>0){
				$s_Rs = $DB->fetch_array($s_Query);
				$OrderList[$i]['havescore'] = 1;
				$OrderList[$i]['score1'] = $s_Rs['score1'];
				$OrderList[$i]['score2'] = $s_Rs['score2'];
				$OrderList[$i]['score3'] = $s_Rs['score3'];
			}else{
				$OrderList[$i]['havescore'] = 0;
			}
		}
		$OrderList[$i]['order_serial'] = $Rs['order_serial'];
		$OrderList[$i]['createtime']   = date("Y-m-d  H:i",$Rs['createtime']);
		$OrderList[$i]['order_state']  = $orderClass->getOrderState($Rs['order_state'],1);
		$OrderList[$i]['order_state_value']  = $Rs['order_state'];
		$OrderList[$i]['pay_state']  = $orderClass->getOrderState($Rs['pay_state'],2);		$OrderList[$i]['pay_state_value']  = $Rs['pay_state'];
		$OrderList[$i]['transport_state']  = $orderClass->getOrderState($Rs['transport_state'],3);		$OrderList[$i]['transport_state_value']  = $Rs['transport_state'];
		$OrderList[$i]['order_show']   = intval($Rs['order_state']);

		$OrderList[$i]['totalprice']   = $Rs['totalprice']+$Rs['transport_price'];
		$OrderList[$i]['order_id']     = $Rs['order_id'];
		$OrderList[$i]['tot']     = $Rs['discount_totalPrices']+$Rs['transport_price'];
		$OrderList[$i]['opList']   =        $orderClass->getUserOp($Rs['order_id']);
		$Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu` where order_serial = '" . $Rs['order_serial'] . "'";
		$Query_linshi = $DB->query($Sql_linshi);
		$Rs_linshi = $DB->fetch_array($Query_linshi);
		if ($Rs_linshi['kid']>0)
			$OrderList[$i]['kid']   =$Rs_linshi['kid'];
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}

$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关


//print_r($OrderList);
$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->display("MyOrder.html");

?>