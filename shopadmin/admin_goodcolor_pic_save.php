<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Addpic' ) {

	$photo   = $FUNCTIONS->Upload_File($_FILES['photo']['name'],$_FILES['photo']['tmp_name'],'',"../".$INFO['good_pic_path']);
	$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['photo2']['name'],$_FILES['photo2']['tmp_name'],$ArrayPic,"../".$INFO['good_pic_path']);

	$db_string = $DB->compile_db_insert_string( array (
	'good_id'                => intval($_POST['good_id']),
	'color'          => trim($_POST['color']),
	'pic1'          =>  $photo,
	'pic2'          =>  $File_NewName[3],
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goodcolor` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->header_location("admin_goodcolor_pic.php?good_id=".intval($_POST['good_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

if ($_POST['Action']=='DelPic' ) {

	@unlink ("../".$INFO['good_pic_path']."/".trim($_POST['GoodpicName']));
	@unlink ("../".$INFO['good_pic_path']."/".trim($_POST['GoodpicName2']));
	$DB->query("delete from `{$INFO[DBPrefix]}goodcolor` where id=".intval($_POST['Delid']));
	$FUNCTIONS->header_location('admin_goodcolor_pic.php?good_id='.intval($_POST['good_id']));
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

	$FUNCTIONS->header_location('admin_goods_list.php');

}

?>