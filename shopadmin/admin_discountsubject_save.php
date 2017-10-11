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
	'min_money'            => intval($_POST['min_money']),
	'min_count'            => intval($_POST['min_count']),
	'mianyunfei'            => intval($_POST['mianyunfei']),
	'subject_content'         =>  $postedValue,
	'template'            => trim($_POST['template']),
	'point'            => intval($_POST['point']),
	'saleoff'            => intval($_POST['saleoff']),
	'remark'            => trim($_POST['remark']),
	'buycount'            => intval($_POST['buycount']),
	'buyprice'            => intval($_POST['buyprice']),
	'buytype'            => intval($_POST['buytype']),
	'presentgid'            => intval($_POST['presentgid']),
	'pic'             => trim($pic),
	'level1money'            => intval($_POST['level1money']),
	'level2money_min'            => intval($_POST['level2money_min']),
	'level2money_max'            => intval($_POST['level2money_max']),
	'level2gid'            => intval($_POST['level2gid']),
	'level3money'            => intval($_POST['level3money']),
	'level3gid'            => intval($_POST['level3gid']),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}discountsubject` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增活動主題");
		$FUNCTIONS->header_location('admin_discountsubject_list.php');
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
	'min_money'            => intval($_POST['min_money']),
	'min_count'            => intval($_POST['min_count']),
	'mianyunfei'            => intval($_POST['mianyunfei']),
	'template'            => trim($_POST['template']),
	'point'            => intval($_POST['point']),
	'saleoff'            => intval($_POST['saleoff']),
	'remark'            => trim($_POST['remark']),
	'subject_content'         =>  $postedValue,
	'buycount'            => intval($_POST['buycount']),
	'buyprice'            => intval($_POST['buyprice']),
	'buytype'            => intval($_POST['buytype']),
	'presentgid'            => intval($_POST['presentgid']),
	'pic'             => trim($pic),
	'level1money'            => intval($_POST['level1money']),
	'level2money_min'            => intval($_POST['level2money_min']),
	'level2money_max'            => intval($_POST['level2money_max']),
	'level2gid'            => intval($_POST['level2gid']),
	'level3money'            => intval($_POST['level3money']),
	'level3gid'            => intval($_POST['level3gid']),

	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}discountsubject` SET $db_string WHERE dsid=".intval($_POST['subject_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯活動主題");
		$FUNCTIONS->header_location('admin_discountsubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}discountsubject` where dsid=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除活動主題");
		$FUNCTIONS->header_location('admin_discountsubject_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}
if ($_GET['Action']=='delPic' ) {

	$Result =  $DB->query("UPDATE `{$INFO[DBPrefix]}discountsubject`SET pic='' WHERE dsid=".intval($_GET['subject_id']));


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除活動主題圖片");
		$FUNCTIONS->header_location('admin_discountsubject.php?Action=Modi&subject_id='.intval($_GET['subject_id']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}
if ($_POST['act']=='SaveSale' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'dsid'          => intval($_POST['subject_id']),
	'money'          => intval($_POST['money']),
	'saleoff'          => intval($_POST['saleoff']),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}discountsubject_sale` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{

		
	$FUNCTIONS->setLog("新增促銷活動折扣");
		
		echo 1;
	}else{
		echo 0;
	}

}

if ($_POST['act']=='UpdateSale' ) {


	$db_string = $DB->compile_db_update_string( array (
	'money'          => intval($_POST['money']),
	'saleoff'          => intval($_POST['saleoff']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}discountsubject_sale` SET $db_string WHERE sid=".intval($_POST['id']);

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{

		
	$FUNCTIONS->setLog("修改促銷活動折扣");
		
		echo 1;
	}else{
		echo 0;
	}

}
if ($_POST['act']=='DelSale' ) {

	$Array_bid  =  $_POST['id'];

		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}discountsubject_sale`  where sid=".intval($Array_bid));
		//还没有删除相关数据。。。。。。。。。。。。。。


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除促銷活動");
		echo 1;
	}else{
		echo 0;
	}

}

// autosave
if ($_GET['act']=='autosave1' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}discountsubject` where dsid=".intval($_GET['subject_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}discountsubject` SET subject_content = '" . $_POST['FCKeditor1'] . "' WHERE dsid=".intval($_GET['subject_id']);
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
if ($_GET['act']=='autosave2' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}discountsubject` where dsid=".intval($_GET['subject_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}discountsubject` SET remark = '" . $_POST['remark'] . "' WHERE dsid=".intval($_GET['subject_id']);
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