<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
//print_r($_POST);
$order_id = $_POST['Order_id'];
$optype = $_POST['optype'];
$state_value = $_POST['state_value'];
$state_type = $_POST['state_type'];
$remark = $_POST['remark'];
$hadsend = $_POST['tonum'];
if ($_GET['op']=="checked" || $_GET['op']=="nochecked"){
	$state_value = $_GET['state_value'];
	$state_type = $_GET['state_type'];
	$order_id = $_GET['order_id'];
	$detail_id = $_GET['detail_id'];
	$state_action_id = $_GET['order_action_id'];		
	$Url = "provider_order.php";
}
//echo $order_id;
if ($_GET['op']=="checked"){
	if ($detail_id>0){
		$orderClass->setOrderDetailState($state_value,$state_type,$order_id,$detail_id,"審核通過",-2,1);
		$orderClass->setOrderState($state_value,$state_type,$order_id,$remark,1,1);
	}
	else
		$orderClass->setOrderState($state_value,$state_type,$order_id,"審核通過");
	$orderClass->checkStateAction($state_action_id,1);
	$FUNCTIONS->setLog("審核訂單");
}elseif($_GET['op']=="nochecked"){
	if ($detail_id>0){
		$orderClass->setOrderDetailState($state_value,$state_type,$order_id,$detail_id,"審核未通過",-2,1);
		$orderClass->setOrderState($state_value,$state_type,$order_id,$remark,1,1);
	}
	else
		$orderClass->setOrderState($state_value,$state_type,$order_id,"審核未通過");
	$orderClass->checkStateAction($state_action_id,2);
	$FUNCTIONS->setLog("審核訂單");
}elseif($optype == 1){
	if (is_array($_POST['cid'])){
		foreach($_POST['cid'] as $k=>$v){
			$orderClass->setOrderState($state_value,$state_type,$v,$remark);
			$orderClass->postMail($order_id,$state_value,$state_type);
		}	
	}	
	$Url = "provider_order_list.php";
}elseif($order_id>0){
	if (is_array($_POST['cid'])){
		
		$order_detail_count = count($_POST['cid']);
		$Sql_order = "select * from `{$INFO[DBPrefix]}order_detail` where order_id = '" . intval($order_id) . "'";
		$Query_order  = $DB->query($Sql_order);
		$order_count=$DB->num_rows($Query_order);
		if ($order_count==$order_detail_count){
			//$orderClass->setOrderState($state_value,$state_type,$order_id,$remark);
			//$orderClass->postMail($order_id,$state_value,$state_type);
			$single = 0;
		}else{
			$single = 1;
		}
			foreach($_POST['cid'] as $k=>$v){
				$orderClass->setOrderDetailState($state_value,$state_type,$order_id,$v,$remark . $_POST['piaocode'],$hadsend[$k],$single);
			}
			$orderClass->setOrderState($state_value,$state_type,$order_id,$remark,1,1);
			$orderClass->postMail($order_id,$state_value,$state_type);
		//}
	}
	$Url = "provider_order.php";
}
$FUNCTIONS->setLog("更改訂單狀態");
//echo $Url;
$FUNCTIONS->header_location($Url."?Action=Modi&order_id=".$order_id);
?>