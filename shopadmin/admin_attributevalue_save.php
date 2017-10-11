<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";




if ($_POST['Action']=='Insert' ) {
	$db_string = $DB->compile_db_insert_string( array (
	'value'          => trim($_POST['valuename']),
	'attrid'          => intval($_POST['attrid']),
	'content'          => trim($_POST['content']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}attributevalue` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增商品類別屬性值");
		$FUNCTIONS->header_location('admin_attributevalue_list.php?attrid=' . intval($_POST['attrid']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'value'          => trim($_POST['valuename']),
	'attrid'          => intval($_POST['attrid']),
	'content'          => trim($_POST['content']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}attributevalue` SET $db_string WHERE valueid=".intval($_POST['valueid']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯商品類別屬性值");
		$FUNCTIONS->header_location('admin_attributevalue_list.php?attrid=' . intval($_POST['attrid']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['valueid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}attributevalue` where valueid=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除商品類別屬性值");
		$FUNCTIONS->header_location('admin_attributevalue_list.php?attrid=' . intval($_GET['attrid']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}
// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}attributevalue` where valueid=".intval($_GET['valueid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}attributevalue` SET content = '" . $_POST['content'] . "' WHERE valueid=".intval($_GET['valueid']);
		$Result = $DB->query($Sql);
		$array = array(
			'error' => false,
			'message' => '已自動保存'
		);
	}else{
		$array = array(
			'error' => true,
			'message' => '自動保存失敗'
		);
	}

    echo stripslashes(json_encode($array));
	
}
?>
