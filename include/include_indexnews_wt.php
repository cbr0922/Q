<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/**
 *  網站焦點 (ncid=)
 *  四組
 */
$Pic_News     = "select ns.news_id,ns.ntitle,ns.nimg,ns.brief,ns.news_id,ns.nltitle,ns.nltitle_color,ns.url_on,ns.url from `{$INFO[DBPrefix]}nclass` nc inner join  `{$INFO[DBPrefix]}news` ns on ( nc.ncid = ns.top_id ) where  nc.ncid='50' and  nc.ncatiffb='1' and  ns.niffb='1' order by ns.nord asc, ns.nidate desc  limit 0,5";
    $Query_Pic_News   = $DB->query($Pic_News);
    $i=0;
	while( $Result_Pic_news = $DB->fetch_array($Query_Pic_News) )
	{
	   $Nltitle        =  $Result_Pic_news['nltitle'];
	   $Nltitle_first  =  $Result_Pic_news['nltitle_color']!="" ? "<font color=".$Result_Pic_news['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
	   $Nltitle_s =  $Result_Pic_news['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Result_Pic_news['news_id'])."&type=print' class=job2>".$Nltitle_first."</a>" :  "<a href='".$Result_Pic_news['url']."'>".$Nltitle_first."</a>";
       $Pic_News1[$i]['news_id']	= $Result_Pic_news['news_id'];
	   $Pic_News1[$i]['title']		= $Result_Pic_news['ntitle'];
	   $Pic_News1[$i]['ltitle']		= $Nltitle_s;
	   $Pic_News1[$i]['brief']		= $Result_Pic_news['brief'];
	   $Pic_News1[$i]['nimg']		= $Result_Pic_news['nimg'];
      $i++;
    }
	//print_r($Pic_News1);
$tpl->assign('pic_news',$Pic_News1);
//網站焦點結束
$tpl->display("include_indexnews_wt.html");
?>
