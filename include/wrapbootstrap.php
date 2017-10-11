<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

if(isset($_GET['numPosts']) && isset($_GET['pageNumber'])){
	$Sql = " select * from `{$INFO[DBPrefix]}image` where pid='" . intval($INFO['album_id']) . "'";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$limit = "limit 10,".$Num;
}else {
	$limit = "limit 0,10";
}

$Sql = " select * from `{$INFO[DBPrefix]}image` where pid='" . intval($INFO['album_id']) . "' order by sort " . $limit;
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$img_array = array();
$i = 0;
while($Result = $DB->fetch_array($Query)){
	$img_array[$i] = $Result;
	$img_array[$i]['n'] = $i % 10;
	$img_array[$i]['url'] = $Result['url']==""?"#":$Result['url'];
	$i++;
}
//print_r($img_array);
$tpl->assign("img_array",$img_array);

$tpl->display("wrapbootstrap.html");
?>
