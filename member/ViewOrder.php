<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";

include("../configs.inc.php");
include_once 'crypt.class.php';
/**
 *  装载客户服务语言包
 */

include "../language/".$INFO['IS']."/Order_Pack.php";

include_once Classes . "/orderClass.php";
$orderClass = new orderClass;

$Order_id  = $FUNCTIONS->Value_Manage($_GET['order_id'],'','back','');
//$Query     = $DB->query("select * from `{$INFO[DBPrefix]}order_table` where order_id=".intval($Order_id)." limit 0,1");


$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id='".intval($Order_id)."' and ot.user_id='" . intval($_SESSION['user_id']) . "' limit 0,1");
$Num       = $DB->num_rows($Query);

if ( $Num <= 0 ){
	$FUNCTIONS->sorry_back("close","");
}

$Result    = $DB->fetch_array($Query);

switch (intval($Result['ifinvoice'])){
	case 0:
		$Ifinvoice   = $Order_Pack[Two_piao];
		$Invoiceform = $Basic_Command['Null'];
		$TheOneNum   = $Basic_Command['Null'];
		break;
	case 1:
		$Ifinvoice   =  $Order_Pack[Three_piao];
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

/**
 * 数据变量
 */

$tpl->assign("TheOneNum",             $TheOneNum); //統一編號
$tpl->assign("order_serial",          $Result['order_serial']);
$tpl->assign("createtime",            date("Y-m-d H:i a ",$Result['createtime']));
$tpl->assign("order_state",           $orderClass->getOrderState($Order_state,1));
$tpl->assign("Pay_state",          $orderClass->getOrderState(intval($Pay_state),2));
$tpl->assign("Transport_state",          $orderClass->getOrderState(intval($Transport_state),3));
$tpl->assign("deliveryname",          $Result['paymentname']);
$tpl->assign("Product_totalprice",    trim(floor($Result['totalprice'])));
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
$tpl->assign("deliveryid",            trim($Result['deliveryid']));
$tpl->assign("ticketid",        trim($Result['ticketid']));
$tpl->assign("ticketmoney",            trim($Result['ticketmoney']));
$tpl->assign("piaocode",            trim($Result['piaocode']));
$tpl->assign("Ifinvoice",             $Ifinvoice);
$tpl->assign("discount_totalPrices",             $Result['discount_totalPrices']+$Result['buyPoint']);
$tpl->assign("ticket_discount_money",             $Result['ticket_discount_money']);
$tpl->assign("ifgroup",             $Result['ifgroup']);
$tpl->assign("totalGrouppoint",             $Result['totalGrouppoint']);
$tpl->assign("buyPoint",            trim($Result['buyPoint']));
$tpl->assign("paycode",            trim($Result['paycode']));
$tpl->assign("storename",            trim($Result['storename']));
$tpl->assign("flightstyle",            trim($Result['flightstyle']));
$tpl->assign("flightid",            trim($Result['flightid']));
$tpl->assign("flightno",            trim($Result['flightno']));
$tpl->assign("flightdate",            trim($Result['flightdate']));
$tpl->assign("Departure",            trim($Result['Departure']));
$tpl->assign("senddate",            trim($Result['senddate']));
$tpl->assign("ifsenddate",  intval($INFO['ifsenddate']));
$tpl->assign("invoice_code",          $Result['invoice_code']);
$tpl->assign("totalbonuspoint",             $Result[totalbonuspoint]);
if ($Result[discount_totalPrices]!= $Result['totalprice']){
		$tickets = "" . $Result[discount_totalPrices] . "(使用折價券或紅利折抵後金額)";
		$tickets2 = "[折價後價格：" . ($Result[discount_totalPrices]+$Result[transport_price]) . "]";
		$tpl->assign("tickets",             $tickets);
		$tpl->assign("tickets2",             $tickets2);
}

$tot = $Result['discount_totalPrices']+$Result['transport_price'];

	if ($Result[bonuspoint] > 0){
		$tpl->assign("bonuspoint",             $Result[bonuspoint]+$Result[totalbonuspoint]);
	}
$order_detail = array();
$tpl->assign("tot",             $tot);
if (intval($Result['ifgroup'])==1){
	$Query_detail = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_group` as g where order_id=".intval($Order_id)."");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
		//gdid,order_id,groupname,count,groupprice,grouppoint,groupbn,goodslist,subject,subjectcontent
		$order_detail[$i][gdid]          = $Rs_detail[gdid];
		$order_detail[$i][groupname]          = $Rs_detail[groupname];
		$order_detail[$i]['count']    = $Rs_detail['count'];
		$order_detail[$i][groupprice] = $Rs_detail[groupprice];
		$order_detail[$i][grouppoint]        = $Rs_detail[grouppoint];
		$order_detail[$i][groupbn]   = $Rs_detail[groupbn];
		$order_detail[$i][goodslist]   = $Rs_detail[goodslist];
		$order_detail[$i][subject]   = $Rs_detail[subject];
		$order_detail[$i][subjectcontent]   = $Rs_detail[subjectcontent];
		$Query_g = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_detail` as g where order_group_id=".intval($Rs_detail[order_group_id])." limit 0,1");
		$Rs_g = $DB->fetch_array($Query_g);
		$order_detail[$i]['order_state']  = $orderClass->getOrderState($Rs_g['detail_order_state'],1) . "," . $orderClass->getOrderState($Rs_g['detail_pay_state'],2) . "," . $orderClass->getOrderState($Rs_g['detail_transport_state'],3);
		$order_detail[$i]['detail_transport_state']  = $Rs_g['detail_transport_state'];
		$i++;
	}
}else{
	$Query_detail = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where order_id=".intval($Order_id)." and packgid=0 order by order_detail_id asc ");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
		$order_detail[$i][gid]          = $Rs_detail[gid];
		$order_detail[$i][bn]          = $Rs_detail[bn];
		$order_detail[$i][goodsname]    = $Rs_detail[goodsname];
		$order_detail[$i][market_price] = $Rs_detail[market_price];
		$order_detail[$i][price]        = $Rs_detail[price];
		$order_detail[$i][goodscount]   = $Rs_detail[goodscount];
		$order_detail[$i][goodstotal]   = $Rs_detail[goodscount]*$Rs_detail[price];
		$order_detail[$i][good_color]   = $Rs_detail[good_color];
		$order_detail[$i][good_size]   = $Rs_detail[good_size];
		$order_detail[$i][detail_id]   = $Rs_detail[detail_id];
		$order_detail[$i][detail_name]   = $Rs_detail[detail_name];
		$order_detail[$i][detail_bn]   = $Rs_detail[detail_bn];
		$order_detail[$i][detail_des]   = $Rs_detail[detail_des];
		$order_detail[$i][ifchange]   = $Rs_detail[ifchange];
		$order_detail[$i][ifxygoods]   = $Rs_detail[ifxygoods];
		$order_detail[$i][xygoods_des]   = $Rs_detail[xygoods_des];
		$order_detail[$i][ifpack]   = $Rs_detail[ifpack];
		$order_detail[$i][packgid]   = $Rs_detail[packgid];
		$order_detail[$i][hadsend]   = $Rs_detail[hadsend];
		$order_detail[$i]['order_state']  = $orderClass->getOrderState($Rs_detail['detail_order_state'],1) . "," . $orderClass->getOrderState($Rs_detail['detail_pay_state'],2) . "," . $orderClass->getOrderState($Rs_detail['detail_transport_state'],3);
		$order_detail[$i]['detail_transport_state']  = $Rs_detail['detail_transport_state'];
		$order_detail[$i]['opList']   =        $orderClass->getUserDetailOp($Rs_detail['order_id'],$Rs_detail[order_detail_id]);
		//if ($Rs_detail['memberorprice']==1){
			$buyprice = intval($Rs_detail[price]);
		//}//else{
		//	$buyprice = intval($Rs_detail[memberprice]) . "+" . intval($Rs_detail[combipoint]) . "積分";
		//}
		$order_detail[$i][buyprice] = $buyprice;

		$i++;
	}
}
$tpl->assign("order_detail",          $order_detail);

$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}user` where user_id=".$_SESSION['user_id']." limit 0,1");
$Num    = $DB->num_rows($Query);
$Result = $DB->fetch_array($Query);



$tpl->assign("Mobile",              MD5Crypt::Decrypt ($Result['other_tel'], $INFO['mcrypt'] )); //移动电话
$tpl->assign("certcode",              $Result['certcode']); //邮政编码
$cn_secondname   = $Result['cn_secondname'];
$en_firstname   = $Result['en_firstname'];
$en_secondname   = $Result['en_secondname'];
$bornCountry   = $Result['bornCountry'];
$tpl->assign("cn_secondname",         $cn_secondname);
$tpl->assign("en_firstname",         $en_firstname);
$tpl->assign("en_secondname",         $en_secondname);
$tpl->assign("bornCountry",         $bornCountry);

$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Order_Pack);
$tpl->display("ViewOrder.html");
?>
