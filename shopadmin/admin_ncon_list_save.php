<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['act']=='Save' ) {

	$News_id          =  $_POST['newsidArr'];
	//$News_id          =  $_POST['newsid'];
	$Order            =  $_POST['order'];

	$Num  = count($Order);

	if ($Num>0){
		for ($i=0;$i<$Num;$i++){
			$Result_Update=$DB->query("update `{$INFO[DBPrefix]}news` set nord=".$Order[$i]."  where news_id=".intval($News_id[$i]));
		}

		if ($Result_Update)
		{
			$FUNCTIONS->header_location('admin_ncon_list.php');
		}else{
			$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
		}

	}else{

		$FUNCTIONS->sorry_back('admin_ncon_list.php',$INFO['admin_pcat_list_checkall']);
	}




}
?>