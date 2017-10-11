<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Insert' ) {

	$ima       = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],'',"../".$INFO['link_pic_path']);
	$db_string = $DB->compile_db_insert_string( array (
	'link_title'                => trim($_POST['link_title']),
	'link_width'                => intval($_POST['link_width']),
	'link_height'               => intval($_POST['link_height']),
	'link_url'                  => trim($_POST['link_url']),
	'link_ima'                  => $ima,
	'link_display'              => intval($_POST['link_display']),
	'orderby'              => intval($_POST['orderby']),

	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}link` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增相關鏈接");
		$FUNCTIONS->header_location('admin_link_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$ima       = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],trim($_POST['Link_ima']),"../".$INFO['link_pic_path']);
	$db_string = $DB->compile_db_update_string( array (
	'link_title'                => trim($_POST['link_title']),
	'link_width'                => intval($_POST['link_width']),
	'link_height'               => intval($_POST['link_height']),
	'link_url'                  => trim($_POST['link_url']),
	'link_ima'                  => $ima,
	'link_display'              => intval($_POST['link_display']),
	'orderby'              => intval($_POST['orderby']),
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}link` SET $db_string WHERE link_id=".intval($_POST['Link_id']);
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯相關鏈接");
		$FUNCTIONS->header_location('admin_link_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}link` where link_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除相關鏈接");
	$FUNCTIONS->header_location('admin_link_list.php');
}

?>