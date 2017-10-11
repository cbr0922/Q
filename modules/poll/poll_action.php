<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../../configs.inc.php" );
include("global.php");
include RootDocument."/language/".$INFO['IS']."/Poll_Pack.php";

$Poll_id     = intval($_POST['Poll_id']);

if ($_POST['action']=='up' && !empty($_POST['subpoll_id'])){

	$subpoll_id  = intval($_POST['subpoll_id']);
	/*
	$Sql   = " select points from poll_option where subpoll_id=".intval($subpoll_id)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
	$Rs = $DB->fetch_array($Query);
	$Poll_num = $Rs['points'];
	*/
	//$Update = "update poll_option set points=".intval($Poll_num+1)." limit 0,1";
	$Update = $DB->query("update `{$INFO[DBPrefix]}poll_option` set points=points+1 where subpoll_id=".intval($subpoll_id) );

	// }
}


unset($Sql);
unset($Query);
unset($Rs);
unset($Poll_num);
unset($Update);

//获得总的投票数字
$Sql = "select sum(points) as total from `{$INFO[DBPrefix]}poll_option` where poll_id=$Poll_id";
$Query = $DB->query($Sql);
$Rs =  $DB->fetch_array($Query);
$Total = $Rs[total];

unset($Sql);
unset($Query);
unset($Rs);


$Sql = "select p.title,p.type,po.subpoll_id,po.subtitle,po.points from `{$INFO[DBPrefix]}poll` p inner join `{$INFO[DBPrefix]}poll_option`  po on (p.poll_id=po.poll_id) where p.open=1  and  p.poll_id=$Poll_id order by subpoll_id asc";
$query = $DB->query($Sql);
$num   = $DB->num_rows($query);

if( $num > 0 )
{
	$i = 0;
	$P_points=0;
	$Poll_view = array();
	while ($Rs = $DB->fetch_array($query))
	{
		$Poll_title = $Rs['title'];  //取出投票标题；
		$Poll_view[$i]['subtitle']         =  $Rs['subtitle'];
		$Poll_view[$i]['points']           =  intval($Rs['points']);
		if ($Rs['points']>0){
			$Poll_view[$i]['pollpercent']   =  round(intval($Rs['points'])/intval($Total)*100,1);
			$Poll_view[$i]['pollwidth']     =  intval($Rs['points'])/intval($Total);
		}
		$i++;
	}
}

$tpl->assign("Total",$Total);
$tpl->assign("Poll_title",$Poll_title);
$tpl->assign("Poll_view",$Poll_view);
$tpl->assign($Poll_Pack);
$tpl->display("poll_view.html");
?>
