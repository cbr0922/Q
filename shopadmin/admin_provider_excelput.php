<?php
@ob_start();
include_once "Check_Admin.php";
//include Classes . "/global.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
$i=0;
$handle = fopen ($_FILES['cvsEXCEL']['tmp_name'],"r");
while ($datastr = fgets ($handle, 1024)) {
	if ($i>0){
	$datastr = big52utf8($datastr);
	$data = explode(",",$datastr);
		$sql = "select * from `{$INFO[DBPrefix]}provider` where providerno = '" . $data[0] . "'";
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		//"廠編,公司簡稱,店家帳號,店家密碼,品牌名稱,聯絡窗口,電話,手機,email,合作模式,付款方式,票期,結帳方式,PM,加入日,合約編號,合約日期(起始),合約日期(結束),狀態,說明,公司類型,公司電話,公司傳真,公司地址,公司主要接收信1,公司主要接收信2,公司主要接收信3,公司網址,對帳窗口聯繫人,對帳窗口電話,對帳窗口手機,對帳窗口Mail,公司統一編號,發票地址,往來銀行/分行,銀行代號,帳號,用戶名\n";
		
		if ($Num_trans > 0 && $data[0]!=""){
			$db_string = $DB->compile_db_update_string(array (
		  'provider_name'             => trim($data[1]),
		'provider_lxr'              => trim($data[5]),
		'provider_tel'              => trim($data[6]),
		'provider_email'            => trim($data[8]),
		'provider_content'          =>  $data[19],
		'provider_thenum'           => trim($data[2]),
		'provider_loginpassword'    => trim($data[3]),
		'provider_addr'             => trim($data[23]),
		'brandname'                => trim($data[4]),
		'mode'                => trim($data[9]),
		'PM'                => trim($data[13]),
		'piaoqi'                => trim($data[11]),
		'start_date'                => trim($data[16]),
		'end_date'                => trim($data[17]),
		'state'                => intval($data[18]),
		'payment'                => trim($data[10]),
		'bankuser'                => trim($data[36]),
	'bankname'                => trim($data[37]),
	'bank'                => trim($data[34]),
	'agreementno'                => trim($data[15]),
	'fax'                => trim($data[22]),
	'websit'                => trim($data[27]),
	'receive_mail1'                => trim($data[24]),
	'receive_mail2'                => trim($data[25]),
	'receive_mail3'                => trim($data[26]),
	'account_lxr'                => trim($data[28]),
	'account_tel'                => trim($data[29]),
	'account_mobile'                => trim($data[30]),
	'account_mail'                => trim($data[31]),
	'provider_mobile'                => trim($data[7]),
	'bankno'                => trim($data[35]),
	'invoice_num'                => trim($data[32]),
	'invoice_addr'                => trim($data[33]),
	'provider_type'                => trim($data[20]),
	'paytype'                => trim($data[12]),
	'company_tel'                => trim($data[21]),
	'mianyunfei'                => intval($data[38]),
		   
		  ));
			$Sql = "UPDATE `{$INFO[DBPrefix]}provider` SET $db_string WHERE providerno='".$data[0] . "'";
		}else{
			$c_sql = "select providerno from `{$INFO[DBPrefix]}provider` order by provider_id desc limit 0,1 ";
	$c_Query =  $DB->query($c_sql);
	$c_Rs = $DB->fetch_array($c_Query);
	if(intval($c_Rs['providerno'])==0){
		$lastno = "100001";	
	}else{
		$lastno = intval($c_Rs['providerno'])+1;
	}
	$newno = str_repeat("0",6-strlen($lastno)) . $lastno;
	if ($data[14]!="")
		$pubtime = $TimeClass->ForYMDGetUnixTime($data[14],"-");
	else
		$pubtime = time();
			//$Result = $DB->query("insert into `{$INFO[DBPrefix]}provider` (provider_name,provider_thenum,provider_loginpassword,provider_lxr,provider_tel,provider_email,provider_addr,provider_content) values ('" . $data[1] . "','" . $data[2] . "','" . $data[3] . "','" . $data[4] . "','" . $data[5] . "','" . $data[6] . "','" . $data[7] . "','" . $data[8] . "')");
			$db_string = $DB->compile_db_insert_string( array (
															   'providerno'                => $newno,
		   'provider_name'             => trim($data[1]),
		'provider_lxr'              => trim($data[5]),
		'provider_tel'              => trim($data[6]),
		'provider_email'            => trim($data[8]),
		'provider_content'          =>  $data[19],
		'provider_thenum'           => trim($data[2]),
		'provider_loginpassword'    => trim($data[3]),
		'provider_addr'             => trim($data[23]),
		'brandname'                => trim($data[4]),
		'mode'                => trim($data[9]),
		'PM'                => trim($data[13]),
		'piaoqi'                => trim($data[11]),
		'start_date'                => trim($data[16]),
		'end_date'                => trim($data[17]),
		'state'                => intval($data[18]),
		'payment'                => trim($data[10]),
		'bankuser'                => trim($data[36]),
	'bankname'                => trim($data[37]),
	'bank'                => trim($data[34]),
	'agreementno'                => trim($data[15]),
	'fax'                => trim($data[22]),
	'websit'                => trim($data[27]),
	'receive_mail1'                => trim($data[24]),
	'receive_mail2'                => trim($data[25]),
	'receive_mail3'                => trim($data[26]),
	'account_lxr'                => trim($data[28]),
	'account_tel'                => trim($data[29]),
	'account_mobile'                => trim($data[30]),
	'account_mail'                => trim($data[31]),
	'provider_mobile'                => trim($data[7]),
	'bankno'                => trim($data[35]),
	'invoice_num'                => trim($data[32]),
	'invoice_addr'                => trim($data[33]),
	'provider_type'                => trim($data[20]),
	'paytype'                => trim($data[12]),
	'company_tel'                => trim($data[21]),
	'mianyunfei'                => intval($data[38]),
		//  'provider_idate'                => time(),
		'provider_idate'                => $pubtime,  
			));
			 $Sql="INSERT INTO `{$INFO[DBPrefix]}provider` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		}
		$Result_Insert=$DB->query($Sql);
	}
	$i++;
}

fclose ($handle);
//exit;
@header("location:admin_provider_list.php");
function big52utf8($big5str) {

$blen = strlen($big5str);
$utf8str = "";

for($i=0; $i<$blen; $i++) {

$sbit = ord(substr($big5str, $i, 1));
//echo $sbit;
//echo "<br>";
if ($sbit < 129) {
$utf8str.=substr($big5str,$i,1);
}elseif ($sbit > 128 && $sbit < 255) {
$new_word = iconv("BIG5", "UTF-8", substr($big5str,$i,2));
$utf8str.=($new_word=="")?"?":$new_word;
$i++;
}
}

return $utf8str;

}
?>