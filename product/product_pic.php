<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");


$Goods_id  = $FUNCTIONS->Value_Manage($_GET['goods_id'],'','back','');


$Query   = $DB->query("select g.gimg,g.bigimg,g.goodsname,g.smallimg from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where  b.catiffb=1 and g.ifpub=1 and g.gid=".intval($Goods_id)." limit 0,1");
$Num   = $DB->num_rows($Query);


if ($Num>0){
	$Result_goods = $DB->fetch_array($Query);

	$Big_pic      = $Result_goods['bigimg']!="" ?  $INFO['site_url']."/".$INFO['good_pic_path']."/".$Result_goods['bigimg'] : "";
	$Goodsname    = $Result_goods['goodsname'];
	$Big_pic      = $Big_pic!="" ? $Big_pic :  $INFO['site_url']."/".$INFO['good_pic_path']."/".$Result_goods['smallimg']  ;
}
//$DB->free_result($Query);


$Sql_pic    = "select goodpic_name from `{$INFO[DBPrefix]}good_pic` where goodpic_name<>'' and good_id=".intval($Goods_id);
$Query_pic  = $DB->query($Sql_pic);
$Num_pic    = $DB->num_rows($Query_pic);
 $Goodpic[0]['pic'] =   $Result_goods['bigimg'];
$Goodpic[0]['title'] =   '';
$i = 1;
if ($Num_pic>0){
	while ($Result_pic = $DB->fetch_array($Query_pic))  {
		$Goodpic[$i]['pic'] =   $Result_pic['goodpic_name'];
		$Goodpic[$i]['title'] =   $Result_pic['goodpic_title'];
		$i++;
	}
}


//开始赋值
$tpl->assign("Goodsname",      $Goodsname);
$tpl->assign("Big_pic",        $Big_pic);
$tpl->assign("Goodpic",        $Goodpic);
$tpl->assign("Close",          $Basic_Command['Close']); //关闭


$tpl->display("product_pic.html");exit;
?>
