<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");


$bid  = $_GET['bid'];


if (intval($bid)>0){
	
	
	$Query = $DB->query("select catname,bid,catcontent,top_id from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$Bname      =  $Result['catname'];
		$Bcontent   =  $Result['catcontent'];
		$top_id   =  $Result['top_id'];
		$firstbid   =  $Result['bid'];
		if ($top_id==0){
			$tpl->assign("Bname",     $Bname);     //产品大类名称
			$tpl->assign("Bid",     $firstbid);     //产品大类名称
			$tpl->assign("Bcontent",  $Bcontent);  //产品HTML编辑器的内容
		}
	}
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($bid);
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	
	foreach ($Array_class as $k=>$v){
		$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
		$Add2 .= trim($v)!="" && intval($v)>0 ? " or gc.bid=".$v." " : "";
	}
	//$Add = substr($Add,3,strlen($Add));
	
	$Query   = $DB->query("select bid from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid=".intval($bid)." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ( $Num==0 ){ //如果不存在资料
		$FUNCTIONS->header_location("index.php");
	}
	$DB->free_result($Query);
	
	
	$gid_array = array();
	$extendsql = "select gc.gid from `{$INFO[DBPrefix]}goods_class` as gc where gc.bid ='" . intval($bid) . "' " . $Add2 . "";
	$extend_query  = $DB->query($extendsql);
	$ei = 0;
	while($extend_rs = $DB->fetch_array($extend_query)){
		$gid_array[$ei] = $extend_rs['gid'];
		$ei++;
	}
	if (is_array($gid_array) && count($gid_array)>0){
		$gid_str = implode(",",$gid_array);
		$gid_sql_str = " or g.gid in (" . $gid_str . ")";
	}
}





//轮播变量
$Sql_top = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.middleimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.salename_color from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) inner join `{$INFO[DBPrefix]}attributegoods` as ag on ag.gid=g.gid where b.catiffb='1' and g.ifpub='1' and g.ifrecommend='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 ";
if (intval($bid)>0){
	$Sql_top .= " and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ") ";
}
$Sql_top .= " and ag.valueid='" . intval($_GET['valueid']) . "'  order by g.goodorder asc,g.idate desc   limit 0,2 ";
$Query_top  = $DB->query($Sql_top);

$i=0;
$j=1;
$Sql_level = "";
$ProTop_array_level = array();

while ( $Rs_top = $DB->fetch_array($Query_top)){
	$ProTop_Rs[$i]['gid']        = intval($Rs_top['gid']) ;
	//$ProTop_Rs[$i]['sale_name']        = trim($Rs_top['sale_name']) ;
	$ProTop_Rs[$i][sale_name] = $Rs_top['salename_color']==""?$Rs_top['sale_name']:"<font color='" . $Rs_top['salename_color'] . "'>" . $Rs_top['sale_name'] . "</font>";
	$ProTop_Rs[$i]['goodsname']  = trim($Rs_top['goodsname'])."".$FUNCTIONS->Storage($Rs_top['ifalarm'],$Rs_top['storage'],$Rs_top['alarmnum']);
	$ProTop_Rs[$i]['price']      = $Rs_top['price'] ;
	$ProTop_Rs[$i]['bn']         = $Rs_top['bn'] ;
	$ProTop_Rs[$i]['smallimg']   = trim($Rs_top['smallimg']) ;
	$ProTop_Rs[$i]['middleimg']  = trim($Rs_top['middleimg']) ;
	$ProTop_Rs[$i]['intro']      = nl2br($Rs_top['intro']);
	$ProTop_Rs[$i]['pricedesc']  = trim($Rs_top['pricedesc']) ;




	$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id='".$ProTop_Rs[$i]['gid']."'";
	$Query_level = $DB->query($Sql_level);
	$v=0;
	$ProTop_array_level = array();
	while ($Result_level=$DB->fetch_array($Query_level)){
		if (intval($Result_level['m_price'])!=0){
			$ProTop_array_level[$v]['level_name'] = $Result_level['level_name'];
			$ProTop_array_level[$v]['m_price']    = $Result_level['m_price'];
			$v++;
		}
	}

	$tpl->assign("ProTop_array_level".$j, $ProTop_array_level);       //商品会员价格数组
    $tpl->assign("ProTop_salename".$j,   $ProTop_Rs[$i]['sale_name']); //轮播名称
	$tpl->assign("ProTop_gid".$j,  intval($ProTop_Rs[$i]['gid'])); //轮播商品ID

	$tpl->assign("ProTop_goodsname".$j,   $ProTop_Rs[$i]['goodsname']); //轮播名称
	$tpl->assign("ProTop_pricedesc".$j,   $ProTop_Rs[$i]['pricedesc']); //轮播特别优惠价格
	$tpl->assign("ProTop_price".$j,       $ProTop_Rs[$i]['price']);     //轮播价格
	$tpl->assign("ProTop_bn".$j,          $ProTop_Rs[$i]['bn']);        //轮播编号
	$tpl->assign("ProTop_img".$j,         $ProTop_Rs[$i]['middleimg']); //轮播图片
	$tpl->assign("ProTop_intro".$j,       $ProTop_Rs[$i]['intro']);     //轮播内容


	$i++;
	$j++;
}



/**
 * 下边是抛出的产品信息列表
 */
include("PageNav.class.php");
$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.sale_name,g.salename_color  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid )  inner join `{$INFO[DBPrefix]}attributegoods` as ag on ag.gid=g.gid  where b.catiffb='1' and g.ifpub='1' and  g.ifjs!='1' and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1  ";
if (intval($bid)>0){
	$Sql .= " and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ") ";
}
$Sql .= "  and ag.valueid='" . intval($_GET['valueid']) . "'  order by g.goodorder asc,g.idate desc  ";
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
		//$ProNav_Rs[$i]['sale_name']        = trim($ProNav['sale_name']) ;
		$ProNav_Rs[$i][sale_name] = $ProNav['salename_color']==""?$ProNav['sale_name']:"<font color='" . $ProNav['salename_color'] . "'>" . $ProNav['sale_name'] . "</font>";

		$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[$i]['gid'];
		$Query_level = $DB->query($Sql_level);
		$v=0;
		$ProNav_array_level = array();
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$ProNav_array_level[$v]['level_name'] = $Result_level['level_name'];
				$ProNav_array_level[$v]['m_price']    = $Result_level['m_price'];
				$v++;
			}
		}
		$tpl->assign("ProNav_array_level".$j, $ProNav_array_level);       //商品一会员价格数组

		$tpl->assign("ProNav_gid".$j,         $ProNav_Rs[$i]['gid']); //商品ID
		$tpl->assign("ProNav_salename".$j,   $ProNav_Rs[$i]['sale_name']); //轮播名称
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





	$class_sql = "select a.attrid,a.attributename from `{$INFO[DBPrefix]}attribute` as a";
	if (intval($bid)>0){
		$class_sql = "select ac.attrid,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac inner join `{$INFO[DBPrefix]}attribute` as a on a.attrid = ac.attrid";
		$class_sql .= " where ac.cid='" . intval($bid) . "' ";
	}
	$Query_class    = $DB->query($class_sql);
	$ic = 0;
	$attr_class = array();
	while($Rs_class=$DB->fetch_array($Query_class)){
		$attr_class[$ic]['attrid']=$Rs_class['attrid'];
		$attr_class[$ic]['bid']=$bid;
		$attr_class[$ic]['attributename']=$Rs_class['attributename'];
		$Sql_value      = "select * from `{$INFO[DBPrefix]}attributevalue` as v inner join `{$INFO[DBPrefix]}attribute` as a on a.attrid=v.attrid where v.attrid='" . intval($Rs_class['attrid']) . "' order by valueid desc ";
		$Query_value     = $DB->query($Sql_value );
		$iv = 0;
		while ($Rs_value =$DB->fetch_array($Query_value)) {
			$attr_class[$ic]['value'][$iv]['valueid'] = $Rs_value['valueid'];
			$attr_class[$ic]['value'][$iv]['value'] = $Rs_value['value'];
			$iv++;
		}
		$ic++;
	}
	//print_r($attr_class);
	$tpl->assign("attr_array",  $attr_class);

$tpl->assign($Good);
$tpl->display("product_attr.html");
?>