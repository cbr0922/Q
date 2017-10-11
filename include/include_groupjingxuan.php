<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");
include "./language/".$INFO['IS']."/Good.php";

$Sql = "select g.groupname, g.groupbn, g.groupMimg,g.gdid,g.price,g.intro,g.groupprice,g.saleoff_endtime,g.saleoff_starttime,g.groupbn,g.salename from `{$INFO[DBPrefix]}groupdetail` as g where g.ifpub=1 and g.ifrecommend=1 order by g.pubtime desc limit 0,5";
$Query = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$i=0;
	$j=1;
	while ( $ProNav = $DB->fetch_array($Query)){
		$sale_p_array[$i]['count'] = 0;
		if (in_array(intval($ProNav['gdid']),$goods_array)){
			foreach($goods_array as $k=>$v){
				if (intval($ProNav['gdid']) == $v){
					$sale_p_array[$i]['count']        = $goods_count_array[$k] ;
					$sale_p_array[$i]['buykey']    = $k;
				}
			}
		}
		
		$sale_p_array[$i][groupname] = $ProNav['groupname'];
		$sale_p_array[$i][salename] = $ProNav['salename'];
		$sale_p_array[$i][groupbn] = $ProNav['groupbn'];
		$sale_p_array[$i][gdid] = $ProNav['gdid'];
		$sale_p_array[$i][groupprice] = $ProNav['groupprice'];
		$sale_p_array[$i][grouppoint] = $ProNav['grouppoint'];
		$sale_p_array[$i][smallimg] = $ProNav['groupMimg'];
		$sale_p_array[$i][price] = $ProNav['price'];
		$sale_p_array[$i][groupbn] = $ProNav['groupbn'];
		$sale_p_array[$i][saleoff_hour] = 0;
		$sale_p_array[$i][saleoff_min] = 0;
		$sale_p_array[$i][saleoff_second] = 0;
		if ($ProNav['saleoff_endtime']>time()){
			$sale_p_array[$i][saleoff_hour] = intval(($ProNav['saleoff_endtime']-time())/(60*60));
			$sale_p_array[$i][saleoff_min] = intval(($ProNav['saleoff_endtime']-time()-$sale_p_array[$i][saleoff_hour]*60*60)/60);
			$sale_p_array[$i][saleoff_second] = intval($ProNav['saleoff_endtime']-time()-$sale_p_array[$i][saleoff_hour]*60*60-$sale_p_array[$i][saleoff_min]*60);
		}
		$sale_p_array[$i][content] = $Char_class->cut_str($ProNav['intro'],70);
		$order_Sql = "select count(*) as sumcount from `{$INFO[DBPrefix]}order_group` where gdid='" . intval($ProNav['gdid']) . "'";
		$order_Query = $DB->query($order_Sql);
		$Rs = $DB->fetch_array($order_Query);
		$sale_p_array[$i][salenum] = intval($Rs['sumcount']);
		$i++;
		$j++;
	}
}
//print_r($sale_p_array);
$tpl->assign("sale_p_array",$sale_p_array);
$tpl->assign($Good);
$tpl->display("include_groupjingxuan.html");
?>
