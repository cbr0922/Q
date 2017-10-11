<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/*
文章
*/
	$Sql = "select * from `{$INFO[DBPrefix]}news` where top_id=5 and niffb=1 ";
	$Query    = $DB->query($Sql);
	$i = 0;
	while ($article_Rs=$DB->fetch_array($Query)) {
	$article_array[$i]['nimg'] = $article_Rs['nimg'];
		$article_array[$i]['ltitle'] = $article_Rs['nltitle'];
		$article_array[$i]['news_id'] = $article_Rs['news_id'];
		$article_array[$i]['brief'] = $article_Rs['brief'];
		$i++;
	}
	$tpl->assign("pic_news",$article_array);

$tpl->display("include_indexarticle.html");
?>
