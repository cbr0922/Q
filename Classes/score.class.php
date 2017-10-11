<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
class SCORE{
	/**
	獲得某商品評分
	**/
	function getProductScore($gid){
		global $DB,$INFO;
		$Sql   =  " select * from `{$INFO[DBPrefix]}score` as s inner join `{$INFO[DBPrefix]}user` as u on s.user_id=u.user_id where s.gid=".intval($gid)." order by s.score_id  desc ";
		$Query =  $DB->query($Sql);
		$Num   =  $DB->num_rows($Query);
		if ($Num>0){
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$OrderList[$i]['content']     = $Rs['content'];
				$OrderList[$i]['answer']     = $Rs['answer'];
				$OrderList[$i]['username'] = substr( $Rs['username'],0,4) . "*****";
				$OrderList[$i]['scoretime'] = date("Y-m-d",$Rs['scoretime']);
				if ($Rs['score1']>0)
					$OrderList[$i]['score1']     = str_repeat("<img src='../images/star.gif' width='11' height='10'>",$Rs['score1']);
				if ($Rs['score1']<5)
					$OrderList[$i]['score2']     = str_repeat("<img src='../images/star0.gif' width='11' height='10'>",5-$Rs['score1']);
				$i++;
			}
		}
		return $OrderList;
	}
}
?>