<?php

error_reporting(7);

@header("Content-type: text/html; charset=utf-8");

require "check_member.php";

include("../configs.inc.php");



$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($_SESSION['user_id'])."' limit 0,1";

$Query  = $DB->query($Sql);

$Rs=$DB->fetch_array($Query);

$recommendno = $Rs['memberno'];

$truename = $Rs['true_name'];

$tpl->assign("recommendno",   $recommendno);



if($_POST['act']=="send"){

	$Array =  array("recommendno"=>$recommendno,"truename"=>$truename,"sendcontent"=>$_POST['email_text']);

	include "SMTP.Class.inc.php";

	include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容

	$j = 0;

	for($i=1;$i<=$_POST['count'];$i++){

		if($_POST['email_input' . $i]!=""){

			$cmail_array[$j] = $_POST['email_input' . $i];

			$j++;

		}

	}

	$cmail = implode(",",$cmail_array);

	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);

	$SMTP->MailForsmartshop($cmail,"",27,$Array);

	$FUNCTIONS->sorry_back("recomendfriend.php","發送成功");

}


$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->display("recomendfriend.html");

?>