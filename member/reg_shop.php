<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
session_start();
include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

$tpl->display("reg_shop.html");
?>
