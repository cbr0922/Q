<?php
include_once "Check_Admin.php";
$db_string = $DB->compile_db_insert_string( array (
	'bannercount'             => 1,
	'bannerorder'             => 0,
	'tag'             => $_GET['tag'],
	)      );
	 $Sql="INSERT INTO `{$INFO[DBPrefix]}index_banner` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	echo 1;
	exit;
?>
