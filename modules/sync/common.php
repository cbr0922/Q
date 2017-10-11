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

	/**
	 * 資料表(Table)的規格
	 */
	function list_columns( $database, $table ){
		$cols = array();	

		$query = "SELECT * "
			. " FROM information_schema.COLUMNS "
			. " WHERE TABLE_SCHEMA = '".$database."' "
			. " AND TABLE_NAME = '".$table."' "
			. "";

		$result = $this->query( $query );
		foreach( $result as $row ){
			array_push( $cols, $row );
		}

		return $cols;
	}

	/**
	 * 檢查資料庫的資料表欄位
	 *
	 * @param $db		資料庫
	 * @param $tbl		資料表
	 * @param $col		欄位
	 *
	 * @retval boolean	是否存在
	 */
	function check_schema( $db, $tbl, $col ){
		$cols = $this->list_columns( $db, $tbl );

		$isFound = false;
		foreach( $cols as $row ){
			if( $col === $row['COLUMN_NAME'] ){
				$isFound = true;
			}
		}

		if( $isFound ){ 
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * 新增資料庫的資料表欄位
	 *
	 * @param $db		資料庫
	 * @param $tbl		資料表
	 * @param $col		欄位
	 * @param $type		型別
	 * @param $is_null	允許NULL
	 * @param $default	預設值
	 * @param $comment	註解
	 *
	 * @retval boolean	新增是否成功
	 */
	function add_schema( $db, $tbl, $col, $type, $is_null, $default, $comment ){
		$result = false;
		
		$null_txt = "";
		if( $is_null ){
			$null_txt = "NULL";
		}
		else{
			$null_txt = "NOT NULL";
		}

		if( gettype($default) === "NULL" ){ $default = "NULL"; }

		$query  = "ALTER TABLE `$db`.`$tbl` "
			." ADD `$col` "
			." $type "
			." $null_txt "
			." DEFAULT $default "
			." COMMENT '$comment'";

		echo $query."\n";
		@$ret = $this->query_nodata( $query );
		if( $ret === 0  ){
			$result = true;
		}
		else{
			$result = false;
		}

		return $result;
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


class GoodSyncList {
	public $_data = array();
	public $_keys = array();

	/**
         * 初始函式
	 */
	function __construct( $goods_sync_list ){
		$this->_data = $goods_sync_list;

		// 建 key 列表，並先排序過
		foreach( $this->_data as $item ){
			array_push( $this->_keys, $item["key"] );
			sort( $this->_keys ); 
		}
	}

	
	/**
	 * 取 keys 列表的總數
	 */
	function total(){
		$ret_data = count( $this->_data );

		return $ret_data;
	}

	/**
	 * 取 keys 列表的最大值
	 *
	 * @return string	最大的 key
	 */
	function max(){
		$len = $this->total();
		$ret_data = $this->_keys[$len-1];

		return $ret_data;
	}

	/**
	 * 取 keys 列表的最小值
	 * @return string	最小的 key
	 */
	function min(){
		$len = $this->total();
		$ret_data = $this->_keys[0];

		return $ret_data;
	}

	/**
	 * 看 key 是否有在 keys 列表中
	 *
	 * @param  $key	要檢查的 key
	 * @return boolean 
	 */
	function hasKey( $key ){
		$ret_data = in_array( $key, $this->_keys );

		return $ret_data;
	}
}


/**
 * 刪除指定商品項目
 *
 * @param  $item 商品項目
 * @return 0 成功
 * @return -1 失敗
 * @return -2 失敗，指定項目參數有誤
 */
function goods_del_item( $item ){
        $ret_code = 0; 

        $gid = $item['gid'];

        if( $gid <= 0 ){ 
                $ret_code = -2; // 指定的項目參數有誤
                return $ret_code;
        }    

        $db = Database::getInstance();

        $query =  "DELETE FROM ntssi_goods WHERE gid='$gid' LIMIT 1" ;
        $ret   = $db->query_nodata($query);
        if( $ret != 0 ){ 
                $ret_code = -1; // 新增項目失敗
        }    

        return $ret_code;
}

/**
 * 新增商品項目至 ntssi_goods
 *
 * @param  $item 商品項目
 * @return 0 成功
 * @return -1 失敗
 */
function goods_add_item( $item, $URL_PREFIX ){
	global $UPLOAD_DIR; 

	$ret_code = 0;

	$keys_orig = array_keys( $item );
	$keys_filter = array(
			"gid"
			,"brand"
			,"gattribs"
			,"gattribs_content"
			,"goodattr"
			,"keyword"
			,"salename_color"
			,"storage"
			,"taobao_product_url"
			,"shoprec"
			,"shophot"
			,"zone"
			,""
			);
	$keys = array_diff( $keys_orig, $keys_filter );
	$keys = array( "bn"
			,"shopclass"
			,"ifhome"
			,"goodsname"
			,"bid"
			,"upbid"
			,"intro"
			,"brand_id"
			,"unit"
			,"pricedesc"
			,"provider_id"
			,"price"
			,"point"
			,"alarmnum"
			,"alarmcontent"
			,"nocarriage"
			,"bonusnum"
			,"subject_id"
			,"salenum"
			,"ifalarm"
			,"ifpub"
			,"ifrecommend"
			,"ifspecial"
			,"ifbonus"
			,"ifhot"
			,"ifgl"
			,"ifjs"
			,"js_begtime"
			,"js_endtime"
			,"js_price"
			,"js_totalnum"
			,"ifobject"
			,"gimg"
			,"middleimg"
			,"smallimg"
			,"bigimg"
			,"good_color"
			,"good_size"
			,"goodorder"
			,"view_num"
			,"keywords"
			,"video_url"
			,"body"
			,"idate"
			,"if_monthprice"
			,"cost"
			,"ifpresent"
			,"present_money"
			,"ifrecommend_man"
			,"en_name"
			,"component"
			,"capability"
			,"cap_des"
			,"trans_type"
			,"trans_special"
			,"iftransabroad"
			,"trans_special_money"
			,"ifxygoods"
			,"ifxy"
			,"ifxysale"
			,"ifchange"
			,"xycount"
			,"sale_name"
			,"goodsno"
			,"sale_subject"
			,"sale_price"
			,"ifsales"
			,"ifsaleoff"
			,"saleoff_starttime"
			,"saleoff_endtime"
			,"ifadd"
			,"addmoney"
			,"addprice"
			,"oeid"
			,"saleoffprice"
			,"iftimesale"
			,"timesale_starttime"
			,"timesale_endtime"
			,"jscount"
			,"present_endmoney"
			,"transtype"
			,"ifmood"
			,"addtransmoney"
			,"transtypemonty"
			,"memberprice"
			,"combipoint"
			,"checkstate"
			,"iftogether"
			,"guojima"
			,"xinghao"
			,"weight"
			,"shopid"
			,"nocheckreason"
			,"salecost"
			,"month"
			,"chandi"
			,"ERP"
			,"bounsgoods"
			,"bounsprice"
			,"bounschange"
			);

	$cols_txt = "";
	$vals_txt = "";

	foreach( $keys as $col ){
		if ( !isset( $item[$col] ) ) { 
			echo "未定義: >>>$col<<<\n";
			continue; 
		}

		$val = $item[$col];
		$cols_txt = $cols_txt."`$col`";
		$vals_txt = $vals_txt."'$val'";

	}

	$cols_txt = "(".str_replace( "``", "`, `", $cols_txt ).")";
	$vals_txt = "(".str_replace( "''", "', '", $vals_txt ).")";

	$db = Database::getInstance();

	$query =  "INSERT INTO ntssi_goods "
		. $cols_txt 
		. " VALUES "
		. $vals_txt
		;
	$ret   = $db->query_nodata($query);
	if( $ret != 0 ){
		$ret_code = -1; // 新增項目失敗
		echo ">>>$query<<<";
	}

	if( $item["gimg"] != "" && $item["middleimg"] != "" && $item["smallimg"] != "" ){
		$url1 = trim( $URL_PREFIX."/UploadFile/GoodPic/".$item["gimg"] );
		$img1 = $UPLOAD_DIR.'/GoodPic/'.$item["gimg"];
		$url2 = trim( $URL_PREFIX."/UploadFile/GoodPic/".$item["middleimg"] );
		$img2 = $UPLOAD_DIR.'/GoodPic/'.$item["middleimg"];
		$url3 = trim( $URL_PREFIX."/UploadFile/GoodPic/".$item["smallimg"] );
		$img3 = $UPLOAD_DIR.'/GoodPic/'.$item["smallimg"];
		$url4 = trim( $URL_PREFIX."/UploadFile/GoodPic/".$item["bigimg"] );
		$img4 = $UPLOAD_DIR.'/GoodPic/'.$item["bigimg"];

		if( $url1 != $URL_PREFIX."/UploadFile/GoodPic/" ) {
			mirror_img( $url1, $img1 );
		}
		if( $url2 != $URL_PREFIX."/UploadFile/GoodPic/" ) {
			mirror_img( $url2, $img2 );
		}
		if( $url3 != $URL_PREFIX."/UploadFile/GoodPic/" ) {
			mirror_img( $url3, $img3 );
		}
		if( $url4 != $URL_PREFIX."/UploadFile/GoodPic/" ) {
			mirror_img( $url4, $img4 );
		}
	}
	else{
		echo "圖片的路徑有缺"."\n";
	}

	return $ret_code;
}

/*
 * 依 key 取得 ntssi_goods 的項目
 *
 * @param $key 項目的序號
 * 
 * @return array 符合項目的項目列表
 */
function goods_get( $key ){
	$ret_data = array();

	// $words = explode( "_", $key );
	// if( count($words) != 2 ) { return $ret_data; }

	//$bn    = $words[0];
	//$idate = $words[1];
	$bn = $key;

	$db = Database::getInstance();
	$query =  "SELECT * "
		. " FROM ntssi_goods "
		. " WHERE `bn` = '$bn' " 
		. "" 
		;
	$ret_data = $db->query( $query );

	return $ret_data;
}

function goods_sync_add_from_good( $item, $site, $time ){
	$ret_code = 0;

	$key          = key_of_goods( $item );
	$chksum       = checksum_goods( $item );
	$gid          = $item['gid'];
	$checkstate   = strval($item['checkstate']);
	$last_chk     = $time;
	$last_changed = $time;

	$matches = goods_sync_get_by_key_and_gid( $site, $key, $gid );
	if( count( $matches ) > 0 ){ 
		$ret_code = -2; // 已經有重複的項目了 
		return $ret_code;
	}

	$db = Database::getInstance();

	$query = "INSERT INTO `ntssi_goods_sync` "
		. " ( `site`, `key`, `gid`, `chksum`, `last_chk`, `last_changed`, `checkstate` ) "
		. " VALUES "
		. " ( '$site', '$key', '$gid', '$chksum', '$last_chk', '$last_changed', $checkstate ) "
		;

	$ret = $db->query_nodata( $query );
	if( $ret !== 0 ){
		echo "新增 ntssi_goods_sync 項目失敗: $query \n";
		$ret_code = -1;
	}
	
	return $ret_code;
}

function goods_sync_edit_from_good( $item, $site, $time ){
	$ret_code = 0;

	$key          = key_of_goods( $item );
	$chksum       = checksum_goods( $item );
	$gid          = $item['gid'];
	$checkstate   = strval($item['checkstate']);
	$last_chk     = $time;
	$last_changed = $time;

	$matches = goods_sync_get_by_key_and_gid( $site, $key, $gid );
	if( count( $matches ) != 1 ){ 
		$ret_code = -2; // 沒找到對應的項目
		return $ret_code;
	}

	$db = Database::getInstance();

	$query = "UPDATE `ntssi_goods_sync` "
		. " SET `gid`='$gid', `chksum`='$chksum', `last_changed`='$last_changed', `checkstate`='$checkstate' "
		. " WHERE `site`='$site' AND `key`='$key' "
		;

	$ret = $db->query_nodata( $query );
	if( $ret !== 0 ){
		echo "更新 ntssi_goods_sync 項目失敗: $query \n";
		$ret_code = -1;
	}
	
	return $ret_code;
}

function goods_sync_touch_from_good( $keys, $site, $time ){
	$ret_code = 0;

	if( !is_array( $keys ) ){
		$ret_code = -2; // 輸入值不對
		return $ret_code;
	}

	$last_chk     = $time;

	$txt_ary = array();
	foreach( $keys as $key ){
		$txt = "`key`='$key'";
		array_push( $txt_ary, $txt );
	}
	if( count( $txt_ary ) > 0 ){
		$subsql_txt = "( ".implode( " OR ", $txt_ary )." ) ";
		}
	else{
		$subsql_txt = "( 1=0 )";
	}


	$db = Database::getInstance();

	$query = "UPDATE `ntssi_goods_sync` "
		. " SET `last_chk`='$last_chk' "
		. " WHERE `site`='$site' AND $subsql_txt "
		;

	$ret = $db->query_nodata( $query );
	if( $ret !== 0 ){
		echo "更新 ntssi_goods_sync 時間失敗: $query \n";
		$ret_code = -1;
	}

	return $ret_code;
}

function goods_sync_del_from_good( $item, $site ){
	$ret_code = 0;

	$key = $item['key'];
	$gid = $item['gid'];

	$db = Database::getInstance();
	$query = "DELETE FROM `ntssi_goods_sync` "
		. " WHERE `site`='$site' AND `key`='$key' AND `gid`='$gid' "
		;

	$ret = $db->query_nodata( $query );
	if( $ret !== 0 ){
		$ret_code = -1; // 刪除失敗
	}

	return $ret_code;
}

function goods_sync_add_from_sync( $item ){
	$ret_code = 0;

	$site         = $item['site'];
	$key          = $item['key'];
	$gid          = $item['gid'];
	$chksum       = $item['chksum'];
	$last_chk     = $item['last_chk'];
	$last_changed = $item['last_changed'];
	$checkstate   = strval($item['checkstate']);

			
	$query =  "INSERT INTO ntssi_goods_sync "
		. " ( `site`, `key`, `gid`, `chksum`, `last_chk`, `last_changed`, `checkstate` ) " 
		. " VALUES "
		. " ( '$site', '$key', '$gid', '$chksum', '$last_chk', '$last_changed', $checkstate ) " 
		. " " 
		;
	$db = Database::getInstance();
	$ret   = $db->query_nodata($query);
	if( $ret != 0 ){
		$ret_code = -1; // failed.
		echo "goods_sync_add_from_sync failed\n";
		print_r( $item );
		echo $query."\n";
			}
		
	return $ret_code;
}

function goods_sync_del_from_sync( $item ){
	$ret_code = 0;

	$site         = $item['site'];
	$key          = $item['key'];
	$gid          = $item['gid'];
	$chksum       = $item['chksum'];
	$last_chk     = $item['last_chk'];
	$last_changed = $item['last_changed'];

	
	$query =  "DELETE FROM ntssi_goods_sync "
		. " WHERE `site` = '$site' AND `key` = '$key' AND `gid` = '$gid' AND `chksum` = '$chksum' "
		. " LIMIT 1"
		;
	$db = Database::getInstance();
	$ret   = $db->query_nodata($query);
	if( $ret != 0 ){
		$ret_code = -1; // failed.
		echo "goods_sync_del_from_sync failed\n";
		print_r( $item );
		echo $query."\n";
	}
	
	return $ret_code;
}

/*
 * 取得 ntssi_goods_sync 的總數
 *
 * @param $site 站點
 *
 * @return integer
 */
function goods_sync_total( $site ){
	$ret_data = -1;

	$db = Database::getInstance();

	$result = $db->query( "SELECT count(*) AS cnt FROM ntssi_goods_sync WHERE `site`='$site' " );
	$ret_data = $result[0]['cnt'];

	return $ret_data;
}

/*
 * 取得 ntssi_goods_sync 的項目
 *
 * @param $site 站點
 * @param $idx 序號
 *
 * @return integer
 */
function goods_sync_get( $site, $idx ){
	$ret_data = NULL;

	$db = Database::getInstance();

	$query = "SELECT * "
		. " FROM ntssi_goods_sync "
		. " WHERE site='$site' " 
		. " ORDER BY `key` ASC " 
		. " LIMIT 1 OFFSET $idx " 
		;
	$result = $db->query( $query );

	if( count( $result ) <= 0 ){ return $ret_data; }

	$ret_data = $result[0];

	return $ret_data;
}

/*
 * 取得 ntssi_goods_sync 的項目
 *
 * @param $site 站點
 * @param $key 序號
 * 
 * @return integer
 */
function goods_sync_get_by_key( $site, $key ){
	$ret_data = NULL;

	$db = Database::getInstance();


	$query = "SELECT * "
		. " FROM ntssi_goods_sync "
		. " WHERE site='$site' " 
		. " AND `key`='$key' " 
		. " ORDER BY `key` ASC " 
		;
	$result = $db->query( $query );

	$ret_data = $result;

	return $ret_data;
}

/*
 * 取得 ntssi_goods_sync 的項目
 *
 * @param $site 站點
 * @param $key 序號
 * 
 * @return integer
 */
function goods_sync_get_by_key_and_gid( $site, $key, $gid ){
	$ret_data = NULL;

	$db = Database::getInstance();

	$query = "SELECT * "
		. " FROM ntssi_goods_sync "
		. " WHERE site='$site' " 
		. " AND `key`='$key' " 
		. " AND `gid`='$gid' " 
		. " ORDER BY `key` ASC " 
		;
	$result = $db->query( $query );

	$ret_data = $result;

	return $ret_data;
}

/*
 * 取得 ntssi_goods_sync 某一區間的項目列表
 *
 * @param $site 站點
 * @param $key_beg 序號開始
 * @param $key_end 序號結尾
 * 
 * @return integer
 */
function goods_sync_get_range_by_key( $site, $key_beg, $key_end ){
	$ret_data = array();

	$db = Database::getInstance();

	$query =  "SELECT * "
		. " FROM ntssi_goods_sync "
		. " WHERE site='$site' " 
		. " AND ( '$key_beg' <= `key` AND `key` <= '$key_end' ) " 
		. " ORDER BY `key` ASC " 
		;
	$ret_data = $db->query( $query );

	return $ret_data;
}


/**
 * 下載指定檔案至本地端
 * 
 * @param $src	來源檔案位置的 URL
 * @param $dst	本地端的路徑
 * 
 * @return >=0	執行成功
 * @return -1	執行失敗
 */
function mirror_img( $src, $dst ){
	$ch = curl_init( $src );
	$fp = fopen($dst, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}

/**
 * 產生商品項目的 checksum
 * 
 * @param $good_item 商品參數的 Array
 *
 * @return str, 商品項目的 checksum 值
 */
function checksum_goods( $good_item ){
	$ret_code = 0;
	$ret_data = NULL;

	$keys = array( 
			"goodsname",
			"intro",
			"pricedesc",
			"price",
			"jsprice",
			"gimg",
			"body",
			"cost",
			"goodsno",
			"checkstate"
		     );
	sort( $keys ); // 先作排序，避免因順序不同而產生的差異

	$content = "";

	for( $i=0; $i < count( $keys ); $i++ ){
		$key = $keys[$i];
		if ( array_key_exists( $key, $good_item ) ){
			$content .= $good_item[$key];
		}
		else{
			$ret_code = -1; // 欄位項目不符合
		}
	}

	$result = md5( $content );

	return $result;
}

function show_item( $item ){
	$site       = $item['site'];
	$key        = $item['key'];
	$gid        = $item['gid'];
	if( array_key_exists( "chksum", $item ) ){
		$checksum   = $item['chksum'];
	}
	else{
		$checksum   = $item['checksum'];
	}
	$checkstate = strval($item['checkstate']);

	$result = sprintf( "key=%s, site=%s, gid=%s, checkstate=%s, checksum=%s"
			,$key
			,$site
			,$gid
			,$checkstate
			,$checksum
			);

	return $result;
}

function find_by_key( $key, $ary ){
	$ret_data = array();

	foreach( $ary as $item ){
		if ( $item['key'] == $key ){
			array_push( $ret_data, $item );
		}
	}

	return $ret_data;
}

function find_by_key_and_gid( $key, $gid, $ary ){
	$ret_data = array();

	foreach( $ary as $item ){
		if ( $item['key'] == $key && $item['gid'] == $gid ){
			array_push( $ret_data, $item );
		}
	}

	return $ret_data;
}

function key_of_goods( $row ){
	//return sprintf( "%s_%s", $row['bn'], $row['idate'] );
	return sprintf( "%s", $row['bn'] );
}

/*
 * 取得 ntssi_provider 的對照表
 *
 * @return array 對照表，含 provider_id, providerno
 */
function provider_list(){
	$ret_data = array();

	$db = Database::getInstance();
	$query =  "SELECT provider_id, providerno "
		. " FROM ntssi_provider "
		;
	$ret_data = $db->query( $query );

	return $ret_data;
}

/**
 * 產生區間的列表
 *
 * @param total	總數
 * @param size	區間大小
 *
 * @return array	傳回陣列，陣列的元素分別有 min, max。如 $item['min'], $item['max']
 */
function make_pagination( $beg, $end, $size ){
	$ret_data = array();

	if( $beg < 0 || $end < 0 ) { return $ret_data; }
	if( $beg > $end ) { return $ret_data; }
	if( $size <= 0 ) { return $ret_data; }

	$idx_beg = floor( $beg / $size );
	$idx_end = floor( $end / $size );

	for( $i = $idx_beg; $i <= $idx_end ; $i++ ){
		$min = $i*$size;
		$max = ($i+1)*$size;
		if( $i == $idx_end ){
			$max = $end;
		}
		$page = array( "min" => $min, "max" => $max );
		array_push( $ret_data, $page );
	}

	return $ret_data;
}

/**
 * 依 key 的排序，產生區間的列表
 *
 * @param total	總數
 * @param size	區間大小
 *
 * @return array	傳回陣列，陣列的元素分別有 min, max。如 $item['min'], $item['max']
 */
function make_pagination_by_key( $site, $size ){
	$ret_data = array();

	if( $size <= 0 ) { return $ret_data; }
	
	$total = goods_sync_total( $site );

	$pages = make_pagination( 0, $total, $size ); 
	foreach( $pages as $page ){
		$idx_min = $page["min"];
		$idx_max = $page["max"] - 1;

		$item_min = goods_sync_get( $site, $idx_min );
		$item_max = goods_sync_get( $site, $idx_max );

		if ( $item_min == NULL || $item_max == NULL ){ continue; }

		$page = array( 
				"total" => $total,
				"min" => $item_min["key"],
				"max" => $item_max["key"] 
			     );
		array_push( $ret_data, $page );
	}

	return $ret_data;
}


function remote_api_call( $api_url, $params ){
	$url = $api_url;

	$options = array(
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0
			);

	$defaults = array(
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($params),
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT => 30 
			);

	$ch = curl_init();
	curl_setopt_array($ch, ($options + $defaults));
	if( ! $result = curl_exec($ch))
	{
		trigger_error(curl_error($ch));
	}

	curl_close($ch);

	return $result;
}

/**
 * 取得遠端站的 ntssi_goods 項目資訊
 *
 * @param $api_url 遠端站 API URL
 * @param $api_key 遠端站 API KEY
 * @param $key     目標項目的 key
 *
 * @return array, 項目列表
 */
function remote_get_item( $api_url, $api_key, $key ){
	$params = array (
			'apikey' => $api_key,
			'action' => "get",
			'param1' => $key,
			'param2' => '',
			'param3' => '',
			'param4' => ''
			);

	$result = remote_api_call( $api_url, $params );
	$items = unserialize( $result );
	
	return $items;
}

function remote_info( $api_url, $api_key, $page_size ){
	$params = array (
			'apikey' => $api_key,
			'action' => "info",
			'param1' => $page_size,
			'param2' => '',
			'param3' => '',
			'param4' => ''
			);

	$input = remote_api_call( $api_url, $params );
	$result = unserialize( $input );
	
	return $result;
}

function remote_fetch( $api_url, $api_key, $min, $max ){
	$params = array (
			'apikey' => $api_key,
			'action' => "fetch",
			'param1' => $min,
			'param2' => $max,
			'param3' => '',
			'param4' => ''
			);

	$input = remote_api_call( $api_url, $params );
	$result = unserialize( $input );

	return $result;
} 

function remote_list_providers( $api_url, $api_key ){
	$params = array (
			'apikey' => $api_key,
			'action' => "list_providers",
			'param1' => '',
			'param2' => '',
			'param3' => '',
			'param4' => ''
			);

	$input = remote_api_call( $api_url, $params );
	$result = unserialize( $input );

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

?>
