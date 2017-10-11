<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";


if ($_POST['Action']=='Insert' ) {
	if(is_array($_POST['type'])){
		$type = implode(",",$_POST['type']);	
	}
	$db_string = $DB->compile_db_insert_string( array (
	'methodname'       => trim($_POST['methodname']),
	'content'       => trim($_POST['content']),
	'payno'       => trim($_POST['payno']),
	'pid'       => intval($_POST['pid']),
	'ifopen'       => intval($_POST['ifopen']),
	'month'       => intval($_POST['month']),
	'showtype'         =>  $type,
	'orderby'           => intval($_POST['orderby']),
	'ifcanappoint'           => intval($_POST['ifcanappoint']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}paymethod` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);
	$id = mysql_insert_id();
	

	if ($Result_Insert)
	{
		
			$FUNCTIONS->header_location("admin_paymethod_list.php?id=" . intval($_POST['pid']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	if(is_array($_POST['type'])){
		$type = implode(",",$_POST['type']);	
	}
	$db_string = $DB->compile_db_update_string( array (
	'methodname'       => trim($_POST['methodname']),
	'content'       => trim($_POST['content']),
	'payno'       => trim($_POST['payno']),
	'pid'       => intval($_POST['pid']),
	'ifopen'       => intval($_POST['ifopen']),
	'month'       => intval($_POST['month']),
	'showtype'         =>  $type,
	'orderby'           => intval($_POST['orderby']),
	'ifcanappoint'           => intval($_POST['ifcanappoint']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}paymethod` SET $db_string WHERE mid=".intval($_POST['id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{	  		  $FUNCTIONS->header_location("admin_paymethod_list.php?id=" . intval($_POST['pid']));
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}paymethod` where mid=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->header_location("admin_paymethod_list.php?id=" . $_GET['pid']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}paymethod` where mid=".intval($_GET['id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}paymethod` SET content = '" . $_POST['content'] . "' WHERE mid=".intval($_GET['id']);
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