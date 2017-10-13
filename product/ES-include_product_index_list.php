<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include_once ("../configs.inc.php");
include("global.php");

include_once ("Char.class.php");
$Char_Class = new Char_class();

include("product.class.php");
$PRODUCT = new PRODUCT();

$bid = intval($_GET['bid']);

$type = $_GET['type'];
if($type=="new"){
	$showcount = 9;
	$ifpage = 0;
}else{
	$showcount = 0;
	$ifpage = 1;
}

$_GET['ordertype'] = intval($_GET['ordertype']);
	//商品列表
	$product_array = $PRODUCT->getProductList($bid,$type,array('key'=>$_GET['skey']),array($_GET['orderby'],$_GET['ordertype']),$showcount,$ifpage,1,0,1);
	//$product_array = $PRODUCT->getProductList($bid,$type,array('key'=>$_GET['skey']),array($_GET['orderby'],$_GET['ordertype']),0,1,1,0,1);
	//屬性

$classinfo_array = $PRODUCT->getClassInfo($bid);   //得到分類信息
	
	switch($type){
			case "bonus":
				$title = "紅利商品";
				break;
			case "xy":
				$title = "任選商品";
				break;
			case "present":
				$title = "額滿禮商品";
				break;
			case "change":
				$title = "加購商品";
				break;
			case "js":
				$title = "集殺商品";
				break;
			case "recommend":
				$title = "推薦商品";
				break;
			case "hot":
				$title = "熱賣商品";
				break;
			case "special":
				$title = "特價商品";
				break;
			case "new":
				$title = "最新商品";
				break;
		}
//品牌
if(intval($_GET['brand_id'])>0){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($_GET['brand_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$brandname    =  trim($Result['brandname']);
		$brandcontent    =  trim($Result['brandcontent']);
		$logopic    =  trim($Result['logopic']);
		$content    =  trim($Result['content']);
		$meta_des    =  trim($Result['meta_des']);
		$meta_key    =  trim($Result['meta_key']);

		$tpl->assign("meta_key",       $meta_key);
		$tpl->assign("meta_des",       $meta_des);
	}
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where brandlist=".intval($_GET['brand_id'])." or brandlist like '%,".intval($_GET['brand_id'])."%' or brandlist like '%".intval($_GET['brand_id']).",%' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		while($Result= $DB->fetch_array($Query)){
			//print_r($Result);
			if($Result['bid']==6){
				$tpl->assign("showalert",1);
			}
		}
	}
	$tpl->assign("brand_id",     intval($_GET['brand_id']));
}
$class_banner = array();
$list = 0;
if (intval($_GET['brand_class'])>0){
	$PRODUCT->getTopBrandBidList(intval($_GET['brand_class']));   //導航
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}
$menutype = "category";//all左側菜單顯示所有分類級別，category只顯示當前館別分類（即從第二級別分類開始顯示）
if($menutype == "all")
	$showbid = 0;
else
	$showbid = $class_banner[0][bid];
$tpl->assign("showbid",     $showbid);
$tpl->assign("class_banner",     $class_banner);
$tpl->assign("title",       $title);
$tpl->assign("content",       $content);
$tpl->assign("classinfo_array",     $classinfo_array);
$tpl->assign("brandname",  $brandname);
$tpl->assign("brandcontent",  $brandcontent);
$tpl->assign("product_array",     $product_array);
$tpl->display("ES-include_product_index_list.html");
?>
