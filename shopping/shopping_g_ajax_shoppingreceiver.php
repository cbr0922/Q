<?php
session_start();
include("../configs.inc.php");
require_once RootDocument.'/Classes/cart_group.class.php';
include("global.php");
if(!isset($_SESSION['cart_group'])) {
	header("Location:shopping.php");
}


$cart =&$_SESSION['cart_group'];

if ($_GET['key']==""){
	$_GET['key']=$cart->get_key;
}
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
		$tel         = $Rs['tel'];
		$post        = $Rs['post'];
		$city        = $Rs['city'];
		$canton      = $Rs['canton'];
		$Country      = $Rs['Country'];
		$addr        = $Rs['addr'];
		$true_name   = $Rs['true_name'];
		$other_tel   = $Rs['other_tel'];
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
		$tpl->assign("receiver_tele",        $Result['receiver_tele']);
		$tpl->assign("receiver_mobile",        $Result['receiver_mobile']);
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
 *  这里是获得宅配時間
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
$tpl->assign("provider_id",  $key_value[1]);
$tpl->assign("HomeTimeType",  $HomeTimeType);
$tpl->assign($Cart);
$tpl->display("shopping_g_ajax_shoppingreceiver.html");
?>
