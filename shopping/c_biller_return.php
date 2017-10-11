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
	include 'auth_mpi_mac8.php';
//print_r($_POST);
/*
if ($_POST['merID'] == '862')
		$key = $INFO['Shop_three_key_I'];
elseif ($_POST['merID'] == '863')
		$key = $INFO['Shop_three_key_II'];
elseif ($_POST['merID'] == '864')
		$key = $INFO['Shop_three_key_III'];
$MACString=auth_out_mac($_POST['status'],$_POST['errcode'],$_POST['authCode'],$_POST['authAmt'],$_POST['lidm'],$_POST['OffsetAmt'],$_POST['OriginalAmt']
,$_POST['UtilizedPoint'],'',$$_POST['Last4digitPAN'],$key,1);
*/
if (isset($_POST)){
	if ($_POST['status']=="0" && $_POST['lidm']!="" && $_POST['Last4digitPAN']!="" && $_POST['xid']!=""){
		$Sql = "select ot.order_serial,ot.order_id,od.goodsname from  `{$INFO[DBPrefix]}order_table` ot inner join  `{$INFO[DBPrefix]}order_detail` od on (ot.order_id=od.order_id) where ot.order_serial='".trim($_POST[lidm])."' ";
		$Query = $DB->query($Sql);
		$Num =   $DB->num_rows($Query);
		if ($Num>0){
			$online_cardnum = $_POST['Last4digitPAN'];
			$db_string = $DB->compile_db_update_string( array (
				'online_paytype'          => 3,
				'pay_state'               => 1,
				'onlinepay'               => 2,
				'online_cardnum'          => $online_cardnum,
				));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".($_POST['lidm'])."'";
			$Result = $DB->query($Sql);
			$Rs = $DB->fetch_array($Query);
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_detail` SET detail_pay_state=1 WHERE order_id='".intval($Rs['order_id'])."'";
			$Result = $DB->query($Sql);
			$Reprot ="pass";
		}else{
			$Reprot ="error";			
		}
	}else{
		$error =  "訂單編號:" . $_POST['lidm'] . "交易失敗" . "<br>失敗原因" . $_POST['errcode'] . ":" .  $_POST['errDesc'];
			//exit;
		$Reprot ="error";		
	}
}

/**
 * 这里是把定单的表资料都取出来
 */


$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".trim($_POST[lidm])."' ");
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
$tpl->assign("order_state",           $FUNCTIONS->OrderPayState(intval($Result['pay_state'])));
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
$tpl->assign("status",$_POST['status']);
$tpl->assign("errcode",$_POST['errcode']);
$tpl->assign("Last4digitPAN",$_POST['Last4digitPAN']);
//exit;
if ($Reprot == "error"){
?>
<script language="javascript">
   location.href = "../shopping/payorder.php?orderno=<?php echo trim($Result['order_serial'])?>&error=<?php echo $error;?>";
</script>
<?php

}

$tpl->assign("Reprot",$Reprot);
$tpl->display("receivePay.html");
?>