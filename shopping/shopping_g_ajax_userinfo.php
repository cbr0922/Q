<?php
session_start();
include("../configs.inc.php");
include("global.php");
/**
 * 这里是得到会员的基本资料
 */
if($_POST['act']=="update"){
	 $db_string = $DB->compile_db_update_string( array (
	  'true_name'         => trim($_POST['true_name']),
	  'email'             => trim($_POST['email']),
	  'addr'              => trim($_POST['addr2']),
	  'city'              => $_POST['city2'],
	  'canton'            => $_POST['province2'],
	  'Country'            => $_POST['county2'],
	  'zip'               => trim($_POST['othercity2']),
	  'post'              => trim($_POST['post']),
	  'tel'               => trim($_POST['tel']),
	  'other_tel'         => trim($_POST['other_tel']),
	  )      );
	  $Sql = "UPDATE `{$INFO[DBPrefix]}user` SET $db_string WHERE user_id=".intval($_SESSION['user_id']);
  
	  $Result_Update = $DB->query($Sql);
	  echo "1";exit;
}
if (intval($_SESSION['user_id'])>0){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1 ");
	$Num   =  $DB->num_rows($Query);
	if ( $Num > 0 ){
		$Rs  = $DB->fetch_array($Query);
		$email       = $Rs['email'];
		$tel         = $Rs['tel'];
		$post        = $Rs['post'];
		$city        = $Rs['city'];
		$canton      = $Rs['canton'];
		$Country      = $Rs['Country'];
		$addr        = $Rs['addr'];
		$true_name   = $Rs['true_name'];
		$other_tel   = $Rs['other_tel'];
	}
}

$tpl->assign("email",         $email);
$tpl->assign("tel",           $tel);
$tpl->assign("city",          $city);
$tpl->assign("canton",        $canton);
$tpl->assign("Country",        $Country);
$tpl->assign("post",          $post);
$tpl->assign("addr",          $addr);
$tpl->assign("true_name",     $true_name);
$tpl->assign("other_tel",     $other_tel);

$tpl->display("shopping_g_ajax_userinfo.html");
?>