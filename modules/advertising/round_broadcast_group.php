<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");


//---------------<!-- Banner Start -->-----------------------------------------------
$Sql = " select adv_id,adv_title,adv_left_url,title_color from `{$INFO[DBPrefix]}advertising` where adv_type=13 and adv_display = 1 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') order by point_num asc limit 0,3 ";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
if ( $Num > 0 ){
	$i=0;
	$Round_array = array();
	while ( $Result = $DB->fetch_array($Query)){
		$adv_id       =  $Result['adv_id'];
		$point_num    =  $Result['point_num'];
		$adv_url      =  $Result['adv_left_url'];
		$adv_title    =  $Result['adv_title'];
		$title_color  =  $Result['title_color'];
		if (trim($title_color)!=''){
			$Round = "<font color='".$title_color."'>".$adv_title."</font>";
		}else{
			$Round = $adv_title;
		}
		if ($adv_url!="")
			$Round_array[$i]['round'] = "<a href='" . $INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$adv_id  . "&url=" .urlencode($adv_url) . "'>".$Round."</a>";
		else
			$Round_array[$i]['round'] = $Round;
		//$Round_array[$i]['point_num'] = $point_num;
		$DB->query("update `{$INFO[DBPrefix]}advertising` set point_num=point_num+1 where adv_id=".intval($adv_id));
		$i++;
	}

}



$tpl->assign("Round_array",  $Round_array); //跑马灯
$tpl->display("round_broadcast.html");

//---------------<!-- Banner End -->-----------------------------------------------

?>
