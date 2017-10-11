<?php
session_start();
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
@header("Content-type: text/html; charset=utf-8");
include("user.class.php");
$USER = new USERS();
switch($_GET['action']){
	case "CheckExit";
		$_GET['email'] = $_POST['email'];
		if($USER->checkExist($_GET['checktype'],$_GET['email']))
			$valid = false;
		else
			$valid = true;
		echo json_encode(array(
			'valid' => $valid,
		));
		break;
	case "CheckExit2";
		$_GET['username'] = $_POST['username'];
		$_GET['memberno'] = $_POST['memberno'];
		if($USER->checkExist($_GET['checktype'],$_GET['username'])||$_GET['memberno']=="")
			$valid = true;
		else
			$valid = false;
		echo json_encode(array(
			'valid' => $valid,
		));
		break;
	case "CheckExit3";
		$_GET['username'] = $_POST['username'];
		if($USER->checkExist($_GET['checktype'],$_GET['username']))
			$valid = true;
		else
			$valid = false;
		echo json_encode(array(
			'valid' => $valid,
		));
		break;
	case "CheckExit4";
		$valid = true;
		if($_POST['mobile']!=''){
			$_GET['mobile'] = $_POST['mobile'];
		}else {
			$_GET['mobile'] = $_POST['other_tel'];
		}
		$_GET['user_id'] = $_POST['user_id'];

		if(intval($_GET['user_id'])==0){
			$Sql = "select other_tel from `{$INFO[DBPrefix]}user`";
			$Query  = $DB->query($Sql);
			$Num    = $DB->num_rows($Query);
			if ($Num>0){
				while ($Rs = $DB->fetch_array($Query)) {
					if($_GET['mobile']==MD5Crypt::Decrypt (trim($Rs['other_tel']), $INFO['mcrypt'])){
						$valid = false;
						break;
					}
				}
			}
		}
		echo json_encode(array(
			'valid' => $valid,
		));
		break;
}
?>
