<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Search_Pack.php");



//获得产品分类中的大类
//include "Char.class.php";
//$Char_class  = new Char_class;
include RootDocumentShare."/cache/GroupclassIndex_show.php";

$ProductListAll = $Char_class->get_page_select_forsearch($Search_Pack[AllClass],"bid",intval($_GET['bid'])," onchange='changecat()'");
$tpl->assign("ProductListAll", $ProductListAll); //商品分类列表

$Sql_add  =  !empty($_GET['bid']) && intval($_GET['bid'])!=0 ? " where bid=".intval($_GET['bid'])." " : "" ;


/**
* array_unique -- 移除数组中重复的值
*/
/*if (is_array($Brand)){
	$Brand = array_unique($Brand);
}*/
$tpl->assign("Brand", $Brand); //商品品牌列表
$tpl->assign($Search_Pack);
$tpl->display("adv_search_group.html");
?>