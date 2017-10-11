<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";

//装载翻页函数
include ("pagenav_stard.php");
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
include ("pagenav_ex.php");
$Build_nav = new NavFunction();

$Sql   =  " select * from `{$INFO[DBPrefix]}receiver` where user_id=".$_SESSION['user_id']." order by reid desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['receiver_name'] = $Rs['receiver_name'];
		$OrderList[$i]['addr']   = $Rs['addr'];
		$OrderList[$i]['receiver_email']  = $Rs['receiver_email'];
		$OrderList[$i]['county']   = $Rs['county'];
		$OrderList[$i]['province']   = $Rs['province'];
		$OrderList[$i]['city']     = $Rs['city'];
		$OrderList[$i]['reid']     = $Rs['reid'];
		$OrderList[$i]['receiver_tele']     =MD5Crypt::Decrypt ( $Rs['receiver_tele'], $INFO['tcrypt']);
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}

$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->display("receiver_list.html");

?>

