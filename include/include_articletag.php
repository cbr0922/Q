<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

/*
TAG
*/

$Sql      = "select * from `{$INFO[DBPrefix]}tag` where articlecount>0 order by rand() limit 0,50";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i = 0;
if ($Num > 0){
	while ($Tag_Rs=$DB->fetch_array($Query)) {
		$tag_array[$i]['tagname'] = $Tag_Rs['tagname'];
		$tag_array[$i]['tagid'] = $Tag_Rs['tagid'];
		$tag_array[$i]['goodscount'] = $Tag_Rs['goodscount'];
		$tag_array[$i]['viewcount'] = $Tag_Rs['viewcount'];
		$i++;
	}
}

$tpl->assign("tag_array_one",$tag_array);

/*
TAG
*/

$tpl->display("include_tag.html");
?>
