<?php
@header("Content-type: text/html; charset=utf-8");

include( dirname(__FILE__)."/"."../configs.inc.php" );
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Search_Pack.php");

if (!is_object($FUNCTIONS_EX)){
//include_once ("function_ex.php");
// $FUNCTIONS_EX = new FUNCTIONS_EX;
}

//获得导航拦上的产品分类
$Sql_bclass    = "select bid,catname from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id=0 order by catord  asc  ";
$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
$i=0;
$j=-20;
while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	$ProductListAll[$i]['bid']     = $Rs_bclass['bid'];
	$ProductListAll[$i]['catname'] = $Rs_bclass['catname'];
	$ReturnCCclassArray  = $FUNCTIONS->Getcclass($Rs_bclass['bid']);
	$ProductListcc[$i]['SubClassList'] = $ReturnCCclassArray ;
	$ProductListcc[$i]['Open'] =  $ReturnCCclassArray!="" ?  1 : 0  ;
	$ProductListcc[$i]['High'] =  $j =  $j+20;



	$i++;
}



$tpl->assign("num_bclass", $num_bclass); //商品分类总数
$tpl->assign("ProductListAll", $ProductListAll); //商品分类
$tpl->assign("ProductListcc",  $ProductListcc); //商品小分类
$tpl->assign($Search_Pack);


if ($INFO['staticState']=='open'){
	for ($i=0;$i<$num_bclass;$i++){
        $ProductListB_staticHtml[$i][Url]     =  $INFO[site_url]."/HTML_C/product_class_".$ProductListAll[$i]['bid']."_0.html"; //静态页面
        $ProductListB_staticHtml[$i][catname] =  $ProductListAll[$i]['catname'];
	}
}

$tpl->assign("ProductListB_staticHtml",  $ProductListB_staticHtml);
$tpl->assign("staticState",  $INFO['staticState']);

$tpl->display("search_style_one.html");
?>

