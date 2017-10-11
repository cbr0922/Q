<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( "../configs.inc.php");
include("global.php");
include (RootDocumentShare."/setindex.php");
$tpl->assign("subclass",               $subclass);
$tpl->assign($Bottom_Pack);
$tpl->display("online_service.html");
?>
