<?php
@header("Content-type: text/html; charset=utf-8");

include( dirname(__FILE__)."/"."../configs.inc.php" );
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Search_Pack.php");
if (intval($_GET['goods_id']) >0){
	$sql = "select bid from `{$INFO[DBPrefix]}groupdetail` where gdid='" . intval($_GET['goods_id']) . "'";
	$query  = $DB->query($sql);
	$Rs =  $DB->fetch_array($query);
	$_GET['bid'] = $Rs['bid'];
}
//if (intval($_GET['bid'])==0)
	//$_GET['bid'] = 1;
if (!is_object($FUNCTIONS_EX)){
//include_once ("function_ex.php");
// $FUNCTIONS_EX = new FUNCTIONS_EX;
}

//获得导航拦上的产品分类
$Sql_bclass    = "select bid,catname,pic1,pic2,top_id from `{$INFO[DBPrefix]}groupclass` where catiffb=1 and bid='" . intval($_GET['bid']) . "' limit 0,1 ";
$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
if ($num_bclass > 0){
	$Rs_bclass =  $DB->fetch_array($query_bclass);
	$top_id = $Rs_bclass['top_id'];
}
if ($top_id == 0){
	$show_top_id = intval($_GET['bid']);	
	$id = intval($_GET['bid']);	
	
}else{
	/*
	if ($top_id==3){
		$link = "product_class_second_groupon.php";
		$link2 = "product_class_second_groupon.php";
	}else{
		$link = "product_class_second_groupon2.php";
		$link2 = "product_class_detail_groupon3.php";	
	}
	*/
	$show_top = 1;
	$Sql_bclass    = "select bid,catname,pic1,pic2,top_id from `{$INFO[DBPrefix]}groupclass` where catiffb=1 and bid='" . intval($top_id) . "' limit 0,1 ";
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
if ($show_top_id==3){
		$link = "product_class_detail_groupon.php";
		$link2 = "product_class_detail_groupon.php";
	}else{
		$link = "product_class_detail_groupon2.php";
		$link2 = "product_class_detail_groupon2.php";	
	}
$Sql_bclass    = "select bid,catname,pic1,pic2,top_id from `{$INFO[DBPrefix]}groupclass` where catiffb=1 and bid='" . intval($show_top_id) . "' ";$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
if ($num_bclass > 0){
	$Rs_bclass =  $DB->fetch_array($query_bclass);
	$show_top_name = $Rs_bclass['catname'];
	$show_top_pic1 = $Rs_bclass['pic1'];
}
$Sql_bclass    = "select bid,catname,pic1,pic2,subject_id from `{$INFO[DBPrefix]}groupclass` where catiffb=1 and top_id='" . intval($show_top_id) . "' order by catord,bid  asc    ";
$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
$i=0;

while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	$ProductListAll[$i]['bid']     = $Rs_bclass['bid'];
	$ProductListAll[$i]['catname'] = $Rs_bclass['catname'];
	//if ($id==$Rs_bclass['bid']){
	$ProductListAll[$i]['pic1'] = $Rs_bclass['pic1'];
	$ProductListAll[$i]['pic2'] = $Rs_bclass['pic2'];
	//}
	$ProductListAll[$i]['link'] = $link;
	if ($id==$Rs_bclass['bid'] || $show_top_id==$Rs_bclass['bid']){
		$j=0;
		$Sql_bclass_2    = "select bid,catname,pic1,pic2,subject_id from `{$INFO[DBPrefix]}groupclass` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "' order by catord,bid  asc  ";
		$query_bclass_2  = $DB->query($Sql_bclass_2);
		$num_bclass_2    = $DB->num_rows($query_bclass_2);
		while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
			$ProductListAll[$i]['sub'][$j]['bid']     = $Rs_bclass_2['bid'];
			$ProductListAll[$i]['sub'][$j]['catname'] = $Rs_bclass_2['catname'];
			$ProductListAll[$i]['sub'][$j]['pic1'] = $Rs_bclass_2['pic1'];
			$ProductListAll[$i]['sub'][$j]['pic2'] = $Rs_bclass_2['pic2'];
			if($show_top_id==1 || $show_top_id==2)
				$ProductListAll[$i]['sub'][$j]['link'] = "product_class_detail_groupon2.php";
			else
				$ProductListAll[$i]['sub'][$j]['link'] = "product_class_detail_groupon.php";
			if($Rs_bclass_2['subject_id']>0)
				$ProductListAll[$i]['sub'][$j]['subject_id'] = $Rs_bclass_2['subject_id'];
				
			if ($id==$Rs_bclass_2['bid']){
				$z=0;
				$Sql_bclass_3    = "select bid,catname,pic1,pic2,subject_id from `{$INFO[DBPrefix]}groupclass` where catiffb=1 and top_id='" . $Rs_bclass_2['bid'] . "' order by catord,bid  asc  ";
				$query_bclass_3  = $DB->query($Sql_bclass_3);
				$num_bclass_3    = $DB->num_rows($query_bclass_3);
				while($Rs_bclass_3 =  $DB->fetch_array($query_bclass_3)){
					$ProductListAll[$i]['sub'][$j]['sub'][$z]['bid']     = $Rs_bclass_3['bid'];
					$ProductListAll[$i]['sub'][$j]['sub'][$z]['catname'] = $Rs_bclass_3['catname'];
					$ProductListAll[$i]['sub'][$j]['sub'][$z]['pic1'] = $Rs_bclass_3['pic1'];
					$ProductListAll[$i]['sub'][$j]['sub'][$z]['pic2'] = $Rs_bclass_3['pic2'];
					if ($color ==1 && $_GET['bid']==$Rs_bclass_3['bid']){
						$ProductListAll[$i]['sub'][$j]['sub'][$z]['color']     = 1;
						
					}
					if ($_GET['bid']==$Rs_bclass_3['bid'])
						$top2id = $Rs_bclass_2['bid'];
					if($show_top_id==1 || $show_top_id==2)
						$ProductListAll[$i]['sub'][$j]['sub'][$z]['link'] = "product_class_detail_groupon2.php";
					else
						$ProductListAll[$i]['sub'][$j]['sub'][$z]['link'] = "product_class_detail_groupon.php";
					if($Rs_bclass_3['subject_id']>0)
						$ProductListAll[$i]['sub'][$j]['sub'][$z]['subject_id'] = $Rs_bclass_3['subject_id'];
					$j++;
				}
			}
			if ($color ==1 && $_GET['bid']==$Rs_bclass_2['bid']){
				$ProductListAll[$i]['sub'][$j]['color']     = 1;
				
			}
			if ($_GET['bid']==$Rs_bclass_2['bid'])
				$top2id = $Rs_bclass['bid'];
			//$ProductListAll[$i]['sub'][$j]['link'] = $link2;
			$j++;
		}
	}
	$i++;
}


//print_r($ProductListAll);
$tpl->assign("show2_top_id",  $top2id);
$tpl->assign("ProductListAll",  $ProductListAll);
$tpl->assign("show_top",  $show_top);
$tpl->assign("show_top_id",  $show_top_id);
$tpl->assign("show_top_name",  $show_top_name);
$tpl->assign("show_top_pic1",  $show_top_pic1);
$tpl->assign("topid", $_GET['topid']);

$tpl->display("include_product_class_groupon.html");
?>
