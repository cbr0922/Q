<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");


include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
$order_serial    = $FUNCTIONS->Value_Manage("",$_POST['Serial'],'back','');
$receiver_email  = $FUNCTIONS->Value_Manage("",$_POST['email'],'back','');

$Query     = $DB->query("select * from `{$INFO[DBPrefix]}order_table` where order_serial='".trim($order_serial)."' and receiver_email ='".trim($receiver_email)."'  limit 0,1");
$Num       = $DB->num_rows($Query);

if ( $Num <= 0 ){
	$FUNCTIONS->sorry_back("close",$Basic_Command['Null']);
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

$Order_state      = $Result['order_state'];
	$Pay_state        = $Result['pay_state'];
	$Transport_state        = $Result['transport_state'];
$Order_id      = $Result['order_id'];
$Order_state   = intval($Result['order_state']);
/**
 * 数据变量
 */

$tpl->assign("TheOneNum",             $TheOneNum); //統一編號
$tpl->assign("order_serial",          $Result['order_serial']);
$tpl->assign("createtime",            date("Y-m-d H:i a ",$Result['createtime']));
$tpl->assign("order_state",           $orderClass->getOrderState($Order_state,1).",".$orderClass->getOrderState(intval($Pay_state),2).",".$orderClass->getOrderState(intval($Transport_state),3));
$tpl->assign("deliveryname",          $Result['paymentname']);
$tpl->assign("Product_totalprice",    trim($Result['totalprice']));
$tpl->assign("transport_price",       trim($Result['transport_price']));
$tpl->assign("paymentname",           trim($Result['deliveryname']));
$tpl->assign("Order_totalprice",      $Result['totalprice']+$Result['transport_price']);
$tpl->assign("invoiceform",           $Invoiceform);
$tpl->assign("atm",                   trim($Result['atm']));
$tpl->assign("receiver_name",         $FUNCTIONS->getOrderUInfo($Result['receiver_name'],1));
$tpl->assign("receiver_mobile",       "********");
$tpl->assign("receiver_email",        $FUNCTIONS->getOrderUInfo($Result['receiver_email'],5));
$tpl->assign("receiver_post",         trim($Result['receiver_post']));
$tpl->assign("receiver_tele",         "********");
$tpl->assign("receiver_address",      $FUNCTIONS->getOrderUInfo($Result['receiver_address'],10));
$tpl->assign("receiver_memo",         trim($Result['receiver_memo']));
$tpl->assign("transport_content",     trim($Result['transport_content']));
$tpl->assign("transtime_name",        trim($Result['transtime_name']));
$tpl->assign("paycontent",            trim($Result['paycontent']));
$tpl->assign("ticketid",        trim($Result['ticketid']));
$tpl->assign("ticketmoney",            trim($Result['ticketmoney']));
$tpl->assign("piaocode",            trim($Result['piaocode']));
$tpl->assign("Ifinvoice",             $Ifinvoice);
$tpl->assign("discount_totalPrices",             $Result['discount_totalPrices']);
$tpl->assign("ticket_discount_money",             $Result['ticket_discount_money']);
$tpl->assign("paycode",            trim($Result['paycode']));

if ($Result[discount_totalPrices]!= $Result['totalprice']){
		$tickets = "" . $Result[discount_totalPrices] . "(使用折價券或紅利折抵後金額)";
		$tickets2 = "[折價後價格：" . ($Result[discount_totalPrices]+$Result[transport_price]) . "]";
		$tpl->assign("tickets",             $tickets);
		$tpl->assign("tickets2",             $tickets2);
}

$tot = $Result['discount_totalPrices']+$Result['transport_price'];

	if ($Result[bonuspoint] > 0){
		$tot = $Result[totalprice]+$Result[transport_price]-$Result[bonuspoint];
		$tpl->assign("bonuspoint",             $Result[bonuspoint]+$Result[totalbonuspoint]);
	}
$order_detail = array();
$tpl->assign("tot",             $tot);
$Query_detail = $DB->query(" select g.*,gd.bn from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where order_id=".intval($Order_id)." order by order_detail_id desc ");
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
	
	$i++;
}
$tpl->assign("order_detail",          $order_detail);

$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Order_Pack);



$tpl->display("NoMember_ViewOrder.html");
?>
