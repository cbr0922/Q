<?php
include_once "Check_Admin.php";
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
if($_POST['act']=="changeorder"){
	$db_string = $DB->compile_db_update_string( array (
		'bannerorder'             => intval($_POST['order']),
)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}index_banner` SET $db_string WHERE ib_id='".trim($_POST['ib_id']) ."'";
		$Result_Insert=$DB->query($Sql);
		echo 1;
	exit;
}
if($_POST['act']=="changetag"){
	$db_string = $DB->compile_db_update_string( array (
		'tag'             => ($_POST['tag']),
)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}index_banner` SET $db_string WHERE ib_id='".trim($_POST['ib_id']) ."'";
		$Result_Insert=$DB->query($Sql);
		echo 1;
	exit;
}

if($_POST['act']=="del"){
	$Sql = "select * from `{$INFO[DBPrefix]}index_banner` WHERE ib_id='".trim($_POST['ib_id']) ."'";
	$Result_Insert=$DB->query($Sql);
	$Result= $DB->fetch_array($Result_Insert);
	$Sql = "delete from `{$INFO[DBPrefix]}advertising` WHERE adv_tag like 'adv_home".trim($Result['ib_id']) ."_%'";
	$Result_Insert=$DB->query($Sql);
		$Sql = "delete from `{$INFO[DBPrefix]}index_banner` WHERE ib_id='".trim($_POST['ib_id']) ."'";
		$Result_Insert=$DB->query($Sql);
		echo 1;
	exit;
}
if($_POST['adv_type']==21){
	
	if($_POST['inpsaveall']==1){
		$db_string = $DB->compile_db_update_string( array (
		'adv_type'                 => intval($_POST['adv_type']),
		//'start_time'              => $begtime,
		//'end_time'              => $endtime,
		)      );
		$Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_tag='".trim($_POST['tag']) ."'";
		$Result_Insert=$DB->query($Sql);
		if(is_array($_POST['adv_ids'])){
			foreach($_POST['adv_ids'] as $k=>$v){
				if (trim($_POST['begtime' . $v])!=''){
					$date_array = explode("-",trim($_POST['begtime' . $v]));
					$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h' . $v]),intval($_POST['start_i' . $v]),0);
				}
				if (trim($_POST['endtime' . $v])!=''){
					$date_array = explode("-",trim($_POST['endtime' . $v]));
					$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h' . $v]),intval($_POST['end_i' . $v]),0);
				}
				$db_string = $DB->compile_db_update_string( array (	
				'adv_left_url'             => trim($_POST['adv_left_url' . $v]),
				'start_time'              => $begtime,
				'end_time'              => $endtime,
				)      );
				$Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_id='".trim($v) ."'";
				$Result_Insert=$DB->query($Sql);
			}
		}
		
		echo 1;
		exit;
	}
	if (trim($_POST['begtime21'])!=''){
		$date_array = explode("-",trim($_POST['begtime21']));
		$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h21']),intval($_POST['start_i21']),0);
	}
	if (trim($_POST['endtime21'])!=''){
		$date_array = explode("-",trim($_POST['endtime21']));
		$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h21']),intval($_POST['end_i21']),0);
	}
	$ima       = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],'',"../UploadFile/AdvPic/");
	$db_string = $DB->compile_db_insert_string( array (	'adv_title'             => trim($_POST['adv_title']),
	'adv_left_url'             => trim($_POST['adv_left_url']),
	'adv_left_img'             => $ima,
	'adv_type'                 => intval($_POST['adv_type']),
	'ifhome'                 => 1,
	'adv_tag'                  => trim($_POST['tag']),
	'start_time'              => $begtime,
	'end_time'              => $endtime,
	)      );
	 $Sql="INSERT INTO `{$INFO[DBPrefix]}advertising` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	echo 1;
	exit;
}
if($_GET['act']=="autosave"){
	
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='" . $_GET['tag'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if($Num>0){
		$db_string = $DB->compile_db_update_string( array (	
			'adv_content'             => trim($_POST['adv_content']),
		)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_tag='".trim($_GET['tag']) ."'";
		$Result_Insert=$DB->query($Sql);
		$array = array(
			'error' => false,
			'message' => '已自動保存'
		);
	}
	$array = array(
			'error' => true,
			'message' => '自動保存失敗'
		);
	echo stripslashes(json_encode($array));
}
if($_POST['adv_type']==22){
	if (trim($_POST['begtime22'])!=''){
		$date_array = explode("-",trim($_POST['begtime22']));
		$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h22']),intval($_POST['start_i22']),0);
	}
	if (trim($_POST['endtime22'])!=''){
		$date_array = explode("-",trim($_POST['endtime22']));
		$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h22']),intval($_POST['end_i22']),0);
	}
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='" . $_POST['tag'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	 $ima       = $FUNCTIONS->Upload_File($_FILES['ima2']['name'],$_FILES['ima2']['tmp_name'],trim($_POST['oldima2']),"../UploadFile/AdvPic/");
	if($Num==0){

		$db_string = $DB->compile_db_insert_string( array (		'adv_title'             => trim($_POST['adv_title']),
		'adv_left_url'             => trim($_POST['adv_left_url2']),
		'adv_left_img'             => $ima,
		'adv_type'                 => intval($_POST['adv_type']),
		'ifhome'                 => 1,
		'adv_tag'                  => trim($_POST['tag']),
		'start_time'              => $begtime,
		'end_time'              => $endtime,
		)      );
		 $Sql="INSERT INTO `{$INFO[DBPrefix]}advertising` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}else{
		$db_string = $DB->compile_db_update_string( array (		'adv_title'             => trim($_POST['adv_title']),
		'adv_left_url'             => trim($_POST['adv_left_url2']),
		'adv_left_img'             => $ima,
		'adv_type'                 => intval($_POST['adv_type']),
		'start_time'              => $begtime,
		'end_time'              => $endtime,
		)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_tag='".trim($_POST['tag']) ."'";
		$Result_Insert=$DB->query($Sql);
	}
	echo 1;
	exit;
}
if($_POST['adv_type']==23){
	if (trim($_POST['begtime23'])!=''){
		$date_array = explode("-",trim($_POST['begtime23']));
		$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h23']),intval($_POST['start_i23']),0);
	}
	if (trim($_POST['endtime23'])!=''){
		$date_array = explode("-",trim($_POST['endtime23']));
		$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h23']),intval($_POST['end_i23']),0);
	}
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='" . $_POST['tag'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if($Num==0){

		$db_string = $DB->compile_db_insert_string( array (		'adv_title'             => trim($_POST['adv_title']),
		'adv_content'             => trim($_POST['adv_content']),
		'adv_type'                 => intval($_POST['adv_type']),
		'ifhome'                 => 1,
		'adv_tag'                  => trim($_POST['tag']),
		'start_time'              => $begtime,
		'end_time'              => $endtime,
		)      );
		 $Sql="INSERT INTO `{$INFO[DBPrefix]}advertising` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}else{
		$db_string = $DB->compile_db_update_string( array (		'adv_title'             => trim($_POST['adv_title']),
		'adv_content'             => trim($_POST['adv_content']),
		'adv_type'                 => intval($_POST['adv_type']),
		'start_time'              => $begtime,
		'end_time'              => $endtime,
		)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_tag='".trim($_POST['tag']) ."'";
		$Result_Insert=$DB->query($Sql);
	}
	echo 1;
	exit;
}
if($_POST['adv_type']==24){
	if (trim($_POST['begtime24'])!=''){
		$date_array = explode("-",trim($_POST['begtime24']));
		$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h24']),intval($_POST['start_i24']),0);
	}
	if (trim($_POST['endtime24'])!=''){
		$date_array = explode("-",trim($_POST['endtime24']));
		$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h24']),intval($_POST['end_i24']),0);
	}
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='" . $_POST['tag'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if($Num==0){

		$db_string = $DB->compile_db_insert_string( array (		'adv_title'             => trim($_POST['adv_title']),
		'adv_content'             => trim($_POST['adv_content4']),
		'adv_type'                 => intval($_POST['adv_type']),
		'ifhome'                 => 1,
		'adv_tag'                  => trim($_POST['tag']),
		'start_time'              => $begtime,
		'end_time'              => $endtime,
		)      );
		 $Sql="INSERT INTO `{$INFO[DBPrefix]}advertising` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}else{
		$db_string = $DB->compile_db_update_string( array (		'adv_title'             => trim($_POST['adv_title']),
		'adv_content'             => trim($_POST['adv_content4']),
		'adv_type'                 => intval($_POST['adv_type']),
		'start_time'              => $begtime,
		'end_time'              => $endtime,
		)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_tag='".trim($_POST['tag']) ."'";
		$Result_Insert=$DB->query($Sql);
	}
	echo 1;
	exit;
}
if($_GET['action']=="del"){
	$Result =  $DB->query("delete from `{$INFO[DBPrefix]}advertising` where adv_id=".intval($_GET['adv_id']));
	echo 1;
	exit;
}
if($_GET['action']=="delgrid"){
	$Result =  $DB->query("delete from `{$INFO[DBPrefix]}advertising` where adv_tag='".trim($_GET['tag'])."'");
	echo 1;
	exit;
}
if($_GET['action']=="updateadv"){
	if (trim($_GET['begtime'])!=''){
		$date_array = explode("-",trim($_GET['begtime']));
		$begtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_GET['start_h']),intval($_GET['start_i']),0);
	}
	if (trim($_GET['endtime'])!=''){
		$date_array = explode("-",trim($_GET['endtime']));
		$endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_GET['end_h']),intval($_GET['end_i']),0);
	}
	$db_string = $DB->compile_db_update_string( 
		array (	
		'adv_left_url'             => trim($_GET['adv_left_url']),
		'start_time'              => $begtime,
		'end_time'              => $endtime,
		)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}advertising` SET $db_string WHERE adv_id='".trim($_GET['adv_id']) ."'";
		$Result_Insert=$DB->query($Sql);
	echo 1;
	exit;
}
?>
