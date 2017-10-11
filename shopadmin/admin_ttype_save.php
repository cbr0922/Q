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
	if(is_array($_POST['type'])){
		$type = implode(",",$_POST['type']);	
	}	
	if(is_array($_POST['payment'])){
		$payment = implode(",",$_POST['payment']);	
	}
	$db_string = $DB->compile_db_insert_string( array (
	'transport_name'            => trim($_POST['transport_name']),
	'transport_price'           => trim($_POST['transport_price']),
	'transport_content'         =>  $postedValue,
	'type'         =>  $type,
	'payment'         =>  $payment,
	'ttype'           => intval($_POST['ttype']),
	'orderby'           => intval($_POST['orderby']),
	'co_name'           => trim($_POST['co_name']),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}transportation` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增配送方式");
		$FUNCTIONS->header_location('admin_ttype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	if(is_array($_POST['type'])){
		$type = implode(",",$_POST['type']);	
	}
	if(is_array($_POST['payment'])){
		$payment = implode(",",$_POST['payment']);	
	}
	$db_string = $DB->compile_db_update_string( array (
	'transport_name'            => trim($_POST['transport_name']),
	'transport_price'           => trim($_POST['transport_price']),
	'transport_content'         =>  $postedValue,
	'type'         =>  $type,
	'payment'         =>  $payment,
	'ttype'           => intval($_POST['ttype']),
	'orderby'           => intval($_POST['orderby']),
	'co_name'           => trim($_POST['co_name']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}transportation` SET $db_string WHERE transport_id=".intval($_POST['Transport_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯配送方式");
		$FUNCTIONS->header_location('admin_ttype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}transportation` where transport_id=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除配送方式");
		$FUNCTIONS->header_location('admin_ttype_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation` where transport_id=".intval($_GET['Transport_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}transportation` SET transport_content = '" . $_POST['FCKeditor1'] . "' WHERE transport_id=".intval($_GET['Transport_id']);
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