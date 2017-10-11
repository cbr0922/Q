<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/*
分類商品
*/
for($i=1;$i<=6;$i++){

	unset($Array_class);
	unset($Next_ArrayClass);
	unset($Add);
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($i);
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	
	foreach ($Array_class as $k=>$v){
		$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
	}

	
			$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.bid=".$i." ".$Add." and g.ifpub='1' and g.ifxy!=1  and g.ifbonus!='1' and g.ifpresent!=1 and g.shopid=0 and g.ifgoodspresent!=1 order by g.idate desc limit 0,4";
			$goods_Query    = $DB->query($goods_sql);
			$j = 0;
			while ($goods_Rs=$DB->fetch_array($goods_Query)) {
				$fl_goods_array[$i][$j]['goodsname'] = $goods_Rs['goodsname'];
				$fl_goods_array[$i][$j]['gid'] = $goods_Rs['gid'];
				$fl_goods_array[$i][$j]['price'] = $goods_Rs['price'];
				$fl_goods_array[$i][$j]['pricedesc'] = $goods_Rs['pricedesc'];
				$fl_goods_array[$i][$j]['smallimg'] = $goods_Rs['smallimg'];
				if ($goods_Rs['iftimesale']==1 && $goods_Rs['timesale_starttime']<=time() && $goods_Rs['timesale_endtime']>=time()){
					$fl_goods_array[$i][$j]['pricedesc']  = $goods_Rs['saleoffprice'];
				}
				$j++;
			}
			$tpl->assign("fl_goods_array_" . $i,$fl_goods_array[$i]);
}

$tpl->display("include_classproduct.html");
?>
