<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include( dirname( __FILE__ )."/"."../configs.inc.php");
$Sql_sub   = " select * from `{$INFO[DBPrefix]}discountsubject` where subject_open=1 and start_date<='" . date("Y-m-d",time()) . "' and end_date>='" . date("Y-m-d",time()) . "'";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][dsid]    =  $Rs_sub['dsid'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$Array_sub[$sub_i][start_date]  =  $Rs_sub['start_date'];
	$Array_sub[$sub_i][end_date]  =  $Rs_sub['end_date'];
	$Array_sub[$sub_i][min_money]  =  $Rs_sub['min_money'];
	$Array_sub[$sub_i][min_count]  =  $Rs_sub['min_count'];
	$Array_sub[$sub_i][mianyunfei]  =  $Rs_sub['mianyunfei'];
	$sub_i++;
}

$tpl->assign("Array_sub",       $Array_sub);           //主题类别名称循环
$tpl->display("include_discountsubject_list.html");
?>
