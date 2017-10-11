<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include_once("PageNav.class_phone.php");


$tpl->assign("MyCollection_say",    $Order_Pack[MyCollection_say]);  // 我的商品收藏

$Sql = "select g.brand_id,g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid,c.collection_id from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where  b.catiffb=1 and g.ifpub=1 and c.user_id=".intval($_SESSION['user_id'])." order by c.cidate desc ";
//$Collection_list = $Build_nav->row_nav_return($DB,$Sql,1,' cellSpacing=0 cellPadding=0 width=99% align=center border=0 ',' vAlign=top width=96% ','p9v',8,4,'Collection_list','')		 ;
//print_r($Collection_list);
//$Sql = "select g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid,c.collection_id from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where  b.catiffb=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and c.user_id=".intval($user_id)." order by c.cidate desc ";
$PageNav    = new PageItem($Sql,12);
$Num        = $PageNav->iTotal;
if ($Num>0){
	$Query = $PageNav->ReadList();
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$goods_array[$i]['smallimg'] = $Rs['smallimg'];
		$goods_array[$i]['goodsname'] = $Rs['goodsname'];
		$goods_array[$i]['intro'] = $Rs['intro'];
		$goods_array[$i]['price'] = $Rs['price'];
		$goods_array[$i]['pricedesc'] = $Rs['pricedesc'];
		$goods_array[$i]['gid'] = $Rs['gid'];
		$goods_array[$i]['collection_id'] = $Rs['collection_id'];
		$goods_array[$i]['brand_id'] = $Rs['brand_id'];
		$br = $goods_array[$i]['brand_id'];

					
					$Sql1 = "select brandname,brand_id from `{$INFO[DBPrefix]}brand` where brand_id=".$br;
					$Query1   = $DB->query($Sql1);
					$j = 0;
					while ( $Result1 = $DB->fetch_array($Query1)){
				
					$goods_array[$i]['goods'][$j]['brand_id']    =  $Result1['brand_id'];
					$goods_array[$i]['goods'][$j]['brandname']    =  $Result1['brandname'];
					
					$j++;
					}
		
		$i++;
	}
	$Nav_banner = $PageNav->myPageItem();
}
$tpl->assign("Nav_banner",    $Nav_banner);  // 我的商品收藏总表
$tpl->assign("goods_array",     $goods_array);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("Collection_list.html");
?>
