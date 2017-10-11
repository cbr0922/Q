<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/**
 *  特價商品g.ifspecial=1
 */
	$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.intro ,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,b.bid,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice  from `{$INFO[DBPrefix]}bclass` b left join `{$INFO[DBPrefix]}goods` g on ( g.bid=b.bid) where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.shopid=0 and g.ifrecommend=1  and g.ifbonus!=1 and g.ifpresent!=1 and g.ifxy!=1 and g.ifchange!=1 and b.catiffb=1 and g.ifgoodspresent!=1 order by g.goodorder asc,g.idate desc limit 0,3";
	
	$Query =    $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$j=0;
	while ( $RecPro = $DB->fetch_array($Query)){
			$Recommendation_productarray[$j][gid] = $RecPro['gid'];
			$Recommendation_productarray[$j][countj] = $j+1;
			$Recommendation_productarray[$j][goodsname] = $RecPro['goodsname']."".$FUNCTIONS->Storage($RecPro['ifalarm'],$RecPro['storage'],$RecPro['alarmnum']);
			$Recommendation_productarray[$j][price] = $RecPro['price'];
			$Recommendation_productarray[$j][pricedesc] = $RecPro['pricedesc'];
			$Recommendation_productarray[$j][bn] = $RecPro['bn'];
			$Recommendation_productarray[$j][smallimg] = $RecPro['smallimg'];
			if ($RecPro['iftimesale']==1 && $RecPro['timesale_starttime']<=time() && $RecPro['timesale_endtime']>=time()){
				$Recommendation_productarray[$j][pricedesc]  = $RecPro['saleoffprice'];
			}
			$j++;
	}

$tpl->assign("Recommendation_productarray",  $Recommendation_productarray);
$tpl->display("include_recproduct_s.html");
?>
