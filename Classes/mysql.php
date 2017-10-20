<?php
error_reporting(7);

class DB_MySQL  {
	var $conn = 0;
	var $technicalemail='tyler@shopnc.net';
	var $utf_String = "
                 SET CHARACTER_SET_CLIENT = utf8,
                 CHARACTER_SET_CONNECTION = utf8,
                 CHARACTER_SET_DATABASE = utf8,
                 CHARACTER_SET_RESULTS = utf8,
                 CHARACTER_SET_SERVER = utf8,
                 COLLATION_CONNECTION = utf8_general_ci,
                 COLLATION_DATABASE = utf8_general_ci,
                 COLLATION_SERVER = utf8_general_ci,
                 AUTOCOMMIT=1
	       ";

	function geterrdesc() {
		$this->error = @mysql_error($this->conn);
		return $this->error;
	}

	function geterrno() {
		$this->errno = @mysql_errno($this->conn);
		return $this->errno;
	}


	function query($query_string) {

		mysql_query($this->utf_String);
		$this->result = mysql_query($query_string);
		if (!$this->result) {
			$this->halt("SQL 無效: ".$query_string);
		}

		return $this->result;
	}

	/*========================================================================*/
	// Fetch the number of rows in a result set
	/*========================================================================*/
	function num_rows($queryid) {

		mysql_query($this->utf_String);
		$this->rows = mysql_num_rows($queryid);

		if (empty($queryid)){
			$this->halt("Query id 無效:".$queryid);
		}
		return $this->rows;
	}

	/**
	 * Fetch the number of rows. Faster, use the mysql sub query syntax.
	 */
	function num_rows2($query_string) {
		global $meter;
		$meter->record( "query beg" );

		mysql_query($this->utf_String);
		$res = mysql_query( "SELECT COUNT(*) FROM ( $query_string ) AS asdfghjk ");
		$result = mysql_fetch_row( $res );
		$meter->record( "query end" );

		return $result[0];
	}

	function fetch_array($queryid) {
		mysql_query($this->utf_String);
		$this->record = mysql_fetch_array($queryid);
		if (empty($queryid)){
			$this->halt("Query id 無效:".$queryid);
		}
		return $this->record;
	}


	function fetch_assoc($queryid) {
		//mysql_query($this->utf_String);
		$this->record = mysql_fetch_assoc($queryid);
		if (empty($queryid)){
			$this->halt("Query id 無效:".$queryid);
		}
		return $this->record;
	}


	function conn(){
		global $INFO,$FUNCTIONS;
		$this->conn = mysql_connect($INFO['DBhostname'], $FUNCTIONS->authcode($INFO['DBuserName'],"DECODE",$INFO['site_userc']), $FUNCTIONS->authcode($INFO['DBpassword'],"DECODE",$INFO['site_userc'])) or  die(mysql_error("資料庫鏈結失敗"));
		/*             $this->conn = mysql_connect($this->servername, $this->dbusername, $this->dbpassword) or
		die(mysql_error("資料庫鏈結失敗"));
		*/
		return $this->conn;
	}

	function selectdb(){
		global $INFO,$FUNCTIONS;
		//if(!mysql_select_db($this->dbname)){
		if(!mysql_select_db($FUNCTIONS->authcode($INFO['DBname'],"DECODE",$INFO['site_userc']))){
			$this->halt("資料庫鏈結失敗");
		}
	}

	function selectdb_mall(){
		global $INFO,$FUNCTIONS;
		//if(!mysql_select_db($this->dbname)){
		if(!mysql_select_db("ddcscom_utv_twstar")){
			$this->halt("資料庫鏈結失敗");
		}
	}


	function my_close() {
		//        mysql_close($this->conn);
		mysql_close();
	}

	function fetch_row($queryid) {
		mysql_query($this->utf_String);
		$this->record = mysql_fetch_row($queryid);
		if (empty($queryid)){
			$this->halt("queryid 無效:".$queryid);
		}
		return $this->record;
	}

	function fetch_one_num($query) {

		$this->result =  $this->query($query);
		$this->record = $this->num_rows($this->result);
		if (empty($query)){
			$this->halt("Query id 無效:".$query);
		}
		return $this->record;

	}
	function fetch_one_array($query) {

		$this->result =  $this->query($query);
		$this->record = $this->fetch_array($this->result);
		if (empty($query)){
			$this->halt("Query id 無效:".$query);
		}
		return $this->record;

	}

	/*========================================================================*/
	// Return an array of fields
	/*========================================================================*/

	function get_result_fields($query_id) {
		mysql_query($this->utf_String);
		while ($field = mysql_fetch_field($query_id))
		{
			$Fields[] = $field;
		}

		return $Fields;
	}


	function free_result($query){
		mysql_query($this->utf_String);
		if (!mysql_free_result($query)){
			$this->halt("fail to mysql_free_result");
		}
	}

	/*========================================================================*/
	// Fetch the last insert id from an sql autoincrement
	/*========================================================================*/
	function insert_id(){
		//mysql_query($this->utf_String);
		$this->insertid = mysql_insert_id();
		if (!$this->insertid){
			$this->halt("fail to get mysql_insert_id");
		}
		return $this->insertid;
	}

	/*========================================================================*/
	// Return an array of tables
	/*========================================================================*/
	function get_table_names() {
		global $INFO;
		mysql_query($this->utf_String);
		$result     = mysql_list_tables($INFO['DBname']);
		//$result     = mysql_list_tables($this->dbname);
		$num_tables = @mysql_num_rows($result);
		for ($i = 0; $i < $num_tables; $i++)
		{
			$tables[] = mysql_tablename($result, $i);
		}

		mysql_free_result($result);

		return $tables;
	}

	/*========================================================================*/
	// Create an array from a multidimensional array returning formatted
	// strings ready to use in an INSERT query, saves having to manually format
	// the (INSERT INTO table) ('field', 'field', 'field') VALUES ('val', 'val')

	/*	例如：

	$str = $DB->compile_db_insert_string( array (
	'id'         => $unique_id,
	'search_date'=> time(),
	'post_id'    => $posts,
	'post_max'   => $max_hits,
	'sort_key'   => 'p.post_date',
	'sort_order' => 'desc',
	'member_id'  => $ibforums->member['id'],
	'ip_address' => $ibforums->input['IP_ADDRESS'],
	)        );

	$DB->query("INSERT INTO ibf_search_results ({$str['FIELD_NAMES']}) VALUES ({$str['FIELD_VALUES']})");
	*/
	/*========================================================================*/

	function compile_db_insert_string($data) {

		$field_names  = "";
		$field_values = "";

		foreach ($data as $k => $v)
		{
			//$v = preg_replace( "/'/", "\\'", $v );
			$v = preg_replace( "/'/", "''", $v );
			$v = preg_replace( "/script/", "\\common", $v );
			$v = preg_replace( "/SCRIPT/", "\\common", $v );
			$field_names  .= "$k,";
			$field_values .= "'$v',";
		}

		$field_names  = preg_replace( "/,$/" , "" , $field_names  );
		$field_values = preg_replace( "/,$/" , "" , $field_values );

		return array( 'FIELD_NAMES'  => $field_names,
		'FIELD_VALUES' => htmlspecialchars_decode($field_values),
		);
	}

	/*========================================================================*/
	/* Create an array from a multidimensional array returning a formatted
	/ string ready to use in an UPDATE query, saves having to manually format
	/ the FIELD='val', FIELD='val', FIELD='val'

	/例如：

	$db_string = $DB->compile_db_update_string( array (
	'last_post'        => $last['post_date'],
	'last_poster_id'   => $last['author_id'],
	'last_poster_name' => $last['author_name'],
	)       );

	$DB->query("UPDATE ibf_topics SET $db_string WHERE tid='".$ibforums->input['tid']."'");
	*/
	/*========================================================================*/



	function compile_db_update_string($data) {

		$return_string = "";

		foreach ($data as $k => $v)
		{
			$v = preg_replace( "/'/", "''", $v );
			$v = preg_replace( "/script/", "\\common", $v );
			$v = preg_replace( "/SCRIPT/", "\\common", $v );
			$return_string .= $k . "='".htmlspecialchars_decode($v)."',";
		}

		$return_string = preg_replace( "/,$/" , "" , $return_string );

		return $return_string;
	}

	function halt($msg){

		global $technicalemail,$debug;

		$message = "<html>\n<head>\n";
		$message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
		$message .= "<STYLE TYPE=\"text/css\">\n";
		$message .=  "<!--\n";
		$message .=  "body,td,p,pre {\n";
		$message .=  "font-family : Verdana, Arial, Helvetica, sans-serif;font-size : 12px;\n";
		$message .=  "}\n";
		$message .=  "</STYLE>\n";
		$message .= "</head>\n";
		$message .= "<body bgcolor=\"#EEEEEE\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";
		$message .= "<font size=2><b>系統調試</b></font><font size=2><b></b></font>\n<hr NOSHADE SIZE=1>\n";


		$content = "<p>資料庫出錯:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
		$content .= "<b>Mysql error description</b>: ".$this->geterrdesc()."\n<br>";
		$content .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br>";
		$content .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br>";
		$content .= "<b>Script</b>: http://".$_SERVER[HTTP_HOST].getenv("REQUEST_URI")."\n<br>";
		$content .= "<b>Referer</b>: ".getenv("HTTP_REFERER")."\n<br><br>";

		$message .= $content;

		//$message .= "<p>請嘗試刷新你的流覽器,如果仍然無法正常顯示,請聯繫<a href=\"mailto:".$this->technicalemail."\">管理員</a>.</p>";
		$message .= "</body>\n</html>";
		echo $message;

		$headers = "From: nt.cn <$this->technicalemail>\r\n";

		$content = strip_tags($content);
		@mail($technicalemail,"資料庫出錯",$content,$headers);

		exit;
	}

}
?>
