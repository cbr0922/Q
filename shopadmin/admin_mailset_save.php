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

	$Sql = "UPDATE `{$INFO[DBPrefix]}sendtype` SET $db_string WHERE sendtype_id=".intval($_POST['sendtype_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯郵件內容");
		$FUNCTIONS->header_location('admin_mailset_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
//autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}sendtype` where sendtype_id=".intval($_GET['sendtype_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}sendtype` SET sendcontent = '" . $_POST['FCKeditor1'] . "' WHERE sendtype_id=".intval($_GET['sendtype_id']);
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