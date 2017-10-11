<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");

$tpl->assign($Search_Pack);
$tpl->display("include_article_search_box.html");
?>

