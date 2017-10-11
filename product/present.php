<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include_once ("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
include("PageNav.class.php");

$Sql     = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.present_endmoney,g.present_money from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass`  b on (g.bid=b.bid ) where  b.catiffb=1 and g.ifpub=1  and  g.ifpresent=1 ";
if (intval($_GET['price'])>0)
	$Sql     .= " and present_money>='" . intval($_GET['price']) . "'";
	if (intval($_GET['endprice'])>0)
	$Sql     .= " and present_endmoney<='" . intval($_GET['endprice']) . "'";
$Sql     .= " order by g.present_money,g.goodorder asc,g.idate desc ";
$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;

if ($Num>0){
	$i=0;
	$j=1;
	$Sql_level = "";
	$ProNav_array_level = array();
	
	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
		$ProNav_Rs[$i]['goodsname']  = $ProNav['goodsname']."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
		$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
		$ProNav_Rs[$i]['smallimg']   = $ProNav['smallimg'] ;
		$ProNav_Rs[$i]['intro']      = $ProNav['intro'];
		$ProNav_Rs[$i]['present_endmoney']      = $ProNav['present_endmoney'];
		$ProNav_Rs[$i]['present_money']      = $ProNav['present_money'];
	
	
		$i++;
		$j++;
	}
	$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条


}
$i = 0;
//額滿禮區間
$Sql     = "select distinct g.present_endmoney,g.present_money from `{$INFO[DBPrefix]}goods` g where g.present_endmoney>0 and g.present_endmoney>0 and g.ifpub=1  and  g.ifpresent=1 group by g.present_endmoney,g.present_money order by g.present_money";
$Query    = $DB->query($Sql);
while ($Rs=$DB->fetch_array($Query)) {
	$present_area[$i]['present_endmoney'] = $Rs['present_endmoney'];
	$present_area[$i]['present_money'] = $Rs['present_money'];
	$i++;
}
$tpl->assign("present_area",$present_area);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("ProNav_Rs",$ProNav_Rs);
$tpl->assign($Good);
$tpl->display("present.html");

?>