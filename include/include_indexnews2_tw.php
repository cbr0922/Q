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
$month_array = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$Pic_News     = "select ns.news_id,ns.ntitle,ns.nimg,ns.brief,ns.news_id,ns.nltitle,ns.nltitle_color,ns.url_on,ns.url,ns.nidate,ns.pubdate from `{$INFO[DBPrefix]}nclass` nc inner join  `{$INFO[DBPrefix]}news` ns on ( nc.ncid = ns.top_id ) where  (nc.ncid='" . $ncid . "' or nc.top_id='" . $ncid . "') and  nc.ncatiffb='1' and  ns.niffb='1'  order by ns.pubdate desc,ns.nord asc, ns.nidate desc  limit 0,5";
    $Query_Pic_News   = $DB->query($Pic_News);
    $i=0;
	while( $Result_Pic_news = $DB->fetch_array($Query_Pic_News) )
	{
	   $Nltitle        =  $Result_Pic_news['nltitle'];
	   $Nltitle_first  =  $Result_Pic_news['nltitle_color']!="" ? "<font color=".$Result_Pic_news['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
	   $Nltitle_s =  $Result_Pic_news['url_on']==0 ? "<a href='article/article.php?articleid=".intval($Result_Pic_news['news_id'])."' class=job2>".$Nltitle_first."</a>" :  "<a href='".$Result_Pic_news['url']."'>".$Nltitle_first."</a>";
       $Pic_News1[$i]['news_id']	= $Result_Pic_news['news_id'];
	   $Pic_News1[$i]['title']		= $Result_Pic_news['ntitle'];
	   $Pic_News1[$i]['ltitle']		= $Nltitle_s;
	   $Pic_News1[$i]['brief']		= $Result_Pic_news['brief'];
	   $Pic_News1[$i]['nimg']		= $Result_Pic_news['nimg'];
	   $pubdate = $Result_Pic_news['pubdate'];
	   $pubdate_array = explode("-",$pubdate);
	   $Pic_News1[$i]['nidate']		= $month_array[$pubdate_array[1]-1] . "  " . $pubdate_array[0];
	   $Pic_News1[$i]['nidated']=$pubdate_array[2];
	  // $Pic_News1[$i]['nidate']		= date("M.  Y",$Result_Pic_news['nidate']);
	  // $Pic_News1[$i]['nidated']		= date("d",$Result_Pic_news['nidate']);
      $i++;
    }
	//print_r($Pic_News1);
//$tpl->assign('pic_news',$Pic_News1);


$Pic_News     = "select nc.* from `{$INFO[DBPrefix]}nclass` nc where  nc.top_id='" . $ncid . "'";
$Query_Pic_News   = $DB->query($Pic_News);
$j=0;
while( $Result_Pic_news = $DB->fetch_array($Query_Pic_News) )
{
	 $Sql = "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where n.top_id='" . $Result_Pic_news['ncid'] . "' order by pubdate desc limit 0,5";
	$Query   = $DB->query($Sql);
	$i = 0;
	$Pic_News = array();
	 while( $Rs = $DB->fetch_array($Query) )
	{
	   $Nltitle        =  $Rs['nltitle'];
	   $Nltitle_first  =  $Rs['nltitle_color']!="" ? "<font color=".$Rs['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
     $Nltitle_s =  $Rs['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($Rs['news_id'])."' class=job2>".$Nltitle_first."</a>" :  "<a href='".$Rs['url']."'>".$Nltitle_first."</a>";
     $Nltitle_s2 =  $Rs['url_on']==0 ? "".$INFO['site_url']."/article/article".intval($Rs['news_id']) :  "".$Rs['url']."";	   $Pic_News[$i]['news_id']	= $Rs['news_id'];
	   $Pic_News[$i]['title']		= $Rs['ntitle'];
	   $Pic_News[$i]['ltitle']		= $Nltitle_s;
     $Pic_News[$i]['ltitle2']		= $Nltitle_s2;
	   $Pic_News[$i]['brief']		= $Rs['brief'];
	   $Pic_News[$i]['nimg']		= $Rs['nimg'];
	    $pubdate = $Rs['pubdate'];
	   $pubdate_array = explode("-",$pubdate);
	   $Pic_News[$i]['nidate']		= $month_array[$pubdate_array[1]-1] . "  " . $pubdate_array[0];
	   $Pic_News[$i]['nidated']=$pubdate_array[2];
	   $i++;
	}
	$j++;
	$tpl->assign('pic_news' . $j,$Pic_News);
}


//網站焦點結束
$tpl->display("include_indexnews2_tw.html");
?>
