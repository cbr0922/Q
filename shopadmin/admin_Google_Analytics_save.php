<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");



if ($_GET['action']=='Google_AnalyticsSave' ) {

	$db_string = $DB->compile_db_update_string( array (
	'info_content'        => trim(strip_tags($_GET[uacct])),
	)      );

	 $Sql = "UPDATE `{$INFO[DBPrefix]}admin_info` SET $db_string WHERE info_id=4";

	$Result = $DB->query($Sql);
	$FUNCTIONS->setLog("編輯GOOGLE統計分析");
	if ($Result)
	{
     echo 1;
	}else{
     echo 0;
	}

}
?>