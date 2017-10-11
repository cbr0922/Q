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
$point =$FUNCTIONS->Buypoint(intval($_SESSION['user_id']),1);
//$Sql      = "select * from `{$INFO[DBPrefix]}combipoint` where user_id=".intval($_SESSION['user_id'])." order by id desc";
//$Sql      = "select bp.* from `{$INFO[DBPrefix]}grouppoint` as bp where bp.user_id=".intval($_SESSION['user_id'])." order by bp.id desc";
$Sql      = "select bp.*,u.username,u.true_name,bpt.typename,o.order_serial from `{$INFO[DBPrefix]}buypoint` as bp inner join `{$INFO[DBPrefix]}user` as u on bp.user_id=u.user_id left join `{$INFO[DBPrefix]}order_table` as o on o.order_id=bp.orderid left join `{$INFO[DBPrefix]}buypointtype` as bpt on bpt.id=bp.buypointtype where bp.user_id='" . intval($_SESSION['user_id']) . "'order by bp.id desc";


$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	$total = $point;
	$oldpoint = 0;
	$oldtype = 0;
	$cha = 0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['point'] = $Rs['point'];
		
		$OrderList[$i]['id'] = $Rs['id'];
		$OrderList[$i]['addtime']   = date("Y-m-d",$Rs['addtime']);
		$OrderList[$i]['orderid']   = intval($Rs['orderid']);
		$OrderList[$i]['content'] = $Rs['content'];
		$OrderList[$i]['groupname'] = $Rs['typename'];
		$OrderList[$i]['order_serial'] = $Rs['order_serial'];
		$OrderList[$i]['type'] = $Rs['type'];
		if($i == 0){
			$total = $point;
			
			$OrderList[$i]['total'] = $total;
		}else{
			$total = $oldtype==1?$total+$oldpoint:$total-$oldpoint;
			$OrderList[$i]['total'] = $total;
		}
		
		$oldpoint =$Rs['point'];
		$oldtype = $Rs['type'];
		//
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}


$tpl->assign("sumpoint",    intval($point)); 

$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("buypointrecord.html");
?>