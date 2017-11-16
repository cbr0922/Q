<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include_once ("../configs.inc.php");
include("product.class.php");
$PRODUCT = new PRODUCT();

if(intval($_GET['brand_id'])>0){
	$Query = $DB->query("SELECT * FROM `{$INFO[DBPrefix]}brand` where brand_id='".intval($_GET['brand_id'])."' AND (classid REGEXP '^6$' OR classid REGEXP '^6,' OR classid REGEXP ',6,' OR classid REGEXP ',6$')");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$tpl->assign("showalert",1);
	}
}

$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($_GET['brand_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);

		$brandcontent    =  trim($Result['brandcontent']);

		$content    =  trim($Result['content']);
		$meta_des    =  trim($Result['meta_des']);
		$meta_key    =  trim($Result['meta_key']);
		$title1    =  trim($Result['title1']);
		$title2    =  trim($Result['title2']);
		$brandname    =  trim($Result['brandname']);
		$logopic    =  trim($Result['logopic']);
		$ifshowgoods    =  trim($Result['ifshowgoods']);
		$brandclass_array = $PRODUCT->getBrandProductClass(intval($_GET['brand_id']));
		if(count($brandclass_array)>0)
			$tpl->assign("brandclass_array",       $brandclass_array);

		$tpl->assign("meta_key",       $meta_key);
		$tpl->assign("ifshowgoods",       $ifshowgoods);
		$tpl->assign("logopic",       $logopic);
		$tpl->assign("meta_des",       $meta_des);
		$tpl->assign("title1",       $title1);
		$tpl->assign("title2",       $title2);
		if($_GET['type']==1){
			$tpl->assign("title",       $title1);
			$tpl->assign("content",       $brandcontent);
		}else{
			$tpl->assign("title",       $title2);
			$tpl->assign("content",       $content);
		}
		$tpl->assign("brandname",  $brandname);
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
$tpl->assign("brand_id",     intval($_GET['brand_id']));
if(intval($_GET['brand_id'])==136){
	$tpl->display("ES-brand_info.html");
}elseif(intval($_GET['brand_id'])==184){
	$tpl->display("LM-brand_info.html");
}else{
	$tpl->display("brand_info.html");
}

?>
