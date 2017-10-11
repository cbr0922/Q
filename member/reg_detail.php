<?php
error_reporting(7);
session_start();
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
@header("Content-type: text/html; charset=utf-8");
if ($_SESSION['user_id']!=""){
	@header("location:index.php?hometype=" . $_GET['hometype']);
}

include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

/*註冊說明*/

$info_id = 5; 
$Query   = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=".$info_id." limit 0,1");
$Num   = $DB->num_rows($Query);

if ( $Num==0 )
$FUNCTIONS->header_location("../index.php");

if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$Content = $Result['info_content'];
}

/*顯示年*/
/*$Byear_select="";
for ($i=date("Y",time())-60;$i<=date("Y",time())-1;$i++){
	$Byear_select .= "<option value=".$i." ";
	if ($_COOKIE['Byear']==$i){
		$Byear_select .= " selected ";
	}
	$Byear_select .= " > ".$i."</option>\n";
}*/

$Byear="";
for ($j=1930;$j<=date("Y",time());$j++){
	$Byear .="<option value=".$j." ";
	if ($_COOKIE['byear']==$j){
		$Byear .= " selected ";
	}
	$Byear .= " >".$j."(民國" . ($j-1911) . "年)</option>\n";
}

/*顯示月*/

$Bmonth="";
for ($i=1;$i<=12;$i++){
	$Bmonth .= "<option value=".$i." ";
	if ($_COOKIE['bmonth']==$i){
		$Bmonth .= " selected ";
	}
	$Bmonth .= " > ".$i."月</option>\n";
}

/*顯示日*/

$Bday="";
for ($j=1;$j<=31;$j++){
	$Bday .="<option value=".$j." ";
	if ($_COOKIE['bday']==$j){
		$Bday .= " selected ";
	}
	$Bday .= " >".$j."日</option>\n";
}
/*$Sql      = "select u.*  from `{$INFO[DBPrefix]}company` u order by u.id desc";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
while ($Rs=$DB->fetch_array($Query)) {
	$company .="<option value=".$Rs['id']." ";
	$company .= " >".$Rs['companyname']."</option>\n";
}*/

$Display =  !empty($_COOKIE['nickname']) ? "" : "none";

if($_GET['u_recommendno']!=""){
	setcookie("u_recommendno",$_GET['u_recommendno'],time()+60*60*24*365);
	$tpl->assign("u_recommendno",   $_GET['u_recommendno']);
}else{
	$tpl->assign("u_recommendno",   $_COOKIE['u_recommendno']); //推薦人會員編號
}
$tpl->assign("CookieDisplay",  	$Display); 							//保存的用户名这个不在前台显示
$tpl->assign("Realname",     	$_COOKIE['realname']); 				//保存姓名
$tpl->assign("Sex",          	$_COOKIE['sex']); 					//保存性别
$tpl->assign("Byear",			$_COOKIE['byear']);					//保存年
$tpl->assign("Address",  		$_COOKIE['address']);  				//保存地址值
$tpl->assign("Post",      		$_COOKIE['post']);     				//保存郵政區號
$tpl->assign("Mobile",    		$_COOKIE['mobile']);   				//保存手機
$tpl->assign("Phone",     		$_COOKIE['phone']);   				//保存電話
//$tpl->assign("TotalDate",		date("Y",time())."/".intval(date("m",time()))."/".intval(date("d",time()))); //年
//$tpl->assign("Byear_select",  $Byear_select); //年
//$tpl->assign("companyselect", $company); //日
$tpl->assign("Content",        	$Content); 							//註冊說明
$tpl->assign("Byear",     		$Byear);  							//年
$tpl->assign("Bmonth",     		$Bmonth); 							//月
$tpl->assign("Bday",      		$Bday);								//日
$tpl->assign("adv_array",     	$adv_array);
$tpl->assign("Float_radio",     intval($INFO['float_radio']));      //浮动广告开关
$tpl->assign("Ear_radio",       intval($INFO['ear_radio']));        //耳朵广告开关
$tpl->assign("sid",				time());
$tpl->assign("app_id", $INFO['mod.login.fb.app_id']);
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
if($INFO['reg_type']==1)
	$tpl->display("reg_detail_".VersionArea.".html");
else
	$tpl->display("reg_detail_big5_simple.html");
?>
