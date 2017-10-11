<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";






if ($_POST['Action']=='Insert' ) {
	$db_string = $DB->compile_db_insert_string( array (
	'trans_id'          => intval($_POST['trans_id']),
	'content'          => trim($_POST['content']),
	'groupname'          => trim($_POST['groupname']),
	'money'          => intval($_POST['money']),
	'permoney'          => intval($_POST['permoney']),
	'mianyunfei'          => intval($_POST['mianyunfei']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}transgroup` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	
	$group_id = mysql_insert_id();
	
	if (is_array($_POST['area_id'])){
		foreach($_POST['area_id'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}areatrans` (area_id,group_id) values ('" . intval($k) . "','" . $group_id . "')";
			$DB->query($sql);
		}
	}

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增地區運費");
		$FUNCTIONS->header_location('admin_areagroup_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'trans_id'          => intval($_POST['trans_id']),
	'content'          => trim($_POST['content']),
	'groupname'          => trim($_POST['groupname']),
	'money'          => intval($_POST['money']),
	'permoney'          => intval($_POST['permoney']),
	'mianyunfei'          => intval($_POST['mianyunfei']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}transgroup` SET $db_string WHERE group_id=".intval($_POST['group_id']);

	$Result_Insert = $DB->query($Sql);
	
	$attr_sql = "select * from `{$INFO[DBPrefix]}areatrans` where group_id='" . intval($_POST['group_id']) . "'";
	$Query_attr    = $DB->query($attr_sql);
	$ic=0;
	$Num_attr   = $DB->num_rows($Query_attr);
	while($Rs_arrt=$DB->fetch_array($Query_attr)){
			$attr_class[$ic]=$Rs_arrt['area_id'];
			$ic++;
	}
	if (is_array($attr_class)){
		foreach($attr_class as $v=>$k){
			if (is_array($_POST['area_id'])){
				if (!in_array($k,$_POST['area_id'])){
					$sql = "delete from `{$INFO[DBPrefix]}areatrans` where area_id='" . intval($k) . "' and group_id='" . intval($_POST['group_id']) . "'";
					$DB->query($sql);
				}
			}
		}
	}
	
	if (is_array($_POST['area_id'])){
		foreach($_POST['area_id'] as $v => $k){
			
			$attr_sql = "select * from `{$INFO[DBPrefix]}areatrans` where area_id='" . intval($k) . "' and group_id='" . intval($_POST['group_id']) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}areatrans` (area_id,group_id) values ('" . intval($k) . "','" . intval($_POST['group_id']) . "')";
				$DB->query($sql);
			}
		}
	}else{
		$sql = "delete from `{$INFO[DBPrefix]}areatrans` where group_id='" . intval($_POST['group_id']) . "'";
		$DB->query($sql);
	}

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯地區運費");
		$FUNCTIONS->header_location('admin_areagroup_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['group_id'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}transgroup` where group_id=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}areatrans` where group_id=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除地區運費");
		$FUNCTIONS->header_location('admin_areagroup_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

?>