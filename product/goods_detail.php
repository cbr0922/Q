<?php
session_start();
include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");
@header("Content-type: text/html; charset=utf-8");
include("user.class.php");
$USER = new USERS();
include("product.class.php");
$PRODUCT = new PRODUCT();
$Goods_id  = $FUNCTIONS->Value_Manage($_GET['goods_id'],$_POST['Goods_id'],'back','');  //判断是否有正常的ID进入
$Goods_id  =intval($Goods_id);
$MemberState =  intval($_SESSION['user_id'])>0 ? 1 : 0 ;
if($MemberState == 1){
	$PRODUCT->ViewGidBelate($Goods_id);
}
$FUNCTIONS->CheckUserView("goods",$Goods_id);
$DB->query("update `{$INFO[DBPrefix]}goods` set view_num=view_num+1 where gid=".intval($Goods_id));
//0113浏览过的商品
$goodscount = count($_COOKIE['viewgoods']);
$flag = 0;
if (isset($_COOKIE['viewgoods'])){
	foreach($_COOKIE['viewgoods'] as $k=>$v){
		if ($v == intval($Goods_id)){
			$flag = 1;
		}
	}
}
if($goodscount > 11 && $flag == 0){
	foreach($_COOKIE['viewgoods'] as $k=>$v){
		if($k<11){
			setcookie("viewgoods[".$k."]", $_COOKIE['viewgoods'][$k+1],time()+3600*24,"/");
		}
	}
}
$goodscount = $goodscount > 11 ? 11 : $goodscount;
if ($flag == 0){
	setcookie("viewgoods[" . $goodscount . "]", intval($Goods_id),time()+3600*24,"/");
}
//print_r($_COOKIE['viewgoods']);
$product_array = $PRODUCT->getProductInfo($Goods_id,$_GET['goodstype']);  //商品信息
$product_array['ifbelate'] = $PRODUCT->ViewGidBelate($Goods_id,1);
$PRODUCT->ViewBidLevel($product_array['bid']);
if($product_array == 0)
	$FUNCTIONS->header_location("../index.php");
$pic_array = $PRODUCT->getProductPic($Goods_id);//商品多圖

if($product_array['good_color']!=""){
	foreach($product_array['color'] as $k=>$v){
		foreach($pic_array as $k1=>$v1){
			if($pic_array[$k1]['color'] == $product_array['color'][$k]['color']){
				$pic_array[$k1]['sort'] = $product_array['color'][$k]['sort'];
			}
		}
	}
}elseif ($product_array['productdetail']['count']>0) {
	foreach($product_array['productdetail']['info'] as $k=>$v){
		foreach($pic_array as $k1=>$v1){
			if($pic_array[$k1]['detail_name'] == $v['detail_name']){
				$pic_array[$k1]['sort'] = $v['sort'];
			}
		}
	}
}

if($product_array['good_color']=="" && $product_array['productdetail']['count']==0){
	$pic_array[0]['pic'] = $product_array['middleimg'];
	$pic_array[0]['bigpic'] = $product_array['bigimg'];

	//$pic_array[0]['bigpic'] = $product_array['bigpic'];
}else {
	$pic_array = array_values($pic_array);
}

//$pic_array = array_values($pic_array);
$UpProduct_array = $PRODUCT->getUporDown($Goods_id,$product_array['bid'],$product_array['bname'],"up");//上一個商品
$DownProduct_array = $PRODUCT->getUporDown($Goods_id,$product_array['bid'],$product_array['bname'],"down");//下一個商品
$LinkProduct_array = $PRODUCT->getProductLink($Goods_id,$product_array['bid'],$product_array['ifgl']);//相關商品
$PresentProduct_array = $PRODUCT->getPresentProduct($Goods_id);//相關商品
//print_r($PresentProduct_array);
$class_banner = array();
$list = 0;
if (intval($product_array['bid'])>0){
	$PRODUCT->getBanner(intval($product_array['bid']));   //導航
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='24'  limit 0,1");
while ($Result  = $DB->fetch_array($Query)){
	$tpl->assign("Content_01",        $Result[info_content]);
}
//print_r($product_array);
//------------------------
//輸出到模板
//------------------------
foreach($product_array as $k=>$v){
	if($k=="intro" && $v=="")
		$meta_desc = $INFO['meta_desc'];
	elseif($k=="intro"){
		$meta_desc = $v;
	}
	if($k=="keywords" && $v=="")
		$v = $INFO['meta_keyword'];
	if($k=="intro"){
		$meta_desc = str_replace("\"","“",$meta_desc);
		$tpl->assign("meta_desc",   $meta_desc);
		$tpl->assign("P_" . $k,   nl2br($v));
	}else{
		$tpl->assign("P_" . $k,   $v);
	}
}
$tpl->assign("banner",     $banner);
$tpl->assign("PresentProduct_array",     $PresentProduct_array);
$tpl->assign("LinkProduct_array",     $LinkProduct_array);
$tpl->assign("UpProduct_array",     $UpProduct_array);
$tpl->assign("DownProduct_array",     $DownProduct_array);
$tpl->assign("Goodpic",     $pic_array);
$tpl->assign("class_banner",     $class_banner);
$tpl->assign("Bcontent",  $Bcontent);
$tpl->assign("Bname",  $class_banner[0][catname]);
$tpl->assign("goodBid",  $class_banner[0][bid]);
$tpl->assign("recommendno",   $USER->getRecommendno($_SESSION['user_id']));    //會員編號，用於商品分享
$menutype = "category";//all左側菜單顯示所有分類級別，category只顯示當前館別分類（即從第二級別分類開始顯示）
if($menutype == "all")
	$showbid = 0;
else
	$showbid = $class_banner[0][bid];
$class_array = $PRODUCT->getProductClass($showbid);  //分類，最多顯示3層
$tpl->assign("ProductListAll",  $class_array);
//評價
$Sql   =  " select *,(score1) as scorea from `{$INFO[DBPrefix]}score` as s inner join `{$INFO[DBPrefix]}user` as u on s.user_id=u.user_id where s.gid=".intval($Goods_id)." and s.ifcheck=1 order by s.score_id  desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$totalscore = 0;
if ($Num>0){
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['score']     = $Rs['score1'];
		$OrderList[$i]['content']     = $Rs['content'];
		$OrderList[$i]['sex']     = $Rs['sex']==0?"先生":"小姐";
		$OrderList[$i]['answer']     = $Rs['answer'];
		//$OrderList[$i]['true_name'] =  $Char_class->cut_str($Rs['true_name'],1,0,'UTF-8').'***';
		$username = explode("@",$Rs['username']);
		$OrderList[$i]['true_name'] = $username[0];
		$OrderList[$i]['scoretime'] = date("Y-m-d",$Rs['scoretime']);
		//if ($Rs['scorea']>0)
			$OrderList[$i]['score1']     = str_repeat("<img src='../templates/default/images/star1.png' width='22' height='22'>",intval($Rs['scorea']));
		if (intval($Rs['scorea'])<5)
			$OrderList[$i]['score2']     = str_repeat("<img src='../templates/default/images/star2.png' width='22' height='22'>",5-intval($Rs['scorea']));
		$totalscore += $Rs['scorea'];
		$i++;
	}
	$avgscore = $totalscore/$Num ;
	$avgscoreimg = str_repeat("<img src='../../templates/default/images/star1.png' width='20' height='20'>",intval($avgscore));
	if ($avgscore<5 && $avgscore>intval($avgscore))
			$avgscoreimg .= str_repeat("<img src='../../templates/default/images/star2.png' width='20' height='20'>",1);
}
$Sql         = "select n.* from `{$INFO[DBPrefix]}goods_books` gl  inner join `{$INFO[DBPrefix]}news` n on (gl.nid=n.news_id) where gl.gid=".intval($Goods_id)." and n.niffb=1 and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') order by gl.ord desc ";
$Query       = $DB->query($Sql);
$bNum         = $DB->num_rows($Query);
$books_array = array();
$i = 0;
while ($Result    = $DB->fetch_array($Query)) {
  $books_array[$i]['ntitle'] = $Result['nltitle'];
  $books_array[$i]['news_id'] = $Result['news_id'];
  $books_array[$i]['brief'] = $Result['brief'];
  $books_array[$i]['nimg'] = $Result['nimg'];
  $i++;
}
$Goods_id  = $FUNCTIONS->Value_Manage($_GET['goods_id'],'','back','');
$Query   = $DB->query("select g.gimg,g.bigimg,g.middleimg,g.goodsname,g.smallimg from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where  b.catiffb=1 and g.ifpub=1 and g.gid=".intval($Goods_id)." limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result_goods = $DB->fetch_array($Query);
	$Big_pic      = $Result_goods['bigimg']!="" ?  $INFO['site_url']."/".$INFO['good_pic_path']."/".$Result_goods['bigimg'] : "";
	$Goodsname    = $Result_goods['goodsname'];
	$Big_pic      = $Big_pic!="" ? $Big_pic :  $INFO['site_url']."/".$INFO['good_pic_path']."/".$Result_goods['smallimg']  ;
}
//$DB->free_result($Query);
/*
$Sql_pic    = "select goodpic_name from `{$INFO[DBPrefix]}good_pic` where goodpic_name<>'' and good_id=".intval($Goods_id);
$Query_pic  = $DB->query($Sql_pic);
$Num_pic    = $DB->num_rows($Query_pic);
$Goodpic[0]['pic'] =   $Result_goods['middleimg'];
$Goodpic[0]['title'] =   '';
$i = 1;
if ($Num_pic>0){
	while ($Result_pic = $DB->fetch_array($Query_pic))  {
		$Goodpic[$i]['pic'] =   $Result_pic['goodpic_name'];
		$Goodpic[$i]['title'] =   $Result_pic['goodpic_title'];
		$i++;
	}
}*/
//开始赋值
$tpl->assign("Goodsname",      $Goodsname);
$tpl->assign("Big_pic",        $Big_pic);
//$tpl->assign("Goodpic",        $Goodpic);
$tpl->assign("Close",          $Basic_Command['Close']); //关闭

$Query = $DB->query("select brand_id from `{$INFO[DBPrefix]}goods` where gid=".$_GET['goods_id']." limit 0,1");
$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$brand_id    =  $Result['brand_id'];
	}
$tpl->assign("brand_id",       $brand_id);


$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($brand_id)." limit 0,1");
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
		$brandclass_array = $PRODUCT->getBrandProductClass(intval($brand_id));
		if(count($brandclass_array)>0)
			$tpl->assign("brandclass_array",       $brandclass_array);
		$tpl->assign("meta_key",       $meta_key);
		$tpl->assign("meta_des",       $meta_des);
		$tpl->assign("title1",       $title1);
		$tpl->assign("title2",       $title2);
		$tpl->assign("ifshowgoods",       $ifshowgoods);
	}




/* FB像素ViewContent事件 */
$track_id = '5';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = "<script>fbq('track', 'ViewContent');</script>";
  }
	else $track_Js="";
	$tpl->assign("ViewContent_js",   $track_Js);
}
/* FB像素AddToCart事件 */
$track_id = '7';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = "fbq('track', 'AddToCart');";
  }
	else $track_Js="";
	$tpl->assign("AddToCart_js",   $track_Js);
}


$tpl->assign("title",       $title);
$tpl->assign("content",       $content);
$tpl->assign("logopic",       $logopic);
$tpl->assign("Array_sub",       $Array_sub);
$tpl->assign("brandname",  $brandname);
$tpl->assign("brandcontent",  $brandcontent);

$tpl->assign("bNum",     $bNum);
$tpl->assign("books_array",     $books_array);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("OrderNum",     $Num);
$tpl->assign("OrderList",     $OrderList);
$tpl->assign("avgscoreimg",     $avgscoreimg);
$tpl->assign("ScoreNum",     $Num);
$tpl->assign("maxbuyCount",  intval($INFO['buy_product_max_num']));
$tpl->assign("MemberState", intval($MemberState)); //會員狀態
if($product_array['brand_id']==136){
	$tpl->display("ES-goods_detail.html");
}elseif($product_array['brand_id']==184){
	$tpl->display("LM-goods_detail.html");
}else{
	$tpl->display("goods_detail.html");
}
?>
