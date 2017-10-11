<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
if (is_file("configs.inc.php")){
 include("./configs.inc.php");
}elseif (is_file("../configs.inc.php")){
 include("../configs.inc.php");
}

include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");
if (intval($_GET['articleid']) >0){
	$sql = "select top_id from `{$INFO[DBPrefix]}news` where news_id='" . intval($_GET['articleid']) . "'";
	$query  = $DB->query($sql);
	$Rs =  $DB->fetch_array($query);
	$_GET['ncid'] = $Rs['top_id'];
}


	$Query = $DB->query("select * from `{$INFO[DBPrefix]}nclass` where ncid=".intval($_GET['ncid'])." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		if($Result['top_id']==0)
			$top_id = $_GET['ncid'];
		else
			$top_id = $Result['top_id'];
	}
	
	

$Sql =  "select ncid,ncname,ncimg,top_id from  `{$INFO[DBPrefix]}nclass`  where  ncatiffb=1 and top_id='" . $top_id . "'order by ncatord asc ";
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
$tpl->assign("top_id", $top_id);
$tpl->assign("Ncat", $Ncat);
$tpl->assign($Article_Pack);
$tpl->display("article_class.html");

?>

