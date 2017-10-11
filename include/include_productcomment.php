<?php
error_reporting(7);
include( dirname( __FILE__ )."/"."../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
@header("Content-type: text/html; charset=utf-8");
$Goods_id  = $_GET['goods_id'];  //判断是否有正常的ID进入
$Goods_id  = intval($Goods_id);
//  这里是当有用户发表评论的时候发生的操作!!!
if ($_POST['action']='SubmitComment' && !empty($_POST['Goods_id'])){
	if ($_SESSION['user_id'] != "" && $_SESSION['username']){
		$Result = $DB->query(" insert into `{$INFO[DBPrefix]}good_comment` (good_id,comment_content,comment_idate) values ('".intval($_POST['Goods_id'])."','".$_POST['content']."','".time()."')" );
		if ($Result){
			echo "<script language='javascript'>alert('您的問題已送出，等待店長回覆');location.href='" . $_POST['Url'] . "';</script>";
			exit;
		}
	}
	else{
		echo "<script language='javascript'>alert('".$Good[PleaseLoginFirst]."');location.href='" . $_POST['Url'] . "';</script>";
		exit;
	}
}
$tpl->assign("Url",         $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);    //URL
//用户品论部分
$Query = $DB->query(" select comment_idate,comment_content,comment_answer from `{$INFO[DBPrefix]}good_comment` where good_id=".$Goods_id." and comment_answer<>'' and ifcheck=1 order by comment_idate desc limit 0,10 ");
$CommentArray=array();
$i = 0;
while ($Rs =  $DB->fetch_array($Query)){
	$CommentArray[$i]['comment_idate']   = date("Y-m-d H:i a",$Rs['comment_idate']);
	$CommentArray[$i]['comment_content'] = nl2br($Rs['comment_content']);
	$CommentArray[$i]['comment_answer']  = trim($Rs['comment_answer'])!="" ? nl2br($Rs['comment_answer']) : "";//尚未回复评论
	$i++;
}
$tpl->assign($Good);
$tpl->assign("CommentArray",      $CommentArray);    //评论部分数组
$tpl->assign("Goods_id",      $Goods_id);    //评论部分数组
$tpl->display("include_productcomment.html");


?>
