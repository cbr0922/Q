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

//$Sql      = "select * from `{$INFO[DBPrefix]}combipoint` where user_id=".intval($_SESSION['user_id'])." order by id desc";
$Sql      = "select * from `{$INFO[DBPrefix]}grouppointbuydetail` where user_id=".intval($_SESSION['user_id'])." order by id desc";

$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['id'] = $Rs['id'];
		$OrderList[$i]['content'] = $Rs['content'];
		$OrderList[$i]['usepoint'] = $Rs['usepoint'];
		$OrderList[$i]['usetime']     = date("Y-m-d",$Rs['usetime']);
		$OrderList[$i]['orderid']   = intval($Rs['orderid']);
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}

$point =$FUNCTIONS->Grouppoint(intval($_SESSION['user_id']),1);
$tpl->assign("sumpoint",    intval($point)); 

$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("grouppointbuyrecord.html");
?>