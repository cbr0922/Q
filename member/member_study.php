<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
include_once "../FCKeditor/fckeditor.php";

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";

if ($_GET['type'] == "mod" && intval($_GET['id'])>0){
	$Sql = "select s.*,t.name as typename from `{$INFO[DBPrefix]}study` as s left join `{$INFO[DBPrefix]}studytype` as t on s.type=t.id where s.user_id=".intval($_SESSION['user_id'])." and s.id='" . intval($_GET['id']) . "' order by s.id desc";
	$Query =  $DB->query($Sql);
	$Num   =  $DB->num_rows($Query);
	if ($Num>0){
		$Rs = $DB->fetch_array($Query);
		$tpl->assign("study_id" , $Rs['id']);
		$tpl->assign("study_title" , $Rs['title']);
		$tpl->assign("study_type" , $Rs['type']);
		$type = $Rs['type'];
		$tpl->assign("study_hiddenschool" , $Rs['hiddenschool']);
		$tpl->assign("study_hiddenname" , $Rs['hiddenname']);
		$tpl->assign("study_content" , $Rs['content']);
		$tpl->assign("study_filename" , $Rs['filename']);
		$tpl->assign("ifpub" , $Rs['ifpub']);
		$tpl->assign("pubdate" , $Rs['pubdate']);
	}
	$tpl->assign("study_add_say" , "編輯教學資料");
}else if ($_GET['type'] == "del"){
	$Result =  $DB->query("delete from `{$INFO[DBPrefix]}study` where id=".intval($_GET['id']));
	$FUNCTIONS->header_location('member_study_list.php');
}else{
	$tpl->assign("study_add_say" , "新增教學資料");	
}
if ($_POST['type'] == "save"){
	if ($_POST['op_type'] == "add"){
		$filename   = $FUNCTIONS->Upload_AllFile($_FILES['upfile']['name'],$_FILES['upfile']['tmp_name'],'',"../studyfile");
		$db_string = $DB->compile_db_insert_string( array (
		'title'          => trim($_POST['title']),
		'type'          => intval(trim($_POST['studytype'])),
		'hiddenschool'          => intval(trim($_POST['hiddenschool'])),
		'hiddenname'          => intval(trim($_POST['hiddenname'])),
		'content'          => trim($_POST['content']),
		'ifpub'          => intval(trim($_POST['ifpub'])),
		'pubdate'          => time(),
		'user_id'          => intval($_SESSION['user_id']),
		'filename'          => $filename,
		'checked'         => 0,
		));
		$Sql="INSERT INTO `{$INFO[DBPrefix]}study` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}
	if ($_POST['op_type'] == "mod"){
		$filename   = $FUNCTIONS->Upload_AllFile($_FILES['upfile']['name'],$_FILES['upfile']['tmp_name'],$_POST['Old_file'],"../studyfile");
		$db_string = $DB->compile_db_update_string( array (
		'title'          => trim($_POST['title']),
		'type'          => intval(trim($_POST['studytype'])),
		'hiddenschool'          => intval(trim($_POST['hiddenschool'])),
		'hiddenname'          => intval(trim($_POST['hiddenname'])),
		'content'          => trim($_POST['content']),
		'ifpub'          => intval(trim($_POST['ifpub'])),
		'filename'          => $filename,
		));
		$Sql = "UPDATE `{$INFO[DBPrefix]}study` SET $db_string WHERE id=".intval($_POST['id']);
		$Result_Insert=$DB->query($Sql);
	}
	if ($Result_Insert)
	{
		$FUNCTIONS->header_location('member_study_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[YanZhengIsBad_say]);
	}
}
$Sql      = "select * from `{$INFO[DBPrefix]}studytype`  order by id  ";
$Query    = $DB->query($Sql);
while ($Rs=$DB->fetch_array($Query)) {
	if ($Rs['id'] == $type)
		$check = "checked";
	else
		$check = "";
	$OptionSelect_kefu .=  "<option value='" . $Rs['id'] . "' " . $check . ">" . $Rs['name'] . "</option>";
}
$tpl->assign("OptionSelect_kefu" , $OptionSelect_kefu);
$tpl->assign("op_type" , $_GET['type']);
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("member_study.html");
?>