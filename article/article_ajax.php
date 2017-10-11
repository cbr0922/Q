<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");

$Articleid = $_GET['articleid'];
$Articleid = intval($Articleid);
$DB->query("update  `{$INFO[DBPrefix]}news` set view=view+1 where  news_id='".intval($Articleid)."' ");

$Query   = $DB->query("select n.* from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 && n.niffb=1 && n.news_id='".intval($Articleid)."'  limit 0,1");
$Num   = $DB->num_rows($Query);

 if ($Num>0){
 $Result_Article = $DB->fetch_array($Query);
 $Ncateid        = $Result_Article['ncid'];
 $Nurl           = $Result_Article['url'];
 $Nurl_on        = $Result_Article['url_on'];
 $Nclass_name    = $Result_Article['ncname'];
 $Ntitle         = $Result_Article['ntitle'];
 $Author 	     = $Result_Article['author'];
 $Nbody          = $Result_Article['nbody']; preg_match('/<iframe[^>]*>(.*?)<\/iframe>/si',$Nbody,$match); $Nbody          = preg_replace('/<iframe[^>]*>(.*?)<\/iframe>/si', "<div class='embed-responsive embed-responsive-16by9'>".$match[0]."</div>", $Nbody);
 $view          = $Result_Article['view'];
 $pubdate          = $Result_Article['pubdate'] == "0000-00-00" ? date("Y-m-d",$Result_Article['nidate']): $Result_Article['pubdate'];
 $templatetype          = $Result_Article['templatetype'];
 $ifcomment          = $Result_Article['ifcomment'];
 $Ntitle_first   = $Result_Article['ntitle_color']!="" ? "<font color=".$Result_news['ntitle_color'].">".$Ntitle."</font>" : $Ntitle ;
 //$Img            = $Result_Article['nimg']!="" ?  "<img src=".$Result_Article['nimg']."><br><br>" : "<br><br>";
 $Img            = trim($Result_Article['nimg'])!="" ?  $FUNCTIONS->ImgTypeReturn("/UploadFile/NewsPic",trim($Result_Article['nimg']),'','')   : "";

$Img2            = $Result_Article['nimg']!="" ?  "".$Result_Article['nimg']."" : "";
 $Idate          = date("Y-m-d",$Result_Article['nidate']);

 }

 $tpl->assign("Ncateid",      $Ncateid);         //新闻大类ID
 $tpl->assign("Nclass_name",  $Nclass_name);     //新闻大类名称
 $tpl->assign("Ntitle",       $Ntitle);          //新闻标题
 $tpl->assign("Nbody",        $Nbody);           //新闻内容
 $tpl->assign("Ntitle_first", $Ntitle_first);    //新闻标题，带颜色控制的！
 $tpl->assign("Img",          $Img);             //新闻图片
 $tpl->assign("Img2",          $Img2);             //新闻图片(只有檔名
 $tpl->assign("Idate",        $Idate);           //新闻日期
 $tpl->assign("Articleid",    $Articleid);
 $tpl->assign("Author",      $Author);
 $tpl->assign("Back_say",     $Article_Pack[Back_say]);            //返回
 $tpl->assign("templatetype",     $templatetype);
 $tpl->assign("pubdate",     $pubdate);
 $tpl->assign("view",     $view);
$tpl->display("article_ajax.html");
 ?>