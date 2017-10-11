<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
session_start();
if ($_SESSION['user_id']==""){
	@header("location:member_login.php");
}
include("../configs.inc.php");
include("global.php");
$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}shopinfo` where uid=".$_SESSION['user_id']." and state=1 limit 0,1");
$Num    = $DB->num_rows($Query);
if ($Num==0){
	$FUNCTIONS->sorry_back("back","您不能管理訂單");
}
$Rs =  $DB->fetch_array($Query);
$sid =$Rs['sid']; 

$Order_id        = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));

$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id=".intval($Order_id)." and ot.shopid='" . $sid . "' limit 0,1");
$Num             = intval($DB->num_rows($Query));
if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$Transtime_name = $Result[transtime_name];
}

require_once( 'PHPExcel.php' ); 
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
							 
$objActSheet->setTitle('下載訂單');
$FUNCTIONS->setLog("下載訂單");

$Sql      = "select * from `{$INFO[DBPrefix]}order_table` ot  where ot.order_id=".$Order_id." ";
$Query    = $DB->query($Sql);
$Rs =$DB->fetch_array($Query);
$Order_serial = $Rs['order_serial'];
$User_id      = $Rs['user_id'];
$Order_Time   = date("Y-m-d H: i a ",$Rs['createtime']);
$Paymentname  = $Rs['paymentname'];
$Deliveryname = $Rs['deliveryname'];
$Totalprice   = $Rs['totalprice'];
$Transport_price = $Rs['transport_price'];
$discount_totalPrices = $Rs['discount_totalPrices'];
$bonuspoint = $Rs['bonuspoint'];
$totalbonuspoint = $Rs['totalbonuspoint'];
$ticketcode = $Rs[ticketcode];
$saler = $Rs[saler];
$rid = $Rs['rid'];

switch (intval($Rs['ifinvoice'])){
  case 0:
	  $Ifinvoice   = $Cart[Two_piao];
	  $Invoiceform = $Basic_Command['Null'];
	  $TheOneNum   = $Basic_Command['Null'];
	  break;
  case 1:
	  $Ifinvoice   =  $Cart[Three_piao];
	  $Invoiceform =  trim($Result['invoiceform']);
	  $TheOneNum   =  trim($Result['invoice_num']);

	  break;
  case 2:
	  $Ifinvoice   = $Basic_Command['Null'];
	  $Invoiceform = $Basic_Command['Null'];
	  $TheOneNum   = $Basic_Command['Null'];
	  break;
}


$Order_state  = $Rs['order_state'];
$Pay_state    = $Rs['pay_state'];
$Receiver_name    = $Rs['receiver_name'];
$Receiver_address = $Rs['receiver_address'];
$Receiver_email   = $Rs['receiver_email'];
$Receiver_post    = $Rs['receiver_post'];
$Receiver_tele    = $Rs['receiver_tele'];
$Receiver_mobile  = $Rs['receiver_mobile'];
$ticket_discount_money  = $Rs['ticket_discount_money'];
$Receiver_memo    = nl2br($Rs['receiver_memo']);
$ATM              = trim($Rs['atm'])!="" ? trim($Rs['atm']) : $Basic_Command['Null'] ;
$objActSheet ->getColumnDimension('A' )->setWidth(10);
$objActSheet ->getColumnDimension('B' )->setWidth(7);
$objActSheet ->getColumnDimension('C' )->setWidth(7);
$objActSheet ->getColumnDimension('D' )->setWidth(7);
$objActSheet ->getColumnDimension('E' )->setWidth(7);
$objActSheet ->getColumnDimension('F' )->setWidth(7);
$objActSheet ->getColumnDimension('G' )->setWidth(10);
$objActSheet ->getColumnDimension('H' )->setWidth(7);
$objActSheet ->getColumnDimension('I' )->setWidth(7);
$objActSheet ->getColumnDimension('J' )->setWidth(7);
$objActSheet ->getColumnDimension('K' )->setWidth(7);
$objActSheet ->getColumnDimension('L' )->setWidth(7);
$objActSheet->setCellValue('A1', "訂單資訊");  // 字符串内容   
$objActSheet->mergeCells('A1:C1');
$objActSheet->setCellValue('A2', "訂單編號");  // 字符串内容 
$objActSheet->setCellValueExplicit('B2', $Order_serial,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('B2:F2');
$objActSheet->setCellValue('G2', "訂單狀態");
$objActSheet->setCellValue('H2', $FUNCTIONS->OrderStateName($Order_state) . "[" . $FUNCTIONS->OrderPayState(intval($Pay_state)) . "]");
$objActSheet->mergeCells('H2:L2');
$objActSheet->setCellValue('A3', "下單日期");
$objActSheet->setCellValue('B3', $Order_Time);
$objActSheet->mergeCells('B3:F3');
$objActSheet->setCellValue('G3', "商品總金額");
$objActSheet->setCellValueExplicit('H3', $Totalprice,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('H3:L3');
$objActSheet->setCellValue('A4', "配送方式");
$objActSheet->setCellValue('B4', $Deliveryname);
$objActSheet->mergeCells('B4:F4');
$objActSheet->setCellValue('G4', "商品運費");
$objActSheet->setCellValueExplicit('H4', $Transport_price,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('H4:L4');
$objActSheet->setCellValue('A5', "使用折價券");
$objActSheet->setCellValueExplicit('B5', $ticket_discount_money . "   " . $ticketcode,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('B5:F5');
$objActSheet->setCellValue('G5', "折扣後金額");
$objActSheet->setCellValueExplicit('H5', $discount_totalPrices,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('H5:L5');
$objActSheet->setCellValue('A6', "使用紅利");
$objActSheet->setCellValueExplicit('B6', $bonuspoint+$totalbonuspoint,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('B6:F6');
$objActSheet->setCellValue('G6', "消費總金額");
$objActSheet->setCellValueExplicit('H6', $discount_totalPrices+$Transport_price,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('H6:L6');
/*
$objActSheet->setCellValue('A5', "付款方式");
$objActSheet->setCellValue('B5', $Paymentname);
$objActSheet->mergeCells('B5:F5');
$objActSheet->setCellValue('G5', "折扣後金額");
$objActSheet->setCellValue('H5', $discount_totalPrices);
$objActSheet->mergeCells('H5:L5');

$objActSheet->setCellValue('A6', "使用紅利");
$objActSheet->setCellValue('B6', $bonuspoint+$totalbonuspoint);
$objActSheet->mergeCells('B6:F6');
$objActSheet->setCellValue('G6', "使用折價券");
$objActSheet->setCellValue('H6', $ticket_discount_money . "   " . $ticketcode);
$objActSheet->mergeCells('H6:L6');
*/
$objActSheet->setCellValue('A7', "付款方式");
$objActSheet->setCellValue('B7', $Paymentname);
$objActSheet->mergeCells('B7:L7');


$objActSheet->setCellValue('A8', "需要發票");
$objActSheet->setCellValue('B8', $Ifinvoice);
$objActSheet->mergeCells('B8:F8');
$objActSheet->setCellValue('G8', "發票抬頭");
$objActSheet->setCellValue('F8', $Invoiceform);
$objActSheet->mergeCells('H8:L8');
$objActSheet->setCellValue('A9', "統一編號");
$objActSheet->setCellValueExplicit('B9', $TheOneNum,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('B9:F9');
$objActSheet->setCellValue('G9', "經銷商");
$objActSheet->setCellValue('H9', $saler);
$objActSheet->mergeCells('H9:L9');
$objActSheet->setCellValue('A10', "美安");
$objActSheet->setCellValue('B10', $rid);
$objActSheet->mergeCells('B10:F10');
$objActSheet->mergeCells('G10:L10');
$u = intval($User_id)==0?$Cart[NO_member]:"";
$objActSheet->setCellValue('A11', "訂購人資訊" . $u);  // 字符串内容   
$objActSheet->mergeCells('A11:F11');
$Query_user = $DB->query(" select * from `{$INFO[DBPrefix]}user` where user_id=".intval($User_id)." limit 0,1 ");
$Num_user   = $DB->num_rows($Query_user);
if ($Num_user>0){
	$Result_user= $DB->fetch_array($Query_user);
	$objActSheet->setCellValue('A12', "真實姓名");
	$objActSheet->setCellValue('B12', $Result_user['true_name']);
	$objActSheet->mergeCells('B12:F12');
	$objActSheet->setCellValue('G12', "帳號");
	$objActSheet->setCellValueExplicit('H12', $Result_user['username'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->mergeCells('H12:L12');
	$objActSheet->setCellValue('A13', "聯絡電話");
	$objActSheet->setCellValueExplicit('B13', $Result_user['tel'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->mergeCells('B13:F13');
	$objActSheet->setCellValue('G13', "電子信箱");
	$objActSheet->setCellValue('H13', $Result_user['Gmail']);
	$objActSheet->mergeCells('H13:L13');
	$objActSheet->setCellValue('A14', "聯絡地址");
	$objActSheet->setCellValueExplicit('B14', $Result_user['addr']);
	$objActSheet->mergeCells('B14:F14');
	$objActSheet->setCellValue('G14', "地區");
	$objActSheet->setCellValue('H14', $Result_user['city']);
	$objActSheet->mergeCells('H14:L14');
	$objActSheet->setCellValue('A15', "郵遞區號");
	$objActSheet->setCellValueExplicit('B15', $Result_user['post'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->mergeCells('B15:F15');
	$objActSheet->setCellValue('G15', "註冊時間 ");
	$objActSheet->setCellValue('H15', $Result_user['reg_date']);
	$objActSheet->mergeCells('H15:L15');
}
$objActSheet->setCellValue('A16', "收貨人資訊");
$objActSheet->mergeCells('A16:F16');
$objActSheet->setCellValue('A17', "收貨人姓名");
$objActSheet->setCellValue('B17', $Receiver_name);
$objActSheet->mergeCells('B17:F17');
$objActSheet->setCellValue('G17', "聯絡手機 ");
$objActSheet->setCellValueExplicit('H17', $Receiver_mobile,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('H17:L17');
$objActSheet->setCellValue('A18', "聯絡電話");
$objActSheet->setCellValueExplicit('B18', $Receiver_tele,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('B18:F18');
$objActSheet->setCellValue('G18', "電子信箱 ");
$objActSheet->setCellValue('H18', $Receiver_email);
$objActSheet->mergeCells('H18:L18');
$objActSheet->setCellValue('A19', "收貨人地址");
$objActSheet->setCellValue('B19', $Receiver_address);
$objActSheet->mergeCells('B19:F19');
$objActSheet->setCellValue('G19', "宅配時間 ");
$objActSheet->setCellValue('H19', $Transtime_name);
$objActSheet->mergeCells('H19:L19');
$objActSheet->setCellValue('A20', "郵遞區號");
$objActSheet->setCellValueExplicit('B20', $Receiver_post,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('B20:F20');
$objActSheet->mergeCells('G20:L20');
$objActSheet->setCellValue('A21', "訂單備註");
$objActSheet->setCellValueExplicit('B21', $Receiver_memo,PHPExcel_Cell_DataType::TYPE_STRING);
$objActSheet->mergeCells('B21:F21');
$objActSheet->mergeCells('G21:L21');
$objActSheet->setCellValue('A22', "商品資訊");
$objActSheet->mergeCells('A22:D22');

$Sql      = "select * from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where ot.order_id=".$Order_id." order by  od.order_detail_id desc ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$Nums     = $Num ;
}else{
	$FUNCTIONS->sorry_back('back','');
}

$i = 1;
$j = 24;
$objActSheet->setCellValue('A23', "商品名稱");
$objActSheet->mergeCells('A23:D23');
$objActSheet->setCellValue('E23', "網購價格");
$objActSheet->setCellValue('F23', "分期數");
$objActSheet->setCellValue('G23', "件數");
$objActSheet->setCellValue('H23', "顏色/尺寸");
$objActSheet->setCellValue('I23', "已出貨數量");
$objActSheet->setCellValue('J23', "需出貨數量");
$objActSheet->setCellValue('K23', "小計");
$objActSheet->setCellValue('L23', "訂單狀態 ");
$objStyleA = $objActSheet->getStyle('A23');
$objStyleB = $objActSheet->getStyle('A2');
if ($Num > 0){
	while ($Rs = $DB->fetch_array($Query)){
		
		$objActSheet->setCellValue('A' . $j, $Rs['goodsname']."\r\n".str_replace("<br>","\r\n",$Rs['xygoods_des'])."\r\n".$Rs_s['detail_name']."\r\n".$Rs_s['detail_des']);
		$objActSheet->getStyle('A' . $j)->getAlignment()->setWrapText(true);
		$objActSheet->getRowDimension($j)->setRowHeight(100); 
		//$objActSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER); 

if ($Rs['memberorprice']==1){
								$buyprice = intval($Rs[price]);
							}else{
								$buyprice = intval($Rs[memberprice]) . "+" . intval($Rs[combipoint]) . "積分";
							}
		$objActSheet->mergeCells('A' . $j .':D' . $j);
		$objActSheet->setCellValueExplicit('E' . $j, $buyprice,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit('F' . $j, $Rs['month'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('G' . $j, $Rs['goodscount'].$Rs['unit']);
		$objActSheet->setCellValue('H' . $j, $Rs['good_color'] . "/" . $Rs['good_size']);
		$objActSheet->setCellValueExplicit('I' . $j, $Rs['hadsend'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit('J' . $j, intval($Rs['goodscount'])-intval($Rs['hadsend']),PHPExcel_Cell_DataType::TYPE_STRING);
		$Total_detailprice = $Rs['hadsend']!=0 ? abs($Rs['hadsend']*$Rs['price']) : abs($Rs['goodscount']*$Rs['price']) ;
		$objActSheet->setCellValueExplicit('K' . $j, $Total_detailprice,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('L' . $j, $FUNCTIONS->OrderStateName($Rs['detail_order_state']) . "[" . $FUNCTIONS->OrderPayState(intval($Rs['detail_pay_state'])) . "]" );
		$i++;
		$j++;
	}
			$objBorderA5 = $objStyleA->getBorders();   
		$objBorderA5->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getTop()->getColor()->setARGB('000000'); // color   
		$objBorderA5->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
		$objActSheet->duplicateStyle($objStyleA, 'A23:L' . ($j-1));  
		$objActSheet->duplicateStyle($objStyleB, 'A2:L10');  
		$objActSheet->duplicateStyle($objStyleB, 'A12:L15');  
		$objActSheet->duplicateStyle($objStyleB, 'A17:L21'); 
		

$outputFileName = "order_" . $Order_serial . ".xls";
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
$objWriter->save('php://output');   
exit;
}
?>
