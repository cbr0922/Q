<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
class ORDERS{
	/**
	得到訂單列表
	**/
	function getOrderList($user_id=0){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		include_once "orderClass.php";
		$orderClass = new orderClass;
		$Sql   =  " select * from `{$INFO[DBPrefix]}order_table` where user_id='".$user_id."' order by createtime desc ";
		$PageNav    = new PageItem($Sql,10);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$tot =  0;
				$monthprice = 0;
				$OrderList[$i]['shopid'] = intval($Rs['shopid']);
				if (intval($Rs['order_state'])==4 && intval($Rs['shopid'])>0){
					$s_Sql   =  " select * from `{$INFO[DBPrefix]}score` where user_id=".$_SESSION['user_id']." and order_id='" . $Rs['order_id'] . "' ";
					$s_Query =  $DB->query($s_Sql);	
					$s_Num   =  $DB->num_rows($s_Query);
					if ($s_Num>0){
						$s_Rs = $DB->fetch_array($s_Query);
						$OrderList[$i]['havescore'] = 1;
						$OrderList[$i]['score1'] = $s_Rs['score1'];
						$OrderList[$i]['score2'] = $s_Rs['score2'];
						$OrderList[$i]['score3'] = $s_Rs['score3'];
					}else{
						$OrderList[$i]['havescore'] = 0;	
					}
				}
				$OrderList[$i]['order_serial'] = $Rs['order_serial'];
				$OrderList[$i]['createtime']   = date("Y-m-d  H:i a ",$Rs['createtime']);
				$OrderList[$i]['order_state']  = $orderClass->getOrderState($Rs['order_state'],1);
				
				$OrderList[$i]['pay_state']  =$orderClass->getOrderState($Rs['pay_state'],2) ;
				$OrderList[$i]['transport_state']  = $orderClass->getOrderState($Rs['transport_state'],3);
				$OrderList[$i]['order_show']   = intval($Rs['order_state']);
				
				$OrderList[$i]['totalprice']   = $Rs['totalprice']+$Rs['transport_price'];
				$OrderList[$i]['order_id']     = $Rs['order_id'];
				$OrderList[$i]['tot']     = $Rs['discount_totalPrices']+$Rs['transport_price'];
				$OrderList[$i]['opList']   =        $orderClass->getUserOp($Rs['order_id'],1);
				$Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu` where order_serial = '" . $Rs['order_serial'] . "'";
				$Query_linshi = $DB->query($Sql_linshi);
				$Rs_linshi = $DB->fetch_array($Query_linshi);
				if ($Rs_linshi['kid']>0)
					$OrderList[$i]['kid']   =$Rs_linshi['kid'];
				$i++;
			}
			$result_array['info'] = $OrderList;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
	/**
	得到某條訂單信息
	**/
	function getOrderDetail($order_id,$user_id=0){
		global $DB,$INFO,$FUNCTIONS;
		include_once "orderClass.php";
		$orderClass = new orderClass;
		$return_array = array();
		$Query = $DB->query(" select ot.* from `{$INFO[DBPrefix]}order_table` ot  where ot.order_id='".intval($order_id)."' and ot.user_id='" . intval($user_id) . "' limit 0,1");
		 $Num       = $DB->num_rows($Query);
		if ( $Num>0 ){
			$Result    = $DB->fetch_array($Query);
			$return_array = $Result;
			$return_array['receiver_name'] = $FUNCTIONS->getOrderUInfo($Result['receiver_name'],1);
			$return_array['receiver_email'] = $FUNCTIONS->getOrderUInfo($Result['receiver_email'],5);
			$return_array['receiver_address'] = $FUNCTIONS->getOrderUInfo($Result['receiver_address'],10);
			$return_array['receiver_tele'] = "********";
			$return_array['receiver_mobile'] = "********";
			$return_array['order_state'] = $orderClass->getOrderState($Result['order_state'],1);
			$return_array['Pay_state'] = $orderClass->getOrderState($Result['pay_state'],2);
			$return_array['Transport_state'] = $orderClass->getOrderState($Result['transport_state'],3);
			$Query_detail = $DB->query(" select g.*,gd.bn from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where order_id=".intval($order_id)." and packgid=0 order by order_detail_id asc ");
			$i = 0 ;
			$count = 0;
			while ($Rs_detail = $DB->fetch_array($Query_detail)){
				$order_detail[$i] = $Rs_detail;
				$count+=$Rs_detail['goodscount'];
				$i++;
			}
			$return_array['count'] = $count;
			$return_array['order_detail'] = $order_detail;
		}
		return $return_array;
	}
	/**
	得到收件人列表
	**/
	function getReceiverList($user_id){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		include_once 'crypt.class.php';
		$Sql   =  " select * from `{$INFO[DBPrefix]}receiver` where user_id='".$user_id."' order by reid desc ";
$PageNav    = new PageItem($Sql,12);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$OrderList[$i]['receiver_name'] = $Rs['receiver_name'];
				$OrderList[$i]['addr']   = $Rs['addr'];
				$OrderList[$i]['receiver_email']  = $Rs['receiver_email'];
				$OrderList[$i]['county']   = $Rs['county'];
				$OrderList[$i]['province']   = $Rs['province'];
				$OrderList[$i]['city']     = $Rs['city'];
				$OrderList[$i]['reid']     = $Rs['reid'];
				$OrderList[$i]['receiver_tele']     =MD5Crypt::Decrypt ( $Rs['receiver_tele'], $INFO['tcrypt']);
				$i++;
			}
			$result_array['info'] = $OrderList;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
	/**
	得到收件人信息
	**/
	function getReceiver($reid){
		global $DB,$INFO;
		include_once 'crypt.class.php';
		$sql = "select * from `{$INFO[DBPrefix]}receiver` where reid='" . $reid . "'";
		$Query = $DB->query($sql);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
			$receiver_array = $Result;
			$receiver_array['receiver_tele'] =        MD5Crypt::Decrypt ($Result['receiver_tele'], $INFO['tcrypt'] );
			$receiver_array['receiver_mobile'] =      MD5Crypt::Decrypt ($Result['receiver_mobile'], $INFO['mcrypt'] );
		}
		return $receiver_array;
	}
	
	/**
	保存收件人信息
	**/
	function setReceiver($act){
		global $DB,$INFO;	
		include_once 'crypt.class.php';
		if($act == "insert"){
			$db_string = $DB->compile_db_insert_string( array (
			'user_id'                      => intval($_SESSION['user_id']),
			'receiver_name'                => $_POST['receiver_name'],
			'addr'                         => trim($_POST['addr']),
			'receiver_email'               => trim($_POST['receiver_email']),
			'post'                => trim($_POST['othercity']), //  receiver_post
			'receiver_tele'                => MD5Crypt::Encrypt ( trim($_POST['receiver_tele']), $INFO['tcrypt']),
			'receiver_mobile'              => MD5Crypt::Encrypt ( trim($_POST['receiver_mobile']), $INFO['mcrypt']),
			'county'                       => str_replace("請選擇","",trim($_POST['county'])),
			'province'                     => str_replace("請選擇","",trim($_POST['province'])),
			'city'                         => str_replace("請選擇","",trim($_POST['city'])),
			)      );
			$Sql="INSERT INTO `{$INFO[DBPrefix]}receiver` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result = $DB->query($Sql);	
		}elseif($act == "update"){
			$db_string = $DB->compile_db_update_string( array (
			'receiver_name'                => $_POST['receiver_name'],
			'addr'                         => trim($_POST['addr']),
			'receiver_email'               => trim($_POST['receiver_email']),
			'post'                => trim($_POST['othercity']), //  receiver_post
			'receiver_tele'                => MD5Crypt::Encrypt ( trim($_POST['receiver_tele']), $INFO['tcrypt']),
			'receiver_mobile'              => MD5Crypt::Encrypt ( trim($_POST['receiver_mobile']), $INFO['mcrypt']),
			'county'                       => str_replace("請選擇","",trim($_POST['county'])),
			'province'                     => str_replace("請選擇","",trim($_POST['province'])),
			'city'                         => str_replace("請選擇","",trim($_POST['city'])),
			)      );
			$Sql = "UPDATE `{$INFO[DBPrefix]}receiver` SET $db_string WHERE reid=".intval($_POST['reid']);
			$Result = $DB->query($Sql);
		}
	}
	/**
	刪除收件人
	**/
	function delReceiver($reid){
		global $DB,$INFO;	
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}receiver` where reid=".intval($reid));
	}
}
?>