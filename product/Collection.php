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
	$FUNCTIONS->header_location("../member/login_windows.php?Url=" . urlencode("../product/Collection.php?gid=" . $_GET['gid'] . "&Action=" . $_GET['Action']));
}


if ($_GET['Action']=='Insert'){

	$Goods_id  = $FUNCTIONS->Value_Manage($_GET['gid'],'','back','');
	$Query   = $DB->query("select * from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (  g.bid=b.bid )  where b.catiffb=1 and g.ifpub=1 and g.gid=".intval($Goods_id)."  limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ( $Num==0 ){   //如果不存在资料
		$FUNCTIONS->header_location("index.php");
	}

	$ifbelate = $PRODUCT->ViewGidBelate($Goods_id);

	$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}collection_goods` where gid=".$Goods_id." and user_id=".$_SESSION['user_id']."  limit 0,1");
	$Nums   = $DB->num_rows($Querys);

	if ( $Nums==0 ) {  //如果不存在资料,就将执行插入操作
		$DB->query("insert into  `{$INFO[DBPrefix]}collection_goods` (gid,user_id) values('".intval($Goods_id)."','".intval($_SESSION['user_id'])."')");
	}

}

if ($_GET['Action']=='Del'){
	$collection_id  = $FUNCTIONS->Value_Manage($_GET['collection_id'],'','back','');
	$DB->query("delete from `{$INFO[DBPrefix]}collection_goods` where collection_id=".intval($collection_id));
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$FUNCTIONS->header_location("../member/Collection_list.php");

?>
