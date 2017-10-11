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
<EMBED id=mePlay style="BORDER-RIGHT: #666666 1px solid; BORDER-TOP: #666666 1px solid; BORDER-LEFT: #666666 1px solid; BORDER-BOTTOM: #666666 1px solid" codeBase=http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,5,0803 src="{$Video_url}"  type=application/x-oleobject classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" standby="Loading Windows Media Player components..." loop="2">
</EMBED>
EOF;


$tpl->assign($Good);
$tpl->assign("Goodsname",$Goodsname);
$tpl->assign("Intro",$Intro);
$tpl->assign("EMBED",$EMBED);
$tpl->assign("Video_url",$Video_url);
$tpl->display("product_playmedia.html");
?>

