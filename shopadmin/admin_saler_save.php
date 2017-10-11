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
	//$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],'',"../newspic");
	$db_string = $DB->compile_db_insert_string( array (
	'name'             => trim($_POST['name']),
	'password'           => trim($_POST['password']),
	'tel'    => trim($_POST['tel']),
	'addr'              => trim($_POST['addr']),
	'email'              => trim($_POST['email']),
	'bankuser'             => trim($_POST['bankuser']),
	'bankname'            => trim($_POST['bankname']),
	'bank'               => trim($_POST['bank']),
	'login'          =>  trim($_POST['login']),
	'ifpub'          =>  intval($_POST['ifpub']),
	'salerset'          =>  intval($_POST['salerset']),
	'openpwd'          =>  trim($_POST['openpwd']),
	'userlevel'          =>  intval($_POST['userlevel']),
	'givebouns'          =>  intval($_POST['givebouns']),
	'company'          =>  trim($_POST['company']),
	'partment'          =>  trim($_POST['partment']),
	'contact'          =>  trim($_POST['contact']),
	'pubtime'            => time(),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}saler` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增經銷商");
		$FUNCTIONS->header_location('admin_saler_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	//$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],$_POST['Nimg'],"../newspic");
	$db_string = $DB->compile_db_update_string( array (
	'name'             => trim($_POST['name']),
	'password'           => trim($_POST['password']),
	'tel'    => trim($_POST['tel']),
	'addr'              => trim($_POST['addr']),
	'email'              => trim($_POST['email']),
	'bankuser'             => trim($_POST['bankuser']),
	'bankname'            => trim($_POST['bankname']),
	'bank'               => trim($_POST['bank']),
	'login'          =>  trim($_POST['login']),
	'salerset'          =>  intval($_POST['salerset']),
	'ifpub'          =>  intval($_POST['ifpub']),
	'openpwd'          =>  trim($_POST['openpwd']),
	'userlevel'          =>  intval($_POST['userlevel']),
	'givebouns'          =>  intval($_POST['givebouns']),
	'company'          =>  trim($_POST['company']),
	'partment'          =>  trim($_POST['partment']),
	'contact'          =>  trim($_POST['contact']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}saler` SET $db_string WHERE id=".intval($_POST['id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯經銷商");
		$FUNCTIONS->header_location('admin_saler_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}saler` where id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除銷商");
		$FUNCTIONS->header_location('admin_saler_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

?>