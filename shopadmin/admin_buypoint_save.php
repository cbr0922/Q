<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}user` where memberno='".$_POST['username']."' limit 0,1");
$Num    = $DB->num_rows($Query);
if ($Num==0){
	$FUNCTIONS->sorry_back("back","");
}else{
	$Result = $DB->fetch_array($Query);
	 $user_id= $Result['user_id'];	
}
$FUNCTIONS->AddBuypoint(intval($user_id),intval($_POST['bonus']),intval($_POST['type']),$_POST['content'],0,$_SESSION['sa_id'],$_SESSION['LOGINADMIN_TYPE'],4);	

$FUNCTIONS->setLog("調整帳上餘額");
$FUNCTIONS->header_location('admin_buypoint_list.php');
?>
