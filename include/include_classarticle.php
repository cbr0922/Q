<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

$bid = intval($_GET['bid']);
if ($bid>0){
	$where = " and nc.classid='" . $bid . "' ";	
}

/*
文章
*/
	$Sql = "select * from `{$INFO[DBPrefix]}news` as n inner join `{$INFO[DBPrefix]}nclass` as nc on n.top_id=nc.ncid  where  n.niffb=1 " . $where . " order by n.nord asc, n.nidate desc limit 0,5";
	$Query    = $DB->query($Sql);
	$i = 0;
	while ($article_Rs=$DB->fetch_array($Query)) {
		$article_array[$i]['ntitle'] = $article_Rs['ntitle'];
		$article_array[$i]['news_id'] = $article_Rs['news_id'];
		$Nltitle_first  =  $article_Rs['nltitle_color']!="" ? "<font color=".$article_Rs['nltitle_color'].">".$article_Rs['ntitle']."</font>" : $article_Rs['ntitle'] ;
		$Nltitle_s =  $article_Rs['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($article_Rs['news_id'])."'><span>".$Nltitle_first."</span></a>" :  "<a href='".$article_Rs['url']."'>".$Nltitle_first."</a>";
		$article_array[$i]['urltitle'] = $Nltitle_s;
		$i++;
	}
	$tpl->assign("article_array",$article_array);
	//print_r($article_array);

$tpl->display("include_classarticle.html");
?>
