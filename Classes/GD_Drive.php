<?php
//=======================================================================
// File:	GDDrive.PHP
// Description:	Run all the example script in current directory
// Created: 	2005-4-30
// Author:	tyler.wu (php_netproject@yahoo.com.cn)
// Copyright (C) 2005 tyler.wu
//========================================================================


class GDDrive {
    
  function RunOneForcode($files) {

  $Pic =  "
          <a href=\"show-example.php?target=$files\"><img src=\"$files\" border=0 align=top></a><br><strong>\n		  
		  ";


    return $Pic;
  }

  function RunOne($files) {

  $Pic =  "
          <img src=\"$files\" border=0 align=top><br>\n		  
		  ";


    return $Pic;
  }

//将当前资料写入交换文件
function TmpGD($file,$Tmp_String,$Tmp_len){

//$file="./inc/tmpgd.inc";	//记录数据的文件

	if(!file_exists($file))		//判断是否存在文件
	{
	 chmod("./inc/",0777);	//修改文件夹属性
	 fopen($file,'wb');
	}
	if(!is_writeable($file))	//判断文件是否可写
	{
		chmod($file,0777);		//修改文件属性
	}

	$fo=fopen($file,"rb");		//打开文件
	$fg=fgets($fo,$Tmp_len);		//读取数据
	
	if($fg=='' || $fg!=$Tmp_file ) $fg=$Tmp_String;

	$fo2=fopen($file,'wb');		//以可写方式打开文件
	fputs($fo2,$fg);
	fclose($fo2);
}

//从交换文件中读取数据
function ReadTmpGD($file){

//$file="./inc/tmpgd.inc";	//记录数据的文件

	if(!file_exists($file))		//判断是否存在文件
	{
	 chmod("./inc/",0777);	//修改文件夹属性
	 fopen($file,'wb');
	}
	if(!is_writeable($file))	//判断文件是否可写
	{
		chmod($file,0777);		//修改文件属性
	}

	$fo=fopen($file,"rb");		//打开文件
	$fg=fgets($fo,1000);		//读取数据

    $Fg = explode(",",trim($fg));
return $Fg;
 }
}
/*
$driver = new GDDrive();
echo $driver->RunOne("horizbarex2.php");
*/
?>
