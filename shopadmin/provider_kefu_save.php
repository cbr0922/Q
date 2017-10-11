<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_POST['Action']=='Post' ) {

	$timeforserialnum = time();
	$db_string = $DB->compile_db_insert_string( array (
	'kid'                       => trim($_POST['kid']),
	'username'                  => $KeFu_Pack['Back_System_report'],
	'postdate'                  => $timeforserialnum,
	'k_post_title'              => $KeFu_Pack['Back_System_report'],
	'k_post_con'                => trim($_POST['k_tem_con']),
	'ifcheck'=>0,
	'provider_id'=>intval($_SESSION['sa_id']),
	)      );

	if ($_POST['kefu_tem']!='none') {
		$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu_posts` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}
	//修改kefu表數據
	if (isset($_POST['type_chuli'])) {
		$linshi = $_POST['type_chuli'];

		$number = explode('-',$linshi);

		$Sql_linshi = "select k_type_name from `{$INFO[DBPrefix]}kefu_type` where k_type_id = $number[0];";
		$Query_linshi = $DB->query($Sql_linshi);
		$type_name_linshi = $DB->fetch_array($Query_linshi);
		$Sql_linshi = "select k_chuli_name , ifclose from `{$INFO[DBPrefix]}kefu_chuli` where k_chuli_id = $number[1];";
		$Query_linshi = $DB->query($Sql_linshi);
		$chuli_name_linshi = $DB->fetch_array($Query_linshi);
		$type_chuli_name = $type_name_linshi['k_type_name'].'-'.$chuli_name_linshi['k_chuli_name'];

		$status = 3;
		if ($chuli_name_linshi['ifclose']==1) {
			$status = 2;
		}

		$Sql="UPDATE `{$INFO[DBPrefix]}kefu` set type_chuli='".$_POST['type_chuli']."' , type_chuli_name='".$type_chuli_name."' , postnum = 1 , lastdate = ".$timeforserialnum." , status = ".$status." , iflogin = ".$_POST['iflogin']." where kid = ".$_POST['kid'];
	}else {
		$Sql="UPDATE `{$INFO[DBPrefix]}kefu` set postnum = 1 , lastdate = ".$timeforserialnum." where kid = ".$_POST['kid'];
	}

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setKefuLog($_POST['kid'],"供應商回覆",1);
		$FUNCTIONS->setLog("回覆在線客服");
		$url_locate = 'provider_kefu_list.php?where='.$_POST['where'].'&offset='.$_POST['offset'];
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




?>