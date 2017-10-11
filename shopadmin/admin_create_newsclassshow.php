<?php 
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include_once "Check_Admin.php";

if ($_GET['Action']=="Index"){
	$Sql = "select * from `{$INFO[DBPrefix]}nclass` where ncatiffb=1 order by  ncatord asc ";
	$Creatfile= RootDocumentShare."/cache/NewsclassIndex_show";
}else{
	$Sql = "select * from `{$INFO[DBPrefix]}nclass` order by  ncatord asc";
	$Creatfile= RootDocumentShare."/cache/Newsclass_show";
}

$file_string = "";
$file_string .= "<?php\n\n\n  \$node_cache = array( \n";

$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i =0;
while ($Rs = $DB->fetch_array($Query)){

	$End = $i+1==$Num ? "" : ",";

	$file_string .="\n       array( 'id'  =>".$Rs['ncid'].",";
	$file_string .="\n        'name'=>'".str_replace("'","‘",str_replace("\"","“",$Rs['ncname']))."',";
	$file_string .="\n        'parentId'=>".$Rs['top_id'].",";
	$file_string .="\n        'iffb'=>".$Rs['ncatiffb'].",";
	$file_string .="\n        'language'=>'".$Rs['language']."',";
	$file_string .="\n        'path'=>'".$Rs['path']."',";
	$file_string .="\n       )".$End." \n";

	$i++;
}

$file_string .= " \n );";

$file_string .= "\n\n\n\n?>";



if ( $fh = fopen( $Creatfile.'.php', 'wb' ) )
{
	@fputs ($fh, $file_string, strlen($file_string) );
	@fclose($fh);
	@chmod ($Creatfile,0777);
}
$BackUrl = $BackUrl!="" ? $BackUrl : "admin_ncat_list.php" ;
$FUNCTIONS->sorry_back($BackUrl,"");

?> 