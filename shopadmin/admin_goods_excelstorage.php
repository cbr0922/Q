<?php
@ob_start();
include "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
require_once '../Resources/phpexcel/PHPExcel.php'; 
include_once 'crypt.class.php';
include_once("product.class.php");
$PRODUCT = new PRODUCT();
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
$string = "貨號,庫存數量";	
$string_array = explode(",",$string);
$objActSheet->setTitle(date("Y-m-d") . '商品庫存');
foreach($string_array as $k=>$v){
	$objActSheet->setCellValue(getC($k) . "1", $v);	
}

$Sql_sub = "select * from `{$INFO[DBPrefix]}goods`";
$Query_sub = $DB->query($Sql_sub);
$Num_sub   = $DB->num_rows($Query_sub);
$row = 2;
$FUNCTIONS->setLog("商品庫存匯出");
while ($Rs_sub = $DB->fetch_array($Query_sub)){
	
	//屬性
	if($Rs_sub['good_color']!="" || $Rs_sub['good_size']!=""){
		$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . trim($Rs_sub['gid']) . "' ";
		$goods_Query =  $DB->query($goods_Sql);
		$goods_Num   =  $DB->num_rows($goods_Query );
		while ($goods_Rs = $DB->fetch_array($goods_Query)){
			
			$f = 0;
			$storage = $PRODUCT->checkStorage($Rs_sub['gid'],0,$goods_Rs['color'],$goods_Rs['size'],$checktype,1);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['goodsno'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row,intval($storage) ,PHPExcel_Cell_DataType::TYPE_STRING);
			$row++;
		}
	}else{
		$f = 0;
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, intval($Rs_sub['storage']),PHPExcel_Cell_DataType::TYPE_STRING);
		$row++;
	}
	
}



function formatstr($str){
	$str = str_replace(",","，",$str);
	$str = str_replace("\"","“",$str);
	$str = str_replace("\r"," ",$str);
	$str = str_replace("\n"," ",$str);	
	return $str;
}
function getC($no){
	$start = 65;
	$end = 90;
	 $len = intval($no/26);
	if($len>0){
		$result = chr($start+$len-1);
		$result .= chr($no%26+$start);
	}else{
		$result = chr($start+$no);	
	}
	//echo $result;
	return $result;
}

$outputFileName = date("Y-m-d") . "goods.xls";
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
