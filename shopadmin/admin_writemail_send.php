<?php
error_reporting(7);
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");



if ($_POST['uid']!="" ){
	$User_id = intval($_POST['uid']);
	$Query = $DB->query("select email from `{$INFO[DBPrefix]}user` where user_id=".intval($User_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$email          =  $Result['email'];
		include_once "SMTP.Class.inc.php";
		include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$Array_list =  array("mailsubject"=>trim($_POST['title']),"mailbody"=>trim($_POST['body']));
		$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
		$mailfalse = $SMTP->MailForsmartshop(trim($email),"","GroupSend",$Array_list);

		if ($mailfalse!=""){
			echo "<script language=javascript>alert('發送失敗');history.back(-1);</script>";
			exit;
		}else{
			$db_string = $DB->compile_db_insert_string( array (
				'usertype'       => $_SESSION['LOGINADMIN_TYPE'],
				'sa_id'       => $_SESSION['sa_id'],
				'user_id'       => $User_id,
				'content'       => trim($_POST['body']),
				'logtime'       => time(),
				'title'       => trim($_POST['title'])
			)      );
			
			 $Sql="INSERT INTO `{$INFO[DBPrefix]}mail_log` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Insert = $DB->query($Sql);
			echo "<script language=javascript>alert('發送成功');location.href='admin_member_list.php';</script>";
			exit;	
		}
	}

}
echo "<script language=javascript>alert('會員不存在');history.back(-1);</script>";
		exit;
?>