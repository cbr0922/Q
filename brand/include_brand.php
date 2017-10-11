<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Brand_Pack.php");

$Sql = "select b.brand_id ,b.brandname,b.brandcontent,b.logopic,b.bdiffb from `{$INFO[DBPrefix]}brand` b where b.bdiffb=1  order by rand()  limit 0,5";

$Query = $DB->query($Sql);
$i=0;
while ($Result = $DB->fetch_array($Query)){
	$BrandArrayList[$i][brand_id]     =  $Result[brand_id];
	$BrandArrayList[$i][brandname]    =  $Result[brandname];
	$BrandArrayList[$i][brandcontent] =  $Result[brandcontent];
	$BrandArrayList[$i][logopic]      =  $Result[logopic];


	$BrandList_staticHtml[$i][Url] = $INFO[site_url]."/HTML_C/brand_list_".$BrandArrayList[$i]['brand_id']."_0.html";
	$BrandList_staticHtml[$i][brandname] = $BrandArrayList[$i]['brandname'];

	$i++;
}

/*
if ($INFO['staticState']=='open'){
	for ($i=0;$i<=10;$i++){
		$BrandList_staticHtml[$i][Url] = $INFO[site_url]."/HTML_C/brand_list_".$BrandArrayList[$i]['brand_id']."_0.html";
		$BrandList_staticHtml[$i][brandname] = $BrandArrayList[$i]['brandname'];
	}
	$tpl->assign("BrandList_staticHtml",$BrandList_staticHtml);
}
*/
$tpl->assign("BrandList_staticHtml",$BrandList_staticHtml);
$tpl->assign("BrandArrayList",$BrandArrayList);
$tpl->assign($Brand_Pack);
$tpl->display("include_brand.html");
?>
