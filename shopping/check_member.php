<?php
session_start();
if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
	@header("Content-type: text/html; charset=utf-8");
	@header("location:../member/login_windows.php?type=" . $hometype . "&key=" . $_GET['key'] . "");
	exit;
}
?>
