<?php
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
@header("Content-type: text/html; charset=utf-8");

if(!isset($_SESSION['cart']) || $_GET['key'] == "") {
	exit;
}
$cart =&$_SESSION['cart'];

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
	$Sql      = "select * from `{$INFO[DBPrefix]}store` where province='" . $province . "' and city='" . $city . "' and address like '%" . $address . "%' limit 0,1";
if ($_GET['type']=="name")
	$Sql      = "select * from `{$INFO[DBPrefix]}store` where store_name like '%" . $store_name . "%' limit 0,1";
if ($_GET['type']=="code")
	$Sql      = "select * from `{$INFO[DBPrefix]}store` where store_code like '%" . $store_code . "%' limit 0,1";
if ($Sql!=""){
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$Rs=$DB->fetch_array($Query);
	$store_id = $Rs['store_id'];
	$store_name = $Rs['store_name'];
	$store_code = $Rs['store_code'];
	$store_province = $Rs['province'];
	$store_city = $Rs['city'];
	$store_address = $Rs['address'];
	$store_tel = $Rs['tel'];
	$store_map = $Rs['map'];
}
$a_Sql      = "select distinct province from `{$INFO[DBPrefix]}store` ";
$a_Query    = $DB->query($a_Sql);
$province_array = array();
$i = 0;
while ($a_Rs=$DB->fetch_array($a_Query)) {
	$province_array[$i]['areaname'] = $a_Rs['province'];
	$i++;
}

$tpl->assign("store_id",  $store_id);
$tpl->assign("store_name",  $store_name);
$tpl->assign("store_code",  $store_code);
$tpl->assign("store_province",  $store_province);
$tpl->assign("store_city",  $store_city);
$tpl->assign("store_address",  $store_address);
$tpl->assign("store_tel",  $store_tel);
$tpl->assign("store_map",  $store_map);
$tpl->assign("province_array",  $province_array);


$tpl->display("shopping_ajax_store.html");
?>
