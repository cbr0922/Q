<?php
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once 'crypt.class.php';
if ($_GET['act']=="modify" && intval($_GET['reid'])>0){
	$sql = "select * from `{$INFO[DBPrefix]}receiver` where reid='" . $_GET['reid'] . "'";
	$Query = $DB->query($sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$tpl->assign("receiver_name",        $Result['receiver_name']);
		$tpl->assign("receiver_email",        $Result['receiver_email']);
		$tpl->assign("post",        $Result['post']);
		$tpl->assign("receiver_tele",        MD5Crypt::Decrypt ( $Result['receiver_tele'], $INFO['tcrypt']));
		$tpl->assign("receiver_mobile",         MD5Crypt::Decrypt ( $Result['receiver_mobile'], $INFO['mcrypt']));
		$tpl->assign("county",        $Result['county']);
		$tpl->assign("province",        $Result['province']);
		$tpl->assign("city",        $Result['city']);
		$tpl->assign("reid",        $Result['reid']);
		$tpl->assign("addr",        $Result['addr']);
	}
	$tpl->assign("act",        "update");
}elseif($_POST['act']=="update"){
	$db_string = $DB->compile_db_update_string( array (
	'receiver_name'                => $_POST['receiver_name'],
	'addr'                         => trim($_POST['addr']),
	'receiver_email'               => trim($_POST['receiver_email']),
	'post'                => trim($_POST['othercity2']), //  receiver_post
	'receiver_tele'                => MD5Crypt::Encrypt ( trim($_POST['receiver_tele']), $INFO['tcrypt']),
	'receiver_mobile'              => MD5Crypt::Encrypt ( trim($_POST['receiver_mobile']), $INFO['mcrypt']),
	'county'                       => str_replace("請選擇","",trim($_POST['county2'])),
	'province'                     => str_replace("請選擇","",trim($_POST['province2'])),
	'city'                         => str_replace("請選擇","",trim($_POST['city2'])),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}receiver` SET $db_string WHERE reid=".intval($_POST['reid']);
	$Result = $DB->query($Sql);
	echo "1";
	exit;
}elseif($_POST['act']=="insert"){
	$db_string = $DB->compile_db_insert_string( array (
	'user_id'                      => intval($_SESSION['user_id']),
	'receiver_name'                => $_POST['receiver_name'],
	'addr'                         => trim($_POST['addr']),
	'receiver_email'               => trim($_POST['receiver_email']),
	'post'                => trim($_POST['othercity2']), //  receiver_post
	'receiver_tele'                => MD5Crypt::Encrypt ( trim($_POST['receiver_tele']), $INFO['tcrypt']),
	'receiver_mobile'              => MD5Crypt::Encrypt ( trim($_POST['receiver_mobile']), $INFO['mcrypt']),
	'county'                       => str_replace("請選擇","",trim($_POST['county2'])),
	'province'                     => str_replace("請選擇","",trim($_POST['province2'])),
	'city'                         => str_replace("請選擇","",trim($_POST['city2'])),
	)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}receiver` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result = $DB->query($Sql);
	echo "1";
	exit;
}else{
	$tpl->assign("addr",       "");
	$tpl->assign("act",        "insert");
}
$tpl->assign($Cart);
$tpl->display("shopping_ajax_receiverinfo.html");
?>
