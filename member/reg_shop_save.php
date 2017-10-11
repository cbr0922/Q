<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
session_start();
include("../configs.inc.php");
include("global.php");

if($_POST['act']=="add"){
	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],$_POST['old_pic'],"../UploadFile/ShopPic/");
	$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}shopinfo` where username='".$_POST['username']."' limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);
	if ($Num_old>0){
		$FUNCTIONS->sorry_back('back',"對不起您已經申請過了"); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
	}
	$db_string = $DB->compile_db_insert_string( array (
	'shopname'          => trim($_POST['shopname']),
	'content'          => trim($_POST['content']),
	'username'          => trim($_POST['username']),
	'password'          => md5(trim($_POST['password'])),
	'lxr'          => trim($_POST['lxr']),
	'tel'          => trim($_POST['tel']),
	'mobile'          => trim($_POST['mobile']),
	'fax'          => trim($_POST['fax']),
	'addr'          => trim($_POST['addr']),
	'email'          => trim($_POST['email']),
	'shop_description'          => trim($_POST['shop_description']),
	'shop_keywords'          => trim($_POST['shop_keywords']),
	'pubtime'          =>time(),
	'state'          =>0,
	)      );
	
	$Sql="INSERT INTO `{$INFO[DBPrefix]}shopinfo` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	
	
	$FUNCTIONS->header_location('../index.php');
}elseif($_POST['act']=="update"){
	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],$_POST['old_pic'],"../UploadFile/ShopPic/");
	$logo   = $FUNCTIONS->Upload_File($_FILES['logo']['name'],$_FILES['logo']['tmp_name'],$_POST['old_logo'],"../UploadFile/ShopPic/");
	$db_string = $DB->compile_db_update_string( array (
	'shopname'          => trim($_POST['shopname']),
	'content'          => trim($_POST['content']),
	'buycontent'          => trim($_POST['buycontent']),
	'askcontent'          => trim($_POST['askcontent']),
	'lxr'          => trim($_POST['lxr']),
	'tel'          => trim($_POST['tel']),
	'mobile'          => trim($_POST['mobile']),
	'fax'          => trim($_POST['fax']),
	'addr'          => trim($_POST['addr']),
	'email'          => trim($_POST['email']),
	'shop_description'          => trim($_POST['shop_description']),
	'shop_keywords'          => trim($_POST['shop_keywords']),
	'shoppic'          => trim($pic),
	'logo'          => trim($logo),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}shopinfo` SET $db_string WHERE sid=".intval($_SESSION['shopid']);
	$Result_Update = $DB->query($Sql);
	$FUNCTIONS->header_location('shopinfo.php');
}elseif($_POST['act']=="update_send"){
	$db_string = $DB->compile_db_update_string( array (
	'mianyunfei'          => intval($_POST['mianyunfei']),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}shopinfo` SET $db_string WHERE sid=".intval($_SESSION['shopid']);
	$Result_Update = $DB->query($Sql);
	$FUNCTIONS->header_location('shop_send.php');
}elseif($_POST['act']=="update_adv"){
	$db_string = $DB->compile_db_update_string( array (
	'adv'          => ($_POST['adv']),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}shopinfo` SET $db_string WHERE sid=".intval($_SESSION['shopid']);
	$Result_Update = $DB->query($Sql);
	$FUNCTIONS->header_location('shop_adv.php');
}elseif($_POST['act']=="login"){
	$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}shopinfo` where username='".$_POST['username']."' and password='" . md5(trim($_POST['password'])) . "' and state=1 limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);
	if ($Num_old>0){
		$Result= $DB->fetch_array($Query_old);
		$_SESSION['shopid'] = $Result['sid'];
		$_SESSION['username'] = $Result['username'];
		$_SESSION['state'] = $Result['state'];
	}	
	$FUNCTIONS->header_location('shop_index.php');
}elseif($_POST['act']=="update_password"){
	$Old_pw =  md5(trim($_POST['old_pwd']));
	$New_pw =  md5(trim($_POST['f_pwd']));
	$Sql = "select * from `{$INFO[DBPrefix]}shopinfo` where sid=".intval($_SESSION['shopid'])." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Pw      =  trim($Result['password']);
		$Email   =  trim($Result['email']);
		$true_name   =  trim($Result['lxr']);
		$username   =  trim($Result['username']);
	}else{
		$FUNCTIONS->sorry_back("shop_index.php",'NoMember');
		exit;
	}
	if ($Pw!=$Old_pw){
		$FUNCTIONS->sorry_back("shop_index.php",$MemberLanguage_Pack[Ydm_bad] ); //原密码输入不正确！
		exit;
	}else{
		$db_string = $DB->compile_db_update_string( array (
		'password'          => md5($_POST['password']),
		)      );
		$Sql = "UPDATE `{$INFO[DBPrefix]}shopinfo` SET $db_string WHERE sid=".intval($_SESSION['shopid']) . "";
		$Result_Update = $DB->query($Sql);
		$FUNCTIONS->header_location('shop_index.php');
	}
}elseif($_GET['act']=="logout"){
	$_SESSION['shopid'] = 0;
	$_SESSION['username'] = "";
	$_SESSION['state'] = "";
	$FUNCTIONS->header_location('../index.php');
}
?>
