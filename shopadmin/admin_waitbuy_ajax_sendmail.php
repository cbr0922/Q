<?php
//include_once "Check_Admin.php";
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include(Classes . "/global.php");
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$gid        = intval($FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back',''));
//***************************************************************************************************************************
include_once Classes . "/SMTP.Class.inc.php";
include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
include Classes . "/sms2.inc.php";
include Classes . "/sendmsg.php";
					
			$sendmsg = new SendMsg;
//***************************************************************************************************************************
//echo $mailstr = $_GET['mailstr'];
$Query = $DB->query(" select goodsname from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)." limit 0,1"
);
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$goodsname        =  $Result['goodsname'];
	
	//$DB->free_result($Query_time);
}else{
	exit;
}
$DB->free_result($Query);
unset ($Query);
unset ($Num);
unset ($Result);

$mailno = $_GET['mailno'];
$permailnum = $_GET['persendnum'];
$firstno = ($mailno-1)*$permailnum;
$mail_array = array();
$j=0;
 $i = 0;
 $mail_array = array();
 $Query = $DB->query(" select mail from `{$INFO[DBPrefix]}falsemail` where mail<>'' and no='0' and sendtime='' order by mail limit " . $firstno . "," . $permailnum);
 while($Result= $DB->fetch_array($Query)){
	$m_array = explode("||",$Result['mail']);
	$Array =  array("username"=>trim($m_array[1]),"goodsname"=>trim($goodsname));
	$SMTP->MailForsmartshop(trim($m_array[0]),"",20,$Array);
	if (trim($m_array[2])!="")
		$sendmsg->send(trim($m_array[2]),$Array,20);
	$mail_array[$i]	= $m_array[0];
	$i++;
 }
 $mailstr = implode(",",$mail_array);


//print_r($_GET);
echo str_replace(",",chr(10) . chr(13),$mailstr). chr(10) . chr(13) . "\r\n";
$DB->my_close();
exit;
?>
