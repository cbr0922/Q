<?php
error_reporting(7);
set_time_limit(60*60*5);
@header("Content-type: text/html; charset=utf-8");
include("../../configs.inc.php");
include("global.php");
echo "é_Ê¼ˆÌÐÐ•rég£º" . date("Y-m-d H:i:s");


$Sql_order = "select count(g.gid) as totalcount from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "')";
$Query_order  = $DB->query($Sql_order);
$Rs_order=$DB->fetch_array($Query_order);
$total = intval($Rs_order['totalcount']);
$count = intval($total*0.03);
$Sql_order = "update `{$INFO[DBPrefix]}goods` set ifnew=0";
$Query_order  = $DB->query($Sql_order);
 $Sql_order = "select * from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') order by gid desc limit 0," . $count;
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	 $Sql_order = "update `{$INFO[DBPrefix]}goods` set ifnew=1 where gid='" . $Rs_order['gid'] . "'";
	$DB->query($Sql_order);
}

?>