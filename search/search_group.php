<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

if (trim($_GET['skey'])!=""){
	$Skey = " ( g.groupname like '%".trim($_GET['skey'])."%' or  g.content like '%".trim($_GET['skey'])."%' or  g.groupbn like '%".trim($_GET['skey'])."%' ) and ";
}

if ($_GET['gprice_from']!=""){
	$Gprice =  " and (g.groupprice>=".floatval($_GET['gprice_from'])." and g.groupprice<=".floatval($_GET['gprice_to']).") ";
	//$Gprice =  " and (g.price>=".floatval($_GET['gprice_from'])." and g.price<=".floatval($_GET['gprice_to']).") ";
}

if ($_GET['bid']!="" && intval($_GET['SearchallSub'])==1){
	// $Bid = " and g.bid = ".intval($_GET['bid'])." ";
	$Bid = intval($_GET['bid']);
	$Next_ArrayClass  = $FUNCTIONS->Sun_groupcon_class($Bid);
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	//$Array_class      = $FUNCTIONS->array_unvalue($Next_ArrayClass) ;  //清除数组中重复值；
	foreach ($Array_class as $k=>$v){
		$Add .= $v!="" ? " or g.bid=".$v." " : "";
	}
	$Add_sql = "and (g.bid=".intval($_GET['bid'])." ".$Add." ) " ;
}

if ($_GET['bid']!="" && intval($_GET['SearchallSub'])==0){
	$Add_sql = "and g.bid=".intval($_GET['bid'])." " ;
}

if (intval($_GET['bid'])==0){
	$Add_sql = "";
}





include("PageNav.class.php");



$Sql = "select g.* from `{$INFO[DBPrefix]}groupdetail` as g where ".$Skey." g.ifpub=1   ".$Gprice."  ".$Add_sql."  ".$sBrand."   order by g.view_num desc ";

$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;
$sale_p_array = array();
if ($Num>0){
	$i=0;
	$j=1;
	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$sale_p_array[$i][groupname] = $ProNav['groupname'];
		$sale_p_array[$i][groupbn] = $ProNav['groupbn'];
		$sale_p_array[$i][gdid] = $ProNav['gdid'];
		$sale_p_array[$i][price] = $ProNav['price'];
		$sale_p_array[$i][groupprice] = $ProNav['groupprice'];
		$sale_p_array[$i][grouppoint] = $ProNav['grouppoint'];
		$sale_p_array[$i][smallimg] = $ProNav['groupSimg'];
		$i++;
		$j++;
	}
}

$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条
$tpl->assign("sale_p_array",       $sale_p_array);
$tpl->assign($Good);
$tpl->display("search_group.html");
?>