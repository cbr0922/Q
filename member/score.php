<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
$order_id = intval($_GET['order_id']);

$Sql   =  " select * from `{$INFO[DBPrefix]}order_table` where user_id=".$_SESSION['user_id']." and order_id='" . $order_id . "' and order_state=4 order by createtime desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$s_Sql   =  " select * from `{$INFO[DBPrefix]}score` where user_id=".$_SESSION['user_id']." and order_id='" . $order_id . "' ";
	$s_Query =  $DB->query($s_Sql);	
	$s_Num   =  $DB->num_rows($s_Query);
	if ($s_Num>0){
		$FUNCTIONS->sorry_back('back',"您已經評價過此訂單的滿意度");	
	}
	$Result    = $DB->fetch_array($Query);
	$tpl->assign("order_serial",          $Result['order_serial']);
	$tpl->assign("createtime",            date("Y-m-d H:i a ",$Result['createtime']));
	$tpl->assign("discount_totalPrices",             $Result['discount_totalPrices']+$Result['transport_price']);
	$Query_detail = $DB->query(" select g.*,gd.bn from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where order_id=".intval($order_id)." order by order_detail_id desc ");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
		$order_detail[$i][gid]          = $Rs_detail[gid];
		$order_detail[$i][bn]          = $Rs_detail[bn];
		$order_detail[$i][goodsname]    = $Rs_detail[goodsname];
		$order_detail[$i][market_price] = $Rs_detail[market_price];
		$order_detail[$i][price]        = $Rs_detail[price];
		$order_detail[$i][goodscount]   = $Rs_detail[goodscount];
		$order_detail[$i][good_color]   = $Rs_detail[good_color];
		$order_detail[$i][good_size]   = $Rs_detail[good_size];
		$order_detail[$i][detail_id]   = $Rs_detail[detail_id];
		$order_detail[$i][detail_name]   = $Rs_detail[detail_name];
		$order_detail[$i][detail_bn]   = $Rs_detail[detail_bn];
		$order_detail[$i][detail_des]   = $Rs_detail[detail_des];
		$order_detail[$i][ifchange]   = $Rs_detail[ifchange];
		$order_detail[$i][ifxygoods]   = $Rs_detail[ifxygoods];
		$order_detail[$i][xygoods_des]   = $Rs_detail[xygoods_des];
		if ($Rs_detail['memberorprice']==1){
			$buyprice = intval($Rs_detail[price]);
		}else{
			$buyprice = intval($Rs_detail[memberprice]) . "+" . intval($Rs_detail[combipoint]) . "積分";
		}
		$order_detail[$i][buyprice] = $buyprice;
		
		$i++;
	}
	$tpl->assign("order_detail",          $order_detail);
}else{
	$FUNCTIONS->sorry_back('back',"您現在不能評價滿意度");	
}


$tpl->display("score.html");
?>