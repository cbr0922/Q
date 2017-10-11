<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}contact`  where contact_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除聯繫我們");
	$FUNCTIONS->header_location('admin_contact_list.php');

}

if ($_POST['act']=='Update' ) {

	$cid = $_POST['cid'];
	$state = $_POST['status'];

	$Sql = "UPDATE `{$INFO[DBPrefix]}contact` SET state=".intval($state)." WHERE contact_id=".intval($cid);
	$Result_Insert = $DB->query($Sql);

	$FUNCTIONS->setLog("編輯聯繫我們");
	$FUNCTIONS->header_location('admin_contact_list.php');

}

?>
