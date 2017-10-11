<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

include "../language/".$INFO['IS']."/KeFu_Pack.php";
if ($_POST['Action']=='Insert' ) {
	$db_string = $DB->compile_db_insert_string( array (
	'k_chuli_name'             => trim($_POST['k_chuli_name']),
	'status'               => intval($_POST['status']),
	'checked'           => trim($_POST['checked']),
	'ifclose'           => trim($_POST['ifclose']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu_chuli` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增客服處理情況");
		if ($_POST['ifgo_on']==1) {
			$url_locate = 'admin_kefu_chuli.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
			$FUNCTIONS->header_location($url_locate);
		}else {
			$url_locate = 'admin_kefu_chuli_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
			$FUNCTIONS->header_location($url_locate);
		}
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'k_chuli_name'             => trim($_POST['k_chuli_name']),
	'status'               => intval($_POST['status']),
	'checked'           => trim($_POST['checked']),
	'ifclose'           => trim($_POST['ifclose']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}kefu_chuli` SET $db_string WHERE k_chuli_id=".intval($_POST['k_chuli_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{	
		$FUNCTIONS->setLog("編輯在線客服處理情況");
	if ($_POST['ifgo_on']==1) {
		$url_locate = 'admin_kefu_chuli.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}else {

		$url_locate = 'admin_kefu_chuli_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}kefu_chuli` where k_chuli_id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除在線客服處理情況");
		$url_locate = 'admin_kefu_chuli_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

?>