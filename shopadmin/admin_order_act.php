<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
//print_r($_POST);exit;
$order_id = $_POST['Order_id'];
$optype = $_POST['optype'];
$state_value = $_POST['state_value'];
$state_type = $_POST['state_type'];
 $remark = $_POST['remark'];
$hadsend = $_POST['tonum'];
$backPrice = $_POST['backPrice'];
$backBouns = $_POST['backBouns'];


if ($_GET['op']=="checked" || $_GET['op']=="nochecked"){
	$state_value = $_GET['state_value'];
	$state_type = $_GET['state_type'];
	$order_id = $_GET['order_id'];
	$detail_id = $_GET['detail_id'];
	$state_action_id = $_GET['order_action_id'];		
	$Url = "admin_order.php";
}
if ($_POST['act']=="Del"){
	if (is_array($_POST['cid'])){
		foreach($_POST['cid'] as $k=>$v){
			$Sql = "delete  {$INFO[DBPrefix]}order_table ,{$INFO[DBPrefix]}order_detail  from `{$INFO[DBPrefix]}order_table`  left join `{$INFO[DBPrefix]}order_detail` on ({$INFO[DBPrefix]}order_table.order_id={$INFO[DBPrefix]}order_detail.order_id)  where {$INFO[DBPrefix]}order_table.order_id=".intval($v);
			$DB->query($Sql);
		}	
	}	
	$Url = "admin_order_list.php";
	$FUNCTIONS->setLog("刪除訂單");
}elseif ($_GET['op']=="checked"){
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
			$orderClass->setOrderState($state_value,$state_type,$v,$remark.$_POST['piaocode'],1);
			$orderClass->postMail($v,$state_value,$state_type);
		}	
	}	
	$Url = "admin_order_list.php";
	$FUNCTIONS->setLog("更改訂單狀態");
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
				if($k == 0)
					$orderClass->setOrderDetailState($state_value,$state_type,$order_id,$v,$remark.$_POST['piaocode'],$hadsend[$k],$single,1,$backPrice,$backBouns);
				else
					$orderClass->setOrderDetailState($state_value,$state_type,$order_id,$v,$remark.$_POST['piaocode'],$hadsend[$k],$single,1);
			}
			$orderClass->setOrderState($state_value,$state_type,$order_id,$remark,1,1);
			$orderClass->postMail($order_id,$state_value,$state_type);
		//}
	}
	$Url = "admin_order.php";
	$FUNCTIONS->setLog("更改訂單狀態");
}

//echo $Url;
if($_POST['Url']!="")
	$Url=$_POST['Url'];
$FUNCTIONS->header_location($Url."?Action=Modi&order_id=".$order_id);
?>