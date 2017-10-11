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

$Sql   =  " select od.*,s.score1,s.content,s.score_id,ot.* from `{$INFO[DBPrefix]}order_detail`  as od inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id left join `{$INFO[DBPrefix]}score` as s on (s.gid=od.gid and s.order_id=od.order_id) where ot.user_id=".$_SESSION['user_id']." and ot.order_state=4 order by ot.createtime desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['order_serial'] = $Rs['order_serial'];
		$OrderList[$i]['goodsname'] = $Rs['goodsname'];
		$OrderList[$i]['price'] = floor($Rs['price']);
		$OrderList[$i]['pay_state'] = $orderClass->getOrderState($Rs['pay_state'],2);
		$OrderList[$i]['transport_state'] = $orderClass->getOrderState($Rs['transport_state'],3);
		$OrderList[$i]['createtime']   = date("Y-m-d",$Rs['createtime']);
		$OrderList[$i]['order_state']  = $orderClass->getOrderState($Rs['order_state'],1);
		$OrderList[$i]['order_id']     = $Rs['order_id'];
		$OrderList[$i]['gid']     = $Rs['gid'];
		$OrderList[$i]['score1']     = $Rs['score1'];
		$OrderList[$i]['content']     = $Rs['content'];
		$OrderList[$i]['score_id'] = intval($Rs['score_id']);
		$OrderList[$i]['ifcanscore'] = $Rs['order_state']==4?1:0;
		$OrderList[$i]['bn'] = $Rs['bn'];
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
$tpl->display("myscore.html");

?>