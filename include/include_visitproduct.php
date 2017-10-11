<?php
error_reporting(7);
include( dirname( __FILE__ )."/"."../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
@header("Content-type: text/html; charset=utf-8");

if($_GET['Action']=='Del'){
	$gid = intval($_GET['gid']);

	if ($gid>0){
		/*
		$goodscount = count($_COOKIE['viewgoods']);

		$flag = "";
		if (isset($_COOKIE['viewgoods'])){
			foreach($_COOKIE['viewgoods'] as $k=>$v){
				if ($v == $gid){
					$flag = $k;
					break;
				}
			}
		}
		setcookie("viewgoods[" . $flag . "]", "",time()-3600,"/");
		*/

		$i = 0;
		foreach($_COOKIE['viewgoods'] as $k=>$v){
			if (intval($v) != $gid){
				setcookie("viewgoods[" . $i . "]", intval($v),time()+3600*24,"/");
				$i++;
			}
		}
		setcookie("viewgoods[" . $i . "]", "",time()-3600,"/");

	}else{
		if (isset($_COOKIE['viewgoods'])){
			$value = end($_COOKIE['viewgoods']);
			foreach($_COOKIE['viewgoods'] as $k=>$v){
				setcookie("viewgoods[" . $k . "]", "",time()-3600,"/");
			}
			setcookie("viewgoods[0]", intval($value),time()+3600*24,"/");
		}
	}
}else{
	//显示浏览过的商品
	$viewProductArray = array();
	if (isset($_COOKIE['viewgoods'])){
		$array = array_reverse($_COOKIE['viewgoods']);
		//print_r($_COOKIE['viewgoods']);
		$i=0;
		foreach($array as $k=>$v){
			if ($v!=''){
			//echo $k."*".$v."<br>";
				if ($i<12){

					$Sql   = "select g.gid,g.goodsname,g.ERP,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.brand_id from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifxy!=1 and g.ifgoodspresent!=1 and g.ifbonus!='1' and g.ifxy!=1 and g.ifchange!=1 and g.shopid=0 and g.ifpresent!=1 and g.gid=".$v;
					$Query = $DB->query($Sql);
					$Rs =  $DB->fetch_array($Query);
					if ($Rs['gid']>0){

						$viewProductArray[$i][gid] = $Rs['gid'];
						$viewProductArray[$i][ERP] = $Rs['ERP'];
						$viewProductArray[$i][goodsname] = $Rs['goodsname'];
						$viewProductArray[$i][price] = number_format($Rs['price']);
						$viewProductArray[$i][smallimg] = $Rs['middleimg'];
						$viewProductArray[$i][pricedesc] = number_format($Rs['pricedesc']);
						$viewProductArray[$i]['sale_name'] = $Rs['sale_name'] ;
						$viewProductArray[$i][ifsaleoff] = $Rs['ifsaleoff'];
						$viewProductArray[$i]['ifshow']  = $FUNCTIONS->getStorage($Rs['ifalarm'],$Rs['storage']) ;
						if ($Rs['saleoff_starttime']!=""){
							$viewProductArray[$i][saleoff_startdate] = date("Y-m-d H:i",$Rs['saleoff_starttime']);
						}
						if ($Rs['saleoff_endtime']!=""){
							$viewProductArray[$i][saleoff_enddate] = date("Y-m-d H:i",$Rs['saleoff_endtime']);
						}
						if ($Rs['iftimesale']==1 && $Rs['timesale_starttime']<=time() && $Rs['timesale_endtime']>=time()){
							$viewProductArray[$i]['pricedesc']  = $Rs['saleoffprice'];
						}
						$view_br = $Rs['brand_id'];					
						$view_Sql = "select brandname,brand_id from `{$INFO[DBPrefix]}brand` where brand_id=".$view_br;
						$view_Query   = $DB->query($view_Sql);
						$h = 0;
						while ( $view_Result = $DB->fetch_array($view_Query)){
							$viewProductArray[$i]['br'][$h]['brand_id']    =  $view_Result['brand_id'];
							$viewProductArray[$i]['br'][$h]['brandname']   =  $view_Result['brandname'];
							$h++;
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
}
?>
