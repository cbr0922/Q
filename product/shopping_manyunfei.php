<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

$saleid = intval($_GET['bid']);

$class_banner = array();
$list = 0;
function getBanner($bid){
	global $DB,$INFO,$class_banner,$list,$Bcontent;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$class_banner[$list]['bid'] = $Result['bid'];
		$class_banner[$list]['catname'] = $Result['catname'];
		$class_banner[$list]['banner'] = $Result['banner'];
		$class_banner[$list]['manyunfei'] = $Result['manyunfei'];
		if($Result['catcontent']!="" && $Bcontent == "")
			$Bcontent = $Result['catcontent'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		
	}
}

if (intval($saleid)>0){
	getBanner($saleid);
	$mianyunfei = $class_banner[0][manyunfei];
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
	
	$tpl->assign("class_banner",     $class_banner);
	$tpl->assign("Bcontent",  $Bcontent);
	$tpl->assign("top_Bid",  $saleid);
	$tpl->assign("manyunfei",  $mianyunfei);
}

$viewProductArray = array();
$total_price = 0;
$total_count = 0;
	if (isset($_COOKIE['mangoods'])){
		$acount = count($_COOKIE['mangoods'][$saleid]);
		$J = 0;
		//for($i=0;$i<$acount;$i++){
		if (is_array($_COOKIE['mangoods'][$saleid] )){
		foreach($_COOKIE['mangoods'][$saleid] as $k=>$v){
				if (intval($_COOKIE['mangoods'][$saleid][$k]) > 0){
					$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc as sale_price from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and g.gid='".$_COOKIE['mangoods'][$saleid][$k] . "'";
					$Query = $DB->query($Sql);
					$Rs =  $DB->fetch_array($Query);
					$viewProductArray[$J][gid] = $Rs['gid'];
					$viewProductArray[$J]['key'] = $k;
					$viewProductArray[$J][goodsname] = $Rs['goodsname'];
					$viewProductArray[$J][price] = $Rs['price'];
					$viewProductArray[$J][smallimg] = $Rs['smallimg'];
					$viewProductArray[$J][pricedesc] = $Rs['pricedesc'];
					$viewProductArray[$J][sale_price] = $Rs['sale_price'];
					$viewProductArray[$J][color] = $_COOKIE['mangoods_color'][$saleid][$k];
					$viewProductArray[$J][size] = $_COOKIE['mangoods_size'][$saleid][$k];
					$viewProductArray[$J]['count'] = $_COOKIE['mangoods_count'][$saleid][$k];
					$total_price += $Rs['sale_price']*$viewProductArray[$J]['count']; 
					$total_count += $viewProductArray[$J]['count']; 
					$J++;
				}
		}
		}
	}
	$haveyunfei = ($mianyunfei - $total_price)>0?($mianyunfei - $total_price):0;
	//print_r( $_COOKIE['mangoods']);print_r( $viewProductArray);
	$tpl->assign("SaleProductArray",      $viewProductArray); 
	$tpl->assign("havecount",      $total_count); 
	$tpl->assign("havemoney",      $havemoney); 
	$tpl->assign("total_price",      $total_price); 
	$tpl->assign("haveyunfei",      $haveyunfei); 
	$tpl->assign("times",      time()); 
	//print_r($_COOKIE['mangoods']);
$tpl->display("shopping_manyunfei.html");
?>