<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载配置文件
 */
include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";



if ($_POST['Action']=='Insert' ) {
	$timeforserialnum = time();
	$serialnum = substr(md5($timeforserialnum),0,14);
	
	if ($_POST['order_serial']!=""){
		$order_Sql = "select * from `{$INFO[DBPrefix]}order_table` where order_serial='" . $_POST['order_serial'] . "'";
		$Query_order         = $DB->query($order_Sql);
		$order_num = $DB->num_rows($Query_order);
		if ($order_num<=0){
			$Result_say = "訂單並不存在！";	
		}else{
			$Rs_order = $DB->fetch_array($Query_order);
			$provider_id = $Rs_order['provider_id'];	
		}
	}
	if ($_POST['marketno']!=""){
		$order_Sql = "select * from `{$INFO[DBPrefix]}goods` where goodsno='" . $_POST['marketno'] . "'";
		$Query_order         = $DB->query($order_Sql);
		$order_num = $DB->num_rows($Query_order);
		if ($order_num<=0){
			$Result_say = "賣場並不存在！";	
		}else{
			$Rs_order = $DB->fetch_array($Query_order);
			$provider_id = $Rs_order['provider_id'];	
		}
	}
	
	if ($Result_say==""){

	$Sql_linshi           = "select k_type_name,k_type_content from `{$INFO[DBPrefix]}kefu_type` where k_type_id = '".$_POST[kefu_type]."' limit 0,1 ";
	$Query_linshi         = $DB->query($Sql_linshi);
	$type_name_linshi_num = $DB->num_rows($Query_linshi);

	if ($type_name_linshi_num>0){
		$type_name_linshi = $DB->fetch_array($Query_linshi);
		$type_name_linshi_content = $type_name_linshi['k_type_content'];
	}

	$Sql_linshi = "select k_chuli_id , k_chuli_name from `{$INFO[DBPrefix]}kefu_chuli` order by checked Desc;";
	$Query_linshi = $DB->query($Sql_linshi);
	$chuli_name_linshi = $DB->fetch_array($Query_linshi);

	$type_chuli = $_POST['kefu_type'].'-'.$chuli_name_linshi['k_chuli_id'];
	$type_chuli_name = $type_name_linshi['k_type_name'].'-'.$chuli_name_linshi['k_chuli_name'];
	$db_string = $DB->compile_db_insert_string( array (
	'serialnum'                 => trim($serialnum),
	'type_chuli'                => trim($type_chuli),
	'type_chuli_name'           => trim($type_chuli_name),
	'user_id'                   => trim($_SESSION['user_id']),
	'username'                  => trim($_POST['username']),
	'realname'                  => trim($_POST['realname']),
	'email'                     => trim($_POST['email']),
	'title'                     => trim($_POST['title']),
	'lastdate'                  => $timeforserialnum,
	'k_kefu_con'                => $_POST['k_kefu_con'],
	'order_serial'                => $_POST['order_serial'],
	'marketno'                => $_POST['marketno'],
	'provider_id'                => $provider_id,
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);
	$kefu_id = mysql_insert_id();
	if ($Result_Insert)
	{
		$Result_say = $KeFu_Pack['ok'] ;//'提交成功！';
		$FUNCTIONS->setKefuLog($kefu_id,"客戶詢問",0);
		if (trim($type_name_linshi_content)!="") {
			$Emailcontent = $KeFu_Pack['YourEmailIs']."\n\n".$_POST['k_kefu_con']."\n\n\n\n\n\n".$type_name_linshi_content;
			$Array  = array("mailsubject"=>$KeFu_Pack['AboutYourEmailIs']."".trim($_POST['title']),"mailbody"=>$Emailcontent);
			include "SMTP.Class.inc.php";
			include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
			$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			$SMTP->MailForsmartshop(trim($_POST['email']),"",'GroupSend',$Array);
		}
	}else {
		$Result_say = $KeFu_Pack['bad'] ;//'提交失败！';
	}
	}
	if($_POST['type']=="back")
	$FUNCTIONS->header_location('../article/article.php?articleid=38');
	else
	$FUNCTIONS->header_location('kefu_list.php');

}

if ($_POST['Action']=='Post' ) {
	$timeforserialnum = time();

	$db_string = $DB->compile_db_insert_string( array (
	'kid'                       => trim($_POST['kid']),
	'username'                  => trim($_POST['username']),
	'postdate'                  => $timeforserialnum,
	'email'                     => trim($_POST['email']),
	'k_post_title'              => trim($_POST['title']),
	'k_post_con'                => trim($_POST['k_post_con']),

	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu_posts` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	$Sql="UPDATE `{$INFO[DBPrefix]}kefu` set postnum = 1 , lastdate = ".$timeforserialnum.",status =0 where kid = ".$_POST['kid'];
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setKefuLog($_POST['kid'],"客戶回覆",0);
		$Result_say = $KeFu_Pack['ok'] ;//'提交成功！';
	}else {
		$Result_say = $KeFu_Pack['bad'] ;//'提交失败！';
	}

}

$tpl->assign("Submit_Result", $KeFu_Pack['submit_result']);     //提交结果
$tpl->assign("Result_say",    $Result_say);


$tpl->assign("Submit_Result_Js", $KeFu_Pack['submit_js_result_one'].$Result_say.$KeFu_Pack['submit_js_result_two']);     //JS提示


$tpl->assign("kefu_list_say",  $KeFu_Pack['type_list']);//留言列表
$tpl->assign("kefu_add_say",   $KeFu_Pack['user_write']);//用户留言
$tpl->display("kefu_save.html");
?>
