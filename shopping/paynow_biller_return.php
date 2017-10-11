<?php
/*
PAYNOW回传
*/
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");

include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
/*
$_POST['PayType'] = "03";
$_POST['OrderNo'] = "20080612003";
$_POST['TranStatus'] = "S";
$_POST['ATMNo'] = "436465";
*/
if (isset($_POST)){
	$online_cardnum = $_POST['ATMNo'];
	if($_POST['PayType'] == "01" || $_POST['PayType'] == "02"){
		if ($_POST['TranStatus'] == "S"){
			$db_string = $DB->compile_db_update_string( array (
			'online_paytype'          => intval($_POST['PayType']),
			'pay_state'               => 1,
			'onlinepay'               => 2,
			//'online_cardnum'          => intval($_POST['BuysafeNo']),
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".($_POST['OrderNo'])."'";
			$Result = $DB->query($Sql);
			//echo "訂單編號:" . $_POST['OrderNo'] . "交易成功";
			
		}else{
			//echo "訂單編號:" . $_POST['OrderNo'] . "交易失敗" . "<br>失敗原因" . $_POST['ErrDesc'];
		}
	}else if ($_POST['PayType'] == "03"){
		if ($_POST['TranStatus'] == "S"){
			$db_string = $DB->compile_db_update_string( array (
			'online_paytype'          => intval($_POST['PayType']),
			'pay_state'               => 1,
			'onlinepay'               => 2,
			'online_cardnum'          => ($_POST['ATMNo']),
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".($_POST['OrderNo'])."'";
			//echo $Sql ;
			$Result = $DB->query($Sql);
			//echo "訂單編號:" . $_POST['OrderNo'] . "交易成功" . "<br>虛擬帳號號碼:" . $_POST['ATMNo'];
			
		}else{
			//echo "訂單編號:" . $_POST['OrderNo'] . "交易失敗" ;
			$error =  "訂單編號:" . $_POST['lidm'] . "交易失敗" . "<br>失敗原因" . $_POST['errcode'] . ":" .  $_POST['errDesc'];
		}
	}
}

/**
 * 这里是把定单的表资料都取出来
 */


$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".trim($_POST[OrderNo])."' ");
$Num       = $DB->num_rows($Query);

if ( $Num <= 0 ){
	$Reprot ="error";
}

$Result    = $DB->fetch_array($Query);

switch (intval($Result['ifinvoice'])){
	case 0:
		$Ifinvoice   = $Cart[Two_piao];
		$Invoiceform = $Basic_Command['Null'];
		$TheOneNum   = $Basic_Command['Null'];
		break;
	case 1:
		$Ifinvoice   =  $Cart[Three_piao];
		$Invoiceform =  trim($Result['invoiceform']);
		$TheOneNum   =  "<font color=red>".trim($Result['invoice_num'])."</font>";

		break;
	case 2:
		$Ifinvoice   = $Cart[DontNeed_piao];
		$Invoiceform = $Basic_Command['Null'];
		$TheOneNum   = $Basic_Command['Null'];
		break;
}

/**
 * 数据变量
 */
$Order_id                             =  $Result['order_id'];
$tpl->assign("TheOneNum",             $TheOneNum); //統一編號
$tpl->assign("order_serial",          $Result['order_serial']);
$tpl->assign("createtime",            date("Y-m-d H:i a ",$Result['createtime']));
$tpl->assign("order_state",           $FUNCTIONS->OrderStateName($Result['order_state']) . "[" . $FUNCTIONS->OrderPayState(intval($Result['pay_state'])) . "]");
$tpl->assign("deliveryname",          $Result['paymentname']);
$tpl->assign("Product_totalprice",    trim($Result['totalprice']));
$tpl->assign("transport_price",       trim($Result['transport_price']));
$tpl->assign("paymentname",           trim($Result['deliveryname']));
$tpl->assign("Order_totalprice",      $Result['totalprice']+$Result['transport_price']);
$tpl->assign("invoiceform",           $Invoiceform);
$tpl->assign("atm",                   trim($Result['atm']));
$tpl->assign("receiver_name",         trim($Result['receiver_name']));
$tpl->assign("receiver_mobile",       trim($Result['receiver_mobile']));
$tpl->assign("receiver_email",        trim($Result['receiver_email']));
$tpl->assign("receiver_post",         trim($Result['receiver_post']));
$tpl->assign("receiver_tele",         trim($Result['receiver_tele']));
$tpl->assign("receiver_address",      trim($Result['receiver_address']));
$tpl->assign("receiver_memo",         trim($Result['receiver_memo']));
$tpl->assign("transport_content",     trim($Result['transport_content']));
$tpl->assign("transtime_name",        trim($Result['transtime_name']));
$tpl->assign("paycontent",            trim($Result['paycontent']));
$tpl->assign("Ifinvoice",             $Ifinvoice);
$discount_totalPrices  = $Result['discount_totalPrices'];
$Totalprice   = $Result['totalprice'];
$bonuspoint = $Result[bonuspoint];
$ticketcode = $Result[ticketcode];
if ($discount_totalPrices != $Totalprice){
	$tickets = "[折價後價格：" . $discount_totalPrices . "]";
	$tickets2 = "[折價後價格：" . ($discount_totalPrices+$Result[transport_price]) . "]";
	$tpl->assign("tickets",             $tickets);
	$tpl->assign("tickets2",             $tickets2);
}

$tot = $discount_totalPrices+$Result[transport_price];
if ($Result[monthcount] > 0){
	$monthprice = " 每期價格".(intval($tot/$Result[monthcount]));
}
$tpl->assign("bonuspoint",             $Result[bonuspoint]);
$tpl->assign("monthprice",             $monthprice);
$tpl->assign("tot",             $tot);

//支付方式帐号
$tpl->assign("online_cardnum",             "[".$online_cardnum."]");


$order_detail = array();

$Query_detail = $DB->query(" select gid,goodsname,market_price,price,goodscount from `{$INFO[DBPrefix]}order_detail` where order_id=".intval($Order_id)." order by order_detail_id desc ");
$i = 0 ;
while ($Rs_detail = $DB->fetch_array($Query_detail)){
	$order_detail[$i][gid]          = $Rs_detail[gid];
	$order_detail[$i][goodsname]    = $Rs_detail[goodsname];
	$order_detail[$i][market_price] = $Rs_detail[market_price];
	$order_detail[$i][price]        = $Rs_detail[price];
	$order_detail[$i][goodscount]   = $Rs_detail[goodscount];
	$i++;
}
$tpl->assign("order_detail",          $order_detail);
$tpl->assign("order_error",          $error);


$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Order_Pack);

$tpl->assign("Reprot",$Reprot);
$tpl->display("receivePay.html");
?>