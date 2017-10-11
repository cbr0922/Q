<?php

include_once "Check_Admin.php";

@header("Content-type: text/html; charset=utf-8");

/**

 *  装载客户服务语言包

 */

include "../language/".$INFO['IS']."/KeFu_Pack.php";


$id = intval($_GET['id']);


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

	'name'       => trim($_POST['name']),

	'classid'    => trim($_POST['classid']),

	'pubtime'    => time(),

	'film'       => trim($_POST['film']),

	'content'    => trim($_POST['content']),

	'type'       => trim($_POST['type']),

	'language'   => trim($_POST['language']),

	'pubdate'    => trim($_POST['pubdate']),

	'videoid'    => $_POST['videoid'],

	)      );





	$Sql="INSERT INTO `{$INFO[DBPrefix]}photo` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";



	$Result_Insert=$DB->query($Sql);

	$id = mysql_insert_id();

	$dir_name = "../UploadFile/photo_img/" . $id;

	if(!file_exists($dir_name))//判断文件夹是否存在

	  {

		  mkdir($dir_name,0777);

		  @chmod($dir_name,0777);

		  

	  }

	  if(!file_exists($dir_name."/images"))//判断文件夹是否存在

		  {

			  mkdir($dir_name."/images",0777);

			  @chmod($dir_name."/images",0777);

		  }


	$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],'',"../UploadFile/photo_img/".$id);

	$Sql = "UPDATE `{$INFO[DBPrefix]}photo` SET `nimg` = '". $Nimg. "' WHERE id=".$id;

	$Result_Insert = $DB->query($Sql);


	if ($Result_Insert)

	{

		

			$FUNCTIONS->header_location("admin_photo_list.php");

	}else{

		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);

	}



}









if ($_POST['Action']=='Update' ) {
	$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],$_POST['Old_Nimg'],"../UploadFile/photo_img/".$_POST['id']);

	$db_string = $DB->compile_db_update_string( array (

	'name'       => trim($_POST['name']),

	'classid'       => trim($_POST['classid']),

	'film'       => trim($_POST['film']),

	'content'       => trim($_POST['content']),

	'type'       => trim($_POST['type']),

	'language'       => trim($_POST['language']),

	'pubdate'       => trim($_POST['pubdate']),

	'videoid'    => $_POST['videoid'],

	'nimg'              => $Nimg,

	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}photo` SET $db_string WHERE id=".intval($_POST['id']);



	$Result_Insert = $DB->query($Sql);



	if ($Result_Insert)

	{	  		  $FUNCTIONS->header_location("admin_photo_list.php");

	}else{

		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);

	}



}







if ($_POST['act']=='Del' ) {



	$Array_cid =  $_POST['cid'];

	$Num_cid  = count($Array_cid);



	for ($i=0;$i<$Num_cid;$i++){

		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}photo` where id=".intval($Array_cid[$i]));

	}





	if ($Result)

	{

		$FUNCTIONS->header_location("admin_photo_list.php");

	}else{

		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);

	}



}

if ($_GET['Action']=='DelPic' ) {
	$id= intval($_GET['id']);
	if ( $id>0 ) {
		$Sql =   " select id,nimg from `{$INFO[DBPrefix]}photo`  where id='".$id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Nimg   =  $Result[nimg];
			$DB->query("update `{$INFO[DBPrefix]}photo` set nimg='' where id='".$id."'");
			@unlink ("../UploadFile/photo_img/".$_GET['id'] . "/" . $Nimg);
		}
	}
	$FUNCTIONS->setLog("刪除文章圖片");
	$FUNCTIONS->header_location("admin_photo.php?Action=Modi&id=".intval($id));
}

?>