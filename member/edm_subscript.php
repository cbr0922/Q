<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

$info_id = 7;
$Query   = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=".$info_id." limit 0,1");
$Num   = $DB->num_rows($Query);

if ( $Num==0 )
$FUNCTIONS->header_location("../index.php");

if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$Content = $Result['info_content'];
}


$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
$tpl->assign("Content",        $Content);
$tpl->display("edm_subscript.html");
?>
