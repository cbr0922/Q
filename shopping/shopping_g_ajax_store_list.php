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
//print_r($items_array);
if (!is_array($items_array) || count($items_array)<=0){
	exit;
}
$county = urldecode($_GET['county']);
$province = urldecode($_GET['province']);
$city = urldecode($_GET['city']);
$address = urldecode($_GET['address']);
$store_name = urldecode($_GET['store_name']);
$store_code = urldecode($_GET['store_code']);
if ($_GET['type']=="area")
	$Sql      = "select * from `{$INFO[DBPrefix]}store` where province='" . $province . "' and city='" . $city . "' and address like '%" . $address . "%' ";
if ($_GET['type']=="name")
	$Sql      = "select * from `{$INFO[DBPrefix]}store` where store_name like '%" . $store_name . "%' ";
if ($_GET['type']=="code")
	$Sql      = "select * from `{$INFO[DBPrefix]}store` where store_code like '%" . $store_code . "%' ";
if ($Sql!=""){
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$store_array = array();
	$i = 0;
	while($Rs=$DB->fetch_array($Query)){
		$store_array[$i]['store_id'] = $Rs['store_id'];
		$store_array[$i]['store_name'] = $Rs['store_name'];
		$store_array[$i]['store_code'] = $Rs['store_code'];
		$store_array[$i]['store_province'] = $Rs['province'];
		$store_array[$i]['store_city'] = $Rs['city'];
		$store_array[$i]['store_address'] = $Rs['address'];
		$store_array[$i]['store_tel'] = $Rs['tel'];
		$store_array[$i]['store_map'] = $Rs['map'];
		$i++;
	}
}
$tpl->assign("store_array",  $store_array);
$tpl->assign("store_id",  $store_id);
$tpl->assign("store_name",  $store_name);
$tpl->assign("store_code",  $store_code);
$tpl->assign("store_province",  $store_province);
$tpl->assign("store_city",  $store_city);
$tpl->assign("store_address",  $store_address);
$tpl->assign("store_tel",  $store_tel);
$tpl->assign("store_map",  $store_map);
$tpl->assign("province_array",  $province_array);


$tpl->display("shopping_g_ajax_store_list.html");
?>
