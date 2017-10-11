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


	$Sql="INSERT INTO `{$INFO[DBPrefix]}edmsmg` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result=$DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("新增電子報");
		$FUNCTIONS->header_location('admin_edmsmg_list.php');
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



	$Sql = "UPDATE `{$INFO[DBPrefix]}edmsmg` SET $db_string WHERE publication_id=".intval($_POST['publication_id']);
	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("修改電子報");
		$FUNCTIONS->header_location('admin_edmsmg_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array =  $_POST['cid'];
	$Num = count($Array);

	for ($i=0;$i<$Num;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}edmsmg` where publication_id=".intval($Array[$i]));
	}

	unset($Array);
	unset($Num);
	unset($Result);
	$FUNCTIONS->setLog("刪除電子報");
	$FUNCTIONS->header_location('admin_edmsmg_list.php');

}

?>