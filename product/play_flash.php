<?php
error_reporting(7);
include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
@header("Content-type: text/html; charset=utf-8");

$Goods_id  = $FUNCTIONS->Value_Manage($_GET['id'],$_POST['id'],'back','');  //判断是否有正常的ID进入
$Sql =   "select g.goodsname,g.video_url,g.intro from `{$INFO[DBPrefix]}goods` g  where   g.gid=".$Goods_id." limit 0,1";
$Query   = $DB->query($Sql);
$Num   = $DB->num_rows($Query);

if ( $Num==0 ) //如果不存在资料
$FUNCTIONS->sorry_back("close","");

if ($Num>0){
	$Result_goods = $DB->fetch_array($Query);
       $Goodsname = $Result_goods['goodsname'];
	   $Intro     = nl2br($Result_goods['intro']);
       $Video_url = trim($Result_goods['video_url']);
	}




$EMBED = <<<EOF
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width=580 ><param name=movie value="{$Video_url}"><param name=quality value=medium><PARAM NAME=wmode VALUE=transparent></object>
EOF;


$tpl->assign($Good);
$tpl->assign("Goodsname",$Goodsname);
$tpl->assign("Intro",$Intro);
$tpl->assign("EMBED",$EMBED);
$tpl->assign("Video_url",$Video_url);
$tpl->display("product_playmedia.html");


?>