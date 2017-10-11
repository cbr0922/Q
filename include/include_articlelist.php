<?php

error_reporting(7);

@header("Content-type: text/html; charset=utf-8");



include( dirname( __FILE__ )."/"."../configs.inc.php");

include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");

$news = array();



/*if(intval($_GET['ncid'])>0){

	$ncid = intval($_GET['ncid']);

}else{*/

	$Sql = "select ns.top_id,nc.ncname from `{$INFO[DBPrefix]}news` ns inner join `{$INFO[DBPrefix]}nclass` as nc on ( nc.ncid = ns.top_id )  where ns.news_id='" . intval($_GET['articleid']) . "'";

	$Query = $DB->query($Sql);

	$Result= $DB->fetch_array($Query);

	$ncid		= $Result['top_id'];

	$NcatName       = $Result['ncname'];


//}

$tpl->assign("ncid",$ncid);

$tpl->assign("NcatName",$NcatName);

$Sql_News     = "select ns.news_id,ns.nltitle,ns.nltitle_color,ns.url_on,ns.url from `{$INFO[DBPrefix]}nclass` nc inner join  `{$INFO[DBPrefix]}news` ns on ( nc.ncid = ns.top_id ) where  nc.ncid='".intval($ncid )."' and  nc.ncatiffb='1' and  ns.niffb='1'  order by ns.nord asc, ns.nidate desc";

    $Query_News   = $DB->query($Sql_News);

    $i=0;

	while( $Result_news = $DB->fetch_array($Query_News) )

	{

	   $Nltitle        =  $Result_news['nltitle'];

	   $Nltitle_first  =  $Result_news['nltitle_color']!="" ? "<font color=".$Result_news['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;

	   // $Nltitle_s =  $Result_news['url_on']==1 ? "<a href='".$Result_news['url']."'>".$Nltitle_first."</a>" : $Nltitle_first ;

	   $Nltitle_s =  $Result_news['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($Result_news['news_id'])."'>".$Nltitle_first."</a>" :  "<a href='".$Result_news['url']."'>".$Nltitle_first."</a>";

       $news[$i]['title'] = $Nltitle_s;

	    $news[$i]['news_id'] = $Result_news['news_id'];

      $i++;

    }



$tpl->assign("news",$news);

//print_r($news);





$tpl->display("include_articlelist.html");

?>
