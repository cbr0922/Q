<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_POST['Action']=='Insert' ) {


	$db_string = $DB->compile_db_insert_string( array (
	'companyname'          => $FUNCTIONS->smartshophtmlspecialchars($_POST['companyname']),
	'password'          => ($_POST['password']),
	'content'         => $FUNCTIONS->smartshophtmlspecialchars($_POST['content']),
	'pubtime'          => date("Y/m/d",time()),
	'level'        => intval($_POST['user_level'])

	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}company` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{

		
	$FUNCTIONS->setLog("新增公司");
		
		$FUNCTIONS->header_location('admin_company_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	/*
	$Query_old = $DB->query("select  username from user where username='".trim($_POST['username'])."' and user_id!=".intval($_POST['user_id'])." limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);

	if ($Num_old>0){
	$FUNCTIONS->sorry_back('back',$PROG_TAGS[ptag_1876]);
	}
	*/

	$db_string = $DB->compile_db_update_string( array (
	'companyname'         => $FUNCTIONS->smartshophtmlspecialchars($_POST['companyname']),
	'level'        => intval($_POST['user_level']),
	'password'        => ($_POST['password']),
	'content'             => $FUNCTIONS->smartshophtmlspecialchars(($_POST['content'])),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}company` SET $db_string WHERE id=".intval($_POST['user_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯公司信息");
		if(file_exists('../api/wordpress.php')) {
			include_once('../api/wordpress.php');
		}
		
		$FUNCTIONS->header_location('admin_company_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	if(file_exists('../api/wordpress.php')) {
		include_once('../api/wordpress.php');
	}

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}company`  where id=".intval($Array_bid[$i]));
		//还没有删除相关数据。。。。。。。。。。。。。。
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除公司");
		$FUNCTIONS->header_location('admin_company_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}

?>