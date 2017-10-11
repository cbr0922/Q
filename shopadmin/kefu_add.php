<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
/*
if ( $Num==0 ) //如果不存在资料
$FUNCTIONS->header_location("../index.php");

if ($Num>0){
$Result_Article = $DB->fetch_array($Query);
$Content = $Result_Article['info_content'];
}
*/

$Query_linshi = '';

$kefu_type = array();

$kefu_type = array(0=>array("name"=>"訂單進度"),1=>array("name"=>"退換維修"),2=>array("name"=>"帳款發票"),3=>array("name"=>"會員諮詢"));

$Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_type` order by typegroup, checked Desc";
$Query_linshi = $DB->query($Sql_linshi);
$i= 0 ;
while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
	$kefu_type[($Rs_linshi['typegroup']-1)]['kefutype'][count($kefu_type[$Rs_linshi['typegroup']-1]['kefutype'])] = $Rs_linshi;
	$i++;
}
$tpl->assign("kefu_type",      $kefu_type);
//print_r($kefu_type);
/*
foreach ($kefu_type as $v) {
	$OptionSelect_kefu .= "<option value=".$v['k_type_id'].">".$v['k_type_name']."</option>\n";
}

$tpl->assign("OptionSelect_kefu",     $OptionSelect_kefu);     //option内容
*/
$tpl->assign("Username",      $_SESSION['username']);//用戶名
$tpl->assign("LoginUsername", $_SESSION['true_name']);//真實姓名

$Query = $DB->query("select email from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$tpl->assign("Email", $Result[email]);//用户email
}


$tpl->assign("kefu_list_say",   $KeFu_Pack['type_list']);  //留言列表
$tpl->assign("kefu_add_say",    $KeFu_Pack['user_write']); //用户留言
$tpl->assign("kefu_type_say",   $KeFu_Pack['type']);       //留言類別
$tpl->assign("kefu_title_say",  $KeFu_Pack['title']);      //簡單標題
$tpl->assign("kefu_access_say", $KeFu_Pack['access_no']);  //帳號
$tpl->assign("kefu_name_say",   $KeFu_Pack['name']);       //姓名
$tpl->assign("kefu_email_say",  $KeFu_Pack['email']);      //電子郵件地址
$tpl->assign("kefu_content_say",$KeFu_Pack['content']);    //留言內容
$tpl->assign("kefu_submit_say", $KeFu_Pack['submit']);    //提交

$tpl->assign("kefu_Js_input_title",   $KeFu_Pack['Js_input_title']);    //请输入簡單標題！
$tpl->assign("kefu_Js_input_name",    $KeFu_Pack['Js_input_name']);    //请输入姓名！
$tpl->assign("kefu_Js_input_email",   $KeFu_Pack['Js_input_email']);    //请输入電子郵件地址！
$tpl->assign("kefu_Js_input_content", $KeFu_Pack['Js_input_content']);    //请输入留言內容！



$tpl->display("kefu_add.html");
?>
