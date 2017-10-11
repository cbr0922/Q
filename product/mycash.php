<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include_once ("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
include("PageNav.class.php");

$Sql     = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.present_money,g.alarmnum from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass`  b on (g.bid=b.bid ) where  b.catiffb=1 and g.ifpub=1 and g.ifpresent=1 order by g.present_money asc,g.goodorder asc,g.idate desc ";
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
		$ProNav_Rs[$i]['goodsname']  = $ProNav['goodsname'];
		$ProNav_Rs[$i]['present_money']      = $ProNav['present_money'] ;
		$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
		$ProNav_Rs[$i]['smallimg']   = $ProNav['smallimg'] ;
		$ProNav_Rs[$i]['intro']      = $ProNav['intro'];
	
	
		$i++;
		$j++;
	}
	$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条


}
$tpl->assign("present_array",$ProNav_Rs);
$tpl->assign($Good);
$tpl->display("mycash.html");

?>