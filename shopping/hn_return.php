<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Order_Pack.php";


if (isset($_POST)){
	$posterip	=	$_SERVER['REMOTE_ADDR'];		
	$FromTrustServer = false;		
	$TrustHost_Prod = gethostbyname ("59.125.96.122");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}		
		
		//2 60.248.7.23
		$TrustHost_Prod = gethostbyname ("60.248.7.23");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}		
		
		//3 59.124.227.218
		$TrustHost_Prod = gethostbyname ("59.124.227.218");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}		
		
		//4 210.65.204.100
		$TrustHost_Prod = gethostbyname ("210.65.204.100");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}	
		
		//5 61.219.56.3
		$TrustHost_Prod = gethostbyname ("61.219.56.3");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}	

		if ($FromTrustServer==true) {
			$Reprot ="passed";
			
		} else {
			$Reprot ="error";
		}	
		if ($Reprot =="passed"){
			$r_OrderNum= $_POST['OrderNum'];
			$orderno = "2" . substr($r_OrderNum,4);
			
			$online_cardnum = $_POST[INACCTNO];
			$db_string = $DB->compile_db_update_string( array (
			'online_paytype'          => 1,
			'pay_state'               => 1,
			'onlinepay'               => 2,
			'online_cardnum'          => intval($online_cardnum),
			));
			$Sql = "select ot.order_serial,ot.order_id,od.goodsname from  `{$INFO[DBPrefix]}order_table` ot inner join  `{$INFO[DBPrefix]}order_detail` od on (ot.order_id=od.order_id) where ot.order_serial='".trim($orderno)."' ";
			$Query = $DB->query($Sql);
			$Num =   $DB->num_rows($Query);
			if ($Num>0){
				$Rs = $DB->fetch_array($Query);
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".intval($_POST['txid'])."'";
				$Result = $DB->query($Sql);
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_detail` SET detail_pay_state=1 WHERE order_id='".intval($Rs['order_id'])."'";
				$Result = $DB->query($Sql);
				if ($Result) {
					$Reprot ="pass";
				}
			}
		}

}


/**
 * 这里是把定单的表资料都取出来
 */


$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".trim($orderno)."' ");
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



$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Order_Pack);

if ($Reprot == "error"){
		  $error = "錯誤碼：" . $_POST['errcode'] . " 錯誤信息：" .  $_POST['errDesc'];
?>
<script language="javascript">
   location.href = "payorder.php?orderno=<?php echo trim($Result['order_serial'])?>&error=<?php echo $error;?>";
</script>
<?php

}

$tpl->assign("Reprot",$Reprot);
$tpl->display("receivePay.html");
?>


