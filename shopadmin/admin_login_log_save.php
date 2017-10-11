<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


if ($_POST['act']=='Del' ) {

	$Array_id =  $_POST['cid'];
	$Num_id  = count($Array_id);

	for ($i=0;$i<$Num_id;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}login_log`  where log_id=".intval($Array_id[$i]));
	}

	$FUNCTIONS->header_location('admin_login_log.php');

}

?>