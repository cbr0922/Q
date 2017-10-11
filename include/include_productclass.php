<?php
@header("Content-type: text/html; charset=utf-8");

if (is_file("configs.inc.php")){
	include_once("configs.inc.php");
}elseif (is_file("../configs.inc.php")){
	include_once("../configs.inc.php");
}
include_once("global.php");
include_once("product.class.php");
$PRODUCT = new PRODUCT();

if (intval($_GET['goods_id']) >0){
	$product_info = $PRODUCT->getSimpleProductInfo(intval($_GET['goods_id']),"bid");  //商品分類ID
	$bid = $product_info['bid'];  
}else{
	$bid = $_GET['bid'];	
}

$menutype = "category";//all左側菜單顯示所有分類級別，category只顯示當前館別分類（即從第二級別分類開始顯示）

$class_banner = array();
$list = 0;
$PRODUCT->getBanner($bid);   //得到對應的第一級分類ID
if(count($class_banner)>0){
	$class_banner = array_reverse($class_banner);
	$top_bid = $class_banner[0][bid];  //頂級分類ID
	$top_banner = $class_banner[0][banner];  //頂級分類圖片可以顯示在菜單上方
}



if($menutype == "category")
	$showbid = 0;
else
	$showbid = $top_bid;
$class_array = $PRODUCT->getProductClass($showbid);  //分類，最多顯示3層


$tpl->assign("ProductListAll",  $class_array);
echo "ccc";
//$tpl->display("include_product_class.html");
?>