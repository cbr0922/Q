<?php
session_start();
if ($_SESSION['session_id'] == '' || empty($_SESSION['session_id']))  {
	//@header("location:provider_desktop.php");
	echo "<script language=javascript>location.href='provider_desktop.php';</script>";
	exit;
}else{
	//@header("location:provider_desktop.php");
	echo "<script language=javascript>location.href='provider_desktop.php';</script>";
	exit;
}
?>