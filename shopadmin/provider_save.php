<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ( version_compare( phpversion(), '4.1.0' ) == -1 ){
	// prior to 4.1.0, use HTTP_POST_VARS
	$postArray = $HTTP_POST_VARS['FCKeditor1'];
}else{
	// 4.1.0 or later, use $_POST
	$postArray = $_POST['FCKeditor1'];
}

if (is_array($postArray))
{
	foreach ( $postArray as $sForm => $value )
	{
		$postedValue = $value;
	}
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;

if ($_POST['Action']=='Update' ) {
	
		$a_array = array (
		'provider_name'             => trim($_POST['provider_name']),
		'provider_lxr'              => trim($_POST['provider_lxr']),
		'provider_tel'              => trim($_POST['provider_tel']),
		'provider_email'            => trim($_POST['provider_email']),
		'provider_addr'             => trim($_POST['provider_addr']),
		'brandname'                => trim($_POST['brandname']),
	'fax'                => trim($_POST['fax']),
	'websit'                => trim($_POST['websit']),
	'receive_mail1'                => trim($_POST['receive_mail1']),
	'receive_mail2'                => trim($_POST['receive_mail2']),
	'receive_mail3'                => trim($_POST['receive_mail3']),
	'account_lxr'                => trim($_POST['account_lxr']),
	'account_tel'                => trim($_POST['account_tel']),
	'account_mobile'                => trim($_POST['account_mobile']),
	'account_mail'                => trim($_POST['account_mail']),
	'provider_mobile'                => trim($_POST['provider_mobile']),
	'invoice_num'                => trim($_POST['invoice_num']),
	'invoice_title'                => trim($_POST['invoice_title']),
	'invoice_addr'                => trim($_POST['invoice_addr']),
	'provider_type'                => trim($_POST['provider_type']),
	'company_tel'                => trim($_POST['company_tel']),
	'invoice_num'                => trim($_POST['invoice_num']),
	'invoice_title'                => trim($_POST['invoice_title']),
	'invoice_addr'                => trim($_POST['invoice_addr']),
	'bankuser'                => trim($_POST['bankuser']),
	'bankname'                => trim($_POST['bankname']),
	'bank'                => trim($_POST['bank']),
	'bankno'                => trim($_POST['bankno']),
	'mianyunfei'                => intval($_POST['mianyunfei']),
	'yunfei'                => intval($_POST['yunfei']),
	'yunfei1'                => intval($_POST['yunfei1']),
	'yunfei2'                => intval($_POST['yunfei2']),
	'mianyunfei1'                => intval($_POST['mianyunfei1']),
	'mianyunfei2'                => intval($_POST['mianyunfei2']),
		) ;
	
	$db_string = $DB->compile_db_update_string($a_array);
	
	

	$Sql = "UPDATE `{$INFO[DBPrefix]}provider` SET $db_string WHERE provider_id=".intval($_SESSION['sa_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		
		$FUNCTIONS->header_location('provider_info.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




?>