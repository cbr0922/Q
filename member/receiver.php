<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
include_once "../language/".$INFO['IS']."/Cart.php";
if ($_GET['act']=="modify" && intval($_GET['reid'])>0){
	$sql = "select * from `{$INFO[DBPrefix]}receiver` where reid='" . $_GET['reid'] . "'";
	$Query = $DB->query($sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$tpl->assign("receiver_name",        $Result['receiver_name']);
		$tpl->assign("receiver_email",        $Result['receiver_email']);
		$tpl->assign("post",        $Result['post']);
		$tpl->assign("receiver_tele",        MD5Crypt::Decrypt ($Result['receiver_tele'], $INFO['tcrypt'] ));
		$tpl->assign("receiver_mobile",        MD5Crypt::Decrypt ($Result['receiver_mobile'], $INFO['mcrypt'] ));
		$tpl->assign("county",        $Result['county']);
		$tpl->assign("province",        $Result['province']);
		$tpl->assign("city",        $Result['city']);
		$tpl->assign("reid",        $Result['reid']);
		$tpl->assign("addr",        $Result['addr']);
	}
	$tpl->assign("act",        "update");
}else{
	$tpl->assign("addr",       "");	
	$tpl->assign("act",        "insert");
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($Cart);
$tpl->display("receiver.html");

?>

