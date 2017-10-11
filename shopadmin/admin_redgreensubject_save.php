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
$pic   = $FUNCTIONS->Upload_File($_FILES['ncimg']['name'],$_FILES['ncimg']['tmp_name'],'',"../UploadFile/LogoPic/");
	$db_string = $DB->compile_db_insert_string( array (
	'subject_name'            => trim($_POST['subject_name']),
	'start_date'             => ($_POST['start_date']),
	'end_date'             => ($_POST['end_date']),
	'subject_open'            => intval($_POST['subject_open']),
	'subject_content'         =>  $postedValue,
	'saleoff'            => intval($_POST['saleoff']),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}subject_redgreen` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增活動主題");
		$FUNCTIONS->header_location('admin_redgreensubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	$pic   = $FUNCTIONS->Upload_File($_FILES['ncimg']['name'],$_FILES['ncimg']['tmp_name'],$_POST['Old_pic'],"../UploadFile/LogoPic/");

	$db_string = $DB->compile_db_update_string( array (
	'subject_name'            => trim($_POST['subject_name']),
	'start_date'             => ($_POST['start_date']),
	'end_date'             => ($_POST['end_date']),
	'subject_open'            => intval($_POST['subject_open']),
	'subject_content'         =>  $postedValue,
	'saleoff'            => intval($_POST['saleoff']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}subject_redgreen` SET $db_string WHERE rgid=".intval($_POST['subject_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯活動主題");
		$FUNCTIONS->header_location('admin_redgreensubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}subject_redgreen` where rgid=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除活動主題");
		$FUNCTIONS->header_location('admin_redgreensubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}subject_redgreen` where rgid=".intval($_GET['subject_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}subject_redgreen` SET subject_content = '" . $_POST['FCKeditor1'] . "' WHERE rgid=".intval($_GET['subject_id']);
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