<?php
@header("Content-type: text/html; charset=utf-8");

if (is_file("configs.inc.php")){
	include("./configs.inc.php");
}elseif (is_file("../configs.inc.php")){
	include("../configs.inc.php");
}

include (RootDocumentAdmin."/inc/global.php");
include (RootDocument."/language/".$INFO['IS']."/Search_Pack.php");

if (!is_object($FUNCTIONS_EX)){
//include_once (RootDocumentAdmin."/inc/function_ex.php");
// $FUNCTIONS_EX = new FUNCTIONS_EX;
}

//获得导航拦上的产品分类
$Sql_bclass    = "select bid,catname,pic1,pic2 from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id=0 order by catord  asc  ";
$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
$i=0;

while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	$ProductListAll[$i]['bid']     = $Rs_bclass['bid'];
	$ProductListAll[$i]['catname'] = $Rs_bclass['catname'];
	$ProductListAll[$i]['pic1'] = $Rs_bclass['pic1'];
	$ProductListAll[$i]['pic2'] = $Rs_bclass['pic2'];
	$j=0;
	$Sql_bclass_2    = "select bid,catname from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "' order by catord  asc  ";
	$query_bclass_2  = $DB->query($Sql_bclass_2);
	$num_bclass_2    = $DB->num_rows($query_bclass_2);
	while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
		$ProductListAll[$i]['sub'][$j]['bid']     = $Rs_bclass_2['bid'];
		$ProductListAll[$i]['sub'][$j]['catname'] = $Rs_bclass_2['catname'];
		$j++;
	}
	$i++;
}



//print_r($ProductListAll);
$tpl->assign("ProductListAll",  $ProductListAll);

$tpl->display("include_product_classS.html");
?>

