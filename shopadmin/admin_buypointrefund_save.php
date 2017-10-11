<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
print_r($_POST);
if(intval($_POST['br_id'])>0){
	$Sql = "UPDATE `{$INFO[DBPrefix]}buypointrefund` SET remark='" . $_POST['remark'] . "',bank='" . $_POST['bank'] . "',bankcode='" . $_POST['bankcode'] . "',account='" . $_POST['account'] . "',acountname='" . $_POST['acountname'] . "',state='" . intval($_POST['type']) . "' WHERE br_id=".intval($_POST['br_id']);
	$Result_Insert = $DB->query($Sql);

	if(intval($_POST['type'])==1){
		$Sql      = "select bp.*,o.order_serial from `{$INFO[DBPrefix]}buypointrefund` as bp left join `{$INFO[DBPrefix]}order_table` as o on o.order_id=bp.order_id where bp.br_id='" . intval($_POST['br_id']) . "' order by bp.refundtime desc";
		$Query    = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		$point = $Rs['point'];
		$refundtype = $Rs['refundtype'];
		$order_serial = $Rs['order_serial'];
		$order_id = $Rs['order_id'];
		$backbouns = $Rs['backbouns'];
		$mpoint = $FUNCTIONS->Buypoint(intval($Rs['u_id']),1);
		if($order_id>0){
			$type = 3;
			$t=0;
			$Sql_order_d = "select * from `{$INFO[DBPrefix]}order_detail` where order_id = '" . intval($order_id) . "' and detail_pay_state=4";
			$Query_order_d  = $DB->query($Sql_order_d);
			$order_detail_count = $DB->num_rows($Query_order_d);
			$Sql_order = "select * from `{$INFO[DBPrefix]}order_detail` where order_id = '" . intval($order_id) . "'";
			$Query_order  = $DB->query($Sql_order);
			$order_count=$DB->num_rows($Query_order);
			if ($order_count==$order_detail_count){
				$single = 0;
			}else{
				$single = 1;
			}
			$k = 0;
				while($Result = $DB->fetch_array($Query_order_d)){
					if($k == 0)
						$orderClass->setOrderDetailState(5,2,$order_id,$Result['order_detail_id'],'退款審核通過',0,$single,1,0,0);
						
					else
						$orderClass->setOrderDetailState(5,2,$order_id,$Result['order_detail_id'],'退款審核通過',0,$single,1,0,0);
						$k++;
				}
				$orderClass->setOrderState(5,2,$order_id,'退款審核通過',1,1);
				$orderClass->postMail($order_id,5,2);
				if(intval($backbouns)>0){
					$FUNCTIONS->AddBonuspoint(intval($Rs['u_id']),intval($backbouns),2,"退款審核通過" . $order_serial,1,$order_id);	
				}
				if(intval($point)>0){
					$back_Sql = "update `{$INFO[DBPrefix]}order_table` set backprice=backprice+" . $point . ",bonuspoint=bonuspoint-" . intval($backbouns) . ",refundtype='" . intval($refundtype) . "' where order_id='" . $order_id . "'";
					$DB->query($back_Sql);
				}
		}else{
			if($point>$mpoint)
				$FUNCTIONS->sorry_back('back',"退款額與帳上餘額不相符");
			$type = 5;
			$t=1;
		}
		if($refundtype==0)
			$FUNCTIONS->AddBuypoint(intval($Rs['u_id']),intval($point),$t,"退款",$order_id,$_SESSION['sa_id'],$_SESSION['LOGINADMIN_TYPE'],$type);
	}else{
		$Sql      = "select bp.* from `{$INFO[DBPrefix]}buypointrefund` as bp where bp.br_id='" . intval($_POST['br_id']) . "' order by bp.refundtime desc";
		$Query    = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		$point = $Rs['point'];
		$refundtype = $Rs['refundtype'];
		$order_id = $Rs['order_id'];
		$mpoint = $FUNCTIONS->Buypoint(intval($Rs['u_id']),1);
		if($order_id>0){
			$type = 3;
			$t=0;
			$Sql_order_d = "select * from `{$INFO[DBPrefix]}order_detail` where order_id = '" . intval($order_id) . "' and detail_pay_state=4";
			$Query_order_d  = $DB->query($Sql_order_d);
			$order_detail_count = $DB->num_rows($Query_order_d);
			$Sql_order = "select * from `{$INFO[DBPrefix]}order_detail` where order_id = '" . intval($order_id) . "'";
			$Query_order  = $DB->query($Sql_order);
			$order_count=$DB->num_rows($Query_order);
			if ($order_count==$order_detail_count){
				$single = 0;
			}else{
				$single = 1;
			}
			$k = 0;
				while($Result = $DB->fetch_array($Query_order_d)){
					if($k == 0)
						$orderClass->setOrderDetailState(1,2,$order_id,$Result['order_detail_id'],'退款審核失敗',0,$single,1,0,0);
					else
						$orderClass->setOrderDetailState(1,2,$order_id,$Result['order_detail_id'],'退款審核失敗',0,$single,1);
						$k++;
				}
				$orderClass->setOrderState(1,2,$order_id,'退款審核失敗',1,1);
				//$back_Sql = "update `{$INFO[DBPrefix]}order_table` set totalprice=totalprice-" . intval($point) . ",discount_totalPrices=discount_totalPrices-" . intval($point) . " where order_id='" . $order_id . "'";
				//$DB->query($back_Sql);
		}	
	}
	echo 1;
}
?>
