<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$filename="追蹤商品(".$_GET['gid'].")統計_".date("Y-m-d",time());

$db_string = $DB->compile_db_insert_string( array (
	'mgroup_name'                 => trim($filename),
	'auto'                => 0,
)      );
$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
$Result_Insert=$DB->query($Sql);
$group_id = mysql_insert_id();

$Sql = "select u.user_id, u.email from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}collection_goods` as cg on u.user_id=cg.user_id where u.user_id!=0 and cg.gid='".$_GET['gid']."' group by cg.user_id";
$Query    = $DB->query($Sql);

while ($Rs=$DB->fetch_array($Query)) {
	$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) VALUES ('" . $group_id . "','" . $Rs['user_id'] . "','" . $Rs['email'] . "')";
	$DB->query($Sql);
}
$FUNCTIONS->setLog("新建郵件組");
echo "<script language=javascript>alert('郵件組已保存!');window.close();</script>";
exit;
?>
