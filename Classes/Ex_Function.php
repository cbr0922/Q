<?php
error_reporting(7);

class Ex_Function
{


	function save_config( $new,$filename )
	{

		$master = array();

		if ( is_array($new) )
		{
			if ( count($new) > 0 )
			{
				$Subarray = array();
				$SubString = "";

				foreach( $new as $k=>$field )
				{

					if (is_array($_POST[$field])){

						$_POST[$field]   = implode(",",$_POST[$field]);

					}

					/*
					if (is_array($_POST[$field])){
					$Subarray	= $_POST[$field];
					foreach($Subarray as $sk=>$sv)
					{
					$SubString .=$sv.""
					}
					}
					*/
					// Handle special..

					if ($field == 'img_ext' or $field == 'avatar_ext' or $field == 'photo_ext')
					{
						$_POST[ $field ] = preg_replace( "/[\.\s]/", "" , $_POST[ $field ] );
						$_POST[ $field ] = str_replace('|', "&#124;", $_POST[ $field ]);
						$_POST[ $field ] = preg_replace( "/,/"     , '|', $_POST[ $field ] );
					}
					else if ($field == 'coppa_address')
					{
						$_POST[ $field ] = nl2br( $_POST[ $field ] );
					}

					if ( $field == 'gd_font' OR $field == 'html_dir' OR $field == 'upload_dir')
					{
						$_POST[ $field ] = preg_replace( "/'/", "&#39;", $_POST[ $field ] );
					}
					else
					{
						$_POST[ $field ] = preg_replace( "/'/", "&#39;", stripslashes($_POST[ $field ]) );
					}


					$master[$field] = stripslashes($_POST[$field]);


				} //end foreach

				$this->rebuild_config($master,$filename);

			} //end count($new)

		}  // end is_array($new)

	}  // end function



	function rebuild_config( $new = "" ,$filename)

	{


		//-----------------------------------------
		// Check to make sure this is a valid array
		//-----------------------------------------

		if (! is_array($new) )
		{
			echo "错误：你正在尝试重新生成配置文件，操作失败！";
			exit;
		}

		//-----------------------------------------
		// Do we have anything to save out?
		//-----------------------------------------

		if ( count($new) < 1 )
		{
			return "";
		}

		//-----------------------------------------
		// Get an up to date copy of the config file
		// (Imports $INFO)
		//-----------------------------------------

		include RootDocumentShare."/".$filename.".php";
		//require "inc/".$filename.".php";


		//-----------------------------------------
		// Rebuild the $INFO hash
		//-----------------------------------------

		foreach( $new as $k => $v )
		{
			// Update the old...

			$v = preg_replace( "/'/", "\\'" , $v );
			$v = preg_replace( "/\r/", ""   , $v );

			$INFO[ $k ] = $v;
		}

		//-----------------------------------------
		// Rename the old config file
		//-----------------------------------------

		//@rename( RootDocumentShare."/".$filename.".php", RootDocumentShare."/".$filename."-bak.php" );
		@chmod ( RootDocumentShare."/".$filename.".php", 0777);

		//@rename( "inc/".$filename.".php", "inc/".$filename."-bak.php" );
		//@chmod(  "inc/".$filename."-bak.php", 0777);

		//-----------------------------------------
		// Rebuild the old file
		//-----------------------------------------

		ksort($INFO);

		$file_string = "<?php\n";

		foreach( $INFO as $k => $v )
		{
			if ($k == 'skin' or $k == 'languages')
			{
				// Protect serailized arrays..
				$v = stripslashes($v);
				$v = addslashes($v);
			}

			$file_string .= '$INFO['."'".$k."'".']'."\t\t\t=\t'".$v."';\n";
		}

		$file_string .= "\n".'?'.'>';   // Question mark + greater than together break syntax hi-lighting in BBEdit 6 :p

		if ( $fh = fopen( RootDocumentShare."/".$filename.".php", 'w' ) )
		//		if ( $fh = fopen( "inc/".$filename.".php", 'w' ) )
		{
			fputs ($fh, $file_string, strlen($file_string) );
			fclose($fh);
		}
		else
		{
			echo "错误警告：无法打开和写入文件 'conf.global.php' - 请检查文件属性是否正确（CHMOD 777）。";
			exit;
		}

		// Pass back the new $INFO array to anyone who cares...

		return $INFO;


	}



	/*-------------------------------------------这里是类结束的地线---------------*/
}/*类结束标识*/

?>