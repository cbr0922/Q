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

$Who =  $_SESSION['LOGINADMIN_TYPE']==2 ? 0 : 1 ;

if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'provider_id'              => intval($_SESSION['sa_id']),
	'provider_nfb'             => intval($_POST['provider_nfb']),
	'provider_ntitle'          => trim($_POST['provider_ntitle']),
	'provider_ncontent'        =>  $postedValue,
	'provider_who'             => $Who,
	'provider_nidate'          => time(),


	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}provider_news` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增供應商公告");
		$FUNCTIONS->header_location('provider_ncon_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'provider_id'              => intval($_SESSION['sa_id']),
	'provider_nfb'             => intval($_POST['provider_nfb']),
	'provider_ntitle'          => trim($_POST['provider_ntitle']),
	'provider_ncontent'        => $postedValue,
	'provider_who'             => $Who,
	'provider_nidate'          => time(),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}provider_news` SET $db_string WHERE provider_nid=".intval($_POST['provider_nid']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯供應商公告");
		$FUNCTIONS->header_location('provider_ncon_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}provider_news` where provider_nid=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除供應商公告");
		$FUNCTIONS->header_location('provider_ncon_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



?>