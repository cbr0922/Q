<?php

/**
 * @copyright Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>. All rights reserved.
 */

require_once( dirname(__FILE__).'/common.php' );

/* 
 * 前置設定
 */
$var_sites = array(
		array( "id" => "tw"
			, "name"       => "UTV Taiwan"
			, "api_url"    => "http://tw.g-utv.com/modules/sync/api.php"
			, "api_key"    => "1qaz2wsx3edc"
			, "url_prefix" => "http://tw.g-utv.com/"
		     )
	      );

$UPLOAD_DIR       = dirname( __FILE__ )."/../../UploadFile/";
$var_api_key      = "1qaz2wsx3edc";
$var_site         = "tw";
$var_database     = "ddcscom_utv_tw";
$var_table        = "ntssi_goods_sync";
$var_page_size    = 1000;
$var_handle_limit = 3000;
$var_db_user      = "root";
$var_db_pass      = "!king2820";
$URL_PREFIX       = "";

/*
 * 建新 ntssi_goods_sync 資料表
 */
function create_table_for_godds_sync( $database ){
	$ret_code = 0;
	$ret_data = NULL;

	$db = Database::getInstance();
	$ret = $db->use_database( $database );
	if( $ret == 0 ){

		$query = <<<EOD
CREATE TABLE IF NOT EXISTS `ntssi_goods_sync` (
  `site` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL,
  `gid` int(11) NOT NULL,
  `chksum` varchar(100) NOT NULL,
  `last_chk` varchar(100) NOT NULL,
  `last_changed` varchar(100) NOT NULL,
  `checkstate` int(11) NOT NULL,
  KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOD;

		$ret = $db->query_nodata( $query );
		if( $ret !== 0 ){
			echo "建立資料庫失敗\n";
		}
	}
	else{
		echo "切換資料庫的步驟有問題\n";
		$ret_code = -1;
	}

	return $ret_code;
}


//echo("pre1. 先建立資料庫連線\n");
{
	$db = Database::getInstance();

	if( !$db->isConnected() ){
		$db->setup( "localhost", "3306", $var_db_user, $var_db_pass );
		$db->connect();
		$db->set_encoding("utf8","utf8");

		if( !$db->isConnected() ){
			echo( "資料庫連線有問題" );
			exit();
		}
	}
}

//echo("pre2. 檢查相關 Database 跟 Tables\n");
{
	$databases = $db->list_databases();
	if( !in_array( $var_database, $databases ) ){
		echo( "找不到指定的資料庫: $var_database \n" );
		exit();
	}

	$tables = $db->list_tables( $var_database );
	if( !in_array( $var_table , $tables ) ){
		//echo( "找不到指定的資料表: $var_table\n" );
		//echo( "新增資料表: $var_table\n" );

		$ret_code = create_table_for_godds_sync( $var_database );	
		if( $ret_code !== 0 ){
			//echo "新增資料表 $var_table 失敗\n";
		}
		else{
			//echo "新增資料表 $var_table 成功\n";
		}

	}

	$ret = $db->check_schema( $var_database, $var_table, "checkstate" );
	if( ! $ret ){
		$db->add_schema( $var_database, $var_table, "checkstate" , "INT(11)" , true , NULL , "審核狀態" );
	}
}

$db->use_database( $var_database );

?>
