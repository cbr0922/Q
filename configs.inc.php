<?php
error_reporting(7);

/**
 * 動態計算 ROOT 的 path ( for system )  跟 url ( for browser )
 * 
 * @author Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>
 */
{
	$ROOT_PATH       = dirname( __FILE__ );
	$script_filename = realpath($_SERVER["SCRIPT_FILENAME"]);
	$php_self        = $_SERVER["PHP_SELF"];
	$host_url        = $_SERVER["HTTP_HOST"];

	// 判斷網址是 http:// 或是 https://
	$proto_url	 = "http://";
	if ( array_key_exists( "HTTPS", $_SERVER ) && $_SERVER["HTTPS"] == "on" ){
		$proto_url = "https://";
	}

	$delta_sys       = str_replace( strtolower($ROOT_PATH), "", str_replace( "/",DIRECTORY_SEPARATOR, strtolower( $script_filename ) ) );
	$delta_url       = str_replace( DIRECTORY_SEPARATOR, "/", $delta_sys );
	$root_url0       = str_replace( $delta_url, "", strtolower($php_self) );
	$root_url        = substr( $php_self, 0, strlen( $root_url0) );
	$ROOT_URL        = $proto_url.$host_url.$root_url;

	define("RootURL"           , $ROOT_URL);
	define("RootURL2"          , $root_url);
	define("RootPATH"          , $ROOT_PATH);
}

/**
 * $OtherPach是為了處理在次目錄下建立SHOP而設立的，前面一定要加/作為開始。
 * 例如: $OtherPach =  "/Shop";
 */

//echo dirname(__FILE__);
$OtherPach =  RootURL;
define("OtherPach",RootURL);
define("OtherPach2", RootURL2 );
define("OtherPach3", RootPATH );


$RootDocument = dirname(__FILE__);
define("RootDocument",$RootDocument);

define("Resources","Resources");
/**
 * 定義圖形管理文件位置
 */
define("Jpgraph",Resources . "/jpgraph/src");

/**
 * 定義管理路徑目錄，這內定不需要修改
 */
define("ShopAdmin","shopadmin");
define("RootDocumentAdmin",RootDocument."/".ShopAdmin);

/**
 * 定義設置文件目錄，這內定不需要修改
 */
define("ConfigDir","Config");
define("RootDocumentShare",RootDocument."/".ConfigDir);
define("Classes","Classes");

/**
 * 定義DZ論壇目錄，這內定不需要修改
 */
define("DzforumDir","dzforum");



/**
 *  判斷目作業系统類型，以獲得路徑分割符号
 *  並初始化INCLUDE的路徑
 */
$TurnDot  =  substr(PHP_OS, 0, 3) == 'WIN'  ?  ";"  :  ":"  ;
$lib_root =".";
$lib_root .= $TurnDot.RootDocument;
$lib_root .= $TurnDot.RootDocument."/".Classes;
$lib_root .= $TurnDot.RootDocument."/".ConfigDir;
$lib_root .= $TurnDot.RootDocument."/".Jpgraph;
$lib_root .= $TurnDot.RootDocument."/".Resources."/Smarty";
$lib_root .= $TurnDot.RootDocument."/".Resources."/Smarty/libs";
$lib_root .= $TurnDot.RootDocument."/".Resources."/CreateHtml";
$lib_root .= $TurnDot.RootDocument."/".Resources."/phpexcel";
$lib_root .= $TurnDot.RootDocument."/".Resources."/phpmailer";
$lib_root .= $TurnDot.RootDocument."/".Resources."/oauth";
$lib_root .= $TurnDot.RootDocument."/".Resources."/3rd";
$lib_root .= $TurnDot.RootDocument."/".Resources."/Mat";
$lib_root .= $TurnDot.RootDocument."/".Resources."/securimage";
$lib_root .= $TurnDot.RootDocument."/".ShopAdmin;
$lib_root .= $TurnDot.RootDocument."/";

/**
 *  定義INCLUDE路徑
 */
ini_set("include_path",$lib_root);

/*
ini_set("memory_limit", "128M");
ini_set("post_max_size", "256M");
ini_set("upload_max_filesize", "20M");
*/


/**
 *  引入config.Smarty.php,這裡建議不用使用INCLUDE_ONCE ,這個不能被删除，否則程式醬不能正常運作。
 */
include (Classes."/detectmobilebrowser.php");
include ("config.Smarty.php");
//include_once RootDocument."/guard/config.php"; 
//include_once RootDocument."/guard/security.php"; 

include_once (RootDocument."/safe.php");
include (Classes."/global.php");

//訪問次數
//$visit_Sql = "select * from `{$INFO[DBPrefix]}count` where visitdate='" . date("Y-m-d",time()) . "' and ip='" . $FUNCTIONS->getip() . "'";
//$visit_Query = $DB->query($visit_Sql);
//$visit_Num   = $DB->num_rows($visit_Query);
//if ($visit_Num<=0){
//	$insert_sql = "insert into `{$INFO[DBPrefix]}count`(visitdate,ip) values ('" . date("Y-m-d",time()) . "','" . $FUNCTIONS->getip() . "')";	
//	$DB->query($insert_sql);
//}

if($_COOKIE['session_id']==""){
	//$session_id = $sid = session_id(); 
	$micr = explode(".", microtime(true));
	$session_id = $sid=date('ymdHis').rand(10, 99).$micr[1];
	setcookie("session_id",$session_id,time()+60*60*24*365);
}else{
	$session_id = $sid = $_COOKIE['session_id']; 	
	setcookie("session_id",$session_id,time()+60*60*24*365);
}
?>
