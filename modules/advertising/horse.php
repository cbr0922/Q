<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
include (RootDocumentShare."/setindex.php");

//---------------<!-- Banner Start -->-----------------------------------------------
$Sql = " select adv_id,adv_title,adv_left_url,title_color from `{$INFO[DBPrefix]}advertising` where adv_type=4 and adv_display = 1 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') order by point_num asc limit 0,10 ";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
if ( $Num > 0 ){
	$i=0;
	$Horse_array = array();
	while ( $Result = $DB->fetch_array($Query)){
		$adv_id       =  $Result['adv_id'];
		$adv_url      =  $Result['adv_left_url'];
		$adv_title    =  $Result['adv_title'];
		$title_color  =  $Result['title_color'];
		if (trim($title_color)!=''){
			$Horse = "<font color='".$title_color."'>".$adv_title."</font>";
		}else{
			$Horse = $adv_title;
		}
		//$Horse_array[$i]['horse'] = "<a href='$adv_url' target=_blank>".$Horse."</a>";
		$Horse_array[$i]['horse'] = $Horse;
		$Horse_array[$i]['url']   = ($adv_url);
		$Horse_array[$i]['adv_id']   = $adv_id;
		$i++;
	}

}


$tpl->assign("Horse_array",  $Horse_array);
$tpl->display("horse.html");



?>