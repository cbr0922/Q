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
$Sql_bclass    = "select bid,catname from `{$INFO[DBPrefix]}bclass` where catiffb=1 and ifhome=1 order by catord  asc  ";
$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
$i=0;
$j=-20;
while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	$ProductListAll[$i]['bid']     = $Rs_bclass['bid'];
	$ProductListAll[$i]['catname'] = $Rs_bclass['catname'];



	$i++;
}




$tpl->assign("ProductListAll",  $ProductListAll);

$tpl->display("recommend_product_class.html");
?>

