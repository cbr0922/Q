<?php
error_reporting(7);
@header("Content-type: text/html; charset=big5");
include("../configs.inc.php");
include("global.php");
$ordernumber = $_GET["MerchantKey"];
$amount = $_GET["amt"];
$Query = $DB->query(" select ot.* from `{$INFO[DBPrefix]}order_table` ot where ot.order_serial='".trim($ordernumber)."'");
$Num       = $DB->num_rows($Query);
if ( $Num <= 0 ){
}else{
	$Result    = $DB->fetch_array($Query);
	if ($amount==($Result['discount_totalPrices']+$Result['transport_price'])){
		echo "
		  <input type=\"hidden\" name=\"CompanyID\" value=\"" . $_GET['CompanyID'] . "\">
		  <input type=\"hidden\" name=\"orderNoGenDate\" value=\"" . date("Y/m/d",time()) . "\">
		  <input type=\"hidden\" name=\"PtrAcno\" value=\"" . $_GET['PtrAcno'] . "\">
		  <input type=\"hidden\" name=\"ItemNo\" value=\"" .($ordernumber) . "\">
		  <input type=\"hidden\" name=\"PurQuantity\" value=\"1\">
		  <input type=\"hidden\" name=\"amount\" value=\"" . $amount . "\">
		  <input type=\"hidden\" name=\"MerchantKey\" value=\"" .($ordernumber) . "\">
		  <INPUT type=\"hidden\" id=rtnCode name=rtnCode value=\"0000\">
		  ";
	}
}
exit;
?>