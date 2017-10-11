<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");
include "../language/".$INFO['IS']."/Good.php";

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/*
熱賣商品
*/
$bid = intval($_GET['bid']);
if ($bid>0){
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($bid);
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	
	foreach ($Array_class as $k=>$v){
		$Addall .= trim($v)!="" && intval($v)>0 ? " or g.bid='".intval($v)."' " : "";
	}
	$where = " and (g.bid='" . $bid . "' " . $Addall . ")";	
}

$Sql = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.salename_color  from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid) where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifbonus!=1 and g.ifhot=1 and g.ifpresent!=1 and g.ifxy!=1 and b.catiffb=1 and g.ifchange!=1 and g.shopid=0 and g.ifgoodspresent!=1 " . $where . " order by RAND() limit 0,2";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
$num=0;
while ($Hot_Rs=$DB->fetch_array($Query)) {
	$Hot_product[$i][gid] = $Hot_Rs['gid'];
	$Hot_product[$i][goodsname] = $Char_class->cut_str($Hot_Rs['goodsname'],18,0,'UTF-8');
	$Hot_product[$i][smallimg] = $Hot_Rs['smallimg'];
	$Hot_product[$i][pricedesc] = $Hot_Rs['pricedesc'];
	$Hot_product[$i][sale_name] = $Hot_Rs['salename_color']==""?$Hot_Rs['sale_name']:"<font color='" . $Hot_Rs['salename_color'] . "'>" . $Char_class->cut_str($Hot_Rs['sale_name'],15,0,'UTF-8') . "</font>";
	
	//$Hot_product[$i][sale_name] = $Hot_Rs['salename_color']==""?$Hot_Rs['sale_name']:"<font color='" . $Hot_Rs['salename_color'] . "'>" . $Hot_Rs['sale_name'] . "</font>";
	if ($Hot_Rs['iftimesale']==1 && $Hot_Rs['timesale_starttime']<=time() && $Hot_Rs['timesale_endtime']>=time()){
		$Hot_product[$i][pricedesc]  = $Hot_Rs['saleoffprice'];
	}
	/*
	$tpl->assign("Hot_product_gid_" . $i,$Hot_product[$i][gid]);
	$tpl->assign("Hot_product_goodsname_" . $i,$Hot_product[$i][goodsname]);
	$tpl->assign("Hot_product_smallimg_" . $i,$Hot_product[$i][smallimg]);
	*/
	$i++;
}
//print_r($Hot_product);
$tpl->assign("Hot_product",$Hot_product);
$tpl->assign($Good);
$tpl->display("include_hotproduct_art.html");
?>
