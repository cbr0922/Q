<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$postArray = ( $_POST['FCKeditor1']);

if (is_array($postArray))
{
	foreach ( $postArray as $sForm => $value )
	{
		$postedValue =( $value);
	}
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;



if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'publication_title'       => trim($_POST['publication_title']),
	'publication_start_time'  => time(),
	'publication_content'     =>  $postedValue,
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}publication` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result=$DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("新增電子報");
		$FUNCTIONS->header_location('admin_publication_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'publication_title'       => trim($_POST['publication_title']),
	'publication_start_time'  => trim($_POST['publication_start_time']),
	'publication_content'     => $postedValue,
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}publication` SET $db_string WHERE publication_id=".intval($_POST['publication_id']);
	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("修改電子報");
		$FUNCTIONS->header_location('admin_publication_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array =  $_POST['cid'];
	$Num = count($Array);

	for ($i=0;$i<$Num;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}publication` where publication_id=".intval($Array[$i]));
	}

	unset($Array);
	unset($Num);
	unset($Result);
	$FUNCTIONS->setLog("刪除電子報");
	$FUNCTIONS->header_location('admin_publication_list.php');

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}publication` where publication_id=".intval($_GET['publication_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}publication` SET publication_content = '" . $_POST['FCKeditor1'] . "' WHERE publication_id=".intval($_GET['publication_id']);
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