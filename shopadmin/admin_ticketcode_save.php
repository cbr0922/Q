<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_POST['Action']=='Insert' ) {

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}ticketcode` where ticketcode='".trim($_POST['ticketcode'])."'");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		$FUNCTIONS->sorry_back("back","此折價券號碼已存在!");
	}
	
	$db_string = $DB->compile_db_insert_string( array (
	'ticketcode'          => ($_POST['ticketcode']),
	'ticketid'          => intval($_POST['ticketid']),

	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}ticketcode` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{


		$FUNCTIONS->setLog("新增折價券號碼");

		$FUNCTIONS->header_location('admin_ticketcode_list.php?ticketid=' . $_POST['ticketid']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}ticketcode`  where codeid=".intval($Array_bid[$i]));
		//还没有删除相关数据。。。。。。。。。。。。。。
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除折價券號碼");
		$FUNCTIONS->header_location('admin_ticketcode_list.php?ticketid=' . $_POST['ticketid']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}

?>
