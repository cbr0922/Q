<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
session_start();
include ("../../configs.inc.php");
$fb_url = "http://os.iqbi.com/utvfb/method.aspx";
$moid = $_GET['moid'];
$op = $_GET['op'];
$utvorderid = $_GET['utvorderid'];
$token="d96ef98d76a64c2c9d7e5a0d819efacd";

if($op == "cartinfo"){
	$result = file_get_contents($fb_url . "?op=" . $op . "&moid=" . $moid . "&token=" . $token);
	$result_array = json_decode($result,TRUE);	
	//print_r($result_array);exit;
	$MallgicOrderId = $result_array['MallgicOrderId'];
	$UTVOrderId = $result_array['UTVOrderId'];
	$Status = $result_array['Status'];
	$OrderDetailLites = $result_array['OrderDetailLites'];
	
	switch($Status){
		case "-1":	
			$order_state = 0;
			break;
		case "0":	
			$pay_state = 0;
			break;
		case "1":	
			$pay_state = 1;
			break;
		case "2":	
			$pay_state = 2;
			break;
		case "3":	
			$transport_state = 1;
			break;
		case "4":	
			$order_state = 3;
			break;
		case "5":	
			$order_state = 4;
			break;
	}
	if(is_array($OrderDetailLites) ){
		$maxkey = 0;
		foreach($OrderDetailLites as $k=>$v){
			if($v['ProductId']>0){
				setcookie("fbgoods[" . $maxkey . "]", $v['ProductId'],time()+60*60*24,"/");
				setcookie("fbgoods_count[" . $maxkey . "]", $v['Count'],time()+60*60*24,"/");
				setcookie("optype", "FB",time()+60*60*24,"/");
				setcookie("opKey", "FB_" . $MallgicOrderId,time()+60*60*24,"/");
				$maxkey++;
			}	
		}
		//print_r($_COOKIE);exit;
		header("Location:shopping/shopping.php?type=FB&Action=Add&key=FB_" . $MallgicOrderId);
		/*
		$Sql_order   = "select order_serial as max_num from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' order by order_serial desc limit 0,1";
		$Query_order = $DB->query($Sql_order);
		$Rs_order = $DB->fetch_array($Query_order);
		if ($Rs_order['max_num']!=""){
			$m = intval(substr($Rs_order['max_num'],9,3))+1;
			$Next_order_serial = date("Ymd",time()) . str_repeat("0",3-strlen($m)) . $m  . rand(0,9);
		}else{
			$Next_order_serial = date("Ymd",time())."001" . rand(0,9);
		}
		$Order_serial = "T" . $Next_order_serial;
		$total = 0;
		foreach($OrderDetailLites as $k=>$v){
			if($v['ProductId']>0){
				$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($v['ProductId'])." limit 0,1");	
				$Result= $DB->fetch_array($Query);
				$total += $Result['pricedesc'] * intval($v['Count']);
			}	
		}
		
		$U_Sql = "insert into `{$INFO[DBPrefix]}order_table` (totalprice,createtime,order_year,order_month,order_day,order_serial,discount_totalPrices,order_state,pay_state,transport_state,MallgicOrderId) values ('" . $total . "','" . time() . "','" . date("Y",time()) . "','" . date("m",time()) . "','" . date("d",time()) . "','" . $Order_serial . "','" . $total . "','" . $order_state . "','" . $pay_state . "','" . $transport_state . "','" . $MallgicOrderId . "')";
		$DB->query($U_Sql);
		
		$InsertId_Sql = "select order_id from `{$INFO[DBPrefix]}order_table`  where createtime='".$Time."' and order_serial='".$Order_serial."' limit 0,1";
			$InsertId_Query = $DB->query($InsertId_Sql);
			$InsertId_Result= $DB->fetch_array($InsertId_Query);
			$Insert_id  = $InsertId_Result[order_id];
		
		foreach($OrderDetailLites as $k=>$v){
			if($v['ProductId']>0){
				$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($v['ProductId'])." limit 0,1");	
				$Result= $DB->fetch_array($Query);
				$Sql_Ok = "insert into `{$INFO[DBPrefix]}order_detail` (gid,goodsname,unit,good_color,good_size,goodscount,market_price,price,order_id,point,provider_id,bn,iftogether,cost,salecontent) values('".$Result['gid']."','".$Result['goodsname']."','".$Result['unit']."','','','".$v['Count']."','".intval($Result['price'])."','".$Result['pricedesc']."','".$Insert_id."','".$Result['point']."','".$Result['provider_id']."','" . $Result['bn'] . "','".$Result['iftogether']."','" . $Result['cost'] . "','')";
				$DB->query($Sql_Ok);
			}	
		}
		*/
	}elseif($Status>=0 && $Status<=5 && $MallgicOrderId!="" && $UTVOrderId!=""){
		$InsertId_Sql = "select order_id from `{$INFO[DBPrefix]}order_table`  where order_serial='".$UTVOrderId."' and MallgicOrderId='" . $MallgicOrderId . "' limit 0,1";
		$InsertId_Query = $DB->query($InsertId_Sql);
		$InsertId_Result= $DB->fetch_array($InsertId_Query);
		$order_serial = $InsertId_Result['order_serial'];
		header("Location:shopping/showorder.php?order_serial=" . $order_serial . "");
	}
	
	
}

if($op == "updateorderstatus"){
	$Sql = "select * from `{$INFO[DBPrefix]}order_table` where order_serial='" . $utvorderid . "'";
	$Query = $DB->query($Sql);
	$Result= $DB->fetch_array($Query);
	if($Result['pay_state']==0){
		$State = 0;	
	}
	if($Result['pay_state']==1){
		$State = 1;	
	}
	if($Result['pay_state']==2){
		$State = 2;	
	}
	if($Result['transport_state']==1){
		$State = 3;	
	}
	if($Result['order_state']==3){
		$State = 4;	
	}
	if($Result['order_state']==4){
		$State = 5;	
	}
	
	echo $result = file_get_contents($fb_url . "?op=" . $op . "&moid=" . $moid . "&utvorderid=" . $utvorderid . "&orderstatus=" . $State . "&token=" . $token);
}
?>
