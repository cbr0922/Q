<?php
@set_time_limit(1200);
/*
+--------------------------------------------------------------------------
|   tyler.wu 
|   ========================================
|   主要功能是将完成是写入服务器指定目录数据库备份SQL 还是下载。默认直接下载方式！
+--------------------------------------------------------------------------
*/


class SQL_Dump {

var $path = "./UploadFile/archive_out" ;

/*
+--------------------------------------------------------------------------
|                                 备份数据库
|   
+--------------------------------------------------------------------------
*/

	function do_safe_backup($ifDown,$ifDrop,$ifTable,$ifData)
	{
		global $DB;
		
		
			$skip        = 0;
			$create_tbl  = 0;
			$enable_gzip = 1;
            $ifDown      = intval($ifDown);  //是否直接下载方式
			$ifData      = intval($ifData);  //保留数据
			$ifTable     = intval($ifTable); //保留表结构
			$ifDrop      = intval($ifDrop);  // 保留删除语句
	        $filename    = $DB->dbname;
			$Create_time = date("Y-m-d H:i",time());
            $Header      =	<<<EOD

#########################################################
## tyler.wu SQL Dump
## version 1.0
## http://www.smartshop.com.tw
##
## 主机: localhost
## 生成日期: $Create_time
## 
## 数据库 : $DB->dbname
##


#--------------------------------------------------------

EOD;
		
		
  if (intval($ifDown)==0){  //如果是采取直接下载方式
	
		@header("Pragma: no-cache");

		/*
		if( $enable_gzip )
		{
			$phpver = phpversion();

			if($phpver >= "4.0")
			{
				if(extension_loaded("zlib"))
				{
					$do_gzip = 1;
				}
			}
		}
		*/



		if( $do_gzip != 0 )
		{
			@ob_start();
			@ob_implicit_flush(0);
			header("Content-Type: text/x-delimtext; name=\"$filename.sql\"");
			header("Content-disposition: attachment; filename=$filename.sql");
		}
		else
		{			
			@ob_start();
			@ob_implicit_flush(0);
			header("Content-Type: text/x-delimtext; name=\"$filename.sql\"");
			header("Content-disposition: attachment; filename=$filename.sql");
			
		}
  } 


		//-----------------------------
		// Get tables to work on
		//-----------------------------
	

	
			$tmp_tbl = $DB->get_table_names();

			foreach($tmp_tbl as $a =>$tbl)
			{				
		        $Drop_op = $ifDrop == 1  ?  "DROP TABLE IF EXISTS ".$tbl." ;\n\n " : '' ;
				$Create_SQL .= $Drop_op.$this->get_table_sql($tbl, $create_tbl, $skip,$ifDrop,$ifTable,$ifData);	
			}

       if (intval($ifDown)==0){   //如果不是下载出来，就是放到服务器上
         echo $Header."\n\n".$Create_SQL;
		 return false;
	   }else{
         return $Header."\n\n".$Create_SQL;
       }
	}
	
	//-----------------------------------------
	// Internal handler to return content from table
	//-----------------------------------------
	
	function get_table_sql($tbl, $create_tbl, $skip=0  ,$ifDrop,$ifTable,$ifData)
	{
		global $DB;
		
		
		if ($create_tbl==0)
		{
			// Generate table structure
			
	        $Sql   = "SHOW CREATE TABLE ".$DB->dbname.".".$tbl;
			$query = $DB->query($Sql);			
		 	$ctable = $DB->fetch_array($query);
		    $sql_table = $this->sql_strip_ticks($ctable['Create Table']).";\n\n";

		}
		



		// Get the data

		

		$query =$DB->query("SELECT * FROM $tbl");
		
		
		$row_count = $DB->num_rows($query);
		
	//	if ($row_count ==0 )
	//	{
	//		return TRUE;
	//	}
		
		//-----------------------------------------
		// Get col names
		//-----------------------------------------
		
		$f_list = "";
	
		$fields = $DB->get_result_fields($query);
		
		$cnt = count($fields);
		
		for( $i = 0; $i < $cnt; $i++ )
		{
			$f_list .= $fields[$i]->name . ", ";
		}
		
		$f_list = preg_replace( "/, $/", "", $f_list );
		
		while ( $row = $DB->fetch_array($query) )
		{
			//-----------------------------------------
			// Get col data
			//-----------------------------------------
			
			$d_list = "";
			
			for( $i = 0; $i < $cnt; $i++ )
			{
				if ( ! isset($row[ $fields[$i]->name ]) )
				{
					$d_list .= "NULL,";
				}
				elseif ( $row[ $fields[$i]->name ] != '' )
				{
					$d_list .= "'".$this->sql_add_slashes($row[ $fields[$i]->name ]). "',";
				}
				else
				{
					$d_list .= "'',";
				}
			}
			
			$d_list = preg_replace( "/,$/", "", $d_list );
			
		 	$sql_date .="INSERT INTO $tbl ($f_list) VALUES($d_list);\n";
		}
		
       $STR  = "\n";
       if ($ifTable==1){
	   $STR  .= $sql_table;
	   }

       if ($ifData==1){
	   
	   $STR  .= "LOCK TABLES `$tbl` WRITE;\n".$sql_date."UNLOCK TABLES; \n ";
	   }

      
		return $STR."\n\n\n#---------------------------------------------------------------------------\n\n";
		
	}
	
	
	
	//-------------------------------------------------------------------
	// sql_strip_ticks from field names
	//-------------------------------------------------------------------
	
	function sql_strip_ticks($data)
	{
		return str_replace( "`", "", $data );
	}
	
	//-------------------------------------------------------------------
	// Add slashes to single quotes to stop sql breaks
	//-------------------------------------------------------------------
	
	function sql_add_slashes($data)
	{
		$data = str_replace('\\', '\\\\', $data);
        $data = str_replace('\'', '\\\'', $data);
        $data = str_replace("\r", '\r'  , $data);
        $data = str_replace("\n", '\n'  , $data);
        
        return $data;
	}

/*
+--------------------------------------------------------------------------
|                                 恢复数据库
|   
+--------------------------------------------------------------------------
*/

function do_safe_restore($savepath,$Sql_File,$tmpfile,$path){

global $DB;
if ($savepath==1 && $Sql_File!='' ){
    $fp = @fopen($Sql_File, "rb") or die("不能打开SQL文件 $Sql_File");//打开文件	
    while($SQL=$this->GetNextSQL($fp)){
                if (!$DB->query($SQL)){
                        echo "<font color=red>执行出错：".mysql_error()."</font><br>";
                        echo "SQL语句为：<br>".$SQL."<br>";
						return false;
                };
    }
 fclose($fp) or die("Can't close file $Sql_File");//关闭文件
 return true;


 }elseif ($savepath==0 && $Sql_File!='' ){   //如果是本地上传的文件
 
 
  $extname=substr($Sql_File,-3);  
  $extname=strtolower($extname);
    if($extname!="sql"){
      echo "<script language=javascript>alert('文件格式不正确！');";
      echo "location.href='admin_dataresume.php';</script>";
      exit;
    } else{
      $Create_file = $path."/".date("YmdHi",time())."_".rand(10,99)."_local_database.sql";
      @copy($tmpfile,$Create_file);
	}
   
    $fp = @fopen($Create_file, "rb") or die("不能打开SQL文件 $Create_file");//打开文件	
    while($SQL=$this->GetNextSQL($fp)){
                if (!$DB->query($SQL)){
                        echo "<font color=red>执行出错：".mysql_error()."</font><br>";
                        echo "SQL语句为：<br>".$SQL."<br>";
                };
    }
    @fclose($fp) or die("Can't close file $Create_file");//关闭文件
    return true;
   
  }

}
	
	
 function GetNextSQL($fp) {
                global $DB;
                $sql="";
                while ($line = @fgets($fp, 40960)) {
                        $line = trim($line);
                        //以下三句在高版本php中不需要，在部分低版本中也许需要修改
                        //$line = str_replace("\\\\","\\",$line);
                        //$line = str_replace("\'","'",$line);
                        //$line = str_replace("\\r\\n",chr(13).chr(10),$line);
                        //$line = stripcslashes($line);
                        if (strlen($line)>1) {
                                if ($line[0]=="-" && $line[1]=="-") {
                                        continue;
                                }
                        }
                        $sql.=$line.chr(13).chr(10);
                        if (strlen($line)>0){
                                if ($line[strlen($line)-1]==";"){
                                        break;
                                }
                        }
                }
                return $sql;
        }


}//class end


/*
+--------------------------------------------------------------------------
|   调用例子：
|   ========================================

 $SQL_Dump = new SQL_Dump;   //初始化类

  $Create_sql = $SQL_Dump->do_safe_backup(1,1,1,1); //采用不是1。将默认保存到指定路径中,1是确定使用DROP语句


if ($Create_sql){  //if return ture
  
  $Create_file = "./UploadFile/archive_out/".date("YmdHi",time())."_".rand(1,100)."_database.sql";

if (is_file($Create_file)){
   @unlink ($Create_file);
}

if(!file_exists($file))		//判断是否存在文件
 {
   chmod("./UploadFile/archive_out/",0777);	//修改文件夹属性
 }

$fp = fopen($Create_file,"w+");

if(!is_writeable($Create_file))	//判断文件是否可写
 {
   chmod($Create_file,0777);		//修改文件属性
 }


fputs($fp,$Create_sql);
fclose($fp);

}// endif return ture

+--------------------------------------------------------------------------
*/
?>
