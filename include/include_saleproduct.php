<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");
include_once ("Char.class.php");
$Char_Class = new Char_class();

/*
人氣商品
*/

$Sql = "select * from `{$INFO[DBPrefix]}salegoodslist` as s left join `{$INFO[DBPrefix]}goods` g on s.gid=g.gid where s.bid=0 order by s.sid asc limit 0,10";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
$num=0;
while ($View_Rs=$DB->fetch_array($Query)) {
		$View_product[$i][gid] = $View_Rs['gid'];
		$View_product[$i][goodsname] = $Char_class->cut_str($View_Rs['goodsname'],14,0,'UTF-8');
		$View_product[$i][intro] = $Char_class->cut_str($View_Rs['intro'],14,0,'UTF-8');
		$View_product[$i][viewnum] = $View_Rs['viewnum'];
		//$View_product[$i][sale_name] = $View_Rs['sale_name'];
		$View_product[$i][sale_name] = $View_Rs['salename_color']==""?$View_Rs['sale_name']:"<font color='" . $View_Rs['salename_color'] . "'>" . $View_Rs['sale_name'] . "</font>";
		$View_product[$i][pricedesc] = $View_Rs['pricedesc'];
		$View_product[$i][smallimg] = $View_Rs['smallimg'];
		$View_product[$i][price] = $View_Rs['price'];
		if ($View_Rs['iftimesale']==1 && $View_Rs['timesale_starttime']<=time() && $View_Rs['timesale_endtime']>=time()){
			$viewProductArray[$i]['pricedesc']  = $View_Rs['saleoffprice'];
		}
	$tpl->assign("View_product",$View_product);
	$i++;
	//print_r($View_Rs);
}

if($Num==0){
	$Sql = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.salename_color  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid  ) where g.ifpub=1 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1 and g.ifxy!=1 and g.shopid=0 and b.catiffb=1 " . $where . " order by g.view_num desc limit 0,10";
	$Query =    $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$i=0;
	$num=0;
	while ($View_Rs=$DB->fetch_array($Query)) {
		$View_product[$i][gid] = $View_Rs['gid'];
		$View_product[$i][goodsname] = $Char_class->cut_str($View_Rs['goodsname'],14,0,'UTF-8');
		$View_product[$i][intro] = $Char_class->cut_str($View_Rs['intro'],14,0,'UTF-8');
		$View_product[$i][viewnum] = $View_Rs['viewnum'];
		//$View_product[$i][sale_name] = $View_Rs['sale_name'];
		$View_product[$i][sale_name] = $View_Rs['salename_color']==""?$View_Rs['sale_name']:"<font color='" . $View_Rs['salename_color'] . "'>" . $View_Rs['sale_name'] . "</font>";
		$View_product[$i][pricedesc] = $View_Rs['pricedesc'];
		$View_product[$i][smallimg] = $View_Rs['smallimg'];
		$View_product[$i][price] = $View_Rs['price'];
		if ($View_Rs['iftimesale']==1 && $View_Rs['timesale_starttime']<=time() && $View_Rs['timesale_endtime']>=time()){
			$viewProductArray[$i]['pricedesc']  = $View_Rs['saleoffprice'];
		}
		//$price = $FUNCTIONS->getBuyMorePrice($View_Rs['gid']);
		//if($price>0)
			//$viewProductArray[$i]['pricedesc'] = $price;

		$tpl->assign("View_product",$View_product);
		$i++;

	}
}//print_r($View_product);
$tpl->assign("View_product_Num",  $Num);
$tpl->display("include_saleproduct.html");
?>