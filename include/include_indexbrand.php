<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$bid = intval($_GET['bid']);
if ($bid>0){
	$where = " where b.classid='" . $bid . "' ";	
}

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

$Sql     = "select b.brand_id ,b.brandname,b.brandcontent,b.logopic,b.bdiffb from `{$INFO[DBPrefix]}brand` b where  b.bdiffb=1  order by b.viewcount desc,b.brand_id  desc limit 0,8";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i = 0;
if ($Num>0){
	while($ResultBrand = $DB->fetch_array($Query)){
		$brandArray[$i][brand_id]  = intval($ResultBrand['brand_id']);
		$brandArray[$i][brandname]  = trim($ResultBrand['brandname']);
		$brandArray[$i][brandcontent]  = $ResultBrand['brandcontent'];
		$brandArray[$i][logopic]  = $ResultBrand['logopic'];	
		$i++;
	}
}
//print_r($brandArray);
$tpl->assign("brandArray",$brandArray);

$tpl->display("include_indexbrand.html");
?>
