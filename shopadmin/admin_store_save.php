<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";




if ($_POST['Action']=='Insert' ) {
	$img_menu   = $FUNCTIONS->Upload_File($_FILES['map']['name'],$_FILES['map']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	$db_string = $DB->compile_db_insert_string( array (
	'store_name'          => trim($_POST['store_name']),
	'store_code'          => trim($_POST['store_code']),
	'province'          => trim($_POST['province']),
	'city'          => trim($_POST['city']),
	'address'          => trim($_POST['address']),
	'tel'          => trim($_POST['tel']),
	'map'       =>  $img_menu,
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}store` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增門市信息");
		$FUNCTIONS->header_location('admin_store_list.php?top_id=' . intval($_POST['top_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	if ($_FILES['map']['name']!=""){
	 	$img_menu   = $FUNCTIONS->Upload_File($_FILES['map']['name'],$_FILES['map']['tmp_name'],$_POST['old_map'],"../" . $INFO['good_pic_path']);
	}else{
		$img_menu   = $_POST['old_map'];
	}
	$db_string = $DB->compile_db_update_string( array (
	'store_name'          => trim($_POST['store_name']),
	'store_code'          => trim($_POST['store_code']),
	'province'          => trim($_POST['province']),
	'city'          => trim($_POST['city']),
	'address'          => trim($_POST['address']),
	'tel'          => trim($_POST['tel']),
	'map'       =>  $img_menu,
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}store` SET $db_string WHERE store_id=".intval($_POST['store_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯門市信息");
		$FUNCTIONS->header_location('admin_store_list.php?top_id=' . intval($_POST['top_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {
	$area_id =  $_POST['area_id'];
	$Num_bid  = count($area_id);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}store` where store_id=".intval($area_id[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除門市信息");
		$FUNCTIONS->header_location('admin_store_list.php?top_id=' . intval($_GET['top_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

if ($_GET['Action']=='delPic' && intval($_GET['id'])>0  && $_GET['pic']!='' ) {
	$Sql = "select map from  `{$INFO[DBPrefix]}store`  where store_id=".intval($_GET['id'])." limit 0,1";
	$Query =  $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		@unlink("../".$INFO['good_pic_path']."/".$_GET['pic']);
	}

	$DB->query("update `{$INFO[DBPrefix]}store` set  map='' where store_id=".intval($_GET['id'])." limit 1");
	$FUNCTIONS->setLog("刪除門市信息圖片");
	$FUNCTIONS->header_location('admin_store.php?store_id=' . $_GET['id'] . '&Action=Modi' );
}
?>