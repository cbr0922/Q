<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";




if ($_POST['Action']=='Insert' ) {
	$db_string = $DB->compile_db_insert_string( array (
	'areaname'          => trim($_POST['areaname']),
	'top_id'          => intval($_POST['top_id']),
	'zip'          => trim($_POST['zip']),
	'areatype'          => trim($_POST['areatype']),
	'membercode'          => trim($_POST['membercode']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}area` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增地區");
		$FUNCTIONS->header_location('admin_area_list.php?top_id=' . intval($_POST['top_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'areaname'          => trim($_POST['areaname']),
	'zip'          => trim($_POST['zip']),
	'membercode'          => trim($_POST['membercode']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}area` SET $db_string WHERE area_id=".intval($_POST['area_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯地區");
		$FUNCTIONS->header_location('admin_area_list.php?top_id=' . intval($_POST['top_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {
	$area_id =  $_POST['area_id'];
	$Num_bid  = count($area_id);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}area` where area_id=".intval($area_id[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除地區");
		$FUNCTIONS->header_location('admin_area_list.php?top_id=' . intval($_GET['top_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

?>