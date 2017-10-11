<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Addpic' ) {

	$photo   = $FUNCTIONS->Upload_File($_FILES['photo']['name'],$_FILES['photo']['tmp_name'],'',"../".$INFO['good_pic_path']);

	$db_string = $DB->compile_db_insert_string( array (
	'good_id'                => intval($_POST['good_id']),
	'goodpic_title'          => trim($_POST['goodpic_title']),
	'goodpic_name'          =>  $photo,
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}good_pic` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->header_location("provider_goods_pic.php?good_id=".intval($_POST['good_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

if ($_POST['Action']=='DelPic' ) {

	@unlink ("../".$INFO['good_pic_path']."/".trim($_POST['GoodpicName']));
	$DB->query("delete from `{$INFO[DBPrefix]}good_pic` where goodpic_id=".intval($_POST['Delid']));
	$FUNCTIONS->header_location('provider_goods_pic.php?good_id='.intval($_POST['good_id']));
}
/*

if ($_POST['Action']=='Update' ) {

$Big_img = $FUNCTIONS->Upload_File($_FILES['bigimg']['name'],$_FILES['bigimg']['tmp_name'],trim($_POST['bigimg']),"img");

$db_string = $DB->compile_db_update_string( array (
'bid'                => intval($_POST['bid']),
'goodsname'          => trim($_POST['goodsname']),

)      );



$Sql = "UPDATE goods SET $db_string WHERE gid=".intval($_POST['gid']);
$Result_Insert = $DB->query($Sql);

if ($Result_Insert)
{
$FUNCTIONS->header_location('admin_goods_list.php');
}else{
$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
}

}

*/

if ($_POST['act']=='Del' ) {

	$FUNCTIONS->header_location('provider_goods_list.php');

}

?>