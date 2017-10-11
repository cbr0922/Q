<?php
@ob_start();
include "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
require_once '../Resources/phpexcel/PHPExcel.php';
include_once 'crypt.class.php';
$objExcel = new PHPExcel();
$objExcel->setActiveSheetIndex(0);
$objActSheet = $objExcel->getActiveSheet();
$objExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
$FUNCTIONS->setLog("匯出訂單資料");
$objActSheet->setTitle(date("Y-m-d") . '訂單');
$objActSheet->setCellValue('A1', '編號');  // 字符串内容
$objActSheet->setCellValue('B1', '訂單編號');
$objActSheet->setCellValue('C1', '訂購人(送禮人)');
$objActSheet->setCellValue('D1', '訂購人Email(帳號)');
$objActSheet->setCellValue('E1', '收貨人姓名');
$objActSheet->setCellValue('F1', '郵遞區號');
$objActSheet->setCellValue('G1', '收貨人地址');
$objActSheet->setCellValue('H1', '收貨人電話');
$objActSheet->setCellValue('I1', '收貨人手機');
$objActSheet->setCellValue('J1', '商品代號');
$objActSheet->setCellValue('K1', '商品名稱');
$objActSheet->setCellValue('L1', '規格');
$objActSheet->setCellValue('M1', '數量');
$objActSheet->setCellValue('N1', '單價');
$objActSheet->setCellValue('O1', '團購點');
$objActSheet->setCellValue('P1', '總金額');
$objActSheet->setCellValue('Q1', '點數折抵');
$objActSheet->setCellValue('R1', '優惠金額');
$objActSheet->setCellValue('S1', '運費');
$objActSheet->setCellValue('T1', '實收額');
$objActSheet->setCellValue('U1', '紅利點數');
$objActSheet->setCellValue('V1', '使用團購點數');
$objActSheet->setCellValue('W1', '使用購物金');
$objActSheet->setCellValue('X1', '廠編');
$objActSheet->setCellValue('Y1', '廠商名稱');
$objActSheet->setCellValue('Z1', '付款方式');
$objActSheet->setCellValue('AA1', '訂購日');
$objActSheet->setCellValue('AB1', '收貨時段');
$objActSheet->setCellValue('AC1', '延遲天數');
$objActSheet->setCellValue('AD1', '延遲罰款');
$objActSheet->setCellValue('AE1', '出貨日');
$objActSheet->setCellValue('AF1', '出貨單號碼');
$objActSheet->setCellValue('AG1', '物流單位');
$objActSheet->setCellValue('AH1', '取貨店');
$objActSheet->setCellValue('AI1', '到貨日');
$objActSheet->setCellValue('AJ1', '訂單狀態');
$objActSheet->setCellValue('AK1', '備註');
$objActSheet->setCellValue('AL1', '發票類型');
$objActSheet->setCellValue('AM1', '發票抬頭');
$objActSheet->setCellValue('AN1', '統一編號');
$objActSheet->setCellValue('AO1', '發票號碼');
$objActSheet->setCellValue('AP1', '發票日');
$objActSheet->setCellValue('AQ1', '支付狀態');
$objActSheet->setCellValue('AR1', '配送狀態');
$objActSheet->setCellValue('AS1', '成本');
$objActSheet->setCellValue('AT1', '成本小計');
$objActSheet->setCellValue('AU1', '付款日');
$objActSheet->setCellValue('AV1', '負責此供應商的pm');
$objActSheet->setCellValue('AW1', '統倉/供應商訂單');
$objActSheet->setCellValue('AX1', '延遲天數');
$objActSheet->setCellValue('AY1', '館別');
$objActSheet->setCellValue('AZ1', '經銷商帳號');
$objActSheet->setCellValue('BA1', '經銷商名稱');


echo iconv("UTF-8","big5",$file_string);
$Cid  = $_POST['cid'];
$Ci   = $_POST['Ci'];
$Tonum= $_POST['tonum'];
$Gid  = $_POST['gid'];

$Cid_num   = count($Cid);
$Ci_num    = count($Ci);
$Tonum_num = count($Tonum);
$row = 2;
for ($i=0;$i<$Cid_num;$i++){
	$Sql = " select  u.*,o.*
			 from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id) where o.order_id='".intval($Cid[$i])."'";
	$Sql      .= "  order by o.createtime desc";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if ($Num>0){
		while ($Rs = $DB->fetch_array($Query)){
			$Query_d = $DB->query(" select sum(goodscount*point) as totals from `{$INFO[DBPrefix]}order_detail`  where order_id='".intval($Cid[$i])."' limit 0,1");
			$Result_d= $DB->fetch_array($Query_d);
			$Query_first = $DB->query(" select ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}transtime` ttime where ttime.transtime_id='".intval($Rs['transtime_id'])."' limit 0,1");
			$Num_first   = $DB->num_rows($Query_first);
			$Result_first= $DB->fetch_array($Query_first);
			$memo = str_replace(",","，",$Rs['receiver_memo']);
			$memo = str_replace("\"","“",$memo);
			$memo = str_replace("\r"," ",$memo);
			$memo = str_replace("\n"," ",$memo);
			$Order_state      = $Rs['order_state'];
			$Pay_state        = $Rs['pay_state'];
			$Transport_state        = $Rs['transport_state'];
			$Query_p = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Rs['provider_id'])." limit 0,1");
			$Result_p= $DB->fetch_array($Query_p);
			$provider = $Result_p['provider_name'];
			$providerno = $Result_p['providerno'];
			$PM = $Result_p['PM'];
			$Sql_o      = "select * from `{$INFO[DBPrefix]}operater` where opid='" . $PM . "' order by lastlogin desc ";
			$Query_o    = $DB->query($Sql_o);
			$Rs_o = $DB->fetch_array($Query_o);
			$PMname = $Rs_o['truename'];
			$Query_m = $DB->query("select u.*,s.login,s.name from `{$INFO[DBPrefix]}user` as u left join `{$INFO[DBPrefix]}saler` as s on u.companyid=s.id where u.user_id=".intval($Rs['user_id'])." limit 0,1");
			$Result_m= $DB->fetch_array($Query_m);
			$createtime  = $Rs['createtime'];
			$days = ceil((time()-$createtime)/(60*60*24));
			if ($days>=4)
				$sdays = $days-3;
			if ($Rs['iftogether']==1)
				$tong = "統倉訂單";
			else
				$tong = "供應商訂單";
			$store_Sql      = "select * from `{$INFO[DBPrefix]}store` where store_id = '" . intval($Rs['store_id']) . "' limit 0,1";
			$store_Query    = $DB->query($store_Sql);
			$store_Rs=$DB->fetch_array($store_Query);
			switch (intval($Rs['ifinvoice'])){
							case 0:
								$Ifinvoice   = "兩聯";
								break;
							case 1:
								$Ifinvoice   =  "三聯";

								break;
							case 2:
								$Ifinvoice   = "無";
								break;
							case 3:
								$Ifinvoice   =  "捐贈華民國全球元母大慈協會";
								break;
						}
			if ($Rs['ifgroup']==1){
				$Query_d = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_group` as g where order_id=".intval($Cid[$i])." ");
				while ($Rs_d=$DB->fetch_array($Query_d)) {
					$Query_p = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Rs_d['provider_id'])." limit 0,1");
					$Result_p= $DB->fetch_array($Query_p);
					$provider = $Result_p['provider_name'];
					$providerno = $Result_p['providerno'];
					$PM = $Result_p['PM'];
					$Sql_o      = "select * from `{$INFO[DBPrefix]}operater` where opid='" . $PM . "' order by lastlogin desc ";
					$Query_o    = $DB->query($Sql_o);
					$Rs_o = $DB->fetch_array($Query_o);
					$PMname = $Rs_o['truename'];
					$objActSheet->setCellValueExplicit('A' . $row, "G" . $d_Rs['order_group_id'],PHPExcel_Cell_DataType::TYPE_STRING);//編號
					$objActSheet->setCellValueExplicit('B' . $row, $Rs['order_serial'],PHPExcel_Cell_DataType::TYPE_STRING);//訂單編號
					$objActSheet->setCellValueExplicit('C' . $row, $Rs['true_name'],PHPExcel_Cell_DataType::TYPE_STRING);//訂購人(送禮人)
					$objActSheet->setCellValueExplicit('D' . $row, $Rs['username'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('E' . $row, $Rs['receiver_name'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨人姓名
					$objActSheet->setCellValueExplicit('F' . $row, $Rs['receiver_post'],PHPExcel_Cell_DataType::TYPE_STRING);//郵遞區號
					$objActSheet->setCellValueExplicit('G' . $row, $Rs['receiver_address'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨人地址
					$objActSheet->setCellValueExplicit('H' . $row,  MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']),PHPExcel_Cell_DataType::TYPE_STRING);//收貨人電話
					$objActSheet->setCellValueExplicit('I' . $row, MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']),PHPExcel_Cell_DataType::TYPE_STRING);//收貨人手機
					$objActSheet->setCellValueExplicit('J' . $row, $Rs_d['groupbn'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('K' . $row, $Rs_d['groupname'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('L' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('M' . $row, $Rs_d['count'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('N' . $row, $Rs_d['groupprice'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('O' . $row, $Rs_d['grouppoint'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('P' . $row, $Rs_d['groupprice']*$Rs_d['count'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('Q' . $row, $Rs['bonuspoint']+$Rs['totalbonuspoint'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('R' . $row, $Rs['totalprice']-$Rs['discount_totalPrices'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('S' . $row, $Rs['transport_price'],PHPExcel_Cell_DataType::TYPE_STRING);//運費
					$objActSheet->setCellValueExplicit('T' . $row, $Rs['discount_totalPrices']+$Rs['transport_price'],PHPExcel_Cell_DataType::TYPE_STRING);//實收額
					$objActSheet->setCellValueExplicit('U' . $row, $Result_d['totals'],PHPExcel_Cell_DataType::TYPE_STRING);//利點數
					$objActSheet->setCellValueExplicit('V' . $row, $Rs['totalGrouppoint'],PHPExcel_Cell_DataType::TYPE_STRING);//使用團購點數
					$objActSheet->setCellValueExplicit('W' . $row, $Rs['buyPoint'],PHPExcel_Cell_DataType::TYPE_STRING);//使用購物金
					$objActSheet->setCellValueExplicit('X' . $row, $providerno,PHPExcel_Cell_DataType::TYPE_STRING);//廠編
					$objActSheet->setCellValueExplicit('Y' . $row, $provider,PHPExcel_Cell_DataType::TYPE_STRING);//廠商名稱
					$objActSheet->setCellValueExplicit('Z' . $row, $Rs['paymentname'],PHPExcel_Cell_DataType::TYPE_STRING);//付款方式
					$objActSheet->setCellValueExplicit('AA' . $row, date("Y-m-d",$Rs['createtime']),PHPExcel_Cell_DataType::TYPE_STRING);//訂購日
					$objActSheet->setCellValueExplicit('AB' . $row, $Result_first['transtime_name'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨時段
					$objActSheet->setCellValueExplicit('AC' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//延遲天數
					$objActSheet->setCellValueExplicit('AD' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//延遲罰款
					$objActSheet->setCellValueExplicit('AE' . $row, $Rs['sendtime'],PHPExcel_Cell_DataType::TYPE_STRING);//出貨日
					$objActSheet->setCellValueExplicit('AF' . $row, $Rs['piaocode'],PHPExcel_Cell_DataType::TYPE_STRING);//出貨單號碼
					$objActSheet->setCellValueExplicit('AG' . $row, $Rs['sendname'],PHPExcel_Cell_DataType::TYPE_STRING);//物流單位
					$objActSheet->setCellValueExplicit('AH' . $row, $store_Rs['store_name'],PHPExcel_Cell_DataType::TYPE_STRING);//取貨店
					$objActSheet->setCellValueExplicit('AI' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//到貨日
					$objActSheet->setCellValueExplicit('AJ' . $row, $orderClass->getOrderState(intval($Order_state),1),PHPExcel_Cell_DataType::TYPE_STRING);//訂單狀態
					$objActSheet->setCellValueExplicit('AK' . $row, $memo,PHPExcel_Cell_DataType::TYPE_STRING);//備註
					$objActSheet->setCellValueExplicit('AL' . $row, $Ifinvoice,PHPExcel_Cell_DataType::TYPE_STRING);//發票類型
					$objActSheet->setCellValueExplicit('AM' . $row, $Rs['invoiceform'],PHPExcel_Cell_DataType::TYPE_STRING);//發票抬頭
					$objActSheet->setCellValueExplicit('AN' . $row, $Rs['invoice_num'],PHPExcel_Cell_DataType::TYPE_STRING);//統一編號
					$objActSheet->setCellValueExplicit('AO' . $row, $Rs['invoice_code'],PHPExcel_Cell_DataType::TYPE_STRING);//發票號碼
					$objActSheet->setCellValueExplicit('AP' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//發票日
					$objActSheet->setCellValueExplicit('AQ' . $row, $orderClass->getOrderState(intval($Pay_state),2),PHPExcel_Cell_DataType::TYPE_STRING);//支付狀態
					$objActSheet->setCellValueExplicit('AR' . $row, $orderClass->getOrderState(intval($Transport_state),3),PHPExcel_Cell_DataType::TYPE_STRING);//配送狀態
					$objActSheet->setCellValueExplicit('AS' . $row, $Rs_d['cost'],PHPExcel_Cell_DataType::TYPE_STRING);//成本
					$objActSheet->setCellValueExplicit('AT' . $row, $Rs_d['cost']*$Rs_d['count'],PHPExcel_Cell_DataType::TYPE_STRING);//成本小計
					$objActSheet->setCellValueExplicit('AU' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//付款日
					$objActSheet->setCellValueExplicit('AV' . $row, $PMname,PHPExcel_Cell_DataType::TYPE_STRING);//負責此供應商的pm
					$objActSheet->setCellValueExplicit('AW' . $row, $tong,PHPExcel_Cell_DataType::TYPE_STRING);//統倉/供應商訂單
					$objActSheet->setCellValueExplicit('AX' . $row, $sdays,PHPExcel_Cell_DataType::TYPE_STRING);//延遲天數
					$objActSheet->setCellValueExplicit('AY' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//館別
					$objActSheet->setCellValueExplicit('AZ' . $row, $Result_m['login'],PHPExcel_Cell_DataType::TYPE_STRING);//經銷商帳號
					$objActSheet->setCellValueExplicit('BA' . $row, $Result_m['name'],PHPExcel_Cell_DataType::TYPE_STRING);//經銷商名稱
					$row++;

					$d_Sql = "select od.*,g.bid,g.provider_id from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}goods` as g on od.gid=g.gid where od.order_id='" . $Rs['order_id'] . "' and od.order_group_id='" . $Rs_d['order_group_id'] . "'";
					$d_Query    = $DB->query($d_Sql);

					$file_string = "";
					while ($d_Rs = $DB->fetch_array($d_Query)){
						$Query_p = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($d_Rs['provider_id'])." limit 0,1");
						$Result_p= $DB->fetch_array($Query_p);
						$provider = $Result_p['provider_name'];
						$providerno = $Result_p['providerno'];
						$PM = $Result_p['PM'];
						$Sql_o      = "select * from `{$INFO[DBPrefix]}operater` where opid='" . $PM . "' order by lastlogin desc ";
						$Query_o    = $DB->query($Sql_o);
						$Rs_o = $DB->fetch_array($Query_o);
						$PMname = $Rs_o['truename'];
						$class_array = getBanner($d_Rs['bid']);
						if($d_Rs['detail_name']=="")
							$detail = $d_Rs['good_color']."/".$d_Rs['good_size'];
						else
							$detail = $d_Rs['detail_name'];
						$objActSheet->setCellValueExplicit('I' . $row, $d_Rs['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
						$objActSheet->setCellValueExplicit('J' . $row, $d_Rs['goodsname'],PHPExcel_Cell_DataType::TYPE_STRING);
						$objActSheet->setCellValueExplicit('K' . $row, $detail,PHPExcel_Cell_DataType::TYPE_STRING);
						$objActSheet->setCellValueExplicit('L' . $row, $d_Rs['goodscount'],PHPExcel_Cell_DataType::TYPE_STRING);

						$objActSheet->setCellValueExplicit('W' . $row, $providerno,PHPExcel_Cell_DataType::TYPE_STRING);//廠編
						$objActSheet->setCellValueExplicit('X' . $row, $provider,PHPExcel_Cell_DataType::TYPE_STRING);//廠商名稱

						$objActSheet->setCellValueExplicit('AU' . $row, $PMname,PHPExcel_Cell_DataType::TYPE_STRING);//負責此供應商的pm
						$row++;
					}
				}
			}else{
				$d_Sql = "select od.*,g.bid from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}goods` as g on od.gid=g.gid where od.order_id='" . $Rs['order_id'] . "'";
				$d_Query    = $DB->query($d_Sql);

				$file_string = "";
				while ($d_Rs = $DB->fetch_array($d_Query)){
					$class_array = getBanner($d_Rs['bid']);
					if($d_Rs['detail_name']=="")
							$detail = $d_Rs['good_color']."/".$d_Rs['good_size'];
						else
							$detail = $d_Rs['detail_name'];
					if($d_Rs['packgid']>0){
						$d_Rs['goodsname'] .= " [組合商品]";
						$d_Rs['price'] = "";
						$xiaoji ="";
						$hongli ="";
					}else{

						$xiaoji = $d_Rs['price']*$d_Rs['goodscount'];
						$hongli = $d_Rs['point']*$d_Rs['goodscount'];

					}
					$objActSheet->setCellValueExplicit('A' . $row, $d_Rs['order_detail_id'],PHPExcel_Cell_DataType::TYPE_STRING);//編號
					$objActSheet->setCellValueExplicit('B' . $row, $Rs['order_serial'],PHPExcel_Cell_DataType::TYPE_STRING);//訂單編號
					$objActSheet->setCellValueExplicit('C' . $row, $Rs['true_name'],PHPExcel_Cell_DataType::TYPE_STRING);//訂購人(送禮人)
					$objActSheet->setCellValueExplicit('D' . $row, $Rs['username'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('E' . $row, $Rs['receiver_name'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨人姓名
					$objActSheet->setCellValueExplicit('F' . $row, $Rs['receiver_post'],PHPExcel_Cell_DataType::TYPE_STRING);//郵遞區號
					$objActSheet->setCellValueExplicit('G' . $row, $Rs['receiver_address'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨人地址
					//$objActSheet->setCellValueExplicit('G' . $row, $Rs['receiver_tele'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨人電話
					//$objActSheet->setCellValueExplicit('H' . $row, $Rs['receiver_mobile'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨人手機
					$objActSheet->setCellValueExplicit('H' . $row, MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']),PHPExcel_Cell_DataType::TYPE_STRING);//收貨人電話
					$objActSheet->setCellValueExplicit('I' . $row,  MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']),PHPExcel_Cell_DataType::TYPE_STRING);//收貨人手機
					$objActSheet->setCellValueExplicit('J' . $row, $d_Rs['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('K' . $row, $d_Rs['goodsname'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('L' . $row, $detail ,PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('M' . $row, $d_Rs['goodscount'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('N' . $row, round($d_Rs['price'],0),PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('O' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('P' . $row, $xiaoji,PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('Q' . $row, $Rs['bonuspoint']+$Rs['totalbonuspoint'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('R' . $row, $Rs['totalprice']-$Rs['discount_totalPrices'],PHPExcel_Cell_DataType::TYPE_STRING);
					$objActSheet->setCellValueExplicit('S' . $row, $Rs['transport_price'],PHPExcel_Cell_DataType::TYPE_STRING);//運費
					$objActSheet->setCellValueExplicit('T' . $row, $Rs['discount_totalPrices']+$Rs['transport_price'],PHPExcel_Cell_DataType::TYPE_STRING);//實收額
					$objActSheet->setCellValueExplicit('U' . $row, $hongli,PHPExcel_Cell_DataType::TYPE_STRING);//利點數
					$objActSheet->setCellValueExplicit('V' . $row, $Rs['totalGrouppoint'],PHPExcel_Cell_DataType::TYPE_STRING);//使用團購點數
					$objActSheet->setCellValueExplicit('W' . $row, $Rs['buyPoint'],PHPExcel_Cell_DataType::TYPE_STRING);//使用購物金
					$objActSheet->setCellValueExplicit('X' . $row, $providerno,PHPExcel_Cell_DataType::TYPE_STRING);//廠編
					$objActSheet->setCellValueExplicit('Y' . $row, $provider,PHPExcel_Cell_DataType::TYPE_STRING);//廠商名稱
					$objActSheet->setCellValueExplicit('Z' . $row, $Rs['paymentname'],PHPExcel_Cell_DataType::TYPE_STRING);//付款方式
					$objActSheet->setCellValueExplicit('AA' . $row, date("Y-m-d",$Rs['createtime']),PHPExcel_Cell_DataType::TYPE_STRING);//訂購日
					$objActSheet->setCellValueExplicit('AB' . $row, $Result_first['transtime_name'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨時段
					$objActSheet->setCellValueExplicit('AC' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//延遲天數
					$objActSheet->setCellValueExplicit('AD' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//延遲罰款
					$objActSheet->setCellValueExplicit('AE' . $row, $Rs['sendtime'],PHPExcel_Cell_DataType::TYPE_STRING);//出貨日
					$objActSheet->setCellValueExplicit('AF' . $row, $Rs['piaocode'],PHPExcel_Cell_DataType::TYPE_STRING);//出貨單號碼
					$objActSheet->setCellValueExplicit('AG' . $row, $Rs['sendname'],PHPExcel_Cell_DataType::TYPE_STRING);//物流單位
					$objActSheet->setCellValueExplicit('AH' . $row, $store_Rs['store_name'],PHPExcel_Cell_DataType::TYPE_STRING);//取貨店
					$objActSheet->setCellValueExplicit('AI' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//到貨日
					$objActSheet->setCellValueExplicit('AJ' . $row, $orderClass->getOrderState(intval($Order_state),1),PHPExcel_Cell_DataType::TYPE_STRING);//訂單狀態
					$objActSheet->setCellValueExplicit('AK' . $row, $memo,PHPExcel_Cell_DataType::TYPE_STRING);//備註
					$objActSheet->setCellValueExplicit('AL' . $row, $Ifinvoice,PHPExcel_Cell_DataType::TYPE_STRING);//發票類型
					$objActSheet->setCellValueExplicit('AM' . $row, $Rs['invoiceform'],PHPExcel_Cell_DataType::TYPE_STRING);//發票抬頭
					$objActSheet->setCellValueExplicit('AN' . $row, $Rs['invoice_num'],PHPExcel_Cell_DataType::TYPE_STRING);//統一編號
					$objActSheet->setCellValueExplicit('AO' . $row, $Rs['invoice_code'],PHPExcel_Cell_DataType::TYPE_STRING);//發票號碼
					$objActSheet->setCellValueExplicit('AP' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//發票日
					$objActSheet->setCellValueExplicit('AQ' . $row, $orderClass->getOrderState(intval($Pay_state),2),PHPExcel_Cell_DataType::TYPE_STRING);//支付狀態
					$objActSheet->setCellValueExplicit('AR' . $row, $orderClass->getOrderState(intval($Transport_state),3),PHPExcel_Cell_DataType::TYPE_STRING);//配送狀態
					$objActSheet->setCellValueExplicit('AS' . $row, $d_Rs['cost'],PHPExcel_Cell_DataType::TYPE_STRING);//成本
					$objActSheet->setCellValueExplicit('AT' . $row, $d_Rs['cost']*$d_Rs['goodscount'],PHPExcel_Cell_DataType::TYPE_STRING);//成本小計
					$objActSheet->setCellValueExplicit('AU' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//付款日
					$objActSheet->setCellValueExplicit('AV' . $row, $PMname,PHPExcel_Cell_DataType::TYPE_STRING);//負責此供應商的pm
					$objActSheet->setCellValueExplicit('AW' . $row, $tong,PHPExcel_Cell_DataType::TYPE_STRING);//統倉/供應商訂單
					$objActSheet->setCellValueExplicit('AX' . $row, $sdays,PHPExcel_Cell_DataType::TYPE_STRING);//延遲天數
					$objActSheet->setCellValueExplicit('AY' . $row, $class_array[0]['catname'],PHPExcel_Cell_DataType::TYPE_STRING);//館別
					$objActSheet->setCellValueExplicit('AZ' . $row, $Result_m['login'],PHPExcel_Cell_DataType::TYPE_STRING);//經銷商帳號
					$objActSheet->setCellValueExplicit('BA' . $row, $Result_m['name'],PHPExcel_Cell_DataType::TYPE_STRING);//經銷商名稱
					$row++;

				}
			}
		}
	}
}
function getBanner($bid){
	global $DB,$INFO,$class_banner,$list,$Bcontent;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$class_banner[$list]['bid'] = $Result['bid'];
		$class_banner[$list]['catname'] = $Result['catname'];
		$class_banner[$list]['banner'] = $Result['banner'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		else
			$Bcontent = $Result['catcontent'];
	}
}
function formatstr($str){
	$str = str_replace(",","，",$str);
	$str = str_replace("\"","“",$str);
	$str = str_replace("\r"," ",$str);
	$str = str_replace("\n"," ",$str);
	return $str;
}

$outputFileName = date("Y-m-d") . "order.xls";
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');


header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Content-Disposition:inline;filename="'.$outputFileName.'"');
header("Content-Transfer-Encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
Header("Content-type: application/octet-stream");
Header("Content-Disposition: inline; filename=".$outputFileName."");
Header("Pragma:public");

$objWriter->save('php://output');
exit;
?>
