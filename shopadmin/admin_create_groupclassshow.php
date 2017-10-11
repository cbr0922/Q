<?php 
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include_once "Check_Admin.php";

if ($_GET['Action']=="Index"){
	$Sql = "select * from `{$INFO[DBPrefix]}groupclass` where catiffb=1 order by catord asc";
	$Creatfile= RootDocumentShare."/cache/GroupclassIndex_show";
}else{
	$Sql = "select * from `{$INFO[DBPrefix]}groupclass` order by catord asc";
	$Creatfile= RootDocumentShare."/cache/Groupclass_show";
}

$file_string = "";
$file_string .= "<?php\n\n\n  \$node_cache = array( \n";

$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i =0;
while ($Rs = $DB->fetch_array($Query)){

	$End = $i+1==$Num ? "" : ",";
	if ($Rs['catiffb']==0){
		$ifpub = "<font color=\"red\">[未發佈]</font>";
	}else{
		$ifpub = "";	
	}

	$file_string .="\n       array( 'id'  =>".$Rs['bid'].",";
	$file_string .="\n        'name'=>'".str_replace("'","‘",str_replace("\"","“",$Rs['catname'])).$ifpub."',";
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
//$FUNCTIONS->sorry_back("admin_pcat_list.php","");

$BackUrl = $BackUrl!="" ? $BackUrl : "admin_groupcat_list.php" ;
$FUNCTIONS->sorry_back($BackUrl,"");
?> 