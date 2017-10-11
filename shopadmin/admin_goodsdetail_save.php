<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";




if ($_POST['Action']=='Insert' ) {
	$img   = $FUNCTIONS->Upload_File($_FILES['img']['name'],$_FILES['img']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	$db_string = $DB->compile_db_insert_string( array (
	'gid'          => intval($_POST['goods_id']),
	'detail_name'          => trim($_POST['detail_name']),
	'detail_bn'          => trim($_POST['detail_bn']),
	'detail_price'          => intval($_POST['detail_price']),
	'detail_pricedes'          => intval($_POST['detail_pricedes']),
	'detail_des'          => trim($_POST['detail_des']),
	'detail_pic'          => $img,
	'storage'          => intval($_POST['storage']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods_detail` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->header_location('admin_goodsdetail_list.php?goods_id=' . intval($_POST['goods_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	$img   = $FUNCTIONS->Upload_File($_FILES['img']['name'],$_FILES['img']['tmp_name'],$_POST['old_pic'],"../" . $INFO['good_pic_path']);
	$db_string = $DB->compile_db_update_string( array (
	'gid'          => intval($_POST['goods_id']),
	'detail_name'          => trim($_POST['detail_name']),
	'detail_bn'          => trim($_POST['detail_bn']),
	'detail_price'          => intval($_POST['detail_price']),
	'detail_pricedes'          => intval($_POST['detail_pricedes']),
	'detail_des'          => trim($_POST['detail_des']),
	'detail_pic'          => $img,
	'storage'          => intval($_POST['storage']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}goods_detail` SET $db_string WHERE detail_id=".intval($_POST['detail_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->header_location('admin_goodsdetail_list.php?goods_id=' . intval($_POST['goods_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['detail_id'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_detail` where detail_id=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->header_location('admin_goodsdetail_list.php?goods_id=' . intval($_POST['goods_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

if ($_GET['Action']=='DelPic' && isset($_GET['detail_id'])) {

	$detail_id  = intval($_GET['detail_id']);

	if ( $detail_id >0 ) {
		$Sql =   " select detail_pic from `{$INFO[DBPrefix]}goods_detail`  where detail_id='".$detail_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Img    =  $Result[detail_pic];
			$DB->query("update `{$INFO[DBPrefix]}goods_detail` set  detail_pic='' where detail_id='".$detail_id."'");
			@unlink ("../".$INFO['good_pic_path'] . "/" . $Img);
		}
		$FUNCTIONS->header_location("admin_goodsdetail.php?Action=Modi&goods_id=".intval($_GET['goods_id']) . "&detail_id=" . intval($_GET['detail_id']));
	}else{
		$FUNCTIONS->sorry_back("back","");
	}

}


?>