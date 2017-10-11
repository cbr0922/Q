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
	'pay_name'            => trim($_POST['pay_name']),
	'pay_state'           => intval($_POST['pay_state']),
	'pay_content'         =>  $postedValue,
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}pay_type` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";


	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增付款方式");
		$FUNCTIONS->header_location('admin_ptype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'pay_name'            => trim($_POST['pay_name']),
	'pay_state'           => intval($_POST['pay_state']),
	'pay_content'         =>  $postedValue,
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}pay_type` SET $db_string WHERE pay_id=".intval($_POST['pay_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯付款方式");
		$FUNCTIONS->header_location('admin_ptype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		//if (intval($Array_bid[$i])!=4){  //这里是采用默认指定ID是不能被删除的方式来处理的
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}pay_type` where pay_id=".intval($Array_bid[$i]));
		//}
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除付款方式");
		$FUNCTIONS->header_location('admin_ptype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}

?>