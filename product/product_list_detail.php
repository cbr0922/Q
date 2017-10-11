<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

switch (trim($_GET['Type'])){
	case "NewProduct":
		$Nav_Product_class = $Good[Title_NewProduct_say];//"最新商品";
		 $Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and  g.ifpub=1  and g.ifbonus!=1 and g.ifjs!=1 order by g.gid  desc ";
		$SysNum_NewProduct = intval($INFO['MaxNewProductNum'])>0 ? intval($INFO['MaxNewProductNum']) : 10 ;
		break;
	case "Recommend":
		$Nav_Product_class = $Good[Title_TProduct_say];//"推荐商品";
		$Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1 and g.ifrecommend=1 and g.ifjs!=1  and g.ifbonus!=1 order by g.goodorder asc,g.idate desc ";
		break;
	case "Special":
		$Nav_Product_class = $Good[Title_SpeProduct_say];//"特价商品";
		$Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro ,g.ifalarm,g.storage,g.alarmnum from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1 and g.ifspecial=1  and g.ifjs!=1  and g.ifbonus!=1 order by g.goodorder asc,g.idate desc";
		break;
	case "Hot":
		$Nav_Product_class = $Good[Title_HotProduct_say];//"热卖商品";
		$Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid )  where  b.catiffb=1 and g.ifhot=1  and g.ifjs!=1 and g.ifbonus!=1  order by g.goodorder asc,g.idate desc ";
		break;

	default:
		$Nav_Product_class = $Good[Title_NewProduct_say];//"最新商品";
		$Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where b.catiffb=1 and g.ifpub=1 and g.ifjs!=1 and g.ifbonus!=1 order by g.goodorder asc,g.idate desc ";
		$SysNum_NewProduct = intval($INFO['MaxNewProductNum'])>0 ? intval($INFO['MaxNewProductNum']) : 10 ;
		break;

}
$tpl->assign("Nav_Product_class",   $Nav_Product_class ); //分类名称

/**
 * 装载翻页类，注意使用include_once函数
 */
include("PageNav.class.php");
$perPageNum   =  intval($INFO['MaxProductNumForList'])>0 ?  intval($INFO['MaxProductNumForList']) : 10 ;
if (trim($_GET['Type'])=="NewProduct"){
	$Limit      = $SysNum_NewProduct ;
	$pType      = "NewProduct";
}else{
	unset($Limit);
	unset($SysNum_NewProduct);
	unset($pType);
}

$PageNav    = new PageItem($Sql,$perPageNum);

if (trim($_GET['Type'])=="NewProduct"){
	$PageNav->iTotal =  $Num   =  $SysNum_NewProduct ;
}else{
	$Num             =  $PageNav->iTotal;
}


$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

if ($Num>0){
	$arrRecords = $PageNav->ReadList();
	if ($arrRecords>0){
	$i=0;
	$j=1;
	$Sql_level = "";
	$ProNav_array_level = array();

	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;

		$Sql_level   = "select m.m_price,u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[$i]['gid'];
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



		$ProNav_Rs[$i]['goodsname']  = $ProNav['goodsname']."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
		$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
		$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
		$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
		$ProNav_Rs[$i]['smallimg']   = $ProNav['smallimg'] ;
		$ProNav_Rs[$i]['intro']      = $ProNav['intro'];

		$tpl->assign("ProNav_gid".$j,         $ProNav_Rs[$i]['gid']);
		$tpl->assign("ProNav_goodsname".$j,   $ProNav_Rs[$i]['goodsname']);
		$tpl->assign("ProNav_price".$j,       $ProNav_Rs[$i]['price']);
		$tpl->assign("ProNav_pricedesc".$j,   $ProNav_Rs[$i]['pricedesc']);
		$tpl->assign("ProNav_bn".$j,          $ProNav_Rs[$i]['bn']);
		$tpl->assign("ProNav_img".$j,         $ProNav_Rs[$i]['smallimg']);
		$tpl->assign("ProNav_intro".$j,       $ProNav_Rs[$i]['intro']);




		if ($ProNav_Rs[$i]['attr']!=""){
			$attrI        =  $ProNav_Rs[$i]['attr'];
			$goods_attrI  =  $ProNav_Rs[$i]['goodattr'];
			$Attr         =  explode(',',$attrI);
			$Goods_Attr   =  explode(',',$goods_attrI);
			$Attr_num=  count($Attr);
		}else{
			$Attr_num=0;
		}
		if (is_array($Attr) && intval($Attr_num)>0 ){
			$AttrArray = array();
			$ProductArray = array();
			for($k=0;$k<$Attr_num;$k++){
				$tpl->assign("ProductAttrArray".$j."_attriName_".$k,     $Attr[$k]);    //循环多属性部分数组
				$tpl->assign("ProductAttrArray".$j."_attriValue_".$k,    $Goods_Attr[$k]);    //循环多属性部分数组
			}
		}

		$i++;
		$j++;
	}
	}

}
$tpl->assign($Good);
$tpl->display("product_list_detail.html");

?>