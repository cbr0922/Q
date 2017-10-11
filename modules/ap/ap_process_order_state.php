<?php
error_reporting(E_ALL); // Report all PHP errors (see changelog)
ini_set('display_errors','On');

include(dirname(__FILE__)."/../../configs.inc.php");
include("global.php");
include_once RootDocument."/Classes/orderClass.php";

/*
 * 函數宣告
 */
function isCLI(){

	$raddr = $_SERVER["REMOTE_ADDR"];
	$mode  = php_sapi_name();
	if( $mode == "cli" ){
		return TRUE;
	}
	else if ($mode == "cgi" && $raddr == "" ){
		return TRUE;
	}
	else{
		return FALSE;
	}
}


function list_order_actions( $order_id ){
	global $DB,$INFO;

	$ret_code = 0;
	$ret_data = NULL;

	$order_id = intval( $order_id ); // 先確認 order_id 為整數

	if( $order_id <= 0 ){
		$ret_code = -1; // order_id 不正常
	}
	else{
		$sql = "SELECT * "
			. " FROM `{$INFO[DBPrefix]}order_action` AS oa "
			. " WHERE oa.order_id=$order_id "
			. " ORDER BY oa.actiontime ASC ";

		$res  = $DB->query($sql);
		$result = array();
		while( $row = $DB->fetch_array($res) ){
			array_push( $result, $row );
		}

		$ret_data = $result;
	}

	return $ret_data;
}

function list_order_details( $order_id ){
	global $DB,$INFO;

	$ret_code = 0;
	$ret_data = NULL;

	$order_id = intval( $order_id ); // 先確認 order_id 為整數

	if( $order_id <= 0 ){
		$ret_code = -1; // order_id 不正常
	}
	else{
		$sql = "SELECT * "
			. " FROM `{$INFO[DBPrefix]}order_detail` AS od "
			. " WHERE od.order_id=$order_id "
			. "";

		$res  = $DB->query($sql);
		$result = array();
		while( $row = $DB->fetch_array($res) ){
			array_push( $result, $row );
		}

		$ret_data = $result;
	}

	return $ret_data;
}

// 檢查執行環境
if( !isCLI() ){ exit(); }

/*
 * 參數設定及環境變數
 */
$time_0 = microtime(true);

$orderClass = new orderClass;

$keyword3 = "出貨3天后自動發貨";
$keyword7 = "出貨7天后自動完成交易";

$now       = time();
$y         = intval(date('Y',$now));
$m         = intval(date('m',$now));
$d         = intval(date('d',$now));

$time3     = gmmktime(0,0,0,$m,$d-3,$y);
$time7     = gmmktime(0,0,0,$m,$d-7,$y);
$time9     = gmmktime(0,0,0,$m-3,$d,$y);

$nowtime   = date("Y-m-d",$now);
$overtime1 = date("Y-m-d",gmmktime(0,0,0,$m,$d-30,$y));
$overtime2 = date("Y-m-d",gmmktime(0,0,0,$m,$d+30,$y));


/*
 *
 * 主程式
 *
 */ 

// 選出要進行處理的訂單項目
$sql = "select * "
            . " from `{$INFO[DBPrefix]}order_table` "
            . " where ( pay_state=1 ) "                                               // 已付款
            . " and (transport_state=1 or transport_state=2 or transport_state=18 ) " // 已發貨 or 已收貨 or 已取貨
	    . " and order_state<>4"                                                   // 未完成交易
            . " and createtime >=$time9 ";                                            // 最近三個月內的
$time_1 = microtime(true);
$res = $DB->query( $sql );
$time_2 = microtime(true);
while( $row =$DB->fetch_array($res) )
{
	$order_id          = $row['order_id'];

	//if( $order_id < 6140 || $order_id > 6150 ){ continue; }
	echo "[ $order_id ] ...\n";	 

	$order_details     = list_order_details( $order_id );
	$order_details_cnt = count( $order_details );
	$actions           = list_order_actions( $order_id );

	$order_detail_ids = array();
	foreach( $order_details as $item ){
		$id = $item['order_detail_id'];
		$isExist = in_array( $id, $order_detail_ids );
		if( !$isExist ){
			array_push( $order_detail_ids, $id );		
		}
	}

	$check1     = false;   // 已發貨
	$check2     = false;   // 已到貨
	$check3     = false;   // 已到貨(細項)
	$check3_ary = array(); // 已到貨的細項

	$check4     = false;   // 發貨時間已達3天
	$check5     = false;   // 發貨時間已達3天(細項)
	$check5_ary = array(); // 發貨時間已達3天的細項
	$check6     = false;   // 是否已重複3

	$check7     = false;   // 到貨時間已達7天
	$check8     = false;   // 到貨時間已達7天(細項)
	$check8_ary = array(); // 到貨時間已達7天的細項
	$check9     = false;   // 是否已重複7

	// 先 scan 所有 order action 的資訊，只看細項
	foreach( $order_detail_ids as $od_id ){
		foreach( $actions as $item ){
			$state_type      = $item['state_type'];
			$state_value     = $item['state_value'];
			$remark          = $item['remark'];
			$actiontime      = $item['actiontime'];
			$order_detail_id = $item['order_detail_id'];

			if( $order_detail_id == $od_id ){
				if( $state_type == 3 && $state_value == 1 ){ 
					if( $actiontime < $time3 ){
						// 發貨時間已達3天(細項)
						if( in_array( $od_id, $check5_ary ) ){
							array_push( $check5_ary, $od_id );
						}
					}
				}
				if( $state_type == 3 && ( $state_value == 2 || $state_value == 18 ) ){ 
					// 已到貨(細項)
					if( in_array( $od_id, $check3_ary ) ){
						array_push( $check3_ary, $od_id );
					}
					if( $actiontime < $time7 ){
						// 到貨時間已達7天(細項)
						if( in_array( $od_id, $check8_ary ) ){
							array_push( $check8_ary, $od_id );
						}
					}
				}
			}
		}
	}

	// 先 scan 所有 order action 的資訊，只看訂身本身
	foreach( $actions as $item ){
		$state_type      = $item['state_type'];
		$state_value     = $item['state_value'];
		$remark          = $item['remark'];
		$actiontime      = $item['actiontime'];
		$order_detail_id = $item['order_detail_id'];

		if( $order_detail_id > 0){ continue; }

		if( $state_type == 3 && $state_value == 1 ){ 
			$check1 = true; 
			// 時間在3天前
			if( $actiontime < $time3 ){
				$check4 = true;
			}
		}
		if( $state_type == 3 && ( $state_value == 2 || $state_value == 18 ) ){ 
			$check2 = true; 
			if( $actiontime < $time7 ){
				$check7 = true;
			}
		}
		if( $remark == $keyword3 ){
			$check6 = true;
		}
		if( $remark == $keyword7 ){
			$check9 = true;
		}
	}
	
	//
	// 執行狀態變更
	//

	// 在訂單本身還沒有到貨的情況下，檢查細項發貨跟發貨天數的條件
	if( !$check2 ){
		// 如果發貨達3天的細項的數量符合細項數量
		if( count( $check5_ary ) >= $order_details_cnt ){
			// 表示全部細項已發貨達3天
			$check5 = true;
		}

		// 對每一個發貨達3天的細項
		foreach( $check5_ary as $od_id ){
			// 如果不是到貨的狀態，就變更為到貨的狀態
			if( !in_array( $od_id, $check3_ary ) ){
				echo "[ $order_id ][$od_id] $keyword3\n";	 
				$orderClass->setOrderDetailState( 2, 3, $order_id, $od_id, $keyword3, -1, 0, 1, 0, 0 );
				array_push( $check3_ary, $od_id );
			}

		}
	
		// 如果到貨的細項的數量符合細項數量
		if( count( $check3_ary ) >= $order_details_cnt ){
			// 表示全部細項已到貨
			$check3 = true;
		}
	}

	// 在訂單本身到貨未滿7天的狀態下，檢查細項到貨的狀熊條件
	if( !$check7 ){
		// 如果到貨已達7天的細項的數量符合細項數量
		if( count( $check8_ary ) >= $order_details_cnt ){
			// 表示全部細項已到貨達7天
			$check8 = true;
		}
	}

	if( $check3 ){ $check2 = true; }
	if( $check5 ){ $check4 = true; }
	if( $check8 ){ $check7 = true; }

	if( $check1 && !$check2 && $check4 && !$check6 ){
		echo "[ $order_id ] $keyword3\n";	 
		$orderClass->setOrderState(2,3,$order_id,$keyword3,1,1);
	}

	if( $check2 && $check7 && !$check9 ){
		echo "[ $order_id ] $keyword7\n";	 
		$orderClass->setOrderState(4,1,$order_id,$keyword7,1,1);
	}
	//echo "check1: $check1.\n";
	//echo "check2: $check2.\n";
	//echo "check3: $check3.\n";
	//echo "check4: $check4.\n";
	//echo "check5: $check5.\n";
	//echo "check6: $check6.\n";
	//echo "check7: $check7.\n";
	//echo "check8: $check8.\n";
	//echo "check9: $check9.\n";
	//print_r( $check3_ary );
	//print_r( $check5_ary );
	//print_r( $check8_ary );
}

$time_3 = microtime(true);

$Sql_order = "select * "
	   . " from `{$INFO[DBPrefix]}order_table` "
           . " where (pay_state<>6 and pay_state<>1 and pay_state<>7) "
	   . " and (transport_state<>1 and transport_state<>8 and transport_state<>7 and transport_state<>9) "
	   . " and createtime<='" . ($now-60*60*24*10) . "' "
	   . " and order_state<>3 and order_state<>5";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	$orderClass->setOrderState(5,1,$Rs_order['order_id'],"訂單失效(系統自動)");
}

//供應商更改狀態

//
$Sql_p   = "select * "
	 . " from `{$INFO[DBPrefix]}provider` "
         . " where state=2 "
	 . " and end_date<'" . $nowtime . "'";
$Query_p = $DB->query($Sql_p);
$Num_p   = $DB->num_rows($Query_p);
while($Rs_p=$DB->fetch_array($Query_p)){
	$update_Sql_g = "update `{$INFO[DBPrefix]}goods` "
                      . " set ifpub=0 "
		      . " where provider_id='" . $Rs_p['provider_id'] . "'";
	$DB->query($update_Sql_g);
}

//
$update_Sql_p = "update `{$INFO[DBPrefix]}provider` "
              . " set state=4 "
              . " where (state=2 or state=3) "
	      . " and end_date<'" . $nowtime . "'";
$DB->query($update_Sql_p);

//
$update_Sql_p = "update `{$INFO[DBPrefix]}provider` "
              . " set state=5 "
	      . " where (state=2 or state=3 or state=4) "
	      . " and end_date<'" . $overtime1 . "'";
$DB->query($update_Sql_p);

//
$update_Sql_p = "update `{$INFO[DBPrefix]}provider` "
              . " set state=3 "
	      . " where state=2 "
	      . " and start_date<='" . $nowtime . "' and end_date<='" . $overtime2 . "'";
$DB->query($update_Sql_p);

$time_4 = microtime(true);

$time_99 = microtime(true);

// 計算時間區間
{
	$delta_1 = $time_1 - $time_0;
	$delta_2 = $time_2 - $time_1;
	$delta_3 = $time_3 - $time_2;
	$delta_4 = $time_4 - $time_3;
	$delta_99 = $time_99 - $time_4;

	$delta_all = $time_99 - $time_0;

	echo "總執行時間 $delta_all 秒<br/>\n";
	echo " 0-1 執行時間 $delta_1 秒<br/>\n";
	echo " 1-2 執行時間 $delta_2 秒<br/>\n";
	echo " 2-3 執行時間 $delta_3 秒<br/>\n";
	echo " 3-4 執行時間 $delta_4 秒<br/>\n";
	echo " 4-E 執行時間 $delta_99 秒<br/>\n";
}

?>
