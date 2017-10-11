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
$file_string .=iconv("UTF-8","big5", "收件人 - 姓名,收件人 - 電話,收件人 - 地址,預定配達時段,品名,備註,email\n");
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
$FUNCTIONS->setLog("匯出黑貓格式");
/*
if (intval($_GET['provider_id'])>0){
	$Query_Provider =  $DB->query("SELECT DISTINCT order_id FROM  `{$INFO[DBPrefix]}order_detail`   where  provider_id=".intval($_GET['provider_id']));
	$Num_Provider   =  $DB->num_rows($Query_Provider);
	if ($Num_Provider>0){
		$Provider_Search = " and ( ";
		while ($Rs_Provider   =  $DB->fetch_array($Query_Provider)){
			$Provider_Search .= "o.order_id='".intval($Rs_Provider[order_id])."'  or ";
		}

		$Provider_Search   = substr($Provider_Search,0,strlen($Provider_Search)-3);
		$Provider_Search  .= " )";
	}
}


$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-");

switch (trim($_GET['State'])){
	case "NoOp":
		$Add = " and o.order_state<=2 ";  // 这里设置为小于等于2，就是说包括未确定，已确定，部分发货三类内容
		$Join = " left join ";
		break;
	case "NoView":
		$Add = " and ou.userback_alread=0 ";
		$Join = " inner join ";
		break;
	case "AllCancel":
		$Add = " and o.order_state=5 ";  // 这里设置为5
		$Join = " left join ";
		$Topname = "[".$Order_Pack[OrderState_say_six]."]";//已取消
		break;
	case "AllPigeonhole":
		$Add = " and o.order_state=4 ";  // 这里设置为4
		$Join = " left join ";
		$Topname = "[".$Order_Pack[OrderState_say_five]."]";//已归档
		break;
	case "Noreplay":
		$Add = " and ou.sys_say='' ";
		$Join = " inner join ";
		break;
    default :
		$Join = " left join ";
		break;
}

if ($_GET['action']=='search') {
	if (trim(urldecode($_GET['skey']))!=$Basic_Command['InputKeyWord']   && trim($_GET['skey'])!=""){

		if (trim($_GET['type'])=='o.order_serial' || trim($_GET['type'])=='u.true_name' || trim($_GET['type'])=='o.receiver_name' ){
			$Add_one = " and ".$_GET['type']."  like '%".trim(urldecode($_GET['skey']))."%' ";
		}else{
			$Add_one = " and ".$_GET['type']."='".trim(urldecode($_GET['skey']))."' ";
		}
	}

	if ($_GET['orderstate']!=''){
		$Add_two  =  " and  o.order_state=".intval($_GET['orderstate'])." ";
	}

	if ($_GET['paystate']!=''){
		$Add_three  =  " and  o.pay_state=".intval($_GET['paystate'])." ";
	}
	if ($_GET['shipstate']!=''){
		$Add_three  .=  " and  o.transport_state=".intval($_GET['shipstate'])." ";
	}
	if (intval($_GET['company'])>0){
		$Add_three  =  " and  u.companyid=".intval($_GET['company'])." ";
	}
	
	if ($_GET['transportation']!="" && $_GET['transportation']!="0"){
		$Add_four  =  " and  o.deliveryname='".$_GET['transportation']."' ";
	}
if ($_GET['methodname']!="" && $_GET['methodname']!="0"){
		$Add_four  .=  " and  o.paymentid='".$_GET['methodname']."' ";
	}
	 $Sql = " select  u.true_name,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.invoice_code,
	         ou.sys_say,ou.userback_type,ou.userback_alread  ,o.ticketmoney,o.bonuspoint,o.ticketcode,o.discount_totalPrices,o.piaocode,o.transport_state,o.bill_no
	         from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id)  
	         where  o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' 
	         ".$Add_one."   ".$Add_two."   ".$Add_three. " " . $Add_four ."  ".$Add."  ".$Provider_Search." ";
	$Sql      .= "  order by o.createtime desc";
	


}else{
	//下边如果不参与查询的资料
	$_GET['ifpage'] = 1;
	if ($_GET[Order_Tracks]=="Show")
		$begtimeunix = 0;
	$Add  = $_GET['State']!=""  ? str_replace("and","where",$Add)." and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' " :  " where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' ";
	$Add  = $_GET[Order_Tracks]=="Show" ? $Add." and o.order007_status=1 and  o.order007_begtime <= '".date("Y-m-d",time())."' " : $Add ;
	
		
	$Sql  = " select u.true_name,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,o.invoice_code,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.bill_no,
	         ou.sys_say,ou.userback_type,ou.userback_alread  ,o.ticketmoney,o.bonuspoint,o.ticketcode,o.discount_totalPrices ,o.piaocode,o.transport_state
	         from `{$INFO[DBPrefix]}order_table` o  left join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id ) ".$Add."  ";
	$Sql      .= "  order by o.createtime desc";
	
}
*/
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
		//收件人 - 姓名,收件人 - 電話,收件人 - 手機,收件人 - 地址,品名,備註,預定配達時段,代收貨款
		$memo = str_replace(",","，",$Rs['receiver_memo']);
		$memo = str_replace("\"","“",$memo);
		$memo = str_replace("\r"," ",$memo);
		$memo = str_replace("\n"," ",$memo);
		$Query_first = $DB->query(" select ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}transtime` ttime where ttime.transtime_id='".intval($Rs['transtime_id'])."' limit 0,1");
		$Num_first   = $DB->num_rows($Query_first);
		$Result_first= $DB->fetch_array($Query_first);
		
		$Sql_d = "select od.*,ot.* from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where (ot.order_id=".$Rs['order_id']." or (ot.order_serial_together='" . $Rs['order_serial_together'] . "' and ot.order_serial_together<>'')) and ifpack=0 order by  od.order_detail_id desc ";


		$Query_d    = $DB->query($Sql_d);
		$flag = "";
		while ($Rs_d=$DB->fetch_array($Query_d)) {
			$goodsname .=  $flag . $Rs_d['goodsname'];
			$flag = ";";
		}

			
		$file_string .= iconv("UTF-8","big5", $Rs['receiver_name'])  . "," . iconv("UTF-8","big5", MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt'])). " " . iconv("UTF-8","big5",MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']) ) . "," . iconv("UTF-8","big5",str_replace("請選擇","",str_replace(",","，",$Rs['receiver_address']))) . "," . iconv("UTF-8","big5",$Result_first['transtime_name']). "," . iconv("UTF-8","big5", $goodsname)  . ",," . ($Rs['receiver_email'])  . "\n";
		
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