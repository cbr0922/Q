<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");
include "./language/".$INFO['IS']."/Good.php";

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/*
聚合商品
*/
$Sql = "select * from `{$INFO[DBPrefix]}goodscollection` where ifshop=1 ";
$Query    = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i = 0;
if ($Num>0){
	while($Result= $DB->fetch_array($Query)){
		$qk_goods_array[$i]['i']    =  $i;
		$qk_goods_array[$i]['gc_name']    =  trim($Result['gc_name']);
		$qk_goods_array[$i]['tag']    =  trim($Result['tag']);
		$qk_goods_array[$i]['gc_pic']    =  trim($Result['gc_pic']);
		$qk_goods_array[$i]['gc_id']      =  intval($Result['gc_id']);
		$qk_goods_array[$i]['gc_link']  =  trim($Result['gc_link']);
		$qk_goods_array[$i]['gc_string']  =  trim($Result['gc_string']);
		
		if($gc_array[$i]['gc_string']!=""){
			$gc_arrays = explode(",",$gc_array[$i]['gc_string']);
			$k = 0;
			foreach($gc_arrays as $k=>$v){
				
				$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.gid ='" .$v. "' and g.ifpub='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifbonus!='1' and g.ifpresent!=1 and g.ifxy!=1 and g.shopid<>0 and g.ifgoodspresent!=1 order by g.gid desc limit 0,1";
				$goods_Query    = $DB->query($goods_sql);
				$goods_Rs=$DB->fetch_array($goods_Query);
				$qk_goods_array[$i]['goods'][$k]['goodsname'] = $goods_Rs['goodsname'];
				$qk_goods_array[$i]['goods'][$k]['sid'] = $goods_Rs['sid'];
				$qk_goods_array[$i]['goods'][$k]['shopname'] = $goods_Rs['shopname'];
				$qk_goods_array[$i]['goods'][$k]['gid'] = $goods_Rs['gid'];
				$qk_goods_array[$i]['goods'][$k]['price'] = $goods_Rs['price'];
				$qk_goods_array[$i]['goods'][$k]['pricedesc'] = $goods_Rs['pricedesc'];
				$qk_goods_array[$i]['goods'][$k]['smallimg'] = $goods_Rs['smallimg'];
				$qk_goods_array[$i]['goods'][$k]['intro'] = $Char_class->cut_str($goods_Rs['intro'],23,0,'UTF-8');
				if ($goods_Rs['iftimesale']==1 && $goods_Rs['timesale_starttime']<=time() && $goods_Rs['timesale_endtime']>=time()){
					$qk_goods_array[$i]['goods'][$k]['pricedesc']  = $goods_Rs['saleoffprice'];
				}
				$k++;
				
			}
		}
		$i++;
	}
}
//print_r($qk_goods_array);
$tpl->assign("qk_goods_array",$qk_goods_array);

$tpl->assign($Good);
$tpl->display("include_juheshopproduct.html");
?>
