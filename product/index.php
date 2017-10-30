<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include_once ("../configs.inc.php");
include("global.php");
include_once ("Char.class.php");
$Char_Class = new Char_class();
include("product.class.php");
$PRODUCT = new PRODUCT();
$bid = intval($_GET['bid']);
$PRODUCT->ViewBidLevel($bid);
/*$class_banner = array();
$list = 0;
if ($bid>0){
	$PRODUCT->getBanner($bid);   //導航
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}*/
$class_banner = array();
$list = 0;
if (intval($_GET['brand_class'])>0){
	$PRODUCT->getTopBrandBidList(intval($_GET['brand_class']));   //導航
	$catname = $class_banner[0][catname];
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}
if ($bid>0){
	$PRODUCT->getBanner($bid);   //導航
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}
$menutype = "category";//all左側菜單顯示所有分類級別，category只顯示當前館別分類（即從第二級別分類開始顯示）
if($menutype == "all")
	$showbid = 0;
else
	$showbid = $class_banner[0][bid];
$classinfo_array = $PRODUCT->getClassInfo($bid);   //得到分類信息
if($bid == 0){
	$type = $_GET['type'];
	switch($type){
			case "bonus":
				$title = "紅利商品";
				break;
			case "xy":
				$title = "任選商品";
				break;
			case "present":
				$title = "額滿禮商品";
				break;
			case "change":
				$title = "加購商品";
				break;
			case "js":
				$title = "集殺商品";
				break;
			case "recommend":
				$title = "推薦商品";
				break;
			case "hot":
				$title = "熱賣商品";
				break;
			case "special":
				$title = "特價商品";
				break;
			case "new":
				$title = "最新商品";
				break;
		}
	//商品列表
	if($_GET['brand_class']==283){
		$class_product_array = $PRODUCT->getBrandClassList($_GET['brand_class'],1);
		//print_r($class_product_array);
	}else{
		$product_array = $PRODUCT->getProductList(0,$type,array('key'=>$_GET['skey']),array($_GET['orderby'],$_GET['ordertype']),0,1,1,0,1,$INFO['product_filter']);
	}
	//屬性

}else{
	$type = "hot";
	//if ($classinfo_array['manyunfei']>0)
		$ifshowcolor = 1;
	
	//else
	//	$ifshowcolor = 0;
	//if($classinfo_array['top_id']>0){
		//推薦商品3個
		//$recommend_array = $PRODUCT->getProductList($bid,"ifrecommend",array(),array(),3,0,0,0,1);
		//熱賣商品5個
		$hot_array = $PRODUCT->getProductList($bid,$type,array(),array(),5,0,0,0,1);
		//商品列表
		$product_array = $PRODUCT->getProductList($bid,"",array(),array($_GET['orderby'],$_GET['ordertype']),0,1,$ifshowcolor,0,1,$INFO['product_filter']);
	//}else{
	//	
	//}
	
}
$attrvalue_array = $PRODUCT->getLikeAttribute($bid);
/*if(intval($_GET['ordertype'])<=1){
	$_GET['ordertype'] = 1-intval($_GET['ordertype']);
}*/
$menutype = "category";//all左側菜單顯示所有分類級別，category只顯示當前館別分類（即從第二級別分類開始顯示）
if($menutype == "all")
	$showbid = 0;
else
	$showbid = $class_banner[0][bid];
$class_array = $PRODUCT->getProductClass($bid);  //分類，最多顯示3層
//品牌
if(intval($_GET['brand_id'])>0){
	$DB->query("update `{$INFO[DBPrefix]}brand` set viewcount=viewcount+1 where brand_id=".intval($_GET['brand_id']));
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where brandlist=".intval($_GET['brand_id'])." or brandlist like '%,".intval($_GET['brand_id'])."%' or brandlist like '%".intval($_GET['brand_id']).",%' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		while($Result= $DB->fetch_array($Query)){
			//print_r($Result);
			if($Result['bid']==6){
				$tpl->assign("showalert",1);
				$showbid = 6;
			}
		}
	}
	$Query = $DB->query("SELECT * FROM `{$INFO[DBPrefix]}brand` where brand_id='".intval($_GET['brand_id'])."' AND (classid REGEXP '^6$' OR classid REGEXP '^6,' OR classid REGEXP ',6,' OR classid REGEXP ',6$')");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$tpl->assign("showalert",1);
		$showbid = 6;
	}

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($_GET['brand_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$brandname    =  trim($Result['brandname']);
		$brandcontent    =  trim($Result['brandcontent']);
		$logopic    =  trim($Result['logopic']);
		$content    =  trim($Result['content']);
		$meta_des    =  trim($Result['meta_des']);
		$meta_key    =  trim($Result['meta_key']);
		$title1    =  trim($Result['title1']);
		$title2    =  trim($Result['title2']);
		$ifshowgoods    =  trim($Result['ifshowgoods']);
		$brandclass_array = $PRODUCT->getBrandProductClass(intval($_GET['brand_id']));
		if(count($brandclass_array)>0)
			$tpl->assign("brandclass_array",       $brandclass_array);
		$tpl->assign("meta_key",       $meta_key);
		$tpl->assign("meta_des",       $meta_des);
		$tpl->assign("title1",       $title1);
		$tpl->assign("title2",       $title2);
		$tpl->assign("ifshowgoods",       $ifshowgoods);
	}
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}nclass` where brand_id=".intval($_GET['brand_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$nCatname =  $Result['ncname'];
		$tpl->assign("nCatname",       $nCatname);
		 $Sql =  "select * from `{$INFO[DBPrefix]}news` n where n.top_id='" . $Result['ncid']  . "' and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') order by n.nord asc, n.nidate desc ";
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$i = 0;
			if ($Num>0) {
				$pic_news = array();
				while($Rs=$DB->fetch_array($Query))
				{
					$pic_news[$i]['title'] = $Rs['nltitle'];
					$pic_news[$i]['news_id'] = $Rs['news_id'];
					$Nltitle_first  =  $Rs['nltitle_color']!="" ? "<font color=".$Rs['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
					$pic_news[$i]['ltitle'] = $Rs['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Rs['news_id'])."'>".$Nltitle_first."</a>" :  "<a href='".$Rs['url']."'>".$Nltitle_first."</a>";;
					$pic_news[$i]['url'] = $INFO['site_url']."/article/article.php?articleid=".intval($Rs['news_id']);
					//$pic_news[$i]['nimg'] = $INFO['site_url']."/UploadFile/NewsPic/".$Rs['nimg'];
					$pic_news[$i]['nimg'] = $Rs['nimg'];
					$pic_news[$i]['nidate']  = date("Y-m-d",$Rs['nidate']);
					$i++;
				}
			}

		//print_r($pic_news);
		 $tpl->assign("menu_news",$pic_news);
	}
	//萬用格廣告
	if($_GET['brand_class']==0){
		$indexAdv_array = array();
		$i = 0;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}index_banner` where tag='brand" . intval($_GET['brand_id']) . "' order by bannerorder asc");
		$Num   = $DB->num_rows($Query);
		while($Result= $DB->fetch_array($Query)){
			$indexAdv_array[$i] = $Result;
			$indexAdv_array[$i]['col'] = 12/$Result['bannercount'];
			for($j=1;$j<=$Result['bannercount'];$j++){
				$Query_adv = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . $Result['ib_id'] . "_" . $j . "' limit 0,1");
				$Num_adv   = $DB->num_rows($Query_adv);
				$Result_adv= $DB->fetch_array($Query_adv);
				if($Result_adv['adv_type']!=21)
					$indexAdv_array[$i]['adv'][$j-1] = $Result_adv;
				else{
					$indexAdv_array[$i]['adv'][$j-1]['adv_type'] = $Result_adv['adv_type'];
					$z = 0;
					$Query_adv = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . $Result['ib_id'] . "_" . $j . "'");
					while($Result_adv= $DB->fetch_array($Query_adv)){
						$indexAdv_array[$i]['adv'][$j-1]['img'][$z] = $Result_adv;
						$z++;
					}
					//print_r($indexAdv_array[$i]['adv'][$j-1]['img']);
				}
			}
			$i++;
		}

		$tpl->assign("indexAdv_array",$indexAdv_array);
	}else{
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($_GET['brand_class'])." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$Catcontent = $Result['catcontent'];
			$tpl->assign("catcontent",$Catcontent);
		}
	}
	$tpl->assign("brand_id",     intval($_GET['brand_id']));
}
$Sql_sub   = " select * from `{$INFO[DBPrefix]}discountsubject` where subject_open=1  and start_date<='" . date("Y-m-d",time()) . "' and end_date>='" . date("Y-m-d",time()) . "' order by dsid desc";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][dsid]    =  $Rs_sub['dsid'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$Array_sub[$sub_i][start_date]  =  $Rs_sub['start_date'];
	$Array_sub[$sub_i][end_date]  =  $Rs_sub['end_date'];
	$Array_sub[$sub_i][min_money]  =  $Rs_sub['min_money'];
	$Array_sub[$sub_i][min_count]  =  $Rs_sub['min_count'];
	$Array_sub[$sub_i][mianyunfei]  =  $Rs_sub['mianyunfei'];
	$sub_i++;
}
if($classinfo_array['nclass']>0){
	$Pic_News     = "select ns.news_id,ns.ntitle,ns.nimg,ns.brief,ns.news_id,ns.nltitle,ns.nltitle_color,ns.url_on,ns.url from `{$INFO[DBPrefix]}nclass` nc inner join  `{$INFO[DBPrefix]}news` ns on ( nc.ncid = ns.top_id ) where  nc.ncid='" . $classinfo_array['nclass'] . "' and  nc.ncatiffb='1' and  ns.niffb='1' order by ns.nord asc, ns.nidate desc  limit 0,5";
		$Query_Pic_News   = $DB->query($Pic_News);
		$i=0;
		while( $Result_Pic_news = $DB->fetch_array($Query_Pic_News) )
		{
		   $Nltitle        =  $Result_Pic_news['nltitle'];
		   $Nltitle_first  =  $Result_Pic_news['nltitle_color']!="" ? "<font color=".$Result_Pic_news['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
		   $Nltitle_s =  $Result_Pic_news['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Result_Pic_news['news_id'])."&type=' class=job2>".$Nltitle_first."</a>" :  "<a href='".$Result_Pic_news['url']."'>".$Nltitle_first."</a>";
		   $Pic_News1[$i]['news_id']	= $Result_Pic_news['news_id'];
		   $Pic_News1[$i]['title']		= $Char_Class->cut_str($Result_Pic_news['ntitle'],12);
		   $Pic_News1[$i]['ltitle']		= $Nltitle_s;
		   $Pic_News1[$i]['brief']		= $Result_Pic_news['brief'];
		   $Pic_News1[$i]['nimg']		= $Result_Pic_news['nimg'];
		  $i++;
		}
		//print_r($Pic_News1);
	$tpl->assign('pic_news',$Pic_News1);
}
/* FB像素Search事件 */
$track_id = '6';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	  if($_GET['skey']!=""){
		  $track_Js = "<script>fbq('track', 'Search');	</script>";
	  }
  }
	else $track_Js="";
	$tpl->assign("Search_js",   $track_Js);
}
$tpl->assign("catname",     $catname);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("title",       $title);
$tpl->assign("content",       $content);
$tpl->assign("logopic",       $logopic);
$tpl->assign("Array_sub",       $Array_sub);
$tpl->assign("brandname",  $brandname);
$tpl->assign("brandcontent",  $brandcontent);
$tpl->assign("banner",  $banner);
$tpl->assign("ProductListAll",  $class_array);
$tpl->assign("attrvalue_array",$attrvalue_array);
$tpl->assign("class_banner",     $class_banner);
$tpl->assign("classinfo_array",     $classinfo_array);
$tpl->assign("class_product_array",     $class_product_array);
//$tpl->assign("recommend_array",     $recommend_array['info']);
$tpl->assign("hot_array",     $hot_array['info']);
$tpl->assign("product_array",     $product_array);
$tpl->assign("showbid",     $showbid);
if(intval($_GET['brand_id'])==136){
	$tpl->display("ES-product_index.html");
}elseif(intval($_GET['brand_id'])==184){
	$tpl->display("LM-product_index.html");
}else{
	$tpl->display("product_index.html");
}
?>
