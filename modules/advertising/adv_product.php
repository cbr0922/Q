<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=3 and adv_tag like 'adv_banner" . $_GET['bid'] ."' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "')  order by orderby asc";
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
$tpl->assign("adv_Num",     $adv_Num);
$tpl->display("adv_product.html");
?>