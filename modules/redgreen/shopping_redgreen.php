<?php
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");

$Subject_id = intval($_GET['saleid']);



$Query = $DB->query("select *  from  `{$INFO[DBPrefix]}subject_redgreen`  where rgid='".$Subject_id."' and subject_open=1 limit 0,1");
$Num      = $DB->num_rows($Query);
	
while ($Rs    = $DB->fetch_array($Query) ){
	$saleoff = $Rs['saleoff'];
	if (date("Y-m-d",time())>=$Rs['start_date'] && date("Y-m-d",time())<=$Rs['end_date'])
		$tpl->assign("ifbuy",          "1");
}

$saleid = $_GET['saleid'];
//紅標商品
$redProductArray = array();
$red_total_price = 0;
$red_total_count = 0;
if (isset($_COOKIE['redgoods'])){
	$acount = count($_COOKIE['redgoods'][$saleid]);
	$J = 0;
	$t = 0;
	//for($i=0;$i<$acount;$i++){
		$price_array = array();
	if (is_array($_COOKIE['redgoods'][$saleid] )){
		foreach($_COOKIE['redgoods'][$saleid] as $k=>$v){
				if (intval($_COOKIE['redgoods'][$saleid][$k]) > 0){
					$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc as sale_price,d.detail_name,d.detail_pricedes from `{$INFO[DBPrefix]}subject_redgoods` as dg inner join `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid left join `{$INFO[DBPrefix]}goods_detail` as d on (d.gid=g.gid and d.detail_id='" . intval($_COOKIE['redgoods_detail'][$saleid][$k]) . "') where g.ifpub=1 and g.gid=".$_COOKIE['redgoods'][$saleid][$k];
					$Query = $DB->query($Sql);
					$Rs =  $DB->fetch_array($Query);
					$redProductArray[$J][gid] = $Rs['gid'];
					$redProductArray[$J]['key'] = $k;
					$redProductArray[$J][goodsname] = $Rs['goodsname'];
					$redProductArray[$J][price] = $Rs['price'];
					$redProductArray[$J][smallimg] = $Rs['smallimg'];
					$redProductArray[$J][pricedesc] = $Rs['sale_price'];
					$redProductArray[$J][sale_price] = $Rs['sale_price'];
					$redProductArray[$J][color] = $_COOKIE['redgoods_color'][$saleid][$k];
					$redProductArray[$J][size] = $_COOKIE['redgoods_size'][$saleid][$k];
					$redProductArray[$J][detail_id] = $_COOKIE['redgoods_detail'][$saleid][$k];
					$redProductArray[$J][detail_name] = $Rs['detail_name'];
					if($redProductArray[$J][detail_id]>0)
						$redProductArray[$J][pricedesc] = $Rs['detail_pricedes'];
					$redProductArray[$J]['count'] = $_COOKIE['redgoods_count'][$saleid][$k];
					
					 $red_total_price += $redProductArray[$J][sale_price]*$redProductArray[$J]['count']; 
					$red_total_count += $redProductArray[$J]['count']; 
					$J++;
				}
		}
	}
}
$tpl->assign("red_total_price",      intval($red_total_price)); 
$tpl->assign("red_total_count",      intval($red_total_count)); 
$tpl->assign("redProductArray",      $redProductArray); 
	
//綠標商品
$redProductArray = array();
$red_total_price = 0;
$red_total_count = 0;
if (isset($_COOKIE['greengoods'])){
	$acount = count($_COOKIE['greengoods'][$saleid]);
	$J = 0;
	$t = 0;
	//for($i=0;$i<$acount;$i++){
		$price_array = array();
	if (is_array($_COOKIE['greengoods'][$saleid] )){
		foreach($_COOKIE['greengoods'][$saleid] as $k=>$v){
				if (intval($_COOKIE['greengoods'][$saleid][$k]) > 0){
					$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc as sale_price,d.detail_name,d.detail_pricedes from `{$INFO[DBPrefix]}subject_greengoods` as dg inner join `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid left join `{$INFO[DBPrefix]}goods_detail` as d on (d.gid=g.gid and d.detail_id='" . intval($_COOKIE['redgoods_detail'][$saleid][$k]) . "') where g.ifpub=1 and g.gid=".$_COOKIE['greengoods'][$saleid][$k];
					$Query = $DB->query($Sql);
					$Rs =  $DB->fetch_array($Query);
					$greenProductArray[$J][gid] = $Rs['gid'];
					$greenProductArray[$J]['key'] = $k;
					$greenProductArray[$J][goodsname] = $Rs['goodsname'];
					$greenProductArray[$J][price] = $Rs['price'];
					$greenProductArray[$J][smallimg] = $Rs['smallimg'];
					$greenProductArray[$J][pricedesc] = $Rs['sale_price'];
					$greenProductArray[$J][sale_price] = $Rs['sale_price'];
					$greenProductArray[$J][color] = $_COOKIE['greengoods_color'][$saleid][$k];
					$greenProductArray[$J][size] = $_COOKIE['greengoods_size'][$saleid][$k];
					$greenProductArray[$J][detail_id] = $_COOKIE['greengoods_detail'][$saleid][$k];
					$greenProductArray[$J][detail_name] = $Rs['detail_name'];
					if($greenProductArray[$J][detail_id]>0)
						$greenProductArray[$J][pricedesc] = $Rs['detail_pricedes'];
					$greenProductArray[$J][sale_price] = round($saleoff * $greenProductArray[$J][pricedesc]/100,0);
					$greenProductArray[$J]['count'] = $_COOKIE['greengoods_count'][$saleid][$k];
					
					$green_total_price += $greenProductArray[$J][sale_price]*$greenProductArray[$J]['count']; 
					$green_total_count += $greenProductArray[$J]['count']; 
					$J++;
				}
		}
	}
}
$tpl->assign("green_total_price",      intval($green_total_price)); 
$tpl->assign("green_total_count",      intval($green_total_count)); 
$tpl->assign("greenProductArray",      $greenProductArray); 
	
$tpl->display("shopping_redgreen.html");
?>