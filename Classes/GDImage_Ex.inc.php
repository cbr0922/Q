<?php
/**
 * 本类是完成水印图片的。
 * $Gimage = new Gimage;
 * $Gimage->src_image_name = "./a.jpg";
 * $Gimage->save_image_file = "./c.jpg";
 * $Gimage->wm_image_name = "./b.jpg";
 * $Gimage->save_file="./d.jpg";
 * $Gimage->create('');
 *
 */

Class Gimage{
	var $src_image_name = "";           //输入图片的文件名(必须包含路径名)
	var $jpeg_quality = 100;             //jpeg图片质量
	var $save_image_file = '';          //输出文件名
	var $wm_image_name = "";            //水印图片的文件名(必须包含路径名)
	var $wm_image_pos = 3;             //水印图片放置的位置
	// 0 = middle
	// 1 = top left
	// 2 = top right
	// 3 = bottom right
	// 4 = bottom left
	// 5 = top middle
	// 6 = middle right
	// 7 = bottom middle
	// 8 = middle left
	//other = 3
	var $wm_image_transition = 50;            //水印图片与原图片的融合度 (1=100)

	var $save_file = 0;

	function create($filename)
	{

		//list($The_width, $The_height, $The_type, $The_attr) = getimagesize($filename);

		if ($filename) $this->src_image_name = trim($filename);
        $src_image_type = $this->get_type($this->src_image_name);
		$src_image = $this->createImage($src_image_type,$this->src_image_name);
		if (!$src_image) return;
		 $src_image_w=ImageSX($src_image);
		 $src_image_h=ImageSY($src_image);



		if ($this->wm_image_name){
			$this->wm_image_name = trim($this->wm_image_name);
			$wm_image_type = $this->get_type($this->wm_image_name);
			$wm_image = $this->createImage($wm_image_type,$this->wm_image_name);
			$wm_image_w=ImageSX($wm_image);
			$wm_image_h=ImageSY($wm_image);
			$temp_wm_image = $this->getPos($src_image_w,$src_image_h,$this->wm_image_pos,$wm_image);
			$wm_image_x = $temp_wm_image["dest_x"];
			$wm_image_y = $temp_wm_image["dest_y"];
			imageCopyMerge($src_image, $wm_image,$wm_image_x,$wm_image_y,0,0,$wm_image_w,$wm_image_h,$this->wm_image_transition);
		}


		if ($this->save_file)
		{
			switch ($this->output_type){
				case 'gif':$src_img=ImagePNG($src_image, $this->save_file); break;
				case 'jpeg':$src_img=ImageJPEG($src_image, $this->save_file, $this->jpeg_quality); break;
				case 'png':$src_img=ImagePNG($src_image, $this->save_file); break;
				default:$src_img=ImageJPEG($src_image, $this->save_file, $this->jpeg_quality); break;
			}
		}
		else
		{
			if ($src_image_type = "jpg") $src_image_type="jpeg";
			header("Content-type: image/{$src_image_type}");
			switch ($src_image_type){
				case 'gif':$src_img=ImagePNG($src_image); break;
				case 'jpg':$src_img=ImageJPEG($src_image, "", $this->jpeg_quality);break;
				case 'png':$src_img=ImagePNG($src_image);break;
				default:$src_img=ImageJPEG($src_image, "", $this->jpeg_quality);break;
			}
		}
		imagedestroy($src_image);
	}

	/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/*
	createImage     根据文件名和类型创建图片
	内部函数
	$type:                图片的类型，包括gif,jpg,png
	$img_name:  图片文件名，包括路径名，例如 " ./mouse.jpg"
	*/
	function createImage($type,$img_name){
		if (!$type){
			$type = $this->get_type($img_name);
		}

		switch ($type){
			case 'gif':
				if (function_exists('imagecreatefromgif'))
				$tmp_img=ImageCreateFromGIF($img_name);
				break;
			case 'jpg':
				$tmp_img=ImageCreateFromJPEG($img_name);
				break;
			case 'png':
				$tmp_img=ImageCreateFromPNG($img_name);
				break;
			default:
				$tmp_img=ImageCreateFromString($img_name);
				break;
		}
		return $tmp_img;
	}

	/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	getPos               根据源图像的长、宽，位置代码，水印图片id来生成把水印放置到源图像中的位置
	内部函数

	$sourcefile_width:        源图像的宽
	$sourcefile_height: 原图像的高
	$pos:               位置代码
	$wm_image:           水印图片ID
	*/
	function getPos($sourcefile_width,$sourcefile_height,$pos,$wm_image){
		if  ($wm_image){
			$insertfile_width = ImageSx($wm_image);
			$insertfile_height = ImageSy($wm_image);
		}else {
			/*
			$lineCount = explode("\r\n",$this->wm_text);
			$fontSize = imagettfbbox($this->wm_text_size,$this->wm_text_angle,$this->wm_text_font,$this->wm_text);
			$insertfile_width = $fontSize[2] - $fontSize[0];
			$insertfile_height = count($lineCount)*($fontSize[1] - $fontSize[3]);
			*/
		}

		switch ($pos){
			case 0:
				$dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
				$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
				break;

			case 1:
				$dest_x = 0;
				if ($this->wm_text){
					$dest_y = $insertfile_height;
				}else{
					$dest_y = 0;
				}
				break;

			case 2:
				$dest_x = $sourcefile_width - $insertfile_width;
				if ($this->wm_text){
					$dest_y = $insertfile_height;
				}else{
					$dest_y = 0;
				}
				break;

			case 3:
				$dest_x = $sourcefile_width - $insertfile_width;
				$dest_y = $sourcefile_height - $insertfile_height;
				break;

			case 4:
				$dest_x = 0;
				$dest_y = $sourcefile_height - $insertfile_height;
				break;

			case 5:
				$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
				if ($this->wm_text){
					$dest_y = $insertfile_height;
				}else{
					$dest_y = 0;
				}
				break;

			case 6:
				$dest_x = $sourcefile_width - $insertfile_width;
				$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
				break;

			case 7:
				$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
				$dest_y = $sourcefile_height - $insertfile_height;
				break;

			case 8:
				$dest_x = 0;
				$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
				break;

			default:
				$dest_x = $sourcefile_width - $insertfile_width;
				$dest_y = $sourcefile_height - $insertfile_height;
				break;
		}
		return array("dest_x"=>$dest_x,"dest_y"=>$dest_y);
	}



	/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	get_type                获得图片的格式，包括jpg,png,gif
	内部函数

	$img_name：        图片文件名，可以包括路径名
	*/
	function get_type($img_name)//获取图像文件类型
	{

		//echo  '$img_name:'.$img_name;
		$name_array = explode(".",$img_name);
		if (preg_match("/\.(jpg|jpeg|gif|png)$/", $img_name, $matches))
		{
			$type = strtolower($matches[1]);
		}
		else
		{
			$type = "string";
		}
		return $type;
	}

}
?>
