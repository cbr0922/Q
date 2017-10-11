<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../../configs.inc.php");
include ("global.php");


if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
  $FUNCTIONS->header_location( "../../member/login_windows.php");
}


if ($_GET['Action']=='Insert'){

$Goods_id  = $FUNCTIONS->Value_Manage($_GET['gid'],'','back','');
$Query   = $DB->query("select * from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (  g.bid=b.bid )  where b.catiffb=1 and g.ifpub=1  and g.ifbonus=1 and g.gid=".intval($Goods_id)."  limit 0,1");
$Num   = $DB->num_rows($Query);
 
if ( $Num==0 ){   //如果不存在资料
 $FUNCTIONS->header_location("index.php");
}
 



$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}bonuscollection_goods` where gid=".$Goods_id." and user_id=".$_SESSION['user_id']."  limit 0,1");
$Nums   = $DB->num_rows($Querys);
 
 if ( $Nums==0 ) {  //如果不存在资料,就将执行插入操作
 $DB->query("insert into  `{$INFO[DBPrefix]}bonuscollection_goods` (gid,user_id) values('".intval($Goods_id)."','".intval($_SESSION['user_id'])."')");
 }

}

if ($_GET['Action']=='Del'){
$collection_id  = $FUNCTIONS->Value_Manage($_GET['collection_id'],'','back','');
$DB->query("delete from `{$INFO[DBPrefix]}bonuscollection_goods` where collection_id=".intval($collection_id));
}
$FUNCTIONS->header_location( "../../member/BonusCollection_list.php");

?>
