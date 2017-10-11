<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include( dirname( __FILE__ )."/"."../configs.inc.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");



$Sql_News     = "select ns.news_id,ns.nltitle,ns.nltitle_color,ns.url_on,ns.url from `{$INFO[DBPrefix]}nclass` nc inner join  `{$INFO[DBPrefix]}news` ns on ( nc.ncid = ns.top_id ) where  nc.ncid='".intval($INFO[IndexNewClassId])."' and  nc.ncatiffb='1' and  ns.niffb='1'  order by ns.nord asc, ns.nidate desc  limit 0,10";
    $Query_News   = $DB->query($Sql_News);
    $i=0;
	while( $Result_news = $DB->fetch_array($Query_News) )
	{
	   $Nltitle        =  $Result_news['nltitle'];
	   $Nltitle_first  =  $Result_news['nltitle_color']!="" ? "<font color=".$Result_news['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
	   // $Nltitle_s =  $Result_news['url_on']==1 ? "<a href='".$Result_news['url']."'>".$Nltitle_first."</a>" : $Nltitle_first ;
	   $Nltitle_s =  $Result_news['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Result_news['news_id'])."'>".$Nltitle_first."</a>" :  "<a href='".$Result_news['url']."'>".$Nltitle_first."</a>";
       $AllNltitle[$i] = $Nltitle_s;
      $i++;
    }



$tpl->assign("AllNltitle", $AllNltitle); //新闻内容数组

$tpl->assign("article_title0", $AllNltitle[0]); //第一条新闻
$tpl->assign("article_title1", $AllNltitle[1]); //
$tpl->assign("article_title2", $AllNltitle[2]); //
$tpl->assign("article_title3", $AllNltitle[3]); //
$tpl->assign("article_title4", $AllNltitle[4]); //
$tpl->assign("article_title5", $AllNltitle[5]); //
$tpl->assign("article_title6", $AllNltitle[6]); //
$tpl->assign("article_title7", $AllNltitle[7]); //
$tpl->assign("article_title8", $AllNltitle[8]); //
$tpl->assign("article_title9", $AllNltitle[9]); //第十条新闻




$tpl->display("include_article.html");
?>
