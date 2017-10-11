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

	$img   = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],'',"../" . $INFO['logo_pic_path']);

	$Spath = "../".$INFO['logo_pic_path'] ."/";
	include (Classes."/imgthumb.class.php");
	$GDImg = new ThumbHandler();
	$GDImg->setSrcImg($Spath .$img);
	$GDImg->setDstImg($Spath."small/".$img);
	$GDImg->createImg(intval($INFO['logo_small']),intval($INFO['logo_small']));
	
	if(is_array($_POST['classid']))
		$class_id = implode(",",$_POST['classid']);
	else
		$class_id = $_POST['classid'];

	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],'',"../" . $INFO['logo_pic_path']);
	$db_string = $DB->compile_db_insert_string( array (
	'brandname'               => trim($_POST['brandname']),
	'brandname_en'               => trim($_POST['brandname_en']),
	'brandcontent'            => $postedValue,
	'logopic'                 => trim($img),
	'viewcount'                 => intval($_POST['viewcount']),
	'meta_des'               => trim($_POST['meta_des']),
	'meta_key'               => trim($_POST['meta_key']),
	'classid'                 => $class_id,
	'brandpic'                 => trim($pic),
	'smalllogo'                 => "small/".$img,
	'orderby'              => intval($_POST['orderby']),
	'content'               => trim($_POST['content']),
	'language'        => $_POST['language'],
	'bdiffb'        => intval($_POST['bdiffb']),
	'title1'        => $_POST['title1'],
	'title2'        => $_POST['title2'],
	'ratio'        => $_POST['ratio'],	
	'goodlist'        => $_POST['goodlist'],
	'ifshowgoods'        => $_POST['ifshowgoods'],
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}brand` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增商品品牌");
		$FUNCTIONS->header_location('admin_brand_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],$_POST['old_brandpic'],"../" . $INFO['logo_pic_path']);
	$img   = $FUNCTIONS->Upload_File($_FILES['ima']['name'],$_FILES['ima']['tmp_name'],$_POST['old_pic'],"../" . $INFO['logo_pic_path']);
if ($img!=""){
	$Spath = "../".$INFO['logo_pic_path'] ."/";
	include (Classes."/imgthumb.class.php");
	$GDImg = new ThumbHandler();

	$GDImg->setSrcImg($Spath .$img);
	$GDImg->setDstImg($Spath."small/".$img);
	$GDImg->createImg(intval($INFO['logo_small']),intval($INFO['logo_small']));
}
	if(is_array($_POST['classid']))
		$class_id = implode(",",$_POST['classid']);
	else
		$class_id = $_POST['classid'];

	$db_string = $DB->compile_db_update_string( array (
	'brandname'               => trim($_POST['brandname']),
	'brandname_en'               => trim($_POST['brandname_en']),
	'brandcontent'            => $postedValue,
	'logopic'                 => trim($img),
	'viewcount'                 => intval($_POST['viewcount']),
	'meta_des'               => trim($_POST['meta_des']),
	'meta_key'               => trim($_POST['meta_key']),
	'classid'                 => $class_id,
	'brandpic'                 => trim($pic),
	'smalllogo'                 => "small/".$img,
	'orderby'              => intval($_POST['orderby']),
	'content'               => trim($_POST['content']),
	'language'        => $_POST['language'],
	'bdiffb'        => intval($_POST['bdiffb']),
	'title1'        => $_POST['title1'],
	'title2'        => $_POST['title2'],
	'ratio'        => $_POST['ratio'],	
	'goodlist'        => $_POST['goodlist'],
	'ifshowgoods'        => $_POST['ifshowgoods'],
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}brand` SET $db_string WHERE brand_id=".intval($_POST['brand_id']);
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯商品品牌");
		$FUNCTIONS->header_location('admin_brand_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
if ($_POST['Action']=='updateratio' ) {
	
	
	$db_string = $DB->compile_db_update_string( array (
	
		'ratio'        => $_POST['ratio'],	
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}brand` SET $db_string WHERE brand_id=".intval($_POST['brand_id']);
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯商品品牌權重");
		echo 1;
	}else{
		echo 0;
	}
exit;
}


if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Sql =   " select logopic,smalllogo,brandpic from `{$INFO[DBPrefix]}brand`  where brand_id='".intval($Array_bid[$i])."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result_img =  $DB->fetch_array($Query);
			@unlink ("../"  . $INFO['logo_pic_path']. "/".$Result_img[logopic]);
			@unlink ("../"  . $INFO['logo_pic_path']. "/".$Result_img[smalllogo]);
			@unlink ("../" . $INFO['logo_pic_path'] . "/".$Result_img[brandpic]);
		}

		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}brand`  where brand_id=".intval($Array_bid[$i]));
	}

	$FUNCTIONS->setLog("刪除商品品牌");
	$FUNCTIONS->header_location('admin_brand_list.php');

}



if ($_GET['Action']=='DelPic' && isset($_GET['brand_id'])) {

	$brand_id  = intval($_GET['brand_id']);

	if ( $brand_id >0 ) {
		$Sql =   " select logopic,smalllogo from `{$INFO[DBPrefix]}brand`  where brand_id='".$brand_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Img    =  $Result[logopic];
			$Pic    =  $Result[smalllogo];
			$DB->query("update `{$INFO[DBPrefix]}brand` set  logopic='',smalllogo='' where brand_id='".$brand_id."'");
			@unlink ("../"  . $INFO['logo_pic_path']. "/".$Img);
			@unlink ("../"  . $INFO['logo_pic_path']. "/".$Pic);
		}
		$FUNCTIONS->setLog("刪除商品品牌圖片");
		$FUNCTIONS->header_location("admin_brand.php?Action=Modi&brand_id=".intval($brand_id));
	}else{
		$FUNCTIONS->sorry_back("back","");
	}

}

if ($_GET['Action']=='DelBrandPic' && isset($_GET['brand_id'])) {

	$brand_id  = intval($_GET['brand_id']);

	if ( $brand_id >0 ) {
		$Sql =   " select logopic from `{$INFO[DBPrefix]}brand`  where brand_id='".$brand_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Img    =  $Result[brandpic];
			$DB->query("update `{$INFO[DBPrefix]}brand` set  brandpic='' where brand_id='".$brand_id."'");
			@unlink ("../" . $INFO['logo_pic_path'] . "/".$Img);
		}
		$FUNCTIONS->setLog("刪除商品品牌圖片");
		$FUNCTIONS->header_location("admin_brand.php?Action=Modi&brand_id=".intval($brand_id));
	}else{
		$FUNCTIONS->sorry_back("back","");
	}

}

// autosave
if ($_GET['act']=='autosave1' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($_GET['brand_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}brand` SET brandcontent = '" . $_POST['FCKeditor1'] . "' WHERE brand_id=".intval($_GET['brand_id']);
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
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($_GET['brand_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}brand` SET content = '" . $_POST['content'] . "' WHERE brand_id=".intval($_GET['brand_id']);
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
