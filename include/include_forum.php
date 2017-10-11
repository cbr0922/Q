<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
$Sql = "select tp.toppost_id,tp.lastmember,tp.lastidate,tp.lastid,tp.lasttitle,fc.catname,tp.topost_title from `{$INFO[DBPrefix]}forum_toppost` as tp inner join `{$INFO[DBPrefix]}forum_class` as fc on tp.topclass_id=fc.bid order by topost_idate desc limit 0,10";
$Sub_NewQuery    = $DB->query($Sql);
$SubNewNum       = $DB->num_rows($Sub_NewQuery);
$forum_array=array();
$i = 0;
while ($ForumRs = $DB->fetch_array($Sub_NewQuery)) {
	$forum_array[$i]['toppost_id'] = $ForumRs['toppost_id'];
	$forum_array[$i]['lastmember'] = $ForumRs['lastmember'];
	$forum_array[$i]['lastidate'] = date("Y-m-d H:i a",$ForumRs['lastidate']);
	$forum_array[$i]['lastid'] = $ForumRs['lastid'];
	$forum_array[$i]['lasttitle'] = $ForumRs['topost_title'];
	$forum_array[$i]['catname'] = $ForumRs['catname'];
	$i++;
}
$tpl->assign("forum_array",$forum_array);
$tpl->display("include_forum.html");
?>
