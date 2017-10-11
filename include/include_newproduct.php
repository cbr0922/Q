<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 12;
/**
 *  最新商品
 */

$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.salename_color,g.intro,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.ifappoint,g.appoint_starttime,g.appoint_endtime,g.ifnew from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where b.catiffb=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.shopid=0 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifxy!=1 and b.catiffb=1 and ifchange!=1 and g.ifgoodspresent!=1  order by g.gid desc  limit 0,12";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
$j=0;
$Sql_level = "";
$NewRs_productarray_level = array();

while ( $NewPro = $DB->fetch_array($Query)){

			$New_productarray[$j][gid] = $NewPro['gid'];
			$New_productarray[$j][countj] = $j+1;
			$New_productarray[$j][goodsname] = $NewPro['goodsname']."".$FUNCTIONS->Storage($NewPro['ifalarm'],$NewPro['storage'],$RecPro['alarmnum']);
			$New_productarray[$j][price] = $NewPro['price'];
			$New_productarray[$j][pricedesc] = $NewPro['pricedesc'];
			$New_productarray[$j][bn] = $NewPro['bn'];
			$New_productarray[$j][ifnew] = $NewPro['ifnew'];
			$New_productarray[$j]['attr'] = getAttributeGoods2($NewPro['gid']);

			$New_productarray[$j][sale_name] = $NewPro['salename_color']==""?$NewPro['sale_name']:"<font color='" . $NewPro['salename_color'] . "'>" . $NewPro['sale_name'] . "</font>";
			//$New_productarray[$j][sale_name] = $NewPro['sale_name'];
			$New_productarray[$j][smallimg] = $NewPro['smallimg'];
			if ($NewPro['ifappoint']==1 && $NewPro['appoint_starttime']<=time() && $NewPro['appoint_endtime']>=time()){
		    	$New_productarray[$j]['ifappoint']  = 1;
			}
			if ($NewPro['iftimesale']==1 && $NewPro['timesale_starttime']<=time() && $NewPro['timesale_endtime']>=time()){
				$New_productarray[$j][pricedesc]  = $NewPro['saleoffprice'];
				$New_productarray[$j]['ifsaleoff']  = 1;
			}
			if ($NewPro['ifsaleoff']==1 && $NewPro['saleoff_starttime']<=time() && $NewPro['saleoff_endtime']>=time()){
				$New_productarray[$j]['ifsaleoff']  = 1;
			}

			if($_SESSION['user_id']>0){
				$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$NewPro['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
				$collection_Query    = $DB->query($collection_sql);
				$collection_Num   = $DB->num_rows($collection_Query);
				if($collection_Num>0){
					$New_productarray[$j]['heartColor'] = 1;
				}
			}else{
				$New_productarray[$j]['heartColor'] = 0;
			}
			$j++;
}

function getAttributeGoods2($gid){
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
$tpl->assign("New_productarray",  $New_productarray);
$tpl->display("include_newproduct.html");
?>