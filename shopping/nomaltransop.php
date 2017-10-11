<?php
error_reporting(0);
require_once( '../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
require "check_member.php";

@header("Content-type: text/html; charset=utf-8");

if(!isset($_SESSION['cart']) || $_GET['key'] == "") {
	header("Location:shopping.php");
}

$cart =&$_SESSION['cart'];
if (intval($_GET['transid'])>0){
	$Query  = $DB->query("select transport_id,transport_name,transport_price,transport_content from `{$INFO[DBPrefix]}transportation` where transport_id='" . intval($_GET['transid']) . "' order by transport_id asc ");
	$Rs=$DB->fetch_array($Query);
	$cart->transname = $Rs['transport_name'];
	$cart->transname_content = $Rs['transport_content'];
	$cart->setTransMoney(0,intval($_GET['key']),intval($_GET['transid']),intval($_GET['transmoney']));	
}

//echo intval($_GET['transid']);
//echo $Cart_nomal_trans_type = $cart->nomal_trans_type;
//exit;
header("Location:shopping2.php?key=" . $_GET['key'] . "&op=trans");

?>

