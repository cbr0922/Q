<?php
error_reporting(7);
include( dirname(__FILE__)."/"."../configs.inc.php" );
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Good.php");

@header("Content-type: text/html; charset=utf-8");

$Sql      = "select * from `{$INFO[DBPrefix]}subject` where subject_open=1 order by subject_id ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i=0;
while ($Rs=$DB->fetch_array($Query)) {
	$subject_array[$i]['id'] = $Rs['subject_id'];
	$subject_array[$i]['subject_name'] = $Rs['subject_name'];
	$i++;
}

//集杀商品 ---这里抛出了商品名称、价格、ID三个变量，以方便页面控制！
/*
$Sql = "select g.gid,g.goodsname,g.price,g.ifalarm,g.storage,g.alarmnum from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where  b.catiffb=1 and g.ifjs!=1  and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1  and  g.ifpub=1 and g.subject_id!=0 order by g.goodorder asc,g.idate desc  limit 0,{$INFO['MaxProductNumForList']}";
$Query = $DB->query($Sql);
$i=0;
$j=1;
$JsProduct = array();
while ( $JsRs = $DB->fetch_array($Query)){
	$JsProduct[$i][JsProduct_name]   = $JsRs['goodsname']."".$FUNCTIONS->Storage($JsRs['ifalarm'],$JsRs['storage'],$JsRs['alarmnum']);
	$JsProduct[$i][JsProduct_price]  = $JsRs['price'];
	$JsProduct[$i][JsProduct_id]     = $JsRs['gid'];

	if ($INFO['staticState']=='open'){
		$JsProduct_staticHtml[$i][Url]       = $INFO[site_url]."/HTML_C/product_".$JsRs['gid'].".html";
		$JsProduct_staticHtml[$i][JsProduct_name] = $JsRs['goodsname']."".$FUNCTIONS->Storage($JsRs['ifalarm'],$JsRs['storage'],$JsRs['alarmnum']);
	}
	$i++;
	$j++;
}

*/
/*
if ($INFO['staticState']=='open'){
	for ($i=0;$i<10;$i++){
		$JsProduct_staticHtml[$i][Url]       = $INFO[site_url]."/HTML_C/product_".$JsProduct[$i]['gid'].".html";
		$JsProduct_staticHtml[$i][goodsname] = $JsProduct[$i]['goodsname'];
	}
	$tpl->assign("JsProduct_staticHtml",  $INFO[site_url]."/HTML_C/product_".$JsProduct[0]['gid'].".html"); //热卖商品静态页面
	$tpl->assign("JsProduct_staticHtm2",  $INFO[site_url]."/HTML_C/product_".$JsProduct[1]['gid'].".html"); //..
	$tpl->assign("JsProduct_staticHtm3",  $INFO[site_url]."/HTML_C/product_".$JsProduct[2]['gid'].".html"); //..
	$tpl->assign("JsProduct_staticHtm4",  $INFO[site_url]."/HTML_C/product_".$JsProduct[3]['gid'].".html"); //..
	$tpl->assign("JsProduct_staticHtm5",  $INFO[site_url]."/HTML_C/product_".$JsProduct[4]['gid'].".html"); //热卖商品静态页面
}
*/

$tpl->assign("subject_array",    $subject_array); //集杀商品


$tpl->assign($Good);
$tpl->display("subject_goods.html");


?>
