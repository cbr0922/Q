<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
if (is_file("configs.inc.php")){
 include("./configs.inc.php");
}elseif (is_file("../configs.inc.php")){
 include("../configs.inc.php");
}
include ("global.php");
include "./language/".$INFO['IS']."/Good.php";

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/*
聚合商品
*/
//for($i=1;$i<=8;$i++){
	$Sql = "select * from `{$INFO[DBPrefix]}goodscollection` where gc_id>0 and  gc_id<5   order by gc_id asc ";
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
			$gc_arrays = explode(",",$gc_array[$i]['gc_string']);
			$j = 0;
			foreach($gc_arrays as $k=>$v){

				$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.gid ='" .$v. "' and g.ifpub='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifbonus!='1' and g.ifpresent!=1 and g.ifxy!=1 and g.ifgoodspresent!=1 order by g.gid desc limit 0,1";
				$goods_Query    = $DB->query($goods_sql);
				$goods_Num   = $DB->num_rows($goods_Query);
				if($goods_Num>0){
					$goods_Rs=$DB->fetch_array($goods_Query);
					$gc_array[$i]['goods'][$j]['goodsname'] = $goods_Rs['goodsname'];
          $gc_array[$i]['goods'][$j]['showstorage'] = $FUNCTIONS->Storage($goods_Rs['ifalarm'],$goods_Rs['storage'],$goods_Rs['alarmnum']);
					$gc_array[$i]['goods'][$j]['sale_name'] = $goods_Rs['salename_color']==""?$goods_Rs['sale_name']:"<font color='" . $goods_Rs['salename_color'] . "'>" . $goods_Rs['sale_name'] . "</font>";
					$gc_array[$i]['goods'][$j]['gid'] = $goods_Rs['gid'];
					$gc_array[$i]['goods'][$j]['price'] = $goods_Rs['price'];
					$gc_array[$i]['goods'][$j]['pricedesc'] = $goods_Rs['pricedesc'];
					$gc_array[$i]['goods'][$j]['smallimg'] = $goods_Rs['smallimg'];
					$gc_array[$i]['goods'][$j]['intro'] = $Char_class->cut_str($goods_Rs['intro'],23,0,'UTF-8');
					if ($goods_Rs['ifappoint']==1 && $goods_Rs['appoint_starttime']<=time() && $goods_Rs['appoint_endtime']>=time()){
		            	$gc_array[$i]['goods'][$j]['ifappoint']  = 1;
		        	}
					if ($goods_Rs['iftimesale']==1 && $goods_Rs['timesale_starttime']<=time() && $goods_Rs['timesale_endtime']>=time()){
						$gc_array[$i]['goods'][$j]['pricedesc']  = $goods_Rs['saleoffprice'];
						$gc_array[$i]['goods'][$j]['ifsaleoff']  = 1;
					}
					if ($goods_Rs['ifsaleoff']==1 && $goods_Rs['saleoff_starttime']<=time() && $goods_Rs['saleoff_endtime']>=time()){
						$gc_array[$i]['goods'][$j]['ifsaleoff']  = 1;
					}
          if($_SESSION['user_id']>0){
            $collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$goods_Rs['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
            $collection_Query    = $DB->query($collection_sql);
            $collection_Num   = $DB->num_rows($collection_Query);
            if($collection_Num>0){
              $gc_array[$i]['goods'][$j]['heartColor'] = 1;
            }
          }else{
            $gc_array[$i]['goods'][$j]['heartColor'] = 0;
          }

					$j++;
				}

			}
		}
		//$tpl->assign("gc_pic" . $i,$gc_array[$i]['gc_pic']);
		//$tpl->assign("gc_link" . $i,$gc_array[$i]['gc_link']);
		$i++;
		}
	}
//}
//print_r($gc_array);
$tpl->assign("gc_array",$gc_array);

$tpl->assign($Good);
$tpl->display("include_juheproduct1.html");
?>
