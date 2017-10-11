<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";

//装载翻页函数
include ("pagenav_stard.php");
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
include ("pagenav_ex.php");
$Build_nav = new NavFunction();

$Sql      = "select * from `{$INFO[DBPrefix]}bonus_record` as br inner join `{$INFO[DBPrefix]}order_table` as ot on ot.order_serial=br.ordercode where br.userid=".intval($_SESSION['user_id'])." order by br.recordid desc";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['recordid'] = $Rs['recordid'];
		$OrderList[$i]['ordercode']   = $Rs['ordercode'];
		$OrderList[$i]['point']   = $Rs['point'];
		$OrderList[$i]['order_id']   = intval($Rs['order_id']);
		$OrderList[$i]['usetime']     = date("Y-m-d",$Rs['rebatetime']);
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}

/**
 * 获得红利点数字
 */
$Sql = "SELECT  member_point FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($_SESSION['user_id'])."' limit 0,1";
$Query  = $DB->query($Sql);
while ($Rs=$DB->fetch_array($Query)){
 
	$tpl->assign("Member_Point",$Rs[member_point]);
 	
}

$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("usebonuspoint.html");
?>