<?php
session_start();
include("../configs.inc.php");
require_once RootDocument.'/Classes/cart_group.class.php';
include("global.php");
@header("Content-type: text/html; charset=utf-8");
if(!isset($_SESSION['cart_group']) || $_GET['key'] == "") {
	exit;
}

$cart =&$_SESSION['cart_group'];

if ($cart->get_key != $_GET['key']){
	$cart->resetCart();
}
$cart->get_key = $_GET['key'];
//購物車中商品
$items_array = $cart->getCartGroup($_GET['key']);
if (!is_array($items_array) || count($items_array)<=0){
	exit;
}

$county = urldecode($_GET['county']);
$province = urldecode($_GET['province']);
$cart->transname_area=$county;
$cart->transname_area2=$province;
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
echo $ishave;
?>
