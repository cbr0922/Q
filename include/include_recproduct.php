<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/**
 *  推荐商品
 */
	$Sql = "select * from `{$INFO[DBPrefix]}bclass` b left join `{$INFO[DBPrefix]}goods` g on ( g.bid=b.bid) where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifrecommend=1 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifxy!=1 and ifchange!=1 and g.ifxy!=1 and b.catiffb=1 and g.ifgoodspresent!=1 order by g.goodorder asc,g.idate desc limit 0,12";

	$Query =    $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$j=0;
	while ( $RecPro = $DB->fetch_array($Query)){
			$Recommendation_productarray[$j][ERP] = $RecPro['ERP'];
			$Recommendation_productarray[$j][gid] = $RecPro['gid'];
			$Recommendation_productarray[$j][countj] = $j+1;
			$Recommendation_productarray[$j][goodsname] = $RecPro['goodsname']."".$FUNCTIONS->Storage($RecPro['ifalarm'],$RecPro['storage'],$RecPro['alarmnum']);
			$Recommendation_productarray[$j][price] = $RecPro['price'];
			$Recommendation_productarray[$j][sale_name] = $RecPro['salename_color']==""?$RecPro['sale_name']:"<font color='" . $RecPro['salename_color'] . "'>" . $RecPro['sale_name'] . "</font>";
			$Recommendation_productarray[$j][pricedesc] = number_format($RecPro['pricedesc']);
			$Recommendation_productarray[$j][bn] = $RecPro['bn'];
			$Recommendation_productarray[$j][smallimg] = $RecPro['smallimg'];

			if ($RecPro['ifappoint']==1 && $RecPro['appoint_starttime']<=time() && $RecPro['appoint_endtime']>=time()){
			$Recommendation_productarray[$j]['ifappoint']  = 1;
			}
			if ($RecPro['iftimesale']==1 && $RecPro['timesale_starttime']<=time() && $RecPro['timesale_endtime']>=time()){
				$Recommendation_productarray[$j][pricedesc]  = number_format($RecPro['saleoffprice']);
				$Recommendation_productarray[$j]['ifsaleoff']  = 1;
			}
			if ($RecPro['ifsaleoff']==1 && $RecPro['saleoff_starttime']<=time() && $RecPro['saleoff_endtime']>=time()){
						$Recommendation_productarray[$j]['ifsaleoff']  = 1;
			}
			if($_SESSION['user_id']>0){
				$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$RecPro['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
				$collection_Query    = $DB->query($collection_sql);
				$collection_Num   = $DB->num_rows($collection_Query);
				if($collection_Num>0){
					$Recommendation_productarray[$j]['heartColor'] = 1;
				}
			}else{
				$Recommendation_productarray[$j]['heartColor'] = 0;
			}
			
			
			$br = $RecPro['brand_id'];
					
					
					$Sql1 = "select brandname,brand_id from `{$INFO[DBPrefix]}brand` where brand_id=".$br;
					$Query1   = $DB->query($Sql1);
					$h = 0;
					while ( $Result1 = $DB->fetch_array($Query1)){
				
					$Recommendation_productarray[$j]['br'][$h]['brand_id']    =  $Result1['brand_id'];
					$Recommendation_productarray[$j]['br'][$h]['brandname']    =  $Result1['brandname'];
					
					$h++;
					
					}

			$j++;
	}
//print_r($Recommendation_productarray);
$tpl->assign("Recommendation_productarray",  $Recommendation_productarray);
$tpl->display("include_recproduct.html");
?>
