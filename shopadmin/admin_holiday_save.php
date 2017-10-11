<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";




if ($_POST['Action']=='Insert' ) {
	$db_string = $DB->compile_db_insert_string( array (
	'name'          => trim($_POST['name']),
	'startdate'          => trim($_POST['startdate']),
	'enddate'          => trim($_POST['enddate']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}holiday` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增假日");
		$FUNCTIONS->header_location('admin_holiday_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'name'          => trim($_POST['name']),
	'startdate'          => trim($_POST['startdate']),
	'enddate'          => trim($_POST['enddate']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}holiday` SET $db_string WHERE hid=".intval($_POST['hid']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯假日");
		$FUNCTIONS->header_location('admin_holiday_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {
	$area_id =  $_POST['area_id'];
	$Num_bid  = count($area_id);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}holiday` where hid=".intval($area_id[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除假日信息");
		$FUNCTIONS->header_location('admin_holiday_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

?>