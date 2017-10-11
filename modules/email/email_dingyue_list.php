<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include ("../../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");



/**
 * 装载翻页函数
*/
include("PageNav.class.php");

//获得电子报列表
$Sql =  "select publication_title,publication_content,publication_alreadysend,publication_start_time,publication_end_time,publication_id from `{$INFO[DBPrefix]}publication`  order by publication_id desc";
$PageNav = new PageItem($Sql,10);
$Num     = $PageNav->iTotal;
$publicationNavArray = array();
if ($Num>0){
	$arrRecords = $PageNav->ReadList();
	$i=0;
	while ( $publicationNav = $DB->fetch_array($arrRecords)){
        $publicationNavArray[$i][publication_id]           =  $publicationNav[publication_id];
        $publicationNavArray[$i][publication_title]        =  $publicationNav[publication_title];
        $publicationNavArray[$i][publication_content]      =  $publicationNav[publication_content];
        $publicationNavArray[$i][publication_alreadysend]  =  $publicationNav[publication_alreadysend];
        $publicationNavArray[$i][publication_start_time]   =  date("Y-m-d",$publicationNav[publication_start_time]);
        $publicationNavArray[$i][publication_end_time]     =  date("Y-m-d",$publicationNav[publication_end_time]);
		$i++;
	}

	$tpl->assign("publicationPageItem",       $PageNav->myPageItem());   
	$tpl->assign("publicationNavArray",       $publicationNavArray);   	

}
$tpl->assign($Email_Pack); 
$tpl->display("email_dingyue_list.html");

?>

