<?php
error_reporting(7);
include( dirname( __FILE__ )."/"."../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
@header("Content-type: text/html; charset=utf-8");


//显示浏览过的商品

$viewProductArray = array();
if (isset($_COOKIE['viewgoods'])){
	$array = array_reverse($_COOKIE['viewgoods']);
	$i=0;
	foreach($array as $k=>$v){
		if ($v!=''){
			//echo $v . "a";
			if ($i<12){

				$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.ifsaleoff,g.ifappoint,g.appoint_starttime,g.appoint_endtime from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifxy!=1 and g.ifgoodspresent!=1 and g.ifbonus!='1' and g.ifxy!=1 and g.ifchange!=1 and g.shopid=0 and g.ifpresent!=1 and g.gid=".$v;
				$Query = $DB->query($Sql);
				$Rs =  $DB->fetch_array($Query);
				if ($Rs['gid']>0){

					$viewProductArray[$i][gid] = $Rs['gid'];
					$viewProductArray[$i][goodsname] = $Rs['goodsname'];
					$viewProductArray[$i][price] = $Rs['price'];
					$viewProductArray[$i][smallimg] = $Rs['middleimg'];
					$viewProductArray[$i][pricedesc] = $Rs['pricedesc'];
					$viewProductArray[$i]['sale_name'] = $Rs['sale_name'] ;
					$viewProductArray[$i][ifsaleoff] = $Rs['ifsaleoff'];
					$viewProductArray[$i]['ifshow']  = $FUNCTIONS->getStorage($Rs['ifalarm'],$Rs['storage']) ;
					if ($Rs['saleoff_starttime']!=""){
						$viewProductArray[$i][saleoff_startdate] = date("Y-m-d H:i",$Rs['saleoff_starttime']);
					}
					if ($Rs['saleoff_endtime']!=""){
						$viewProductArray[$i][saleoff_enddate] = date("Y-m-d H:i",$Rs['saleoff_endtime']);
					}

					if ($Rs['ifappoint']==1 && $Rs['appoint_starttime']<=time() && $Rs['appoint_endtime']>=time()){
							$viewProductArray[$i]['ifappoint']  = 1;
					}
					if ($Rs['iftimesale']==1 && $Rs['timesale_starttime']<=time() && $Rs['timesale_endtime']>=time()){
						$viewProductArray[$i][pricedesc]  = $Rs['saleoffprice'];
						$viewProductArray[$i]['ifsaleoff']  = 1;
					}
					if ($Rs['ifsaleoff']==1 && $Rs['saleoff_starttime']<=time() && $Rs['saleoff_endtime']>=time()){
						$viewProductArray[$i]['ifsaleoff']  = 1;
					}

					if($_SESSION['user_id']>0){
						$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$Rs['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
						$collection_Query    = $DB->query($collection_sql);
						$collection_Num   = $DB->num_rows($collection_Query);
						if($collection_Num>0){
							$viewProductArray[$i]['heartColor'] = 1;
						}
					}else{
						$viewProductArray[$i]['heartColor'] = 0;
					}
					$i++;
				}
			}
		}

	}
}
//print_r($viewProductArray);
$tpl->assign("viewProductArray",      $viewProductArray);
$tpl->display("include_visitproduct.html");
?>
