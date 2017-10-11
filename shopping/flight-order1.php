<?php
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");

include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
@header("Content-type: text/html; charset=utf-8");

$Query = $DB->query("select Departure from `{$INFO[DBPrefix]}order_table` where order_id='".$_GET['order_id']."' limit 0,1");
$Rs = $DB->fetch_array($Query);
$tpl->assign("Departure", $Rs['Departure']);

$tpl->assign("order_id", $_GET['order_id']);

if(isset($_POST['flight-time']) && isset($_POST['flight-id']) && isset($_POST['flight-no'])){
	$db_string = $DB->compile_db_update_string( array (
	'flightdate'           => trim($_POST['flight-time']),
	'flightstyle'         => trim($_POST['flightstyle']),
	'flightid'         => trim($_POST['flight-id']),
	'flightno'         => intval($_POST['flight-no']),
	'Departure'         => trim($_POST['Departure']),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_id='".$_POST['order_id']."'";
	$DB->query($Sql);
	$FUNCTIONS->header_location("../member/MyOrder.php?hometype=");
}

if(isset($_POST['flight-time'])){
	$datetime  = $_POST['flight-time'];
}else {
	$datetime  = date("Y-m-d",time());
}

$tpl->assign("datetime", $datetime);

$tpl->display("flight-order1.html");
?>
