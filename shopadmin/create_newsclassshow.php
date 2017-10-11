<?php 
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
/**
 * 数据库连接
 */


$Sql = "select * from `bclass` ";
$Creatfile= "class_show";


$file_string = "";
$file_string .= "<?php\n\n\n  \$node_cache = array( \n";

$Query = mysql_query($Sql);
$Num   = mysql_num_rows($Query);

while ($Rs = mysql_fetch_array($Query)){

	$End = $i+1==$Num ? "" : ",";

	$file_string .="\n       array( 'id'  =>".$Rs['bid'].",";
	$file_string .="\n        'name'=>'".$Rs['ncname']."',";
	$file_string .="\n        'parentId'=>".$Rs['top_id'].",";
	$file_string .="\n       )".$End." \n";

}

$file_string .= " \n );";

$file_string .= "\n\n\n\n?>";



if ( $fh = fopen( $Creatfile.'.php', 'wb' ) )
{
	@fputs ($fh, $file_string, strlen($file_string) );
	@fclose($fh);
	@chmod ($Creatfile,0777);
}


?> 