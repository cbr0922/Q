<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");


$bid  = $FUNCTIONS->Value_Manage($_GET['bid'],'','back','');
$bid  =intval($bid);

if (intval($bid)>0){
	
	
	$Query = $DB->query("select catname,bid,catcontent,top_id,meta_des,meta_key from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$Bname      =  $Result['catname'];
		$Bcontent   =  $Result['catcontent'];
		$top_id   =  $Result['top_id'];
		$Meta_desc   =  $Result['meta_des'];
		$Meta_keyword   =  $Result['meta_key'];
		$firstbid   =  $Result['bid'];
		$tpl->assign("Meta_desc",  $Meta_desc);
		$tpl->assign("Meta_keyword",  $Meta_keyword);
		if ($top_id==0){
			$tpl->assign("Bname",     $Bname);     //产品大类名称
			$tpl->assign("Bid",     $firstbid);     //产品大类名称
			$tpl->assign("Bcontent",  $Bcontent);  //产品HTML编辑器的内容
		}else{
			$tpl->assign("cBname",     $Bname);     //产品大类名称
			$tpl->assign("cBid",     $firstbid);     //产品大类名称
			$tpl->assign("Bcontent",  $Bcontent);  //产品HTML编辑器的内容
			$Query = $DB->query("select catname,bid,catcontent,top_id from `{$INFO[DBPrefix]}bclass` where bid=".intval($top_id)." limit 0,1 ");
			$Num   = $DB->num_rows($Query);
			if ($Num>0){
				$Result     =  $DB->fetch_array($Query);
				$Bname      =  $Result['catname'];
				$top_id   =  $Result['top_id'];
				$firstbid   =  $Result['bid'];
				$tpl->assign("Bname",     $Bname);     //产品大类名称
				$tpl->assign("Bid",     $firstbid);     //产品大类名称
				$Query = $DB->query("select catname,bid,catcontent,top_id from `{$INFO[DBPrefix]}bclass` where bid=".intval($top_id)." limit 0,1 ");
				$Num   = $DB->num_rows($Query);	
				if ($Num>0){
					$Result     =  $DB->fetch_array($Query);
					$Bname      =  $Result['catname'];
					$Bcontent   =  $Result['catcontent'];
					$tpl->assign("top_Bname",     $Bname);     //产品大类名称
					$tpl->assign("top_Bid",     $Result['bid']);     //产品大类名称
				}
			}
		}
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
$Sql_top = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.middleimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.js_begtime,g.js_endtime,g.ifjs,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.salename_color from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifrecommend='1' and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ")  order by g.goodorder asc,g.idate desc   limit 0,2 ";
$Query_top  = $DB->query($Sql_top);

$i=0;
$j=1;
$Sql_level = "";
$ProTop_array_level = array();

while ( $Rs_top = $DB->fetch_array($Query_top)){
	$ProTop_Rs[$i]['gid']        = intval($Rs_top['gid']) ;
	$ProTop_Rs[$i]['goodsname']  = trim($Rs_top['goodsname'])."".$FUNCTIONS->Storage($Rs_top['ifalarm'],$Rs_top['storage'],$Rs_top['alarmnum']);
	$ProTop_Rs[$i]['price']      = $Rs_top['price'] ;
	$ProTop_Rs[$i]['bn']         = $ProTop['bn'] ;
	$ProTop_Rs[$i]['smallimg']   = trim($Rs_top['smallimg']) ;
	$ProTop_Rs[$i]['middleimg']  = trim($Rs_top['middleimg']) ;
	$ProTop_Rs[$i]['intro']      = nl2br($Rs_top['intro']);
	$ProTop_Rs[$i]['pricedesc']  = trim($Rs_top['pricedesc']) ;
	$ProTop_Rs[$i]['ifalarm']    = $ProTop['ifalarm'] ;     //警告圖片
	$ProTop_Rs[$i]['storage']    = $ProTop['storage'];   //警告圖片庫存
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
	$tpl->assign("ProTop_intro".$j,           $Char_class->cut_str($ProTop_Rs[$i]['intro'],70,0,'UTF-8'));//轮播内容
	$tpl->assign("ProTop_ifsaleoff".$j,       $ProTop_Rs[$i]['ifsaleoff']);
	$tpl->assign("ProTop_saleoff_startdate".$j,       $ProTop_Rs[$i]['saleoff_startdate']);
	$tpl->assign("ProTop_saleoff_enddate".$j,       $ProTop_Rs[$i]['saleoff_enddate']);
	$tpl->assign("ProTop_sale_name".$j,  $ProTop_Rs[$i]['sale_name']);
	$tpl->assign("ProTop_ifalarm".$j,     $ProTop_Rs[$i]['ifalarm']);  //警告圖片
	$tpl->assign("ProTop_storage".$j,     $ProTop_Rs[$i]['storage']);     //警告圖片庫存



	$i++;
	$j++;
}



/**
 * 下边是抛出的产品信息列表
 */
include("PageNav.class.php");
$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.salename_color   from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1  and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ")   order by g.goodorder asc,g.idate desc  ";
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
		$ProNav_Rs[$i]['ifalarm']    = $ProNav['ifalarm'] ;     //警告圖片
	    $ProNav_Rs[$i]['storage']    = $ProNav['storage'];   //警告圖片庫
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
		$tpl->assign("ProNav_array_level".$j, $ProNav_array_level);       //商品一会员价格数组

		$tpl->assign("ProNav_gid".$j,         $ProNav_Rs[$i]['gid']); //商品ID
		$tpl->assign("ProNav_goodsname".$j,   $ProNav_Rs[$i]['goodsname']); //商品一名称
		$tpl->assign("ProNav_price".$j,       $ProNav_Rs[$i]['price']);     //商品一价格
		$tpl->assign("ProNav_pricedesc".$j,   $ProNav_Rs[$i]['pricedesc']); //商品一价格
		$tpl->assign("ProNav_bn".$j,          $ProNav_Rs[$i]['bn']);        //商品一编号
		$tpl->assign("ProNav_img".$j,         $ProNav_Rs[$i]['smallimg']);  //商品一图片
		$tpl->assign("ProNav_intro".$j,       $ProNav_Rs[$i]['intro']);     //商品一内容
		$tpl->assign("ProNav_ifalarm".$j,     $ProNav_Rs[$i]['ifalarm']);  //警告圖片
		$tpl->assign("ProNav_storage".$j,     $ProNav_Rs[$i]['storage']);     //警告圖片庫存
		$tpl->assign("ProNav_ifsaleoff".$j,       $ProNav_Rs[$i]['ifsaleoff']);
		$tpl->assign("ProNav_saleoff_startdate".$j,       $ProNav_Rs[$i]['saleoff_startdate']);
		$tpl->assign("ProNav_saleoff_enddate".$j,       $ProNav_Rs[$i]['saleoff_enddate']);
		$tpl->assign("ProNav_sale_name".$j,         $ProNav_Rs[$i]['sale_name']);


		$i++;
		$j++;
	}

}

$tpl->assign($Good);
$tpl->display("product_class_detailpublisher.html");
?>