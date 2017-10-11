<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Search_Pack.php");



//获得产品分类中的大类
//include "Char.class.php";
//$Char_class  = new Char_class;
//include RootDocumentShare."/cache/ProductclassIndex_show.php";

//$ProductListAll = $Char_class->get_page_select_forsearch($Search_Pack[AllClass],"bid",intval($_GET['bid'])," onchange='changecat()'");

$goodsclass = "<select name='bid'    class=\"trans-input\">";
$Sql_bclass    = "select bid,catname,pic1,pic2 from `{$INFO[DBPrefix]}shopbclass` where catiffb=1 and top_id=0 order by catord  asc  ";
$query_bclass  = $DB->query($Sql_bclass);
$num_bclass    = $DB->num_rows($query_bclass);
while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	$goodsclass .= "<option value='" . $Rs_bclass['bid'] . "'>├─" . $Rs_bclass['catname'] . "</option>";
	$Sql_bclass_2    = "select bid,catname from `{$INFO[DBPrefix]}shopbclass` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "' order by catord  asc  ";
	$query_bclass_2  = $DB->query($Sql_bclass_2);
	$num_bclass_2    = $DB->num_rows($query_bclass_2);
	while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
		$goodsclass .= "<option value='" . $Rs_bclass_2['bid'] . "'>│├─" . $Rs_bclass_2['catname'] . "</option>";
	}
}
$goodsclass .= "</select>";
$tpl->assign("ProductListAll", $goodsclass); //商品分类列表

$Sql_add  =  !empty($_GET['bid']) && intval($_GET['bid'])!=0 ? " where bid=".intval($_GET['bid'])." " : "" ;


$Sql    = "select distinct(b.brandname),b.brand_id from `{$INFO[DBPrefix]}brand` b inner join `{$INFO[DBPrefix]}goods` g    on (g.brand_id=b.brand_id)  ".$Sql_add;
$Query  = $DB->query($Sql);
$i=0;
while ($Rs = $DB->fetch_array($Query)){
	$Brand[$i]['brandname'] = $Rs['brandname'];
	$Brand[$i]['brand_id'] = $Rs['brand_id'];
	$i++;
}
/**
* array_unique -- 移除数组中重复的值
*/
/*if (is_array($Brand)){
	$Brand = array_unique($Brand);
}*/
$tpl->assign("Brand", $Brand); //商品品牌列表
$tpl->assign($Search_Pack);
$tpl->display("shop_adv_search.html");
?>