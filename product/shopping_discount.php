<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

$Subject_id = intval($_GET['saleid']);



$Query = $DB->query("select *  from  `{$INFO[DBPrefix]}discountsubject`  where dsid='".$Subject_id."' and subject_open=1 limit 0,1");
$Num      = $DB->num_rows($Query);
	
while ($Rs    = $DB->fetch_array($Query) ){
	$min_money = $Rs['min_money'];
	$min_count = $Rs['min_count'];
	$mianyunfei = $Rs['mianyunfei'];
	$tpl->assign("Subject_name",          $Rs['subject_name']);              //主题名字
	$tpl->assign("Subject_content",       $Rs['subject_content']);           //主题内容
	$tpl->assign("start_date",          $Rs['start_date']);
	$tpl->assign("end_date",          $Rs['end_date']);
	$tpl->assign("min_money",          $Rs['min_money']);
	$tpl->assign("min_count",          $Rs['min_count']);
	$tpl->assign("mianyunfei",          $Rs['mianyunfei']);
	if (date("Y-m-d",time())>=$Rs['start_date'] && date("Y-m-d",time())<=$Rs['end_date'])
		$tpl->assign("ifbuy",          "1");
}

$saleid = $_GET['saleid'];
$viewProductArray = array();
$total_price = 0;
$total_count = 0;
	if (isset($_COOKIE['discountgoods'])){
		$acount = count($_COOKIE['discountgoods'][$saleid]);
		$J = 0;
		//for($i=0;$i<$acount;$i++){
		if (is_array($_COOKIE['discountgoods'][$saleid] )){
		foreach($_COOKIE['discountgoods'][$saleid] as $k=>$v){
				if (intval($_COOKIE['discountgoods'][$saleid][$k]) > 0){
					$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,dg.price as sale_price from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where g.ifpub=1 and g.gid=".$_COOKIE['discountgoods'][$saleid][$k];
					$Query = $DB->query($Sql);
					$Rs =  $DB->fetch_array($Query);
					$viewProductArray[$J][gid] = $Rs['gid'];
					$viewProductArray[$J]['key'] = $k;
					$viewProductArray[$J][goodsname] = $Rs['goodsname'];
					$viewProductArray[$J][price] = $Rs['price'];
					$viewProductArray[$J][smallimg] = $Rs['smallimg'];
					$viewProductArray[$J][pricedesc] = $Rs['pricedesc'];
					$viewProductArray[$J][sale_price] = $Rs['sale_price'];
					$viewProductArray[$J][color] = $_COOKIE['discountgoods_color'][$saleid][$k];
					$viewProductArray[$J][size] = $_COOKIE['discountgoods_size'][$saleid][$k];
					$viewProductArray[$J]['count'] = $_COOKIE['discountgoods_count'][$saleid][$k];
					$total_price += $Rs['sale_price']*$viewProductArray[$J]['count']; 
					$total_count += $viewProductArray[$J]['count']; 
					$J++;
				}
		}
		}
	}
	$havecount = ($min_count - $total_count)>0?($min_count - $total_count):0;
	$havemoney = ($min_money - $total_price)>0?($min_money - $total_price):0;
	$haveyunfei = ($mianyunfei - $total_price)>0?($mianyunfei - $total_price):0;
	//print_r( $_COOKIE['discountgoods']);print_r( $viewProductArray);
	$tpl->assign("SaleProductArray",      $viewProductArray); 
	$tpl->assign("havecount",      $havecount); 
	$tpl->assign("havemoney",      $havemoney); 
	$tpl->assign("haveyunfei",      $haveyunfei); 
	
$tpl->display("shopping_discount.html");
?>