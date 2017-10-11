<?php
error_reporting(0);
include( dirname(__FILE__)."/"."../../configs.inc.php" );
include("global.php");
include(RootDocument."/language/".$INFO['IS']."/Link_Pack.php");

@header("Content-type: text/html; charset=utf-8");


$Sql = "select link_id,link_title,link_width,link_height,link_url,link_ima from `{$INFO[DBPrefix]}link` where link_display=1 and link_ima!='' order by link_id desc limit 0,10";

$Query = $DB->query($Sql);
$i=0;
while ( $FriRs = $DB->fetch_array($Query)){
	$Friend_link[$i]['link_id']     = $FriRs['link_id'] ;
	$Friend_link[$i]['link_title']  = $FriRs['link_title'] ;
	$Friend_link[$i]['link_width']  = $FriRs['link_width'] ;
	$Friend_link[$i]['link_height'] = $FriRs['link_height'] ;
	$Friend_link[$i]['link_url']    = $FriRs['link_url'] ;
	$Friend_link[$i]['link_ima']    = $FriRs['link_ima'] ;
	$i++;
}

$tpl->assign("Friend_link_title1",  $Friend_link[0]['link_title']); //友情连接标题一
$tpl->assign("Friend_link_title2",  $Friend_link[1]['link_title']); //  .....
$tpl->assign("Friend_link_title3",  $Friend_link[2]['link_title']); //  .....
$tpl->assign("Friend_link_title4",  $Friend_link[3]['link_title']); //  .....
$tpl->assign("Friend_link_title5",  $Friend_link[4]['link_title']); //友情连接标题五
$tpl->assign("Friend_link_title6",  $Friend_link[5]['link_title']);
$tpl->assign("Friend_link_title7",  $Friend_link[6]['link_title']);
$tpl->assign("Friend_link_title8",  $Friend_link[7]['link_title']);
$tpl->assign("Friend_link_title9",  $Friend_link[8]['link_title']);
$tpl->assign("Friend_link_title10", $Friend_link[9]['link_title']);
$tpl->assign("Friend_link_title11", $Friend_link[10]['link_title']);
$tpl->assign("Friend_link_title12", $Friend_link[11]['link_title']);




$tpl->assign("Friend_link_width1",  $Friend_link[0]['link_width']); //友情连接宽一
$tpl->assign("Friend_link_width2",  $Friend_link[1]['link_width']); //  .....
$tpl->assign("Friend_link_width3",  $Friend_link[2]['link_width']); //  .....
$tpl->assign("Friend_link_width4",  $Friend_link[3]['link_width']); //  .....
$tpl->assign("Friend_link_width5",  $Friend_link[4]['link_width']); //友情连接宽五
$tpl->assign("Friend_link_width6",  $Friend_link[5]['link_width']);
$tpl->assign("Friend_link_width7",  $Friend_link[6]['link_width']);
$tpl->assign("Friend_link_width8",  $Friend_link[7]['link_width']);
$tpl->assign("Friend_link_width9",  $Friend_link[8]['link_width']);
$tpl->assign("Friend_link_width10", $Friend_link[9]['link_width']);
$tpl->assign("Friend_link_width11", $Friend_link[10]['link_width']);
$tpl->assign("Friend_link_width12", $Friend_link[11]['link_width']);


$tpl->assign("Friend_link_height1",  $Friend_link[0]['link_height']); //友情连接高一
$tpl->assign("Friend_link_height2",  $Friend_link[1]['link_height']); //  .....
$tpl->assign("Friend_link_height3",  $Friend_link[2]['link_height']); //  .....
$tpl->assign("Friend_link_height4",  $Friend_link[3]['link_height']); //  .....
$tpl->assign("Friend_link_height5",  $Friend_link[4]['link_height']); //友情连接高五
$tpl->assign("Friend_link_height6",  $Friend_link[5]['link_height']);
$tpl->assign("Friend_link_height7",  $Friend_link[6]['link_height']);
$tpl->assign("Friend_link_height8",  $Friend_link[7]['link_height']);
$tpl->assign("Friend_link_height9",  $Friend_link[8]['link_height']);
$tpl->assign("Friend_link_height10", $Friend_link[9]['link_height']);
$tpl->assign("Friend_link_height11", $Friend_link[10]['link_height']);
$tpl->assign("Friend_link_height12", $Friend_link[11]['link_height']);


$tpl->assign("Friend_link_url1",  $Friend_link[0]['link_url']); //友情连接一
$tpl->assign("Friend_link_url2",  $Friend_link[1]['link_url']); //  .....
$tpl->assign("Friend_link_url3",  $Friend_link[2]['link_url']); //  .....
$tpl->assign("Friend_link_url4",  $Friend_link[3]['link_url']); //  .....
$tpl->assign("Friend_link_url5",  $Friend_link[4]['link_url']); //友情连接五
$tpl->assign("Friend_link_url6",  $Friend_link[5]['link_url']);
$tpl->assign("Friend_link_url7",  $Friend_link[6]['link_url']);
$tpl->assign("Friend_link_url8",  $Friend_link[7]['link_url']);
$tpl->assign("Friend_link_url9",  $Friend_link[8]['link_url']);
$tpl->assign("Friend_link_url10", $Friend_link[9]['link_url']);
$tpl->assign("Friend_link_url11", $Friend_link[10]['link_url']);
$tpl->assign("Friend_link_url12", $Friend_link[11]['link_url']);


$tpl->assign("Friend_link_ima1",  $Friend_link[0]['link_ima']); //友情连接图片一
$tpl->assign("Friend_link_ima2",  $Friend_link[1]['link_ima']); //  .....
$tpl->assign("Friend_link_ima3",  $Friend_link[2]['link_ima']); //  .....
$tpl->assign("Friend_link_ima4",  $Friend_link[3]['link_ima']); //  .....
$tpl->assign("Friend_link_ima5",  $Friend_link[4]['link_ima']); //友情连接图片五
$tpl->assign("Friend_link_ima6",  $Friend_link[5]['link_ima']);
$tpl->assign("Friend_link_ima7",  $Friend_link[6]['link_ima']);
$tpl->assign("Friend_link_ima8",  $Friend_link[7]['link_ima']);
$tpl->assign("Friend_link_ima9",  $Friend_link[8]['link_ima']);
$tpl->assign("Friend_link_ima10", $Friend_link[9]['link_ima']);
$tpl->assign("Friend_link_ima11", $Friend_link[10]['link_ima']);
$tpl->assign("Friend_link_ima12", $Friend_link[11]['link_ima']);

$tpl->assign("Friend_link", $Friend_link); //友情连接
unset($Sql);
unset($Query);






$tpl->assign($Link_Pack);
$tpl->display("link_friend.html");


?>
