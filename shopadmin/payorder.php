<?php
session_start();
include("../configs.inc.php");
include(Classes . "/global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

@header("Content-type: text/html; charset=utf-8");
/**
 *  这里是获得付款方式
 */

$Query_pay    = $DB->query("select pay_id,pay_name,pay_state,pay_content from `{$INFO[DBPrefix]}pay_type` where pay_state='1' order by pay_id asc ");
$Num_pay      = $DB->num_rows($Query_pay);
$i=0;
while($Rs_pay = $DB->fetch_array($Query_pay)){
	$PayType_array[$i][pay_id]      = $Rs_pay['pay_id'];
	$PayType_array[$i][pay_name]    = $Rs_pay['pay_name'];
	$PayType_array[$i][pay_state]   = $Rs_pay['pay_state'];
	$PayType_array[$i][pay_content] = $Rs_pay['pay_content'] ;
	$i++;
}
$tpl->assign("PayType_array",  $PayType_array);

function Tw_OnePay_Function($INput){
	$Array = explode(",",$INput);
	foreach ($Array as $k=>$v){
		if ($v==$Value){
			return   "checked";
			break 1;
		}
	}

}

/**
 * 如果台湾的陪送方式没有。那么将不会显示相关的资料
 */

if (trim($INFO['Tw_OnePay'])!='' && $INFO['Pay_twpay'] == '1'){

	$Tw_OnePay_Array = explode(",",$INFO['Tw_OnePay']);
	//echo $INFO['Tw_OnePay'];
	$i = 0;
	$TW_ARRAY = array();
	foreach ($Tw_OnePay_Array as $k =>$v ){
		$TW_ARRAY[$i][Value]   = $v;
		$TW_ARRAY[$i][Title]   = TwPayOne_Function($v);
		$TW_ARRAY[$i][Content] = TwPayOne_Function_String($v);
		$TW_ARRAY[$i][showiid] = 1;
		//echo TwPayOne_Function($v);
		$i++;
	}
	$tpl->assign("TW_ARRAY",      $TW_ARRAY);

}
else if(trim($INFO['Tw_TwoPay'])!='' && $INFO['Pay_twpay'] == '2'){
	$Tw_TwoPay_Array = explode(",",$INFO['Tw_TwoPay']);
	//echo $INFO['Tw_OnePay'];
	$i = 0;
	$TW_ARRAY = array();
	foreach ($Tw_TwoPay_Array as $k =>$v ){
		$TW_ARRAY[$i][Value]   = $v;
		$TW_ARRAY[$i][Title]   = TwPayTwo_Function($v);
		$TW_ARRAY[$i][Content] = TwPayTwo_paymentFunction($v);
		$TW_ARRAY[$i][showbankid] = 1;
		//echo TwPayOne_Function($v);
		$i++;
	}
	$tpl->assign("TW_ARRAY",      $TW_ARRAY);
}
$Tw_FourPay_Array = explode(",",$INFO['lianhe_paytype']);
	//echo $INFO['Tw_OnePay'];
	$i = 0;
	$TW_ARRAY_F = array();
	foreach ($Tw_FourPay_Array as $k =>$v ){
		if ($v!=""){
			$TW_ARRAY_F[$i][Value]   = $v;
			$TW_ARRAY_F[$i][Title]   = TwPayFour_Function($v);
			$TW_ARRAY_F[$i][Content] = TwPayFour_paymentFunction($v);
			//echo TwPayOne_Function($v);
			$i++;
		}
	}
	$tpl->assign("TW_ARRAY_F",      $TW_ARRAY_F);
	
$Tw_SevenPay_Array = explode(",",$INFO['paytype']);
	//echo $INFO['Tw_OnePay'];
	$i = 0;
	$TW_ARRAY_G = array();
	foreach ($Tw_SevenPay_Array as $k =>$v ){
		if ($v!=""){
			$TW_ARRAY_G[$i][Value]   = $v;
			$TW_ARRAY_G[$i][Title]   = TwPaySeven_Function($v);
			$TW_ARRAY_G[$i][Content] = TwPaySeven_paymentFunction($v);
		
		//echo TwPayOne_Function($v);
			$i++;
		}
	}
	$tpl->assign("TW_ARRAY_G",      $TW_ARRAY_G);

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

$order_detail = array();

$Query_detail = $DB->query(" select gid,goodsname,market_price,price,goodscount from `{$INFO[DBPrefix]}order_detail` where order_id=".intval($Order_id)." order by order_detail_id desc ");
$i = 0 ;
while ($Rs_detail = $DB->fetch_array($Query_detail)){
	$order_detail[$i][gid]          = $Rs_detail[gid];
	$order_detail[$i][goodsname]    = $Rs_detail[goodsname];
	$order_detail[$i][market_price] = $Rs_detail[market_price];
	$order_detail[$i][price]        = $Rs_detail[price];
	$order_detail[$i][goodscount]   = $Rs_detail[goodscount];
	$order_detail[$i][detail_name]   = $Rs_detail[detail_name];
	$order_detail[$i][detail_bn]   = $Rs_detail[detail_bn];
	$order_detail[$i][xygoods]   = $Rs_detail[xygoods];
	$order_detail[$i][ifxygoods]   = $Rs_detail[ifxygoods];
	$order_detail[$i][ifchange]   = $Rs_detail[ifchange];
	$i++;
}
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