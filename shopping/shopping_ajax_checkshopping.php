<?php
require_once( '../Classes/cart.class.php' );
session_start();
header("Cache-Control: must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
include("../configs.inc.php");

include("global.php");
@header("Content-type: text/html; charset=utf-8");

if(!isset($_SESSION['cart']) || $_GET['key'] == "") {
	exit;
}

$cart =$_SESSION['cart'];

$cart->transname_area2=$province = urldecode($_GET['province']);

if ($cart->get_key != $_GET['key']){
	$cart->resetCart();
}
$cart->get_key = $_GET['key'];
//購物車中商品
$items_array = $cart->getCart($_GET['key']);
if (!is_array($items_array) || count($items_array)<=0){
	exit;
}
$county = urldecode($_GET['county']);
$province = urldecode($_GET['province']);
$ifcanec = 1;
$ifarea = 1;	
foreach($items_array as $k => $v){
	
	//是否存在超商商品
	if($v['trans_type']==1){
		$ifcanec = 0;	
	}
	//判斷海外配送
	if ($v['iftransabroad']==0){
		$ifarea = 0;	
	}
}
if ($cart->transname_area!="台灣" && $cart->transname_area!="請選擇" && ($ifarea == 0 || intval($_GET['key']) > 0)){
	$cart->transname_id = "";
	$cart->transname = "";
	$cart->transname_content = "";
	echo 0;	
	exit;
}
$ishave = 0;
$trans_sql = "select * from `{$INFO[DBPrefix]}area` at where at.areaname='" . $county . "' and at.top_id=0";
$trans_Query    = $DB->query($trans_sql);
$trans_Num      = $DB->num_rows($trans_Query);	
if ($trans_Num>0){
	$trans_Rs=$DB->fetch_array($trans_Query);
	$county_id = $trans_Rs['area_id'];
	$county_sql = "select * from `{$INFO[DBPrefix]}area` at where at.top_id='" . $county_id . "'";
	$county_Query    = $DB->query($county_sql);
	$county_Num      = $DB->num_rows($county_Query);
	$province_sql = "select * from `{$INFO[DBPrefix]}area` at where at.areaname='" . $province . "' and  at.top_id='" . $county_id . "'";
	$province_Query    = $DB->query($province_sql);
	$province_Num      = $DB->num_rows($province_Query);
	$province_Rs=$DB->fetch_array($province_Query);
	$province_id = $province_Rs['area_id'];
	if ($county_Num>0 && $province_Num>0){
		$ishave = 1;
	}elseif($county_Num==0){
		$ishave = 1;
	}
}
$key_value = explode("_",$_GET['key']);
if ($key_value[0] == 0){
	$Cart_nomal_trans_type = $cart->transname_id;
	if ($Cart_nomal_trans_type==0 && $cart->ifnotrans!=1)
		$ishave = 0;
}else{
	$ishave = 1;	
}

echo $ishave;
?>
