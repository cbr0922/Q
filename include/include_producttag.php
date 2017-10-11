<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");
$bid = intval($_GET['bid']);
$str_id = $FUNCTIONS->father_class_topid($bid);
$id_array = explode(" ",$str_id);
$top_id = $id_array[count($id_array)-1]==0?$bid:$id_array[count($id_array)-1];
$class_sql = "select ac.attrid,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac left join `{$INFO[DBPrefix]}attribute` as a on a.attrid = ac.attrid where ac.cid='" . intval($top_id) . "'";
	$Query_class    = $DB->query($class_sql);
	$ic = 0;
	$attr_class = array();
	while($Rs_class=$DB->fetch_array($Query_class)){
		$attr_class[$ic]['attrid']=$Rs_class['attrid'];
		$attr_class[$ic]['bid']=$top_id;
		$attr_class[$ic]['attributename']=$Rs_class['attributename'];
		$Sql_value      = "select * from `{$INFO[DBPrefix]}attributevalue` where attrid='" . intval($Rs_class['attrid']) . "' order by valueid desc  limit 0,8";
		$Query_value     = $DB->query($Sql_value );
		$iv = 0;
		while ($Rs_value =$DB->fetch_array($Query_value)) {
			$attr_class[$ic]['value'][$iv]['valueid'] = $Rs_value['valueid'];
			$attr_class[$ic]['value'][$iv]['value'] = $Rs_value['value'];
			$iv++;
		}
		$ic++;
	}
	//print_r($attr_class);
	$tpl->assign("attr_class",  $attr_class);
	$tpl->display("include_producttag.html");
?>
