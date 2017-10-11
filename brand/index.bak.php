<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include_once ("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Brand_Pack.php";
include("PageNav.class.php");

$Sql     = "select b.brand_id ,b.brandname,b.brandcontent,b.smalllogo from `{$INFO[DBPrefix]}brand` b where b.bdiffb=1 order by orderby  asc,viewcount desc ";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$hang = ceil($Num/3);
	for($i=0;$i<$hang;$i++){
		for($j=0;$j<3;$j++){
		$ResultBrand = $DB->fetch_array($Query);
			if (intval($ResultBrand['brand_id'])>0){
				$brandArray[$i][brand][$j][brand_id]  = intval($ResultBrand['brand_id']);
				$brandArray[$i][brand][$j][brandname]  = trim($ResultBrand['brandname']);
				$brandArray[$i][brand][$j][brandcontent]  = $ResultBrand['brandcontent'];
				$brandArray[$i][brand][$j][logopic]  = $ResultBrand['smalllogo'];		
				
			}
		}
	}
}

$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
//print_r($brandArray);
$tpl->assign("brandArray" , $brandArray);
$tpl->assign($Brand_Pack);
$tpl->display("brand_index.html");

?>