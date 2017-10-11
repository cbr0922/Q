<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include "../language/".$INFO['IS']."/Desktop_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

$Level_query = $DB->query(" select level_id,level_name from `{$INFO[DBPrefix]}user_level`");
while ($Rs_level = $DB->fetch_array($Level_query)){
	$gSql = "select * from `{$INFO[DBPrefix]}mail_group` where auto=1 and userlevel='" . $Rs_level['level_id'] . "'";
	$gQuery    = $DB->query($gSql);
	$gNum   = $DB->num_rows($gQuery);
	if($gNum>0){
		$gResult = $DB->fetch_array($gQuery);
		$group_id = $gResult['mgroup_id'];
	}else{
		$db_string = $DB->compile_db_insert_string( array (
		'mgroup_name'                 => $Rs_level['level_name'],
		'auto'                => 1,
		'userlevel'           => $Rs_level['level_id'],
		)      );
		$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
		$group_id = mysql_insert_id();	
	}
	$DB->query(" delete from `{$INFO[DBPrefix]}mail_group_list` where group_id='" . $group_id . "'");
	$Query_usr  = $DB->query(" select user_id,email from `{$INFO[DBPrefix]}user` where user_level='" . $Rs_level['level_id'] . "'");
	while ($Rs=$DB->fetch_array($Query_usr)) {
		$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) VALUES ('" . $group_id . "','" . $Rs['user_id'] . "','" . $Rs['email'] . "')";
		$DB->query($Sql);
	}
}


@header ("location:admin_group_list.php");
exit;
?>