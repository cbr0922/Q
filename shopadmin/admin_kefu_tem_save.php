<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'k_tem_name'             => trim($_POST['k_tem_name']),
	'status'               => intval($_POST['status']),
	'checked'              => trim($_POST['checked']),
	'k_tem_con'              => trim($_POST['k_tem_con']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu_tem` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增在線客服回覆樣板");
		if ($_POST['ifgo_on']==1) {
			$url_locate = 'admin_kefu_tem.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
			$FUNCTIONS->header_location($url_locate);
		}else {
			$url_locate = 'admin_kefu_tem_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
			$FUNCTIONS->header_location($url_locate);
		}
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'k_tem_name'             => trim($_POST['k_tem_name']),
	'status'               => intval($_POST['status']),
	'checked'           => trim($_POST['checked']),
	'k_tem_con'              => trim($_POST['k_tem_con']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}kefu_tem` SET $db_string WHERE k_tem_id=".intval($_POST['k_tem_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{	 
	$FUNCTIONS->setLog("編輯在線客服回覆樣板");
	if ($_POST['ifgo_on']==1) {
		$url_locate = 'admin_kefu_tem.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}else {

		$url_locate = 'admin_kefu_tem_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
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
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}kefu_tem` where k_tem_id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除在線客服回覆樣板");
		$url_locate = 'admin_kefu_tem_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

?>