<?php
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include(Classes."/global.php");
include(RootDocument."/language/".$INFO['IS']."/Search_Pack.php");

$tpl->assign($Search_Pack);
$tpl->display("search_box_2.html");
?>

