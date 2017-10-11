<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
//装载翻页函数
include ("pagenav_stard.php");
//include_once "pagenav_stard.php";
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
include ("pagenav_ex.php");
//include_once "pagenav_ex.php";
$Build_nav = new NavFunction();


$Collection_list = array();
$tpl->assign("MyCollection_say",    $Order_Pack[MyCollection_say]);  // 我的商品收藏
$pagesize = 10;
$curpage = intval($_GET['page']);
if ($curpage == 0)
	$curpage = 1;
$f = $pagesize * ($curpage-1);
$l = $f + $pagesize;
$Sql = "select g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid,c.collection_id from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where  b.catiffb=1 and g.ifpub=1 and c.user_id=".intval($_SESSION['user_id'])." and g.storage>0 order by c.cidate desc ";

$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$pagecount = ceil($Num/$pagesize);
$Sql .= " limit " . $f . "," . $pagesize;
$Query = $DB->query($Sql);
$i = 0;
while($Rs=$DB->fetch_array($Query)){
	$Collection_list[$i]['gid'] = $Rs['gid'];
	$Collection_list[$i]['i'] = $i+1;
	$Collection_list[$i]['goodsname'] = $Rs['goodsname'];
	$Collection_list[$i]['pricedesc'] = $Rs['pricedesc'];
	$Collection_list[$i]['smallimg'] = $Rs['smallimg'];
	$Collection_list[$i]['cidate'] = date("Y-m-d H:s",$Rs['cidate']);
	$i++;
}
for($j=1;$j<=$pagecount;$j++){
	$page_array[$j-1]['page'] = $j;	
}
//print_r($page_array);
$tpl->assign("Collection_list",    $Collection_list);  // 我的商品收藏总表
$tpl->assign("pagecount",    $pagecount);
$tpl->assign("Num",    $Num);
$tpl->assign("curpage",    $curpage);
$tpl->assign("page_array",    $page_array);
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->display("ajax_collection.html");
?>