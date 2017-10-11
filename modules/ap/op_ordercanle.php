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
		$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);

//01:00執行 未付款的訂單失效
$d = date('d',time())-1;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$starttime = gmmktime(0,0,0,$m,$d,$y);
$overtime = gmmktime(0,0,0,$m,$d+1,$y);

echo $Sql_order = "select o.*,u.email,u.true_name from `{$INFO[DBPrefix]}order_table` as o inner join `{$INFO[DBPrefix]}user` as u on o.user_id=u.user_id where date_format(flightdate,'%Y-%m-%d')<='" . date("Y-m-d",$starttime) . "'";
$Query_order  = $DB->query($Sql_order);
$Num    = $DB->num_rows($Query_order); 
while($Rs_order=$DB->fetch_array($Query_order)){
	$orderClass->setOrderState(5,1,$Rs_order['order_id'],"訂單失效(系統自動)");
}

?>