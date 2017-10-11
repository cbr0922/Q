<?php
require_once( '../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";

//require "check_member.php";
@header("Content-type: text/html; charset=utf-8");

if(!isset($_SESSION['cart'])) {
 $_SESSION['cart'] = new Cart;
}

$cart =&$_SESSION['cart'];
if (!method_exists($cart,"getCart")){
   $_SESSION['cart'] = new Cart;
   $cart =& $_SESSION['cart'];
}
$items_array = $cart->getCart();
$Cart_item = array();

$i = 0;
$MemberPice_total = 0;
$num = 0;
$count = 0;
if (is_array($items_array)){
	foreach($items_array as $k=>$v){
		$j = 0;
		$Cart_item[$i]['key'] = intval($k);
		if (is_array($v)){
			foreach($v as $kk=>$vv){
				if ($vv['packgid']==0){
				$Cart_item[$i]['goods'][$j] = $vv;
				$Cart_item[$i]['goods'][$j]['total'] = $vv['count'] * $vv['price'];
				$MemberPice_total+=$Cart_item[$i]['goods'][$j]['total'];
				
				if ($Cart_item[$i]['goods'][$j][ifbonus] == 1 || $Cart_item[$i]['goods'][$j][ifgoodspresent] == 1 || $Cart_item[$i]['goods'][$j][ifpresent] == 1 || $Cart_item[$i]['goods'][$j][ifchange] == 1 || $Cart_item[$i]['goods'][$j][ifadd] == 1) {
					$urlisbox=1;
				}else{
					$urlisbox=0;
				}
				
				$num+=$vv['count'];
        $count++;
				$j++;
				}
			}
		}
		$i++;
	}
}

$tpl->assign("Cart_item",       $Cart_item);
$tpl->assign("CountItem",       $num); //个
$tpl->assign("Cart_Count",      $count);
//$totalPrices = $cart->ThetotalPrices;
//$tpl->assign("totalPrices",     $totalPrices); //总数
$tpl->assign("totalPrices",     $MemberPice_total); //总数
$tpl->assign("urlisbox",     $urlisbox);

$tpl->assign($Cart);
$tpl->display("cart_windows.html");

?>
