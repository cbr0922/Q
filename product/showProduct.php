<?php
error_reporting(7);
global $tpl;
include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");
@header("Content-type: text/html; charset=utf-8");
include("product.class.php");
$PRODUCT = new PRODUCT();

$Goods_id  = $FUNCTIONS->Value_Manage($_GET['goods_id'],$_POST['Goods_id'],'back','');  //判断是否有正常的ID进入
$Goods_id  =intval($Goods_id);

$product_array = $PRODUCT->getSimpleProductInfo($Goods_id);  //商品信息
if($product_array == 0)
	$FUNCTIONS->header_location("../index.php");

//print_r($product_array);
//------------------------
//輸出到模板
//------------------------
foreach($product_array as $k=>$v){
	$tpl->assign("P_" . $k,   $v);
}
$tpl->display("showProduct.html");
?>
