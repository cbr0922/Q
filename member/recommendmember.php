<?php
error_reporting(7);
header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");

//装载翻页函数
include ("pagenav_stard.php");
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);

$u_sql = "select * from `{$INFO[DBPrefix]}user` where user_id='" . intval($_SESSION['user_id']) . "'";
$Query_u=$DB->query($u_sql);
$Rs_u = $DB->fetch_array($Query_u);
$memberno = $Rs_u['memberno'];
$recommendno = $Rs_u['recommendno'];
if ($recommendno!=""){
	$r_sql = "select * from `{$INFO[DBPrefix]}user` where memberno='" . $recommendno . "'";
	$Query_r=$DB->query($r_sql);
	$Rs_r = $DB->fetch_array($Query_r);
	$username_re = $Rs_r['username'];
	$true_name_re = $Rs_r['true_name'];
}
$Sql      = "select * from `{$INFO[DBPrefix]}user` where recommendno='".$memberno."' order by user_id desc";
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
		$p_sql = "select sum(point) as sumpoint from `{$INFO[DBPrefix]}bonuspoint` where type=4 and user_id='" . intval($_SESSION['user_id']) . "' and content='推薦會員" . $Rs['username'] . "'";
		$Query_p=$DB->query($p_sql);
		$Rs_p = $DB->fetch_array($Query_p);
		$User_array[$i]['point'] = intval($Rs_p['sumpoint']);
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}
$tpl->assign("recommend_username",        $username_re);
$tpl->assign("recommend_true_name",        $true_name_re);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("User_array",        $User_array);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->display("recommendmember.html");
?>