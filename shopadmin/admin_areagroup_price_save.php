<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";






if ($_POST['Action']=='Insert' ) {
	$db_string = $DB->compile_db_insert_string( array (
	'group_id'          => intval($_POST['group_id']),
	'startweight'          => trim($_POST['startweight']),
	'endweight'          => trim($_POST['endweight']),
	'price'          => intval($_POST['price']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}transgroup_price` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	
	
	
	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增地區重量運費");
		$FUNCTIONS->header_location('admin_areagroup_pricelist.php?group_id=' . $_POST['group_id']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'startweight'          => trim($_POST['startweight']),
	'endweight'          => trim($_POST['endweight']),
	'price'          => intval($_POST['price']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}transgroup_price` SET $db_string WHERE tp_id=".intval($_POST['tp_id']);

	$Result_Insert = $DB->query($Sql);
	
	

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯地重量區運費");
		$FUNCTIONS->header_location('admin_areagroup_pricelist.php?group_id=' . $_POST['group_id']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['group_id'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}transgroup_price` where tp_id=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除地區重量運費");
		$FUNCTIONS->header_location('admin_areagroup_pricelist.php?group_id=' . $_POST['group_id']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

?>