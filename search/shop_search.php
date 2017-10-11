<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Search_Pack.php");
include(RootDocument."/language/".$INFO['IS']."/Good.php");



//开始匹配搜索结果
//关键字将同时匹配产品简介中的相关资料

if($_GET['type']==2){
	if (trim($_GET['skey'])!=""){
		$Skey = " ( g.goodsname like '%".trim($_GET['skey'])."%' or  g.intro like '%".trim($_GET['skey'])."%' or  g.bn like '%".trim($_GET['skey'])."%' ) and ";
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
	
	$Sql = "select g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.gid,g.bid,g.ifalarm,g.storage,g.alarmnum,g.js_begtime,g.js_endtime,g.ifjs from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}shopbclass` b on (g.bid=b.bid) where ".$Skey."  b.catiffb=1 and g.ifpub=1  ".$Gprice." ".$Add_sql."  ".$sBrand." and g.ifpresent!=1 and g.ifxy!=1 and g.ifchange!=1  and g.ifbonus!='1' and g.shopid<>0 order by g.goodorder asc,g.idate desc"; //最终匹配出来的SQL语句
	
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
	$tpl->display("shop_search.html");
}else{
	if (trim($_GET['skey'])!=""){
		$Skey = " (s.shopname like '%".trim($_GET['skey'])."%' or  s.content like '%".trim($_GET['skey'])."%') and ";
	}	
		$Sql = "select s.*,u.username from `{$INFO[DBPrefix]}shopinfo` s inner join `{$INFO[DBPrefix]}user` u on (s.uid=u.user_id) where ".$Skey."   s.state=1 order by s.sid desc"; //最终匹配出来的SQL语句
	
	include("PageNav.class.php");
	
	$PageNav = new PageItem($Sql,20);
	
	$Num     = $PageNav->iTotal;
	
	if ($Num>0){
		$arrRecords = $PageNav->ReadList();
		$i=0;
		$shop_array = array();
	
		while ( $ProNav = $DB->fetch_array($arrRecords)){
			$shop_array[$i][shopname] = $ProNav['shopname'];
			$shop_array[$i][username] = $ProNav['username'];
			$shop_array[$i][content] = $ProNav['content'];
			$shop_array[$i][shoppic] = $ProNav['shoppic'];
			$shop_array[$i]['sid'] = $ProNav['sid'];
			$i++;
		}
	
		$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条
	}
	$tpl->assign("shop_array",$shop_array);
	$tpl->assign("productNum",intval($Num));
	$tpl->display("shop_list_search.html");
}
?>




