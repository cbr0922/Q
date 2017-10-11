<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include ("global.php");
include_once "../language/".$INFO['IS']."/Bottom_Pack.php";



$info_id = intval($FUNCTIONS->Value_Manage($_GET['info_id'],'','back',''));
$Query   = $DB->query("select title,info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=".$info_id." limit 0,1");
$Num     = $DB->num_rows($Query);

if ( $Num==0 ){ //如果不存在资料
	if ($INFO['staticState']=='open'){
		$FUNCTIONS->header_location($INFO[site_url]."/HTML_C/index.html");
	}else{
		$FUNCTIONS->header_location("../index.php");
	}
}

$Back_Url    = $INFO['staticState']=='open' ?  $INFO[site_url]."/HTML_C/index.html"  : "../index.php" ;
$Contact_url = $INFO['staticState']=='open' ?  $INFO[site_url]."/HTML_C/help_8.html" : "contact.php" ;

if ($Num>0){
	$Result_Article = $DB->fetch_array($Query);
	$Content = $Result_Article['info_content'];
	$Title = $Result_Article['title'];
}


$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("Title",          $Title);         //标题
$tpl->assign("Content",        $Content);       //新闻内容
$tpl->display("Aboutour.html");
?>
