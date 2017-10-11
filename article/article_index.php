<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");

$ncid = $_GET['ncid'];
$ncid2_array = array("86","83","89","90","85","91");
$ncid_array = array("36","75","2","16","37","32");
foreach($ncid_array as $k=>$v){
	if($ncid == $v){
		$ncid1 = $ncid2_array[$k];
	}
}

$Query   = $DB->query("select * from  `{$INFO[DBPrefix]}nclass` where  ncatiffb=1 && ncid='".intval($ncid)."'  limit 0,1");
$Num   = $DB->num_rows($Query);
$Result_Article = $DB->fetch_array($Query);
 $Nclass_name    = $Result_Article['ncname'];

$Pic_News1 = array();
$Sql =  "select ncid,ncname,ncimg,top_id from  `{$INFO[DBPrefix]}nclass`  where  ncatiffb=1 and top_id='" . $ncid1 . "' order by ncatord desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$i=0;
	while ( $Rs = $DB->fetch_array($Query)){
		$Pic_News1[$i]['ncid'] = $Rs['ncid'];
		$Pic_News1[$i]['ncname'] = $Rs['ncname'];
		$Sql_n =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and n.top_id='" . $Rs['ncid'] . "' order by n.nord asc, n.nidate desc limit 0,6 ";
		$Query_n = $DB->query($Sql_n);
		$j=0;
		while ( $NewNav = $DB->fetch_array($Query_n)){
			$Nltitle        =  $NewNav['nltitle'];
			$Nltitle_first  =  $NewNav['nltitle_color']!="" ? "<font color=".$NewNav['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
			$Pic_News1[$i]['news'][$j]['news_id']	= $NewNav['news_id'];
			$Pic_News1[$i]['news'][$j]['title']		= $NewNav['ntitle'];
			$Pic_News1[$i]['news'][$j]['ltitle']	= $Nltitle_first;
			$Pic_News1[$i]['news'][$j]['brief']		= $Char_class->cut_str($NewNav['brief'],40,0,'UTF-8');
			$Pic_News1[$i]['news'][$j]['nimg']		= $NewNav['smallimg'];
			$Pic_News1[$i]['news'][$j]['videoid']		= $NewNav['videoid'];
			if($j==0){
				$Sql_g   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,gl.gid,g.sale_name from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}news_link` gl  on (g.gid=gl.gid) where g.ifpub=1 and g.ifpresent!=1 and gl.nid=".$NewNav['news_id'] . " limit 0,4";

				$Query_g = $DB->query($Sql_g);
				$z = 0;
				while ($Rs_g =  $DB->fetch_array($Query_g)){
					$Pic_News1[$i]['goods'][$z]['goodsname']  = $Rs_g['goodsname'];
					$Pic_News1[$i]['goods'][$z]['gid']        = $Rs_g['gid'];
					$Pic_News1[$i]['goods'][$z]['price']      = $Rs_g['price'];
					$Pic_News1[$i]['goods'][$z]['pricedesc']  = $Rs_g['pricedesc'];
					$Pic_News1[$i]['goods'][$z]['smallimg']   = $Rs_g['smallimg'];
					$Pic_News1[$i]['goods'][$z]['middleimg']  = $Rs_g['middleimg'];
					$Pic_News1[$i]['goods'][$z]['sale_name']  = $Rs_g['sale_name'];
					$Pic_News1[$i]['goods'][$z]['intro']      = nl2br($Rs_g['intro']);
					$z++;
				}
			}
			//echo $i . $j ."<br>";
			//print_r($Pic_News1[$i]['news'][$j]['goods']);
		 	 $j++;
		}
		$i++;

	}
}
//}

$tpl->assign('pic_news',$Pic_News1);
$Pic_News1 = array();
$Sql =  "select ncid,ncname,ncimg,top_id from  `{$INFO[DBPrefix]}nclass`  where  ncatiffb=1 and top_id='" . $ncid . "' order by ncatord desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$i=0;
	while ( $Rs = $DB->fetch_array($Query)){
		$Pic_News1[$i]['ncid'] = $Rs['ncid'];
		$Pic_News1[$i]['ncname'] = $Rs['ncname'];
		$Sql_n =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and n.top_id='" . $Rs['ncid'] . "' order by n.nord asc, n.nidate desc limit 0,6 ";
		$Query_n = $DB->query($Sql_n);		$Num_n   =  $DB->num_rows($Query_n);		$Pic_News1[$i]['count'] = $Num_n;
		$j=0;
		while ( $NewNav = $DB->fetch_array($Query_n)){
			$Nltitle        =  $NewNav['nltitle'];
			$Nltitle_first  =  $NewNav['nltitle_color']!="" ? "<font color=".$NewNav['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
			$Pic_News1[$i]['news'][$j]['news_id']	= $NewNav['news_id'];
			$Pic_News1[$i]['news'][$j]['title']		= $NewNav['ntitle'];
			$Pic_News1[$i]['news'][$j]['author']	= $NewNav['author'];
			$Pic_News1[$i]['news'][$j]['ltitle']	= $Nltitle_first;
			$Pic_News1[$i]['news'][$j]['brief']		= $Char_class->cut_str($NewNav['brief'],40,0,'UTF-8');
			$Pic_News1[$i]['news'][$j]['nimg']		= $NewNav['smallimg'];
			$Pic_News1[$i]['news'][$j]['videoid']		= $NewNav['videoid'];
		 	 $j++;
		}
		$i++;

	}
}
// facebook連結
$info_id = '33';
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='33'");
while ($Result  = $DB->fetch_array($Query)){
  if ($Result[info_id]==33){
	$tpl->assign("facebook_info",$Result[info_content]);
  }
}
$tpl->assign("news_array",       $Pic_News1);
$tpl->assign("Nclass_name",       $Nclass_name);
//print_r($Pic_News1);
$tpl->assign("NcatName",       $NcatName);
$tpl->assign($Article_Pack);
$tpl->display("article_index.html");?>
