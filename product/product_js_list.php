<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");
include("PageNav.class.php");
echo $Sql        = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.js_begtime,g.js_endtime,g.js_totalnum,g.js_price,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1  and g.ifbonus!=1 and g.ifjs=1 and g.js_begtime <= CURDATE() and g.js_endtime > CURDATE()      order by g.goodorder asc,g.idate  desc  ";
$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$Num        = $PageNav->iTotal;

$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

if ($Num>0){
	$arrRecords = $PageNav->ReadList();
	$i=0;
	$Js_array = array();
	while ( $ProNav = $DB->fetch_array($arrRecords)){
		//下边将输出产品资料
		$tpl->assign("ProNav_gid".$j,         intval($ProNav['gid'])); //最新商品ID
		$tpl->assign("ProNav_goodsname".$j,   $ProNav['goodsname']."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum'])); //商品名称
		$tpl->assign("ProNav_price".$j,       $ProNav['price']);     //商品价格
		$tpl->assign("ProNav_bn".$j,          $ProNav['bn']);        //商品编号
		$tpl->assign("ProNav_img".$j,         $ProNav['smallimg']);  //商品图片
		$tpl->assign("ProNav_intro".$j,       $ProNav['intro']);     //商品内容

		$tpl->assign("js".$j."_begtime",    $ProNav['js_begtime']);     //商品开始时间
		$tpl->assign("js".$j."_endtime",    $ProNav['js_endtime']);     //商品结束时间
		$tpl->assign("js".$j."_totalnum",   $ProNav['js_totalnum']);     //商品总购买人数
		$Js_array = explode("||",           $ProNav['js_price']);
		$tpl->assign("js".$j."_num1",       $Js_array[0]);     //商品起購價
		$tpl->assign("js".$j."_num5",       $Js_array[4]);     //商品集殺價

		$i++;
		$j++;
	}

}

$tpl->assign($Good);
$tpl->display("product_js_list.html");
?>