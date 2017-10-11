<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

include("settings.php");

class db_class {
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
	function db_class() {
		$this->host 		= DB_HOST;
		$this->user 		= DB_USER;
		$this->password 	= DB_PASS;
		$this->db           = DB_NAME;
		$link = mysqli_connect($this->host, $this->user, $this->password, $this->db);
		$this->link = $link;
		if (!$link){
			echo 'Cannot connect to server: '.$this->host;
			return false;
		}
		return $link;
	}
    function query($sql) {
		
		if (!$this->link){
		echo 'Cannot connect to database server: '.$this->db;
		return false;
		}
		mysqli_query($this->link,$this->utf_String);
		$result = mysqli_query($this->link, $sql) or die("Query failed: $sql<br><br>" . mysqli_error($this->link));
 		if (!$result) {
			$this->last_error = mysqli_error();
			echo    '<br>Problem in select. SQL: '.$sql.'<br>';
			return false;
		}
		return $result;
	}
	function real_escape_string($sql) 	{	//not used
		return (mysqli_real_escape_string($this->link, $sql));
    }
	function field_count() 	{	// returns the field count
		return (mysqli_field_count($this->link));
		//uses latest $result
		//$fields 	= $obj->field_count();
	}
	function num_rows($result) 	{
		return ceil(mysqli_num_rows($result));
		//Usage: $result = $obj->query($mySQL);
		//$rows 	= $obj->num_rows($result);
	}
	function get_rows ($sql) {
		//to use with queries like SELECT count(*)
		mysqli_query($this->link,$this->utf_String);
        $result = mysqli_query($this->link, $sql);
        $tcount = mysqli_fetch_array($result);
        return $tcount[0];
		//Usage: $obj->get_rows("$sql");
	}
	function affected_rows() {
		return ceil(mysqli_affected_rows($this->link));
		//Usage: $obj->affected_rows($result);
	}
	function insert_id() 	{
		return mysqli_insert_id($this->link);
		//Usage: Immediately after an insert query. NOT for BIGINT fields
	}
	function fetch_array($result)  {
		return mysqli_fetch_array($result);
		//Returns an array that corresponds to the fetched row and moves the internal data pointer ahead
	}
	function fetch_row($result) {
		return mysqli_fetch_row($result);
		//Returns a numerical array that corresponds to the fetched row and moves the internal data pointer ahead
	}
	function fetch_field($result) {
		return mysqli_fetch_field($result);
		//Returns the definition of one column of a result set as an object
		//Call this function repeatedly to retrieve information about all columns in the result set.
	}
	function data_seek($result, $pointer) {
		return mysqli_data_seek($result, $pointer);
	}
	function getSetting($field, $idGroup) {
		mysqli_query($this->link,$this->utf_String);
		$r = mysqli_query($this->link, "SELECT $field from ".$idGroup."_groupSettings WHERE idGroup=".$idGroup);
		$object =  mysqli_fetch_array($r);
		return $object[0];
		//$obj->getSetting("groupLogo", $idGroup);
	}
	function tableCount($table)	{
		mysqli_query($this->link,$this->utf_String);
		$tcount = mysqli_query($this->link, "SELECT count(*) from $table");
		if (!$tcount) {return false;}
		$tcount = mysqli_fetch_array($tcount);
		return $tcount[0];
		//Usage: $obj->tableCount("states");
	}
	function tableCount_condition($table, $condition)	{
		mysqli_query($this->link,$this->utf_String);
		$tcount = mysqli_query($this->link, "SELECT count(*) from $table $condition");
		if (!$tcount) {return false;}
		$tcount = mysqli_fetch_array($tcount);
		return $tcount[0];
	}
	function free_result($r)  {
		return mysqli_free_result($r);
	}
	function closeDb()  {
		return mysqli_close($this->link);
	}
}
?>