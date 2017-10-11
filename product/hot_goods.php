<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Good.php");


//热卖商品 ---这里抛出了商品名称、价格、ID三个变量，以方便页面控制！

$Sql = "select g.gid,g.goodsname,g.price,g.ifalarm,g.storage,g.alarmnum,g.smallimg,g.middleimg from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where  b.catiffb=1 and  g.ifhot=1 and  g.ifpub=1 and g.ifjs!=1 and  g.ifbonus!=1  order by g.goodorder asc,g.idate desc  limit 0,{$INFO['MaxProductNumForList']}";
$Query = $DB->query($Sql);
$i=0;
$j=1;
while ( $HotRs = $DB->fetch_array($Query)){
	$HotProduct[$i]['goodsname']  = $HotRs['goodsname']."".$FUNCTIONS->Storage($HotRs['ifalarm'],$HotRs['storage'],$HotRs['alarmnum']);
	$HotProduct[$i]['price']      = $HotRs['price'] ;
	$HotProduct[$i]['smallimg']   = $HotRs['smallimg'] ;
	$HotProduct[$i]['middleimg']  = $HotRs['middleimg'] ;
	$HotProduct[$i]['gid']        = $HotRs['gid'] ;

	$tpl->assign("HotProduct_name".$j,       $HotProduct[$i]['goodsname']);
	$tpl->assign("HotProduct_price".$j,      $HotProduct[$i]['price']);
	$tpl->assign("HotProduct_id".$j,         $HotProduct[$i]['gid']);
	$tpl->assign("HotProduct_smallimg".$j,   $HotProduct[$i]['smallimg']);
	$tpl->assign("HotProduct_middleimg".$j,  $HotProduct[$i]['middleimg']);


	$HotProduct_staticHtml[$i][Url]       = $INFO[site_url]."/HTML_C/product_".$HotProduct[$i]['gid'].".html";
	$HotProduct_staticHtml[$i][goodsname] = $HotProduct[$i]['goodsname'];

	$i++;
	$j++;
}

$tpl->assign("HotProduct",        $HotProduct); //热卖商品


/*

if ($INFO['staticState']=='open'){
	for ($i=0;$i<=10;$i++){
		$HotProduct_staticHtml[$i][Url]       = $INFO[site_url]."/HTML_C/product_".$HotProduct[$i]['gid'].".html";
		$HotProduct_staticHtml[$i][goodsname] = $HotProduct[$i]['goodsname'];
		//$tpl->assign("HotProduct_staticHtm".intval($i+1),  $INFO[site_url]."/HTML_C/product_".$HotProduct[$i]['gid'].".html"); //热卖商品静态页面
	}
}
*/
$tpl->assign("staticState",  $INFO['staticState']); //是否开启了静态文件
$tpl->assign("HotProduct_staticHtml",  $HotProduct_staticHtml); //是否开启了静态文件
unset($Sql);
unset($Query);

$tpl->assign($Good);
$tpl->display("hot_goods.html");


?>
