<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");

if (isset($_COOKIE['viewgoods'])){
		foreach($_COOKIE['viewgoods'] as $k=>$v){
			setcookie("viewgoods[" . $k . "]", "");
		}
	}
//print_r($_COOKIE['viewgoods']);
$FUNCTIONS->header_location("../index.php");

?>