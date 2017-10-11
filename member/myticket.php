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
$Sql = "select sum(ut.count) as count,ut.ticketid,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.userid=".intval($_SESSION['user_id'])." group by ut.ticketid";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
//$PageNav    = new PageItem($Sql,10);
//$Num        = $PageNav->iTotal;
//echo $PageNav->myPageItem();
//$tpl->assign("Nav_banner",       $PageNav->myPageItem());     //商品翻页条

if ($Num>0){
	//$arrRecords = $PageNav->ReadList();
	$i=0;
	while ( $Rs = $DB->fetch_array($Query)){
		$OrderList[$i]['ticketid'] = $Rs['ticketid'];
		$OrderList[$i]['count']   = intval($Rs['count']);
		$OrderList[$i]['money']   = ($Rs['money']);
		$OrderList[$i]['ticketname']     = $Rs['ticketname'];
		$OrderList[$i]['use_starttime']     = $Rs['use_starttime'];
		$OrderList[$i]['use_endtime']     = $Rs['use_endtime'];
		$OrderList[$i]['moneytype']     = $Rs['moneytype'];
		$i++;
	}
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
//$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("myticket.html");
?>
