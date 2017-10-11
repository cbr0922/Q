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
for($i=1;$i<=5;$i++){
	$Sql = "select * from `{$INFO[DBPrefix]}goodscollection` where gc_id=".intval($i)." limit 0,1";
	$Query    = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$gc_array[$i]['gc_name']    =  trim($Result['gc_name']);
		$gc_array[$i]['gc_pic']    =  trim($Result['gc_pic']);
		$gc_array[$i]['gc_id']      =  intval($Result['gc_id']);
		$gc_array[$i]['gc_link']  =  trim($Result['gc_link']);
		$gc_array[$i]['gc_string']  =  trim($Result['gc_string']);
		
		if($gc_array[$i]['gc_string']!=""){
			$gc_arrays = explode(",",$gc_array[$i]['gc_string']);
			$j = 0;
			foreach($gc_arrays as $k=>$v){
				
				$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.gid ='" .$v. "' and g.ifpub='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifbonus!='1' and g.ifpresent!=1 and g.ifxy!=1 and g.ifgoodspresent!=1 order by g.gid desc limit 0,1";
				$goods_Query    = $DB->query($goods_sql);
				$goods_Rs=$DB->fetch_array($goods_Query);
					$qk_goods_array[$i][$j]['goodsname'] = $goods_Rs['goodsname'];
				$qk_goods_array[$i][$j]['gid'] = $goods_Rs['gid'];
				$qk_goods_array[$i][$j]['price'] = $goods_Rs['price'];
				$qk_goods_array[$i][$j]['pricedesc'] = $goods_Rs['pricedesc'];
				$qk_goods_array[$i][$j]['smallimg'] = $goods_Rs['smallimg'];
				$qk_goods_array[$i][$j]['intro'] = $Char_class->cut_str($goods_Rs['intro'],23,0,'UTF-8');
				if ($goods_Rs['iftimesale']==1 && $goods_Rs['timesale_starttime']<=time() && $goods_Rs['timesale_endtime']>=time()){
					$qk_goods_array[$i][$j]['pricedesc']  = $goods_Rs['saleoffprice'];
				}
				$j++;
				
			}
		}
		$tpl->assign("qk_goods_array_" . $i,$qk_goods_array[$i]);
		$tpl->assign("gc_pic" . $i,$gc_array[$i]['gc_pic']);
		$tpl->assign("gc_link" . $i,$gc_array[$i]['gc_link']);
	}
}

//屬性
$Sql = "select * from `{$INFO[DBPrefix]}bclass` where top_id=0";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i = 0;
while ($class_Rs=$DB->fetch_array($Query)) {
	$class_sql = "select ac.attrid,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac left join `{$INFO[DBPrefix]}attribute` as a on a.attrid = ac.attrid where ac.cid='" . intval($class_Rs['bid']) . "'";
	$Query_class    = $DB->query($class_sql);
	$ic = 0;
	$attr_class = array();
	while($Rs_class=$DB->fetch_array($Query_class)){
		$attr_class[$ic]['attrid']=$Rs_class['attrid'];
		$attr_class[$ic]['bid']=$class_Rs['bid'];
		$attr_class[$ic]['attributename']=$Rs_class['attributename'];
		$Sql_value      = "select * from `{$INFO[DBPrefix]}attributevalue` where attrid='" . intval($Rs_class['attrid']) . "' order by valueid desc  limit 0,8";
		$Query_value     = $DB->query($Sql_value );
		$iv = 0;
		while ($Rs_value =$DB->fetch_array($Query_value)) {
			$attr_class[$ic]['value'][$iv]['valueid'] = $Rs_value['valueid'];
			$attr_class[$ic]['value'][$iv]['value'] = $Rs_value['value'];
			$iv++;
		}
		$ic++;
	}
	//print_r($attr_class);
	$tpl->assign("attr" . intval($class_Rs['bid']),  $attr_class);
	$i++;
}

$tpl->assign($Good);
$tpl->display("include_juheproduct_s.html");
?>
