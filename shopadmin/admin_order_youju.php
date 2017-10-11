<?php
//session_start();
include "Check_Admin.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
@header("Content-type: text/html; charset=utf-8");

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

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
							 
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : $TimeClass->ForGetDate("Month","-6","Y-m-d");
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : $TimeClass->ForGetDate("Day","1","Y-m-d");

switch ($INFO['admin_IS']){
	case "gb":
		$ToEncode = "GB2312";
		break;
	case "en":
		$ToEncode = "GB2312";
		break;
	case "big5":
		$ToEncode = "BIG5";
		break;
	default:
		$ToEncode = "GB2312";
		break;
}

//$objActSheet->setTitle(iconv("UTF-8","big5",'郵局'));
$objActSheet->setTitle('郵局');


if (intval($_GET['provider_id'])>0){
	$Query_Provider =  $DB->query("SELECT DISTINCT order_id FROM  `{$INFO[DBPrefix]}order_detail`   where  provider_id=".intval($_GET['provider_id']));
	$Num_Provider   =  $DB->num_rows($Query_Provider);
	if ($Num_Provider>0){
		$Provider_Search = " and ( ";
		while ($Rs_Provider   =  $DB->fetch_array($Query_Provider)){
			$Provider_Search .= "o.order_id='".intval($Rs_Provider[order_id])."'  or ";
		}

		$Provider_Search   = substr($Provider_Search,0,strlen($Provider_Search)-3);
		$Provider_Search  .= " )";
	}
}

$FUNCTIONS->setLog("訂單匯出郵局格式");
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-");

switch (trim($_GET['State'])){
	case "NoOp":
		$Add = " and o.order_state<=2 ";  // 这里设置为小于等于2，就是说包括未确定，已确定，部分发货三类内容
		$Join = " left join ";
		break;
	case "NoView":
		$Add = " and ou.userback_alread=0 ";
		$Join = " inner join ";
		break;
	case "AllCancel":
		$Add = " and o.order_state=5 ";  // 这里设置为5
		$Join = " left join ";
		$Topname = "[".$Order_Pack[OrderState_say_six]."]";//已取消
		break;
	case "AllPigeonhole":
		$Add = " and o.order_state=4 ";  // 这里设置为4
		$Join = " left join ";
		$Topname = "[".$Order_Pack[OrderState_say_five]."]";//已归档
		break;
	case "Noreplay":
		$Add = " and ou.sys_say='' ";
		$Join = " inner join ";
		break;
    default :
		$Join = " left join ";
		break;
}

if ($_GET['action']=='search') {
	if (trim(urldecode($_GET['skey']))!=$Basic_Command['InputKeyWord']   && trim($_GET['skey'])!=""){

		if (trim($_GET['type'])=='o.order_serial' || trim($_GET['type'])=='u.true_name' ){
			$Add_one = " and ".$_GET['type']."  like '%".trim(urldecode($_GET['skey']))."%' ";
		}else{
			$Add_one = " and ".$_GET['type']."'".trim(urldecode($_GET['skey']))."' ";
		}
	}

	if ($_GET['orderstate']!=''){
		$Add_two  =  " and  o.order_state=".intval($_GET['orderstate'])." ";
		switch (intval($_GET['orderstate'])){
			case 0:
				$Topname = "[".$Order_Pack[OrderPayState_say_one]."]" ;//未确认
				break;
			case 1:
				$Topname = "[".$Order_Pack[OrderState_say_two]."]";//已确认
				break;
			case 2:
				$Topname = "[".$Order_Pack[OrderState_say_three]."]";//部分发货
				break;
			case 3:
				$Topname = "[".$Order_Pack[OrderState_say_four]."]";//已发货
				break;
			case 4:
				$Topname = "[".$Order_Pack[OrderState_say_five]."]";//已归档
				break;
			case 5:
				$Topname = "[".$Order_Pack[OrderState_say_six]."]";//已取消
				break;
		}
	}

	if ($_GET['paystate']!=''){
		$Add_three  =  " and  o.pay_state=".intval($_GET['paystate'])." ";
	}
	if (intval($_GET['company'])>0){
		$Add_three  =  " and  u.companyid=".intval($_GET['company'])." ";
	}

	$Sql = " select  u.true_name,u.tel,u.post,u.addr,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.receiver_post,o.receiver_tele,o.receiver_mobile,o.receiver_address,
	         ou.sys_say,ou.userback_type,ou.userback_alread  ,o.ticketmoney,o.discount_totalPrices
	         from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id)  
	         where  o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' 
	         ".$Add_one."   ".$Add_two."   ".$Add_three."  ".$Add."  ".$Provider_Search." ";
	$Sql      .= "  order by o.createtime desc";


}else{
	//下边如果不参与查询的资料

	$Add  = $_GET['State']!=""  ? str_replace("and","where",$Add)." and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' " :  " where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' ";
	$Add  = $_GET[Order_Tracks]=="Show" ? $Add." and o.order007_status=1 and  o.order007_begtime <= '".date("Y-m-d",time())."' " : $Add ;
	$Sql  = " select u.true_name,u.tel,u.post,u.addr,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.receiver_post,o.receiver_tele,o.receiver_mobile,o.receiver_address,
	         ou.sys_say,ou.userback_type,ou.userback_alread ,o.ticketmoney,o.discount_totalPrices 
	         from `{$INFO[DBPrefix]}order_table` o  left join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id ) ".$Add."  ";
	$Sql      .= "  order by o.createtime desc";
}

// $Sql;

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$year = intval(date("Y",time()))-1911;
$month = date("m",time());
$day = date("d",time());

$objActSheet->setCellValue('A1', "中華民國 " . $year . " 年 " . $month . " 月 " . $day . " 日");  // 字符串内容   
$objActSheet->mergeCells('A1:C1');
$objActSheet->setCellValue('A2', "寄   件   人");  // 字符串内容   
$objActSheet->mergeCells('A2:C2');
$objActSheet->setCellValue('A3', "寄件人代表名稱:星全安旅行社");
$objActSheet->mergeCells('A3:C3');
$objActSheet->setCellValue('E3', "詳細地址: ");
$objActSheet->setCellValue('F3', "電話號碼: 02-25462222");
$objActSheet->mergeCells('F3:K3');
//$file_string .="中華民國 " . $year . " 年 " . $month . " 月 " . $day . " 日\n";
//$file_string .="寄   件   人\n";
//$file_string .="寄件人代表名稱:星全安旅行社              ,詳細地址:                              ,電話號碼: 02-25462222 \n";
//$file_string .= "順序,訂單編號,掛號號碼,收件人,寄達地名(或地址),是否回執,是否航空,是否印刷物,重量,郵資,備考\n";
$i = 1;
$j = 5;
/*
$objActSheet->setCellValue('A4', iconv("UTF-8","big5","順序"));
$objActSheet->setCellValue('B4', iconv("UTF-8","big5","訂單編號"));
$objActSheet->setCellValue('C4', iconv("UTF-8","big5","掛號號碼"));
$objActSheet->setCellValue('D4', iconv("UTF-8","big5","收件人"));
$objActSheet->setCellValue('E4', iconv("UTF-8","big5","寄達地名(或地址)"));
$objActSheet->setCellValue('F4', iconv("UTF-8","big5","是否回執"));
$objActSheet->setCellValue('G4', iconv("UTF-8","big5","是否航空"));
$objActSheet->setCellValue('H4', iconv("UTF-8","big5","是否印刷物"));
$objActSheet->setCellValue('I4', iconv("UTF-8","big5","重量"));
$objActSheet->setCellValue('J4', iconv("UTF-8","big5","郵資"));
$objActSheet->setCellValue('K4', iconv("UTF-8","big5","備考"));
*/
$objActSheet->setCellValue('A4', "順序");
$objActSheet->setCellValue('B4', "訂單編號");
$objActSheet->setCellValue('C4', "掛號號碼");
$objActSheet->setCellValue('D4', "收件人");
$objActSheet->setCellValue('E4', "寄達地名(或地址)");
$objActSheet->setCellValue('F4', "是否回執");
$objActSheet->setCellValue('G4', "是否航空");
$objActSheet->setCellValue('H4', "是否印刷物");
$objActSheet->setCellValue('I4', "重量");
$objActSheet->setCellValue('J4', "郵資");
$objActSheet->setCellValue('K4', "備考");
$objActSheet->getColumnDimension('A')->setWidth(5); 
$objActSheet->getColumnDimension('B')->setWidth(15); 
$objActSheet->getColumnDimension('C')->setWidth(15); 
$objActSheet->getColumnDimension('D')->setWidth(20); 
$objActSheet->getColumnDimension('E')->setWidth(50); 
$objActSheet->getColumnDimension('F')->setWidth(10); 
$objActSheet->getColumnDimension('G')->setWidth(10); 
$objActSheet->getColumnDimension('H')->setWidth(10); 
$objActSheet->getColumnDimension('I')->setWidth(5); 
$objActSheet->getColumnDimension('J')->setWidth(5); 
$objActSheet->getColumnDimension('K')->setWidth(5); 
$objStyleA = $objActSheet->getStyle('A4');
 
if ($Num > 0){
	while ($Rs = $DB->fetch_array($Query)){
		/*
		$objActSheet->setCellValueExplicit('A' . $j, iconv("UTF-8","big5",$i),PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit('B' . $j, iconv("UTF-8","big5",$Rs['order_serial']),PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit('C' . $j,"",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $j, iconv("UTF-8","big5",$Rs['receiver_name']));
		$objActSheet->setCellValue('E' . $j, iconv("UTF-8","big5",$Rs['post'] . $Rs['addr']));
		*/
		$objActSheet->setCellValueExplicit('A' . $j, $i,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit('B' . $j, $Rs['order_serial'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit('C' . $j,"",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $j, $Rs['receiver_name']);
		$objActSheet->setCellValue('E' . $j, $Rs['receiver_post'] . $Rs['receiver_address']);
		$objActSheet->setCellValue('F' . $j, "");
		$objActSheet->setCellValue('G' . $j, "");
		$objActSheet->setCellValue('H' . $j, "");
		$objActSheet->setCellValue('I' . $j, "");
		$objActSheet->setCellValue('J' . $j, "");
		$objActSheet->setCellValue('K' . $j, "");
		/*
		$objStyleA = $objActSheet->getStyle('A' . $j);
		$objBorderA5 = $objStyleA->getBorders();   
		$objBorderA5->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getTop()->getColor()->setARGB('000000'); // color   
		$objBorderA5->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		$objActSheet->duplicateStyle($objStyleA, 'B' . $j . ':K' . $j);   
		*/
		//$file_string .= $i . "," . $Rs['order_serial'] . ",," . $Rs['receiver_name'] . "," . $Rs['post'] .  $Rs['addr'] .  ",,,,,,\n";
		$i++;
		$j++;
	}
			$objBorderA5 = $objStyleA->getBorders();   
		$objBorderA5->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getTop()->getColor()->setARGB('000000'); // color   
		$objBorderA5->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorderA5->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		$objActSheet->duplicateStyle($objStyleA, 'A4:K' . ($j-1));  

$outputFileName = "order_r_" . date("Y-m-d") . ".xls";
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
}
?>
