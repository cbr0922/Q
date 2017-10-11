<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/**
 *  特价商品
 */

$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid  ) where  b.catiffb=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifpresent!=1 and g.ifspecial=1 and g.ifbonus!=1 and g.ifxy!=1 and b.catiffb=1 and g.shopid=0 and g.ifgoodspresent!=1 order by g.goodorder asc,g.idate desc limit 0,3";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
$j=0;
$Sql_level = "";
$SpecialOffer_productarray_level = array();

while ( $NewPro = $DB->fetch_array($Query)){
	$New_productarray[$j][gid] = $NewPro['gid'];
			$New_productarray[$j][countj] = $j+1;
			$New_productarray[$j][goodsname] = $NewPro['goodsname']."".$FUNCTIONS->Storage($NewPro['ifalarm'],$NewPro['storage'],$RecPro['alarmnum']);
			$New_productarray[$j][price] = $NewPro['price'];
			$New_productarray[$j][pricedesc] = $NewPro['pricedesc'];
			$New_productarray[$j][bn] = $NewPro['bn'];
			$New_productarray[$j][smallimg] = $NewPro['smallimg'];
			if ($NewPro['iftimesale']==1 && $NewPro['timesale_starttime']<=time() && $NewPro['timesale_endtime']>=time()){
				$New_productarray[$j][pricedesc]  = $NewPro['saleoffprice'];
			}
			$j++;
}
$tpl->assign("New_productarray",  $New_productarray);
$tpl->display("include_specialproduct.html");
?>
