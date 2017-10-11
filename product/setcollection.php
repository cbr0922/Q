<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
//include "../language/".$INFO['IS']."/Good.php";

include("product.class.php");
$PRODUCT = new PRODUCT();

if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
	$FUNCTIONS->header_location("../member/login_windows.php?Url=" . urlencode("../product/setcollection.php?gid=" . $_GET['gid'] . "&Action=" . $_GET['Action']));
}

if ($_GET['Action']==''){

	$Goods_id  = $FUNCTIONS->Value_Manage($_GET['gid'],'','back','');
	$Query   = $DB->query("select * from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (  g.bid=b.bid )  where b.catiffb=1 and g.ifpub=1 and g.gid=".intval($Goods_id)."  limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ( $Num==0 ){   //如果不存在资料
		$FUNCTIONS->header_location("index.php");
	}

	$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}collection_goods` where gid=".$Goods_id." and user_id=".$_SESSION['user_id']."  limit 0,1");
	$Nums   = $DB->num_rows($Querys);

	if ( $Nums==0 ) {  //如果不存在资料,就将执行插入操作
		$ifbelate = $PRODUCT->ViewGidBelate($Goods_id,1);
		if($ifbelate == 1){
			echo "<script>alert('請先至會員資訊填寫生日。');</script>";
		}else if($ifbelate == 2){
			echo "<script>alert('未滿18歲不得將酒類商品加入追蹤清單。');</script>";
		}else {
			$DB->query("insert into  `{$INFO[DBPrefix]}collection_goods` (gid,user_id) values('".intval($Goods_id)."','".intval($_SESSION['user_id'])."')");
			echo "<script>alert('商品已加入追蹤清單');</script>";
		}
	}else{
		$DB->query("delete from `{$INFO[DBPrefix]}collection_goods` where gid=".$Goods_id." and user_id=".$_SESSION['user_id']);
		echo "<script>alert('商品已移出追蹤清單');</script>";
	}
	$url = substr($_SERVER['HTTP_REFERER'],0,strripos($_SERVER['HTTP_REFERER'],"?"));
	$FUNCTIONS->header_location($url);
}

if ($_GET['Action']=='Insert'){

	$Goods_id  = $FUNCTIONS->Value_Manage($_GET['gid'],'','back','');
	$Query   = $DB->query("select * from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (  g.bid=b.bid )  where b.catiffb=1 and g.ifpub=1 and g.gid=".intval($Goods_id)."  limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ( $Num==0 ){   //如果不存在资料
		$FUNCTIONS->header_location("index.php");
	}

	$ifbelate = $PRODUCT->ViewGidBelate($Goods_id,1);
	if($ifbelate > 0){
		echo $ifbelate;
		exit;
	}

	$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}collection_goods` where gid=".$Goods_id." and user_id=".$_SESSION['user_id']."  limit 0,1");
	$Nums   = $DB->num_rows($Querys);

	if ( $Nums==0 ) {  //如果不存在资料,就将执行插入操作
		$DB->query("insert into  `{$INFO[DBPrefix]}collection_goods` (gid,user_id) values('".intval($Goods_id)."','".intval($_SESSION['user_id'])."')");
	}
}

if ($_GET['Action']=='Del'){
	$Goods_id  = $FUNCTIONS->Value_Manage($_GET['gid'],'','back','');
	$DB->query("delete from `{$INFO[DBPrefix]}collection_goods` where gid=".$Goods_id." and user_id=".$_SESSION['user_id']);
}
?>
