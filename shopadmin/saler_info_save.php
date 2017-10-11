<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


	//$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],$_POST['Nimg'],"../newspic");
	$db_string = $DB->compile_db_update_string( array (
	'name'             => trim($_POST['name']),
	'password'           => trim($_POST['password']),
	'tel'    => trim($_POST['tel']),
	'addr'              => trim($_POST['addr']),
	'email'              => trim($_POST['email']),
	'bankuser'             => trim($_POST['bankuser']),
	'bankname'            => trim($_POST['bankname']),
	'bank'               => trim($_POST['bank']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}saler` SET $db_string WHERE id=".intval($_SESSION['sa_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->header_location('saler_info.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



?>