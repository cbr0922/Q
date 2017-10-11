<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$pos = strpos($_POST[gc_string],",");

//将POST过来串格式化
	if ($pos === false ){
        $gc_strings = trim($_POST[gc_string]);
	}else{
		$gc_string_array = explode(",",$_POST[gc_string]);
		$gc_string_array = array_unique($gc_string_array);
		$gc_strings = implode(",",$gc_string_array);
    }



if ($_POST['Action']=='Insert' ) {

	$img   = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],'',"../" . $INFO['logo_pic_path']);

	$db_string = $DB->compile_db_insert_string( array (
	'gc_name'               => trim($_POST['gc_name']),
	'gc_string'             => $gc_strings,
	'gc_pic'                => trim($img),
	'gc_link'               => trim($_POST['gc_link']),
	'tag'               => trim($_POST['tag']),
	'ifshop'               => intval($_POST['ifshop']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goodscollection` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增聚合商品");
		$FUNCTIONS->header_location('admin_goods_collection_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$img   = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],$_POST['old_pic'],"../"  . $INFO['logo_pic_path']);
	$db_string = $DB->compile_db_update_string( array (
	'gc_name'               => trim($_POST['gc_name']),
	'gc_string'             => $gc_strings,
	'gc_pic'                => trim($img),
	'gc_link'               => trim($_POST['gc_link']),
	'tag'               => trim($_POST['tag']),
	'ifshop'               => intval($_POST['ifshop']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}goodscollection` SET $db_string WHERE gc_id=".intval($_POST['gc_id']);
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯聚合商品");
		$FUNCTIONS->header_location('admin_goods_collection_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goodscollection`  where gc_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除聚合商品");
	$FUNCTIONS->header_location('admin_goods_collection_list.php');

}



if ($_GET['Action']=='DelPic' && isset($_GET['gc_id'])) {

	$gc_id  = intval($_GET['gc_id']);

	if ( $gc_id >0 ) {
		$Sql =   " select gc_pic from `{$INFO[DBPrefix]}goodscollection`  where gc_id='".$gc_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Img    =  $Result[logopic];
			$DB->query("update `{$INFO[DBPrefix]}goodscollection` set  gc_pic='' where gc_id='".$gc_id."'");
			@unlink ("../" . $INFO['logo_pic_path'] ."/".$Img);
			$FUNCTIONS->setLog("刪除聚合商品圖片");
		}
		$FUNCTIONS->header_location("admin_goods_collection.php?Action=Modi&gc_id=".intval($gc_id));
	}else{
		$FUNCTIONS->sorry_back("back","");
	}

}


?>