<?php
/******************************************
*
* 文件名: Html_Smarty.php
* 作  用: 扩展Samrty类,使它能够生成静态页
*
* 作  者:  大白菜芯
* Email:  php_netproject@yahoo.com.cn
*
*******************************************/
include_once('Smarty.class.php');

$tpl_HTML = new Smarty();                                           //建立smarty实例对象$smarty
$tpl_HTML->debugging = flase;
//$tpl->cache_lifetime = 60 * 60 * 24;                         //设置缓存时间
$tpl_HTML->cache_lifetime = 0;                                      //设置缓存时间
$tpl_HTML->caching        = false;                                  //这里是调试时设为false,发布时请使用true
$tpl_HTML->left_delimiter = '<{';
$tpl_HTML->right_delimiter= '}>';
$tpl_HTML->template_dir   = RootDocumentShare."/templates";                    //设置模板目录
$tpl_HTML->compile_dir    = RootDocumentShare."/templates_c";     //设置编译目录
//$tpl_HTML->clear_all_cache();
//$tpl_HTML->clear_compiled_tpl();




class Html_Smarty extends Smarty
{
	function MakeHtmlFile($fileName,  $Content="")
	{
		global $INFO;



		if (is_writable($fileName)){
			@chmod ($fileName, 0777);
		}else{
			if (is_dir(RootDocument."/HTML_C")){
				@chmod (RootDocument."/HTML_C", 0777);
			}else{
				@mkdir(RootDocument."/HTML_C",0777);
				@chmod (RootDocument."/HTML_C", 0777);
			}

		}


		if(!$fp = @fopen($fileName, "wb"))
		{
			echo "<b>problem open $fileName!</b>";

			return false;
		}

		if(!fwrite($fp, $Content))
		{
			echo "<b>problem write $fileName!</b>";
			@fclose($fp);
			return false;
		}

		@fclose($fp);
		@chmod ($fileName, 0777);

		return true;
	}


	function MakeHtmlDocFile($fileName,  $Content="")
	{
		global $INFO;


		if (is_writable($fileName)){
			@chmod ($fileName, 0777);
		}


		if(!$fp = fopen($fileName, "wb"))
		{
			echo "<b>problem open $fileName!</b>";

			return false;
		}

		if(!fwrite($fp, $Content))
		{
			echo "<b>problem write $fileName!</b>";
			@fclose($fp);
			return false;
		}

		@fclose($fp);
		@chmod ($fileName, 0777);

		return true;
	}
}
?>