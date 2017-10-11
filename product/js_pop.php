<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");

include("../Classes/product.class.php");
$PRODUCT = new PRODUCT();

$Goods_id  = intval($_GET['goods_id']);

$product_array = $PRODUCT->getProductInfo($Goods_id);  //商品信息
if($product_array == 0)
	$FUNCTIONS->header_location("../index.php");
$JS = $PRODUCT->checkJS($product_array);
$JS_array = $JS['Js_open'][count($JS['Js_open'])-1];
$product_array['JS'] = $JS;

foreach($product_array as $k=>$v){
	$tpl->assign("P_" . $k,   $v);
}
foreach($JS_array as $k=>$v){
	$tpl->assign("J_" . $k,   $v);
}

$tpl->display("js_pop.html");

?>

