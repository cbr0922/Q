<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");

//  这里是当有用户发表评论的时候发生的操作!!!
if ($_POST['action']='SubmitComment' && !empty($_POST['news_id'])){
	//print_r($_POST);exit;
	if ($_SESSION['user_id'] != "" && $_SESSION['username']){
		$Result = $DB->query(" insert into `{$INFO[DBPrefix]}news_comment` (nid,comment_content,comment_idate) values ('".intval($_POST['news_id'])."','".$_POST['content']."','".time()."')" );
		if ($Result){
			echo "<script language='javascript'>alert('您的問題已送出，等待店長回覆');location.href='" . $_POST['Url'] . "';</script>";
		}
	}
	else{
		echo "<script language='javascript'>alert('請先登入');history.back(-1);</script>";
	}
}
$Articleid = $_GET['articleid'];
$Articleid = intval($Articleid);
$class_banner = array();
$list = 0;

/**
瀏覽等級
**/
$ifview = 0;
$viewlevel_sql = "select * from `{$INFO[DBPrefix]}news_userlevel` as gu inner join `{$INFO[DBPrefix]}user_level` as ul on gu.levelid=ul.level_id where gu.nid='" . intval($Articleid) . "'";
$Query_viewlevel = $DB->query($viewlevel_sql);
$viewlevel = array();
$v = 0;
while ($Result_viewlevel=$DB->fetch_array($Query_viewlevel)){
	$viewlevel[$v] = $Result_viewlevel['level_name'];
	if (intval($_SESSION['user_level'])>0 && intval($Result_viewlevel['level_id'])==intval($_SESSION['user_level'])){
		$ifview = 1;
	}
	$v++;
}

$viewlevel_string = "";
if (count($viewlevel)>0)
	$viewlevel_string = "僅允許" . implode(" ",$viewlevel) . "查看文章詳細內容";

if ($viewlevel_string != "" && $ifview == 0){
	 $string = "location.href='index.php'";
	if($_GET['url']==""){
		 $_GET['url'] = $_SERVER['HTTP_REFERER'];

	}
	if($_GET['url']!=""){
		 $string = "location.href='" . $_GET['url']  . "'";
	}

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('" . $viewlevel_string . "');" . $string . "</script>";
    exit;
}
/**
結束
**/
//浏览过的文章
	$goodscount = count($_COOKIE['viewarticle'])+1;
	$flag = 0;
	if (isset($_COOKIE['viewarticle'])){
		foreach($_COOKIE['viewarticle'] as $k=>$v){
			if ($v == intval($Articleid)){
				$flag = 1;
			}
		}
	}
	if ($flag == 0){
		setcookie("viewarticle[" . $goodscount . "]", intval($Articleid),time()+3600*24,"/");
	}
//显示浏览过的文章
//print_r($_COOKIE['viewarticle']);
$viewArticleArray = array();
if (isset($_COOKIE['viewarticle'])){
	$array = array_reverse($_COOKIE['viewarticle']);
	$i=0;
	foreach($array as $k=>$v){
			if ($i<5){

				$Sql   = "select n.* from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid )  where  nc.ncatiffb=1 && n.niffb=1 && n.news_id='".intval($v)."' and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') limit 0,1";
				$Query = $DB->query($Sql);
				$Rs =  $DB->fetch_array($Query);
				if ($Rs['news_id']>0){
					$Nltitle        =  $Rs['nltitle'];
					$viewArticleArray[$i]['title'] = $Rs['nltitle_color']!="" ? "<font color=".$Rs['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
					//$viewArticleArray[$i]['title'] = $Nltitle;
					//$Nltitle_first  =  $Rs['nltitle_color']!="" ? "<font color=".$Rs['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
					$viewArticleArray[$i]['ltitle'] = $Rs['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Rs['news_id'])."' class='a14'>".$Nltitle_first."</a>" :  "<a href='".$Rs['url']."'>".$Nltitle_first."</a>";
					$viewArticleArray[$i]['nimg'] = $Rs['nimg'];
					//$viewArticleArray[$i]['nimg'] = $Rs['smallimg'];
					$viewArticleArray[$i]['url']		= $Rs['url_on']==0 ? $INFO['site_url']."/article/article.php?articleid=".intval($Rs['news_id']) :  $Rs['url'];
					$viewArticleArray[$i]['nidate'] =date("Y-m-d",$Rs['nidate']);
					$i++;
				}
			}

	}
}
//print_r($viewArticleArray);
$tpl->assign("viewArticleArray",      $viewArticleArray);

/**
瀏覽數
**/
$Articleid = $_GET['articleid'];
$Articleid = intval($Articleid);
$DB->query("update  `{$INFO[DBPrefix]}news` set view=view+1 where  news_id='".intval($Articleid)."' ");

$Query   = $DB->query("select nc.ncid,nc.ncname,nc.templatetype,nc.ifcomment,nc.top_id,n.* from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 && n.niffb=1 && n.news_id='".intval($Articleid)."' and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') limit 0,1");
$Num   = $DB->num_rows($Query);
if ( $Num==0 ) {//如果不存在资料
$FUNCTIONS->header_location("../index.php");
}
 if ($Num>0){
 $Result_Article = $DB->fetch_array($Query);
 $Ncateid        = $Result_Article['ncid'];
  $viewnum        = $Result_Article['viewnum'];
 $author        = $Result_Article['author'];
 $nidate        = date("Y-m-d",$Result_Article['nidate']);
 $top_id        = $Result_Article['top_id'];
 $nord        = $Result_Article['nord'];
 $Nurl           = $Result_Article['url'];
 $Nurl_on        = $Result_Article['url_on'];
 $Nclass_name    = $Result_Article['ncname'];
 $Ntitle         = $Result_Article['ntitle'];
 $Nbody          = $Result_Article['nbody'];
 $Brief          = $Result_Article['brief'];
 $Keywords       = $Result_Article['keywords'];
 $templatetype   = $Result_Article['templatetype'];
 $ifcomment      = $Result_Article['ifcomment'];
  $author      = $Result_Article['author'];
  $view      = $Result_Article['view'];
 $Ntitle_first   = $Result_Article['ntitle_color']!="" ? "<font color=".$Result_news['ntitle_color'].">".$Ntitle."</font>" : $Ntitle ;

 $Img            = trim($Result_Article['nimg'])!="" ?  $FUNCTIONS->ImgTypeReturn("/UploadFile/NewsPic",trim($Result_Article['nimg']),'','')   : "";
$Img2            = $Result_Article['nimg']!="" ?  "".$Result_Article['nimg']."" : "";

 $Idate          = date("Y-m-d",$Result_Article['nidate']);
 $Image_single     = $Result_Article['nimg'];
 $Queryall   = $DB->query("select n.news_id,nc.ncid,nc.ncname,n.ntitle,n.nbody,n.ntitle_color,n.nimg,n.nidate,n.brief,n.keywords from `{$INFO[DBPrefix]}news`  n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 && n.niffb=1 && n.top_id='".$Ncateid."' and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') order by  n.nord asc,  n.nidate desc limit 0,9");
 $Numall     = $DB->num_rows($Queryall);
  $i=0;
  while ($Rsall = $DB->fetch_array($Queryall)){
    $Ncatearray[$i]['titleid'] = $Rsall['news_id'];
    $Ncatearray[$i]['title']   = $Rsall['ntitle_color']!="" ? "<font color=".$Rsall['ntitle_color'].">".$Rsall['ntitle']."</font>" : $Rsall['ntitle'];
  $i++;
  }
   $tpl->assign("Ncatearray",      $Ncatearray);         //新闻大类数组
 }
if ($Ncid>0){
	include_once ("article.class.php");
	$Article = new Article_Class;
	$Article->getBanner($Ncateid);   //導航
	$class_banner = array_reverse($class_banner);
}
 $tpl->assign("Image_single",      $Image_single);
$tpl->assign("class_banner",     $class_banner);
 $tpl->assign("Ncateid",      $Ncateid);         //新闻大类ID
 $tpl->assign("Nclass_name",  $Nclass_name);     //新闻大类名称
 $tpl->assign("Ntitle",       $Ntitle);          //新闻标题
 $tpl->assign("Nbody",        $Nbody);           //新闻内容
 $tpl->assign("Ntitle_first", $Ntitle_first);    //新闻标题，带颜色控制的！
 $tpl->assign("Img",          $Img);             //新闻图片
  $tpl->assign("author",      $author);
 $tpl->assign("viewnum",      $viewnum);
 $tpl->assign("nidate",      $nidate);
 $tpl->assign("Img2",          $Img2);             //新闻图片(只有檔名)
 $tpl->assign("Idate",        $Idate);           //新闻日期
 $tpl->assign("Articleid",    $Articleid);
 $tpl->assign("Brief",        $Brief);
 $tpl->assign("Keywords",     $Keywords);
 $tpl->assign("Back_say",     $Article_Pack[Back_say]);            //返回
 $tpl->assign("templatetype",     $templatetype);
 $tpl->assign("ifcomment",     $ifcomment);
  $tpl->assign("Author",      $author);
  $tpl->assign("View",     $view);
 $Sql =  "select ncid,ncname,ncimg,top_id from  `{$INFO[DBPrefix]}nclass`  where  ncatiffb=1 and ncid='" . $Ncateid . "' order by ncatord desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Rs    = $DB->fetch_array($Query);
	$tpl->assign("Top_id",      $Rs['top_id']);         //新闻大类ID
}

$Sql_Up = "select n.news_id,nc.ncid,nc.ncname,n.ntitle,n.nbody,n.ntitle_color,n.nimg,n.nidate from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid )  where  nc.ncatiffb=1 && n.niffb=1 && ((n.news_id<".intval($Articleid)." and n.nord='" . $nord . "') or  n.nord<'" . $nord . "')  &&  nc.ncid  ='".$Ncateid."' and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') order by  n.nord desc  limit 0,1  ";
$Query_Up   = $DB->query($Sql_Up);
$Num_Up   = $DB->num_rows($Query_Up);

 if ( $Num_Up>0 ) {
	$HaveUp    = "Yes";
	 $Rs_Up    = $DB->fetch_array($Query_Up);
	 $HaveUpId = $Rs_Up[news_id];
 }

 $tpl->assign("HaveUp",$HaveUp);
 $tpl->assign("HaveUpId",$HaveUpId);



$Sql_Next = "select n.news_id,nc.ncid,nc.ncname,n.ntitle,n.nbody,n.ntitle_color,n.nimg,n.nidate from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid )  where   nc.ncatiffb=1 && n.niffb=1 && ((n.news_id>".intval($Articleid)." and n.nord='" . $nord . "') or  n.nord>'" . $nord . "') &&  nc.ncid  ='".$Ncateid."' and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') order by  n.nord asc  limit 0,1  ";

$Query_Next   = $DB->query($Sql_Next);
$Num_Next   = $DB->num_rows($Query_Next);

 if ( $Num_Next>0 ) {
	$HaveNext    = "Yes";
	 $Rs_Next    = $DB->fetch_array($Query_Next);
	 $HaveNextId = $Rs_Next[news_id];
 }

 $tpl->assign("HaveNext",$HaveNext);
 $tpl->assign("HaveNextId",$HaveNextId);



if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
   $O = "no";
   $UrltoValue =   trim($Nurl) ;
}else{
   $O = "yes";
   $UrltoValue =   trim($Nurl) ;
}

 $tpl->assign("O",$O);
 $tpl->assign("UrltoValue",base64_encode($UrltoValue));

 //获得新闻列表
$Sql =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and nc.ncid='" . $Ncateid  . "' and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') order by n.nord asc, n.nidate desc ";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i = 0;
	if ($Num>0) {
		$p = array();
		while($Rs=$DB->fetch_array($Query))
		{
			$pic_news[$i]['title'] = $Rs['nltitle'];
			$Nltitle_first  =  $Rs['nltitle_color']!="" ? "<font color=".$Rs['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
			$pic_news[$i]['ltitle'] = $Rs['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Rs['news_id'])."'>".$Nltitle_first."</a>" :  "<a href='".$Rs['url']."'>".$Nltitle_first."</a>";;
			$pic_news[$i]['url'] = $INFO['site_url']."/article/article.php?articleid=".intval($Rs['news_id']);
			//$pic_news[$i]['nimg'] = $INFO['site_url']."/UploadFile/NewsPic/".$Rs['nimg'];
			$pic_news[$i]['nimg'] = $Rs['nimg'];
			$pic_news[$i]['nidate']  = date("Y-m-d",$Rs['nidate']);
			$i++;
		}
	}

//print_r($pic_news);
 $tpl->assign("pic_news",$pic_news);


 // 相关产品

	$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.ifappoint,g.appoint_starttime,g.appoint_endtime,gl.gid from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}news_link` gl  on (g.gid=gl.gid) where g.ifpub=1 and g.checkstate=2 and g.ifpresent!=1 and gl.nid=".$Articleid;

	$Query = $DB->query($Sql);
	$i=1;
	$j=0;
	$abProductArray = array();

	while ($Rs =  $DB->fetch_array($Query)){
		$abProductArray[$j]['autonum']    = $i;
		$abProductArray[$j]['Bgcolor']    = $i%2==0 ?  "#FAFAFA" : 'white';
		$abProductArray[$j]['goodsname']  = $Rs['goodsname'];
		$abProductArray[$j]['gid']        = $Rs['gid'];
		$abProductArray[$j]['price']      = $Rs['price'];
		$abProductArray[$j]['pricedesc']  = $Rs['pricedesc'];
		$abProductArray[$j]['smallimg']   = $Rs['smallimg'];
		$abProductArray[$j]['middleimg']  = $Rs['middleimg'];
		$abProductArray[$j]['intro']      = nl2br($Rs['intro']);

		if ($Rs['ifappoint']==1 && $Rs['appoint_starttime']<=time() && $Rs['appoint_endtime']>=time()){
						$abProductArray[$j]['ifappoint']  = 1;
		}

		if ($Rs['iftimesale']==1 && $Rs['timesale_starttime']<=time() && $Rs['timesale_endtime']>=time()){
			$abProductArray[$j]['pricedesc']  = $Rs['saleoffprice'];
			$abProductArray[$j]['ifsaleoff']  = 1;
		}

		if ($Rs['ifsaleoff']==1 && $Rs['saleoff_starttime']<=time() && $Rs['saleoff_endtime']>=time()){
			$abProductArray[$j]['ifsaleoff']  = 1;
		}

		if($_SESSION['user_id']>0){
			$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$Rs['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
			$collection_Query    = $DB->query($collection_sql);
			$collection_Num   = $DB->num_rows($collection_Query);
			if($collection_Num>0){
				$abProductArray[$j]['heartColor'] = 1;
			}
		}else{
			$abProductArray[$j]['heartColor'] = 0;
		}
		$i++;
		$j++;
	}
	$tpl->assign("abProductArray",      $abProductArray);    //相关产品数组
	$tpl->assign("abProductArrayCount", count($abProductArray));
 $tpl->assign($Article_Pack);
 $tpl->assign($Email_Pack);
 $tag_sql = "select * from `{$INFO[DBPrefix]}article_tag` as at inner join `{$INFO[DBPrefix]}tag` as t on at.tagid = t.tagid where at.news_id='" . intval($Articleid) . "'";
		$Query_tag= $DB->query($tag_sql);
		$ig = 0;
		while($Rs_tag=$DB->fetch_array($Query_tag)){
		  $tag_goods[$ig]['tagid']=$Rs_tag['tagid'];
		  $tag_goods[$ig]['tagname']=$Rs_tag['tagname'];
		  $ig++;
		} $tpl->assign("tag_goods",     $tag_goods);
 $update = "update `{$INFO[DBPrefix]}news` set viewnum=viewnum+1 where news_id=' ". intval($Articleid) . "'";
 $DB->query($update);
//用户品论部分
$Query = $DB->query(" select comment_idate,comment_content,comment_answer from `{$INFO[DBPrefix]}news_comment` where nid=".intval($Articleid)."  limit 0,10 ");
$CommentArray=array();
$i = 0;
while ($Rs =  $DB->fetch_array($Query)){
	$CommentArray[$i]['comment_idate']   = date("Y-m-d H:i a",$Rs['comment_idate']);
	$CommentArray[$i]['comment_content'] = nl2br($Rs['comment_content']);
	$CommentArray[$i]['comment_answer']  = trim($Rs['comment_answer'])!="" ? nl2br($Rs['comment_answer']) : "";//尚未回复评论
	$i++;
}
$tpl->assign("CommentArray",      $CommentArray);    //评论部分数组
$tpl->assign("CommentArrayCount", count($CommentArray));
$tpl->assign("Url",         $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
 if ($Ncateid!=31)
 $_GET['ncid'] = $Ncateid;
include("mobile.class.php");
$mobile_class = new Mobile;

 //echo $top_id;
// if($Ncateid==17 || $top_id==17){
//	 $tpl->display("article_groupon.html");
// }elseif ($_GET['type']=="company")
// 	$tpl->display("article_index2.html");
//elseif ($_GET['type']=="print")
 	//$tpl->display("article_print_index2.html");
// else
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));//耳朵广告开关
 	$tpl->display("article.html");
 ?>