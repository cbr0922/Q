<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='22'");
while ($Result  = $DB->fetch_array($Query)){
  if ($Result[info_id]==22){
	$tpl->assign("Content",        $Result[info_content]);      
  }
}

$Query1 = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='15'");
while ($Result1  = $DB->fetch_array($Query1)){
  if ($Result1[info_id]==15){
	$tpl->assign("Content1",        $Result1[info_content]);      
  }
}
/* FB像素CompleteRegistration事件 */
$track_id = '11';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){

  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = "<script>fbq('track', 'CompleteRegistration');	</script>";
  }
	else $track_Js="";
	$tpl->assign("CompleteRegistration_js",   $track_Js);
}
/*GA轉換代碼*/
$track_id = '14';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){

  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = $track_array[trackcode];
  }
	else $track_Js="";
	$tpl->assign("googleadservices1_js",   $track_Js);
}


$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);

$tpl->display("reg_ok.html");
?>

