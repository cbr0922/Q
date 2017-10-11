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
if(date("d",time())>=28){
	$btime = time();	
}else{
	$btime = mktime(0, 0 , 0,date("m",time())-1,date("d",time()),date("Y",time()));	
}
$year  = $_GET['year']!="" ? $_GET['year'] : date("Y",$btime);
$month  = $_GET['month']!="" ? $_GET['month'] : date("m",$btime);
$begtimeunix  =mktime(0, 0 , 0,$month-1,26,$year);
$endtimeunix  = mktime(0, 0 , 0,$month,25,$year)+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
$end_month = date("m",mktime(0, 0 , 0,date("m",$endtimeunix)+1,26,date("Y",$endtimeunix)));
$end_year = date("Y",mktime(0, 0 , 0,date("m",$endtimeunix)+1,26,date("Y",$endtimeunix)));														 
$over_month = date("m",mktime(0, 0 , 0,date("m",$endtimeunix),26,date("Y",$endtimeunix)));			
$over_year = date("Y",mktime(0, 0 , 0,date("m",$endtimeunix),26,date("Y",$endtimeunix)));	

$subSql = " and od.provider_id='" . intval($_SESSION[sa_id]) . "'";
$_GET['provider_id'] = intval($_SESSION[sa_id]);
if (intval($_GET['iftogether'])>0){
	$togetherSql .= " and ot.deliveryid='" . intval($_GET['iftogether']) . "'";
}

if(intval($_GET['provider_id'])==114)
	$cost = "cast(od.goods_cost as DECIMAL )";
else
	$cost = "od.cost*od.goodscount";
	
 $Sql = "select ot.*,od.*," . $cost . " as cost from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . $togetherSql . " group by od.order_detail_id order by ot.order_id";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
require_once( RootDocument.'/Resources/phpexcel/PHPExcel.php' ); 
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
$title = $_GET['iftogether']==23?"門市取貨":"宅配";				 
$objActSheet->setTitle(''. $month . '月' . $title . '交易明細');
$FUNCTIONS->setLog("供應商對帳單");
$objActSheet->setCellValue('A1', ''. $month . '月' . $title . '交易明細');  // 字符串内容   
$objActSheet->mergeCells('A1:F1');
$objActSheet->setCellValue('A2', $year . "年" . $month . "月");  // 字符串内容 
$objActSheet->mergeCells('A2:F2');
$objActSheet->setCellValue('A3', "廠商名稱:"); 
$objActSheet->setCellValue('B3', $Rs_p['providerno'] . $Rs_p['provider_name']); 
$objActSheet->mergeCells('B3:D3');
$objActSheet->setCellValue('E3', "對帳區間:");
$objActSheet->setCellValue('F3', $current_year . "/" . $current_month . "/26~" . $over_year . "/" . $over_month . "/25");
$objActSheet->mergeCells('F3:H3');
$objActSheet->setCellValue('A4', "統一編號:"); 
$objActSheet->setCellValueExplicit('B4', $Rs_p['invoice_num'],PHPExcel_Cell_DataType::TYPE_STRING); 
$objActSheet->mergeCells('B4:D4');
$objActSheet->setCellValue('E4', "結算日:");
$objActSheet->setCellValue('F4', $year . "/" . $month . "/26");
$objActSheet->mergeCells('F4:H4');
$objActSheet->setCellValue('A5', "科目：代收金額(+)");
$objActSheet->mergeCells('A5:I5');
$objActSheet->setCellValue('A6', "訂單編號");  // 字符串内容   
$objActSheet->setCellValue('B6', "訂單日期");
$objActSheet->setCellValue('C6', "商品貨號");
$objActSheet->setCellValue('D6', "品名");
$objActSheet->setCellValue('E6', "數量");
$objActSheet->setCellValue('F6', "成本小計");
$objActSheet->setCellValue('G6', "稅額");
$objActSheet->setCellValue('H6', "總計");
$row = 8;
$total1 = 0;
$totalbouns = 0;
$costt = 0;
$i = 1;
while($Rs=$DB->fetch_array($Query)){
	$trans_array[$Rs['order_id']] = $Rs['transport_price'];
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])!=114 && $transport_price>0){
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, "運費");
		$objActSheet->setCellValue('E' . $row, 1);
		$objActSheet->setCellValue('F' . $row, $transport_price);
		$objActSheet->setCellValue('G' . $row, round($transport_price*0.05,0));
		$objActSheet->setCellValue('H' . $row, $transport_price+round($transport_price*0.05,0));
		$total1 += $transport_price+round($transport_price*0.05,0);
		$row++;	
	}
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_bundle_count>0||$cur_house_bundle_count>0)){
		if ($cur_store_bundle_count>0){
		  $goodsname =  "整箱運費(出貨)";
		  $yuncost = 10;
		  $yuncount = $cur_store_bundle_count;
		}elseif ($cur_house_bundle_count>0){
		  $goodsname =   "整箱運費(出貨)";
		  $yuncost = 10;
		  $yuncount = $cur_house_bundle_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total1 += $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
		$row++;	
	}
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_single_count>0||$cur_house_single_count>0)){
		if ($cur_store_single_count>0){
		   $goodsname= "散箱運費(出貨)";
		  $yuncost = 16;
		  $yuncount = $cur_store_single_count;
		}elseif ($cur_house_single_count>0){
		   $goodsname= "散箱運費(出貨)";
		  $yuncost = 16;
		  $yuncount = $cur_house_single_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total1 += $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
		$row++;	
	}
	//$trans_array[$Rs['order_id']] = $Rs['transport_price'];
	$objActSheet->setCellValueExplicit('A' . $row, $Rs['order_serial'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValue('B' . $row, date("Y-m-d",$Rs['createtime']));
	$objActSheet->setCellValueExplicit('C' . $row, $Rs['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValue('D' . $row, $Rs['goodsname']);
	$objActSheet->setCellValue('E' . $row, $Rs['goodscount']);
	$objActSheet->setCellValue('F' . $row, $Rs['cost']);
	$objActSheet->setCellValue('G' . $row, round($Rs['cost']*0.05,0));
	$objActSheet->setCellValue('H' . $row, round($Rs['cost']*0.05,0)+$Rs['cost']);
	$total1+=round($Rs['cost']*0.05,0)+$Rs['cost'];
	$curorder = $Rs['order_serial'];
	$curdeliveryid = $Rs['deliveryid'];
	$curcreatetime = date("Y-m-d",$Rs['createtime']);
	$cur_store_bundle_count = $Rs['store_bundle_count'];
	$cur_store_single_count = $Rs['store_single_count'];
	$cur_house_bundle_count = $Rs['house_bundle_count'];
	$cur_house_single_count = $Rs['house_single_count'];
	$cur_store_return_bundle_count = $Rs['store_return_bundle_count'];
	$cur_store_return_single_count = $Rs['store_return_single_count'];
	$cur_house_return_bundle_count = $Rs['house_return_bundle_count'];
	$cur_house_return_single_count = $Rs['house_return_single_count'];
	$transport_price = $Rs['transport_price'];
	$row++;	
	
	
	$i++;
}
if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])!=114 && $transport_price>0){
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, "運費");
		$objActSheet->setCellValue('E' . $row, 1);
		$objActSheet->setCellValue('F' . $row,$transport_price);
		$objActSheet->setCellValue('G' . $row, round($transport_price*0.05,0));
		$objActSheet->setCellValue('H' . $row, $transport_price+round($transport_price*0.05,0));
		$total1 += $transport_price+round($transport_price*0.05,0);
		$row++;	
	}
if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_bundle_count>0||$cur_house_bundle_count>0)){
		if ($cur_store_bundle_count>0){
		  $goodsname =  "整箱運費(出貨)";
		  $yuncost = 10;
		  $yuncount = $cur_store_bundle_count;
		}elseif ($cur_house_bundle_count>0){
		  $goodsname =   "整箱運費(出貨)";
		  $yuncost = 10;
		  $yuncount = $cur_house_bundle_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total1 += $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
		$row++;	
	}
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_single_count>0||$cur_house_single_count>0)){
		if ($cur_store_single_count>0){
		   $goodsname= "散箱運費(出貨)";
		  $yuncost = 16;
		  $yuncount = $cur_store_single_count;
		}elseif ($cur_house_single_count>0){
		   $goodsname= "散箱運費(出貨)";
		  $yuncost = 16;
		  $yuncount = $cur_house_single_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, "-" . $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, "-" . round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, "-" . $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total1 += $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
		$row++;	
	}
$objActSheet->setCellValue('H' . $row, "小計");
$objActSheet->setCellValue('I' . $row, $total1);
$row++;

if(intval($_GET['provider_id'])==114){
	$objActSheet->setCellValue('H' . $row, "其他費用(未稅)");
	$Sql_m = "select * from `{$INFO[DBPrefix]}provider_month` where mid='" . intval($_GET['mid']) . "'";
	$Query_m    = $DB->query($Sql_m);
	$Rs_m=$DB->fetch_array($Query_m);
	$objActSheet->setCellValue('I' . $row, $Rs_m['zhang']);
}else{
	/*
	$total_tr = 0;
				  if(is_array($trans_array)){
					foreach($trans_array as $k=>$v){
						 $total_tr+= $v;	
					}  
				  }
				  $Rs_m['zhang']=$total_tr;
	$objActSheet->setCellValue('H' . $row, "運費總計");
	
	$objActSheet->setCellValue('I' . $row, $Rs_m['zhang']);	
	*/
}
$total1 = round(intval($Rs_m['zhang'])*1.05,0)+$total1;
$row++;
$objActSheet->setCellValue('H' . $row, "小計(A)");
$objActSheet->setCellValue('I' . $row, $total1);
$row++;
$objActSheet->setCellValue('A' . $row, "科目：代退金額(-)");
$objActSheet->mergeCells('A' . $row . ':I' . $row);
$row++;
$objActSheet->setCellValue('A' . $row, "訂單編號");  // 字符串内容   
$objActSheet->setCellValue('B' . $row, "訂單日期");
$objActSheet->setCellValue('C' . $row, "商品貨號");
$objActSheet->setCellValue('D' . $row, "品名");
$objActSheet->setCellValue('E' . $row, "數量");
$objActSheet->setCellValue('F' . $row, "成本小計");
$objActSheet->setCellValue('G' . $row, "稅額");
$objActSheet->setCellValue('H' . $row, "總計");
$total2 = 0;
$row++;
	$Sql = "select ot.*,od.*," . $cost . " as cost from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and ( oa.state_value=20) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . $togetherSql . " group by od.order_detail_id";
$Query    = $DB->query($Sql);
$cur_store_bundle_count = 0;
$cur_store_single_count = 0;
$cur_house_bundle_count =0;
$cur_house_single_count = 0;
$cur_store_return_bundle_count = 0;
$cur_store_return_single_count = 0;
$cur_house_return_bundle_count = 0;
$cur_house_return_single_count = 0;
$transport_price=0;
while($Rs=$DB->fetch_array($Query)){
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])!=114 && $transport_price>0){
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, "運費");
		$objActSheet->setCellValue('E' . $row,1);
		$objActSheet->setCellValue('F' . $row, "-" . $transport_price);
		$objActSheet->setCellValue('G' . $row,"-" .  round($transport_price*0.05,0));
		$objActSheet->setCellValue('H' . $row, "-" . $transport_price+round($transport_price*0.05,0));
		$total2 += $transport_price+round($transport_price*0.05,0);
		$row++;	
	}
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_return_bundle_count>0||$cur_house_return_bundle_count>0)){
		if ($cur_store_return_bundle_count>0){
		  $goodsname =  "整箱運費(退貨)";
		  $yuncost = 5;
		  $yuncount = $cur_store_return_bundle_count;
		}elseif ($cur_house_return_bundle_count>0){
		  $goodsname =   "整箱運費(退貨)";
		  $yuncost = 5;
		  $yuncount = $cur_house_return_bundle_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, "-" . $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, "-" . round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, "-" . $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total2 -=$yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
		$row++;	
	}
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_return_single_count>0||$cur_house_return_single_count>0)){
		if ($cur_store_return_single_count>0){
		   $goodsname= "散箱運費(退貨)";
		  $yuncost = 8;
		  $yuncount = $cur_store_return_single_count;
		}elseif ($cur_house_return_single_count>0){
		   $goodsname= "散箱運費(退貨)";
		  $yuncost = 16;
		  $yuncount = $cur_house_return_single_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, "-" . $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, "-" . round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, "-" . $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total2 -= $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
		$row++;	
	}
	$objActSheet->setCellValueExplicit('A' . $row, $Rs['order_serial'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValue('B' . $row, date("Y-m-d",$Rs['createtime']));
	$objActSheet->setCellValueExplicit('C' . $row, $Rs['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValue('D' . $row, $Rs['goodsname']);
	$objActSheet->setCellValue('E' . $row, $Rs['goodscount']);
	$objActSheet->setCellValue('F' . $row, $Rs['cost']);
	$objActSheet->setCellValue('G' . $row, round($Rs['cost']*0.05,0));
	$objActSheet->setCellValue('H' . $row, round($Rs['cost']*0.05,0)+$Rs['cost']);
	$curorder = $Rs['order_serial'];
	$curdeliveryid = $Rs['deliveryid'];
	$curcreatetime = date("Y-m-d",$Rs['createtime']);
	$cur_store_bundle_count = $Rs['store_bundle_count'];
	$cur_store_single_count = $Rs['store_single_count'];
	$cur_house_bundle_count = $Rs['house_bundle_count'];
	$cur_house_single_count = $Rs['house_single_count'];
	$cur_store_return_bundle_count = $Rs['store_return_bundle_count'];
	$cur_store_return_single_count = $Rs['store_return_single_count'];
	$cur_house_return_bundle_count = $Rs['house_return_bundle_count'];
	$cur_house_return_single_count = $Rs['house_return_single_count'];
	$transport_price = $Rs['transport_price'];
	$total2+=round($Rs['cost']*0.05,0)+$Rs['cost'];
	$row++;	
	
}
if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])!=114 && $transport_price>0){
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, "運費");
		$objActSheet->setCellValue('E' . $row, 1);
		$objActSheet->setCellValue('F' . $row, "-" . $transport_price);
		$objActSheet->setCellValue('G' . $row, "-" . round($transport_price*0.05,0));
		$objActSheet->setCellValue('H' . $row, "-" . $transport_price+round($transport_price*0.05,0));
		$total2 += $transport_price+round($transport_price*0.05,0);
		$row++;	
	}
if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_return_bundle_count>0||$cur_house_return_bundle_count>0)){
		if ($cur_store_return_bundle_count>0){
		  $goodsname =  "整箱運費(退貨)";
		  $yuncost = 5;
		  $yuncount = $cur_store_return_bundle_count;
		}elseif ($cur_house_return_bundle_count>0){
		  $goodsname =   "整箱運費(退貨)";
		  $yuncost = 5;
		  $yuncount = $cur_house_return_bundle_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, "-" . $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, "-" . round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, "-" . $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total2 -= $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
		$row++;	
	}
	if($curorder!=$Rs['order_serial'] && intval($_GET['provider_id'])==114 && ($cur_store_return_single_count>0||$cur_house_return_single_count>0)){
		if ($cur_store_return_single_count>0){
		   $goodsname= "散箱運費(退貨)";
		  $yuncost = 8;
		  $yuncount = $cur_store_return_single_count;
		}elseif ($cur_house_return_single_count>0){
		   $goodsname= "散箱運費(退貨)";
		  $yuncost = 8;
		  $yuncount = $cur_house_return_single_count;
		}
		$objActSheet->setCellValueExplicit('A' . $row, $curorder,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('B' . $row, $curcreatetime);
		$objActSheet->setCellValueExplicit('C' . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValue('D' . $row, $goodsname);
		$objActSheet->setCellValue('E' . $row, $yuncount);
		$objActSheet->setCellValue('F' . $row, "-" . $yuncount*$yuncost);
		$objActSheet->setCellValue('G' . $row, "-" . round($yuncost*$yuncount*0.05,0));
		$objActSheet->setCellValue('H' . $row, "-" . $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0));
		$total2 -= $yuncost*$yuncount+round($yuncost*$yuncount*0.05,0);
	}
$row++;	
$objActSheet->setCellValue('H' . $row, "小計(B)");
$objActSheet->setCellValue('I' . $row, $total2);


$row+=2;
$total4 = round(($total1-$total2-$total3)/1.05);
$objActSheet->setCellValue('H' . $row, "合計(D=A-B/1.05)");
$objActSheet->setCellValue('I' . $row, $total4);
$row++;
$total5 = round($total4*0.05,0);
$objActSheet->setCellValue('H' . $row, "營業稅(E=D*5%)");
$objActSheet->setCellValue('I' . $row, $total5);
$row++;
$objActSheet->setCellValue('H' . $row, "總計(F=D+E)");
$objActSheet->setCellValue('I' . $row, $total5+$total4);
$outputFileName = $year . "/" . $month . '-' . $Rs_p['providerno'] . 'detail.xls';
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

