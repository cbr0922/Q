<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$Query = $DB->query("select * from `{$INFO[DBPrefix]}salermoney`");
$Result= $DB->fetch_array($Query);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	//$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],$_POST['Nimg'],"../newspic");
	$db_string = $DB->compile_db_update_string( array (
	'salerset'          =>  intval($_POST['salerset']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}salermoney` SET $db_string ";
}else{
	$db_string = $DB->compile_db_insert_string( array (
	'salerset'          =>  intval($_POST['salerset']),
	)      );

	$Sql = "insert into `{$INFO[DBPrefix]}salermoney` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
}
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("設置傭金比例");
		$FUNCTIONS->header_location('admin_saler_money.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



?>