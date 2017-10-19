<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'ticketname'          => $FUNCTIONS->smartshophtmlspecialchars($_POST['ticketname']),
	'ticketcode'          => ($_POST['ticketcode']),
	'money'          => ($_POST['money']),
	'pub_starttime'          => ($_POST['pub_starttime']),
	'pub_endtime'          => ($_POST['pub_endtime']),
	'use_starttime'          => ($_POST['use_starttime']),
	'use_endtime'          => ($_POST['use_endtime']),
	'type'          => ($_POST['type']),
	'moneytype'          => ($_POST['moneytype']),
	'goods_ids'          => ($_POST['goods_ids']),
	'ordertotal'          => intval($_POST['ordertotal']),
	'canmove'          => intval($_POST['canmove']),	
	'count'          => intval($_POST['count']),	
	)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}ticket` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	if ($Result_Insert)
	{
		
	$FUNCTIONS->setLog("新增折價券");
		
		$FUNCTIONS->header_location('admin_ticket_list.php');
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
	'ticketname'          => $FUNCTIONS->smartshophtmlspecialchars($_POST['ticketname']),
	'ticketcode'          => ($_POST['ticketcode']),
	'money'          => ($_POST['money']),
	'pub_starttime'          => ($_POST['pub_starttime']),
	'pub_endtime'          => ($_POST['pub_endtime']),
	'use_starttime'          => ($_POST['use_starttime']),
	'use_endtime'          => ($_POST['use_endtime']),
	'type'          => ($_POST['type']),
	'moneytype'          => ($_POST['moneytype']),
	'goods_ids'          => ($_POST['goods_ids']),
	'ordertotal'          => intval($_POST['ordertotal']),
	'canmove'          => intval($_POST['canmove']),	
	'count'          => intval($_POST['count']),	
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}ticket` SET $db_string WHERE ticketid=".intval($_POST['ticketid']);
	$Result_Insert = $DB->query($Sql);
	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯折價券");
		$FUNCTIONS->header_location('admin_ticket_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

if ($_POST['act']=='Del' ) {
	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}ticket`  where ticketid=".intval($Array_bid[$i]));
		//还没有删除相关数据。。。。。。。。。。。。。。
	}

	if ($Result)
	{
		$FUNCTIONS->setLog("刪除折價券");
		$FUNCTIONS->header_location('admin_ticket_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
?>