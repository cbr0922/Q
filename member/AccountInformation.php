<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");include_once 'crypt.class.php';
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

$grouppoint =$FUNCTIONS->Grouppoint(intval($Result['user_id']));

$tpl->assign("grouppoint",            $grouppoint);

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
$tpl->assign("Mobile",              MD5Crypt::Decrypt ($Result['other_tel'], $INFO['mcrypt'] )); //移动电话
$tpl->assign("Phone",             MD5Crypt::Decrypt ($Result['tel'], $INFO['tcrypt'] )); //固定电话
$tpl->assign("Post",              $Result['post']); //邮政编码
$tpl->assign("certcode",              $Result['certcode']); //邮政编码
$tpl->assign("dianzibao",              $Result['dianzibao']);
$tpl->assign("nickname",              $Result['nickname']);
$tpl->assign("pic",              $Result['pic']);
$tpl->assign("msn",              $Result['msn']);
$tpl->assign("blog",              $Result['blog']);
$tpl->assign("membercode",              $Result['memberno']);
$tpl->assign("ifupdate",              $Result['ifupdate']);
$cn_secondname   = $Result['cn_secondname'];
$en_firstname   = $Result['en_firstname'];
$en_secondname   = $Result['en_secondname'];
$bornCountry   = $Result['bornCountry'];
$tpl->assign("cn_secondname",         $cn_secondname);
$tpl->assign("en_firstname",         $en_firstname);
$tpl->assign("en_secondname",         $en_secondname);
$tpl->assign("bornCountry",         $bornCountry);
$schoolname     =  $Result['schoolname'];
$chenghu     =  $Result['chenghu'];
$tpl->assign("schoolname",              $Result['schoolname']); //邮政编码
$tpl->assign("chenghu",              $Result['chenghu']); //邮政编码

$Sql      = "select u.*  from `{$INFO[DBPrefix]}saler` u where u.id ='" . $Result['companyid'] . "'  order by u.id asc limit 0,1";
	$Query    = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
$tpl->assign("companyname",              $Rs['name']); //邮政编码


//echo intval(substr($Result['born_date'],5,6));
$dateb = explode("-",$Result['born_date']);
$Born_year = "\n";
$disabled="";
$disabled=$dateb[0]!=""?'disabled="disabled"':"";
$Born_year="<SELECT name='byear' class='form-control select' " . $disabled . "><option value=''>請選擇</option>";
for ($i=1930;$i<=date("Y",time());$i++){
	$Born_year .= "<option value=".$i." ";
	if (intval(substr($Result['born_date'],0,4))==$i){
		$Born_year .= " selected ";
	}
	$Born_year .= " > ".$i."(民國" . ($i-1911) . "年)</option>\n";
}
$Born_year .=" </SELECT> ";

$Born_month = "\n";
$disabled=$dateb[1]!=""?'disabled="disabled"':"";
$Born_month .= " <SELECT name='bmonth' class='form-control select' " . $disabled . "><option value=''>請選擇</option>";
for ($i=1;$i<=12;$i++){
	$Born_month .= "<option value=".$i."" ;
	if ($dateb[1]==$i){
		$Born_month .= " selected ";
	}
	$Born_month .= " >".$i."月</option>";
}
$Born_month .=" </SELECT> ";

$Born_day = "\n";
$disabled="";
$disabled=$dateb[2]!=""?'disabled="disabled"':"";
$Born_day .= " <SELECT name='bday' class='form-control select' " . $disabled . "><option value=''>請選擇</option>";
for ($j=1;$j<=31;$j++){
	$Born_day .= "<option value=".$j."" ;
	if ($dateb[2]==$j){
		$Born_day .= " selected ";
	}
	$Born_day .= " >".$j."日</option>";
}
$Born_day .=" </SELECT> ";

$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("Born_year",         $Born_year); //出生年
$tpl->assign("Born_month",        $Born_month); //出生日期月
$tpl->assign("Born_day",          $Born_day); //出生日期日
$tpl->assign("born_date",          $born_date);
//$tpl->assign("Born_year",         intval(substr($Result['born_date'],0,4))); //出生日期年
//$tpl->assign("Born_month",        intval(substr($Result['born_date'],5,6))); //出生日期月
//$tpl->assign("Born_day",          intval(substr($Result['born_date'],7,8))); //出生日期日

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Good);
$tpl->display("AccountInformation_".VersionArea.".html");
?>
