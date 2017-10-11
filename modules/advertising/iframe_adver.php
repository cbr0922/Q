<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");


$IframeAdv_tag = trim($_GET[IframeAdv_tag]);

$Sql = " select adv_id,adv_left_url,adv_right_url,adv_width,adv_height,adv_content,point_num,adv_left_img,adv_right_img from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=3 and adv_tag='".$IframeAdv_tag."'  order by rand() limit 0,1 ";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
//echo $IframeAdv_tag;
if ( $Num > 0 ){
	$Result = $DB->fetch_array($Query);
	$adv_id          =  $Result['adv_id'];
	$adv_url_left    =  trim($Result['adv_left_url']);
	$adv_url_right   =  trim($Result['adv_right_url']);
	$adv_width       =  $Result['adv_width'];
	$adv_height      =  $Result['adv_height'];
	$adv_content     =  $Result['adv_content'];
	$point_num       =  $Result['point_num'];
	$adv_left_img    =  trim($Result['adv_left_img']);
	$adv_right_img   =  trim($Result['adv_right_img']);

	$tpl->assign("Iframe_adver",  $adv_content); //Banner

	//$Banner = $FUNCTIONS->ImgTypeReturn("../".$INFO['advs_pic_path'],$adv_img,$adv_height,$adv_width);

	$point_num=intval($point_num+1);
	$Query = $DB->query(" update `{$INFO[DBPrefix]}advertising`  set point_num=".$point_num." where adv_id=".intval($adv_id));
	unset ($Query);
	unset ($Result);
	unset ($adv_content);
	unset ($point_num);
	unset ($adv_width);
	unset ($adv_height);
}

$tpl->display("iframe_adver.html");

?>
