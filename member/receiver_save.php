<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
include_once "../language/".$INFO['IS']."/Cart.php";
if ($_POST['act']=="insert"){
	$db_string = $DB->compile_db_insert_string( array (
	'user_id'                      => intval($_SESSION['user_id']),
	'receiver_name'                => $_POST['receiver_name'],
	'addr'                         => trim($_POST['addr']),
	'receiver_email'               => trim($_POST['receiver_email']),
	'post'                => trim($_POST['othercity']), //  receiver_post
	'receiver_tele'                => MD5Crypt::Encrypt ( trim($_POST['receiver_tele']), $INFO['tcrypt']),
	'receiver_mobile'              => MD5Crypt::Encrypt ( trim($_POST['receiver_mobile']), $INFO['mcrypt']),
	'county'                       => str_replace("Õˆßx“ñ","",trim($_POST['county'])),
	'province'                     => str_replace("Õˆßx“ñ","",trim($_POST['province'])),
	'city'                         => str_replace("Õˆßx“ñ","",trim($_POST['city'])),
	)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}receiver` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result = $DB->query($Sql);
}
if ($_POST['act']=="update"){
	$db_string = $DB->compile_db_update_string( array (
	'receiver_name'                => $_POST['receiver_name'],
	'addr'                         => trim($_POST['addr']),
	'receiver_email'               => trim($_POST['receiver_email']),
	'post'                => trim($_POST['othercity']), //  receiver_post
	'receiver_tele'                => MD5Crypt::Encrypt ( trim($_POST['receiver_tele']), $INFO['tcrypt']),
	'receiver_mobile'              => MD5Crypt::Encrypt ( trim($_POST['receiver_mobile']), $INFO['mcrypt']),
	'county'                       => str_replace("Õˆßx“ñ","",trim($_POST['county'])),
	'province'                     => str_replace("Õˆßx“ñ","",trim($_POST['province'])),
	'city'                         => str_replace("Õˆßx“ñ","",trim($_POST['city'])),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}receiver` SET $db_string WHERE reid=".intval($_POST['reid']);
	$Result = $DB->query($Sql);
}
if ($_GET['act']=="del"){
	$Result =  $DB->query("delete from `{$INFO[DBPrefix]}receiver` where reid=".intval($_GET['reid']));
}
$FUNCTIONS->header_location('receiver_list.php?hometype=' . $_GET['hometype']);
?>

