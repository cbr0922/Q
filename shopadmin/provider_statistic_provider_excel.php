<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";

include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
if(date("d",time())>=28){
	$btime = time();	
}else{
	$btime = mktime(0, 0 , 0,date("m",time())-1,date("d",time()),date("Y",time()));	
}
$year  = $_GET['year']!="" ? $_GET['year'] : date("Y",$btime);
$month  = $_GET['month']!="" ? $_GET['month'] : date("m",$btime);
$begtime  =date("Y-m-d H:i:s",mktime(0, 0 , 0,$month-1,26,$year));
$endtime  = date("Y-m-d H:i:s",mktime(0, 0 , 0,$month,25,$year));
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
	$subSql = " and p.provider_id='" . intval($_SESSION[sa_id]) . "'";
if ($_GET['ifcheck']=="1"){
	$togetherSql2 .= " and pm.ifok=1";
}
if ($_GET['skey']!=""){
	$togetherSql2 .= " and p." . $_GET['select_type'] . " like '%" . $_GET['skey'] . "%'";
}
if ($_GET['ifcheck']=="0"){
	$togetherSql2 .= " and pm.ifok=0";
}
if (intval($_GET['iftogether'])>0){
	$togetherSql2 .= " and pm.iftogether='" . intval($_GET['iftogether']) . "'";
}
/*
$Sql = "select p.*,ot.deliveryid,p.provider_id as provider_id from `{$INFO[DBPrefix]}order_action` oa 
inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0) and oa.order_id=od.order_id
left join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=oa.order_id 
left join `{$INFO[DBPrefix]}provider` as p on p.provider_id=od.provider_id 
left join `{$INFO[DBPrefix]}provider_month` as pm on (p.provider_id=pm.pid and pm.year='" . $current_year . "' and pm.month='" . $current_month . "'  and pm.iftogether=ot.deliveryid)
where oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and  ot.deliveryid>0 " . $subSql . " " . $togetherSql . " group by p.provider_id,ot.deliveryid";
*/
$Sql = "select p.*,pm.* from `{$INFO[DBPrefix]}provider_month` as pm inner join `{$INFO[DBPrefix]}provider` as p on p.provider_id=pm.pid where pm.year='" . $year . "' and pm.month='" . $month . "' " . $togetherSql2 . " and pm.pid='" . intval($_SESSION[sa_id]) . "'";

$Query    = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
require_once( RootDocument.'/Resources/phpexcel/PHPExcel.php' ); 
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
							 
$objActSheet->setTitle('來來物流對帳');
$FUNCTIONS->setLog("來來物流對帳");
$objActSheet->setCellValue('A1', $province);  // 字符串内容   
$objActSheet->mergeCells('A1:F1');
$objActSheet->setCellValue('A2', $year . "年" . $month . "月");  // 字符串内容 
$objActSheet->mergeCells('A2:F2');
$objActSheet->setCellValue('A5', "編號");  // 字符串内容   
$objActSheet->setCellValue('B5', "月份");
$objActSheet->setCellValue('C5', "廠編");
$objActSheet->setCellValue('D5', "廠商簡稱");
$objActSheet->setCellValue('E5', "商品狀態");
$objActSheet->setCellValue('F5', "請款金額");
$objActSheet->setCellValue('G5', "實際請款金額");
$objActSheet->setCellValue('H5', "票期");
$objActSheet->setCellValue('I5', "兌現日");
$objActSheet->setCellValue('J5', "廠商發票");
$objActSheet->setCellValue('K5', "對帳狀態");
$row = 6;
$totals = 0;
$totalbouns = 0;
$i = 1;
while($Rs=$DB->fetch_array($Query)){
	if ($_GET['iftogether']=="1"){
		$subSql = " and ot.iftogether=1";
	}
	if ($_GET['iftogether']=="0"){
		$subSql .= " and ot.iftogether=0";
	}
	if ($Rs['ifok']==1)
		$state = "結案";
	else
		$state =  "未結案";
		/*
	$Sql_d = "select case when (oa.state_value=13 or oa.state_value=17 or oa.state_value=20) then (0-cast(od.goods_cost as DECIMAL)) else (cast(od.goods_cost as DECIMAL )) end as sumcost,ot.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and od.provider_id='" . $Rs['provider_id'] . "' and ot.deliveryid='" . $Rs['deliveryid'] . "' group by od.order_detail_id order by ot.order_id";
						$Query_d    = $DB->query($Sql_d);
						$total = 0;
						$yunfei = 0;
					    $Num_d      = $DB->num_rows($Query_d);
						$curorder = "";
						$cur_store_bundle_count = 0;
							$cur_store_single_count = 0;
							$cur_house_bundle_count = 0;
							$cur_house_single_count = 0;
							$cur_store_return_bundle_count = 0;
							$cur_store_return_single_count = 0;
							$cur_house_return_bundle_count =0;
							$cur_house_return_single_count = 0;
						while ($Rs_d=$DB->fetch_array($Query_d)) {
							//echo $Rs_d['sumcost'];
							$total+= round($Rs_d['sumcost']*1.05,0);
							if($curorder!=$Rs_d['order_serial'])
								$yunfei += round($cur_store_bundle_count*10*1.05,0) + round($cur_store_single_count*16*1.05,0) + round($cur_house_bundle_count*10*1.05,0) + round($cur_house_single_count*16*1.05,0)-round($cur_store_return_bundle_count*5*1.05,0)-round($cur_store_return_single_count*8*1.05,0)-round($cur_house_return_bundle_count*5*1.05,0)-round($cur_house_return_single_count*8*1.05,0);
							$curorder = $Rs_d['order_serial'];
							$curdeliveryid = $Rs_d['deliveryid'];
							$cur_store_bundle_count = $Rs_d['store_bundle_count'];
							$cur_store_single_count = $Rs_d['store_single_count'];
							$cur_house_bundle_count = $Rs_d['house_bundle_count'];
							$cur_house_single_count = $Rs_d['house_single_count'];
							$cur_store_return_bundle_count = $Rs_d['store_return_bundle_count'];
							$cur_store_return_single_count = $Rs_d['store_return_single_count'];
							$cur_house_return_bundle_count = $Rs_d['house_return_bundle_count'];
							$cur_house_return_single_count = $Rs_d['house_return_single_count'];
						}
						$yunfei += round($cur_store_bundle_count*10*1.05,0) + round($cur_store_single_count*16*1.05,0) + round($cur_house_bundle_count*10*1.05,0) + round($cur_house_single_count*16*1.05,0)-round($cur_store_return_bundle_count*5*1.05,0)-round($cur_store_return_single_count*8*1.05,0)-round($cur_house_return_bundle_count*5*1.05,0)-round($cur_house_return_single_count*8*1.05,0);
						//$yunfei = $Rs['store_bundle_count']*10 + $Rs['store_single_count']*16 + $Rs['house_bundle_count']*10 + $Rs['house_single_count']*16-$Rs['store_return_bundle_count']*5-$Rs['store_return_single_count']*8-$Rs['house_return_bundle_count']*5-$Rs['house_return_single_count']*8;
						$total+= $yunfei;
						*/
	$type = $Rs['iftogether']==23?"門市取貨":"宅配";
	$shiji = intval($Rs['paymoney']+round(intval($Rs['zhang'])*1.05,0));	
	$objActSheet->setCellValue('A' . $row, $i);
	$objActSheet->setCellValue('B' . $row, $month . "月");
	$objActSheet->setCellValue('C' . $row, $Rs['providerno']);
	$objActSheet->setCellValue('D' . $row, $Rs['provider_name']);
	$objActSheet->setCellValue('E' . $row, $type);
	$objActSheet->setCellValue('F' . $row, $total . "元");
	$objActSheet->setCellValue('G' . $row, $shiji . "元");
	$objActSheet->setCellValue('H' . $row, $Rs['piaoqi']);
	$objActSheet->setCellValue('I' . $row, date("Y-m-d",$endtimeunix+intval($Rs['piaoqi'])*60*60*24));
	$objActSheet->setCellValue('J' . $row, $Rs['invoice_titles']);
	$objActSheet->setCellValue('K' . $row, $state);
	$totals+=$shiji;
	$row++;	
	$i++;
}
$objActSheet->setCellValue('A' . $row, "總計");
$objActSheet->setCellValue('C' . $row, $totals . "元");

$outputFileName = $_GET['province'] ." " . $year ."/". $month . ".xls";
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
