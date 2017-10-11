<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


if ( version_compare( phpversion(), '4.1.0' ) == -1 ){
	// prior to 4.1.0, use HTTP_POST_VARS
	$postArray = $HTTP_POST_VARS['FCKeditor1'];
}else{
	// 4.1.0 or later, use $_POST
	$postArray = $_POST['FCKeditor1'];
}

if (is_array($postArray))
{
	foreach ( $postArray as $sForm => $value )
	{
		$postedValue = $value;
	}
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;



if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'subject_name'            => trim($_POST['subject_name']),
	'subject_num'             => intval($_POST['subject_num']),
	'subject_open'            => intval($_POST['subject_open']),
	'salecount'            => intval($_POST['salecount']),
	'subject_content'         =>  $postedValue,
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}sale_subject` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增促銷主題");
		$FUNCTIONS->header_location('admin_salesubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'subject_name'            => trim($_POST['subject_name']),
	'subject_num'             => intval($_POST['subject_num']),
	'subject_open'            => intval($_POST['subject_open']),
	'subject_content'         =>  $postedValue,
	'salecount'            => intval($_POST['salecount']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}sale_subject` SET $db_string WHERE subject_id=".intval($_POST['subject_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯促銷主題");
		$FUNCTIONS->header_location('admin_salesubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}sale_subject` where subject_id=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除促銷主題");
		$FUNCTIONS->header_location('admin_salesubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}

?>