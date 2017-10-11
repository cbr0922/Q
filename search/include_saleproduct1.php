<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
if (is_file("configs.inc.php")){
 include("./configs.inc.php");
}elseif (is_file("../configs.inc.php")){
 include("../configs.inc.php");
}
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;
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

/*
人氣商品
*/


$Sql = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage  from `{$INFO[DBPrefix]}goods` g  left join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid)where g.ifpub=1 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifpresent!=1 and g.ifxy!=1 and b.catiffb=1 " . $where . " order by g.salenum desc,g.gid desc limit 0,10";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
$num=0;
while ($View_Rs=$DB->fetch_array($Query)) {
	$View_product[$i][gid] = $View_Rs['gid'];
	//$View_product[$i][goodsname] = $Char_class->cut_str($View_Rs['goodsname'],14,0,'UTF-8');
	$View_product[$i][goodsname] = $View_Rs['goodsname'];
	$View_product[$i][intro] = $Char_class->cut_str($View_Rs['intro'],14,0,'UTF-8');
	$View_product[$i][viewnum] = $View_Rs['viewnum'];
	//$View_product[$i][sale_name] = $View_Rs['sale_name'];
	$View_product[$i][sale_name] = $View_Rs['salename_color']==""?$View_Rs['sale_name']:"<font color='" . $View_Rs['salename_color'] . "'>" . $View_Rs['sale_name'] . "</font>";
	$View_product[$i][pricedesc] = $View_Rs['pricedesc'];
	$View_product[$i][price] = $View_Rs['price'];
	$View_product[$i][smallimg] = $View_Rs['smallimg'];
	if ($View_Rs['iftimesale']==1 && $View_Rs['timesale_starttime']<=time() && $View_Rs['timesale_endtime']>=time()){
		$viewProductArray[$i]['pricedesc']  = $View_Rs['saleoffprice'];
	}
	
	$tpl->assign("View_product_gid_" . ($i+1),$View_product[$i][gid]);
	$tpl->assign("View_product_sale_name_" . ($i+1),$View_product[$i][sale_name]);
	$tpl->assign("View_product_goodsname_" . ($i+1),$View_product[$i][goodsname]);
    $tpl->assign("View_product_intro_" . ($i+1),$View_product[$i][intro]);
	$tpl->assign("View_product_pricedesc_" . ($i+1),$View_product[$i][pricedesc]);
	$tpl->assign("View_product_price_" . ($i+1),$View_product[$i][price]);
	$tpl->assign("View_product_smallimg_" . ($i+1),$View_product[$i][smallimg]);
	$i++;
}
$tpl->display("include_saleproduct1.html");
?>