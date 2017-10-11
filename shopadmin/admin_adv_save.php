<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
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

	if (trim($_POST['begtime'])!=''){
		$date_array = explode("-",trim($_POST['begtime']));
		$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h']),intval($_POST['start_i']),0);
	}
	if (trim($_POST['endtime'])!=''){
		$date_array = explode("-",trim($_POST['endtime']));
		$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h']),intval($_POST['end_i']),0);
	}
	$ima       = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],'',"../".$INFO['advs_pic_path']);
	$ima1      = $FUNCTIONS->Upload_File($_FILES['ima1']['name'],$_FILES['ima1']['tmp_name'],'',"../".$INFO['advs_pic_path']);

	$db_string = $DB->compile_db_insert_string( array (
	'adv_title'                => trim($_POST['adv_title']),
	'adv_width'                => intval($_POST['adv_width']),
	'adv_height'               => intval($_POST['adv_height']),
	'title_color'              => trim($_POST['title_color']),
	'adv_left_url'             => trim($_POST['adv_left_url']),
	'adv_right_url'            => trim($_POST['adv_right_url']),
	'adv_tag'                  => trim($_POST['adv_tag']),
	'adv_type'                 => intval($_POST['adv_type']),
	'point_num'                => intval($_POST['point_num']),
	'adv_content'              => $postedValue,
	'adv_banner'              => $_POST['adv_banner'],
	'adv_left_img'             => $ima,
	'adv_right_img'            => $ima1,
	'adv_display'              => intval($_POST['adv_display']),
	'company'              => trim($_POST['company']),
	'start_time'              => $begtime,
	'end_time'              => $endtime,
	'orderby'                => intval($_POST['orderby']),
	'ifallclass'                => intval($_POST['ifallclass']),
	'position'                  => trim($_POST['position']),
	'language'        => $_POST['language'],
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}advertising` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增廣告");
		$FUNCTIONS->header_location('admin_adv_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	if (trim($_POST['begtime'])!=''){
		$date_array = explode("-",trim($_POST['begtime']));
		$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h']),intval($_POST['start_i']),0);
	}
	if (trim($_POST['endtime'])!=''){
		$date_array = explode("-",trim($_POST['endtime']));
		$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h']),intval($_POST['end_i']),0);
	}
	$ima       = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],$_POST['adv_left_img_old'],"../".$INFO['advs_pic_path']);
	$ima1      = $FUNCTIONS->Upload_File($_FILES['ima1']['name'],$_FILES['ima1']['tmp_name'],$_POST['adv_right_img_old'],"../".$INFO['advs_pic_path']);
	$db_string = $DB->compile_db_update_string( array (
	'adv_title'                => trim($_POST['adv_title']),
	'adv_width'                => intval($_POST['adv_width']),
	'adv_height'               => intval($_POST['adv_height']),
	'title_color'              => trim($_POST['title_color']),
	'adv_left_url'             => trim($_POST['adv_left_url']),
	'adv_right_url'            => trim($_POST['adv_right_url']),
	'adv_type'                 => intval($_POST['adv_type']),
	'adv_tag'                  => trim($_POST['adv_tag']),
	'point_num'                => intval($_POST['point_num']),
	'adv_content'              => $postedValue,
	'adv_banner'              => $_POST['adv_banner'],
	'adv_left_img'             => $ima,
	'adv_right_img'            => $ima1,
	'adv_display'              => intval($_POST['adv_display']),
	'company'              => trim($_POST['company']),
	'start_time'              => $begtime,
	'end_time'              => $endtime,
	'orderby'                => intval($_POST['orderby']),
	'ifallclass'                => intval($_POST['ifallclass']),
	'position'                  => trim($_POST['position']),
	'language'        => $_POST['language'],
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_id=".intval($_POST['Adv_id']);
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯廣告");
		$FUNCTIONS->header_location('admin_adv_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}advertising` where adv_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除廣告");

	$FUNCTIONS->header_location('admin_adv_list.php');

}


if ($_GET['Action']=='DelPic' &&  isset($_GET['Type'])) {
	$Type    = trim($_GET['Type']);
	$Adv_id  = intval($_GET['adv_id']);

	if ( $Adv_id >0 ) {
		$Sql =   " select adv_left_img,adv_right_img from `{$INFO[DBPrefix]}advertising`  where adv_id='".$Adv_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);

		switch ($Type){
			case "LeftPic":
				$Fieldname = "adv_left_img";
				break;
			case "RightPic":
				$Fieldname = "adv_right_img";
				break;
		}

		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Img   =  $Result[$Fieldname];
			$DB->query("update `{$INFO[DBPrefix]}advertising` set  $Fieldname='' where adv_id='".$Adv_id."'");
			@unlink ("../LinkPic/".$Img);
			$FUNCTIONS->setLog("刪除廣告圖片");
		}

	}
	$FUNCTIONS->header_location("admin_adv.php?Action=Modi&Adv_id=".intval($Adv_id));
}

// autosave
if ($_GET['act']=='autosave1' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_id=".intval($_GET['Adv_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET adv_content = '" . $_POST['FCKeditor1'] . "' WHERE adv_id=".intval($_GET['Adv_id']);
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
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_id=".intval($_GET['Adv_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET adv_banner = '" . $_POST['adv_banner'] . "' WHERE adv_id=".intval($_GET['Adv_id']);
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
