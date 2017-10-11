<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

if (is_file("configs.inc.php")){
	include ("./configs.inc.php");
}elseif (is_file("../configs.inc.php")){
	include ("../configs.inc.php");
}
include ("global.php");
include (RootDocumentShare."/setindex.php");



//---------------<!-- Banner Start -->-----------------------------------------------
$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_type=3 and adv_display = 1 order by point_num asc limit 0,1 ";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
if ( $Num > 0 ){
	$Result = $DB->fetch_array($Query);
	$adv_id     =  $Result['adv_id'];
	$adv_url    =  $Result['adv_left_url'];
	$adv_width  =  $Result['adv_width'];
	$adv_height =  $Result['adv_height'];
	$point_num  =  $Result['point_num'];
	$adv_img    =  trim($Result['adv_img']);


	$Banner = $FUNCTIONS->ImgTypeReturn("../".$INFO['advs_pic_path'],$adv_img,$adv_height,$adv_width);

	$point_num=intval($point_num+1);
	$Query = $DB->query(" update `{$INFO[DBPrefix]}advertising` set point_num=".$point_num." where adv_id=".intval($adv_id));
	unset ($Query);
	unset ($Result);
	unset ($adv_url);
	unset ($adv_width);
	unset ($adv_height);
	unset ($adv_img);
}



$tpl->assign("Banner",  $Banner); //Banner
$tpl->assign("adv_url", $adv_url); //adv_url
$tpl->display("banner.html");

//---------------<!-- Banner End -->-----------------------------------------------

?>