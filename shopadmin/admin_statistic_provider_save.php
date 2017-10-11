<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
$year  = $_GET['year']!="" ? $_GET['year'] : date("Y",time());
$month  = $_GET['month']!="" ? $_GET['month'] : date("m",time());
$begtime  =date("Y-m-d H:i:s",mktime(0, 0 , 0,$month-1,26,$year));
$endtime  = date("Y-m-d H:i:s",mktime(0, 0 , 0,$month,25,$year));
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
$Array_bid =  $_GET['cid'];
$Num_bid  = count($Array_bid);
foreach($_GET as $k=>$v){
		$restring .= "&" . $k . "=" . $v;	
	}
	//echo $restring;exit;
	//print_r($_POST);
if ($_GET['action'] == "OK"){
	//echo "update `{$INFO[DBPrefix]}provider_month` set paymoney='" . intval($_GET['paymoney']) . "', mark='" . trim($_GET['mark']) . "' where mid='" . intval($_GET['mid']) . "'";exit;
		//$Result =  $DB->query("update `{$INFO[DBPrefix]}provider_month` set paymoney=" . intval($_GET['paymoney']) . "-round(zhang*1.05), mark='" . trim($_GET['mark']) . "' where mid='" . intval($_GET['mid']) . "'");
		$Result =  $DB->query("update `{$INFO[DBPrefix]}provider_month` set mark='" . trim($_GET['mark']) . "',invoice_titles='" . trim($_GET['invoice_titles']) . "' where mid='" . intval($_GET['mid']) . "'");
		
			
	//}
	$FUNCTIONS->setLog("供應商對帳確認");
	$FUNCTIONS->header_location('admin_statistic_provider_month.php?type=' . $_GET['type'] . $restring);
}
if ($_GET['acts'] == "suan"){
	$Array_bid =  $_GET['cid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("update `{$INFO[DBPrefix]}provider_month` set ifok=1 where mid='" . intval($Array_bid[$i]) . "'");
	}
	$FUNCTIONS->setLog("供應商對帳結算");
	$FUNCTIONS->header_location('admin_statistic_provider_month.php?type=' . $_GET['type'] . $restring);
}
if ($_GET['detailaction'] == "sale"){
	//echo "update `{$INFO[DBPrefix]}provider_month` set zhang='" . $_POST['zhang'] . "' where mid='" . intval($_POST['mid']) . "'";exit;
	$Result =  $DB->query("update `{$INFO[DBPrefix]}provider_month` set zhang='" . $_GET['zhang'] . "' where mid='" . intval($_GET['mid']) . "'");
	$FUNCTIONS->setLog("供應商對帳結算");
	
	$FUNCTIONS->header_location('admin_statistic_provider_detail.php?' . $restring);
}


?>