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

//24:00執行 未付款的訂單失效
$d = date('d',time())-3;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$starttime = gmmktime(0,0,0,$m,$d,$y);
$overtime = gmmktime(0,0,0,$m,$d+1,$y);

$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where (pay_state<>6 and pay_state<>1 and pay_state<>7) and createtime<='" . $starttime . "' and order_state<>3 and order_state<>5";
$Query_order  = $DB->query($Sql_order);
$Num    = $DB->num_rows($Query_order); 
while($Rs_order=$DB->fetch_array($Query_order)){
	$orderClass->setOrderState(5,1,$Rs_order['order_id'],"訂單失效(系統自動)");
}

//取貨期限6天(下單日+6天 24:00)，第7天未取貨系統自動做退貨
$d = date('d',time())-9;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$starttime = gmmktime(0,0,0,$m,$d,$y);
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where deliveryid=23 and transport_state=16 and goods_arrive_date<='" . date("Ymd",$starttime) . "' and order_state<>3 and order_state<>5 and order_state<>4 and order_state<>7";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	$orderClass->setOrderState(17,3,$Rs_order['order_id'],"訂單逾期退貨(系統自動)");
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
		
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
	$Result= $DB->fetch_array($Query);
	if ($Result['mail']!=""){
		$cmail = $Result['mail'];	
	}
	$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($User_id)."' limit 0,1";
	$Query  = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
	$ctel = $Rs[tel].",".$Rs['other_tel'];//电话
	$recommendno = $Rs['recommendno'];
	$companyid = $Rs['companyid'];
	$truename = $Rs['true_name'];
	$useremail = $Rs['email'];
	
	$Sql = "select * from `{$INFO[DBPrefix]}baduser` where user_id='" . intval($User_id) . "'";
	$Query  = $DB->query($Sql);
	//$Rs=$DB->fetch_array($Query);
	$Num    = $DB->num_rows($Query);
	if($Num>0){
		$b_Sql = "update from `{$INFO[DBPrefix]}baduser` set count=count+1 where user_id='" . intval($User_id) . "'";	
	}else{
		$b_Sql = "insert into `{$INFO[DBPrefix]}baduser` (user_id,count)values('" . intval($User_id) . "',1)";	
	}
	$DB->query($b_Sql);
	$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_day"=>$Pay_Idate);
	$SMTP->MailForsmartshop($useremail,$cmail,27,$Array);
}


$d = date('d',time())-7;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$starttime = gmmktime(0,0,0,$m,$d,$y);
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$Sql_order = "select * from `{$INFO[DBPrefix]}order_action` as oa where oa.actiontime<='" . $overtime . "' and oa.state_type=3 and (oa.state_value=2 || oa.state_value=18) group by oa.order_id,oa.order_detail_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	if($Rs_order['order_detail_id']>0)
		$subsql = "od.order_detail_id=" . $Rs_order['order_detail_id'];
	else
		$subsql = "od.order_id=" . $Rs_order['order_id'];
	$d_sql  = "select * from `{$INFO[DBPrefix]}order_detail` as od where " . $subsql . " and (od.detail_order_state=1 or od.detail_order_state=0) and (od.detail_transport_state=2 or od.detail_transport_state=18) and od.detail_pay_state=1";
	$d_Query  = $DB->query($d_sql);
	while($d_Rs=$DB->fetch_array($d_Query)){
		$orderClass->setOrderDetailState(4,1,$d_Rs['order_id'],$d_Rs['order_detail_id'],"到貨/取貨7天后自動完成交易",-1,0,1,0,0);
		$u_Sql = "update `{$INFO[DBPrefix]}goods` set gid='" . $d_Rs['gid'] ."' where salenum=salenum+1";
		$DB->query($u_Sql);
	}
	$orderClass->setOrderState(4,1,$Rs_order['order_id'],"到貨/取貨7天后自動完成交易",1,1);
}

//商品庫存提醒

$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
$Result= $DB->fetch_array($Query);
if ($Result['mail']!=""){
  	$cmail = $Result['mail'];	
}
$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where storage<alarmnum");
while($Rs=$DB->fetch_array($Query)){
	if($Rs['provider_id']>0){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Rs['provider_id'])." limit 0,1");
		$Num   = $DB->num_rows($Query);
		$Result= $DB->fetch_array($Query);
		$receive_mail1           =  $Result['receive_mail1'];
		$receive_mail2           =  $Result['receive_mail2'];
		$receive_mail3           =  $Result['receive_mail3'];
		$receivemail =  $receive_mail1 . "," . $receive_mail2 . "," . $receive_mail3;
	}
	$Array =  array("goodsname"=>$Rs['goodsname'],"bn"=>trim($Rs['bn']),"storage"=>trim($Rs['storage']));
	$SMTP->MailForsmartshop($cmail . "," . $receivemail,"",34,$Array);
}
$Query = $DB->query("select gd.*,g.goodsname,g.bn,g.provider_id from `{$INFO[DBPrefix]}goods_detail` as gd inner join `{$INFO[DBPrefix]}goods` as g on gd.gid=g.gid where g.storage>=g.alarmnum and gd.storage<g.alarmnum");
while($Rs=$DB->fetch_array($Query)){
	if($Rs['provider_id']>0){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Rs['provider_id'])." limit 0,1");
		$Num   = $DB->num_rows($Query);
		$Result= $DB->fetch_array($Query);
		$receive_mail1           =  $Result['receive_mail1'];
		$receive_mail2           =  $Result['receive_mail2'];
		$receive_mail3           =  $Result['receive_mail3'];
		$receivemail =  $receive_mail1 . "," . $receive_mail2 . "," . $receive_mail3;
	}
	$Array =  array("goodsname"=>$Rs['goodsname'],"bn"=>trim($Rs['bn']),"storage"=>trim($Rs['storage']),"goodsdetail"=>trim($Rs['detail_name'] . "(" . $Rs['detail_bn'] . ")"));
	$SMTP->MailForsmartshop($cmail . "," . $receivemail,"",34,$Array);
}

$Query      = $DB->query("select s.*,g.goodsname,g.bn,g.provider_id from `{$INFO[DBPrefix]}storage` as s inner join `{$INFO[DBPrefix]}goods` as g on s.goods_id=g.gid where g.storage>=g.alarmnum and s.storage<g.alarmnum");
while($Rs=$DB->fetch_array($Query)){
	if($Rs['provider_id']>0){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Rs['provider_id'])." limit 0,1");
		$Num   = $DB->num_rows($Query);
		$Result= $DB->fetch_array($Query);
		$receive_mail1           =  $Result['receive_mail1'];
		$receive_mail2           =  $Result['receive_mail2'];
		$receive_mail3           =  $Result['receive_mail3'];
		$receivemail =  $receive_mail1 . "," . $receive_mail2 . "," . $receive_mail3;
	}
	$Array =  array("goodsname"=>$Rs['goodsname'],"bn"=>trim($Rs['bn']),"storage"=>trim($Rs['storage']),"goods_color"=>trim($Rs['color']),"goods_size"=>trim($Rs['size']));
	$SMTP->MailForsmartshop($cmail . "," . $receivemail,"",34,$Array);
}
?>