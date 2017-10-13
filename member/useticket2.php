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
include("PageNav.class.php");
$Sql      = "select ot.ticketmoney,ot.discount_totalPrices,ot.transport_price,ot.ticket_discount_money,ot.totalprice,ut.*,ut.ordercode,ot.order_id,t.moneytype,t.money from `{$INFO[DBPrefix]}use_ticket` as ut inner join `{$INFO[DBPrefix]}order_table` as ot on ot.order_serial=ut.ordercode inner join `{$INFO[DBPrefix]}ticketcode` as tc on tc.ticketcode=ut.ticketcode inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where ut.userid=".intval($_SESSION['user_id'])." order by ut.useid desc";
$PageNav    = new PageItem($Sql,10);
$Num        = $PageNav->iTotal;
if ($Num>0){
	$Query = $PageNav->ReadList();
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		//$OrderList[$i]['money'] = $Rs['money'];
		$OrderList[$i]['ordercode']   = $Rs['ordercode'];
		$OrderList[$i]['order_id']   = intval($Rs['order_id']);
		$OrderList[$i]['usetime']     = date("Y-m-d",$Rs['usetime']);
		$OrderList[$i]['moneytype']     = $Rs['moneytype'];
		$OrderList[$i]['totalprice']     = $Rs['discount_totalPrices']+$Rs['transport_price']. "元";
		$OrderList[$i]['money'] = $Rs['ticket_discount_money']. "元";
		$i++;
	}
	$Nav_banner = $PageNav->myPageItem();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}

$Sql = "select ut.ticketcode,ut.ticketid,ut.userid,ut.usetime,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}ticketcode` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.ownid=".intval($_SESSION['user_id'])." and ut.userid =0  and t.use_endtime>='" . date("Y-m-d",time()) . "'";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$ticketcount = 0;
while ( $Rs = $DB->fetch_array($Query)){
		$ticketcount+=intval($Rs['count']);
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
