<?php
@ob_start();
//session_start();
include "Check_Admin.php";

@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
$Creatfile ="member_".date("Y-m-d");

	@ob_implicit_flush(0);
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
	@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");

if (intval($_GET['companyid']) > 0){
	$Date_string = " companyid ='".intval($_GET[companyid])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql("",$Date_string);
}
if ($_GET['Month']){
	$Date_string = " MONTH(born_date) ='".intval($_GET[Month])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql("",$Date_string);
}
if ($_GET['Sex']!=""){
	$Sex_string = " sex='".intval($_GET[Sex])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Sex_string);
}
if($_GET[county]!="" && $_GET[county]!="請選擇"){
	$Area_string = "   Country='".trim($_GET[county])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if($_GET[province]!="" && $_GET[province]!="請選擇"){
	$Area_string = "   canton='".trim($_GET[province])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if($_GET[city]!="" && $_GET[city]!="請選擇"){
	$Area_string = "   city='".trim($_GET[city])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if ($_GET['skey']){
	$key_string = "u.username like '%".trim(urldecode($_GET['skey']))."%'";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$key_string);
}
$Sql      = "select u.* ,l.level_name from `{$INFO[DBPrefix]}user` u  left join `{$INFO[DBPrefix]}user_level` l on (u.user_level = l.level_id) ".$Create_Sql." order by u.user_id desc";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

$FUNCTIONS->setLog("導出會員名片");
$file_string .= iconv("UTF-8","big5","會員代碼,帳號,密碼,真實姓名,性別,身份證號碼,出生日期,聯絡地址,郵件,傳真,電話,移動電話,紅利點數,消費次數,消費總額,會員等級,註冊時間,狀態,推薦代碼,推薦人,總推薦人數,所屬單位\n");
$i = 1;
if ($Num > 0){
	while ($Rs = $DB->fetch_array($Query)){
		foreach($Rs as $k=>$v){
			$Rs[$k] = str_replace(",","",$v);	
		}
		$sex =$Rs['sex'] ;
		if ($sex==0)
			$sex = "男";
		else
			$sex = "女";
			
		if ($Rs['user_state']==0)
			$user_state = "正常";
		else
			$user_state = "關閉";
			
		$point =$FUNCTIONS->Userpoint(intval($User_id),1);
		
		$order_Sql = "select count(*) as counts,sum(discount_totalPrices) as totals from `{$INFO[DBPrefix]}order_table` where user_id='" . $Rs['user_id'] . "'";
		$order_Query    = $DB->query($order_Sql);
		$order_Rs = $DB->fetch_array($order_Query);
		
		$re_Sql = "select username from `{$INFO[DBPrefix]}user` where memberno='" . $Rs['recommendno'] . "'";
		$re_Query    = $DB->query($re_Sql);
		$re_Rs = $DB->fetch_array($re_Query);
		
		$me_Sql = "select count(*) as counts from `{$INFO[DBPrefix]}user` where recommendno='" . $Rs['memberno'] . "'";
		$me_Query    = $DB->query($me_Sql);
		$me_Rs = $DB->fetch_array($me_Query);
		
		$saler_Sql = "select name from `{$INFO[DBPrefix]}saler` where id='" . $Rs['companyid'] . "'";
		$saler_Query    = $DB->query($saler_Sql);
		$saler_Rs = $DB->fetch_array($saler_Query);
		
		
		$file_string .= iconv("UTF-8","big5",$Rs['memberno']) . "," . iconv("UTF-8","big5",$Rs['username']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['password']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['true_name']) . "," ;
		$file_string .= iconv("UTF-8","big5",$sex) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['certcode']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['born_date']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['addr']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['email']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['fax']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['tel']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['other_tel']) . "," ;
		$file_string .= $point . "," . $order_Rs['counts'] . "," . $order_Rs['totals'] . ",";
		$file_string .= iconv("UTF-8","big5",$Rs['level_name']) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['reg_date']) . "," ;
		$file_string .= iconv("UTF-8","big5",$user_state) . "," ;
		$file_string .= iconv("UTF-8","big5",$Rs['recommendno']) . "," ;
		$file_string .= iconv("UTF-8","big5",$re_Rs['username']) . "," ;
		$file_string .= iconv("UTF-8","big5",$me_Rs['counts']) . "," ;
		$file_string .= iconv("UTF-8","big5",$saler_Rs['name']) . "\r\n" ;
		$i++;
	}
echo $file_string;


	//echo $file_string;
	//$Creatfile ="../__share/order_doc/member_".date("Y-m-d");


	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 * 
	 */
	///if ( $fh = fopen( $Creatfile.'.csv', 'w+' ) )
	//{
	//fputs ($fh, $file_string, strlen($file_string) );
	//fclose($fh);
	//@chmod ($Creatfile.'.csv',0777);
	//}
	
	//@header("location:".$Creatfile.'.csv');
}
?>