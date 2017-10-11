<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");
include "./language/".$INFO['IS']."/Good.php";

if (intval($_GET['goods_id']) >0){
	$sql = "select bid from `{$INFO[DBPrefix]}groupdetail` where gdid='" . intval($_GET['goods_id']) . "'";
	$query  = $DB->query($sql);
	$Rs =  $DB->fetch_array($query);
	$_GET['bid'] = $Rs['bid'];
}

$bid = intval($_GET['bid']);

$Next_ArrayClass  = $FUNCTIONS->Sun_groupcon_class($bid);
$Next_ArrayClass  = explode(",",$Next_ArrayClass);
$Array_class      = array_unique($Next_ArrayClass);

foreach ($Array_class as $k=>$v){
	$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
}


$Sql = "select g.groupname, g.groupbn, g.groupSimg,g.gdid,g.price,g.intro,g.groupprice from `{$INFO[DBPrefix]}groupdetail` as g where g.ifpub=1 and g.ifhot=1 and (g.bid='" . $bid . " '" . $Add . ") order by g.view_num desc limit 0,5";
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
		$sale_p_array[$i][groupbn] = $ProNav['groupbn'];
		$sale_p_array[$i][gdid] = $ProNav['gdid'];
		$sale_p_array[$i][groupprice] = $ProNav['groupprice'];
		$sale_p_array[$i][grouppoint] = $ProNav['grouppoint'];
		$sale_p_array[$i][smallimg] = $ProNav['groupSimg'];
		$sale_p_array[$i][price] = $ProNav['price'];
		$sale_p_array[$i][content] = $Char_class->cut_str($ProNav['intro'],32);
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
$tpl->display("include_group_salelist.html");
?>
