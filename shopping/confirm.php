<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");

$storeid = $_GET["storeid"];
$ordernumber = $_GET["ordernumber"];
$amount = $_GET["amount"];

$Query = $DB->query(" select ot.* from `{$INFO[DBPrefix]}order_table` ot where ot.order_serial='".trim($ordernumber)."'");
$Num       = $DB->num_rows($Query);
if ( $Num <= 0 ){
	echo "<EPOS OrderConfirm='no' />";
}else{
	$Result    = $DB->fetch_array($Query);
	if ($amount==($Result['discount_totalPrices']+$Result['transport_price'])){
		echo "<EPOS OrderConfirm='yes' />";	
	}else{
		echo "<EPOS OrderConfirm='no' />";	
	}
}
exit;
?>