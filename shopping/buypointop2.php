<?php
error_reporting(0);
require_once 'cart.class.php';
session_start();
include("../configs.inc.php");
include("global.php");

require "check_member.php";
@header("Content-type: text/html; charset=utf-8");

if(!isset($_SESSION['cart']) || $_POST['key'] == "") {
	header("Location:shopping.php?hometype=" . $_POST['hometype']);
}

$cart =&$_SESSION['cart'];
$cart->setTotal($_POST['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$Cart_totalGrouppoint = $cart->totalGrouppoinit;
$Cart_transmoney = $cart->transmoney;

if(intval($_SESSION['user_id'])>0)
	$myBuyPoint = $FUNCTIONS->Buypoint(intval($_SESSION['user_id']));

if(intval($_POST['buypoint']) > $myBuyPoint){
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用" . $myBuyPoint . "購物金');location.href='shopping2_g.php?key=" . $_POST['key'] . "';</script>";exit;
}
if(intval($_POST['buypoint']) > $Cart_totalPrices+$Cart_transmoney){
	$_POST['buypoint'] = $Cart_totalPrices+$Cart_transmoney;
}
$usebuypoint = intval($_POST['buypoint']);

$cart->setBuypoint($usebuypoint);

header("Location:shopping3.php?key=" . $_POST['key'] . "&hometype=" . $_POST['hometype']);

?>
