<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../../configs.inc.php" );
include ("global.php");
include(RootDocument."/language/".$INFO['IS']."/Email_Pack.php");


if ($_POST[action]=='send'){
    $dingyue_email = strtolower($_POST[email]);
	$Sql    = "select * from `{$INFO[DBPrefix]}mail_group_list`  where group_id=1 and email='" . $dingyue_email . "' limit 0,1";
	$Query  = $DB->query($Sql);
    $Result = $DB->fetch_array($Query);
	$ListNum   = $DB->num_rows($Query);
	if ($ListNum>0){
      	$D_sql = "delete from `{$INFO[DBPrefix]}mail_group_list` where group_id='1' and email='" . $dingyue_email . "'";
	 	$DB->query($D_sql);
		$OpEmail="您已經取消訂閱電子報";
	}else{
		$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) VALUES ('1','0','" . $dingyue_email . "')";
		$DB->query($Sql);
		$OpEmail="您已經成功訂閱電子報";
	}
	  
	/**
	nuevoMailer系統串接
	**/
	if($INFO['nuevo.ifopen']==true){
		include_once("../apmail/nuevomailer.class.php");
		$nuevo = new NuevoMailer;
		$nuevo->dingYue($_POST['email']);
	}

	$FUNCTIONS->sorry_back('back',$OpEmail);

}
$tpl->assign($Email_Pack);
$tpl->display("email_dingyue.html");
?>

