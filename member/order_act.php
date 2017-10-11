<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
$order_id = $_POST['order_id'];
$optype = $_POST['optype'];
$state_value = $_POST['state_value'];
$state_type = $_POST['state_type'];
$remark = $_POST['remark'];
$detail_id = $_POST['detail_id'];

if($optype == 1){
	$orderClass->setOrderState($state_value,$state_type,$order_id,$remark,0);
	$Url = "MyOrder.php";
}elseif($detail_id>0){
	$orderClass->setOrderDetailState($state_value,$state_type,$order_id,$detail_id,$remark,0,1,0);
	$orderClass->setOrderState($state_value,$state_type,$order_id,$remark,0,1);
	$Url = "ViewOrder.php";
}

$FUNCTIONS->header_location($Url."?Action=Modi&order_id=".$order_id);
?>