<?php
//error_reporting(0);
session_start();
include("../configs.inc.php");
require_once RootDocument.'/Classes/cart_group.class.php';
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";

require "check_member.php";
@header("Content-type: text/html; charset=utf-8");
if (intval($_SESSION['user_id'])>0){
	$member_grouppoint = $FUNCTIONS->Grouppoint(intval($_SESSION['user_id']));
}
/**
 cart_LOGO的尺寸
*/
$tpl->assign("cart_logo_width",  $INFO["cart_logo_width"]);
$tpl->assign("cart_logo_height", $INFO["cart_logo_height"]);

if(!isset($_SESSION['cart_group']) || $_GET['key'] == "") {
	header("Location:shopping.php");
}

$cart =&$_SESSION['cart_group'];
if ($cart->get_key != $_GET['key']){
	$cart->resetCart();
}
$cart->get_key = $_GET['key'];
//購物車中商品
$items_array = $cart->getCartGroup($_GET['key']);
if (!is_array($items_array) || count($items_array)<=0){
	header("Location:shopping_g.php");
}
$Cart_item = array();
$i = 0;

foreach($items_array as $k => $v){
	$Cart_item[$i] = $v;
	$Cart_item[$i]['price']       = $cart->setSaleoff($_GET['key'],$v['gkey']);
	$Cart_item[$i]['total'] = $v['count'] * $Cart_item[$i]['price'];
	if ($v['buytype']==0)
		$Cart_item[$i]['total'] = $v['count'] * $Cart_item[$i]['price'];
	if ($v['buytype']==1){
		$Cart_item[$i]['total'] = $v['count'] * $v['memberprice'];
		$Cart_item[$i]['totalpoint'] = $v['count'] * $Cart_item[$i]['grouppoint'];
	}
	$i++;
}
$cart->setTotal($_GET['key']);
$Cart_totalPrices = $cart->totalPrices;//優惠金額
$Cart_totalGrouppoint = $cart->totalGrouppoinit;

$tpl->assign("MaxbonusPoint",      $MaxbonusPoint);
$tpl->assign("Cart_item",          $Cart_item);
$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);
$tpl->assign("Cart_totalGrouppoint",   $Cart_totalGrouppoint);
$tpl->assign("sumpoint",   $member_grouppoint);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
$tpl->display("shopping2_g.html");
?>
