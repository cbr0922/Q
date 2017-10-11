<?php
/*
$menutype = "category";//all左側菜單顯示所有分類級別，category只顯示當前館別分類（即從第二級別分類開始顯示）
echo $bid;
echo $top_bid = $PRODUCT->getTopBid($bid);   //得到對應的第一級分類ID
if($menutype == "all")
	$showbid = 0;
else
	$showbid = $top_bid;
$class_array = $PRODUCT->getProductClass($showbid);  //分類，最多顯示3層
$tpl->assign("ProductListAll",  $class_array);
*/
@header("Content-type: text/html; charset=utf-8");

if (is_file("configs.inc.php")){
	include("./configs.inc.php");
}elseif (is_file("../configs.inc.php")){
	include("../configs.inc.php");
}

include ("global.php");
 $Sql_bclass    = "select * from `{$INFO[DBPrefix]}brand` order by orderby  asc  ";
$query_bclass  = $DB->query($Sql_bclass);
 $num_bclass    = $DB->num_rows($query_bclass);
$i=0;

while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	$ProductListAll[$i]['bid']     = $Rs_bclass['brand_id'];
	$ProductListAll[$i]['catname'] = $Rs_bclass['brandname'];
	$ProductListAll[$i]['pic1'] = $Rs_bclass['brandpic'];
	$j=0;
	/*
	$gid_array = array();
	$extendsql = "select gc.gid from `{$INFO[DBPrefix]}goods_class` as gc where gc.bid ='" . intval($Rs_bclass['bid']) . "'";
	$extend_query  = $DB->query($extendsql);
	$ei = 0;
	while($extend_rs = $DB->fetch_array($extend_query)){
		$gid_array[$ei] = $extend_rs['gid'];
		$ei++;
	}
	if (is_array($gid_array) && count($gid_array)>0){
		$gid_str = implode(",",$gid_array);
		$gid_sql_str = " or gid in (" . $gid_str . ")";
	}
	*/
	$Sql_bclass_2    = "select * from `{$INFO[DBPrefix]}goods` where brand_id='" . $Rs_bclass['brand_id'] . "'  and ifpub='1' and ifbonus!='1' and ifxy!=1 and ifchange!=1 and ifpresent!=1 and ifchange!=1 order by goodorder asc,idate desc  ";
	$query_bclass_2  = $DB->query($Sql_bclass_2);
	$num_bclass_2    = $DB->num_rows($query_bclass_2);
	while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
		$ProductListAll[$i]['sub'][$j]['gid']     = $Rs_bclass_2['gid'];
		$ProductListAll[$i]['sub'][$j]['goodsname'] = $Rs_bclass_2['goodsname'];
		$j++;
	}
	$i++;
}
//print_r($ProductListAll);
$tpl->assign("ProductListAll",$ProductListAll);
$tpl->display("include_product_class1.html");
?>

