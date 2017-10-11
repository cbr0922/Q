<?php
/**
 *
 * Mat's php libraries
 * @link http://www.giantyoung.com/
 * @author Chun-Yu Lee (Mat) <matlinuxer2@mail.com>
 * @copyright 2013 Mat
 * @license All rights reserved.
 */

if ( file_exists( dirname( __FILE__ )."/../../configs.inc.php" ) ){ define( "ISSMSP", true ); }
if ( file_exists( dirname( __FILE__ )."/../../includes/init.php"  ) ){ define( "ISECSP", true ); }

if ( file_exists( dirname(__FILE__)."/libMat.php" ) ){
	require_once( dirname(__FILE__)."/libMat.php" );
}
elseif ( file_exists( dirname(__FILE__)."/src/Mat.php" ) ){
	require_once( dirname(__FILE__)."/src/Mat.php" );
}

if ( defined("ISSMSP") ){
require_once( dirname( __FILE__ )."/../../configs.inc.php" );
// require_once( "orderClass.php" );
// require_once( 'user.class.php' );
// require_once( 'product.class.php' );
}

/* 
 * 通用變數及定義
 */
function getVar( $get_or_post, $var_name, $default_value ){
	$result = NULL;

	/// @todo 過濾 input
	if( $get_or_post == "post" ){
		if( array_key_exists( $var_name, $_POST) ) 
		{
			$result = $_POST["$var_name"];  
		}
	}
	/// @todo 過濾 input
	else if( $get_or_post == "get" ){
		if ( array_key_exists( $var_name, $_GET) ){
			$result = $_GET["$var_name"];
		}
	}
	else if( $get_or_post == "both" ){
		if ( array_key_exists( $var_name, $_GET) ){
			$result = $_GET["$var_name"];
		}
		else if( array_key_exists( $var_name, $_POST) ) 
		{
			$result = $_POST["$var_name"];  
		}
	}
	else{
		;//do nothing
	}

	$result = $result ? $result : $default_value;

	return $result;
}

function count_goods( $cond ){
	$result = 0;

	global $DB;

	$query = "SELECT COUNT(*) "
		." FROM ntssi_goods "
		.$cond
		." ";

	$ary = $DB->fetch_one_array( $query );
	$num = $ary[0];

	$result = $num;

	return $result;
}

function fetch_goods_metadata(){
	$result = array();

	global $DB;

	$cond = " WHERE 1 = 1 ";
	$idx  = 0;
	$end  = 10000;

	$query = "SELECT gid, bn, idate"
		." FROM ntssi_goods "
		.$cond
		." LIMIT $idx, $end ";

	$res = $DB->query( $query );
	while( $row = $DB->fetch_assoc( $res ) ){
		array_push( $result, $row );
	}

	return $result;
}

function list_goods( $cond, $idx, $len ){
	$result = array();

	global $DB;

	$beg = $idx;
	$end = $idx+$len;

	$query = "SELECT gid, bn, goodsno, goodsname, pricedesc, price, cost, provider_id, bid, chandi, weight, unit, ifpub "
		." FROM ntssi_goods "
		.$cond
		." LIMIT $idx, $end ";

	$res = $DB->query( $query );
	while( $row = $DB->fetch_assoc( $res ) ){
		array_push( $result, $row );
	}

	return $result;
}

function list_providers(){
	$result = array();

	global $DB;

	$query = "SELECT provider_id, provider_name "
		." FROM ntssi_provider ";

	$res = $DB->query( $query );
	while( $row = $DB->fetch_assoc( $res ) ){
		array_push( $result, $row );
	}

	return $result;
}

function list_providers2(){
	$result = array();

	global $DB;

	$query = "SELECT COUNT(*) AS total, provider_id "
		." FROM ntssi_goods "
		." GROUP BY provider_id ";

	$res = $DB->query( $query );
	while( $row = $DB->fetch_assoc( $res ) ){
		array_push( $result, $row );
	}

	return $result;
}

function list_bclasses(){
	$result = array();

	global $DB;

	$query = "SELECT bid, top_id, catname "
		." FROM ntssi_bclass ";

	$res = $DB->query( $query );
	while( $row = $DB->fetch_assoc( $res ) ){
		array_push( $result, $row );
	}

	return $result;
}

function get_uploadfile_path( $field_name ){
	$result = NULL;
	$ret_code = -1;

	global $_FILES;

	if ( array_key_exists( $field_name, $_FILES )
			&& array_key_exists( "Err", $_FILES[$field_name])
			&& $_FILES[$field_name]['Err'] != 0 ){
		$ret_code = -2; // 檔案上傳過程失敗
	}
	else{
		// 只取 1 項
		for( $i=0 ; $i < 1; $i++ ){
			$path = $_FILES[$field_name]['tmp_name'][$i];
			$name = $_FILES[$field_name]['name'][$i];

			if( is_file($path) ){
				$result = $path;
				$ret_code = 0;
			}
		}         
	}

	return $result;
}

function get_array_from_csv( $path, $limit, $delimit ){
	$ret_data = array();
	$ret_code = -1;

	$idx_bn        = -1;
	$idx_pricedesc = -1;
	$idx_price     = -1;
	$idx_cost      = -1;
	$idx_goodsname = -1;

	// 1. 開啟檔案
	$handle = fopen( $path, "r");
	if ( $handle !== FALSE) {

		// 先取出第一欄
		$row = 0;
		$data0 = fgetcsv($handle, 1000, $delimit );
		if( $data0 !== FALSE ){
			// 找出各欄位的對應編號
			for( $i=0; $i< count($data0); $i++ ){
				$word = $data0[$i];
				switch( $word ){
					case "bn":
						$idx_bn = $i;
						break;

					case "pricedesc":
						$idx_pricedesc = $i;
						break;

					case "price":
						$idx_price = $i;
						break;

					case "cost":
						$idx_cost = $i;
						break;

					case "goodsname":
						$idx_goodsname = $i;
						break;
				}
			}

			if( $idx_bn == -1 || $idx_pricedesc == -1 || $idx_price == -1 || $idx_cost == -1 || $idx_goodsname == -1 ){
				$ret_code = -4; // 有欄位沒有對應到，可能是資料格式錯誤
			}
			else{
				// 開始一行一行抓資料
				$row = 1;
				while ( $data = fgetcsv($handle, 1000, $delimit ) ) {
					if( $data !== FALSE ){

						$bn        = $data[$idx_bn];
						$pricedesc = $data[$idx_pricedesc];
						$price     = $data[$idx_price];
						$cost      = $data[$idx_cost];
						$goodsname = $data[$idx_goodsname];

						$item = array( 
								"bn"        => $bn,
								"pricedesc" => $pricedesc,
								"price"     => $price,
								"cost"      => $cost,
								"goodsname" => $goodsname
							     );

						array_push( $ret_data, $item );
					}
					else{
						$ret_code = -5; // fgetcsv() 讀取出錯
						//echo "處理行數 ".($row+1)." 出錯<br/>";
					}
					$row++;

					// 如果有 preview 的數量，到達數量後就不再讀取
					if( $limit > 0 && $row > $limit ){
						break;
					}
				}
			}
		}
		else{
			$ret_code = -3; // fgetcsv() 讀取出錯
		}

		fclose($handle);
	}
	else{
		$ret_code = -2; // 開啟檔案出錯
	}

	return $ret_data;
}

function update_goods_prices( $ary ){
	$ret_data = array();
	$ret_code = -1; 

	global $DB;

	$done = 0;
	$fail = 0;
	$list = array();

	for( $i=0; $i < count( $ary ); $i++ ){
		$bn        = trim( $ary[$i]["bn"] );
		$pricedesc = $ary[$i]["pricedesc"];
		$price     = $ary[$i]["price"];
		$cost      = $ary[$i]["cost"];

		if( $bn == "" ){
			$ret_code = -2; // bn 是空的
			$fail += 1;
			array_push( $list, "NULL" );
			continue;
		}
		else{
			$query = sprintf( "UPDATE ntssi_goods SET pricedesc=%s, price=%s, cost=%s WHERE bn=%s ;", $pricedesc, $price, $cost, $bn );
			//echo $query."<br/>";
			$res = $DB->query( $query );
			if ( $res ){
				$done += 1;
			}
			else{
				$fail += 1;
				array_push( $list, $bn );
			}
		}
	}

	$ret_data["done"] = $done;
	$ret_data["fail"] = $fail;
	$ret_data["fail_list"] = $list;

	return $ret_data;
}

if ( !function_exists('setup_schema') ) {
function setup_schema( $table, $column, $type_txt, $default_txt, $comment_txt ){
	global $DB, $INFO,$FUNCTIONS;

	$database = $FUNCTIONS->authcode($INFO['DBname'],"DECODE",$INFO['site_userc']); 
	//=====================
	$cols = array();	

	$query = "SELECT * "
		. " FROM information_schema.COLUMNS "
		. " WHERE TABLE_SCHEMA = '".$database."' "
		. " AND TABLE_NAME = '".$table."' "
		. " AND COLUMN_NAME = '".$column."' "
		. "";

	echo $query."\n";
	@$res = $DB->query( $query );
	$res_cnt = $DB->num_rows( $res );

	if( $res_cnt == 1 ){
		return; // 該 column 已存在，不需再作任何動作
	}

	$query  = "ALTER TABLE `$table` "
		." ADD `$column` "
		." $type_txt "
		." DEFAULT $default_txt "
		." COMMENT '$comment_txt'";

	echo $query."\n";
	@$res = $DB->query( $query );

	return $result;
}
}

function dencrypt($string, $isEncrypt = true, $key = '')
{

	$key = '23rcafgdf2e';    //加密密钥
	$dynKey = $isEncrypt ? hash('sha1', microtime(true)) : substr($string, 0, 40);    
	$fixedKey = hash('sha1', $key);


	$dynKeyPart1 = substr($dynKey, 0, 20);
	$dynKeyPart2 = substr($dynKey, 20);
	$fixedKeyPart1 = substr($fixedKey, 0, 20);
	$fixedKeyPart2 = substr($fixedKey, 20);

	$key = hash('sha1', $dynKeyPart1 . $fixedKeyPart1 . $dynKeyPart2 . $fixedKeyPart2);

	$string = $isEncrypt ? $fixedKeyPart1 . $string . $dynKeyPart2 : (isset($string{339}) ? gzuncompress(base64_decode(substr($string, 40))) : base64_decode(substr($string, 40)));

	$n = 0;
	$result = '';
	$len = strlen($string);

	for ($n = 0; $n < $len; $n++)
	{
		$result .= chr(ord($string{$n}) ^ ord($key{$n % 40}));
	}

	return $isEncrypt ? $dynKey . str_replace('=', '_', base64_encode($n > 299 ? gzcompress($result) : $result)) : substr($result, 20, -20);    
}

function encrypt($str, $key)
{
    return base64_encode( dencrypt ( $str, true, $key ) );
}

function decrypt($str, $key)
{  
    return dencrypt( base64_decode($str), false, $key );
}

function url_enc_for_auth( $secret, $info ){ 
        $keys = array( "user", "priv", "login", "expire", "id" ); 
        $url_ary = array();
        foreach( $keys as $key ){ 
                $url_txt = $key."=".encrypt( $info[$key], $secret );       
                array_push( $url_ary, $url_txt ); 
        }  

        $result = implode( "&", $url_ary ); 

        return $result;
}

function url_dec_for_auth( $secret, $info ){ 
        $keys = array( "user", "priv", "login", "expire", "id" );
        $result = array();
        foreach( $keys as $key ){
                $result[$key] = decrypt( $info[$key], $secret );
        }

        return $result;
}

if ( defined("ISSMSP") ){
class ClsOrder {
	/**
	 * 取得訂單資料物件
         * 
         * $order_obj = orderClass::get( 18142 );
         * echo $order_obj->info["receiver_mobile"];
         * echo $order_obj->detail[0]["price"];
	 * 
	 * @param	$order_id	訂單編號
	 * @return
	 *      Object, 一個 orderClass 的物件
	 */
	static function get( $order_id ){
		global $DB, $INFO;
		$result = new self();	

		$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where order_id = '" . intval($order_id) . "'";
		$Query_order          = $DB->query($Sql_order);
		$Rs_order             = $DB->fetch_array($Query_order);
		{
			$keys = array_keys( $Rs_order );
			foreach( $keys as $key ){
				if( strval( intval( $key ) ) != $key ){
					$result->info[$key] = $Rs_order[$key];
				}
			}
		}

		$result->detail = array();
		$Sql_detail = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order_id . "'";
		$Query_detail  = $DB->query($Sql_detail);
		while($Rs_detail=$DB->fetch_array($Query_detail)){
			//$item = new stdClass();
			$item = array();
			$keys = array_keys( $Rs_detail );
			foreach( $keys as $key ){
				if( strval( intval( $key ) ) != $key ){
					$item[$key] = $Rs_detail[$key];
				}
			}
			array_push( $result->detail, $item );
		}

		return $result;
	}

	static function getBySn( $order_sn ){
		global $DB, $INFO;
		$result = new self();	

		$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where order_serial= '" . strval($order_sn) . "'";
		$Query_order          = $DB->query($Sql_order);
		$Rs_order             = $DB->fetch_array($Query_order);
		{
			$keys = array_keys( $Rs_order );
			foreach( $keys as $key ){
				if( strval( intval( $key ) ) != $key ){
					$result->info[$key] = $Rs_order[$key];
				}
			}
		}
	
		$order_id = $result->info['order_id'];

		$result->detail = array();
		$Sql_detail = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order_id . "'";
		$Query_detail  = $DB->query($Sql_detail);
		while($Rs_detail=$DB->fetch_array($Query_detail)){
			//$item = new stdClass();
			$item = array();
			$keys = array_keys( $Rs_detail );
			foreach( $keys as $key ){
				if( strval( intval( $key ) ) != $key ){
					$item[$key] = $Rs_detail[$key];
				}
			}
			array_push( $result->detail, $item );
		}

		return $result;
	}

	/**
	 * 重新載入訂單物件
	 */
	function reload(){
		$order_id = $this->info["order_id"];
		$foo = self::get( $order_id );
		foreach (get_object_vars($foo) as $key => $value){
			$this->$key = $value;
		}
	}

	/**
	 * 取得訂單金額資料
	 * 
	 * @param	$sbj	金額項目
	 * @return
	 *      String, 對應的金額
	 */
	function getPrice( $sbj="" ){
		$result = array();

		$var1 = floatval(0);
		$var11 = floatval(0);
		foreach( $this->detail as $item ){
			//echo "market_price="      . $item["market_price"]  . "\n";
			//echo "price="             . $item["price"]         . "\n";
			//echo "bargain="           . $item["bargain"]       . "\n";
			//echo "memberorprice="     . $item["memberorprice"] . "\n";
			//echo "memberprice="       . $item["memberprice"]   . "\n";
			//echo "cost="              . $item["cost"]          . "\n";
			//echo "goodscount="        . $item["goodscount"]    . "\n";
			//echo "==================" . "\n";
			$var1  = $var1  +  ( floatval($item["price"]) * floatval($item["goodscount"]) );
			$var11 = $var11 +  ( floatval($item["price"]) * floatval($item["goodscount"]) - floatval( $item["bargain"] ) );
		}

		//echo "transport_price="       . $this->info["transport_price"]       . "\n";
		//echo "totalprice="            . $this->info["totalprice"]            . "\n";
		//echo "discount_totalPrices="  . $this->info["discount_totalPrices"]  . "\n";
		//echo "bargainPrice="          . $this->info["bargainPrice"]          . "\n";
		//echo "ticket_discount_money=" . $this->info["ticket_discount_money"] . "\n";
		//echo "ticketmoney="           . $this->info["ticketmoney"]           . "\n";

		$var2  = floatval( $this->info["transport_price"] );              // 配送費用
		$var21 = floatval( $this->info["bargainPrice"] );                 // 商品折價
		$var22 = floatval( $this->info["transport_bargain"] );            // 配送折價
		$var3  = floatval( $this->info["ticket_discount_money"] );        // 折價券折價
		$var4  = floatval( $this->info["discount_totalPrices"] );         // 折價後金額 
		$var5  = $var2 + ( $var1  - $var3 );                              // 原始總金額  
		$var55 = $var2 + ( $var11 - $var3 ) - $var22;                              // 議後總金額  
		$var6  = $var2 + $var4;                                           // 消費總金額  
		$var7  = abs( $var5 - $var6 );                                    // 議價差額 

		switch( $sbj ){
			case "商品總金額" :
				$result = $var1;
				break;
			case "配送費用":
				$result = $var2;
				break;
			case "折價券折價":
				$result = $var3;
				break;
			case "折價後金額":
				$result = $var4;
				break;
			case "原始總金額":
				$result = $var5;
				break;
			case "議後總金額":
				$result = $var55;
				break;
			case "消費總金額":
				$result = $var6;
				break;
			case "議價差額":
				$result = $var7;
				break;
			case "商品折價":
				$result = abs( $var21 );
				break;
			case "運費折價":
				$result = abs( $var22 );
				break;
			default:
				$result = 0;
		}

		$result = strval( $result );

		return $result;
	}

	/**
	 * 載入訂單變數至模版
	 *      
	 */
	function loadToTmpl( &$tpl ){

	}
}
}

if ( defined("ISSMSP") ){
class Good {


	static function addNewGoodsByBn( $bn ){
		global $DB, $INFO;

		$db_string = $DB->compile_db_insert_string( array (
					'bn'          => $bn,
					'ifpub'       => 0,
					'idate'       => time(),
					)      
				);

		$Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}

	/**
	 * 取得商品資料
	 * @param  $key		關鍵字
	 * @param  $type	檢索方式, 預設是 _gid
	 * @retval object	使用者物件，資料放在 $good_obj->info 裡，NULL 表示沒有這個商品
	 */
	static function get( $key, $type="gid"){
		global $DB,$INFO;

		switch($type){
			case "bn":	
				{
					$subsql = " bn='".trim($key)."'";
				} break;
			case "gid":	
			default:
				$subsql = " gid='".trim($key)."'";
		}
		$Sql = "select * from `{$INFO[DBPrefix]}goods`  where " . $subsql . " limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num>0){
			$result = new self();   
			$res = $DB->fetch_array($Query);
			$keys = array_keys( $res );
			foreach( $keys as $k ){
				if( strval( intval( $k ) ) != $k ){
					$result->info[$k] = $res[$k];
				}
			}

			return $result;
		}else{
			return NULL;
		}
	}

	/**
	 * 將資料更新寫進資料庫
	 */
	function save(){
		global $DB,$INFO;
		if( isset($this->info) && intval( $this->info['gid'] ) > 0 ){

			$kv_ary = $this->info;
			unset( $kv_ary['gid'] );
			
			$subsql_ary = array();
			foreach( $kv_ary as $k => $v ){
				$subsql_txt = " `$k`='$v' ";
				array_push( $subsql_ary, $subsql_txt );
			}
			$subsql = implode( ",", $subsql_ary );
			// $subsql = "";
			// $subsql .= " goodsname='"     .  $this->info['goodsname']    .  "' ";
			// $subsql .= ", intro='"        .  $this->info['intro']        .  "' ";
			// $subsql .= ", pricedesc='"    .  $this->info['pricedesc']    .  "' ";
			// $subsql .= ", price='"        .  $this->info['price']        .  "' ";
			// $subsql .= ", ifpub='"        .  $this->info['ifpub']        .  "' ";
			// $subsql .= ", gimg='"         .  $this->info['gimg']         .  "' ";
			// $subsql .= ", good_color='"   .  $this->info['good_color']   .  "' ";
			// $subsql .= ", cost='"         .  $this->info['cost']         .  "' ";
			// $subsql .= ", sale_name='"    .  $this->info['sale_name']    .  "' ";
			// $subsql .= ", designer_id='"  .  $this->info['designer_id']  .  "' ";
			// $subsql .= ", caser_id='"     .  $this->info['caser_id']     .  "' ";

			$Sql = "UPDATE `{$INFO[DBPrefix]}goods`  SET $subsql WHERE gid='".$this->info['gid']."'";
			// echo $Sql;
			$Query  = $DB->query($Sql);
		}
	}

	/**
	 * 取得這個商品的細節訊息
	 */
	function getInfo(){
		if( isset($this->info) && intval( $this->info['gid'] ) > 0 ){
			if( defined("PRODUCT") ){
				$prdt = new PRODUCT();
				$result = $prdt->getProductInfo( $this->info['gid'] );
			}
			else{
				; // @todo wait for implement
			}
		}
		return $result;
	}
}
}

define('DEFAULT_DEBUG_DIR', '/tmp/');
class Logger {
	private static $_LOGGER_DEBUG;

	/**
	 *
	 */
	public static function init($filename = '')
	{
		if ($filename != false && gettype($filename) != 'string') {
			self::error('type mismatched for parameter $dbg (should be false or the name of the log file)');
		}
		if ($filename === false) {
			self::$_LOGGER_DEBUG['filename'] = false;

		} else {
			if (empty ($filename)) {
				$debugDir = DEFAULT_DEBUG_DIR;
				$filename = $debugDir . 'debug.log';
			}

			if (empty (self::$_LOGGER_DEBUG['unique_id'])) {
				self::$_LOGGER_DEBUG['unique_id'] = substr(strtoupper(md5(uniqid(''))), 0, 4);
			}

			self::$_LOGGER_DEBUG['filename'] = $filename;
			self::$_LOGGER_DEBUG['indent'] = 0;

			self::trace('START Logger ******************');
		}
	}


	/**
	 *
	 */
	public static function log($str)
	{
		if (empty (self::$_LOGGER_DEBUG['unique_id'])) { self::init(); }
		$indent_str = ".";


		if (!empty(self::$_LOGGER_DEBUG['filename'])) {
			// Check if file exists and modifiy file permissions to be only
			// readable by the webserver
			if (!file_exists(self::$_LOGGER_DEBUG['filename'])) {
				touch(self::$_LOGGER_DEBUG['filename']);
				// Chmod will fail on windows
				@chmod(self::$_LOGGER_DEBUG['filename'], 0600);
			}
			for ($i = 0; $i < self::$_LOGGER_DEBUG['indent']; $i++) {

				$indent_str .= '|    ';
			}
			// allow for multiline output with proper identing. Usefull for
			// dumping cas answers etc.
			$str2 = str_replace("\n", "\n" . self::$_LOGGER_DEBUG['unique_id'] . ' ' . $indent_str, $str);
			error_log(self::$_LOGGER_DEBUG['unique_id'] . ' ' . $indent_str . $str2 . "\n", 3, self::$_LOGGER_DEBUG['filename']);
		}

	}

	/**
	 *
	 */
	public static function trace($str)
	{
		if (empty (self::$_LOGGER_DEBUG['unique_id'])) { self::init(); }
		$dbg = debug_backtrace();
		self::log($str . ' [' . basename($dbg[0]['file']) . ':' . $dbg[0]['line'] . ']');
	}

	/**
	 *
	 */
	public static function traceBegin()
	{
		if (empty (self::$_LOGGER_DEBUG['unique_id'])) { self::init(); }
		$dbg = debug_backtrace();
		$str = '=> ';
		if (!empty ($dbg[1]['class'])) {
			$str .= $dbg[1]['class'] . '::';
		}
		$str .= $dbg[1]['function'] . '(';
		if (is_array($dbg[1]['args'])) {
			foreach ($dbg[1]['args'] as $index => $arg) {
				if ($index != 0) {
					$str .= ', ';
				}
				if (is_object($arg)) {
					$str .= get_class($arg);
				} else {
					$str .= str_replace(array("\r\n", "\n", "\r"), "", var_export($arg, true));
				}
			}
		}
		if (isset($dbg[1]['file'])) {
			$file = basename($dbg[1]['file']);
		} else {
			$file = 'unknown_file';
		}
		if (isset($dbg[1]['line'])) {
			$line = $dbg[1]['line'];
		} else {
			$line = 'unknown_line';
		}
		$str .= ') [' . $file . ':' . $line . ']';
		self::log($str);
		if (!isset(self::$_LOGGER_DEBUG['indent'])) {
			self::$_LOGGER_DEBUG['indent'] = 0;
		} else {
			self::$_LOGGER_DEBUG['indent']++;
		}
	}

	/**
	 *
	 */
	public static function traceEnd($res = '')
	{
		if (empty (self::$_LOGGER_DEBUG['unique_id'])) { self::init(); }
		if (empty(self::$_LOGGER_DEBUG['indent'])) {
			self::$_LOGGER_DEBUG['indent'] = 0;
		} else {
			self::$_LOGGER_DEBUG['indent']--;
		}
		$dbg = debug_backtrace();
		$str = '';
		if (is_object($res)) {
			$str .= '<= ' . get_class($res);
		} else {
			$str .= '<= ' . str_replace(array("\r\n", "\n", "\r"), "", var_export($res, true));
		}

		self::log($str);
	}
}

/**
 * 用來合併檔案的路徑
 * 特色: 會去掉尾端的 / 號, 同時，會將 // 換成 / 號
 * ex: path_join( "/path", "/to/", "file" ) => /path/to/file
 */
function path_join(){
	$args_ary = func_get_args();
	$args_cnt = func_num_args();
	// for( $i=0; $i<$args_cnt; $i++ ){
	//         $arg = $args_ary[$i];
	// }

	$path = implode( "/", $args_ary );
	$path = str_replace( "//", "/", $path );
	$path = trim( $path );
	$eop  = substr( $path, -1 );
	if ( $eop == "/" ){
		$path = substr( $path, 0, -1 );
	}

	return $path;
}

if ( defined("ISSMSP") ){
function SMSP_load_mods(){
	$dirs = scandir( path_join(RootDocument, "modules" ) );
	foreach( (array) $dirs as $dir ){
		$d_name = $dir;
		$d_path = path_join( RootDocument, "modules", $d_name ) ;
		if( is_dir( $d_path ) && $d_name != "." && $d_name != ".." ) {
			$mod_lib_path = path_join( $d_path, "lib".$d_name.".php" );
			$mod_conf_key = "mod.".$d_name.".enable";
			if ( file_exists( $mod_lib_path ) && isset( $GLOBALS['INFO'][$mod_conf_key] ) && $GLOBALS['INFO'][$mod_conf_key] == "yes" ){
				require_once( $mod_lib_path );
			}
		}
	}

}
}

?>
