<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";


if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'payname'       => trim($_POST['payname']),
	'content'       => trim($_POST['content']),
	'shopcode'       => trim($_POST['shopcode']),
	'f1'       => trim($_POST['f1_1']) . "|" . trim($_POST['f1_2']),
	'f2'       => trim($_POST['f2_1']) . "|" . trim($_POST['f2_2']),
	'f3'       => trim($_POST['f3_1']) . "|" . trim($_POST['f3_2']),
	'f4'       => trim($_POST['f4_1']) . "|" . trim($_POST['f4_2']),
	'f5'       => trim($_POST['f5_1']) . "|" . trim($_POST['f5_2']),
	'payurl'       => trim($_POST['payurl']),
	'returnurl'       => trim($_POST['returnurl']),
	'returnurl2'       => trim($_POST['returnurl2']),
	'paytype'       => intval($_POST['paytype']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}paymanager` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);
	$id = mysql_insert_id();
	

	if ($Result_Insert)
	{
		
			$FUNCTIONS->header_location("admin_paymanager_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'payname'       => trim($_POST['payname']),
	'content'       => trim($_POST['content']),
	'shopcode'       => trim($_POST['shopcode']),
	'f1'       => trim($_POST['f1_1']) . "|" . trim($_POST['f1_2']),
	'f2'       => trim($_POST['f2_1']) . "|" . trim($_POST['f2_2']),
	'f3'       => trim($_POST['f3_1']) . "|" . trim($_POST['f3_2']),
	'f4'       => trim($_POST['f4_1']) . "|" . trim($_POST['f4_2']),
	'f5'       => trim($_POST['f5_1']) . "|" . trim($_POST['f5_2']),
	'payurl'       => trim($_POST['payurl']),
	'returnurl'       => trim($_POST['returnurl']),
	'returnurl2'       => trim($_POST['returnurl2']),
	'paytype'       => intval($_POST['paytype']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}paymanager` SET $db_string WHERE pid=".intval($_POST['id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{	  		  $FUNCTIONS->header_location("admin_paymanager_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}paymanager` where pid=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->header_location("admin_paymanager_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}paymanager` where pid=".intval($_GET['id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}paymanager` SET content = '" . $_POST['content'] . "' WHERE pid=".intval($_GET['id']);
		$Result = $DB->query($Sql);
		$array = array(
			'error' => false,
			'message' => '已自動保存'
		);
	}else{
		$array = array(
			'error' => true,
			'message' => '自動保存失敗'
		);
	}

    echo stripslashes(json_encode($array));
	
}

?>