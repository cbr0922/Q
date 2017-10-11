<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";


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
	'k_type_name'       => trim($_POST['k_type_name']),
	'status'            => intval($_POST['status']),
	'k_type_content'    => $postedValue ,
	'checked'           => trim($_POST['checked']),
	'k_type_id'            => intval($_POST['k_type_id']),
	'typegroup'            => intval($_POST['typegroup']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu_type` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增在線客服問題類別");
		if ($_POST['ifgo_on']==1) {
			$url_locate = 'admin_kefu_type.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
			$FUNCTIONS->header_location($url_locate);
		}else {
			$url_locate = 'admin_kefu_type_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
			$FUNCTIONS->header_location($url_locate);
		}
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'k_type_name'          => trim($_POST['k_type_name']),
	'k_type_content'       => $postedValue ,
	'status'               => intval($_POST['status']),
	'checked'              => trim($_POST['checked']),
	'k_type_id'            => intval($_POST['k_type_id']),
	'typegroup'            => intval($_POST['typegroup']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}kefu_type` SET $db_string WHERE k_type_id=".intval($_POST['k_type_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{	
	$FUNCTIONS->setLog("編輯在線客服問題類別");
	if ($_POST['ifgo_on']==1) {
		$url_locate = 'admin_kefu_type.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}else {

		$url_locate = 'admin_kefu_type_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}kefu_type` where k_type_id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除在線客服問題類別");
		$url_locate = 'admin_kefu_type_list.php?type='.$_POST['type'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}kefu_type` where k_type_id=".intval($_GET['k_type_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}kefu_type` SET k_type_content = '" . $_POST['FCKeditor1'] . "' WHERE k_type_id=".intval($_GET['k_type_id']);
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