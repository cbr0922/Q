<?php
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

@header("Content-type: text/html; charset=utf-8");


$orderno = $_GET['orderno'];

$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".trim($orderno)."' ");
$Num       = $DB->num_rows($Query);

if ( $Num <= 0 ){
	$Reprot ="error";
}

$Result    = $DB->fetch_array($Query);
$Order_id  = $Result['order_id'];
$discount_totalPrices  = $Result['discount_totalPrices'];
$totalprice  = $Result['totalprice'];
$transport_price  = $Result['transport_price'];
$deliveryname  = $Result['deliveryname'];
$deliveryid  = $Result['deliveryid'];
$order_detail = array();

$Query_detail = $DB->query(" select gid,goodsname,market_price,price,goodscount from `{$INFO[DBPrefix]}order_detail` where order_id=".intval($Order_id)." and packgid=0 order by order_detail_id desc ");
$i = 0 ;
while ($Rs_detail = $DB->fetch_array($Query_detail)){
	$order_detail[$i][gid]          = $Rs_detail[gid];
	$order_detail[$i][goodsname]    = $Rs_detail[goodsname];
	$order_detail[$i][market_price] = floor($Rs_detail[market_price]);
	$order_detail[$i][price]        = floor($Rs_detail[price]);
	$order_detail[$i]['count']   = $Rs_detail[goodscount];
	$order_detail[$i]['total']   = round($Rs_detail[goodscount]*$Rs_detail[price]);
	$order_detail[$i][detail_name]   = $Rs_detail[detail_name];
	$order_detail[$i][detail_bn]   = $Rs_detail[detail_bn];
	$order_detail[$i][xygoods]   = $Rs_detail[xygoods];
	$order_detail[$i][ifxygoods]   = $Rs_detail[ifxygoods];
	$order_detail[$i][ifchange]   = $Rs_detail[ifchange];
	$i++;
}

/**
金流
**/
$i = 0;
$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation` where transport_id=".intval($deliveryid)." limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$payment   =  $Result['payment'];
}
if ($payment!="")
	$sub_sql = " and p.mid in (" . $payment . ")";

$paySql = "select *,p.content as pcontent,p.month as pmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1" . $sub_sql . " order by pm.paytype desc,p.mid";
$payQuery    = $DB->query($paySql);
$payNum      = $DB->num_rows($payQuery);
if($payNum>0){
	while($payRs = $DB->fetch_array($payQuery)){
		if ($i == 0)
			$payArray[$i]['checked'] = 1;
		$payArray[$i]['methodname'] = $payRs['methodname'];
		$payArray[$i]['mid'] = $payRs['mid'];
		$payArray[$i]['pcontent'] = $payRs['pcontent'];
		$payArray[$i]['payname'] = $payRs['payname'];
		$payArray[$i]['month'] = $payRs['pmonth'];
		if($payRs['pmonth']>0)
			$payArray[$i]['price'] =  round(($discount_totalPrices+$transport_price)/$payRs['pmonth'],0);
		$i++;
	}
}
$tpl->assign("payArray",  $payArray);
$tpl->assign("Cart_item",          $order_detail);
$tpl->assign("Order_id",$Order_id);
$tpl->assign("discount_totalPrices",$discount_totalPrices);
$tpl->assign("Cart_totalPrices",$totalprice);
$tpl->assign("Cart_transmoney",$transport_price);
$tpl->assign("deliveryname",$deliveryname);

$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Order_Pack);

$tpl->assign("Reprot",$Reprot);
$tpl->display("payorder.html");
?>