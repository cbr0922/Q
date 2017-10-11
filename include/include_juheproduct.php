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
//for($i=1;$i<=8;$i++){
	/*
	$Sql = "select * from `{$INFO[DBPrefix]}goodscollection` where gc_id=8 order by gc_id asc ";
	$Query    = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$i = 0;
		while($Result= $DB->fetch_array($Query)){
		$gc_array[$i]['i']    =  trim($i);
		$gc_array[$i]['gc_name']    =  trim($Result['gc_name']);
		$gc_array[$i]['gc_pic']    =  trim($Result['gc_pic']);
		$gc_array[$i]['gc_id']      =  intval($Result['gc_id']);
		$gc_array[$i]['gc_link']  =  trim($Result['gc_link']);
		$gc_array[$i]['gc_string']  =  trim($Result['gc_string']);
		if($gc_array[$i]['gc_string']!=""){
			$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.gid in (" . $gc_array[$i]['gc_string'] . ") and g.ifpub='1' and g.ifbonus!='1' and g.ifpresent!=1 and g.ifxy!=1 order by g.gid desc limit 0,10";
			$goods_Query    = $DB->query($goods_sql);
			$j = 0;
			while ($goods_Rs=$DB->fetch_array($goods_Query)) {
				$gc_array[$i]['goods'][$j]['goodsname'] = $goods_Rs['goodsname'];
				$gc_array[$i]['goods'][$j]['sale_name'] = $goods_Rs['sale_name'];
				$gc_array[$i]['goods'][$j]['gid'] = $goods_Rs['gid'];
				$gc_array[$i]['goods'][$j]['price'] = $goods_Rs['price'];
				$gc_array[$i]['goods'][$j]['pricedesc'] = $goods_Rs['pricedesc'];
				$gc_array[$i]['goods'][$j]['smallimg'] = $goods_Rs['smallimg'];
				$gc_array[$i]['goods'][$j]['intro'] = $Char_class->cut_str($goods_Rs['intro'],23,0,'UTF-8');
				if ($goods_Rs['iftimesale']==1 && $goods_Rs['timesale_starttime']<=time() && $goods_Rs['timesale_endtime']>=time()){
					$gc_array[$i]['goods'][$j]['pricedesc']  = $goods_Rs['saleoffprice'];
				}
				$j++;
			}
			//$tpl->assign("qk_goods_array_" . $i,$qk_goods_array[$i]);
		}
		//$tpl->assign("gc_pic" . $i,$gc_array[$i]['gc_pic']);
		//$tpl->assign("gc_link" . $i,$gc_array[$i]['gc_link']);
		$i++;
		}
	}
	*/
//}
//print_r($gc_array);

$Sql = "select * from `{$INFO[DBPrefix]}goodscollection` where gc_id=8 limit 0,1";
	$Query    = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
$i=0;
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
				
				$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.gid ='" .$v. "' and g.ifpub!='0' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifbonus!='1' and g.ifpresent!=1 and g.ifxy!=1 and g.ifgoodspresent!=1 order by g.gid desc ";
				$goods_Query    = $DB->query($goods_sql);
				$goods_Rs=$DB->fetch_array($goods_Query);
				$Num_g   = $DB->num_rows($goods_Query);
				if($Num_g>0){
					$gc_array[$i]['goods'][$j]['ERP'] = $goods_Rs['ERP'];
					$gc_array[$i]['goods'][$j]['ifnew'] = $goods_Rs['ifnew'];
					$gc_array[$i]['goods'][$j]['goodsname'] = $goods_Rs['goodsname'];
					$gc_array[$i]['goods'][$j]['showstorage'] = $FUNCTIONS->Storage($goods_Rs['ifalarm'],$goods_Rs['storage'],$goods_Rs['alarmnum']);
					$gc_array[$i]['goods'][$j]['gid'] = $goods_Rs['gid'];					
					$gc_array[$i]['goods'][$j]['price'] = number_format($goods_Rs['price']);
					$gc_array[$i]['goods'][$j]['pricedesc'] = number_format($goods_Rs['pricedesc']);
					$gc_array[$i]['goods'][$j]['smallimg'] = $goods_Rs['smallimg'];
					$gc_array[$i]['goods'][$j]['intro'] = $Char_class->cut_str($goods_Rs['intro'],23,0,'UTF-8');
					$gc_array[$i]['goods'][$j]['attr'] = getAttributeGoods3($goods_Rs['gid']);
					if ($goods_Rs['iftimesale']==1 && $goods_Rs['timesale_starttime']<=time() && $goods_Rs['timesale_endtime']>=time()){
						$gc_array[$i]['goods'][$j]['pricedesc']  = $goods_Rs['saleoffprice'];
					}
					
					$Sql2      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $goods_Rs['gid']. "' order by detail_id desc ";

					$Query2    = $DB->query($Sql2);
					$Num      = $DB->num_rows($Query2);
					$gc_array[$i]['goods'][$j]['productdetailcount'] = $Num;
					/*
					$i = 0;
					while ( $Detail_Rs = $DB->fetch_array($Query2)){
						
						$i++;
					}
					*/
					$br = $goods_Rs['brand_id'];					
					$Sql1 = "select brandname,brand_id from `{$INFO[DBPrefix]}brand` where brand_id=".$br;
					$Query1   = $DB->query($Sql1);
					$h = 0;
					while ( $Result1 = $DB->fetch_array($Query1)){
				
					$gc_array[$i]['goods'][$j]['br'][$h]['brand_id']    =  $Result1['brand_id'];
					$gc_array[$i]['goods'][$j]['br'][$h]['brandname']    =  $Result1['brandname'];
					
					$h++;
					
					}
										
					$j++;
				}
			}
			//print_r($gc_array[$i]['goods']);
		}
		//$tpl->assign("qk_goods_array_1",$qk_goods_array[$i]);
		//$tpl->assign("gc_pic" . $i,$gc_array[$i]['gc_pic']);
		//$tpl->assign("gc_link" . $i,$gc_array[$i]['gc_link']);
	}
function getAttributeGoods3($gid){
		global $DB,$INFO;
		$attrvalue_array = array();

		$Sql = "select av.value from `{$INFO[DBPrefix]}attributegoods` as ag right join `{$INFO[DBPrefix]}attributevalue` as av on ag.valueid=av.valueid  where ag.gid ='" . $gid . "' and av.attrid=6";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$i = 0;
		while ($Rs=$DB->fetch_array($Query)) {
			$attrvalue_array[$i]['value'] = $Rs['value'];
			$i++;

		}
		return $attrvalue_array;

	}
	//print_r($gc_array);
$tpl->assign("gc_array",$gc_array);

$tpl->assign($Good);
$tpl->display("include_juheproduct.html");


?>



