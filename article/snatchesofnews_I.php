<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");




/**
 * 装载文章类
 */
include_once ("article.class.php");
$Article_Class  = new Article_Class;


//得獎讯息
$cakeArticleList = $Article_Class->ArticleClassList_Array(6," limit 0,10 ");
$tpl->assign("cakeArticleList",               $cakeArticleList);

//书评专区
$BookClassList = $Article_Class->ArticleClass_Array(5,"  ");
$tpl->assign("BookClassList",               $BookClassList);

//小鲁学习单
$SPageClassList = $Article_Class->ArticleClass_Array(4,"  ");
$tpl->assign("SPageClassList",               $SPageClassList);

/**
 * 主题专区
 */
$Sql_sub   = " select subject_name,subject_id from `{$INFO[DBPrefix]}subject` where subject_open=1 order by subject_num desc ";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][subject_id]    =  $Rs_sub['subject_id'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$sub_i++;
}
$tpl->assign("Array_sub",       $Array_sub);           //主题类别名称循环



 $tpl->assign($Article_Pack);
 $tpl->assign($Email_Pack);
 $tpl->display("snatchesofnews_I.html");
 ?>
