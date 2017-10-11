<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
session_start();
include("../configs.inc.php");
if($_GET['action']=="Ok" || $_GET['action']=="Cancle"){
	$pid = trim($_GET['pid']);
	$checkno = trim($_GET['checkno']);
	if($pid=="" || $checkno==""){
		$FUNCTIONS->sorry_back('back',"發生錯誤");	
		exit;
	}else{
		if($_GET['action']=="Ok")
			$state=7;
		else
			$state=6;
		$Sql = "UPDATE `{$INFO[DBPrefix]}provider` SET state='" . $state . "' WHERE provider_id='".$pid . "' and checkno='" . $checkno . "'";
		$Result_Insert = $DB->query($Sql);	
		$FUNCTIONS->sorry_back('../index.php',"審核通過");	
		exit;
	}
}

$info_id = 17;
$Query   = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=".$info_id." limit 0,1");
$Num   = $DB->num_rows($Query);
if ( $Num==0 )
	$FUNCTIONS->header_location("../index.php?hometype=" . $_GET['hometype']);

if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$Content = $Result['info_content'];
}


$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Basic_Command);
$tpl->assign("Content",        $Content);
$tpl->display("reg_rule2.html");
?>
