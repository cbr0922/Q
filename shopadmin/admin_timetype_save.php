<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'transtime_name'            => trim(strip_tags($_POST['transtime_name'])),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}transtime` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增宅配時間");
		$FUNCTIONS->header_location('admin_timetype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'transtime_name'            => trim(strip_tags($_POST['transtime_name'])),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}transtime` SET $db_string WHERE transtime_id=".intval($_POST['Transtime_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯宅配時間");
		$FUNCTIONS->header_location('admin_timetype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}transtime`  where transtime_id=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除宅配時間");
		$FUNCTIONS->header_location('admin_timetype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}

?>