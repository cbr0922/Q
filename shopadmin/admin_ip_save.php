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
	'ip'       => trim($_POST['ip']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}adminip` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增鎖定IP");
			$FUNCTIONS->header_location("admin_ip_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'ip'       => trim($_POST['ip']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}adminip` SET $db_string WHERE id=".intval($_POST['id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{	  
	$FUNCTIONS->setLog("編輯鎖定IP");
	$FUNCTIONS->header_location("admin_ip_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}adminip` where id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除鎖定IP");
		$FUNCTIONS->header_location("admin_ip_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

?>