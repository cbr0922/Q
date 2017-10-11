<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
$_GET['pid'] = 41;
include_once "PayFunction.php";
include_once "orderClass.php";
$orderClass = new orderClass;
$req = 'cmd=_notify-validate';
if(is_array($_POST)){
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
}
if(is_array($_GET)){

foreach ($_GET as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
}
/*
if(is_array($_SESSION)){
foreach ($_SESSION as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
}
*/
//建议在此将接受到的信息记录到日志文件中以确认是否收到 IPN 信息
$Creatfile ="log/".date("Ymd")."-" . time() . "-P" . $_GET['pid'];
$fh = fopen( $Creatfile.'.txt', 'w+' );
@chmod ($Creatfile.'.txt',0777);
fputs ($fh, $req, strlen($req) );
fclose($fh);
//$_POST['P_OrderNumber'] = "21";
include_once "SMTP.Class.inc.php";
				include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
				$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
if (isset($_POST)||isset($_GET)){
	$array = array_merge($_POST,$_GET);
	//$array['pid'] = $_GET['pid'];
	//$array['type'] = $_GET['type'];
	$payFunction = new PayFunction;
	$return_array = $payFunction->returnPay($array);
	//print_r($array);exit;
	$ip = $FUNCTIONS->getip();
	//if (!($_SESSION['shopping_ip'] == $ip && ($_SESSION['shopping_orderserial'] == trim($return_array[order_serial]) || $_SESSION['shopping_orderid'] == trim($return_array[order_id])))){
	////	$FUNCTIONS->header_location("../index.php");	
	//	exit;
	//}
	
	if ($return_array['order_serial'] != ""){
		$Sql = "select ot.*,od.goodsname from  `{$INFO[DBPrefix]}order_table` ot inner join  `{$INFO[DBPrefix]}order_detail` od on (ot.order_id=od.order_id) where ot.order_serial='".trim($return_array[order_serial])."' ";
		$Query = $DB->query($Sql);
		$Num =   $DB->num_rows($Query);
		if ($Num>0){
			$Rs    = $DB->fetch_array($Query);
			$paymentid  = $Rs['paymentid'];
			$deliveryid  = $Rs['deliveryid'];
			$order_id  = $Rs['order_id'];
			$iftogether  = $Rs['iftogether'];
			$provider_id  = $Rs['provider_id'];
			$Order_id  = $Rs['order_id'];
			$ifsendreceiver  = $Rs['ifsendreceiver'];
			$receiver_email  = $Rs['receiver_email'];
			//if (!($_GET['webtemp']==25 || intval($_GET['cvstemp'])==26) || $deliveryid==69 || $paymentid==70){
				//if (intval($return_array['paystate'])==1)
				//	$subsql = ",order_state=1";
				$db_string = $DB->compile_db_update_string( array (
				'online_paytype'          => intval($return_array['pay_type']),
				'pay_state'               => intval($return_array['paystate']),
				'onlinepay'               => 2,
				'online_cardnum'          => $return_array['account_no'],
				//'paytime'=>date("YmdHis")
				));
				if (intval($return_array['paystate'])==1)
					$orderClass->setOrderState(1,2,$Order_id,"線上支付",0,0);
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string" . $subsql . " WHERE order_serial='".($return_array['order_serial'])."'";
				$Result = $DB->query($Sql);
				//if (intval($return_array['paystate'])==1)
				//	$subsql = ",detail_order_state=1";
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_detail` set detail_pay_state=" . intval($return_array['paystate']) . "" . $subsql . " WHERE order_id='".($Rs['order_id'])."'";
				$Result = $DB->query($Sql);
				if(intval($return_array['paystate'])==0){
					$State = 0;	
				}
				if(intval($return_array['paystate'])==1 && $iftogether==0){
					//發貨通知
					$State = 1;	
					$Array =  array("Order_id"=>$order_id);
					$pSql = "select * from `{$INFO[DBPrefix]}provider` where provider_id='" . $provider_id . "'";
					$pQuery  = $DB->query($pSql);
					$pNum   = $DB->num_rows($pQuery);
					if($pNum>0){
						$pRs=$DB->fetch_array($pQuery);
						$SMTP->MailForsmartshop(trim($pRs['receive_mail1'] . "," . $pRs['receive_mail2'] . "," . $pRs['receive_mail3']),"",29,$Array);
					}
				}
				if(intval($return_array['paystate'])==2){
					$State = 2;	
				}
				
			//}
			$Order_id  = $Rs['order_id'];
			$Order_serial = $order_serial  = $Rs['order_serial'];
			$Cart_discount_totalPrices = $discount_totalPrices  = $Rs['discount_totalPrices'];
			$totalprice  = $Rs['totalprice'];
			$Cart_transmoney = $transport_price  = $Rs['transport_price'];
			$deliveryname  = $Rs['deliveryname'];
			$receiver_name = $Rs['receiver_name'];
			$receiver_address = $Rs['receiver_address'];
			$receiver_email = $Rs['receiver_email'];
			$receiver_post = $Rs['receiver_post'];
			$receiver_tele = $Rs['receiver_tele'];
			$receiver_mobile =$Rs['receiver_mobile'];
			$receiver_memo = $Rs['receiver_memo'];
			$MallgicOrderId = $Rs['MallgicOrderId'];
			$user_id = $Rs['user_id'];
		}
	


	if (($return_array['paystate'] == 2 || $return_array['paystate'] == 0 )&& $array['pid']!=3){
		$FUNCTIONS->header_location("payorder.php?orderno=" . trim($return_array[order_serial]));	
	
	
	}else{
		$Sql_u = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($user_id)."' limit 0,1";
				$Query_u  = $DB->query($Sql_u);
				$Num_u =   $DB->num_rows($Query_u);
				if($Num_u>0){
					$Rs_u=$DB->fetch_array($Query_u);
					$truename = $Rs_u['true_name'];
					$useremail = $Rs_u['email'];
				}else{
					$truename = $Rs['receiver_name'];
				}
				$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
				$Result= $DB->fetch_array($Query);
				if ($Result['mail']!=""){
					$cmail = $Result['mail'];	
				}
				$Array =  array("Order_id"=>$Order_id,"receiver_name"=>trim($receiver_name),"truename"=>trim($truename),"orderid"=>trim($Order_serial));
				
				
				//echo $_POST['ifsendreceiver'];echo "d";
				if($ifsendreceiver==1)
					$useremail .= "," . $receiver_email;
					//echo $useremail.$receiver_mobile;
				$SMTP->MailForsmartshop($useremail,$cmail,7,$Array);
				include_once "sms2.inc.php";
				include_once "sendmsg.php";
				$sendmsg = new SendMsg;
				$sendmsg->send(trim($receiver_mobile),$Array,8);
		//if ($_GET['webtemp']==25 || intval($_GET['cvstemp'])==26 || $paymentid==69 || $deliveryid==13){
		$FUNCTIONS->header_location("showorder.php?orderno=" . trim($return_array[order_serial]));	
	/*
	}else{
			$pay_array = array(
					  "order_serial"    =>   $Order_serial,
					  "total"    =>   round($Cart_discount_totalPrices+Cart_transmoney,0),
					  "receiver_name"   => $receiver_name,
					  "receiver_address"   => $receiver_address,
					  "receiver_tele"   => trim($receiver_tele),
					  "receiver_mobile"   => trim($receiver_mobile),
					  "receiver_email"   => trim($receiver_email),
					  "receiver_post"   => trim($receiver_post),
					  "order_id"   => $Order_id,
					  "userno"   => $uno,
					  );
			$payFunction = new PayFunction;
			echo $payform = $payFunction->CreatePay(69,$pay_array);
		}
		*/
		}
	}else{
		$FUNCTIONS->sorry_back("../index.php","支付失敗");	
	}
}

?>


