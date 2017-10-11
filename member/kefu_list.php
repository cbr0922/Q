<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
require_once ("../configs.inc.php");
include("global.php");

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

//装载翻页函数
include ("pagenav_stard.php");
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
//include ("pagenav_ex.php");
//include_once "pagenav_ex.php";
//$Build_nav = new NavFunction();


$Query_linshi = '';

$kefu = array();
//if ($_SESSION['user_id']!=""){
	$User_id =  " where k.user_id='".$_SESSION['user_id']."'";
//}
$Sql_linshi   = " select k.*,g.gid,g.goodsname,k.marketno,o.order_id from `{$INFO[DBPrefix]}kefu` as k left join `{$INFO[DBPrefix]}goods` as g on k.marketno=g.goodsno left join `{$INFO[DBPrefix]}order_table` as o on o.order_serial=k.order_serial ".$User_id." order by lastdate desc ";
$Query_linshi = $DB->query($Sql_linshi);
$Num_linshi   = $DB->num_rows($Query_linshi);

if ($Num_linshi>0){
	$Nav->total_result=$Num_linshi;
	$Nav->execute($Sql_linshi,10);
	$Query_linshi = $Nav->sql_result;
	$i= 0 ;
	while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
		$kefu[$i][title]      = $Rs_linshi[title];
		$kefu[$i][k_kefu_con] = $Rs_linshi[k_kefu_con];
		$kefu[$i][kid]        = $Rs_linshi[kid];
		$kefu[$i]['gid']        = $Rs_linshi['gid'];
		$kefu[$i]['goodsname']        = $Rs_linshi['goodsname'];
		$kefu[$i]['marketno']        = $Rs_linshi['marketno'];
		$kefu[$i]['order_serial']        = $Rs_linshi['order_serial'];
		$kefu[$i]['order_id']        = $Rs_linshi['order_id'];
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $KeFu_Pack['nodata']; // "無相關資料！" ;
}

$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关

$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("Num_linshi",       $Num_linshi);
$tpl->assign("kefu",             $kefu);
$tpl->assign($KeFu_Pack);
$tpl->display("kefu_list.html");
?>
