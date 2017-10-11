<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");

$Sql =  "select ncid,ncname,ncimg,top_id from  `{$INFO[DBPrefix]}nclass`  where  ncatiffb=1 and top_id=" . intval($_GET['ncid']) . " and ncid<>1 order by ncatord desc ";
$Query =  $DB->query($Sql);
 $Num   =  $DB->num_rows($Query);
if ($Num>0){

	$i=0;
	while ( $Rs = $DB->fetch_array($Query)){
		$Ncat[$i][ncid]        =  $Rs['ncid'];
        $Ncat[$i][ncname]      =  $Rs['ncname'];
		$Ncat[$i][ncimg]       =  $Rs['ncimg'];
		$Ncat[$i][top_id]      =  $Rs['top_id'];
		$i++;
	}

}

$tpl->assign("Ncat", $Ncat);
$tpl->assign($Article_Pack);
$tpl->display("include_article_class2.html");

?>
