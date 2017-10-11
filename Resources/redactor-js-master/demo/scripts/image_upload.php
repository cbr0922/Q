<?php
 @session_start();
// This is a simplified example, which doesn't cover security of uploaded images. 
// This example just demonstrate the logic behind the process. 
 if ($_SESSION['LOGINADMIN_session_id'] == '' || empty($_SESSION['LOGINADMIN_session_id']))  {
	//echo "is here";

	echo "error";
	exit;
}
 
// files storage folder
$dir = "../../../../UploadFile/UserFiles/";
 
$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
 
if ($_FILES['file']['type'] == 'image/png' 
|| $_FILES['file']['type'] == 'image/jpg' 
|| $_FILES['file']['type'] == 'image/gif' 
|| $_FILES['file']['type'] == 'image/jpeg'
|| $_FILES['file']['type'] == 'image/pjpeg')
{	
    // setting file's mysterious name
    $filename = md5(date('YmdHis')).'.jpg';
    $file = $dir.$filename;

    // copying
	move_uploaded_file($_FILES['file']['tmp_name'], $file);
	include_once ("../../../../Classes/imgthumb.class.php");
	$GDImg = new ThumbHandler();
	$ImgThumb = $dir."/thumb/".$filename;
	$GDImg->setSrcImg($file);
	$GDImg->setDstImg($ImgThumb);
	$GDImg->setMaskWord("");
	$GDImg->setMaskImg("");
	$GDImg->createImg(120,120);
    // displaying file    
	$array = array(
		'url' => '/UploadFile/UserFiles/'.$filename,
		'id' => date('YmdHis')
	);
	
	echo stripslashes(json_encode($array));   
    
}
 
?>