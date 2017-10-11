<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");


$bid  = $FUNCTIONS->Value_Manage($_GET['bid'],'','back','');
$bid  =intval($bid);
$class_banner = array();
$list = 0;
function getBanner($bid){
	global $DB,$INFO,$class_banner,$list,$Bcontent;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$class_banner[$list]['bid'] = $Result['bid'];
		$class_banner[$list]['catname'] = $Result['catname'];
		$class_banner[$list]['banner'] = $Result['banner'];
		if($Result['catcontent']!="" && $Bcontent == "")
			$Bcontent = $Result['catcontent'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		
	}
}

if (intval($bid)>0){
	getBanner($bid);
	$catname = $class_banner[0][catname];
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
	$tpl->assign("cBname",     $catname);
	$tpl->assign("class_banner",     $class_banner);
	$tpl->assign("Bcontent",  $Bcontent);
	$tpl->assign("top_Bid",  $class_banner[0][bid]);

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


//轮播变量
$Sql_top = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.middleimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.ifxygoods,g.salename_color from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifrecommend='1'   and g.ifbonus!='1' and g.ifxy!=1 and g.shopid=0 and g.ifchange!=1 and g.ifpresent!=1 and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ")  order by rand(),g.goodorder asc,g.idate desc   limit 0,3 ";
$Query_top  = $DB->query($Sql_top);

$i=0;
$j=1;
$Sql_level = "";
$ProTop_array_level = array();

while ( $Rs_top = $DB->fetch_array($Query_top)){
	$ProTop_Rs[$i][sale_name] = $Rs_top['salename_color']==""?$Rs_top['sale_name']:"<font color='" . $Rs_top['salename_color'] . "'>" . $Rs_top['sale_name'] . "</font>";
	$ProTop_Rs[$i]['gid']        = intval($Rs_top['gid']) ;
	$ProTop_Rs[$i]['goodsname']  = trim($Rs_top['goodsname'])."".$FUNCTIONS->Storage($Rs_top['ifalarm'],$Rs_top['storage'],$Rs_top['alarmnum']);
	$ProTop_Rs[$i]['price']      = $Rs_top['price'] ;
	$ProTop_Rs[$i]['bn']         = $Rs_top['bn'] ;
	$ProTop_Rs[$i]['smallimg']   = trim($Rs_top['smallimg']) ;
	$ProTop_Rs[$i]['middleimg']  = trim($Rs_top['middleimg']) ;
	$ProTop_Rs[$i]['intro']      = nl2br($Rs_top['intro']);
	$ProTop_Rs[$i]['pricedesc']  = trim($Rs_top['pricedesc']) ;
	if ($Rs_top['iftimesale']==1 && $Rs_top['timesale_starttime']<=time() && $Rs_top['timesale_endtime']>=time()){
		$ProTop_Rs[$i]['pricedesc']  = $Rs_top['saleoffprice'];
	}

	$ProTop_Rs[$i][ifsaleoff] = $Rs_top['ifsaleoff'];
	if ($Rs_top['saleoff_starttime']!=""){
		$ProTop_Rs[$i][saleoff_startdate] = date("Y-m-d H:i",$Rs_top['saleoff_starttime']);
	}
	if ($Rs_top['saleoff_endtime']!=""){
		$ProTop_Rs[$i][saleoff_enddate] = date("Y-m-d H:i",$Rs_top['saleoff_endtime']);
	}
	if ($Rs_top['ifxygoods']!=1 && !($Rs_top['iftimesale']==1 && $Rs_top['timesale_starttime']<=time() && $Rs_top['timesale_endtime']>=time())){
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
	}

	$tpl->assign("ProTop_array_level".$j, $ProTop_array_level);       //商品会员价格数组

	$tpl->assign("ProTop_gid".$j,  intval($ProTop_Rs[$i]['gid'])); //轮播商品ID

	$tpl->assign("ProTop_goodsname".$j,   $ProTop_Rs[$i]['goodsname']); //轮播名称
	$tpl->assign("ProTop_salename".$j,   $ProTop_Rs[$i]['sale_name']); //轮播名称
	$tpl->assign("ProTop_pricedesc".$j,   $ProTop_Rs[$i]['pricedesc']); //轮播特别优惠价格
	$tpl->assign("ProTop_price".$j,       $ProTop_Rs[$i]['price']);     //轮播价格
	$tpl->assign("ProTop_bn".$j,          $ProTop_Rs[$i]['bn']);        //轮播编号
	$tpl->assign("ProTop_img".$j,         $ProTop_Rs[$i]['middleimg']); //轮播图片
	$tpl->assign("ProTop_intro".$j,       $ProTop_Rs[$i]['intro']);     //轮播内容
		$tpl->assign("ProTop_ifsaleoff".$j,       $ProTop_Rs[$i]['ifsaleoff']);
	$tpl->assign("ProTop_saleoff_startdate".$j,       $ProTop_Rs[$i]['saleoff_startdate']);
	$tpl->assign("ProTop_saleoff_enddate".$j,       $ProTop_Rs[$i]['saleoff_enddate']);
	$tpl->assign("ProTop_sale_name".$j,  $ProTop_Rs[$i]['sale_name']);


	$i++;
	$j++;
}



/**
 * 下边是抛出的产品信息列表
 */
include("PageNav.class.php");
$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.sale_name,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.ifxygoods,g.salename_color   from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.shopid=0 and g.ifpub='1'  and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1  and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ")   ";
if ($_GET['orderby']=="price")
	$Sql        .= "  order by g.pricedesc ";
elseif ($_GET['orderby']=="pubtime")
	$Sql        .= "  order by g.idate ";
elseif ($_GET['orderby']=="visit")
	$Sql        .= "  order by g.view_num ";
else
	$Sql        .= "  order by g.goodorder asc,g.idate ";
if ($_GET['ordertype']=="1"){
	$_GET['ordertype'] = 0;
	$Sql        .= " asc";	
}else{
	$Sql        .= " desc";
	$_GET['ordertype'] = 1;
}
$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$Num        = $PageNav->iTotal;
$tpl->assign("product_Num",       $Num); 
$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

if ($Num>0){
	$arrRecords = $PageNav->ReadList();
	$i=0;
	$j=1;
	$Sql_level = "";
	$ProNav_array_level = array();

	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
		$ProNav_Rs[$i]['goodsname']  = $Char_class->cut_str(trim($ProNav['goodsname']),24)."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
		$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
		$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
		$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
		$ProNav_Rs[$i]['smallimg']   = trim($ProNav['smallimg']) ;
		$ProNav_Rs[$i]['intro']      = nl2br($ProNav['intro']);
		$ProNav_Rs[$i][sale_name] = $ProNav['salename_color']==""?$ProNav['sale_name']:"<font color='" . $ProNav['salename_color'] . "'>" . $ProNav['sale_name'] . "</font>";
		
		if ($ProNav['iftimesale']==1 && $ProNav['timesale_starttime']<=time() && $ProNav['timesale_endtime']>=time()){
			$ProNav_Rs[$i]['pricedesc']  = $ProNav['saleoffprice'];
		}
		
		$ProNav_Rs[$i][ifsaleoff] = $ProNav['ifsaleoff'];
		if ($ProNav['saleoff_starttime']!=""){
			$ProNav_Rs[$i][saleoff_startdate] = date("Y-m-d H:i",$ProNav['saleoff_starttime']);
		}
		if ($ProNav['saleoff_endtime']!=""){
			$ProNav_Rs[$i][saleoff_enddate] = date("Y-m-d H:i",$ProNav['saleoff_endtime']);
		}
		if ($ProNav['ifxygoods']!=1 && !($ProNav['iftimesale']==1 && $ProNav['timesale_starttime']<=time() && $ProNav['timesale_endtime']>=time())){
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
		}
		$tpl->assign("ProNav_array_level".$j, $ProNav_array_level);       //商品一会员价格数组

		$tpl->assign("ProNav_gid".$j,         $ProNav_Rs[$i]['gid']); //商品ID
		$tpl->assign("ProNav_goodsname".$j,   $ProNav_Rs[$i]['goodsname']); //商品一名称
		$tpl->assign("ProNav_salename".$j,   $ProNav_Rs[$i]['sale_name']); //轮播名称
		$tpl->assign("ProNav_price".$j,       $ProNav_Rs[$i]['price']);     //商品一价格
		$tpl->assign("ProNav_pricedesc".$j,   $ProNav_Rs[$i]['pricedesc']); //商品一价格
		$tpl->assign("ProNav_bn".$j,          $ProNav_Rs[$i]['bn']);        //商品一编号
		$tpl->assign("ProNav_img".$j,         $ProNav_Rs[$i]['smallimg']);  //商品一图片
		$tpl->assign("ProNav_intro".$j,       $ProNav_Rs[$i]['intro']);     //商品一内容
		$tpl->assign("ProNav_ifsaleoff".$j,       $ProNav_Rs[$i]['ifsaleoff']);
		$tpl->assign("ProNav_saleoff_startdate".$j,       $ProNav_Rs[$i]['saleoff_startdate']);
		$tpl->assign("ProNav_saleoff_enddate".$j,       $ProNav_Rs[$i]['saleoff_enddate']);
		$tpl->assign("ProNav_sale_name".$j,         $ProNav_Rs[$i]['sale_name']);


		$i++;
		$j++;
	}

}

$Query = $DB->query("select catname,bid,catcontent,meta_des,meta_key,banner from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result     =  $DB->fetch_array($Query);
	$Bname      =  $Result['catname'];
	$Bcontent   =  $Result['catcontent'];
	$Meta_desc   =  $Result['meta_des'];
	$Meta_keyword   =  $Result['meta_key'];
	//$banner   =  $Result['banner'];
}
//$tpl->assign("banner",  $banner);
$tpl->assign($Good);
$tpl->display("product_class_second.html");
?>