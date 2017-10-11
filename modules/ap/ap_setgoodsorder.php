<?php
error_reporting(7);
set_time_limit(60*60*5);
@header("Content-type: text/html; charset=utf-8");
include("../../configs.inc.php");
include("global.php");
echo "開始執行時間：" . date("Y-m-d H:i:s");

//销售量最多
$Sql_order = "select max(salenum) as maxsalenum from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "')";
$Query_order  = $DB->query($Sql_order);
$Rs_order=$DB->fetch_array($Query_order);
$maxsalenum = intval($Rs_order['maxsalenum']);

$Sql_order = "update `{$INFO[DBPrefix]}goods` set goodorder=0";
$Query_order  = $DB->query($Sql_order);
$Sql_order = "select g.*,b.ratio from `{$INFO[DBPrefix]}goods` as g left join `{$INFO[DBPrefix]}brand` as b on g.brand_id=b.brand_id where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "')";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	$ifsale = 0;
	if($Rs_order['price']!=$Rs_order['pricedesc']){
		$ifsale = 1;
	}
	$order = ($Rs_order['salenum']/$maxsalenum)*0.4*100+$Rs_order['ratio']*6+$Rs_order['ifnew']*5+$ifsale*10+$Rs_order['ifrecommend']*10+$Rs_order['ifspecial']*5;
	 $Sql_orders = "update `{$INFO[DBPrefix]}goods` set goodorder=" . $order . " where gid='" . $Rs_order['gid'] . "'";
	$Query_orders  = $DB->query($Sql_orders);
}

?>