<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Update' ) {

	$Poll_id         =  trim($_POST[Poll_id]);
	$Title           =  trim($_POST[Title]);
	$PollOptionNum   =  intval($_POST[PollOptionNum]);
	$open            =  intval($_POST[open]);
	$subpoll_id      =  $_POST[subpoll_id];
	$subtitle        =  $_POST[subtitle];
	$subtitleNum     =  $_POST[subtitleNum];


	$Nums = count($subtitle);
	for($i=0;$i<$Nums;$i++){
		$subtitledetail      = trim($subtitle[$i]);
		$subtitleNumdetail   = intval($subtitleNum[$i]);
		$subpoll_iddetail    = intval($subpoll_id[$i]);
		$Sql = "update  `{$INFO[DBPrefix]}poll_option` set subtitle='$subtitledetail',points='$subtitleNumdetail' where subpoll_id='$subpoll_iddetail'";
		$DB->query($Sql);
	}

	if (!empty($_POST[addsubtitle])){
		$Sql = "insert into `{$INFO[DBPrefix]}poll_option` (subtitle,points,poll_id) values ('".trim($_POST[addsubtitle])."','".trim($_POST[addsubtitleNum])."','$Poll_id')";
		$DB->query($Sql);
		$Nums = intval($Nums+1);
	}

	$DB->query("update `{$INFO[DBPrefix]}poll` set title='$Title',polloptionnum='$Nums',open='$open' where poll_id='$Poll_id'");

	$FUNCTIONS->setLog("編輯在線投票");
	$FUNCTIONS->header_location('admin_poll_list.php');
}


if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}poll` where poll_id=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}poll_option` where poll_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除在線投票");
	$FUNCTIONS->header_location('admin_poll_list.php');

}


if ($_POST['Action']=='DelItem' ) {
	$Poll_id =  $_POST['Poll_id'];
	$Result =  $DB->query("delete from `{$INFO[DBPrefix]}poll_option` where subpoll_id=".intval($_GET[subpoll_id]));
	$FUNCTIONS->setLog("刪除投票項");
	$FUNCTIONS->header_location('admin_poll_edit.php?Action=Modi&Poll_id='.intval($Poll_id));
}

?>