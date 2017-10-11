<?php
include "../configs.inc.php";
include (Classes . "/global.php");
if ($tpl->clear_compiled_tpl()){
echo "0"."&nbsp;".$Basic_Command['Ge_say'];
}
/*
$dir = "../templates/".$INFO['templates']."/templates_c";
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {   
	   $files[] = $filename;			
      }


	  array_shift ($files);
 	  array_shift ($files);
	  $templates_c = array();
	  foreach ($files as $k=>$v){

	    if ( $v!='.' && $v!='..' ) {
            if (!is_dir($dir."/".$v)){
			 //$templates_c[] = $v ;
		    unlink($dir."/".$v);
			}
	    }
 	  }		
*/
?>