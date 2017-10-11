<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
//include "../language/".$INFO['IS']."/Good.php";

if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
	$FUNCTIONS->header_location("../member/login_windows.php?Url=" . urlencode("../product/waitbuy.php?Action=Insert&gid=" . $_GET['gid']));
}
//print_r($_GET);
//exit;

//if ($_GET['Action']=='Insert'){

	$Goods_id  = $FUNCTIONS->Value_Manage($_GET['gid'],'','back','');
	$Query   = $DB->query("select * from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (  g.bid=b.bid )  where b.catiffb=1 and g.ifpub=1 and g.gid=".intval($Goods_id)."  limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ( $Num==0 ){   //如果不存在资料
		$FUNCTIONS->header_location("index.php");
		exit;
	}

	$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}waitbuy` where gid=".$Goods_id." and user_id=".$_SESSION['user_id']."  limit 0,1");
	$Nums   = $DB->num_rows($Querys);

	if ( $Nums<=0 ) {  //如果不存在资料,就将执行插入操作
		$DB->query("insert into  `{$INFO[DBPrefix]}waitbuy` (gid,user_id,pubtime) values('".intval($Goods_id)."','".intval($_SESSION['user_id'])."','" . time() . "')");
		echo "<script language='javascript'>alert('商品已經為您列入貨到通知紀錄');location.href='../product/goods_detail.php?goods_id=" . $_GET['gid'] . "';</script>";
	}
echo "<script language='javascript'>alert('商品已經為您列入貨到通知紀錄');location.href='../product/goods_detail.php?goods_id=" . $_GET['gid'] . "';</script>";
//}


?>