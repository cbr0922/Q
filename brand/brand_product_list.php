<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include_once ("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
include("PageNav.class.php");

$HaveBrandid =  intval($_GET[BrandID]) > 0 ? " and br.brand_id='".intval($_GET[BrandID])."'  " : "" ;

$Sql     = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,br.brand_id,br.brandname,br.brandcontent,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass`  b on (g.bid=b.bid ) inner join   `{$INFO[DBPrefix]}brand`  br on (br.brand_id=g.brand_id)  where  b.catiffb=1 and g.ifpub=1 and g.ifjs!=1 and g.ifbonus!=1 ".$HaveBrandid." order by g.goodorder asc,g.idate desc ";
$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;

if ($Num>0){
$i=0;
$j=1;
$Sql_level = "";
$ProNav_array_level = array();

while ( $ProNav = $DB->fetch_array($arrRecords)){
	$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
	$ProNav_Rs[$i]['goodsname']  = $ProNav['goodsname']."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
	$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
	$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
	$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
	$ProNav_Rs[$i]['smallimg']   = $ProNav['smallimg'] ;
	$ProNav_Rs[$i]['intro']      = $ProNav['intro'];


	


	$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[0]['gid'];
	$Query_level = $DB->query($Sql_level);
	$v=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$ProNav_array_level[$v]['level_name'] = $Result_level['level_name'];
				$ProNav_array_level[$v]['m_price']    = $Result_level['m_price'];
				$v++;
			}
		}
		$tpl->assign("ProNav_array_level".$j, $ProNav_array_level);       //商品会员价格数组
		$tpl->assign("ProNav_gid".$j,  $ProNav_Rs[$i]['gid']); //商品ID
		$tpl->assign("ProNav_goodsname".$j,   $ProNav_Rs[$i]['goodsname']); //商品名称
		$tpl->assign("ProNav_price".$j,       $ProNav_Rs[$i]['price']);     //商品价格
		$tpl->assign("ProNav_pricedesc".$j,   $ProNav_Rs[$i]['pricedesc']); //商品价格
		$tpl->assign("ProNav_bn".$j,          $ProNav_Rs[$i]['bn']);        //商品编号
		$tpl->assign("ProNav_img".$j,         $ProNav_Rs[$i]['smallimg']);  //商品图片
		$tpl->assign("ProNav_intro".$j,       $ProNav_Rs[$i]['intro']);     //商品内容




	$i++;
	$j++;
}
	$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条


}
$DB->query("update `{$INFO[DBPrefix]}brand` set viewcount=viewcount+1 where brand_id=".intval($_GET[BrandID]));
$HaveBrandid =  intval($_GET[BrandID]) > 0 ? " where brand_id='".intval($_GET[BrandID])."'  " : "" ;

$Sql_level   = "select  *  from `{$INFO[DBPrefix]}brand` " . $HaveBrandid;
$Query_level = $DB->query($Sql_level);
$Result_level=$DB->fetch_array($Query_level);
$tpl->assign("The_brand_id",       $Result_level['brand_id']);
$tpl->assign("The_brandname",      $Result_level['brandname']);
$tpl->assign("The_brandcontent",   $Result_level['brandcontent']);
$tpl->assign("The_logopic",        $Result_level['logopic']);
$tpl->assign("Meta_desc",   $Result_level['meta_des']);
$tpl->assign("Meta_keyword",   $Result_level['meta_key']);
$tpl->assign($Good);
$tpl->display("brand_product_list.html");

?>