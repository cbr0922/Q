<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Insert' ) {

	if(is_array($_POST['mail'])){
		$mail = implode(",",$_POST['mail']);	
	}
	$db_string = $DB->compile_db_insert_string( array (
	'groupname'                => trim($_POST['groupname']),
	'maillist'                => trim($mail),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}operatergroup` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	$opid = mysql_insert_id();

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增管理員組");
		$FUNCTIONS->header_location('admin_operatergroup_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	if(is_array($_POST['mail'])){
		$mail = implode(",",$_POST['mail']);	
	}
	$db_string = $DB->compile_db_update_string( array (
	'groupname'                => trim($_POST['groupname']),
	'maillist'                => trim($mail),
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}operatergroup` SET $db_string WHERE opid=".intval($_POST['opid']);
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯管理員組");
		$FUNCTIONS->header_location('admin_operatergroup_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}operatergroup`  where opid=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除管理員組");
	$FUNCTIONS->header_location('admin_operatergroup_list.php');

}

?>