<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include_once ("../configs.inc.php");
include("product.class.php");
$PRODUCT = new PRODUCT();

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
			}
		}
	}
	$Query = $DB->query("SELECT * FROM `{$INFO[DBPrefix]}brand` where brand_id='".intval($_GET['brand_id'])."' AND (classid REGEXP '^6$' OR classid REGEXP '^6,' OR classid REGEXP ',6,' OR classid REGEXP ',6$')");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$tpl->assign("showalert",1);
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
		$brandclass_array = $PRODUCT->getBrandProductClass(intval($_GET['brand_id']));
		if(count($brandclass_array)>0)
			$tpl->assign("brandclass_array",       $brandclass_array);
		$tpl->assign("meta_key",       $meta_key);
		$tpl->assign("meta_des",       $meta_des);
		$tpl->assign("title1",       $title1);
		$tpl->assign("title2",       $title2);
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
				$Query_adv = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . $Result['ib_id'] . "_" . $j . "' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') limit 0,1");
				$Num_adv   = $DB->num_rows($Query_adv);
				$Result_adv= $DB->fetch_array($Query_adv);
				if($Result_adv['adv_type']!=21)
					$indexAdv_array[$i]['adv'][$j-1] = $Result_adv;
				else{
					$indexAdv_array[$i]['adv'][$j-1]['adv_type'] = $Result_adv['adv_type'];
					$z = 0;
					$Query_adv = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . $Result['ib_id'] . "_" . $j . "' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "')");
					$Num_adv2   = $DB->num_rows($Query_adv);
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
		$tpl->assign("Num_adv2",$Num_adv2);
	}else{
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($_GET['brand_class'])." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$Catcontent = $Result['catcontent'];
			$tpl->assign("catcontent",$Catcontent);
		}
	}
}


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
$tpl->display("ES-index.html");
?>
