<?php
session_start();
include("../configs.inc.php");
include("global.php");
@header("Content-type: text/html; charset=utf-8");
include("user.class.php");
$USER = new USERS();
switch($_GET['action']){
	case "CheckExit";
		$_GET['email'] = $_REQUEST['fieldValue'];
		$result[0]=$_REQUEST['fieldId'];
		if($USER->checkExist($_GET['checktype']))
			$result[1] = false;
		else
			$result[1] = true;
		echo json_encode($result);
		break;
	case "CheckExit2";
		$_GET['email'] = $_REQUEST['fieldValue'];
		$result[0]=$_REQUEST['fieldId'];
		if($USER->checkExist($_GET['checktype']))
			$result[1] = true;
		else
			$result[1] = false;
		echo json_encode($result);
		break;
}
?>