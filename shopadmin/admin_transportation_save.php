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

	$image   = $FUNCTIONS->Upload_File($_FILES['image']['name'],$_FILES['image']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	$db_string = $DB->compile_db_insert_string( array (
	'name'            => trim($_POST['name']),
	'type'           => 1,
	'content'         =>  $postedValue,
	'image'         =>  $image,
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}transportation_special` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增特殊配送方式");
		$FUNCTIONS->header_location('admin_transportation_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	if ($_FILES['image']['name']!=""){
	 	$image   = $FUNCTIONS->Upload_File($_FILES['image']['name'],$_FILES['image']['tmp_name'],$_POST['old_image'],"../" . $INFO['good_pic_path']);
	}else{
		$image   = $_POST['old_image'];
	}
	$db_string = $DB->compile_db_update_string( array (
	'name'            => trim($_POST['name']),
	'content'         =>  $postedValue,
	'image'         =>  $image,
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}transportation_special` SET $db_string WHERE trid=".intval($_POST['Transport_id']);

	$Result = $DB->query($Sql);

	if ($Result)
	{
		$FUNCTIONS->setLog("編輯特殊配送方式");
		$FUNCTIONS->header_location('admin_transportation_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}transportation_special` where trid=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除特殊配送方式");
		$FUNCTIONS->header_location('admin_transportation_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation` where transport_id=".intval($_GET['Transport_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}transportation` SET content = '" . $_POST['FCKeditor1'] . "' WHERE transport_id=".intval($_GET['Transport_id']);
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