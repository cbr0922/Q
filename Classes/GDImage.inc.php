<?php

//====================================================

// FileName:GDImage.inc.php

// Summary: 图片处理程序

// Author: tyler.wu(大白菜芯)

// CreateTime: 2005-1-12

// LastModifed:2005-1-12

// copyright (c)2005 php_netproject@yahoo.com.cn

//====================================================



class GDImage

{

	var $sourcePath;         //图片存储路径

	var $galleryPath;        //图片缩略图存储路径

	var $toFile     = false; //是否生成文件

	var $fontName;           //使用的TTF字体名称

	var $maxWidth   = 500;   //图片最大宽度

	var $maxHeight  = 600;   //图片最大高度

	var $srcW       = "";    //图片最大宽度

	var $srcH       = "";    //图片最大高度





	//==========================================

	// 函数: GDImage($sourcePath ,$galleryPath, $fontPath)

	// 功能: constructor

	// 参数: $sourcePath 图片源路径(包括最后一个"/")

	// 参数: $galleryPath 生成图片的路径

	// 参数: $fontPath 字体路径

	//==========================================

	function GDImage($sourcePath, $galleryPath, $fontPath)

	{

		global $INFO;

		$this->sourcePath = $sourcePath;

		$this->galleryPath = $galleryPath;

		//$this->fontName = $fontPath . "04B_08__.TTF";

		//$this->fontName = $fontPath."/".$INFO['gd_font'];

		$this->fontName = $fontPath . "verdana.ttf";







	}



	//==========================================

	// 函数: makeThumb($sourFile,$width=128,$height=128)

	// 功能: 生成缩略图(输出到浏览器)

	// 参数: $sourFile 图片源文件

	// 参数: $width 生成缩略图的宽度

	// 参数: $height 生成缩略图的高度

	// 返回: 0 失败 成功时返回生成的图片路径

	//==========================================

	function makeThumb($sourFile,$_EndName,$width=128,$height=128)

	{

		$imageInfo = $this->getInfo($sourFile);

		$sourFile = $this->sourcePath . $sourFile;

		//$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")) . "_thumb.jpg";

        $newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")).$_EndName.".jpg";

		switch ($imageInfo["type"])

		{

			case 1: //gif

			$img = imagecreatefromgif($sourFile);

			break;

			case 2: //jpg

			$img = $this->LoadJpeg($sourFile);

			break;

			case 3: //png

			$img = imagecreatefrompng($sourFile);

			break;

			default:

				return 0;

				break;

		}



		if (!$img)

		return 0;



		 $width = ($width > $imageInfo["width"]) ? $imageInfo["width"] : $width;

		//echo "||";

		 $height = ($height > $imageInfo["height"]) ? $imageInfo["height"] : $height;

		

		$srcW = $imageInfo["width"];

		$srcH = $imageInfo["height"];

		

		if ($srcW > $srcH)

		$height = round($srcH * ($width / $srcW));

		else

		$width = round($srcW * ($height / $srcH));

		//echo $height . "|" . $width;

		//*

		if ($height==0){

			$height = round($srcH * $width / $srcW);

		}

		if (function_exists("imagecreatetruecolor")) //GD2.0.1

		{

			$new = imagecreatetruecolor($width, $height);

			imagealphablending($new,false);

			imagesavealpha($new,true);

			ImageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);



		}

		else

		{

			$new = imagecreate($width, $height);

			ImageCopyResized($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);

		}

		//*/

		if ($this->toFile)

		{

			if (file_exists($this->galleryPath . $newName))

			unlink($this->galleryPath . $newName);

			if($imageInfo["type"]==3)

				imagepng($new, $this->galleryPath . $newName);

			else

			ImageJPEG($new, $this->galleryPath . $newName,100);

			//return $this->galleryPath . $newName;

			return $newName;

		}

		else

		{

			if($imageInfo["type"]==3)

				imagepng($new);

			else

				ImageJPEG($new);

		}

		ImageDestroy($new);

		ImageDestroy($img);



	}

	//==========================================

	// 函数: waterMark($sourFile, $text , $_EndName)

	// 功能: 给图片加水印

	// 参数: $sourFile 图片文件名

	// 参数: $text 文本数组(包含二个字符串)

	// 返回: 1 成功 成功时返回生成的图片路径

	//==========================================

	function waterMark($sourFile, $text , $_EndName)

	{

		$imageInfo = $this->getInfo($sourFile);

		$sourFile = $this->sourcePath . $sourFile;

		$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")).$_EndName.".jpg";

		switch ($imageInfo["type"])

		{

			case 1: //gif

			$img = imagecreatefromgif($sourFile);

			break;

			case 2: //jpg

			$img = $this->LoadJpeg($sourFile);

			break;

			case 3: //png

			$img = imagecreatefrompng($sourFile);

			break;

			default:

				return 0;

				break;

		}

		if (!$img)

		return 0;



		$width = ($this->maxWidth > $imageInfo["width"]) ? $imageInfo["width"] : $this->maxWidth;

		$height = ($this->maxHeight > $imageInfo["height"]) ? $imageInfo["height"] : $this->maxHeight;

		$srcW = $imageInfo["width"];

		$srcH = $imageInfo["height"];



		if ($srcW * $width > $srcH * $height){

			$height = round($srcH * $width / $srcW);

		} else{

			$width = round($srcW * $height / $srcH);

		}

		

		/**

	     * 这里是判断是否有外部的输入，如果外部输入了宽度，那么系统将计算一个合适的宽度和高度来生成相应的图片

	     */

		if ( $this->srcW != "" ){

			$width  = $this->srcW;

			$Per    = $width / $srcW;

			$height = $srcH * $Per ;

		}



			

		if (function_exists("imagecreatetruecolor")) //GD2.0.1

		{

			$new = imagecreatetruecolor($width, $height);

			imagealphablending($new,false);

			imagesavealpha($new,true);

			ImageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $srcW, $srcH);

		}

		else

		{

			$new = imagecreate($width, $height);

			ImageCopyResized($new, $img, 0, 0, 0, 0, $width, $height, $srcW, $srcH);

		}

		if (is_array($text)){

			$white = imageColorAllocate($new, 255, 255, 255);

			$black = imageColorAllocate($new, 0, 0, 0);

			$alpha = imageColorAllocateAlpha($new, 250, 250, 250, 80);

			$rectW = max(strlen($text[0]),strlen($text[1]))*7;

			ImageFilledRectangle($new, 0, $height-26, $width, $height, $alpha);

			//ImageFilledRectangle($new, 13, $height-20, 15, $height-7, $black);

			imagealphablending($new, true);

			ImageTTFText($new, 7.9, 0, 20, $height-14, $black, $this->fontName, $text[0]);

			ImageTTFText($new, 7.9, 0, 20, $height-6, $black, $this->fontName, $text[1]);

		}



		if ($this->toFile)

		{

			if (file_exists($this->galleryPath . $newName))

			unlink($this->galleryPath . $newName);

			if($imageInfo["type"]==3)

				imagepng($new, $this->galleryPath . $newName);

			else

			ImageJPEG($new, $this->galleryPath . $newName);

			//return $this->galleryPath . $newName;

			return $newName;

		}

		else

		{

			if($imageInfo["type"]==3)

				imagepng($new);

			else

				ImageJPEG($new);

		}

		ImageDestroy($new);

		ImageDestroy($img);



	}



	//==========================================

	// 函数: LoadJpeg ($imgname)

	// 功能: 显示指定jpg图片的错误处理

	// 参数: $imgname 文件名

	// 返回: 0 图片不存在

	//==========================================



	function LoadJpeg ($imgname) {

		$im = @imagecreatefromjpeg ($imgname); /* Attempt to open */

		if (!$im) { /* See if it failed */

			$im  = imagecreate (150, 30); /* Create a blank image */

			$bgc = imagecolorallocate ($im, 255, 255, 255);

			$tc  = imagecolorallocate ($im, 0, 0, 0);

			imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);

			/* Output an errmsg */

			imagestring ($im, 1, 5, 5, "Error loading $imgname", $tc);

		}



		return $im;

	}



	//==========================================

	// 函数: displayThumb($file)

	// 功能: 显示指定图片的缩略图

	// 参数: $file 文件名

	// 返回: 0 图片不存在

	//==========================================

	function displayThumb($file,$Old_img)

	{

		$thumbName = substr($file, 0, strrpos($file, ".")) . "_thumb.jpg";

		$file = $this->galleryPath . $thumbName;

		if (!file_exists($file)) {

			if ($Old_img!="" && file_exists($Old_img)){

				$html = "<img src='$Old_img' style='border:1px solid #000' width=128/>";

			}else{

				$html = "<img src='test.jpg' style='border:1px solid #000' width=128/>";

			}

		}else{

			$html = "<img src='$file' style='border:1px solid #000'/>";

		}

		return $html;

	}

	//==========================================

	// 函数: displayMark($file)

	// 功能: 显示指定图片的水印图

	// 参数: $file 文件名

	// 返回: 0 图片不存在

	//==========================================

	function displayMark($file)

	{

		$markName = substr($file, 0, strrpos($file, ".")) . "_mark.jpg";

		$file = $this->galleryPath . $markName;

		$html = "<img src='$file' style='border:1px solid #000'/>";

		return $html;

	}

	//==========================================

	// 函数: getInfo($file)

	// 功能: 返回图像信息

	// 参数: $file 文件路径

	// 返回: 图片信息数组

	//==========================================

	function getInfo($file)

	{

		$file = $this->sourcePath . $file;

		if (file_exists($file)) {

			$data = getimagesize($file);

			$imageInfo["width"] = $data[0];

			$imageInfo["height"]= $data[1];

			$imageInfo["type"] = $data[2];

			$imageInfo["name"] = basename($file);

		}

		return $imageInfo;

	}



}



?>