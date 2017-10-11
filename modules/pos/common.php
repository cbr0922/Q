<?php

/**
 * @file common.php
 *
 * 這個檔案用來定義 function。
 *
 * @copyright Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>. All rights reserved.
 */
class Database{
	public $_host      = "localhost";
	public $_port      = "3306";
	public $_resource  = false;
	public $_user      = NULL;
	public $_pass      = NULL;
	public $_database  = NULL;

	function __construct(){

	}

	function setup( $host, $port="3306", $user, $pass ){
		$this->_host = $host;
		$this->_port = strval($port);
		$this->_user = $user;
		$this->_pass = $pass;
	}

	function isConnected(){
		if ( $this->_resource === false ){
			return false;
		}
		else{
			return true;
		}
	}

	function set_encoding( $enc, $enc2 ){
		$res = mysql_query( "SET NAMES $enc" ,$this->_resource );
		$res = mysql_query( "SET collation_connection='".$enc2."' ", $this->_resource );
	}

	function use_database( $database_name ){
		$ret_code = 0;

		$this->_database = $database_name;

		$isSelected = mysql_selectdb( $this->_database , $this->_resource );
		if ( !$isSelected ){
			$ret_code = -1; // 切換資料庫失敗
		}

		return $ret_code;
	}

	function connect(){
		$ret_code = 0;
		$ret_data = NULL;

		if( !$this->_resource ){
			if( $this->_host != NULL && $this->_port != "0" ) {


				$this->_resource = mysql_connect( $this->_host.":".$this->_port, $this->_user, $this->_pass );

				if ( $this->_resource !== false ) {
					$ret_code = 0; // 連線成功

				}
				else{
					$ret_code = -1; // 建立資料庫連線失敗
					//echo('無法建立資料庫連線: ' . mysql_error());
				}
			}
			else{
				$ret_code = -2; // 連線參數錯誤

			}
		}

		return $ret_data;
	}

	function disconnect(){
		if( $this->_resource !== false ){
			mysql_close( $this->_resource );
			$this->_resource = false;
		}
	}

	function list_databases(){
		//$query = "SELECT TABLE_SCHEMA, TABLE_NAME"
		$query = "SELECT TABLE_SCHEMA"
			. " FROM information_schema.TABLES "
			. " WHERE TABLE_SCHEMA != 'information_schema' AND table_schema != 'mysql' "
			. " GROUP BY TABLE_SCHEMA ";
		$result = $this->query( $query );
		
		$databases = array();	
		foreach( $result as $row ){
			array_push( $databases, $row["TABLE_SCHEMA"] );
		}

		return $databases;
	}

	function list_tables( $database ){
		$query = "SELECT TABLE_SCHEMA, TABLE_NAME"
			. " FROM information_schema.TABLES "
			. " WHERE TABLE_SCHEMA = '".$database."' "
			. "";
		$result = $this->query( $query );
		
		$tables = array();	
		foreach( $result as $row ){
			array_push( $tables, $row["TABLE_NAME"] );
		}

		return $tables;
	}

	function query( $query ){
		$ret_code = 0;
		$ret_data = NULL;

		if( $this->_resource !== false ){
			$res = mysql_query( $query, $this->_resource );
			if( $res !== false ){
				$result = array();
				while( $row = mysql_fetch_assoc( $res ) ){
					array_push( $result, $row ); // TODO: 資料量大的時候，這裡的記憶體會過量
				}	
				$ret_data = $result;
			}
			else{
				$ret_code = -1; // query 語法失敗
			}
		}
		else{
			$ret_data = NULL;
		}

		return $ret_data;
	}

	function query_nodata( $query ){
		$ret_code = 0;

		if( $this->_resource !== false ){
			$res = mysql_query( $query, $this->_resource );
			if( $res !== false ){
				$ret_code = 0;
			}
			else{
				$ret_code = -2; // query fail
			}
		}
		else{
			$ret_code = -1; // database connection fail
		}

		return $ret_code;
	}

	/*
 	 * Singleton Pattern
	 */
	static private $_instance=null;

	static function getInstance(){
		if(! self::$_instance){
			self::$_instance= new self;
		}
		return self::$_instance;
	}
}


/** 
 * 資料庫表格的通用類別
 * 
 * 規則1: 變數名稱即是 Table 的欄位名稱
 * 規則2: 變數名稱會跳過 _ 開頭的命名
 * 規則3: $_unique 為註明 unique 的部分, 以 "," 來作分隔
 */
class TblObj{
	public $oid     = -1;
	public $_unique = "";
	public $_db     = NULL;
	public $_dbname = NULL;

	/**
	 * 檢查 MySQL is ready or not
	 * 
	 * @return true
	 * @return false
	 */
	public function _check_db(){
		if( $this->_db == NULL ){
			$this->_db = Database::getInstance();
			$this->_db->use_database( $this->_dbname );
		}

		if( $this->_db->isConnected() ){ 
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * 檢查資料庫表格是否存在
	 * 
	 * @return
	 *	Boolean, true or false
	 */
	public function exists(){
		$result = false;

		if( ! $this->_check_db() ){  return $result; }

		$query = sprintf( " SELECT TABLE_SCHEMA, TABLE_NAME "
				. " FROM information_schema.TABLES "
				. " WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s' "
				. " LIMIT 1"
				, $this->_dbname, get_class($this) );

                $res = $this->_db->query( $query );

		if( count( $res ) > 0 ){
			$result = true;
		}

		return $result;
	}

	public function columns(){
		$result = array();

		if( ! $this->_check_db() ){  return $result; }

                $res = $this->_db->query( "DESCRIBE ".get_class($this) );
		foreach( $res as $row ){
			array_push( $result, $row['Field'] );
		}

		return $result;
	}

	/**
	 *
	 * @return
	 *	Boolean, true or false
	 */
	public function create(){
		$result = NULL;

		$cols = array();

		foreach( $this as $key => $value ) {
			if( substr($key, 0, 1 ) == "_" ){ continue; }

			$uniqAry = explode( ",", $this->_unique );

			switch( gettype( $value ) ){
				case "integer":
					$type = "integer";
					break;
				case "string":
				default:
					$type = "text";
			}

			if ( $key == "oid" ){
				$type .= " primary key";
			}

			if ( in_array( $key, $uniqAry ) ){
				$type .= " unique";
			}

			array_push( $cols, "$key $type" );
		}
		$query  = "CREATE TABLE ".get_class($this)." ( ";
		$query .= implode( ",", $cols );
		$query .= ")";

		$db_path = getConf( 'DB3_FILE');
		if ( $db = new SQLite3( $db_path )) {
			logger_log( $query );
			@$ret = $db->exec($query);
			if( $ret ){
				$result = true;
			}
			else{
				$result = false;
			}
			@$db->close();
		}
		else{
			$result = false;
		}

		return $result;
	}

	/**
	 *
	 * @return
	 *	Boolean, true or false
	 */
	public function insert(){
		$result = false;

		$keys = array();
		$vals = array();

		foreach( $this as $key => $value ) {
			if( substr($key, 0, 1 ) == "_" ){ continue; }
			if( $key == "oid" ) { continue; }

			array_push( $keys, "\"$key\"" );
			switch( gettype( $value ) ){
				case "integer":
					array_push( $vals, "$value" );
					break;
				case "string":
				default:
					array_push( $vals, "\"$value\"" );
					break;
			}
		}

		$query  = "INSERT INTO ".get_class($this)." ( ";
		$query .= implode( ",", $keys );
		$query .= ") VALUES ( ";
		$query .= implode( ",", $vals );
		$query .= ")";

		$db_path = getConf( 'DB3_FILE');
		if ( $db = new SQLite3( $db_path )) {
			logger_log( $query );
			@$ret = $db->exec($query);
			if( $ret ){
				$result = true;
			}
			else{
				$result = false;
			}
			@$db->close();
		}
		else{
			$result = false;
		}

		return $result;
	}

	/**
	 * 取得資料庫資料陣列
	 *
	 * @param $cols
	 *    指定過濾的資料欄位，若不指定 ( 即 NULL ) 的話，就是取得所有資料
	 * @return
	 *     物件陣列，物件為表格物件。或是空陣列。
	 */
	public function select( $cols=NULL ){
		$result = array();

		if( !is_array($cols ) ){
			$tmp = $cols;
			$cols = array();
			array_push( $cols, $tmp );
		}

		$eqs = array();
		foreach( $this as $key => $value ) {
			if( substr($key, 0, 1 ) == "_" ){ continue; }

			if( in_array( $key, $cols) ){
				switch( gettype( $value ) ){
					case "integer":
						array_push( $eqs, "$key=$value" );
						break;
					case "string":
					default:
						array_push( $eqs, "$key=\"$value\"" );
						break;
				}
			}
		}

		$query = "SELECT * FROM ".get_class($this)." WHERE 1=1 ";
		if( count($eqs) > 0 ){
			$query .= " AND ". implode( " AND ", $eqs );
		}

		$db_path = getConf( 'DB3_FILE');
		if ( $db = new SQLite3( $db_path )) {
			logger_log( $query );
			@$ret = $db->query($query);
			if( $ret instanceof SQLite3Result ){
				while( $res = $ret->fetchArray(SQLITE3_ASSOC)){ 
					//print_r( $res );
					$self_class = get_class($this);
					$item = new $self_class();
					foreach( $res as $key => $val ){
						$item->$key = $val;
					}
					array_push( $result, $item );
				}	
			}
			else{
				; // do nothing
			}
			@$db->close();
		}
		return $result;
	}

	/**
	 * 取出一筆資料
	 * 
	 * 依指定的欄位，跟指定的值，來取出一筆資料。
	 * 例如: 
	 *   $data = $data->selectOne( "username", $username );
	 * 
	 * @param $cols
	 *  資料庫欄位，必須是物件的屬性列表裡
	 *
	 * @param $val
	 *  值，指定符合的內容
	 * 
	 * @return 
	 *  物件，回傳一筆表格物件，或是 NULL 表示沒符合項目
	 */
	public function selectOne( $cols, $val ){
		$result = NULL;

		// 如果  $cols 不是陣列，那就包成只有一個元素的陣列
		if( !is_array($cols ) ){
			$tmp = $cols;
			$cols = array();
			array_push( $cols, $tmp );
		}

		$eqs = array();
		foreach( $this as $key => $value ) {
			if( substr($key, 0, 1 ) == "_" ){ continue; }

			if( in_array( $key, $cols) ){
				switch( gettype( $value ) ){
					case "integer":
						array_push( $eqs, "$key=$val" );
						break;
					case "string":
					default:
						array_push( $eqs, "$key=\"$val\"" );
						break;
				}
			}
		}

		$query = "SELECT * FROM ".get_class($this)." WHERE 1=1 ";
		if( count($eqs) > 0 ){
			$query .= " AND ". implode( " AND ", $eqs );
		}

		$query .= " LIMIT 1";

		$db_path = getConf( 'DB3_FILE');
		if ( $db = new SQLite3( $db_path )) {
			logger_log( $query );
			@$ret = $db->query($query);
			if( $ret instanceof SQLite3Result ){
				while($res = $ret->fetchArray(SQLITE3_ASSOC)){ 
					$self_class = get_class( $this );
					$item = new $self_class();
					foreach( $res as $key => $val ){
						$item->$key = $val;
					}
					$result = $item;
				}
			}
			@$db->close();
		}
		return $result;
	}

	/**
	 *
	 * @return
	 *	Boolean, true or false
	 */
	public function update(){
		$result = false;

		$eqs = array();
		foreach( $this as $key => $value ) {
			if( substr($key, 0, 1 ) == "_" ){ continue; }

			switch( gettype( $value ) ){
				case "integer":
					array_push( $eqs, "$key=$value" );
					break;
				case "string":
				default:
					array_push( $eqs, "$key=\"$value\"" );
					break;
			}
		}

		$query = "UPDATE ".get_class($this)." SET ";
		if( count($eqs) > 0 ){
			$query .= implode( ",", $eqs );
		}
		$query .= " WHERE oid=".$this->oid."";

		$db_path = getConf( 'DB3_FILE');
		if ( $db = new SQLite3( $db_path ) ) {
			logger_log( $query );
			@$ret = $db->exec($query);
			if( $ret){
				$result = true;
			}
			@$db->close();
		}

		return $result;
	}

	/**
	 *
	 * @return
	 *	Boolean, true or false
	 */
	public function delete(){
		$result = false;

		$query = "DELETE FROM ".get_class($this)." ";
		$query .= " WHERE oid=".$this->oid."";

		$db_path = getConf( 'DB3_FILE');
		if ( $db = new SQLite3( $db_path ) ) {
			logger_log( $query );
			@$ret = $db->exec($query);
			if( $ret){
				$result = true;
			}
			@$db->close();
		}

		return $result;
	}

	/**
	 * 將 TblObj 的物件轉換成 Array 回傳
	 *
	 * @return
	 *    陣列, 陣列的 key 跟 val 跟物件裡的屬性的 key 跟 val 相同
	 */
	public function toarray(){
		$result = array();

		foreach( $this as $key => $value ) {
			if( substr($key, 0, 1 ) == "_" ){ continue; }

			$result[$key] = $value;
		}

		return $result;
	}

	/**
	 * 執行低階 SQL 指令並取得資料陣列
	 *
	 * @param $sql
	 *    即 SQL 語法
	 * @return
	 *    陣列，內容為資料庫欄位跟值的對應，或是空陣列。
	 */
	public static function sqlquery( $query ){
		$result = array();

		$db_path = getConf( 'DB3_FILE');
		if ( $db = new SQLite3( $db_path )) {
			logger_log( $query );
			@$ret = $db->query($query);
			if( $ret instanceof SQLite3Result ){
				while( $res = $ret->fetchArray(SQLITE3_ASSOC)){ 
					array_push( $result, $res );
				}	
			}
			else{
				; // do nothing
			}
			@$db->close();
		}
		return $result;
	}
}


class ntssi_order_table extends TblObj{

}


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


/**
 * UTF-8 轉 BIG-5
 *
 * @param $text in UTF-8
 * @return string in BIG-5
 */
function utf8_to_big5( $text ){
	$result = "";

	$sub = "//IGNORE";
	$utf8_tw = $text;
	$big5_tw = iconv("UTF-8", "BIG-5".$sub, $utf8_tw);

	$result = $big5_tw;
	return $result;
}

/**
 * BIG-5 轉 UTF-8
 *
 * @param $text in BIG-5
 * @return string in UTF-8
 */
function big5_to_utf8( $text ){
	$result = "";

	$sub = "//IGNORE";
	$big5_tw = $text;
	$utf8_tw = iconv("BIG-5", "UTF-8".$sub, $big5_tw);

	$result = $utf8_tw;
	return $result;
}


/**
 * 繁體字轉簡體字
 *
 * @param $text 繁體文字 in UTF-8
 * @return string 簡體文字 in UTF-8
 */
function text_tw2cn( $text ){
	$result = "";

	$sub = "//IGNORE";
	$utf8_tw = $text;
	$big5_tw = iconv("UTF-8", "BIG-5".$sub, $utf8_tw);
	$big5_tw = iconv("UTF-8", "BIG-5".$sub, $utf8_tw);
	$gb2312_cn = iconv( "BIG-5" , "GB2312".$sub, $big5_tw );
	$utf8_cn = iconv( "GB2312", "UTF-8".$sub, $gb2312_cn );

	$result = $utf8_cn;
	return $result;
}


require_once( dirname(__FILE__)."/../../Config/conf.global.php" );
require_once( dirname(__FILE__)."/../../Classes/function.php" );

$FUNCTIONS = new FUNCTIONS();

$var_db_host  = $INFO['DBhostname'];
$var_db_user  = $FUNCTIONS->authcode($INFO['DBuserName'],"DECODE",$INFO['site_userc']);
$var_db_pass  = $FUNCTIONS->authcode($INFO['DBpassword'],"DECODE",$INFO['site_userc']);
$var_database = $FUNCTIONS->authcode($INFO['DBname']    ,"DECODE",$INFO['site_userc']);



// 1. 先建立資料庫連線
{
        $db = Database::getInstance();

        if( !$db->isConnected() ){
                $db->setup( $var_db_host, "3306", $var_db_user, $var_db_pass );
                $db->connect();
                $db->set_encoding("utf8","utf8");
		$db->use_database( $var_database );

                if( !$db->isConnected() ){
                        echo( "資料庫連線有問題" );
                        exit();
                }
        }
}

//$ntssi_order_table = new ntssi_order_table();
//$ntssi_order_table->_dbname = $var_database;
//
////print_r( $ntssi_order_table->columns() );
//echo $ntssi_order_table->exists();

//exit();

// 2. 檢查相關 Database 跟 Tables
//{
//        $databases = $db->list_databases();
//        if( !in_array( $var_database, $databases ) ){
//                echo( "找不到指定的資料庫: $var_database \n" );
//                exit();
//        }
//
//        $tables = $db->list_tables( $var_database );
//        if( !in_array( $var_table , $tables ) ){
//                echo( "找不到指定的資料表: $var_table\n" );
//                echo( "新增資料表: $var_table\n" );
//
//                $ret_code = create_table_for_godds_sync( $var_database );       
//                if( $ret_code !== 0 ){
//                        echo "新增資料表 $var_table 失敗\n";
//                }
//                else{
//                        echo "新增資料表 $var_table 成功\n";
//                }
//
//        }
//}


?>
