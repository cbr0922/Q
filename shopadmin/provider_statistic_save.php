<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
foreach($_GET as $k=>$v){
	$restring .= "&" . $k . "=" . $v;	
}
//echo $restring;exit;
if ($_GET['acts'] == "sale"){
	$Result =  $DB->query("update `{$INFO[DBPrefix]}provider_month` set zhang='" . $_POST['zhang'] . "' where mid='" . intval($_GET['mid']) . "'");
	$FUNCTIONS->setLog("¹©‘ªÉÌŒ¦Ž¤½YËã");
	
	$FUNCTIONS->header_location('provider_statistic_provider_detail.php?' . $restring);
	exit;
}
/*
$a_array = array (
	'invoice_title'                => trim($_POST['invoice_num']),
		) ;
	
	$db_string = $DB->compile_db_update_string($a_array);
$Sql = "UPDATE `{$INFO[DBPrefix]}provider` SET $db_string WHERE provider_id=".intval($_SESSION['sa_id']);
$Result_Insert = $DB->query($Sql);
*/
//print_r($_POST);exit;
	$Result =  $DB->query("update `{$INFO[DBPrefix]}provider_month` set invoice_titles='" . $_POST['invoice_title'] . "' where mid='" . $_POST['mid'] . "'");
$FUNCTIONS->header_location('provider_statistic_provider.php?' . $restring);

?>