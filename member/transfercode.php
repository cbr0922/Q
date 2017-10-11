<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include_once 'crypt.class.php';
$ticketcode =$_GET['ticketcode'];


if($_POST['act']=="send"){
	$Sql = "select t.* from `{$INFO[DBPrefix]}ticket` as t  where  t.ticketid=".intval($_POST['ticketid'])." ";
	$Query =  $DB->query($Sql);
	$Rs = $DB->fetch_array($Query);
	if($Rs['moneytype']==1)
		$money = ($Rs['money']*100) . "%";
	else
		$money = $Rs['money'] . "元";
	$checkno = MD5Crypt::Encrypt ($_POST['ticketid'] . "&" . $_POST['ticketcode'] . "&" . intval($_SESSION['user_id']), $_POST['ticketcode']); 
	$Array =  array("ticketcode"=>$_POST['ticketcode'],"ticketid"=>$_POST['ticketid'],"checkno"=>$checkno,"sendcontent"=>$_POST['email_text'],"ticketname"=>$Rs['ticketname'],"money"=>$money . " (購買" . $Rs['ordertotal'] . "元以上才能使用此張優惠券)");
	include "SMTP.Class.inc.php";
	include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$j = 0;
	
	$cmail = $_POST['email_input'];
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	$SMTP->MailForsmartshop($cmail,"",39,$Array);
	$FUNCTIONS->sorry_back("mymoveticket.php","發送成功");
}

$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->display("transfercode.html");
?>