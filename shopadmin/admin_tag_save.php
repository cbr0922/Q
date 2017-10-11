<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";





if ($_POST['Action']=='Insert' ) {
	$db_string = $DB->compile_db_insert_string( array (
	'tagname'          => trim($_POST['tagname']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}tag` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增TAG");
		$FUNCTIONS->header_location('admin_tag_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'tagname'          => trim($_POST['tagname']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}tag` SET $db_string WHERE tagid=".intval($_POST['tagid']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯TAG");
		$FUNCTIONS->header_location('admin_tag_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['tagid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}tag` where tagid=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除AG");
		$FUNCTIONS->header_location('admin_tag_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

?>