<?php
/**
 * 處理前台页面底部資訊的程式片段
 */
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");


include( dirname( __FILE__ )."/"."../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
include (RootDocumentShare."/setindex.php");

/** 裝載語言包*/
include RootDocument."/language/".$INFO['IS']."/Bottom_Pack.php";



/** 版權資訊*/

$info_id = '2';
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='2' or info_id='4' limit 0,2");
while ($Result  = $DB->fetch_array($Query)){
  if ($Result[info_id]==2){
	$tpl->assign("copyright_info",        $Result[info_content]);
  }
}
/** 服務資訊*/

$info_id = '31';
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='31'");
while ($Result  = $DB->fetch_array($Query)){
  if ($Result[info_id]==31){
	$tpl->assign("serve_info",        $Result[info_content]);
  }
}
/* FB像素AddToWishlist事件 */
$track_id = '8';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){

  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = "fbq('track', 'AddToWishlist');";
  }
	else $track_Js="";
	$tpl->assign("AddToWishlist_js",   $track_Js);
}
/*GA再行銷碼*/
$track_id = '12';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){

  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = $track_array[trackcode];
  }
	else $track_Js="";
	$tpl->assign("googleadservices_js",   $track_Js);
}
/*yahoo原生碼*/
$track_id = '16';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){

  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = $track_array[trackcode];
  }
	else $track_Js="";
	$tpl->assign("yahooUET1_js",   $track_Js);
}
//計數器
//$visit_Sql = "select count(*) as counta from `{$INFO[DBPrefix]}count` ";
//$visit_Query = $DB->query($visit_Sql);
//$visit_Result = $DB->fetch_array($visit_Query);
//if (intval($INFO['countStyle'])<=9){
//	$StrNum =  "0".intval($INFO['countStyle']);
//}else{
//	$StrNum =  intval($INFO['countStyle']);
//}
//$fg=sprintf("%08s",$visit_Result['counta']+1000000);	//修改此處的 %06s 为 %08s 就可以把六位計數器改為8位計數器
//$mc=chunk_split($fg,1,'|');		//每隔一個字元插入一個|號
//$arr=explode('|',$mc);		//按|號切開，存成數组
//for($i=0;$i<count($arr);$i++)
//{
//	if($arr[$i]!='')
//	{
//		$countimg .= "<img src=".$INFO['site_url']."/Resources/count/style".$StrNum."/".$arr[$i].".gif>";
//	}
//}
$MemberState =  intval($_SESSION['user_id'])>0 ? 1 : 0 ;
//$tpl->assign("countimg",$countimg);

$tpl->assign("Copyright",$Copyright);

$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮動廣告開關
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵廣告
$tpl->assign("fbmsg_radio",                 intval($INFO['fbmsg_radio'])); 
$tpl->assign("MemberState", intval($MemberState)); //會員狀態
$tpl->assign($Bottom_Pack);

$tpl->display("bottom.html");

?>
