<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


//这里是将POST过来的值，校验是否有ALL，如果有那么就只把ALL放到数据库中，否则就是全部的字符串
$mystring = trim($_POST['outcus']);
$my_array = explode(",",$mystring);
$findme   = 'ALL';

if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'mgroup_name'        => trim($_POST['name']),
  'auto'               => 0,
	));

	$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result=$DB->query($Sql);

	$group_id = mysql_insert_id();

	$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) SELECT '" . $group_id . "',user_id,email FROM `{$INFO[DBPrefix]}user` WHERE email IN('".implode("','",$my_array)."')";
	$DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("新增郵件組");
		$FUNCTIONS->header_location('admin_group_list.php');
	}else{
		$FUNCTIONS->sorry_back('admin_group_list.php',$Basic_Command['Back_System_Error']);
	}

}

if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'mgroup_name'        => trim($_POST['name']),
  'auto'               => $Auto,
	));

	$Sql = "UPDATE `{$INFO[DBPrefix]}mail_group` SET $db_string WHERE mgroup_id=".intval($_POST['Group_id']);
	$Result = $DB->query($Sql);

	$DB->query("delete from `{$INFO[DBPrefix]}mail_group_list` where group_id='".intval($_POST['Group_id'])."'");

	$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) SELECT '" . intval($_POST['Group_id']) . "',user_id,email FROM `{$INFO[DBPrefix]}user` WHERE email IN('".implode("','",$my_array)."')";
	$DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯郵件組");
		$FUNCTIONS->header_location('admin_group_list.php');
	}else{
		$FUNCTIONS->sorry_back('admin_group_list.php',$Basic_Command['Back_System_Error']);
	}

}


?>
