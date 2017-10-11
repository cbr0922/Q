<?php
@ob_start();
//session_start();
include "Check_Admin.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=big5");
@ob_implicit_flush(0);
$Creatfile ="order_rid_".date("Y-m-d");
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
	@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
include_once "Time.class.php";
include_once 'crypt.class.php';
$TimeClass = new TimeClass;
$FUNCTIONS->setLog("訂單匯出新竹格式");
$file_string .=iconv("UTF-8","big5", "訂購人,訂購人電話,訂購人mail,收件人姓名,收件人電話,收件人地址,匯款日期,匯款銀行,匯款帳號後5碼(存簿),匯款金額,商品購買總數量,運費,備註,商品編號,訂購數量,訂單編號\n");
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : $TimeClass->ForGetDate("Month","-6","Y-m-d");
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : $TimeClass->ForGetDate("Day","1","Y-m-d");

switch ($INFO['admin_IS']){
	case "gb":
		$ToEncode = "GB2312";
		break;
	case "en":
		$ToEncode = "GB2312";
		break;
	case "big5":
		$ToEncode = "BIG5";
		break;
	default:
		$ToEncode = "GB2312";
		break;
}

$Cid  = $_POST['cid'];
$Ci   = $_POST['Ci'];
$Tonum= $_POST['tonum'];
$Gid  = $_POST['gid'];

$Cid_num   = count($Cid);
$Ci_num    = count($Ci);
$Tonum_num = count($Tonum);
for ($i=0;$i<$Cid_num;$i++){
	$Sql = "select ot.*,u.*  from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}user` as u on (u.user_id=ot.user_id)  where ot.order_id='".intval($Cid[$i])."' order by  ot.order_id desc ";
	$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num > 0){
	while ($Rs = $DB->fetch_array($Query)){
		
		$Sql_d = "select sum(od.goodscount) as allcount from `{$INFO[DBPrefix]}order_detail` od where od.order_id=".$Rs['order_id']." ";
		$Query_d    = $DB->query($Sql_d);
		$Rs_d=$DB->fetch_array($Query_d);
		$allcount = $Rs_d['allcount'];
		
		
		$memo = str_replace(",","，",$Rs['receiver_memo']);
		$memo = str_replace("\"","“",$memo);
		$memo = str_replace("\r"," ",$memo);
		$memo = str_replace("\n"," ",$memo);
		$Rs['receiver_mobile'] = MD5Crypt::Decrypt ( $Rs['receiver_mobile'], $INFO['mcrypt']);
		if($Rs['user_id']==0){
			$Rs['true_name'] = $Rs['receiver_name'];
			$Rs['other_tel'] = MD5Crypt::Decrypt ( $Rs['receiver_mobile'], $INFO['mcrypt']);
			$Rs['email'] = $Rs['receiver_email'];
		}else{
			$Rs['other_tel'] = MD5Crypt::Decrypt ( $Rs['other_tel'], $INFO['mcrypt']);	
		}
		//$file_string .=iconv("UTF-8","big5", "訂購人,訂購人電話,訂購人mail,收件人姓名,收件人電話,收件人地址,匯款日期,匯款銀行,匯款帳號後5碼(存簿),匯款金額,商品購買總數量,運費,備註,商品編號,訂購數量,訂單編號\n");
		$Sql_d = "select od.*,ot.* from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where (ot.order_id=".$Rs['order_id']." or (ot.order_serial_together='" . $Rs['order_serial_together'] . "' and ot.order_serial_together<>'')) and ifpack=0 order by  od.order_detail_id desc ";

		$Query_d    = $DB->query($Sql_d);
		$flag = "";
		while ($Rs_d=$DB->fetch_array($Query_d)) {
			$file_string .= iconv("UTF-8","big5", $Rs['true_name']) . ",";
			$file_string .= iconv("UTF-8","big5", $Rs['other_tel']) . ",";
			$file_string .= iconv("UTF-8","big5", $Rs['email']) . ",";
			$file_string .= iconv("UTF-8","big5", $Rs['receiver_name']) . ",";
			$file_string .= iconv("UTF-8","big5", $Rs['receiver_mobile']) . ",";
			$file_string .= iconv("UTF-8","big5", $Rs['receiver_address']) . ",";
			$file_string .= ",";
			$file_string .= ",";
			$file_string .= ",";
			$file_string .= $Rs['discount_totalPrices'] . ",";
			$file_string .= $allcount . ",";
			$file_string .= $Rs['transport_price'] . ",";
			$file_string .= iconv("UTF-8","big5", $memo). ",";
			$file_string .= $Rs_d['bn'] . ",";
			$file_string .= $Rs_d['goodscount'] . ",";
			$file_string .= $Rs['order_serial'] . "\r\n";
		}
	}

	


	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 * 
	
	if ( $fh = fopen( $Creatfile.'.csv', 'w+' ) )
	{
	fputs ($fh, iconv("UTF-8","big5",$file_string), strlen($file_string) );
	fclose($fh);
	@chmod ($Creatfile.'.csv',0777);
	}
	 */

	
	//@header("location:".$Creatfile.'.csv');
}
}
echo $file_string;
?>