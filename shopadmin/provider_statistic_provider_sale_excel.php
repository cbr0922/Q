<?php
//session_start();
include "Check_Admin.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
$subSql = " and p.provider_id='" . intval($_SESSION[sa_id]) . "'";
if (intval($_GET['province'])>0 ){
	$subSql = " and ot.provider_id='" . intval($_GET['province']) . "'";
}
if (intval($_GET['provider_id'])>0){
	$subSql = " and ot.provider_id='" . intval($_GET['provider_id']) . "'";
}
if ($_GET['iftogether']=="1"){
	$subSql .= " and ot.iftogether=1";
}
if ($_GET['iftogether']=="0"){
	$subSql .= " and ot.iftogether=0";
}

$Sql = "select sum(od.cost*od.goodscount) as sumcost,od.*,sum(od.goodscount) as goodscounts from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=2 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " group by od.gid,od.cost";

$Query    = $DB->query($Sql);
require_once( 'PHPExcel.php' ); 
$objExcel = new PHPExcel();  
$objExcel->setActiveSheetIndex(0); 
$objActSheet = $objExcel->getActiveSheet(); 

if (intval($_GET['provider_id'])>0){
  $Provider_Search = " and provider_id='" . intval($_GET['provider_id']) . "'";
}
$Sql_p      = "select * from `{$INFO[DBPrefix]}provider` where 1=1 ".$Provider_Search." order by providerno desc  ";
$Query_p    = $DB->query($Sql_p);
$Rs_p=$DB->fetch_array($Query_p);

$objExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
							 
$objActSheet->setTitle('UTV金連網' . $current_month . '月' . $Rs_p['providerno'] . $Rs_p['provider_name'] . '對帳銷售表');
$FUNCTIONS->setLog("供應商對帳單");
$objActSheet->setCellValue('A1', 'UTV金連網' . $current_month . '月對帳銷售表');  // 字符串内容   
$objActSheet->mergeCells('A1:F1');
$objActSheet->setCellValue('A2', $begtime . "至" . $endtime);  // 字符串内容 
$objActSheet->mergeCells('A2:F2');
$objActSheet->setCellValue('A5', "商品編碼");  // 字符串内容   
$objActSheet->setCellValue('B5', "品名");
$objActSheet->setCellValue('C5', "數量");
$objActSheet->setCellValue('D5', "單成本價");
$objActSheet->setCellValue('E5', "成本小計");
$objActSheet->setCellValue('F5', "備註");
$row = 6;
$total = 0;
$totalbouns = 0;
$i = 1;
while($Rs=$DB->fetch_array($Query)){
	$total += $Rs['sumcost'];
	$objActSheet->setCellValueExplicit('A' . $row, $Rs['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValue('B' . $row, $Rs['goodsname']);
	$objActSheet->setCellValue('C' . $row, $Rs['goodscounts']);
	$objActSheet->setCellValue('D' . $row, $Rs['cost']);
	$objActSheet->setCellValue('E' . $row, $Rs['sumcost']);
	$objActSheet->setCellValue('F' . $row, $Rs['receiver_memo ']);
	$row++;	
	$i++;
}
$objActSheet->setCellValue('D' . $row, "(+a)銷售小計");
$objActSheet->setCellValue('E' . $row, $total . "元");

$t_Sql = "select sum(ot.transport_price) as sumtrans from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=2 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " ";
$t_Query    = $DB->query($t_Sql);
$t_Rs=$DB->fetch_array($t_Query);

$objActSheet->setCellValue('D' . ($row+1), "(+b)" . $current_month . "月物流運費總金額");
$objActSheet->setCellValue('E' . ($row+1), $t_Rs['sumtrans'] . "元");

$objActSheet->setCellValue('D' . ($row+2), "(a+b)合計");
$objActSheet->setCellValue('E' . ($row+2), ($t_Rs['sumtrans']+$total));

$objActSheet->setCellValue('A' . ($row+3), "備註：\r\n1.列印對帳銷售表，於對帳總表蓋發票章及負責窗口簽章\r\n2.對帳銷售表與發票、回郵信封一起寄回UTV金連網 財務部 收\r\n                   105 台北市松山區復興北路1號4摟 02-27416166");
$objActSheet->setCellValue('A' . ($row+4), "                                             廠商發票章    負責窗口簽章\r\n\r\n\r\n\r\n\r\n");
$objActSheet->mergeCells('A' . ($row+3) . ':F'. ($row+3));
$objActSheet->mergeCells('A' . ($row+4) . ':B'. ($row+4));
$objActSheet->getDefaultColumnDimension()->setWidth(15);
$objActSheet->getColumnDimension('B')->setWidth(50); 
$objActSheet->getColumnDimension('F')->setWidth(50); 
$objActSheet->getRowDimension($row+3)->setRowHeight(100); 
$objActSheet->getRowDimension($row+4)->setRowHeight(100); 
$objActSheet->getStyle('A' . $row+4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
$objActSheet->getStyle('A' . $row+4)->getFont()->setBold(true); 
$objActSheet->getStyle('A1:F' . ($row+4))->getAlignment()->setWrapText(true);

$outputFileName = $_GET['province'] ." " . $begtime ." to ". $endtime . "sale.xls";
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
?>
