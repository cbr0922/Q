<?php
error_reporting(7);
set_time_limit(60*60*5);
@header("Content-type: text/html; charset=utf-8");
include("../../configs.inc.php");
include("global.php");
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
echo "開始執行時間：" . date("Y-m-d H:i:s");
include_once "SMTP.Class.inc.php";
		include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$SMTP =  new smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
include_once "sms2.inc.php";
	include_once "sendmsg.php";
	$sendmsg = new SendMsg;
//23:00執行 提醒取貨
$d = date('d',time())-3;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$starttime1 = gmmktime(0,0,0,$m,$d,$y);
$overtime1 = gmmktime(0,0,0,$m,$d+1,$y);

$d = date('d',time())-5;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$starttime2 = gmmktime(0,0,0,$m,$d,$y);
$overtime2 = gmmktime(0,0,0,$m,$d+1,$y);
$Sql_order = "select * from `{$INFO[DBPrefix]}order_action` as oa inner join `{$INFO[DBPrefix]}order_table` as ot on oa.order_id=ot.order_id where ((oa.actiontime>='" . $starttime1 . "' and oa.actiontime<='" . $overtime1 . "') or (oa.actiontime>='" . $starttime2 . "' and oa.actiontime<='" . $overtime2 . "')) and oa.state_type=3 and (oa.state_value=16) and ot.transport_state=16 and( ot.order_state=1 or ot.order_state=0) group by oa.order_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$order_id     = $Rs_order['order_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		$okmap      = $Rs_order['okmap'];
		$goods_arrive_date      = $Rs_order['goods_arrive_date'];
		$d = substr($goods_arrive_date,6,2)+6;
		$y = substr($goods_arrive_date,0,4);
		$m = substr($goods_arrive_date,4,2);
		$givetime = mktime(0,0,0,$m,$d,$y);
		$givedate = date("Y-m-d",$givetime);
	$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_day"=>$Pay_Idate,"okmap"=>$okmap,"givedate"=>$givedate);
	$SMTP->MailForsmartshop($Receiver_email,"",20,$Array);
	
			
	
	$sendmsg->send($receiver_mobile,$Array,20);
}

//提醒發貨

$d = date('d',time())-7;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$starttime = gmmktime(0,0,0,$m,$d,$y);
$overtime = gmmktime(0,0,0,$m,$d+5,$y);

$Sql_order = "select * from `{$INFO[DBPrefix]}order_action` as oa inner join `{$INFO[DBPrefix]}order_table` as ot on oa.order_id=ot.order_id where oa.actiontime>='" . $starttime . "' and oa.actiontime<='" . $overtime . "' and oa.state_type=2 and oa.state_value=1 and ot.transport_state=0 and ot.pay_state=1 and ot.provider_id>0 group by oa.order_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$order_id     = $Rs_order['order_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$Provider_id      = $Rs_order['provider_id'];
		$actiontime      = $Rs_order['actiontime'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		
	$dates = floor((time()-$actiontime)/(60*60*24));
	switch($dates){
		case 3:
			$mailno = 24;
			break;
		case 4:
			$mailno = 30;
			break;
		case 5:
			$mailno = 31;
			break;
		case 6:
			$mailno = 32;
			break;
		case 7:
			$mailno = 33;
			break;
	}
	
	if($Provider_id>0){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Provider_id)." limit 0,1");
		$Num   = $DB->num_rows($Query);
		$Result= $DB->fetch_array($Query);
		$receive_mail1           =  $Result['receive_mail1'];
		$receive_mail2           =  $Result['receive_mail2'];
		$receive_mail3           =  $Result['receive_mail3'];
		$receivemail =  $receive_mail1 . "," . $receive_mail2 . "," . $receive_mail3;
	}
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
	$Result= $DB->fetch_array($Query);
	if ($Result['mail']!=""){
		$cmail = $Result['mail'];	
	}
		
	$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_day"=>$Pay_Idate);
	$SMTP->MailForsmartshop($receivemail,$cmail . "," . $INFO['email'],$mailno,$Array);
}
?>