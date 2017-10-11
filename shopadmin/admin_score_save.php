<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Update' ) {

	$score_id  = intval($FUNCTIONS->Value_Manage($_GET['score_id'],$_POST['score_id'],'back',''));

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
	'answer'     =>  $postedValue,
	'ifcheck'     =>  intval($_POST['ifcheck']),
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}score` SET $db_string WHERE score_id=".intval($score_id);
	$Result = $DB->query($Sql);
	$s_Sql   =  " select * from `{$INFO[DBPrefix]}score` where score_id='" . intval($score_id) . "' ";
		$Query       = $DB->query($s_Sql);
		$Result    = $DB->fetch_array($Query);
	if (intval($INFO['commnetPoint'])>0 &&intval($_POST['ifcheck'])==1){
					$FUNCTIONS->AddBonuspoint($Result['user_id'],intval($INFO['commnetPoint']),10,"評論商品贈點",1,0);
				}

	if ($Result)
	{
		$FUNCTIONS->setLog("回覆商品評價");
		$FUNCTIONS->header_location('admin_score_list.php');
	}else{
		$FUNCTIONS->sorry_back('back','');
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}score` where score_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除商品評價");
	$FUNCTIONS->header_location('admin_score_list.php');

}

if ($_POST['act']=='Check' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("update `{$INFO[DBPrefix]}score` set ifcheck=1 where score_id=".intval($Array_bid[$i]));
		$s_Sql   =  " select * from `{$INFO[DBPrefix]}score` where score_id='" . intval($Array_bid[$i]) . "' ";
		$Query       = $DB->query($s_Sql);
		$Result    = $DB->fetch_array($Query);
		if (intval($INFO['commnetPoint'])>0 ){
					$FUNCTIONS->AddBonuspoint($Result['user_id'],intval($INFO['commnetPoint']),10,"評論商品贈點",1,0);
				}
	}
	$FUNCTIONS->setLog("審核商品評論");
	$FUNCTIONS->header_location('admin_score_list.php');

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}score` where score_id=".intval($_GET['score_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}score` SET answer = '" . $_POST['FCKeditor1'] . "' WHERE score_id=".intval($_GET['score_id']);
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
