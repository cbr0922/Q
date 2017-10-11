<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");

$Sql =  "select ncid,ncname,ncimg,top_id from  `{$INFO[DBPrefix]}nclass`  where  ncatiffb=1 and top_id=0 order by ncatord desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){

	$i=0;
	while ( $Rs = $DB->fetch_array($Query)){
		$Ncat[$i][ncid]        =  $Rs['ncid'];
        $Ncat[$i][ncname]      =  $Rs['ncname'];
		$Ncat[$i][ncimg]       =  $Rs['ncimg'];
		$Ncat[$i][top_id]      =  $Rs['top_id'];

$Sqlcount =  "select count(top_id) AS top_idcount from  `{$INFO[DBPrefix]}news`  where top_id=".$Rs['ncid'];
$Querycount =  $DB->query($Sqlcount);
$Numcount   =  $DB->num_rows($Querycount);
if ($Numcount>0){

	$j=0;
	while ( $Rscount = $DB->fetch_array($Querycount)){
		$Ncat[$i][count1]  =  $Rscount['top_idcount'];
		$j++;
	}
}		
		$i++;	
    }

}





$tpl->assign("Ncat", $Ncat);
$tpl->assign($Article_Pack);
$tpl->display("include_article_class.html");

?>

