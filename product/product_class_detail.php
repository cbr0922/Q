<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");
include_once ("Char.class.php");
$Char_Class = new Char_class();

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
		$class_banner[$list]['manyunfei'] = $Result['manyunfei'];
		$class_banner[$list]['catcontent'] = $Result['catcontent'];
		//if($Result['catcontent']!="" && $Bcontent == "")
			//$Bcontent = $Result['catcontent'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		
	}
}

if (intval($bid)>0){
	getBanner($bid);
	$manyunfei = $class_banner[0][manyunfei];
	$Bcontent = $class_banner[0][catcontent];
	$tpl->assign("Bname",     $class_banner[0][catname]);
	$class_banner = array_reverse($class_banner);
	//print_r($class_banner);
	$banner = $class_banner[0][banner];
	$tpl->assign("banner",     $banner);
	$tpl->assign("class_banner",     $class_banner);
	
	$tpl->assign("Bcontent",  $Bcontent);
	$tpl->assign("top_Bid",  $class_banner[0][bid]);
	$tpl->assign("manyunfei",  $manyunfei);
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

/*
$Query = $DB->query("select catname,bid,catcontent from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result     =  $DB->fetch_array($Query);
	$Bname      =  $Result['catname'];
	$Bcontent   =  $Result['catcontent'];
	$Bname_url  = "<a href=".$INFO[site_url]."/product/product_class_detail.php?bid=".intval($bid).">".$Bname."</a>";
	$Egg  = $FUNCTIONS->father_Nav_banner(intval($Result['top_id']));

	if ($Egg!=""){
		$Egg  = explode(",",str_replace("||",",",$Egg));
		krsort($Egg);
		reset ($Egg);
		foreach ($Egg as $k=>$v){
			$Father_Nav .= $v!="" ? $v." - " : "";
		}
	}

}
$Nav_Product_class  = $Father_Nav.$Bname_url;
$tpl->assign("Nav_Product_class",  $Nav_Product_class); //产品种类导航条

$tpl->assign("Bname",     $Bname);     //产品大类名称
$tpl->assign("Bcontent",  $Bcontent);  //产品HTML编辑器的内容

*/
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
$Sql_top = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.middleimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.js_begtime,g.js_endtime,g.ifjs,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.salename_color,g.good_size,g.good_color from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifrecommend='1' and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ")  order by g.goodorder asc,g.idate desc   limit 0,3 ";
$Query_top  = $DB->query($Sql_top);

$i=0;
$j=1;
$Sql_level = "";
$ProTop_array_level = array();

while ( $Rs_top = $DB->fetch_array($Query_top)){
	$ProTop_Rs[$i]['gid']        = intval($Rs_top['gid']) ;
	$ProTop_Rs[$i]['goodsname']  =$Char_Class->cut_str( trim($Rs_top['goodsname'])."".$FUNCTIONS->Storage($Rs_top['ifalarm'],$Rs_top['storage'],$Rs_top['alarmnum']),16);
	$ProTop_Rs[$i]['price']      = $Rs_top['price'] ;
	$ProTop_Rs[$i]['bn']         = $Rs_top['bn'] ;
	$ProTop_Rs[$i]['smallimg']   = trim($Rs_top['smallimg']) ;
	$ProTop_Rs[$i]['storage']  = trim($Rs_top['storage']) ;
	$ProTop_Rs[$i]['middleimg']  = trim($Rs_top['middleimg']) ;
	$ProTop_Rs[$i]['intro']      = nl2br($Rs_top['intro']);
	$ProTop_Rs[$i]['pricedesc']  = trim($Rs_top['pricedesc']) ;
	$ProTop_Rs[$i][sale_name] = $Rs_top['salename_color']==""?$Rs_top['sale_name']:"<font color='" . $Rs_top['salename_color'] . "'>" . $Rs_top['sale_name'] . "</font>";
	$ProTop_Rs[$i][ifsaleoff] = $Rs_top['ifsaleoff'];
	$ProTop_Rs[$i]['ifshow']  = $FUNCTIONS->getStorage($Rs_top['ifalarm'],$Rs_top['storage']) ;
	
	if ($Rs_top['saleoff_starttime']!=""){
		$ProTop_Rs[$i][saleoff_startdate] = date("Y-m-d H:i",$Rs_top['saleoff_starttime']);
	}
	if ($Rs_top['saleoff_endtime']!=""){
		$ProTop_Rs[$i][saleoff_enddate] = date("Y-m-d H:i",$Rs_top['saleoff_endtime']);
	}
	if ($Rs_top['iftimesale']==1 && $Rs_top['timesale_starttime']<=time() && $Rs_top['timesale_endtime']>=time()){
		$ProTop_Rs[$i]['pricedesc']  = $Rs_top['saleoffprice'];
	}



	if ($Rs_top['ifxygoods']!=1 && !($Rs_top['iftimesale']==1 && $Rs_top['timesale_starttime']<=time() && $Rs_top['timesale_endtime']>=time())){
		$Sql_level   = "select  u.*  from `{$INFO[DBPrefix]}user_level` u ";
		$Query_level = $DB->query($Sql_level);
		$v=0;
		$ProTop_array_level = array();
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['pricerate'])>0){
				$ProTop_array_level[$v]['level_name'] = $Result_level['level_name'];
				$ProTop_array_level[$v]['m_price']    = round($Result_level['pricerate']*0.01*$Rs_top['pricedesc'],0);
				$v++;
			}
		}
	}

	$tpl->assign("ProTop_array_level".$j, $ProTop_array_level);       //商品会员价格数组

	$tpl->assign("ProTop_gid".$j,  intval($ProTop_Rs[$i]['gid'])); //轮播商品ID

	$tpl->assign("ProTop_goodsname".$j,   $ProTop_Rs[$i]['goodsname']); //轮播名称
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
$goods_array = array();
$goods_color_array = array();
$goods_size_array = array();
$goods_count_array = array();
if (is_array($_COOKIE['mangoods'][$bid])){
	$goods_array = $_COOKIE['mangoods'][$bid];
	$goods_color_array = $_COOKIE['mangoods_color'][$bid];
	$goods_size_array = $_COOKIE['mangoods_size'][$bid];
	$goods_count_array = $_COOKIE['mangoods_count'][$bid];
}



/**
 * 下边是抛出的产品信息列表
 */
include("PageNav.class.php");
$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.salename_color   from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1  and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ")     ";
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
		$ProNav_Rs[$i]['count'] = 0;
		if (in_array(intval($ProNav['gid']),$goods_array)){
			foreach($goods_array as $k=>$v){
				if (intval($ProNav['gid']) == $v){
					$ProNav_Rs[$i]['count']        = $goods_count_array[$k] ;
					$ProNav_Rs[$i]['colors']        = $goods_color_array[$k] ;
					$ProNav_Rs[$i]['sizes']        = $goods_size_array[$k] ;
					$ProNav_Rs[$i]['buykey']    = $k;
				}
			}
		}
		$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
		$ProNav_Rs[$i]['goodsname']  =$Char_Class->cut_str( trim($ProNav['goodsname'])."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']),16);
		$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
		$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
		$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
		$ProNav_Rs[$i]['smallimg']   = trim($ProNav['smallimg']) ;
		$ProNav_Rs[$i]['intro']      = nl2br($ProNav['intro']);
		$ProNav_Rs[$i][sale_name] = $ProNav['salename_color']==""?$ProNav['sale_name']:"<font color='" . $ProNav['salename_color'] . "'>" . $ProNav['sale_name'] . "</font>";
		$ProNav_Rs[$i]['ifshow']  = $FUNCTIONS->getStorage($ProNav['ifalarm'],$ProNav['storage']) ;
		
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
		$Sql_level   = "select  u.*  from `{$INFO[DBPrefix]}user_level` u ";
		$Query_level = $DB->query($Sql_level);
		$v=0;
		$ProNav_array_level = array();
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['pricerate'])>0){
				$ProNav_array_level[$v]['level_name'] = $Result_level['level_name'];
				$ProNav_array_level[$v]['m_price']    = round($Result_level['pricerate']*0.01*$ProNav['pricedesc'],0);
				$v++;
			}
		}
	}
	
	/**
		 *  获得产品颜色下拉菜单内变量
		 */
	
		if (trim($ProNav['good_color'])!=""){
			$Good_color_array    =  explode(',',trim($ProNav['good_color']));
	
			if (is_array($Good_color_array)){
				foreach($Good_color_array as $k=>$v )
				{
					$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Color_Option = "";
		}
	
		$ProNav_Rs[$i]['color'] =  $Good_Color_Option;
	
		/**
		 *  获得产品尺寸下拉菜单内变量
		 */
	
		if (trim($ProNav['good_size'])!=""){
			$Good_size_array    =  explode(',',trim($ProNav['good_size']));
	
			if (is_array($Good_size_array)){
				foreach($Good_size_array as $k=>$v )
				{
					$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Size_Option = "";
		}
	
	
		$ProNav_Rs[$i]['size'] =  $Good_Size_Option;
		
		$tpl->assign("ProNav_array_level".$j, $ProNav_array_level);       //商品一会员价格数组

		$tpl->assign("ProNav_gid".$j,         $ProNav_Rs[$i]['gid']); //商品ID
		$tpl->assign("ProNav_goodsname".$j,   $ProNav_Rs[$i]['goodsname']); //商品一名称
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
$tpl->assign("goods_array",       $ProNav_Rs); 
$tpl->assign($Good);
$tpl->display("product_class_detail.html");
?>
