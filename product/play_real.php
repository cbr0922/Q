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
<object id="video2" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width=530 height=400> 
<param name="_ExtentX" value="11906"> 
<param name="_ExtentY" value="8996"> 
<param name="AUTOSTART" value="-1"> 
<param name="SHUFFLE" value="0"> 
<param name="PREFETCH" value="0"> 
<param name="NOLABELS" value="0"> 
<param name="SRC" value="{$Video_url}"> 
<param name="CONTROLS" value="ImageWindow"> 
<param name="CONSOLE" value="Clip1"> 
<param name="LOOP" value="0"> 
<param name="NUMLOOP" value="0"> 
<param name="CENTER" value="0"> 
<param name="MAINTAINASPECT" value="0"> 
<param name="BACKGROUNDCOLOR" value="#000000"> 
<embed src="4.rpm" type="audio/x-pn-realaudio-plugin" console="Clip1" controls="ImageWindow" width=530 height=400 autostart="false"></embed> 
</object> 
<object id="video1" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width=530 height=60> 
<param name="_ExtentX" value="11906"> 
<param name="_ExtentY" value="1588"> 
<param name="AUTOSTART" value="-1"> 
<param name="SHUFFLE" value="0"> 
<param name="PREFETCH" value="0"> 
<param name="NOLABELS" value="0"> 
<param name="CONTROLS" value="ControlPanel,StatusBar"> 
<param name="CONSOLE" value="Clip1"> 
<param name="LOOP" value="0"> 
<param name="NUMLOOP" value="0"> 
<param name="CENTER" value="0"> 
<param name="MAINTAINASPECT" value="0"> 
<param name="BACKGROUNDCOLOR" value="#000000"> 
<embed type="audio/x-pn-realaudio-plugin" console="Clip1" controls="ControlPanel,StatusBar" width=530 height=60 autostart="false"></embed> 
</object>
EOF;



$tpl->assign($Good);
$tpl->assign("Goodsname",$Goodsname);
$tpl->assign("Intro",$Intro);
$tpl->assign("EMBED",$EMBED);
$tpl->assign("Video_url",$Video_url);
$tpl->display("product_playmedia.html");
?>
