<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");

if (intval($_GET['ncid'])>0){
	$Ncid = intval($_GET['ncid']);
	$Option = " and nc.ncid=".$Ncid." ";
}
if ($_GET['skey']!=""){
	$Option = " and (n.nltitle like '%".$_GET['skey']."%' or  n.ntitle like '%".$_GET['skey']."%')";
}

/**

 * 装载翻页函数

*/

include("PageNav.class.php");

$Sql =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 ".$Option." order by n.nord asc, n.nidate desc ";

$Query = $DB->query($Sql);
$Result= $DB->fetch_array($Query);
$templatetype		= $Result['templatetype'];
$NcatName       = $Ncid >0 ?  $Result['ncname'] : "" ;
$Meta_des		= $Result['meta_des'];
$Meta_key		= $Result['meta_key'];


$tpl->assign("News_des",       $Meta_des);
$tpl->assign("News_key",       $Meta_key);



$month_array = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

$Sql =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 ".$Option." order by n.nord asc, n.nidate desc ";

	$PageNav = new PageItem($Sql,10);

	 $Num     = $PageNav->iTotal;

	if ($Num>0){

		$arrRecords = $PageNav->ReadList();

		$i=0;

		$j=1;

		while ( $NewNav = $DB->fetch_array($arrRecords)){
		
		
		
			$Nltitle        =  $NewNav['nltitle'];
			$Nimg            =  $NewNav['nimg'];

			$Nltitle_first  =  $NewNav['nltitle_color']!="" ? "<font color=".$NewNav['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;

			$Nltitle_s =  $NewNav['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($NewNav['news_id']). "'><span>".$Nltitle_first."</span></a>" :  "<a href='".$NewNav['url']."'>".$Nltitle_first."</a>";
			
			$Smalling		="<img src='".$INFO['site_url']."/UploadFile/NewsPic/".$NewNav['smallimg']."' border='0' width='180'/>";
			
			$Smalling_s =  $NewNav['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($NewNav['news_id'])."'><span>".$Smalling."</span></a>" :  "<a href='".$NewNav['url']."'>".$Smalling."</a>";
										
			$AllNltitle[$i] = $Nltitle_s; 

			$AllNltime[$i]  = $NewNav['nidate'];
			
$More_s =  $NewNav['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article".intval($NewNav['news_id']). "'><button type=button' class='btn btn-white btn-sm'> 閱讀更多 </button></a>" :  "<a href='".$NewNav['url']."'><button type=button' class='btn btn-white btn-sm'> 閱讀更多 </button></a>";
		 

			$Pic_News1[$i]['url']	= $NewNav['url_on']==0 ? "".$INFO['site_url']."/article/article".intval($NewNav['news_id']) :  "".$NewNav['url']."";

			$Pic_News1[$i]['news_id']	= $NewNav['news_id']; 		//文章id
			$Pic_News1[$i]['title']		= $NewNav['ntitle'];  		//大標題
			$Pic_News1[$i]['ltitle']	= $Nltitle_s;  		  		//小標題+顏色+連結
			$Pic_News1[$i]['brief']		= $Char_class->cut_str($NewNav['brief'],90,0,'UTF-8');   		//文章簡要
			$Pic_News1[$i]['nimg']		= $NewNav['smallimg']; 		//圖片含路徑
			$Pic_News1[$i]['smallimg']		= $Nimg; 		//圖片
			$Pic_News1[$i]['urlnimg']	= $Smalling_s;  			//圖片+連結
			
			$Pic_News1[$i]['mores']	= $More_s;  			//了解更多+連結
			$Pic_News1[$i]['pubdate']		= $NewNav['pubdate'];
			$Pic_News1[$i]['nbody']    = $NewNav['nbody'];
			$Pic_News1[$i]['author']	= $NewNav['author'];
			
			
			$pubdate = $NewNav['pubdate'];

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



$tpl->display("article_search_index.html");





?>













