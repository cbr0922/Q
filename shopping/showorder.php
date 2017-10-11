<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include_once 'crypt.class.php';
include("global.php");
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
//$_POST['txid'] = "20091023002";
//print_r($_SESSION);
$orderno = $_GET['orderno'];
$first = $_GET['first'];

$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".trim($orderno)."'  and (ot.user_id='" . intval($_SESSION['user_id']) . "'or ot.session_id='" . $session_id . "')");
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

$Order_state      = $Result['order_state'];
	$Pay_state        = $Result['pay_state'];
	$Transport_state        = $Result['transport_state'];
/**
 * 数据变量
 */

$Order_id                             =  $Result['order_id'];
$tpl->assign("TheOneNum",             $TheOneNum); //統一編號
$tpl->assign("order_serial",          $Result['order_serial']);
$tpl->assign("createtime",            date("Y-m-d H:i a ",$Result['createtime']));
$tpl->assign("order_state",           $orderClass->getOrderState($Order_state,1).",".$orderClass->getOrderState(intval($Pay_state),2).",".$orderClass->getOrderState(intval($Transport_state),3));
$tpl->assign("deliveryname",          $Result['paymentname']);
$tpl->assign("Product_totalprice",    trim(floor($Result['totalprice'])));
$tpl->assign("transport_price",       trim(floor($Result['transport_price'])));
$tpl->assign("paymentname",           trim($Result['deliveryname']));
$tpl->assign("Order_totalprice",      floor($Result['totalprice']+$Result['transport_price']));
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
$tpl->assign("ifgroup",            trim($Result['ifgroup']));
$tpl->assign("discount_totalPrices",            trim($Result['discount_totalPrices']));
$tpl->assign("giveGroup",            trim($Result['giveGroup']));
$tpl->assign("buyPoint",            trim($Result['buyPoint']));
$tpl->assign("totalGrouppoint",            trim($Result['totalGrouppoint']));
$tpl->assign("Ifinvoice",             $Ifinvoice);
$tpl->assign("ticket_discount_money",             $Result['ticket_discount_money']);//折價券抵用
$tpl->assign("ifgroup",             $Result['ifgroup']);
$tpl->assign("paycode",            trim($Result['paycode']));
$tpl->assign("storename",            trim($Result['storename']));
$tpl->assign("flightstyle",            trim($Result['flightstyle']));
$tpl->assign("flightid",            trim($Result['flightid']));
$tpl->assign("flightno",            trim($Result['flightno']));
$tpl->assign("flightdate",            trim($Result['flightdate']));
$tpl->assign("Departure",            trim($Result['Departure']));
$tpl->assign("senddate",            trim($Result['senddate']));

$discount_totalPrices  = $Result['discount_totalPrices'];
$Totalprice   = $Result['totalprice'];
$bonuspoint = $Result[bonuspoint]+$Result['totalbonuspoint'];
$ticketcode = $Result[ticketcode];
if ($discount_totalPrices != $Totalprice){
	$tickets = "[折價後價格：" . $discount_totalPrices . "]";
	$tickets2 = "[折價後價格：" . ($discount_totalPrices+$Result[transport_price]) . "]";
	$tpl->assign("tickets",             $tickets);
	$tpl->assign("tickets2",             $tickets2);
}

$tot = $discount_totalPrices+$Result[transport_price];
$tpl->assign("bonuspoint",             $Result[bonuspoint]);
$tpl->assign("totalbonuspoint",             $Result[totalbonuspoint]);
if ($Result[monthcount] > 0){
	$monthprice = " 每期價格".(intval($tot/$Result[monthcount]));
}
$tpl->assign("monthprice",             $monthprice);
$tpl->assign("tot",             $tot);

//支付方式帐号
if($online_cardnum!="")
	$tpl->assign("online_cardnum",             "[".$online_cardnum."]");


$order_detail = array();
if (intval($Result['ifgroup'])==1){
	$Query_detail = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_group` as g where order_id=".intval($Order_id)."");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
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
		$i++;
	}
}else{
	$Query_detail = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where order_id=".intval($Order_id)." and g.packgid=0 order by order_detail_id asc ");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
		$order_detail[$i][gid]          = $Rs_detail[gid];
		$order_detail[$i][bn]          = $Rs_detail[bn];
		$order_detail[$i][goodsname]    = $Rs_detail[goodsname];
		$order_detail[$i][market_price] = floor($Rs_detail[market_price]);
		$order_detail[$i][price]        = floor($Rs_detail[price]);
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
		$order_detail[$i][ifpack]   = $Rs_detail[ifpack];
		$order_detail[$i][packgid]   = $Rs_detail[packgid];
		$order_detail[$i][goodstotal]   = $Rs_detail[goodscount]*$Rs_detail[price];
		$order_detail[$i]['order_state']  = $orderClass->getOrderState($Rs_detail['detail_order_state'],1) . "," . $orderClass->getOrderState($Rs_detail['detail_pay_state'],2) . "," . $orderClass->getOrderState($Rs_detail['detail_transport_state'],3);
		$order_detail[$i]['opList']   =        $orderClass->getUserDetailOp($Rs_detail['order_id'],$Rs_detail[order_detail_id]);
		if ($Rs_detail['memberorprice']==1){
			$buyprice = intval($Rs_detail[price]);
		}else{
			$buyprice = intval($Rs_detail[memberprice]) . "+" . intval($Rs_detail[combipoint]) . "積分";
		}
		$order_detail[$i][buyprice] = $buyprice;

		$i++;
	}
}
/*轉換代碼（訂購完成）*/
$track_id = '13';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
	if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
		$track_Js =$track_array[trackcode];
	}
	else $track_Js ="";
	$tpl->assign("googleConversion_track",   $track_Js);
}
/*FB追蹤*/
$track_id = '9';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
	if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
		$track_Js ="<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
		document,'script','//connect.facebook.net/en_US/fbevents.js');
		fbq('init', '" . $track_array[trackcode] . "');
		fbq('track', 'PageView');
		fbq('track', 'Purchase', {value:'".trim($Result['discount_totalPrices'])."', currency:'TWD'});
	</script>
	<noscript><img height='1' width='1' style='display:none'
src='https://www.facebook.com/tr?id=" . $track_array[trackcode] . "&ev=PageView&noscript=1'
/></noscript>
<!-- End Facebook Pixel Code -->";
	}
	else $track_Js ="";
	$tpl->assign("FbPixel_track",   $track_Js);
}
/**

  Google Analytics

*/
$track_id = '2';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
	if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
		$trid = $track_array[trid];
		$trackcode = $track_array[trackcode];
	}
	else {
		$track_id = '1';
		$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
		$Query   = $DB->query($Sql_track);
		while ($track_array  = $DB->fetch_array($Query)){
			if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
				$trid = $track_array[trid];
				$trackcode = $track_array[trackcode];
			}
			else{
				$trid = "";
				$trackcode = "";
			}
		}
	}
  $tpl->assign("trid",   $trid);
  $tpl->assign("trackcode",   $trackcode);
}
/*yahoo追蹤碼*/
$track_id = '17';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
	if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
		$track_Js =$track_array[trackcode];
	}
	else $track_Js ="";
	$tpl->assign("yahooUET_track",   $track_Js);
}
$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}user` where user_id='".$Result['user_id']."' limit 0,1");
$Num    = $DB->num_rows($Query);
$Result = $DB->fetch_array($Query);



$tpl->assign("Mobile",              MD5Crypt::Decrypt ($Result['other_tel'], $INFO['mcrypt'] )); //移动电话
$tpl->assign("certcode",              $Result['certcode']); //邮政编码
$tpl->assign("true_name",              $Result['true_name']);
$tpl->assign("username",              $Result['username']);
$tpl->assign("first",              $first);
$cn_secondname   = $Result['cn_secondname'];
$en_firstname   = $Result['en_firstname'];
$en_secondname   = $Result['en_secondname'];
$bornCountry   = $Result['bornCountry'];
$tpl->assign("cn_secondname",         $cn_secondname);
$tpl->assign("en_firstname",         $en_firstname);
$tpl->assign("en_secondname",         $en_secondname);
$tpl->assign("bornCountry",         $bornCountry);
$tpl->assign("order_detail",          $order_detail);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Order_Pack);
$tpl->assign("ifsenddate",  intval($INFO['ifsenddate']));
$tpl->assign("Reprot",$Reprot);
$tpl->display("receivePay.html");
?>
