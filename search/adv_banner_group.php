<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=6 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') order by orderby";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$adv_array = array();
$i = 0;
while($Result = $DB->fetch_array($Query)){
	$adv_array[$i]['img'] = $Result['adv_content'];
	$adv_array[$i]['url'] = $Result['adv_left_url']==""?"#":$INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$Result['adv_id']  . "&url=" .urlencode($Result['adv_left_url']);
	$adv_array[$i]['title'] = $Result['adv_title'];
	$DB->query("update `{$INFO[DBPrefix]}advertising` set point_num=point_num+1 where adv_id=".intval($Result['adv_id']));  
	$i++;
}
//print_r($adv_array);
$tpl->assign("adv_array",     $adv_array);
$tpl->display("adv_banner_group.html");
?>
