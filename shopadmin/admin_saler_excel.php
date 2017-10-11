<?php
@ob_start();
//session_start();
include "Check_Admin.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
@ob_implicit_flush(0);
$Creatfile ="saler_".date("Y-m-d");
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
	@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
include_once "Time.class.php";
$TimeClass = new TimeClass;

if ($_GET['Action']=="Search"){
	$S_year        = $_GET['S_year'];
	$S_month       = $_GET['S_month']>9 ? $_GET['S_month'] : "0".$_GET['S_month'] ;
	$S_day       = $_GET['S_day']>9 ? $_GET['S_day'] : "0".$_GET['S_day'] ;
	$E_year        = $_GET['E_year'];
	$E_month       = $_GET['E_month']>9 ? $_GET['E_month'] : "0".$_GET['E_month'] ;
	$E_day       = $_GET['E_day']>9 ? $_GET['E_day'] : "0".$_GET['E_day'] ;
}else{
	$S_year =  date("Y",time()-30*24*60*60);
	$S_month = date("m",time()-30*24*60*60);
	$S_day = date("d",time()-30*24*60*60);
	$E_year        = date("Y",time());
	$E_month       = date("m",time());
	$E_day       = date("d",time());
}
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($S_year."-".$S_month."-".$S_day,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($E_year."-".$E_month."-".$E_day,"-")+24*60*60-1;

$Sql = "select od.*,o.saler,o.createtime,o.order_serial,o.paymentname,u.true_name from `{$INFO[DBPrefix]}order_table` as o left join  `{$INFO[DBPrefix]}order_detail` od on o.order_id=od.order_id left join `{$INFO[DBPrefix]}user` as u on o.user_id=u.user_id where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix'";
/*if ($_GET['payment']!=""){
	$Sql .= " and o.paymentname='" . $_GET['payment'] . "'"; 
}*/
if ($_GET['saler']!=""){
	$Sql .= " and o.saler='" . $_GET['saler'] . "'"; 
}

if( isset($_GET['type']) ){
  if( $_GET['type'] == 0 )
    $Sql .= " and order_state=4";
  else if( $_GET['type'] == 1 )
    $Sql .= " and pay_state=1 and transport_state=1";
}
else{
  $Sql .= " and order_state=4";
}


$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$file_string .= iconv("UTF-8","big5","訂單編號,訂購人,商品名稱,單位,市價,網絡價,銷售數量,銷售金額,業績,經銷商\n");
if ($Num > 0){
	while ($Rs = $DB->fetch_array($Query)){
		$salern = 0;
		$Query_s = $DB->query("select * from `{$INFO[DBPrefix]}saler` where login='" . trim($Rs['saler']) . "' limit 0,1");
		$Num_s   = $DB->num_rows($Query_s);
	  
		if ($Num_s>0){
			$Result= $DB->fetch_array($Query_s);
			if (intval($Result['salerset'])>0){
				$salerset = intval($Result['salerset']);
			}else{
				$Query_s = $DB->query("select * from `{$INFO[DBPrefix]}salermoney` limit 0,1");
				$Num_s   = $DB->num_rows($Query_s);
				  if ($Num>0){
					$Result= $DB->fetch_array($Query_s);
					$salerset           =  $Result['salerset'];
				  }	
			}
		}
		if ($salerset > 0){
			$salern = round($Rs['goodscount']*$Rs['price']*($salerset/100),0);	
		}
	
		$file_string .= $Rs['order_serial'] . "," . iconv("UTF-8","big5",$Rs['true_name']) . "," . iconv("UTF-8","big5",$Rs['goodsname']) . "," . iconv("UTF-8","big5",$Rs['unit']) . "," . $Rs['market_price'] .  "," . $Rs['price'] .  "," . $Rs['goodscount'] .  "," . ($Rs['goodscount']*$Rs['price']) .  "," . $salern . "," . iconv("UTF-8","big5",$Rs['saler']) . "\n";
	}



	
	


	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 * 
	 */
	

	
}
echo $file_string;
?>