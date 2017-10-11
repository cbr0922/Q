<?php
include_once "Check_Admin.php";
$output_dir = "../UploadFile/GoodPic/";
if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData()
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 		//move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
		$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['myfile']['name'],$_FILES['myfile']['tmp_name'],$ArrayPic,$output_dir,"middle,small");
    	$ret[]= $File_NewName[0];
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		//move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
		$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['myfile']['name'],$_FILES['myfile']['tmp_name'],$ArrayPic,$output_dir,"middle,small");
	  	$ret[]= $File_NewName[0];
	  }

	}
    echo json_encode($ret);
 }
 ?>
