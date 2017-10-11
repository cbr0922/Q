<?php
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
include ("../configs.inc.php");
include ("global.php");
include_once 'crypt.class.php';

if ($_POST['action']=="save"){
	/*if ($_SESSION['code']!=trim($_POST['verify'])){
		$_SESSION['code']="";
		echo "<script language='javascript'>alert('驗證碼錯誤');history.back(-1);</script>";
		exit;
	}*/
	include("securimage.php");
	$img=new Securimage();
	$valid=$img->check($_POST['inputcode']);
	if($valid==false) {
	 	$FUNCTIONS->sorry_back("back","驗證碼錯誤");
	}
	$Result = $DB->query(" insert into `{$INFO[DBPrefix]}message` (username,email,comment_content,comment_idate,tel,user_id) values ('".$_POST['username']."','".$_POST['email']."','".$_POST['content']."','".time()."','".$_POST['tel']."','".intval($_SESSION['user_id'])."')" );
	echo "<script language='javascript'>alert('您的留言已送出，等待店長回覆');location.href='message.php';</script>";
	exit;
}else{
	include("PageNav.class.php");
	$Sql = " select * from `{$INFO[DBPrefix]}message` where comment_answer<>'' order by comment_idate desc";
	$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
	$Num        = $PageNav->iTotal;
	$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条
	$CommentArray=array();
	$i = 0;
	if ($Num>0){
		$arrRecords = $PageNav->ReadList();
		while ($Rs = $DB->fetch_array($arrRecords)){
			$CommentArray[$i]['comment_idate']   = date("Y-m-d H:i a",$Rs['comment_idate']);
			$CommentArray[$i]['email'] = $Rs['email'];
			$CommentArray[$i]['tel'] = MD5Crypt::Decrypt($Rs['tel'], $INFO['tcrypt']);
			if($Rs['user_id'] == intval($_SESSION['user_id']) || $Rs['user_id'] == 0){
				$CommentArray[$i]['comment_content'] = nl2br($Rs['comment_content']);
				$CommentArray[$i]['username'] = $Rs['username'];
				$CommentArray[$i]['comment_answer']  = trim($Rs['comment_answer'])!="" ? nl2br($Rs['comment_answer']) : "";//尚未回复评论
			}else{
				$CommentArray[$i]['comment_content'] = mb_substr(nl2br($Rs['comment_content']),0,3,"utf-8")."************************";
				$CommentArray[$i]['username'] = mb_substr($Rs['username'],0,1,"utf-8")."**";
				$CommentArray[$i]['comment_answer']  = trim($Rs['comment_answer'])!="" ? "**** 這是悄悄話，需留言本人登入會員後方能看全文 ****" : "";//尚未回复评论
			}
			$i++;
		}
	}

	$tpl->assign("CommentArray",      $CommentArray);    //评论部分数组

	if (intval($_SESSION['user_id'])>0){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1 ");
		$Num   =  $DB->num_rows($Query);
		if ( $Num > 0 ){
			$Rs  = $DB->fetch_array($Query);
			$email       = $Rs['email'];
			$true_name   = $Rs['true_name'];
			$tel   = MD5Crypt::Decrypt($Rs['tel'], $INFO['tcrypt']);
			$tpl->assign("emails",         $email);
			$tpl->assign("tels",         $tel);
			$tpl->assign("true_name",     $true_name);
		}
	}
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("sid",        time());
$tpl->display("message.html");
?>
