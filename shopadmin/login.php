<?php
ob_start();
session_set_cookie_params(60*60*10);
session_start();
include ("../configs.inc.php");
include ( RootDocument."/Classes/version.php" );
require_once './PHPGangsta/GoogleAuthenticator.php';
//echo $INFO['nuevo.ifopen'];exit;
if (isset($_SESSION['HTTP_USER_AGENT'])){

}else{
	$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
}
/**
 *  装载产品语言包
 */
include "../language/".$INFO['IS']."/Desktop_Pack.php";
global $_VERSION;
switch ($_VERSION->VersionType){
	case "Free" :
		$Version = "Free";
		break;
	case "Business";
	$Version = "Business";
	break;
	default :
		$Version = "Free";
		break;
}
@header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
@header( "Cache-Control: no-cache, must-revalidate" );
@header( "Pragma: no-cache" );
@header("Content-type: text/html; charset=utf-8");
$IP = $FUNCTIONS->getip();
if ($INFO['checkip']==1){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}adminip` where ip='" . $IP . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if($Num<=0){
		echo "您沒有權限訪問，請聯繫管理員";
		exit;
	}
}
//phpinfo();
/*
* 检验用户是否已经过期
*/

//$FUNCTIONS->GetLicense();
/*
include_once(Classes . "/ajax.class.php");
$Ajax = new Ajax();
echo $Ajax->PostLicense();

$HttpHost = $_SERVER[HTTP_HOST];
$Pos = strpos($HttpHost,":");
if ($Pos === false) {
}else{
$HttpHost_array = explode(":",$HttpHost);
$HttpHost  = $HttpHost_array[0];
}
*/
//echo $HttpHost;
//if ($_SERVER[HTTP_HOST]!="localhost:81" && $_SERVER[HTTP_HOST]!="127.0.0.1"){
	/*
	if ($HttpHost!="localhost" && $HttpHost!="127.0.0.1" && $HttpHost!="219.84.143.49"){

    $SubPath               = str_replace("/shopadmin","",dirname(str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME'])));
	$Serverrealhost		   = str_replace("/shopadmin","",$_SERVER[HTTP_HOST].dirname(str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME'])));
	//echo "<hr>";
    //echo $_SERVER['SERVER_NAME'];
	//echo "<hr>";
	$Serverhost            = $_SERVER[HTTP_HOST].OtherPach;
	//echo "<hr>";


    if ( OtherPach!="" && $SubPath != OtherPach ){
		echo "設定錯誤，請正確設定系統目錄下configs.inc.php中的\$OtherPach變量。 &nbsp;";
		echo "\$OtherPach = \"".OtherPach."\" 是<font color='red'> 錯誤的</font>";
		exit();

	}

	if($Serverrealhost != $Serverhost && OtherPach == '') {
		echo "設定錯誤，請正確設定系統目錄下configs.inc.php中的\$OtherPach變量。 &nbsp;";
		echo "\$OtherPach = \"".OtherPach."\" 是<font color='red'> 錯誤的</font>";
		exit();
	}

	$serverhost_file_write = str_replace("/","~",$Serverhost);
	$ServerReply           = "http://yesddcs.com/license/".$serverhost_file_write.".txt";
    //ini_set("allow_url_fopen","On");
	$handle = fopen($ServerReply,"r");


	if ($handle){

		while (!feof($handle)) {
			$buffer .= trim(fgets($handle, 4096));
		}
		if ($buffer!=""){
			$Err_level = substr($buffer,strlen($buffer)-1,strlen($buffer));
		}else{
			$Err_level = 40;
		}
	}else{
		$buffer    =  " Unregistered version <hr>";
		$buffer   .=  "<a href='http://www.yesddcs.com/register/forbackregister.php'>找回license</a><hr> 請首先申請授權文件! <a href='http://www.yesddcs.com/register/register.php'>立刻申請!</a>";
		$Err_level = 25;
	}

}else{
	$Err_level = 35;
}
*/
/**
*  如果服务状态有警告信息。那么就停止操作。
*/
if ( intval($Err_level) < 30 ){
	//echo $buffer;
	//exit;
}
/* */



//超级管理员登陆
$Time = time();
$Rand =rand(0,1000);

if ($_POST['Action']=="Login" && trim($_POST['username'])!="" && trim($_POST['passwd'])!=""  && trim($_POST['type'])==0){

	//校验验证码
	if (trim($_POST['codeNum'])!=$_SESSION['Code_ShopadminLogin']){
		$FUNCTIONS->sorry_back("login.php",$ShopLogin[CodeIsBad_say]); //驗證碼錯誤
	}

	if(intval($INFO['ifauth'])==1 && $INFO['secretkey']!=''){
		$ga = new PHPGangsta_GoogleAuthenticator();
		$checkResult = $ga->verifyCode($INFO['secretkey'], trim($_POST['oneCode']), 1);
		//校验動態驗證碼
		if (!$checkResult){
			$FUNCTIONS->sorry_back("login.php","動態驗證碼錯誤"); //動態驗證碼錯誤
		}
	}


	$Sql   = "select * from `{$INFO[DBPrefix]}administrator` where sa='".trim($_POST['username'])."' limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$Result = $DB->fetch_array($Query);
	if ($Num>0 && password_verify(trim($_POST['passwd']), $Result['pw'])){
		//print_r($_POST);exit;
		$Session_id = intval($Result['sa_id']).$Time.$Rand;

		/*这里为了插入SESSION表中数据，以保证后台管理者的正确登陆*/
		$Session_sql   = " select session_id,sa_id from `{$INFO[DBPrefix]}session_table` where session_id=".$Session_id." and type=0 and sa_id='".$Result['sa_id']."' limit 0,1";
		$Session_query = $DB->query($Session_sql);
		 $Session_num   = $DB->num_rows($Session_query);

		if ($Session_num <= 0){
			if ($DB->query("insert into `{$INFO[DBPrefix]}session_table` (sa_id,session_id,type,actiontime) values('".intval($Result['sa_id'])."','".$Session_id."','0','" . time() . "')")){
				$_SESSION['LOGINADMIN_session_id']   = $Session_id;
				$_SESSION['sa_id']        = intval($Result['sa_id']);
				$_SESSION['LOGINADMIN_TYPE']         = 0;
				$_SESSION['Admin_Sa']     = trim($Result['sa']);
				$_SESSION['Admin_Logintime']  = time();
				$_SESSION['privilege'] = "";
			}
		}
 		$DB->query( "insert into  `{$INFO[DBPrefix]}login_log` (loginuser,logintype,loginip,logintime) values('".trim($_POST[username])."','".$ShopLogin[administrator]."','".$IP."','".time()."')");
		/**
		nuevoMailer系統串接
		**/
		
		if($INFO['nuevo.ifopen']==true){
			include_once("../modules/apmail/nuevomailer.class.php");
			$nuevo = new NuevoMailer;
			$nuevo->checkLogin(trim($_POST[username]),trim($_POST['passwd']));
		}
		//echo "insert into  `{$INFO[DBPrefix]}login_log` (loginuser,logintype,loginip,logintime) values('".trim($_POST[username])."','".$ShopLogin[administrator]."','".$IP."','".time()."')" ;

		//--------------------wordpress管理员登录----------------
		if(file_exists('../api/wordpress.php')) {
			include_once('../api/wordpress.php');
		}
		$FUNCTIONS->setLog("高級管理員登入");

		$FUNCTIONS->header_location("index.php");
		exit;
	}
}
//一般管理员登陆
if ($_POST['Action']=="Login" && trim($_POST['username'])!="" && trim($_POST['passwd'])!=""  && trim($_POST['type'])==1){

	//校验验证码
	if (trim($_POST['codeNum'])!=$_SESSION['Code_ShopadminLogin']){
		$FUNCTIONS->sorry_back("login.php",$ShopLogin[CodeIsBad_say]); //驗證碼錯誤
	}

	if(intval($INFO['ifauth'])==1 && $INFO['secretkey']!=''){
		$ga = new PHPGangsta_GoogleAuthenticator();
		$checkResult = $ga->verifyCode($INFO['secretkey'], trim($_POST['oneCode']), 1);
		//校验動態驗證碼
		if (!$checkResult){
			$FUNCTIONS->sorry_back("login.php","動態驗證碼錯誤"); //動態驗證碼錯誤
		}
	}


	$Sql   = "select * from `{$INFO[DBPrefix]}operater` where username='".trim($_POST['username'])."' and status=1 limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$Result = $DB->fetch_array($Query);
	if ($Num>0 && password_verify(trim($_POST['passwd']), $Result['userpass'])){
		$Session_id = intval($Result['opid']).$Time.$Rand;

		/*这里为了插入SESSION表中数据，以保证后台管理者的正确登陆*/
		$Session_sql   = " select session_id,sa_id from `{$INFO[DBPrefix]}session_table` where session_id=".$Session_id." and type=1 and sa_id='".$Result['sa_id']."' limit 0,1";
		$Session_query = $DB->query($Session_sql);
		$Session_num   = $DB->num_rows($Session_query);

		if ($Session_num <= 0){
			if ($DB->query("insert into `{$INFO[DBPrefix]}session_table` (sa_id,session_id,type,actiontime) values('".intval($Result['opid'])."','".$Session_id."','1','" . time() . "')")){
				$_SESSION['LOGINADMIN_session_id']   = $Session_id;
				$_SESSION['sa_id']        = intval($Result['opid']);
				$_SESSION['sa_type']        = intval($Result['type']);
				$_SESSION['LOGINADMIN_TYPE']         = 1;
				$_SESSION['privilege']    = $Result['privilege'];
				$_SESSION['Admin_Sa']     = trim($Result['username']);
				$_SESSION['Admin_Logintime']  = time();
				$DB->query("update `{$INFO[DBPrefix]}operater` set lastlogin='".time()."' where opid=".intval($Result['opid']));
			}
		}
		/**
		nuevoMailer系統串接
		**/
		if($INFO['nuevo.ifopen']==true){
			include_once("../modules/apmail/nuevomailer.class.php");
			$nuevo = new NuevoMailer;
			$nuevo->checkLogin(trim($_POST[username]),trim($_POST['passwd']));
		}
		//echo "insert into  `{$INFO[DBPrefix]}login_log` (loginuser,logintype,loginip,logintime) values ('".trim($_POST['username'])."','".$ShopLogin[admin]."','".$FUNCTIONS->getip()."','".time()."') ";
		//exit;
		$DB->query("insert into  `{$INFO[DBPrefix]}login_log` (loginuser,logintype,loginip,logintime) values ('".trim($_POST['username'])."','".$ShopLogin[admin]."','".$FUNCTIONS->getip()."','".time()."') ");
		$FUNCTIONS->setLog("一般管理員登入");
		$FUNCTIONS->header_location("index.php");
		exit;
	}
}

//供货商登陆
if ($_POST['Action']=="Login" && trim($_POST['username'])!="" && trim($_POST['passwd'])!=""  && trim($_POST['type'])==2){

	//校验验证码
	if (trim($_POST['codeNum'])!=$_SESSION['Code_ShopadminLogin']){
		$FUNCTIONS->sorry_back("login.php",$ShopLogin[CodeIsBad_say]); //驗證碼錯誤
	}

	if(intval($INFO['ifauth'])==1 && $INFO['secretkey']!=''){
		$ga = new PHPGangsta_GoogleAuthenticator();
		$checkResult = $ga->verifyCode($INFO['secretkey'], trim($_POST['oneCode']), 1);
		//校验動態驗證碼
		if (!$checkResult){
			$FUNCTIONS->sorry_back("login.php","動態驗證碼錯誤"); //動態驗證碼錯誤
		}
	}

	$Sql   = "select * from `{$INFO[DBPrefix]}provider` where provider_thenum='".trim($_POST['username'])."' and provider_loginpassword='".trim($_POST['passwd'])."' and ((state=2 or state=3 ) and start_date<='" . date("Y-m-d",time()) . "' and end_date>='" . date("Y-m-d",time()) . "' ) limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$Session_id = intval($Result['provider_id']).$Time.$Rand;

		/*这里为了插入SESSION表中数据，以保证后台管理者的正确登陆*/
		$Session_sql   = " select session_id,sa_id from `{$INFO[DBPrefix]}session_table` where session_id=".$Session_id." and type=2 and sa_id='".$Result['provider_id']."' limit 0,1";
		$Session_query = $DB->query($Session_sql);
		$Session_num   = $DB->num_rows($Session_query);

		if ($Session_num <= 0){
			if ($DB->query("insert into `{$INFO[DBPrefix]}session_table` (sa_id,session_id,type,actiontime) values('".intval($Result['provider_id'])."','".$Session_id."','2','" . time() . "')")){
				$_SESSION['LOGINADMIN_session_id']       = $Session_id;
				$_SESSION['sa_id']            = intval($Result['provider_id']);
				$_SESSION['LOGINADMIN_TYPE']  = 2;
				$_SESSION['Provider_thenum']  = trim($Result['provider_thenum']);
				$_SESSION['Admin_Sa']         = trim($Result['provider_name']);
				$_SESSION['Admin_Logintime']  = time();
				$DB->query("update `{$INFO[DBPrefix]}provider` set provider_lastlogin='".time()."' where provider_id=".intval($Result['provider_id']));

			}
		}
		$DB->query("insert into  `{$INFO[DBPrefix]}login_log` (loginuser,logintype,loginip,logintime) values ('".trim($_POST['username'])."','".$ShopLogin[provider]."','".$FUNCTIONS->getip()."','".time()."') ");
		$FUNCTIONS->setLog("供貨商登入");
		$FUNCTIONS->header_location("provider_index.php");
		exit;
	}
}


//經銷商登陆
if ($_POST['Action']=="Login" && trim($_POST['username'])!="" && trim($_POST['passwd'])!=""  && trim($_POST['type'])==3){

	//校验验证码
	if (trim($_POST['codeNum'])!=$_SESSION['Code_ShopadminLogin']){
		$FUNCTIONS->sorry_back("login.php",$ShopLogin[CodeIsBad_say]); //驗證碼錯誤
	}

	if(intval($INFO['ifauth'])==1 && $INFO['secretkey']!=''){
		$ga = new PHPGangsta_GoogleAuthenticator();
		$checkResult = $ga->verifyCode($INFO['secretkey'], trim($_POST['oneCode']), 1);
		//校验動態驗證碼
		if (!$checkResult){
			$FUNCTIONS->sorry_back("login.php","動態驗證碼錯誤"); //動態驗證碼錯誤
		}
	}

	$Sql   = "select * from `{$INFO[DBPrefix]}saler` where login='".trim($_POST['username'])."' and password='".trim($_POST['passwd'])."' and ifpub=1 limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$Session_id = intval($Result['id']).$Time.$Rand;

		/*这里为了插入SESSION表中数据，以保证后台管理者的正确登陆*/
		$Session_sql   = " select session_id,sa_id from `{$INFO[DBPrefix]}session_table` where session_id=".$Session_id." and type=3 and sa_id='".$Result['id']."' limit 0,1";
		$Session_query = $DB->query($Session_sql);
		$Session_num   = $DB->num_rows($Session_query);

		if ($Session_num <= 0){
			if ($DB->query("insert into `{$INFO[DBPrefix]}session_table` (sa_id,session_id,type,actiontime) values('".intval($Result['id'])."','".$Session_id."','3','" . time() . "')")){
				$_SESSION['LOGINADMIN_session_id']       = $Session_id;
				$_SESSION['sa_id']            = intval($Result['id']);
				$_SESSION['LOGINADMIN_TYPE']  = 3;
				$_SESSION['Provider_thenum']  = trim($Result['login']);
				$_SESSION['Admin_Sa']         = trim($Result['name']);
				$_SESSION['Admin_Logintime']  = time();
				//$DB->query("update `{$INFO[DBPrefix]}provider` set provider_lastlogin='".time()."' where provider_id=".intval($Result['provider_id']));

			}
		}
		$DB->query("insert into  `{$INFO[DBPrefix]}login_log` (loginuser,logintype,loginip,logintime) values ('".trim($_POST['username'])."','".$ShopLogin[provider]."','".$FUNCTIONS->getip()."','".time()."') ");
		$FUNCTIONS->setLog("經銷商登入");
		$FUNCTIONS->header_location("saler_index.php");
		exit;
	}
}

if ($_GET['Action']=="Logout") {

	$FUNCTIONS->setLog("登出");
	$_SESSION['LOGINADMIN_session_id'] = "";
	$_SESSION['sa_id'] = "";
	$_SESSION['type']="";
	$_SESSION['privilege']="";
	$_SESSION['Provider_thenum'] = "";
	/**
	nuevoMailer系統串接
	**/
	if($INFO['nuevo.ifopen']==true){
			$_SESSION['idAdmin'] = "";
			$_SESSION['adminName'] = "";
			$_SESSION['idGroupL'] = "";
	}

	$Session_sql   = " select sa_id from `{$INFO[DBPrefix]}session_table`";
	$Session_query = $DB->query($Session_sql);
	$Session_num   = $DB->num_rows($Session_query);
	if ($Session_num >500){
		$DB->query("TRUNCATE TABLE `{$INFO[DBPrefix]}session_table`  ");
	}
	@session_destroy();

	//--------------------wordpress退出-------------------
	if(file_exists('../api/wordpress.php')) {
		include_once('../api/wordpress.php');
	}

	$FUNCTIONS->header_location("login.php");

}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo $INFO['site_name']?></TITLE>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="../js/jquery/jquery.js"></script>
<script>
<!--
$("#mantle").ready(function() {
	$("#mantle").addClass("ms"+Math.floor(Math.random()*3+1));
	$("login").focus();
	setTimeout(function(){
		$("#tips").show('slow');
	},800);
})
var timer;
var isdisplay;


function hide() {
	isdisplay = false;
	timer=setTimeout("cls()",800);}
	-->
</script>
<style type="text/css">
<!--
 html, body {
    margin:0; padding:0; height:100%;
}
#floater { float:left; height:50%; margin-bottom:-200px;
    width:1px; /* only for IE7 */
}
#middle {  clear:both; height:400px; position:relative; }
-->
</style>
</HEAD>
<body>

<div id="floater"></div>
    <div id="middle">
      <FORM name='form1' action='login.php' method='post'>
      <div class="backend_login_box1">
      <p>&nbsp;</p>
      <TABLE width="383" height=158 border=0 align="center" cellPadding=2 cellSpacing=0 class=p9black style="margin-top:15px" >
		          <TBODY>
		            <TR>
		              <TD height="19" colspan="3" align=center noWrap><input type="hidden" name="Action" value="Login" />

                          <input  name='type' type='radio' class="p9orange" value='0' checked="checked" />
                          <?php echo $ShopLogin[administrator];?>
                          <!--管理员-->
                          <input type="radio" value="1" name="type" />
                          <?php echo $ShopLogin[admin];?>
                          <!--普通用户 -->
                          <!--<input type="radio" value="2" name="type" />
                          <?php echo $ShopLogin[provider];?>
                          供货商 -->
                          <input type="radio" value="3" name="type" />
                      經銷商</TD>
	                </TR>
		            <TR>
		              <TD width="107" height="32" align=right noWrap style="font-size:14px;padding-right:10px">帳&nbsp;&nbsp;&nbsp;&nbsp;號</TD>
                        <TD colspan="2" align="left"><input type=text class='box_no_pic1'  onmouseover="this.className='box_no_pic2'" onMouseOut="this.className='box_no_pic1'" placeholder=" Your Username" maxLength=20   name="username" /> </TD>
                        </TR>
		            <TR>
		              <TD height="32" align=right noWrap style="font-size:14px;padding-right:10px">密&nbsp;&nbsp;&nbsp;&nbsp;碼</TD>
                        <TD colspan="2" align="left"><input  class='box_no_pic1' onmouseover="this.className='box_no_pic2'" onMouseOut="this.className='box_no_pic1'" placeholder=" Your Password"  type=password  maxLength="20" name="passwd" /> </TD>
                        </TR>
		            <TR>
		              <TD height="32" align=right style="font-size:14px;padding-right:10px">驗證碼</TD>
                        <TD width="83"><input name="codeNum" type=text class='box_code1'  onmouseover="this.className='box_code2'" onMouseOut="this.className='box_code1'"  id="codeNum" size="10" maxLength="10" /></TD>
                        <TD width="167" align="left"><img src="./shopadminLogin_code.php" align="absmiddle" /></TD>
                    </TR>
								<?php if(intval($INFO['ifauth'])==1 && $INFO['secretkey']!=''){ ?>
								<TR>
									<TD height="32" align=right style="font-size:14px;padding-right:10px">動態驗證碼</TD>
												<TD width="83"><input name="oneCode" type=text class='box_code1'  onmouseover="this.className='box_code2'" onMouseOut="this.className='box_code1'" placeholder="共六碼"  id="oneCode" size="10" maxLength="10" /></TD>
										</TR>
								<?php } ?>
		            <TR>
		              <TD height="32" align=right>&nbsp;</TD>
                        <TD colspan="2"><div class="backend_login"><a href="javascript:document.form1.submit();">登&nbsp;&nbsp;入</a></div></TD>
                        </TR>
        </TABLE></div>
        </FORM>
</div>

</body>
</html>
