<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";





if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'type'       => trim($_POST['type']),
	'orderby'            => intval($_POST['orderby']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}contact_type` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增聯繫我們問題類別");
		if ($_POST['ifgo_on']==1) {
			$url_locate = 'admin_contact_type.php';
			$FUNCTIONS->header_location($url_locate);
		}else {
			$url_locate = 'admin_contact_type_list.php';
			$FUNCTIONS->header_location($url_locate);
		}
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'type'       => trim($_POST['type']),
	'orderby'            => intval($_POST['orderby']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}contact_type` SET $db_string WHERE type_id=".intval($_POST['type_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{	
	$FUNCTIONS->setLog("編輯聯繫我們問題類別");
	if ($_POST['ifgo_on']==1) {
		$url_locate = 'admin_contact_type.php';
		$FUNCTIONS->header_location($url_locate);
	}else {

		$url_locate = 'admin_contact_type_list.php';
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
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}contact_type` where type_id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除在線客服問題類別");
		$url_locate = 'admin_contact_type_list.php';
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

?>