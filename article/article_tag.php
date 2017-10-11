<?php

error_reporting(7);

@header("Content-type: text/html; charset=utf-8");



include ("../configs.inc.php");






//活動刊版

$Sql = " select adv_id,adv_left_url,adv_right_url,adv_width,adv_height,adv_content,point_num,adv_left_img,adv_right_img from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=3 and adv_tag='activity'  order by rand() limit 0,1 ";

$Query = $DB->query($Sql);

$Num   = $DB->num_rows($Query);

//echo $IframeAdv_tag;

$i = 0;

if ($Num > 0){

   $Result = $DB->fetch_array($Query); 

   $LeftAdv=  $Result['adv_content'];  



}

$tpl->assign("LeftAdv",  $LeftAdv);

//活動刊版


if (intval($_GET['tagid'])>0){

	$Option = " and gt.tagid='" . intval($_GET['tagid']) . "'";

}



/**

 * 装载翻页函数

*/

include(Classes . "/PageNav.class.php");



//获得新闻列表

$Sql =  "select * from `{$INFO[DBPrefix]}article_tag` gt inner join `{$INFO[DBPrefix]}news` n on ( n.news_id=gt.news_id ) inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 ".$Option." order by n.nord asc, n.nidate desc ";

$PageNav = new PageItem($Sql,10);

$Num     = $PageNav->iTotal;

if ($Num>0){

	$arrRecords = $PageNav->ReadList();

	$i=0;

	$j=1;

	while ( $NewNav = $DB->fetch_array($arrRecords)){
		
		$viewlevel_sql = "select * from `{$INFO[DBPrefix]}news_userlevel` as gu inner join `{$INFO[DBPrefix]}user_level` as ul on gu.levelid=ul.level_id where gu.nid='" . intval($NewNav['news_id']) . "'";
		$Query_viewlevel = $DB->query($viewlevel_sql);
		$viewlevel = array();
		$v = 0;
		while ($Result_viewlevel=$DB->fetch_array($Query_viewlevel)){
			$viewlevel[$v] = $Result_viewlevel['level_name'];
			$v++;
		}
		
		$viewlevel_string = "";
		if (count($viewlevel)>0)
			$viewlevel_string = " (僅允許" . implode(" ",$viewlevel) . "查看)";

		$Nltitle        =  $NewNav['nltitle'].$viewlevel_string;

		$Nltitle_first  =  $NewNav['nltitle_color']!="" ? "<font color=".$NewNav['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;



		$Nltitle_s =  $NewNav['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($NewNav['news_id'])."'><span>".$Nltitle_first."</span></a>" :  "<a href='".$NewNav['url']."'>".$Nltitle_first."</a>";

		$AllNltitle[$i] = $Nltitle_s;

		$AllNltime[$i]  = $NewNav['nidate'];		 

		$NcatName       = $Ncid >0 ?  $NewNav['ncname'] : "" ;

		
        $tpl->assign("article_title".$j, $AllNltitle[$i]); 

		$tpl->assign("ntitle".$j, $AllNltitle[$i]); 

	    if (trim($AllNltime[$i])!=""){

		   $tpl->assign("nidate".$j,       date("Y-m-d",$AllNltime[$i]));    

	    }

		$i++;

		$j++;

	}

	$tpl->assign("ArticlePageItem",       $PageNav->myPageItem());    

}

$tpl->assign("NcatName",       $NcatName);  
$Sql = "select * from `{$INFO[DBPrefix]}tag` where tagid=".intval($_GET['tagid'])." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$Result= $DB->fetch_array($Query);
$tpl->assign("tag",      $Result['tagname']); 
$tpl->assign($Article_Pack); 

$tpl->display("article_tag.html");
?>