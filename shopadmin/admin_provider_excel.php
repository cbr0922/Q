<?php
@ob_start();
//session_start();
include "Check_Admin.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
@ob_implicit_flush(0);
$Creatfile ="provider_".date("Y-m-d");
@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
	
$file_string .= "廠編,公司簡稱,店家帳號,店家密碼,品牌名稱,聯絡窗口,電話,手機,email,合作模式,付款方式,票期,結帳方式,PM,加入日,合約編號,合約日期(起始),合約日期(結束),狀態,說明,公司類型,公司電話,公司傳真,公司地址,公司主要接收信1,公司主要接收信2,公司主要接收信3,公司網址,對帳窗口聯繫人,對帳窗口電話,對帳窗口手機,對帳窗口Mail,公司統一編號,發票地址,往來銀行/分行,銀行代號,帳號,用戶名,免運費金額\n";
echo iconv("UTF-8","big5",$file_string);
	
$Sql      = "select * from `{$INFO[DBPrefix]}provider` order by providerno   ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
while ($Rs=$DB->fetch_array($Query)) {
	if (is_array($Rs)){
		foreach($Rs as $k=>$v){
			$Rs[$k] = foramtString($v);	
		}
	}
	$o_Sql      = "select * from `{$INFO[DBPrefix]}operater` where opid='" . $Rs['PM'] . "' order by lastlogin desc ";
   $o_Query    = $DB->query($o_Sql);
   $o_Rs=$DB->fetch_array($o_Query);
   if (is_array($o_Rs)){
	   foreach($o_Rs as $k=>$v){
			$o_Rs[$k] = foramtString($v);	
		}
   }
   $PM= $o_Rs['truename'];
   $type = $Rs['provider_type']==0?"公司":"個人";
	echo $file_string = $Rs['providerno'] . "," . iconv("UTF-8","big5",$Rs['provider_name']) . "," . iconv("UTF-8","big5",$Rs['provider_thenum']) . "," . iconv("UTF-8","big5",$Rs['provider_loginpassword']) . "," . iconv("UTF-8","big5",$Rs['brandname']) . "," . iconv("UTF-8","big5",$Rs['provider_lxr']) . "," . iconv("UTF-8","big5",$Rs['provider_tel']) . ","  . iconv("UTF-8","big5",$Rs['provider_mobile']) . "," . iconv("UTF-8","big5",$Rs['provider_email']) . "," . iconv("UTF-8","big5",$Rs['mode']) . "," . iconv("UTF-8","big5",$Rs['payment']) . "," . iconv("UTF-8","big5",$Rs['piaoqi'])  . "," . iconv("UTF-8","big5",$Rs['paytype'])  . "," . iconv("UTF-8","big5",$PM)  . "," . iconv("UTF-8","big5",date("Y-m-d",$Rs['provider_idate']))  . "," . iconv("UTF-8","big5",$Rs['agreementno'])  . "," . iconv("UTF-8","big5",$Rs['start_date'])  . "," . iconv("UTF-8","big5",$Rs['end_date'])  . "," . iconv("UTF-8","big5",$provider_state[$Rs['state']]) . "," . iconv("UTF-8","big5",$memo) . ","  . iconv("UTF-8","big5",$type)  . ","  . iconv("UTF-8","big5",$Rs['company_tel'])  . ","  . iconv("UTF-8","big5",$Rs['fax'])  . ","  . iconv("UTF-8","big5",$Rs['provider_addr'])  . ","  . iconv("UTF-8","big5",$Rs['receive_mail1'])  . ","  . iconv("UTF-8","big5",$Rs['receive_mail2'])  . ","   . iconv("UTF-8","big5",$Rs['receive_mail3'])  . ","  . iconv("UTF-8","big5",$Rs['websit'])  . ","  . iconv("UTF-8","big5",$Rs['account_lxr'])  . "," . iconv("UTF-8","big5",$Rs['account_tel'])  . "," . iconv("UTF-8","big5",$Rs['account_mobile'])  . "," . iconv("UTF-8","big5",$Rs['account_mail'])  . "," . iconv("UTF-8","big5",$Rs['invoice_num'])  . "," . iconv("UTF-8","big5",$Rs['invoice_addr'])  . "," . iconv("UTF-8","big5",$Rs['bank'])  . "," . iconv("UTF-8","big5",$Rs['bankno'])  . "," . iconv("UTF-8","big5",$Rs['bankuser'])  . "," . iconv("UTF-8","big5",$Rs['bankname'])  . "," . iconv("UTF-8","big5",$Rs['mianyunfei']) . "\n";
}

function foramtString($str){
	$str = str_replace(",","，",$str);
	$str = str_replace("\"","“",$str);
	$str = str_replace("\r"," ",$str);
	$str = str_replace("\n"," ",$str);
	return $str;
}
?>