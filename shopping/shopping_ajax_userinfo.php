<?php
session_start();
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
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
	  'tel'               => MD5Crypt::Encrypt ( trim($_POST['tel']), $INFO['tcrypt']),
	  'other_tel'         => MD5Crypt::Encrypt ( trim($_POST['other_tel']), $INFO['mcrypt']),
	  'born_date'         => trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday'])
	  
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
		$tel         =  MD5Crypt::Decrypt ( $Rs['tel'], $INFO['tcrypt']);
		$post        = $Rs['post'];
		$city        = $Rs['city'];
		$canton      = $Rs['canton'];
		$Country      = $Rs['Country'];
		$addr        = $Rs['addr'];
		$true_name   = $Rs['true_name'];
		$cn_secondname   = $Rs['cn_secondname'];
		$en_firstname   = $Rs['en_firstname'];
		$en_secondname   = $Rs['en_secondname'];
		$bornCountry   = $Rs['bornCountry'];
		$certcode   = $Rs['certcode'];
		$other_tel   =  MD5Crypt::Decrypt ( $Rs['other_tel'], $INFO['mcrypt']);
		$born_date   = $Rs['born_date'];
	}
}



$Bmonth="";
for ($i=1;$i<=12;$i++){
	$Bmonth .= "<option value=".$i." ";
	if ($_COOKIE['bmonth']==$i){
		$Bmonth .= " selected ";
	}
	$Bmonth .= " > ".$i."</option>\n";
}

$tpl->assign("Bmonth",      $Bmonth); //月值

$Bday="";
for ($j=1;$j<=31;$j++){
	$Bday .="<option value=".$j." ";
	if ($_COOKIE['bday']==$j){
		$Bday .= " selected ";
	}
	$Bday .= " >".$j."</option>\n";
}
$Byear="";
for ($j=1930;$j<=date("Y",time());$j++){
	$Byear .="<option value=".$j." ";
	if ($_COOKIE['byear']==$j){
		$Byear .= " selected ";
	}
	$Byear .= " >".$j."(民國" . ($j-1911) . "年)</option>\n";
}
$tpl->assign("Byear",      $Byear);
$tpl->assign("Bday",      $Bday); //日值
$tpl->assign("cn_secondname",         $cn_secondname);
$tpl->assign("en_firstname",         $en_firstname);
$tpl->assign("en_secondname",         $en_secondname);
$tpl->assign("bornCountry",         $bornCountry);
$tpl->assign("certcode",         $certcode);
$tpl->assign("email",         $email);
$tpl->assign("tel",           $tel);
$tpl->assign("city",          $city);
$tpl->assign("canton",        $canton);
$tpl->assign("Country",        $Country);
$tpl->assign("post",          $post);
$tpl->assign("addr",          $addr);
$tpl->assign("true_name",     $true_name);
$tpl->assign("other_tel",     $other_tel);
$tpl->assign("born_date",     $born_date);
$tpl->assign("S_user_id",     intval($_SESSION['user_id']));
$tpl->display("shopping_ajax_userinfo.html");
?>
