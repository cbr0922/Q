<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");



//轮播变量
$Sql_top = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.middleimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.js_begtime,g.js_endtime,g.ifjs from `{$INFO[DBPrefix]}goods_tag` gt inner join `{$INFO[DBPrefix]}goods` g on ( g.gid=gt.gid ) where gt.tagid='" . intval($_GET['tagid']) . "' and g.ifpub='1' and g.ifrecommend='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifpresent!=1 order by g.goodorder asc,g.idate desc   limit 0,2 ";
$Query_top  = $DB->query($Sql_top);

$i=0;
$j=1;
$Sql_level = "";
$ProTop_array_level = array();

while ( $Rs_top = $DB->fetch_array($Query_top)){
	$ProTop_Rs[$i]['gid']        = intval($Rs_top['gid']) ;
	$ProTop_Rs[$i]['goodsname']  = trim($Rs_top['goodsname'])."".$FUNCTIONS->Storage($Rs_top['ifalarm'],$Rs_top['storage'],$Rs_top['alarmnum']);
	$ProTop_Rs[$i]['price']      = $Rs_top['price'] ;
	$ProTop_Rs[$i]['bn']         = $Rs_top['bn'] ;
	$ProTop_Rs[$i]['smallimg']   = trim($Rs_top['smallimg']) ;
	$ProTop_Rs[$i]['middleimg']  = trim($Rs_top['middleimg']) ;
	$ProTop_Rs[$i]['intro']      = nl2br($Rs_top['intro']);
	$ProTop_Rs[$i]['pricedesc']  = trim($Rs_top['pricedesc']) ;
	



	/*
	 商品規格
	 */
	 $Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($Rs_top['gid']) . "' order by detail_id desc ";
	
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$k = 0;
	while ( $Detail_Rs = $DB->fetch_array($Query)){
		$ProTop_Rs[$i]['detail'][$k]['detail_name'] = $Detail_Rs['detail_name'];
		
		//产品价格体系
	
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".intval($ProTop_Rs['gid']) . " and m.m_detail_id='" . intval($Detail_Rs['detail_id']) . "' and u.level_name='" . $_SESSION['user_level'] . "'";
		$Query_level = $DB->query($Sql_level);
		$Num_level =   $DB->num_rows($Query_level);
		if ($Num_level>0){
					$ProTop_Rs[$i]['detail'][$k][memberprice] =   $Result_level['m_price'];
		}else{
			$ProTop_Rs[$i]['detail'][$k][memberprice] =   $Detail_Rs['detail_pricedes'];
		}
		
		$k++;
	}

	$tpl->assign("ProTop_gid".$j,  intval($ProTop_Rs[$i]['gid'])); //轮播商品ID

	$tpl->assign("ProTop_goodsname".$j,   $ProTop_Rs[$i]['goodsname']); //轮播名称
	$tpl->assign("ProTop_pricedesc".$j,   $ProTop_Rs[$i]['pricedesc']); //轮播特别优惠价格
	$tpl->assign("ProTop_price".$j,       $ProTop_Rs[$i]['price']);     //轮播价格
	$tpl->assign("ProTop_bn".$j,          $ProTop_Rs[$i]['bn']);        //轮播编号
	$tpl->assign("ProTop_img".$j,         $ProTop_Rs[$i]['middleimg']); //轮播图片
	$tpl->assign("ProTop_intro".$j,       $ProTop_Rs[$i]['intro']);     //轮播内容
	$tpl->assign("ProTop_detail".$j,       $ProTop_Rs[$i]['detail']);     //轮播内容

	$i++;
	$j++;
}



/**
 * 下边是抛出的产品信息列表
 */
include("PageNav.class.php");
$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods_tag` gt inner join `{$INFO[DBPrefix]}goods` g on ( g.gid=gt.gid ) where gt.tagid='" . intval($_GET['tagid']) . "' and g.ifpub='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifpresent!=1   order by g.goodorder asc,g.idate desc  ";
$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$Num        = $PageNav->iTotal;

$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

if ($Num>0){
	$arrRecords = $PageNav->ReadList();
	$i=0;
	$j=1;
	$Sql_level = "";
	$ProNav_array_level = array();

	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
		$ProNav_Rs[$i]['goodsname']  = trim($ProNav['goodsname'])."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
		$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
		$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
		$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
		$ProNav_Rs[$i]['smallimg']   = trim($ProNav['smallimg']) ;
		$ProNav_Rs[$i]['intro']      = nl2br($ProNav['intro']);

		
		/*
	 商品規格
	 */
	 $Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($ProNav['gid']) . "' order by detail_id desc ";
	
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$k = 0;
	while ( $Detail_Rs = $DB->fetch_array($Query)){
		$ProNav_Rs[$i]['detail'][$k]['detail_name'] = $Detail_Rs['detail_name'];
		
		//产品价格体系
	
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".intval($ProNav['gid']) . " and m.m_detail_id='" . intval($Detail_Rs['detail_id']) . "' and u.level_name='" . $_SESSION['user_level'] . "'";
		$Query_level = $DB->query($Sql_level);
		$Num_level =   $DB->num_rows($Query_level);
		if ($Num_level>0){
					$ProNav_Rs[$i]['detail'][$k][memberprice] =   $Result_level['m_price'];
		}else{
			$ProNav_Rs[$i]['detail'][$k][memberprice] =   $Detail_Rs['detail_pricedes'];
		}
		
		$k++;
	}

		$tpl->assign("ProNav_gid".$j,         $ProNav_Rs[$i]['gid']); //商品ID
		$tpl->assign("ProNav_detail".$j,         $ProNav_Rs[$i]['detail']); //商品ID
		$tpl->assign("ProNav_goodsname".$j,   $ProNav_Rs[$i]['goodsname']); //商品一名称
		$tpl->assign("ProNav_price".$j,       $ProNav_Rs[$i]['price']);     //商品一价格
		$tpl->assign("ProNav_pricedesc".$j,   $ProNav_Rs[$i]['pricedesc']); //商品一价格
		$tpl->assign("ProNav_bn".$j,          $ProNav_Rs[$i]['bn']);        //商品一编号
		$tpl->assign("ProNav_img".$j,         $ProNav_Rs[$i]['smallimg']);  //商品一图片
		$tpl->assign("ProNav_intro".$j,       $ProNav_Rs[$i]['intro']);     //商品一内容


		$i++;
		$j++;
	}

}
$tpl->assign($Good);
$tpl->display("product_tag.html");
?>