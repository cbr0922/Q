<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$class_array = array();
$i = 0;
$class_Sql = "select * from `{$INFO[DBPrefix]}shopclass` where top_id=0";
$class_Query  = $DB->query($class_Sql);
while($class_Rs =  $DB->fetch_array($class_Query)){
	$class_array[$i]['classname']	= $class_Rs['classname'];
	$class_array[$i]['scid']	= $class_Rs['scid'];
	$class_sub_Sql = "select * from `{$INFO[DBPrefix]}shopclass` where top_id='" . $class_Rs['scid'] . "'";
	$class_sub_Query  = $DB->query($class_sub_Sql);
	$j = 0;
	while($class_sub_Rs =  $DB->fetch_array($class_sub_Query)){
		$class_array[$i]['sub'][$j]['classname']	= $class_sub_Rs['classname'];
		$class_array[$i]['sub'][$j]['scid']	= $class_sub_Rs['scid'];
		$j++;
	}
	$i++;
}
$tpl->assign("class_array",$class_array);
$tpl->display("include_shopclass.html");
?>
