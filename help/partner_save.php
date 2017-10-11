<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../configs.inc.php");

/*if (trim($_POST['inputcode'])!=$_SESSION['Code_Reg']){
	$FUNCTIONS->sorry_back("back","驗證碼錯誤"); //驗證碼錯誤
}*/
include("securimage.php"); 
	$img=new Securimage(); 
	$valid=$img->check($_POST['inputcode']);
	if($valid==false) { 
	 	$FUNCTIONS->sorry_back("back","驗證碼錯誤");
	}
if($_POST['provider_name']!=""){
	$c_sql = "select providerno from `{$INFO[DBPrefix]}provider` order by provider_id desc limit 0,1 ";
	$c_Query =  $DB->query($c_sql);
	$c_Rs = $DB->fetch_array($c_Query);
	if(intval($c_Rs['providerno'])==0){
		$lastno = "100001";	
	}else{
		$lastno = intval($c_Rs['providerno'])+1;
	}
	$newno = str_repeat("0",6-strlen($lastno)) . $lastno;
	
	$provider_tel = trim($_POST['provider_tel1'])==""?trim($_POST['provider_tel2']):trim($_POST['provider_tel1'])."-".trim($_POST['provider_tel2']);
	$fax = trim($_POST['fax1'])==""?trim($_POST['fax2']):trim($_POST['fax1'])."-".trim($_POST['fax2']);
	$db_string = $DB->compile_db_insert_string( array (
	'providerno'                => $newno,
	'provider_name'             => trim($_POST['provider_name']),
	'provider_lxr'              => trim($_POST['provider_lxr']),
	'company_tel'              => $provider_tel,
	'provider_addr'             => trim($_POST['provider_addr']),
	'receive_mail1'            => trim($_POST['provider_email']),
	'provider_content'          =>  trim($_POST['provider_content']),
	'brandname'                => trim($_POST['brandname']),
	'state'                => 0,
	'provider_idate'            => time(),
	'fax'                => $fax,
	'websit'                => trim($_POST['websit']),
	'account_lxr'                => trim($_POST['account_lxr']),
	'provider_tel'                => trim($_POST['account_tel']),
	'provider_mobile'                => trim($_POST['account_mobile']),
	'provider_email'                => trim($_POST['account_mail']),
	'invoice_num'                => trim($_POST['invoice_num']),
	'invoice_addr'                => trim($_POST['invoice_addr']),
	'provider_type'                => trim($_POST['provider_type']),
	'provider_post'                => trim($_POST['provider_post']),
	'invoice_post'                => trim($_POST['invoice_post']),
	)      );


	 $Sql="INSERT INTO `{$INFO[DBPrefix]}provider` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	include_once "SMTP.Class.inc.php";
	include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	$Array =  array("provider_name"=>trim($_POST['provider_name']));
	$Sql      = "select * from `{$INFO[DBPrefix]}operater` where email<>'' order by lastlogin desc ";
	$Query    = $DB->query($Sql);
	while ($Rs=$DB->fetch_array($Query)) {
		$email .= "," . trim($Rs['email']);
	}
	$SMTP->MailForsmartshop($INFO['email'] . $email,"",36,$Array);
	$FUNCTIONS->sorry_back("../index.php","申請成功");	
}else{
	$FUNCTIONS->sorry_back("back","請填寫資料");	
}
?>