<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


	//$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],$_POST['Nimg'],"../newspic");
	$db_string = $DB->compile_db_update_string( array (
	'ip'          =>  $_POST['ip'],
	'port'          =>  intval($_POST['port']),
	'user'          =>  $_POST['user'],
	'password'          =>  $_POST['password'],
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}msgconfig` SET $db_string ";

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯簡訊設置");
		$FUNCTIONS->header_location('admin_msgconfig.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



?>