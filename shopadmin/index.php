<?php
error_reporting(7);
session_start();
if ($_SESSION['LOGINADMIN_session_id'] == '' || empty($_SESSION['LOGINADMIN_session_id']))  {
	//header("location:desktop.php");
	echo "<script language=javascript>location.href='login.php';</script>";
	exit;
}else{
	echo "<script language=javascript>location.href='desktop.php';</script>";
	//header("location:desktop.php");
	exit;
}
?>