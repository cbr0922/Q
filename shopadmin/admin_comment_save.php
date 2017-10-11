<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Update' ) {

	$Comment_id  = intval($FUNCTIONS->Value_Manage($_GET['comment_id'],$_POST['comment_id'],'back',''));

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

	$db_string = $DB->compile_db_update_string( array (
	'comment_id'         => intval($Comment_id),
	'comment_answer'     =>  $postedValue,
	'already_read'       => 1,
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}good_comment` SET $db_string WHERE comment_id=".intval($Comment_id);
	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("回覆商品評論");
		$FUNCTIONS->header_location('admin_comment_list.php');
	}else{
		$FUNCTIONS->sorry_back('back','');
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}good_comment` where comment_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除商品評論");
	$FUNCTIONS->header_location('admin_comment_list.php');

}

if ($_POST['act']=='Check' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("update `{$INFO[DBPrefix]}good_comment` set ifcheck=1 where comment_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("審核商品評論");
	$FUNCTIONS->header_location('admin_comment_list.php');

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}good_comment` where comment_id=".intval($_GET['Comment_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}good_comment` SET comment_answer = '" . $_POST['FCKeditor1'] . "' WHERE comment_id=".intval($_GET['Comment_id']);
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