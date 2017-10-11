<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

$Subject_id = intval($_GET['saleid']);



$Query = $DB->query("select subject_name,subject_content,salecount  from  `{$INFO[DBPrefix]}sale_subject`  where subject_id='".$Subject_id."'  limit 0,1");

while ($Rs    = $DB->fetch_array($Query) ){
	$salecount = $Rs['salecount'];
	$tpl->assign("Subject_name",          $Rs['subject_name']);              //主题名字
	$tpl->assign("Subject_content",       $Rs['subject_content']);           //主题内容
	$tpl->assign("salecount",          $Rs['salecount']);
}
$saleid = $_GET['saleid'];
$viewProductArray = array();
	if (isset($_COOKIE['buysalegoods'])){
		$acount = count($_COOKIE['buysalegoods'][$saleid]);
		$J = 0;
		//for($i=0;$i<$acount;$i++){
		if (is_array($_COOKIE['buysalegoods'][$saleid] )){
		foreach($_COOKIE['buysalegoods'][$saleid] as $k=>$v){
				if (intval($_COOKIE['buysalegoods'][$saleid][$k]) > 0){
					$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.sale_price from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and g.gid=".$_COOKIE['buysalegoods'][$saleid][$k];
					$Query = $DB->query($Sql);
					$Rs =  $DB->fetch_array($Query);
					$viewProductArray[$J][gid] = $Rs['gid'];
					$viewProductArray[$J]['key'] = $k;
					$viewProductArray[$J][goodsname] = $Rs['goodsname'];
					$viewProductArray[$J][price] = $Rs['price'];
					$viewProductArray[$J][smallimg] = $Rs['smallimg'];
					$viewProductArray[$J][pricedesc] = $Rs['pricedesc'];
					$viewProductArray[$J][sale_price] = $Rs['sale_price'];
					$viewProductArray[$J][color] = $_COOKIE['buysalegoods_color'][$saleid][$k];
					$viewProductArray[$J][size] = $_COOKIE['buysalegoods_size'][$saleid][$k];
					$J++;
				}
		}
		}
	}
	$havecount = ($salecount - $J)>0?($salecount - $J):0;
	//print_r( $_COOKIE['buysalegoods']);print_r( $viewProductArray);
	$tpl->assign("SaleProductArray",      $viewProductArray); 
	$tpl->assign("havecount",      $havecount); 
	
$tpl->display("shopping_sale.html");
?>