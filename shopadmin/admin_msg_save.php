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


if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'sendtitle'            => trim($_POST['sendtitle']),
	'sendcontent'          =>  $postedValue,
	'sendstatus'           => intval($_POST['sendstatus']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}sendmsg` SET $db_string WHERE sendtype_id=".intval($_POST['sendtype_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯簡訊內容");
		$FUNCTIONS->header_location('admin_msglist.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


?>