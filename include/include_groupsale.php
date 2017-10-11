<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");
include "./language/".$INFO['IS']."/Good.php";

$Sql = "select g.groupname, g.groupbn, g.groupSimg,g.gdid,g.price,g.intro,g.groupprice,sum(og.count) as ogcount from `{$INFO[DBPrefix]}order_group` as og inner join `{$INFO[DBPrefix]}groupdetail` as g on og.gdid=g.gdid where g.ifpub=1 group by g.gdid order by ogcount desc limit 0,5";
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
		$sale_p_array[$i][salenum] = intval($ProNav['ogcount']);
		$i++;
		$j++;
	}
}
//print_r($sale_p_array);
$tpl->assign("sale_p_array",$sale_p_array);
$tpl->assign($Good);
$tpl->display("include_groupsale.html");
?>
