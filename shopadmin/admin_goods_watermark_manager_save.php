<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


if ($_POST['Action']=='Modi' ) {
	if($_POST[water_type]!=""){
		$WatermarkPicfile   = $FUNCTIONS->Upload_File($_FILES['WatermarkPicfile']['name'],$_FILES['WatermarkPicfile']['tmp_name'],$_POST['Old_WatermarkPicfile'],"../UploadFile/UserFiles");
	
		
		include (Classes."/imgthumb.class.php");
		$GDImg = new ThumbHandler();
		$path = "../UploadFile/UserFiles/";
		$filename_array = explode(".",$WatermarkPicfile);
		$GDImg->setSrcImg($path . $WatermarkPicfile);
		$GDImg->setDstImg("../UploadFile/UserFiles/".$filename_array[0]."_big." . $filename_array[1]);
		$GDImg->createImg(intval($_POST[Watermark_pic_big]),intval($_POST[Watermark_pic_big]));
		$GDImg->setSrcImg($path . $WatermarkPicfile);
		$GDImg->setDstImg("../UploadFile/UserFiles/".$filename_array[0]."_middle." . $filename_array[1]);
		$GDImg->createImg(intval($_POST[Watermark_pic_middle]),intval($_POST[Watermark_pic_middle]));
		$GDImg->setSrcImg($path . $WatermarkPicfile);
		$GDImg->setDstImg("../UploadFile/UserFiles/".$filename_array[0]."_small." . $filename_array[1]);
		$GDImg->createImg(intval($_POST[Watermark_pic_small]),intval($_POST[Watermark_pic_small]));
	}
	
	$file_string = "";
	$file_string .= "<?php\n\n\n  \$INFO[SystemWaterMark] ='".$_POST[water_type]."' ;\n";
	$file_string .= "      \$INFO[WatermarkPicfile] ='".$WatermarkPicfile."' ;\n";
	$file_string .= "      \$INFO[WatermarkPicfile_middle] ='".$filename_array[0]."_middle." . $filename_array[1]."' ;\n";
	$file_string .= "      \$INFO[WatermarkPicfile_big] ='".$filename_array[0]."_big." . $filename_array[1]."' ;\n";
	$file_string .= "      \$INFO[WatermarkPicfile_small] ='".$filename_array[0]."_small." . $filename_array[1]."' ;\n";
	$file_string .= "      \$INFO[watermark_transition] ='".intval($_POST[watermark_transition])."' ;\n";
	$file_string .= "      \$INFO[WatermarkWhere] ='".intval($_POST[WatermarkWhere])."'; \n";
	$file_string .= "      \$INFO[Watermark_pic_small] ='".intval($_POST[Watermark_pic_small])."'; \n";
	$file_string .= "      \$INFO[Watermark_pic_middle] ='".intval($_POST[Watermark_pic_middle])."'; \n";
	$file_string .= "      \$INFO[Watermark_pic_big] ='".intval($_POST[Watermark_pic_big])."'; \n";
	$file_string .= "\n?>";

	if ( $fh = fopen( "../".ConfigDir.'/cache/setwatermark.php', 'wb' ) )
	{
		fputs ($fh, $file_string, strlen($file_string) );
		fclose($fh);
		@chmod ("../".ConfigDir.'/setwatermark.php',0777);
	}
	
	$FUNCTIONS->sorry_back("admin_goods_watermark_manager.php","");

}




?>