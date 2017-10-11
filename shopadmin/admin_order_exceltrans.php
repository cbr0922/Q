<?php
@ob_start();
include "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
require_once 'PHPExcel.php';
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

$objActSheet->setTitle(date("Y-m-d") . '訂單');
$objActSheet->setCellValue('A1', '廠商編號');  // 字符串内容
$objActSheet->setCellValue('B1', '檔案編號');
$objActSheet->setCellValue('C1', '訂單編號');
$objActSheet->setCellValue('D1', '貨品數序號');
$objActSheet->setCellValue('E1', '郵遞區號');
$objActSheet->setCellValue('F1', '地址');
$objActSheet->setCellValue('G1', '姓名');
$objActSheet->setCellValue('H1', '電話');
$objActSheet->setCellValue('I1', '商品代號');
$objActSheet->setCellValue('J1', '物品名稱');
$objActSheet->setCellValue('K1', '數量');
$objActSheet->setCellValue('L1', '單價');
$objActSheet->setCellValue('M1', '檔案製作日期');
$objActSheet->setCellValue('N1', '其他費用');
$objActSheet->setCellValue('O1', '發票號碼');
$objActSheet->setCellValue('P1', '發票日期');
$objActSheet->setCellValue('Q1', '檢查號碼');
$objActSheet->setCellValue('R1', '買受人統一編號');
$objActSheet->setCellValue('S1', '買受人');
$objActSheet->setCellValue('T1', '未稅總額');
$objActSheet->setCellValue('U1', '總稅額');
$objActSheet->setCellValue('V1', '含稅總金額');
$objActSheet->setCellValue('W1', '稅額小計');
$objActSheet->setCellValue('X1', '銀行名稱');
$objActSheet->setCellValue('Y1', '銀行確認碼');
$FUNCTIONS->setLog("訂單匯出物流格式");

//echo iconv("UTF-8","big5",$file_string);
$Cid  = $_POST['cid'];
$Ci   = $_POST['Ci'];
$Tonum= $_POST['tonum'];
$Gid  = $_POST['gid'];

$Cid_num   = count($Cid);
$Ci_num    = count($Ci);
$Tonum_num = count($Tonum);
$cid_string = implode(",",$Cid);
if($cid_string!=""){
	$Sql = " select  o.*
			 from `{$INFO[DBPrefix]}order_table` o  where o.order_id in (" . $cid_string . ")";
	$Sql      .= "  order by o.createtime desc";
	$Query    = $DB->query($Sql);
	while ($Rs = $DB->fetch_array($Query)){
		if($Rs['order_serial_together']!=""){
			$Sql_t      = "select * from `{$INFO[DBPrefix]}order_table` ot  where ot.order_serial_together='".$Rs['order_serial_together']."' order by  ot.order_id desc ";
			$Query_t    = $DB->query($Sql_t);
			while (	$Rs_t = $DB->fetch_array($Query_t)){
				$Cid[count($Cid)] = $Rs_t['order_id'];
			}
		}
	}
}
$Cid = array_unique($Cid);
$Cid_num   = count($Cid);
$row = 2;
$cid_string = implode(",",$Cid);
	$Sql = " select  u.*,o.*
			 from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id) where o.order_id in (".$cid_string.")";
	$Sql      .= "  order by o.createtime desc";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if ($Num>0){
		while ($Rs = $DB->fetch_array($Query)){
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

				$d_Sql = "select od.*,g.bid from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}goods` as g on od.gid=g.gid where od.order_id='" . $Rs['order_id'] . "'";
				$d_Query    = $DB->query($d_Sql);

				$file_string = "";
				while ($d_Rs = $DB->fetch_array($d_Query)){
					if($d_Rs['packgid']>0){
						$d_Rs['goodsname'] .= " [組合商品]";
						$d_Rs['price'] = "";
						$xiaoji ="";
						$hongli ="";
					}else{

						$xiaoji = $d_Rs['price']*$d_Rs['goodscount'];
						$hongli = $d_Rs['point']*$d_Rs['goodscount'];

					}
					$class_array = getBanner($d_Rs['bid']);
					$objActSheet->setCellValueExplicit('A' . $row, $providerno,PHPExcel_Cell_DataType::TYPE_STRING);//廠商編號
					$objActSheet->setCellValueExplicit('B' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//檔案編號
					$objActSheet->setCellValueExplicit('C' . $row, $Rs['order_serial'],PHPExcel_Cell_DataType::TYPE_STRING);//訂單編號
					$objActSheet->setCellValueExplicit('D' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//貨品數序號
					$objActSheet->setCellValueExplicit('E' . $row, $Rs['receiver_post'],PHPExcel_Cell_DataType::TYPE_STRING);//郵遞區號
					$objActSheet->setCellValueExplicit('F' . $row, $Rs['receiver_address'],PHPExcel_Cell_DataType::TYPE_STRING);//收貨人地址
					$objActSheet->setCellValueExplicit('G' . $row, $Rs['receiver_name'],PHPExcel_Cell_DataType::TYPE_STRING);//姓名
					$objActSheet->setCellValueExplicit('H' . $row,  MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']). "/" .  MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']),PHPExcel_Cell_DataType::TYPE_STRING);//收貨人電話
					$objActSheet->setCellValueExplicit('I' . $row, $d_Rs['bn'],PHPExcel_Cell_DataType::TYPE_STRING);//商品代號
					$objActSheet->setCellValueExplicit('J' . $row, $d_Rs['goodsname'],PHPExcel_Cell_DataType::TYPE_STRING);//物品名稱
					$objActSheet->setCellValueExplicit('K' . $row, $d_Rs['goodscount'],PHPExcel_Cell_DataType::TYPE_STRING);//數量
					$objActSheet->setCellValueExplicit('L' . $row, round($d_Rs['price'],0),PHPExcel_Cell_DataType::TYPE_STRING);//單價
					$objActSheet->setCellValueExplicit('M' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//檔案製作日期
					$objActSheet->setCellValueExplicit('N' . $row, "0",PHPExcel_Cell_DataType::TYPE_STRING);//其他費用
					$objActSheet->setCellValueExplicit('O' . $row, $Rs['invoice_code'],PHPExcel_Cell_DataType::TYPE_STRING);//發票號碼
					$objActSheet->setCellValueExplicit('P' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//發票日期
					$objActSheet->setCellValueExplicit('Q' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//檢查號碼
					$objActSheet->setCellValueExplicit('R' . $row, $Rs['invoice_num'],PHPExcel_Cell_DataType::TYPE_STRING);//買受人統一編號
					$objActSheet->setCellValueExplicit('S' . $row, $Rs['invoiceform'],PHPExcel_Cell_DataType::TYPE_STRING);//買受人
					$objActSheet->setCellValueExplicit('T' . $row, $Rs['discount_totalPrices']+$Rs['transport_price'],PHPExcel_Cell_DataType::TYPE_STRING);//未稅總額
					$objActSheet->setCellValueExplicit('U' . $row, "0",PHPExcel_Cell_DataType::TYPE_STRING);//總稅額
					$objActSheet->setCellValueExplicit('V' . $row, $Rs['discount_totalPrices']+$Rs['transport_price'],PHPExcel_Cell_DataType::TYPE_STRING);//含稅總金額
					$objActSheet->setCellValueExplicit('W' . $row, "0",PHPExcel_Cell_DataType::TYPE_STRING);//稅額小計
					$objActSheet->setCellValueExplicit('X' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//銀行名稱
					$objActSheet->setCellValueExplicit('Y' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//銀行確認碼

					$row++;

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
