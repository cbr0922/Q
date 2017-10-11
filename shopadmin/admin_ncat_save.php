<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$template = trim($_POST['template']);

switch ($template) {
	case 'article_classindex2.html':
		$templatetype = 2;
		break;
	case 'article_classindex3.html':
		$templatetype = 3;
		break;
	case 'article_classindex4.html':
		$templatetype = 4;
		break;
	case 'article_classindex5.html':
		$templatetype = 5;
		break;
	case 'article_classindex6.html':
		$templatetype = 6;
		break;
	case 'article_classindex7.html':
		$templatetype = 7;
		break;
	case 'article_classindex8.html':
		$templatetype = 8;
		break;
	case 'article_classindex9.html':
		$templatetype = 9;
		break;
	default:
		$templatetype = 1;
		break;
}

if ($_POST['Action']=='Insert' ) {

	$Ncimg   = $FUNCTIONS->Upload_File($_FILES['ncimg']['name'],$_FILES['ncimg']['tmp_name'],'',"../" . $INFO['news_pic_path']);

	$db_string = $DB->compile_db_insert_string( array (
	'top_id'            => intval($_POST['top_id']),
	'ncname'            => trim($_POST['ncname']),
	'ncatord'           => intval($_POST['ncatord']),
	'ncatiffb'          => intval($_POST['ncatiffb']),
	'ncimg'             => trim($Ncimg),
	'meta_des'               => trim($_POST['meta_des']),
	'meta_key'               => trim($_POST['meta_key']),
	'classid'                 => intval($_POST['classid']),
	'templatetype'                 => $templatetype,
	'template'                 => $template,
	'ifcomment'                 => intval($_POST['ifcomment']),
	'language'        => $_POST['language'],
	'path'        => $_POST['path'],
	'brand_id'        => $_POST['brand_id'],
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}nclass` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增文章分類");
		$FUNCTIONS->header_location('admin_create_newsclassshow.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	/*
	$Egg  = $FUNCTIONS->father_nclass_topid(intval($_POST['top_id']));
	$Egg  = explode(" ",$Egg);
	/*
	print_r ($Egg);
	exit;

	$N_Egg= count($Egg);
	for ($i=0;$i<$N_Egg;$i++){
	if ($Egg[$i]==intval($_POST['ncid'])){
	$FUNCTIONS->sorry_back('back',$INFO['admin_pcat_noself']);
	exit;
	}
	}
	*/
	$Ncimg   = $FUNCTIONS->Upload_File($_FILES['ncimg']['name'],$_FILES['ncimg']['tmp_name'],$_POST['Old_Ncimg'],"../" . $INFO['news_pic_path']);
	$db_string = $DB->compile_db_update_string( array (
	'top_id'            => intval($_POST['top_id']),
	'ncname'            => trim($_POST['ncname']),
	'ncatord'           => intval($_POST['ncatord']),
	'ncatiffb'          => intval($_POST['ncatiffb']),
	'ncimg'             => trim($Ncimg),
	'meta_des'          => trim($_POST['meta_des']),
	'meta_key'          => trim($_POST['meta_key']),
	'classid'                 => intval($_POST['classid']),
	'templatetype'                 => $templatetype,
	'template'                 => $template,
	'ifcomment'                 => intval($_POST['ifcomment']),
	'language'        => $_POST['language'],
	'path'        => $_POST['path'],
		'brand_id'        => $_POST['brand_id'],
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}nclass` SET $db_string WHERE ncid=".intval($_POST['ncid']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯文章分類");
		$FUNCTIONS->header_location('admin_create_newsclassshow.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {
	$Array_nid =  $_POST['ncid'];
	$Num_nid  = count($Array_nid);

	for ($i=0;$i<$Num_nid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}nclass` where ncid=".intval($Array_nid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}news` where top_id=".intval($Array_nid[$i]));
	}

	if ($Result)
	{
		$FUNCTIONS->setLog("刪除文章分類");
		$FUNCTIONS->header_location('admin_create_newsclassshow.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

if ($_GET['Action']=='delPic' && intval($_GET['id'])>0  && $_GET['pic']!='' ) {
	$Sql = "select ncimg from  `{$INFO[DBPrefix]}nclass`  where ncid=".intval($_GET['id'])." limit 0,1";
	$Query =  $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		@unlink("../".$INFO['news_pic_path']."/".$_GET['pic']);
	}

	$DB->query("update `{$INFO[DBPrefix]}nclass` set  ncimg='' where ncid=".intval($_GET['id'])." limit 1");
	$FUNCTIONS->setLog("刪除文章分類圖片");
	$FUNCTIONS->header_location('admin_ncat.php?bid=' . $_GET['id'] . '&Action=Modi' );
}
?>
