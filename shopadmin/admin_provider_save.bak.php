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
include_once "SMTP.Class.inc.php";
include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);

if ($_POST['Action']=='Insert' ) {
	
	$c_sql = "select providerno from `{$INFO[DBPrefix]}provider` order by provider_id desc limit 0,1 ";
	$c_Query =  $DB->query($c_sql);
	$c_Rs = $DB->fetch_array($c_Query);
	if(intval($c_Rs['providerno'])==0){
		$lastno = "100001";	
	}else{
		$lastno = intval($c_Rs['providerno'])+1;
	}
	$newno = str_repeat("0",6-strlen($lastno)) . $lastno;
	if ($_SESSION['LOGINADMIN_TYPE']==0){
		$PM =  trim($_POST['PM']);	
	}else{
		$PM = $_SESSION['sa_id'];		
	}
	//$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],'',"../newspic");
	$db_string = $DB->compile_db_insert_string( array (
	'provider_name'             => trim($_POST['provider_name']),
	'provider_thenum'           => trim($_POST['provider_thenum']),
	'provider_loginpassword'    => trim($_POST['provider_loginpassword']),
	'provider_lxr'              => trim($_POST['provider_lxr']),
	'provider_tel'              => trim($_POST['provider_tel']),
	'provider_addr'             => trim($_POST['provider_addr']),
	'provider_email'            => trim($_POST['provider_email']),
	'provider_content'          =>  $postedValue,
	'providerno'                => $newno,
	'brandname'                => trim($_POST['brandname']),
	'mode'                => trim($_POST['mode']),
	'piaoqi'                => trim($_POST['piaoqi']),
	'PM'                => $PM,
	'start_date'                => trim($_POST['start_date']),
	'end_date'                => trim($_POST['end_date']),
	'state'                => intval($_POST['state']),
	'provider_idate'            => time(),
	'payment'                => trim($_POST['payment']),
	'bankuser'                => trim($_POST['bankuser']),
	'bankname'                => trim($_POST['bankname']),
	'bank'                => trim($_POST['bank']),
	'agreementno'                => trim($_POST['agreementno']),
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
	'bankno'                => trim($_POST['bankno']),
	'invoice_num'                => trim($_POST['invoice_num']),
	'invoice_title'                => trim($_POST['invoice_title']),
	'invoice_addr'                => trim($_POST['invoice_addr']),
	'provider_type'                => trim($_POST['provider_type']),
	'paytype'                => trim($_POST['paytype']),
	'company_tel'                => trim($_POST['company_tel']),
	'mianyunfei'                => intval($_POST['mianyunfei']),
	'yunfei'                => intval($_POST['yunfei']),
	'fanid'                => trim($_POST['fanid']),
	'yunfei1'                => intval($_POST['yunfei1']),
	'yunfei2'                => intval($_POST['yunfei2']),
	'mianyunfei1'                => intval($_POST['mianyunfei1']),
	'mianyunfei2'                => intval($_POST['mianyunfei2']),
	)      );


	 $Sql="INSERT INTO `{$INFO[DBPrefix]}provider` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增供應商");
		$FUNCTIONS->header_location('admin_provider_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($_POST['Provider_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$provider_fb    =  $Result['provider_fb'];
		$PM    =  $Result['PM'];
	}

	//$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],$_POST['Nimg'],"../newspic");
	if ($_SESSION['LOGINADMIN_TYPE']==1){
		$a_array = array (
		'provider_name'             => trim($_POST['provider_name']),
		'provider_lxr'              => trim($_POST['provider_lxr']),
		'provider_tel'              => trim($_POST['provider_tel']),
		'provider_email'            => trim($_POST['provider_email']),
		'mode'                => trim($_POST['mode']),
		'piaoqi'                => trim($_POST['piaoqi']),
		'payment'                => trim($_POST['payment']),
		'paytype'                => trim($_POST['paytype']),
		'provider_mobile'                => trim($_POST['provider_mobile']),
		'fanid'                => trim($_POST['fanid']),
		) ;
	}elseif ($_SESSION['LOGINADMIN_TYPE']==0){
		$a_array = array (
		'provider_name'             => trim($_POST['provider_name']),
		'provider_lxr'              => trim($_POST['provider_lxr']),
		'provider_tel'              => trim($_POST['provider_tel']),
		'provider_email'            => trim($_POST['provider_email']),
		'provider_content'          =>  $postedValue,
		'provider_thenum'           => trim($_POST['provider_thenum']),
		'provider_loginpassword'    => trim($_POST['provider_loginpassword']),
		'provider_addr'             => trim($_POST['provider_addr']),
		'provider_fb'               => intval($_POST['provider_fb']),
		'brandname'                => trim($_POST['brandname']),
		'mode'                => trim($_POST['mode']),
		'PM'                => trim($_POST['PM']),
		'piaoqi'                => trim($_POST['piaoqi']),
		'start_date'                => trim($_POST['start_date']),
		'end_date'                => trim($_POST['end_date']),
		'state'                => intval($_POST['state']),
		'payment'                => trim($_POST['payment']),
		'bankuser'                => trim($_POST['bankuser']),
	'bankname'                => trim($_POST['bankname']),
	'bank'                => trim($_POST['bank']),
	'agreementno'                => trim($_POST['agreementno']),
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
	'bankno'                => trim($_POST['bankno']),
	'invoice_num'                => trim($_POST['invoice_num']),
	'invoice_title'                => trim($_POST['invoice_title']),
	'invoice_addr'                => trim($_POST['invoice_addr']),
	'provider_type'                => trim($_POST['provider_type']),
	'paytype'                => trim($_POST['paytype']),
	'company_tel'                => trim($_POST['company_tel']),
	'mianyunfei'                => intval($_POST['mianyunfei']),
	'yunfei'                => intval($_POST['yunfei']),
	'fanid'                => trim($_POST['fanid']),
	'yunfei1'                => intval($_POST['yunfei1']),
	'yunfei2'                => intval($_POST['yunfei2']),
	'mianyunfei1'                => intval($_POST['mianyunfei1']),
	'mianyunfei2'                => intval($_POST['mianyunfei2']),
		) ;
	}
	
	$db_string = $DB->compile_db_update_string($a_array);
	
	

	$Sql = "UPDATE `{$INFO[DBPrefix]}provider` SET $db_string WHERE provider_id=".intval($_POST['Provider_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		if ($_SESSION['LOGINADMIN_TYPE']==0 && intval($_POST['state'])==3 && $provider_fb !=3){
			$Array =  array("provider_name"=>trim($_POST['provider_name']),"provider_thenum"=>trim($_POST['provider_thenum']),"provider_loginpassword"=>trim($_POST['provider_loginpassword']));
			$SMTP->MailForsmartshop(trim($_POST['provider_email']),"",18,$Array);
			$Sql      = "select * from `{$INFO[DBPrefix]}operater` where email<>'' and opid='" . $PM . "' order by lastlogin desc ";
			$Query    = $DB->query($Sql);
			while ($Rs=$DB->fetch_array($Query)) {
				$SMTP->MailForsmartshop(trim($Rs['email']),"",18,$Array);
			}
			
		}
		$FUNCTIONS->setLog("編輯供應商");
		$FUNCTIONS->header_location('admin_provider_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除供應商");
		$FUNCTIONS->header_location('admin_provider_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

if($_GET['Action']=="send"){
	$Sql = "select * from `{$INFO[DBPrefix]}provider` where provider_id='" . intval($_GET['Provider_id']) . "'";
	$Query    = $DB->query($Sql);
	 $Num      = $DB->num_rows($Query);
	if ($Num>0){
		//echo "a";
		$Rs=$DB->fetch_array($Query);
		$Array =  array("provider_name"=>trim($Rs['provider_name']),"provider_thenum"=>trim($Rs['provider_thenum']),"provider_loginpassword"=>trim($Rs['provider_loginpassword']));
		$SMTP->MailForsmartshop(trim($Rs['provider_email']),"",18,$Array);
		$Sql_o      = "select * from `{$INFO[DBPrefix]}operater` where email<>'' and opid='" . $Rs['PM'] . "' order by lastlogin desc ";
		$Query_o    = $DB->query($Sql_o);
		while ($Rs_o=$DB->fetch_array($Query_o)) {
			$SMTP->MailForsmartshop(trim($Rs_o['email']) . "," . $Rs['provider_email'],"",18,$Array);
		}
	}
	$FUNCTIONS->header_location('admin_provider_list.php');
}
?>