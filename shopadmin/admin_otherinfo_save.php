<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$postArray = ( $_POST['FCKeditor1']);

if (is_array($postArray))
{
	foreach ( $postArray as $sForm => $value )
	{
		$postedValue = $value;
	}
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;

/**
 *  判断如果是免费或演示版本，并发现更改版本信息资料的时候。自动加上版本信息。
 */
if (is_file( RootDocument."/Classes/version.php" )){
	require RootDocument."/Classes/version.php";


	if ( ($_VERSION->VersionType == 'Free' || $_VERSION->VersionClass == 'Demo' ) && intval($_POST['Info_id'])==7)
	{
		strpos($postedValue,"SmartShop") ? "" : $postedValue .= "<center>".$_VERSION->COPYRIGHT."</center>" ;
	}
}elseif(intval($_POST['Info_id'])==7){
	$postedValue .= "<center>".'Copyright 2005 - 2007 © <a href="http://www.SmartShop.com.tw" target="_blank">SmartShop Software Inc.</a> SmartShop® . All rights reserved.</center>';
}
if ($_POST['Action']=='Insert' ) {


	$db_string = $DB->compile_db_insert_string( array (
	'info_content'        => $postedValue,
	'title'        => $_POST['title'],
	'language'        => $_POST['language'],
	'top_id'        => $_POST['top_id'],
	'path'        => $_POST['path'],
	)      );



	$Sql="INSERT INTO `{$INFO[DBPrefix]}admin_info` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	//$Sql = "UPDATE admin_info SET info_content=".$postedValue." WHERE info_id=".intval($_POST['Info_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("新增網站資料");
		//  $FUNCTIONS->sorry_back('admin_otherinfo.php?info_id='.$_POST['Info_id'].'&Action=Modi',$INFO['sava_ok']);
		$FUNCTIONS->header_location("admin_otherinfo_list.php");
		//$FUNCTIONS->header_location('admin_otherinfo.php?info_id=1&Action=Modi');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
if ($_POST['Action']=='Update' ) {


	$db_string = $DB->compile_db_update_string( array (
	'info_content'        => $postedValue,
	'title'        => $_POST['title'],
	'language'        => $_POST['language'],
	'top_id'        => $_POST['top_id'],
	'path'        => $_POST['path'],
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}admin_info` SET $db_string WHERE info_id=".intval($_POST['Info_id']);

	//$Sql = "UPDATE admin_info SET info_content=".$postedValue." WHERE info_id=".intval($_POST['Info_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯網站資料");
		//  $FUNCTIONS->sorry_back('admin_otherinfo.php?info_id='.$_POST['Info_id'].'&Action=Modi',$INFO['sava_ok']);
		$FUNCTIONS->header_location("admin_otherinfo_list.php");
		//$FUNCTIONS->header_location('admin_otherinfo.php?info_id=1&Action=Modi');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}admin_info` where info_id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除網站資料");
		$FUNCTIONS->header_location("admin_otherinfo_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}admin_info` where info_id=".intval($_GET['info_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}admin_info` SET info_content = '" . $_POST['FCKeditor1'] . "' WHERE info_id=".intval($_GET['info_id']);
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
