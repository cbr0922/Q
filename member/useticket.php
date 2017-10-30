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

$Sql      = "select ot.ticketmoney,ot.discount_totalPrices,ot.transport_price,ot.ticket_discount_money,ot.totalprice,ut.*,ut.ordercode,ot.order_id from `{$INFO[DBPrefix]}use_ticket` as ut inner join `{$INFO[DBPrefix]}order_table` as ot on ot.order_serial=ut.ordercode where ut.ticketid='" . intval($_GET['ticketid']) . "' and ut.userid=".intval($_SESSION['user_id'])." order by ut.useid desc";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		//print_r($Rs);

		$OrderList[$i]['ordercode']   = $Rs['ordercode'];
		$OrderList[$i]['order_id']   = intval($Rs['order_id']);
		$OrderList[$i]['usetime']     = date("Y-m-d",$Rs['usetime']);
		$OrderList[$i]['moneytype']     = $Rs['moneytype'];
		$OrderList[$i]['totalprice']     = $Rs['discount_totalPrices']+$Rs['transport_price']. "元";
		$OrderList[$i]['discount_totalPrices']     = $Rs['discount_totalPrices'];
		$OrderList[$i]['money'] = $Rs['ticket_discount_money']. "元";

		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}
$Sql_count1 = "select sum(ut.count) as count,ut.ticketid,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where t.use_starttime<='" . date("Y-m-d",time()) . "' and t.use_endtime>='" . date("Y-m-d",time()) . "' and ut.userid=".intval($_SESSION['user_id'])." group by ut.ticketid";
$Query_count1 =  $DB->query($Sql_count1);
$Num_count1   =  $DB->num_rows($Query_count1);
$ticketcount = 0;
while ( $Rs_count1 = $DB->fetch_array($Query_count1)){
		$ticketcount += intval($Rs_count1['count']);
}
$Sql_count ="select ut.ticketcode,ut.ticketid,ut.userid,ut.usetime,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}ticketcode` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.ownid=".intval($_SESSION['user_id'])." and (ut.usetime='' or ut.usetime is null) and ut.userid =0 and t.use_starttime<='" . date("Y-m-d",time()) . "' and t.use_endtime>='" . date("Y-m-d",time()) . "'";
$Query_count =  $DB->query($Sql_count);
$Num_count   =  $DB->num_rows($Query_count);

$tpl->assign("Num",               $Num_count);
$tpl->assign("ticketcount",               $ticketcount);
$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("useticket.html");
?>
