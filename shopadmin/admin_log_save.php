<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


if ($_POST['act']=='Del' ) {

	$Array_id =  $_POST['cid'];
	$Num_id  = count($Array_id);

	for ($i=0;$i<$Num_id;$i++){
		$Sql = "select * from `{$INFO[DBPrefix]}adminlog` where id=".intval($Array_id[$i]);
		$Query    = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}adminlog`  where id=".intval($Array_id[$i]));
		$FUNCTIONS->setLog("刪除日誌：" . $Rs['admin'] . $Rs['content']);
	}
	$FUNCTIONS->header_location('admin_log.php');

}

?>