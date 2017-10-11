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

$FUNCTIONS->CheckUserView("goods",$Goods_id);



//0113浏览过的商品
	$goodscount = count($_COOKIE['viewgoods'])+1;
	$flag = 0;
	if (isset($_COOKIE['viewgoods'])){
		foreach($_COOKIE['viewgoods'] as $k=>$v){
			if ($v == intval($Goods_id)){
				$flag = 1;
			}
		}
	}
	if ($flag == 0){
		setcookie("viewgoods[" . $goodscount . "]", intval($Goods_id),time()+3600*24,"/");
	}




$product_array = $PRODUCT->getProductInfo($Goods_id,$_GET['goodstype']);  //商品信息
if($product_array == 0)
	$FUNCTIONS->header_location("../index.php");
$pic_array = $PRODUCT->getProductPic($Goods_id);//商品多圖
$pic_array[0]['pic'] = $product_array['middleimg'];
$UpProduct_array = $PRODUCT->getUporDown($Goods_id,$product_array['bid'],$product_array['bname'],"up");//上一個商品
$DownProduct_array = $PRODUCT->getUporDown($Goods_id,$product_array['bid'],$product_array['bname'],"down");//下一個商品
$LinkProduct_array = $PRODUCT->getProductLink($Goods_id,$product_array['bid'],$product_array['ifgl']);//相關商品 
$class_banner = array();
$list = 0;
if (intval($product_array['bid'])>0){
	$PRODUCT->getBanner(intval($product_array['bid']));   //導航
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='13'  limit 0,1");

while ($Result  = $DB->fetch_array($Query)){
	$tpl->assign("Content_01",        $Result[info_content]);      
}
//print_r($product_array);
//------------------------
//輸出到模板
//------------------------
foreach($product_array as $k=>$v){
	$tpl->assign("P_" . $k,   $v);
}
$tpl->assign("banner",     $banner);
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
$Sql   =  " select * from `{$INFO[DBPrefix]}score` as s inner join `{$INFO[DBPrefix]}user` as u on s.user_id=u.user_id where s.gid=".intval($Goods_id)." and s.ifcheck=1 order by s.score_id  desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		//$OrderList[$i]['score1']     = $Rs['score1'];
		$OrderList[$i]['content']     = $Rs['content'];
		$OrderList[$i]['answer']     = $Rs['answer'];
		$OrderList[$i]['username'] = substr( $Rs['username'],0,4) . "*****";
		$OrderList[$i]['scoretime'] = date("Y-m-d",$Rs['scoretime']);
		$i++;
	}
}
$tpl->assign("OrderNum",     $Num);
$tpl->assign("OrderList",     $OrderList);
$tpl->assign("maxbuyCount",  intval($INFO['buy_product_max_num']));
$tpl->display("goods_detail.html");
?>
