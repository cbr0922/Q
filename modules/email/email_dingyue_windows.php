<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../../configs.inc.php" );
include ("global.php");
include(RootDocument."/language/".$INFO['IS']."/Email_Pack.php");


$tpl->assign($Email_Pack);
$tpl->display("email_dingyue_windows.html");
?>

