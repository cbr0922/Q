<?php
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");

include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
@header("Content-type: text/html; charset=utf-8");


	$tpl->display("flight-check.html");
?>
