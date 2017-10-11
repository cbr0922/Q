<?php
error_reporting (0);
@session_start();
if (defined('RootDocument')){
	include_once (RootDocument."/configs.inc.php");
}else{
	include ("../configs.inc.php");
}


/*
if (!file_exists( RootDocumentShare."/install.lockup" )) {
	echo "<script language=javascript>location.href='./installation/index.php';</script>";
	//header("location:".RootDocument."/installation/index.php");
	exit();
}
*/
//echo RootDocument;
include      ConfigDir."/conf.global.php";

// 這裡作 $INFO site_url 跟 site_shopadmin 的修正
if( isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['REMOTE_PORT']) )
{
	global $INFO;
	$INFO['site_url'] = RootURL;
	$INFO['site_shopadmin'] = RootURL."/shopadmin";
}
/*
設置HTTPS的
if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
{
	$INFO['site_url'] = "https://www.combimall.com.tw";
}
*/
/**
 * 如果前台临时修改的语言项目。这里将及时调整当前用户所用的资料
 */
if (!empty($_SESSION[CurrentUserLanguage])){
	$INFO['IS'] = $INFO['admin_IS']= $_SESSION[CurrentUserLanguage];
}
include_once Classes."/function.php";
$FUNCTIONS   = new FUNCTIONS;
include_once Classes."/mysql.php";
$DB = new DB_MySQL;
$DB->conn();
$DB->selectdb();


include_once Classes."/Ex_Function.php";
$Ex_Function = new Ex_Function;


include_once Classes."/Char.class.php";
$Char_class  = new Char_class;


if (intval(str_replace(".","",phpversion()))<410) {

	echo "PHP 的版本太低,本程序最低要求是 4.1.0 或以下的版本,当前服务器所安装的版本为 ".phpversion();
}


if (get_magic_quotes_gpc()) {

	$_GET    = str_replace("'","\'",$FUNCTIONS->stripslashes_array($_GET));
	$_POST   = str_replace("'","\'",$FUNCTIONS->stripslashes_array($_POST));
	$_COOKIE = str_replace("'","\'",$FUNCTIONS->stripslashes_array($_COOKIE));

}

set_magic_quotes_runtime(0);


if (!ini_get("register_globals")) {
	extract($_GET,EXTR_SKIP);
	extract($_POST,EXTR_SKIP);
}


$Url_array = explode("/",$_SERVER['PHP_SELF']);
$Language_where = 0;

foreach ($Url_array as $k=>$v){
	if ($v=='shopadmin'){
		$Language_where = 1;
	}
}


if ($Language_where == 1){
	$BackPath = $INFO['admin_IS'];
	include RootDocument."/language/".$INFO['admin_IS'].".php";  //获得公用的信息

}else{
	$BackPath = $INFO['IS'];
	include RootDocument."/language/".$INFO['IS'].".php";  //获得公用的信息
}

?>
