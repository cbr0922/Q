<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../../configs.inc.php" );
include("global.php");

include RootDocument."/language/".$INFO['IS']."/Poll_Pack.php";

if (!empty($_GET[Poll_id])){
	$pollid = intval($_GET[Poll_id]);
}else{
	$pollid = 1;
}

$pollblank = "&nbsp;&nbsp;";

$Sql = "select p.title,p.type,po.subpoll_id,po.subtitle,po.points from `{$INFO[DBPrefix]}poll` p inner join `{$INFO[DBPrefix]}poll_option`  po on (p.poll_id=po.poll_id) where p.open=1  and  p.poll_id=$pollid order by subpoll_id asc";
$query = $DB->query($Sql);
$num   = $DB->num_rows($query);

if( $num > 0 )
{
	$i = 0;
	$Poll = array();
	while ($Rs = $DB->fetch_array($query))
	{
		$P_type = intval($Rs[$i]['type']);

		$Poll_title = $Rs['title'];  //取出投票标题；



		//以下取出投票选择各项目
		switch($P_type)
		{
			case "0":
				$Poll[$i]['option'] = "<input type=radio name='subpoll_id' value=\"".$Rs["subpoll_id"]."\">".$pollblank.$Rs["subtitle"]."";
				break;

			default:
				$Poll[$i]['option'] = "<input type=radio name='subpoll_id' value=\"".$Rs["subpoll_id"]."\">".$pollblank.$Rs["subtitle"]."";
				break;
		}

		$i++;
	}

}

$tpl->assign("Poll_id",$pollid);
$tpl->assign("Poll_title",$Poll_title);
$tpl->assign("Poll",$Poll);
$tpl->assign("SubmitPoll",$Basic_Command[Submit]);  //提交
//echo $Poll_Pack[ConSubmit];
$tpl->assign($Poll_Pack);
$tpl->display("poll.html");


 ?>
