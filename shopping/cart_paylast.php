<?php
error_reporting(7);
session_start();
require "check_member.php";
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
	$tpl->assign("Pay_merchantID",      urlencode($_POST['MerchantID']));   //商店代号
	$tpl->assign("Pay_transMode",      urlencode($_POST['TransMode']));   //商店代号
	$tpl->assign("Pay_terminalID",      urlencode($_POST['TerminalID']));   //商店代号
	$tpl->assign("Order_serial",      urlencode($_POST['OrderID']));   //商店代号
	$tpl->assign("Total_member_all",      urlencode($_POST['TransAmt']));   //商店代号
	$tpl->assign("Install",      urlencode($_POST['Install']));   //商店代号
$Psql = "select * from `{$INFO[DBPrefix]}paymanager` as pg where pg.pid='3'";
$PQuery    = $DB->query($Psql);
$PRs=$DB->fetch_array($PQuery);
$payurl = $PRs['payurl'];
$returnurl = $INFO['site_url'] . $PRs['returnurl'];
$tpl->assign("payurl",      $payurl);
$tpl->assign("returnurl",      $returnurl);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
$tpl->display("cart_paylast_".VersionArea.".html");
?>



