<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载产品语言包
 */
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";



$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}user` where user_id=".$_SESSION['user_id']." limit 0,1");
$Num    = $DB->num_rows($Query);
if ($Num==0){
	$FUNCTIONS->sorry_back("back","");
}
$Result = $DB->fetch_array($Query);


$tpl->assign("Sessid",            $_SESSION['user_id']); //会员ID
$tpl->assign("Username",          $Result['username']); //用户名
$tpl->assign("Email",             $Result['email']); //E-mail地址
$tpl->assign("True_name",         $Result['true_name']); //真实姓名
$tpl->assign("Sex",               $Result['sex']); //性别
$tpl->assign("city",    $Result['city']); //省/市[gb]
$tpl->assign("canton",        $Result['canton']); //市县
$tpl->assign("Country",        $Result['Country']); //市县
$tpl->assign("othercity_value",   $Result['zip']); //其他地区
$tpl->assign("Address",           $Result['addr']); //联系地址
$tpl->assign("Area",              $Result['city'].$Result['canton']); //地区名称
$tpl->assign("Mobile",            $Result['other_tel']); //移动电话
$tpl->assign("Phone",             $Result['tel']); //固定电话
$tpl->assign("Post",              $Result['post']); //邮政编码
$tpl->assign("certcode",              $Result['certcode']); //邮政编码
$tpl->assign("dianzibao",              $Result['dianzibao']);
$tpl->assign("nickname",              $Result['nickname']);
$tpl->assign("pic",              $Result['pic']);
$tpl->assign("msn",              $Result['msn']);
$tpl->assign("blog",              $Result['blog']);
$schoolname     =  $Result['schoolname'];
		$chenghu     =  $Result['chenghu'];
		$tpl->assign("schoolname",              $Result['schoolname']); //邮政编码
		$tpl->assign("chenghu",              $Result['chenghu']); //邮政编码

$Sql      = "select u.*  from `{$INFO[DBPrefix]}company` u where u.id ='" . $Result['companyid'] . "'  order by u.id asc limit 0,1";
	$Query    = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
$tpl->assign("companyname",              $Rs['companyname']); //邮政编码


//echo intval(substr($Result['born_date'],5,6));
$Born_year = "\n";
$Born_year="";
for ($i=date("Y",time())-60;$i<=date("Y",time())-1;$i++){
	$Born_year .= "<option value=".$i." ";
	if (intval(substr($Result['born_date'],0,4))==$i){
		$Born_year .= " selected ";
	}
	$Born_year .= " > ".$i."</option>\n";
}


$Born_month = "\n";
$Born_month .= " <SELECT name=bmonth>";
for ($i=1;$i<=12;$i++){
	$Born_month .= "<option value=".$i."" ;
	if (intval(substr($Result['born_date'],5,6))==$i){
		$Born_month .= " selected ";
	}
	$Born_month .= " >".$i."</option>";
}
$Born_month .=" </SELECT> ";

$Born_day = "\n";
$Born_day .= " <SELECT name=bday>";
for ($j=1;$j<=31;$j++){
	$Born_day .= "<option value=".$j."" ;
	if (intval(substr($Result['born_date'],7,8))==$j){
		$Born_day .= " selected ";
	}
	$Born_day .= " >".$j."</option>";
}
$Born_day .=" </SELECT> ";

$tpl->assign("Born_year",         $Born_year); //出生年
$tpl->assign("Born_month",        $Born_month); //出生日期月
$tpl->assign("Born_day",          $Born_day); //出生日期日
//$tpl->assign("Born_year",         intval(substr($Result['born_date'],0,4))); //出生日期年
//$tpl->assign("Born_month",        intval(substr($Result['born_date'],5,6))); //出生日期月
//$tpl->assign("Born_day",          intval(substr($Result['born_date'],7,8))); //出生日期日

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Good);
$tpl->display("AccountInformation_".VersionArea.".html");
?>

  