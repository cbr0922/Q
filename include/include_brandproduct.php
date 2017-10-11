<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/*
品牌推薦
*/

for ($i=0;$i<4;$i++){
$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.intro ,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,b.brand_id,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage  from `{$INFO[DBPrefix]}brand` b left join `{$INFO[DBPrefix]}goods` g on ( g.brand_id=b.brand_id) where g.ifpub=1 and g.ifrecommend=1  and g.ifbonus!=1 and g.ifxy!=1 and g.ifgoodspresent!=1 and b.brand_id ='" . ($i+1) . "' order by g.goodorder asc,g.idate desc";

	$Query =    $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$j=0;
	$Brand_productarray[$i][brand_id] = $i+1;
	while ( $RecPro = $DB->fetch_array($Query)){
	if ($j<3){
			$Brand_productarray[$i][goods][$j][brand_id] = $RecPro['brand_id'];
			$Brand_productarray[$i][goods][$j][gid] = $RecPro['gid'];
			$Brand_productarray[$i][goods][$j][countj] = $j+1;
			$Brand_productarray[$i][goods][$j][goodsname] = $RecPro['goodsname']."".$FUNCTIONS->Storage($RecPro['ifalarm'],$RecPro['storage'],$RecPro['alarmnum']);
			$Brand_productarray[$i][goods][$j][price] = $RecPro['price'];
			$Brand_productarray[$i][goods][$j][pricedesc] = $RecPro['pricedesc'];
			$Brand_productarray[$i][goods][$j][bn] = $RecPro['bn'];
			$Brand_productarray[$i][goods][$j][smallimg] = $RecPro['smallimg'];
			if ($RecPro['iftimesale']==1 && $RecPro['timesale_starttime']<=time() && $RecPro['timesale_endtime']>=time()){
					$Brand_productarray[$i][goods][$j]['pricedesc']  = $RecPro['saleoffprice'];
				}
			$j++;
		}
	}
}
sort($Brand_productarray);

$tpl->assign("Brand_productarray",  $Brand_productarray);

$tpl->display("include_brandproduct.html");
?>
