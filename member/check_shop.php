<?php
session_start();
if ($_SESSION['shopid']=="" || empty($_SESSION['shopid'])){
	@header("Content-type: text/html; charset=utf-8");
	@header("location:shop_login.php");
}
?>
