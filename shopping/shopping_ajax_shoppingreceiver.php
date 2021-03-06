<?php
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
if(!isset($_SESSION['cart'])) {
	header("Location:shopping.php");
}


$cart =&$_SESSION['cart'];

if ($_GET['key']==""){
	$_GET['key']=$cart->get_key;
}
//echo $cart->transname_area2;
include_once "../language/".$INFO['IS']."/Cart.php";

if ($cart->transname_area==""){
	$tpl->assign("transname_area",  "");
	$tpl->assign("transname_area2",  "");
}else{
	$tpl->assign("transname_area",  $cart->transname_area);
	$tpl->assign("transname_area2",  $cart->transname_area2);
}
if($_GET['op']=="userinfo"){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1 ");
	$Num   =  $DB->num_rows($Query);
	if ( $Num > 0 ){
		$Rs  = $DB->fetch_array($Query);
		$email       = trim($Rs['email']);
		$tel         = MD5Crypt::Decrypt ( $Rs['tel'], $INFO['tcrypt']);
		$post        = $Rs['post'];
		$city        = $Rs['city'];
		$canton      = $Rs['canton'];
		$Country      = $Rs['Country'];
		$addr        = $Rs['addr'];
		$true_name   = $Rs['true_name'];
		$other_tel   =  MD5Crypt::Decrypt ( $Rs['other_tel'], $INFO['mcrypt']);
		$tpl->assign("receiver_name",        $true_name);
		$tpl->assign("receiver_email",        $email);
		$tpl->assign("post",        $post);
		$tpl->assign("receiver_tele",        $tel);
		$tpl->assign("receiver_mobile",        $other_tel );
		$tpl->assign("county",        $Country);
		$tpl->assign("province",        $canton);
		$tpl->assign("city",        $city);
		$tpl->assign("addr",        $addr);
		//echo $Rs['Country'];
		//echo $cart->transname_area;
		if ($cart->transname_area==""){
			$tpl->assign("transname_area",  ($Rs['Country']));
			$tpl->assign("transname_area2",  ($Rs['canton']));
		}else{
			$tpl->assign("transname_area",  $cart->transname_area);
			$tpl->assign("transname_area2",  $cart->transname_area2);
		}
	}
}elseif($_GET['op']=="receiverinfo" && intval($_GET['reid'])>0){
	$sql = "select * from `{$INFO[DBPrefix]}receiver` where reid='" . $_GET['reid'] . "'";
	$Query = $DB->query($sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$tpl->assign("receiver_name",       trim( $Result['receiver_name']));
		$tpl->assign("receiver_email",        $Result['receiver_email']);
		$tpl->assign("post",        $Result['post']);
		$tpl->assign("receiver_tele",       MD5Crypt::Decrypt (  $Result['receiver_tele'], $INFO['tcrypt']));
		$tpl->assign("receiver_mobile",        MD5Crypt::Decrypt (  $Result['receiver_mobile'], $INFO['mcrypt']));
		$tpl->assign("county",        $Result['county']);
		$tpl->assign("province",        $Result['province']);
		$tpl->assign("city",        $Result['city']);
		$tpl->assign("reid",        $Result['reid']);
		$tpl->assign("addr",        $Result['addr']);
		if ($cart->transname_area==""){
			$tpl->assign("transname_area",  ($Result['county']));
			$tpl->assign("transname_area2",  ($Result['province']));
		}else{
			$tpl->assign("transname_area",  $cart->transname_area);
			$tpl->assign("transname_area2",  $cart->transname_area2);
		}
	}
}else{
	$tpl->assign("receiver_name",        "");
		$tpl->assign("receiver_email",       "");
		$tpl->assign("post",        "");
		$tpl->assign("receiver_tele",        "");
		$tpl->assign("receiver_mobile",        "");
		$tpl->assign("county",        "");
		$tpl->assign("province",       "");
		$tpl->assign("city",       "");
		$tpl->assign("reid",        "");
		$tpl->assign("addr",        "");
		if ($cart->transname_area==""){
			$tpl->assign("transname_area",  (""));
			$tpl->assign("transname_area2",  (""));
		}else{
			$tpl->assign("transname_area",  $cart->transname_area);
			$tpl->assign("transname_area2",  $cart->transname_area2);
		}
}
/**
 *  �����ǻ���լ���r�g
 */

$Query  = $DB->query("select transtime_id,transtime_name from `{$INFO[DBPrefix]}transtime` order by transtime_id asc ");
$i=0;
while($Rs=$DB->fetch_array($Query)){
	$HomeTimeType[$i][transtime_id]              = $Rs['transtime_id'];
	$HomeTimeType[$i][transtime_name]            = $Rs['transtime_name'];
	$HomeTimeType[$i][transtime64encode_name]    = base64_encode($Rs['transtime_name']);
	$HomeTimeType[$i][transtime64decode_name]    = $Rs['transtime_name'];

	$i++;
}
$key_value = explode("P",$_GET['key']);
$week = date("w",time());
if($week!=6&&$week!=0){
	$Sql      = "select * from `{$INFO[DBPrefix]}holiday` where  startdate<='" . date("Y-m-d") . "' and enddate>='" . date("Y-m-d") . "'";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if($Num>0){
		$Rs=$DB->fetch_array($Query);
		$enddate = 	$Rs['enddate'];
		$enddate_array = explode("-",$enddate);
		$endtime = mktime(0,0,0,$enddate_array[1],$enddate_array[2]+1,$enddate_array[0]);
		$week = date("w",$endtime);
		$dif = ($endtime-time())/(60*60*24)+1;
	}
}
$days = intval($INFO['senddate_day']);
$tpl->assign("days",         intval($days+$dif));
$tpl->assign("provider_id",  $key_value[1]);
$tpl->assign("HomeTimeType",  $HomeTimeType);
$tpl->assign("senddate_day",  intval($INFO['senddate_day']));
$tpl->assign("ifsenddate",  intval($INFO['ifsenddate']));
$tpl->assign("transname_id",  $cart->transname_id);
$tpl->assign($Cart);
$tpl->display("shopping_ajax_shoppingreceiver.html");
?>
