<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");
//活動刊版
if($_GET['name']!=""){
	$Option = " and nc.path='our".$_GET['name']."'";
}elseif (intval($_GET['ncid'])>0){
	$Ncid = intval($_GET['ncid']);
	$Option = " and (nc.ncid=".$Ncid." or nc.top_id='" . intval($Ncid) . "') ";
}else{
	$Option = " and (nc.ncid='1' or nc.top_id='1') ";
}
if($_GET['orderby']=="bytime")
	$orderby = "bytime";
else
	$orderby = "default";

/**
 * 装载翻页函数
*/
include("PageNav_five.class.php");
$Sql =  "select * from  `{$INFO[DBPrefix]}nclass` nc where  nc.ncatiffb=1 ".$Option." ";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
if ( $Num==0 ) {//如果不存在资料
$FUNCTIONS->header_location("../index.php");
}
$Result= $DB->fetch_array($Query);
$templatetype		= $Result['templatetype'];
$template		= $Result['template'];
$NcatName       = $Ncid >0 ?  $Result['ncname'] : "" ;
$top_id		= $Result['top_id'];

$Sql1 =  "select ncname from  `{$INFO[DBPrefix]}nclass` nc where  nc.ncid=".$top_id." ";
$Query1 = $DB->query($Sql1);
$Num1  = $DB->num_rows($Query1);
$Result1= $DB->fetch_array($Query1);
$ncname		= $Result1['ncname'];


$Meta_des		= $Result['meta_des'];
$Meta_key		= $Result['meta_key'];
$Ncimg           = $Result['ncimg'];
$tpl->assign("ncname",       $ncname);
$tpl->assign("top_id",       $top_id);
$tpl->assign("Ncid",       $Ncid);
$tpl->assign("Meta_des",       $Meta_des);
$tpl->assign("Meta_key",       $Meta_key);
$tpl->assign("Ncimg",       $Ncimg);

if($template == 'article_classindex3.html'){
	//$Sql = "select ns.news_id from `{$INFO[DBPrefix]}news` ns where ns.top_id='" . intval($Ncid) . "' and ns.niffb=1";
	$Sql = "select nc.ncid,nc.ncname,n.ntitle,n.nbody,n.ntitle_color,n.url,n.url_on,n.nimg,n.nidate,nc.templatetype,nc.ifcomment,n.news_id from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and (n.top_id='" . intval($Ncid) . "' or nc.top_id='" . intval($Ncid) . "') and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') order by n.nord asc,n.news_id desc limit 0,1";

	$Query = $DB->query($Sql);
	$Result= $DB->fetch_array($Query);
	//print_r($Result);exit;
	echo "<script language='javascript'>location.href='".$INFO['site_url']."/article/article" . $Result['news_id'] . "';</script>";exit;
	echo "<script language='javascript'>location.href='article.php?articleid=" . $Result['news_id'] . "&type=" . $_GET['type'] . "&url=" . urlencode($_SERVER['HTTP_REFERER']) . "';</script>";exit;
}

//获得新闻列表




$month_array = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$Sql =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and (n.pubstarttime='' or n.pubstarttime<='" . time() . "') and (n.pubendtime='' or n.pubendtime>='" . time() . "') and nc.ncatiffb=1 and n.niffb=1 ".$Option." order by n.nord asc, n.nidate desc ";
	$PageNav = new PageItem($Sql,10);
	 $Num     = $PageNav->iTotal;
	 

 
	 
	if ($Num>0){
		$arrRecords = $PageNav->ReadList();
		$i=0;
		$j=1;
		$k=0;
      
        $b=3;

		while ( $NewNav = $DB->fetch_array($arrRecords)){
			
				if($k != $b){
					if($k == 0){
						$Pic_News1[$k]['a']=4;
					}else{
						$Pic_News1[$k]['a']=8;
					}
					$k++;
				}else{
					$Pic_News1[$k]['a']=4;
					$Pic_News1[$k+1]['a']=4;
					$b=$b+4;
					$k=$k+2;
				}
				
			
		
			$Nltitle        =  $NewNav['nltitle'];
			$Nimg            =  $NewNav['nimg'];
			$Nltitle_first  =  $NewNav['nltitle_color']!="" ? "<font color=".$NewNav['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
			$Nltitle_s =  $NewNav['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($NewNav['news_id']). "'><span>".$Nltitle_first."</span></a>" :  "<a href='".$NewNav['url']."'>".$Nltitle_first."</a>";
			$Smalling		="<img src='".$INFO['site_url']."/UploadFile/NewsPic/".$NewNav['smallimg']."' border='0' width='180'/>";
			$Smalling_s =  $NewNav['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($NewNav['news_id'])."'><span>".$Smalling."</span></a>" :  "<a href='".$NewNav['url']."'>".$Smalling."</a>";
			$AllNltitle[$i] = $Nltitle_s;
			$AllNltime[$i]  = $NewNav['nidate'];
$More_s =  $NewNav['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($NewNav['news_id']). "'><button type=button' class='btn btn-white btn-sm'> 閱讀更多 </button></a>" :  "<a href='".$NewNav['url']."'><button type=button' class='btn btn-white btn-sm'> 閱讀更多 </button></a>";
			$Pic_News1[$i]['niffb'] = $NewNav['niffb'];
			$Pic_News1[$i]['url']	= $NewNav['url_on']==0 ? "".$INFO['site_url']."/article/article".intval($NewNav['news_id']) :  "".$NewNav['url']."";
			$Pic_News1[$i]['news_id']	= $NewNav['news_id']; 		//文章id
			$Pic_News1[$i]['title']		= $NewNav['ntitle'];  		//大標題
			$Pic_News1[$i]['ltitle']	= $Nltitle_s;  		  		//小標題+顏色+連結
			$Pic_News1[$i]['nltitle']	= $Nltitle_first;  		  		//小標題+顏色
			$Pic_News1[$i]['brief']		= $Char_class->cut_str($NewNav['brief'],90,0,'UTF-8');   		//文章簡要
			$Pic_News1[$i]['nimg']		= $NewNav['smallimg']; 		//圖片含路徑
			$Pic_News1[$i]['smallimg']		= $Nimg; 		//圖片
			$Pic_News1[$i]['nimg1']		= $NewNav['nimg1']; 
			$Pic_News1[$i]['urlnimg']	= $Smalling_s;  			//圖片+連結
			$Pic_News1[$i]['mores']	= $More_s;  			//了解更多+連結
			$Pic_News1[$i]['pubdate'] = $NewNav['pubdate'] == "0000-00-00" ? date("Y-m-d",$NewNav['nidate']): $NewNav['pubdate'];
			$Pic_News1[$i]['nbody']    = $NewNav['nbody'];
			$Pic_News1[$i]['author']	= $NewNav['author'];

			$pubdate = $Pic_News1[$i]['pubdate'];
	    $pubdate_array = explode("-",$pubdate);
	    $Pic_News1[$i]['nidate']	= $pubdate_array[0]. "  " .$month_array[$pubdate_array[1]-1] ;
	    $Pic_News1[$i]['nidated']=$pubdate_array[2];
			$Pic_News1[$i]['view']  = $NewNav['view'];
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
//}

$tpl->assign('pic_news',$Pic_News1);
//print_r($Pic_News1);
$tpl->assign("NcatName",       $NcatName);

$tpl->assign("Path",       $Path);
$tpl->assign($Article_Pack);
$tpl->assign("orderby",       $orderby);
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));//耳朵广告开关
if($template == '' || $template == 'article_classindex8.html'){
	$tpl->display("article_classindex1.html");
}else{
	$tpl->display($template);
}

?>
