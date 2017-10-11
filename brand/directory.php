<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include_once ("../configs.inc.php");


$Sql     = "select * from `{$INFO[DBPrefix]}brand` b where b.bdiffb=1 order by brandname_en asc,orderby asc";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$brandArray = array();
$firstCharArray = array();
if ($Num>0){
	while($ResultBrand = $DB->fetch_array($Query)){
		$firstChar = substr($ResultBrand['brandname_en'],0,1);
		if($firstChar==""){
			$firstChar = "#";
			$ResultBrand['brandname_en'] = $ResultBrand['brandname'];
	    }else{
			if(!in_array($firstChar,$firstCharArray)){
				$firstCharArray[count($firstCharArray)] = $firstChar;
			}
		}
		$len = count($brandArray[$firstChar]);
		$brandArray[$firstChar][$len] = $ResultBrand;
	}
}
$resultArray = array();
foreach($firstCharArray as $key=>$value){
	//$i = 0;
	$resultArray[$key]['name'] = $value;
	foreach($brandArray as $key_b=>$value_b){
		if($value == $key_b){
			$resultArray[$key]['brand'] = $value_b;
			//$i++;
		}
	}
}
if($brandArray["#"]>0){
	$resultArray[$key+1]['name'] = "#";
	$resultArray[$key+1]['brand'] = $brandArray["#"];
}

//print_r($resultArray);
$tpl->assign("brandArray" , $brandArray);
$tpl->assign("firstCharArray" , $resultArray);
$tpl->assign($Brand_Pack);
$tpl->display("directory.html");

?>