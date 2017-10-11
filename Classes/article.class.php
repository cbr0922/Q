<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

class Article_Class
{
	var $bid_array;

	function ArticleClassList_Array($Ncid,$Limit){
		global $INFO,$DB;
		$Sql    =  "select n.news_id,n.ntitle,n.nltitle_color,n.url_on,n.news_id,n.nidate,nc.ncname from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and nc.ncid='".intval($Ncid)."' order by n.nord asc, n.nidate desc  ".$Limit." ";
		$Query  =  $DB->query($Sql);
		$Return_array = array();
		$i = 0;
		while ($Rs = $DB->fetch_array($Query)) {
			$Nltitle        =  $Rs['ntitle'];
			$Nltitle_first  =  $Rs['nltitle_color']!="" ? "<font color=".$Rs['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
			$Nltitle_s      =  $Rs['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Rs['news_id'])."'>".$Nltitle_first."</a>" :  "<a href='".$Rs['url']."' target='_blank'>".$Nltitle_first."</a>";
			$Return_array[$i][ntitle]   = $Nltitle_s;
			$Return_array[$i][nidate]   = $Rs['nidate'];
			$Return_array[$i][ncname]   = $Rs['ncname'];
			$Return_array[$i][news_id]  = $Rs['news_id'];
			$i++;
		}
		return $Return_array;
	}
	
	
	function ArticleClass_Array($Ncid){
		global $INFO,$DB;
		//$Sql    =  "select nc.ncid,nc.ncname from `{$INFO[DBPrefix]}nclass` nc  where  nc.ncatiffb=1 and (nc.top_id='".intval($Ncid)."' or nc.ncid='".intval($Ncid)."' ) order by nc.ncatord asc  ".$Limit." ";
        $Sql    =  "select nc.ncid,nc.ncname from `{$INFO[DBPrefix]}nclass` nc  where  nc.ncatiffb=1 and  nc.top_id='".intval($Ncid)."' order by nc.ncatord asc  ";
		$Query  =  $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		$Return_array = array();
		$i = 0;
		while ($Rs = $DB->fetch_array($Query)) {
			$Sql_n =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and (nc.ncid='".$Rs['ncid']."' or nc.top_id='".$Rs['ncid']."' ) order by n.nord asc, n.nidate desc ";
			$Query_n  =  $DB->query($Sql_n);
			$Num_n    = $DB->num_rows($Query_n);
			if($Num_n>0){
				$Return_array[$i][ncid]    =  $Rs['ncid'];
				$Return_array[$i][ncname]  = $Rs['ncname'];
				$j = 0;
				$Sql_1    =  "select nc.ncid,nc.ncname from `{$INFO[DBPrefix]}nclass` nc  where  nc.ncatiffb=1 and  nc.top_id='".intval($Rs['ncid'])."' order by nc.ncatord asc   ";
				$Query_1 =  $DB->query($Sql_1);
				$Num_1    = $DB->num_rows($Query_1);
				$Return_array[$i][num]  = $Num_1;
				while ($Rs_1 = $DB->fetch_array($Query_1)) {
					$Sql_n =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and (nc.ncid='".$Rs_1['ncid']."' or nc.top_id='".$Rs_1['ncid']."' ) order by n.nord asc, n.nidate desc ";
					$Query_n  =  $DB->query($Sql_n);
					$Num_n    = $DB->num_rows($Query_n);
					if($Num_n>0){
						$Return_array[$i]['sub'][$j][ncid]    =  $Rs_1['ncid'];
						$Return_array[$i]['sub'][$j][ncname]  = $Rs_1['ncname'];
						$Sql_2    =  "select nc.ncid,nc.ncname from `{$INFO[DBPrefix]}nclass` nc  where  nc.ncatiffb=1 and  nc.top_id='".intval($Rs_1['ncid'])."' order by nc.ncatord asc   ";
						$Query_2 =  $DB->query($Sql_2);
						$Num_2    = $DB->num_rows($Query_2);
						$Return_array[$i]['sub'][$j][num]  = $Num_2;
						$z = 0;
						while ($Rs_2 = $DB->fetch_array($Query_2)) {
							$Sql_n =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and (nc.ncid='".$Rs_2['ncid']."' or nc.top_id='".$Rs_2['ncid']."' ) order by n.nord asc, n.nidate desc ";
							$Query_n  =  $DB->query($Sql_n);
							$Num_n    = $DB->num_rows($Query_n);
							if($Num_n>0){
								$Return_array[$i]['sub'][$j]['sub'][$z][ncid]    =  $Rs_2['ncid'];
								$Return_array[$i]['sub'][$j]['sub'][$z][ncname]  = $Rs_2['ncname'];
								$z++;
							}
						}
						$j++;
					}
				}
			}
			$i++;
		}
		return $Return_array;
	}
	function Sub_class($id,$bid_array = array())
	{
		global $DB,$INFO;
		$Query  = $DB->query("select ncid from `{$INFO[DBPrefix]}nclass` where top_id=".intval($id)." ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			while($Rs=$DB->fetch_array($Query)){
				$this->bid_array[count($this->bid_array)] = $Rs['ncid'];
				$this->Sub_class($Rs['ncid'],$bid_array);
			}
			//return  $bid_array;
		}else{
			//return $bid_array;
		}

	}
	function getBanner($id){
		global $DB,$INFO,$class_banner,$list;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}nclass` where ncid=".intval($id)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$class_banner[$list]['ncid'] = $Result['ncid'];
			$class_banner[$list]['ncname'] = $Result['ncname'];
			$list++;
			if ($Result['top_id']>0)
				$this->getBanner($Result['top_id']);
		}
	}
}
?>