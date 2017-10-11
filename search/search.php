<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Search_Pack.php");
include(RootDocument."/language/".$INFO['IS']."/Good.php");



//开始匹配搜索结果
//关键字将同时匹配产品简介中的相关资料
if (trim($_GET['skey'])!=""){
	$Skey = " ( g.goodsname like '%".trim($_GET['skey'])."%' or  g.intro like '%".trim($_GET['skey'])."%' or  g.bn like '%".trim($_GET['skey'])."%' or  g.keywords like '%".$_GET['skey']."%' ) and ";
}

if ($_GET['gprice_from']!=""){
	$Gprice =  " and (g.pricedesc>=".floatval($_GET['gprice_from'])." and g.pricedesc<=".floatval($_GET['gprice_to']).") ";
	//$Gprice =  " and (g.price>=".floatval($_GET['gprice_from'])." and g.price<=".floatval($_GET['gprice_to']).") ";
}

if ($_GET['bid']!="" && intval($_GET['SearchallSub'])==1){
	// $Bid = " and g.bid = ".intval($_GET['bid'])." ";
	$Bid = intval($_GET['bid']);
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($Bid);
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



if (trim($_GET['Brand_id']!="")){

	$sBrand = " and g.brand_id ='".trim($_GET['Brand_id'])."' ";
}

$Sql = "select g.goodsname,g.keywords,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.gid,g.bid,g.ifalarm,g.storage,g.alarmnum,g.js_begtime,g.js_endtime,g.ifjs from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where ".$Skey."  b.catiffb=1 and g.ifpub=1  ".$Gprice." ".$Add_sql."  ".$sBrand." and g.ifpresent!=1 and g.ifxy!=1 and g.ifchange!=1  and g.ifbonus!='1' "; //最终匹配出来的SQL语句
if ($_GET['orderby']=="price")
	$Sql        .= "  order by g.pricedesc ";
elseif ($_GET['orderby']=="pubtime")
	$Sql        .= "  order by g.idate ";
elseif ($_GET['orderby']=="visit")
	$Sql        .= "  order by g.view_num ";
else
	$Sql        .= "  order by g.goodorder asc,g.idate ";
if ($_GET['ordertype']=="1"){
	$_GET['ordertype'] = 0;
	$Sql        .= " asc";	
}else{
	$Sql        .= " desc";
	$_GET['ordertype'] = 1;
}
include("PageNav.class.php");

$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;

if ($Num>0){
	$i=0;
	$j=1;

	while ( $ProNav = $DB->fetch_array($arrRecords)){

		if ((intval($ProNav['ifjs'])==1 && $ProNav['js_begtime']<=date("Y-m-d",time()) && $ProNav['js_endtime']>=date("Y-m-d",time()))  || (intval($ProNav['ifjs'])!=1)){
			$tpl->assign("ProNav_gid".$j,        intval($ProNav['gid'])); //最新商品一ID
			$tpl->assign("ProNav_goodsname".$j,  $ProNav['goodsname']."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum'])); //商品一名称
			$tpl->assign("ProNav_price".$j,      $ProNav['price']);     //商品一价格
			$tpl->assign("ProNav_pricedesc".$j,  $ProNav['pricedesc']); //商品一价格
			$tpl->assign("ProNav_bn".$j,         $ProNav['bn']);        //商品一编号
			$tpl->assign("ProNav_img".$j,        $ProNav['smallimg']);  //商品一图片
			$tpl->assign("ProNav_intro".$j,      $ProNav['intro']);     //商品一内容
			//print_r($ProNav);echo $j . "<br>";
			$j++;
			
		}

		//$i++;
		//$j++;
	}

	$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条
}


$tpl->assign("productNum",intval($Num));

//屬性值
$Sql      = "select * from `{$INFO[DBPrefix]}attributevalue` as v inner join `{$INFO[DBPrefix]}attribute` as a on a.attrid=v.attrid  where v.value like '%" . trim($_GET['skey']) . "%' order by valueid desc ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$attrvalue_array = array();
$i = 0;
while ($Rs=$DB->fetch_array($Query)) {
	$attrvalue_array[$i]['value'] = $Rs['value'];
	$attrvalue_array[$i]['attrid'] = $Rs['attrid'];
	$attrvalue_array[$i]['valueid'] = $Rs['valueid'];
	$i++;
}
$tpl->assign("attrvalue_array",$attrvalue_array);

$tpl->assign($Search_Pack);
if (trim($_GET['skey'])!=""){
	$tpl->assign('SearchResult_say', str_replace("#Key#","&nbsp;\"".trim($_GET['skey'])."\"&nbsp;",$Search_Pack[SearchResult_say]));
}else{
	$tpl->assign('SearchResult_say', str_replace("#Key#","",$Search_Pack[SearchResult_say]));
}
$tpl->assign($Good);
$tpl->assign("skey",$_GET['skey']);
$tpl->display("search.html");
?>




