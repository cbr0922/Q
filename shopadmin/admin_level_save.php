<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'level_name'            => trim($_POST['level_name']),
	'level_num'             => intval($_POST['level_num']),
	'pricerate'            => trim($_POST['pricerate']),
	'vip_yearmoney'             => intval($_POST['vip_yearmoney']),
	'vip_money'             => intval($_POST['vip_money']),
	'vip_days'             => intval($_POST['vip_days']),
	'sort'             => intval($_POST['sort']),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}user_level` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	$levelid = mysql_insert_id();
	/*
	if ($_POST['pricerate']>0){
		$dSql = "delete from `{$INFO[DBPrefix]}member_price` where m_level_id='" . $levelid . "'";
		$DB->query($dSql);
		$Sql      = "select * from `{$INFO[DBPrefix]}goods`";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		if($Num>0){
			while ($Rs=$DB->fetch_array($Query)) {
				if($Rs['pricedesc']>0){

					$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price,m_detail_id) values(".$Rs['gid'].",".$levelid.",".intval($_POST['pricerate']*0.01*intval($Rs['pricedesc'])).",0)";
					$Result = $DB->query($Sql);
				}
			}
		}
		$Sql      = "select * from `{$INFO[DBPrefix]}goods_detail`";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		if($Num>0){
			while ($Rs=$DB->fetch_array($Query)) {
				if($Rs['detail_pricedes']>0){
					$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price,m_detail_id) values(".$Rs['gid'].",".$levelid.",".intval($_POST['pricerate']*0.01*intval($Rs['detail_pricedes'])).",".$Rs['detail_id'].")";
					$Result = $DB->query($Sql);
				}
			}
		}
	}
	*/



	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增會員級別");
		$FUNCTIONS->header_location('admin_level_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'level_name'            => trim($_POST['level_name']),
	'level_num'             => intval($_POST['level_num']),
	'pricerate'            => trim($_POST['pricerate']),
	'vip_yearmoney'             => intval($_POST['vip_yearmoney']),
	'vip_money'             => intval($_POST['vip_money']),
	'vip_days'             => intval($_POST['vip_days']),
	'sort'             => intval($_POST['sort']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}user_level` SET $db_string WHERE level_id=".intval($_POST['level_id']);

	$Result_Insert = $DB->query($Sql);
	/*
	if ($_POST['pricerate']>0){
		$Sql      = "select * from `{$INFO[DBPrefix]}goods`";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		if($Num>0){
			while ($Rs=$DB->fetch_array($Query)) {
					$Sql = "update `{$INFO[DBPrefix]}member_price` set m_price='" . intval($_POST['pricerate']*0.01*intval($Rs['pricedesc'])) . "' where  m_goods_id=".intval($Rs['gid'])." and m_level_id=".$_POST['level_id']." and m_detail_id=0";
			$Result = $DB->query($Sql);
					$Result = $DB->query($Sql);
			}
		}

		$Sql      = "select * from `{$INFO[DBPrefix]}goods_detail`";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		if($Num>0){
			while ($Rs=$DB->fetch_array($Query)) {
					$Sql = "update `{$INFO[DBPrefix]}member_price` set m_price='" . intval($_POST['pricerate']*0.01*intval($Rs['detail_pricedes'])) . "' where  m_goods_id=".intval($Rs['gid'])." and m_level_id=".$_POST['level_id']." and m_detail_id='" . $Rs['detail_id'] . "'";
			$Result = $DB->query($Sql);
					$Result = $DB->query($Sql);
			}
		}
	}
	*/

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯會員級別");
		$FUNCTIONS->header_location('admin_level_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}user_level` where level_id=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除會員級別");
		$FUNCTIONS->header_location('admin_level_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}

?>
