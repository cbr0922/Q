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

if ($_GET['gkey']!="" && intval($_SESSION['user_id'])>0){
	$cart->getdiscount($_GET['key']);
	$memberpoint = $FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
	$combi = 0;
	if ($cart->goodsGroup[$_GET['key']][$_GET['gkey']]['memberorprice']==1){
		$combi = $cart->goodsGroup[$_GET['key']][$_GET['gkey']]['combipoint']*$cart->goodsGroup[$_GET['key']][$_GET['gkey']]['count'];	
	}
	if ($_GET['setprice']==2){
		if (($cart->combipoint+$combi)<=$memberpoint){
			$cart->changeItems($_GET['key'],$_GET['gkey'],"memberorprice",$_GET['setprice']);
			$_SESSION['cartc'][$_GET['key']][$_GET['gkey']] = 2;
		}else{
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您的積分額度不足，您可以使用" . $memberpoint . "積分');location.href='shopping2.php?key=" . $_GET['key'] . "';</script>";exit;	
		}
	}
	if ($_GET['setprice']==1){
		
		$cart->changeItems($_GET['key'],$_GET['gkey'],"memberorprice",$_GET['setprice']);
		$items_array = $cart->getCartGroup($_GET['key']);
		//print_r($items_array);exit;
		$_SESSION['cartc'][$_GET['key']][$_GET['gkey']] = 1;
	}
}else{
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('非會員不能使用會員價格');location.href='shopping2.php?key=" . $_GET['key'] . "';</script>";exit;		
}
echo "<script>location.href='shopping2.php?key=" . $_GET['key'] . "';</script>";

?>
