<?php 
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include_once "Check_Admin.php";


$Sql = "select * from `{$INFO[DBPrefix]}forum_class` order by catord asc ";
$Creatfile= RootDocumentShare."/cache/Forumclass_show";


$file_string = "";
$file_string .= "<?php\n\n\n  \$node_cache = array( \n";

$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i =0;
while ($Rs = $DB->fetch_array($Query)){

	$End = $i+1==$Num ? "" : ",";

	$file_string .="\n       array( 'id'  =>".$Rs['bid'].",";
	$file_string .="\n        'name'=>'".$Rs['catname']."',";
	$file_string .="\n        'parentId'=>".$Rs['top_id'].",";
	$file_string .="\n        'iffb'=>".$Rs['catiffb'].",";
	$file_string .="\n       )".$End." \n";

	$i++;
}

$file_string .= " \n );";

$file_string .= "\n\n\n\n?>";



if ( $fh = fopen( $Creatfile.'.php', 'wb' ) )
{
	fputs ($fh, $file_string, strlen($file_string) );
	fclose($fh);
	@chmod ($Creatfile,0777);
}
$FUNCTIONS->sorry_back("admin_fcat_list.php","");
?> 