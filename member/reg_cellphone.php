<?php
error_reporting(7);
session_start();
include("../configs.inc.php");
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
@header("Content-type: text/html; charset=utf-8");
if ($_SESSION['user_id']!=""){
	@header("location:index.php?hometype=" . $_GET['hometype']);
}
$tpl->display("reg_cellphone.html");
?>
