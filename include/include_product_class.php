<?php
@header("Content-type: text/html; charset=utf-8");

if (is_file("configs.inc.php")){
	include("./configs.inc.php");
}elseif (is_file("../configs.inc.php")){
	include("../configs.inc.php");
}elseif (is_file("../../configs.inc.php")){
	include("../../configs.inc.php");
}elseif (is_file("../../../configs.inc.php")){
	include("../../../configs.inc.php");
}

if (intval($_GET['goods_id']) >0){
	$sql = "select bid from `{$INFO[DBPrefix]}goods` where gid='" . intval($_GET['goods_id']) . "'";
	$query  = $DB->query($sql);
	$Rs =  $DB->fetch_array($query);
	$_GET['bid'] = $Rs['bid'];
}

if (!is_object($FUNCTIONS_EX)){
//include_once (RootDocumentAdmin."/inc/function_ex.php");
// $FUNCTIONS_EX = new FUNCTIONS_EX;
}

//获得导航拦上的产品分类
$Sql_bclass    = "select bid,catname,pic1,pic2,top_id from `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid='" . intval($_GET['bid']) . "' limit 0,1 ";
$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
if ($num_bclass > 0){
	$Rs_bclass =  $DB->fetch_array($query_bclass);
	$top_id = $Rs_bclass['top_id'];
}
if ($top_id == 0){
	$id = intval($_GET['bid']);	
	$link = "product_class_detail.php";
	$link2 = "product_class_detail.php";
	$show_top_id = $id;
	$show_top = 1;
}else{
	$link = "product_class_detail.php";
	$link2 = "product_class_detail.php";
	$show_top = 1;
	$Sql_bclass    = "select bid,catname,pic1,pic2,top_id from `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid='" . intval($top_id) . "' limit 0,1 ";
	$query_bclass  = $DB->query($Sql_bclass);
	$num_bclass    = $DB->num_rows($query_bclass);
	if ($num_bclass > 0){
		$Rs_bclass =  $DB->fetch_array($query_bclass);
		$stop_id = $Rs_bclass['top_id'];
	}
	if ($stop_id == 0){
		$show_top_id = $top_id;
		$id = intval($_GET['bid']);		
	}else{
		$show_top_id = $stop_id;
		$id = intval($top_id);
		$color = 1;
	}
}
if (intval($_GET['bid'])==0){
	$_GET['bid'] = 0;
	$show_top_id = 0;
}
$Sql_bclass    = "select bid,catname,pic1,pic2,top_id from `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid='" . intval($show_top_id) . "' ";$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
if ($num_bclass > 0){
	$Rs_bclass =  $DB->fetch_array($query_bclass);
	$show_top_name = $Rs_bclass['catname'];
	$show_top_pic1 = $Rs_bclass['pic1'];
}
if (intval($_GET['bid'])==0){
	$Sql_bclass    = "select bid,catname,pic1,pic2,subject_id,subject_id2,url  from `{$INFO[DBPrefix]}bclass` where catiffb=1   order by catord  asc  ";
}else{
	$Sql_bclass    = "select bid,catname,pic1,pic2,subject_id,subject_id2,url  from `{$INFO[DBPrefix]}bclass` where catiffb=1  and top_id='" . intval($show_top_id) . "' order by catord  asc  ";
}

$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
$i=0;

while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	$ProductListAll[$i]['bid']     = $Rs_bclass['bid'];
	$ProductListAll[$i]['catname'] = $Rs_bclass['catname'];
	//if ($id==$Rs_bclass['bid']){
	$ProductListAll[$i]['pic1'] = $Rs_bclass['pic1'];
	$ProductListAll[$i]['pic2'] = $Rs_bclass['pic2'];
	$ProductListAll[$i]['subject_id'] = $Rs_bclass['subject_id'];
	$ProductListAll[$i]['subject_id2'] = $Rs_bclass['subject_id2'];
	$ProductListAll[$i]['url'] = $Rs_bclass['url'];
	//}
	$ProductListAll[$i]['link'] = $link;
	//if ($id==$Rs_bclass['bid']){
	$j=0;
	$Sql_bclass_2    = "select bid,catname,subject_id,subject_id2,manyunfei,url from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "' order by catord  asc  ";
	$query_bclass_2  = $DB->query($Sql_bclass_2);
	$num_bclass_2    = $DB->num_rows($query_bclass_2);
	while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
		$ProductListAll[$i]['sub'][$j]['bid']     = $Rs_bclass_2['bid'];
		$ProductListAll[$i]['sub'][$j]['subject_id']     = $Rs_bclass_2['subject_id'];
		$ProductListAll[$i]['sub'][$j]['subject_id2']     = $Rs_bclass_2['subject_id2'];
		$ProductListAll[$i]['sub'][$j]['catname'] = $Rs_bclass_2['catname'];
		$ProductListAll[$i]['sub'][$j]['url'] = $Rs_bclass_2['url'];
		if ($color ==1 && $_GET['bid']==$Rs_bclass_2['bid']){
			$ProductListAll[$i]['sub'][$j]['color']     = 1;	
		}
		$ProductListAll[$i]['sub'][$j]['link'] = $link2;		$ProductListAll[$i]['sub'][$j]['manyunfei'] = $Rs_bclass_2['manyunfei'];
		$j++;
	}
	//}
	$i++;
}


//print_r($ProductListAll);

$tpl->assign("ProductListAll",  $ProductListAll);
$tpl->assign("show_top",  $show_top);
$tpl->assign("show_top_id",  $show_top_id);
$tpl->assign("show_top_name",  $show_top_name);
$tpl->assign("show_top_pic1",  $show_top_pic1);
$tpl->assign("topid", $_GET['topid']);

$tpl->display("include_product_class.html");
?>

