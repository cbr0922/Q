<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
//装载翻页函数
include ("pagenav_stard.php");
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
include ("pagenav_ex.php");
$Build_nav = new NavFunction();

if ($_GET['begtime']!=""){
	$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['begtime'],"-");
	$subsql = " and ot.createtime>='" . $begtimeunix . "'";
}
if ($_GET['endtime']!=""){
	$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['endtime'],"-")+60*60*24;	
	$subsql = " and ot.createtime<='" . $endtimeunix . "'";
}

$u_sql = "select * from `{$INFO[DBPrefix]}user` where user_id='" . intval($_SESSION['user_id']) . "'";
$Query_u=$DB->query($u_sql);
$Rs_u = $DB->fetch_array($Query_u);
$memberno = $Rs_u['memberno'];

$Sql      = "select u.username,u.true_name,u.reg_date,sum(bp.point)as sumpoint from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}bonuspoint` as bp on ot.order_id=bp.orderid inner join `{$INFO[DBPrefix]}user` as u on u.user_id=ot.user_id where ot.recommendno='".$memberno."' " . $subsql . " and ot.pay_state=1 and ot.order_state=4 and bp.type=5 group by ot.user_id order by ot.order_id desc";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$User_array[$i]['id'] = $i+1;
		$User_array[$i]['username'] = $Rs['username'];
		$User_array[$i]['true_name'] = $Rs['true_name'];
		$User_array[$i]['reg_date'] = $Rs['reg_date'];
		$User_array[$i]['point'] = intval($Rs['sumpoint']);
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
//print_r($User_array);
$tpl->assign("User_array",        $User_array);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->display("recommendpoint.html");
?>