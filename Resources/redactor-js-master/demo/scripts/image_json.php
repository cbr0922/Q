<?php
@session_start();
 if ($_SESSION['LOGINADMIN_session_id'] == '' || empty($_SESSION['LOGINADMIN_session_id']))  {
	echo "error";
	exit;
}
$dir = "../../../../UploadFile/UserFiles/thumb/";
$folder = "/UploadFile/UserFiles/";
$handle=opendir($dir);
$file_array = array();
$i = 0;
while ($file = readdir($handle)) {
   $filel=strtolower($file);
   if(substr($filel,-3,3)=="gif" || substr($filel,-3,3)=="jpg" || substr($filel,-3,3)=="png" || substr($filel,-3,3)=="bmp")
   {
	  $file_array[$i]['thumb'] = $folder ."/thumb/". $file;
	  $file_array[$i]['url'] = $folder . $file;
	  $file_array[$i]['title'] = "";
	  $file_array[$i]['id'] = $i+1; 
	  $i++;
   }
}
echo stripslashes(json_encode($file_array));   
?>