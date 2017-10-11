<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";


if (isset($_GET['kid'])&&intval($_GET['kid'])!=0) {

	$Query_linshi = '';

	$kefu = array();

	$Sql_linshi = " select k.*,g.*,o.order_id from `{$INFO[DBPrefix]}kefu` k left join `{$INFO[DBPrefix]}goods` as g on k.marketno=g.goodsno left join `{$INFO[DBPrefix]}order_table` as o on o.order_serial=k.order_serial where kid = '".$_GET[kid]."' and k.user_id=".intval($_SESSION['user_id'])."";
	$Query_linshi = $DB->query($Sql_linshi);
	$Num   = $DB->num_rows($Query_linshi);
	if($Num<=0){
		$FUNCTIONS->header_location('kefu_list.php');	
	}
	while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
		$kefu = $Rs_linshi;
	}
	/**
	 * 这里判断是否是后台规定登陆会员才能查看的提示信息
	 */
	if ($kefu['iflogin']==1&&empty($_SESSION['user_id'])) {

		$kefu_post_list = <<<EOF
                        <TR>
                          <TD width="19%" height="24" align=left valign="middle" class="comment head">$KeFu_Pack[Report]：</TD>
                          <TD><font color=red>$KeFu_Pack[Please_Login_View]</font></TD>
                        </TR>
EOF;
}
/**
	 * 这里判断是否有回复的提示信息
	 */
else {
	if ($kefu['postnum']==0) {
		$kefu_post_list = <<<EOF
                        <TR>
                          <TD width="19%" height="24" align=right valign="middle" class="comment head">{$KeFu_Pack[Report]}：</TD>
                          <TD class="body">$KeFu_Pack[No_Report]</TD>
                        </TR>
EOF;
}
/**
	 * 这里将循环显示出来回复的信息资料
	 */
else {
	$Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_posts` where kid = '".$_GET[kid]."' and (ifcheck=1 or provider_id=0)";
	$Query_linshi = $DB->query($Sql_linshi);
	$kefu_posts = array();
	while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
		$kefu_posts[] = $Rs_linshi;
	}
	$i = 1;
	foreach ($kefu_posts as $value) {
		$kefu_post_date = getdate_kefu($value['postdate']);
		$kefu_post_con  = nl2br($value[k_post_con]);
		$kefu_post_list .= <<<EOF
                        <TR>
                          <TD width="19%" height="24" align=right valign="middle" bgcolor="#FFFFFF" class="comment head">{$KeFu_Pack[Report]} # {$i}： <br>
                          $value[username]<br>
                          $kefu_post_date
                          </TD>
                          $value[username]<br>
                          $kefu_post_date
                          </TD>
                          <TD class="body"><b>$value[k_post_title]</b> <br>
                          $kefu_post_con
                          </TD>
                        </TR>
EOF;
		$i++;
}

}
}
}

$kefu_arrays = explode('-',$kefu['type_chuli_name']);

$kefu_type = $kefu_arrays[0];
$kefu_chuli = $kefu_arrays[1];

$kefu_title = $kefu['title'];
if ($kefu['status']==0) {
	$kefu_status = $KeFu_Pack['Wait_Report'];    //'等待回覆';
}elseif ($kefu['status']==1) {
	$kefu_status = $KeFu_Pack['Already_Report']; //'已經回覆';
}elseif ($kefu['status']==2) {
	$kefu_status = $KeFu_Pack['Close_Report'];   //'關閉問題';
}else{
	$kefu_status = $KeFu_Pack['Wait_Report'];    //'等待回覆';	
}

$Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_chuli` where k_chuli_id='" . $kefu_chuli . "'";
$Query_linshi = $DB->query($Sql_linshi);
$Rs_linshi = $DB->fetch_array($Query_linshi);
if ($Rs_linshi['k_type_id']!=""){
	$kefu_status = $KeFu_Pack['Close_Report'] . "-" . $Rs_linshi['k_type_name'] ;
}

$tpl->assign("Username",      $_SESSION['username']);//用戶名
$tpl->assign("LoginUsername", $_SESSION['true_name']);//真實姓名

$Query = $DB->query("select email from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$tpl->assign("Email", $Result[email]);//用户email
}

$kefu_username = $kefu['username'];
$kefu_date = getdate_kefu($kefu['lastdate']);
$kefu_email = $kefu['email'];
$kefu_con = $kefu['k_kefu_con'];
$kefu_serialnum = $kefu['serialnum'];
$order_serial = $kefu['order_serial'];
$marketno = $kefu['marketno'];
$tpl->assign("order_id", $kefu['order_id']);
$tpl->assign("kefu_serialnum", $kefu_serialnum);
$tpl->assign("kefu_id", intval($_GET['kid']));
$tpl->assign("kefu_type", $kefu_type);
$tpl->assign("goodsname", $kefu['goodsname']);
$tpl->assign("gid", $kefu['gid']);
$tpl->assign("kefu_title", $kefu_title);
$tpl->assign("kefu_status", $kefu_status);
$tpl->assign("kefu_username",  $kefu_username);
$tpl->assign("kefu_date", $kefu_date);
$tpl->assign("kefu_email", $kefu_email);
$tpl->assign("kefu_con", nl2br($kefu_con));
$tpl->assign("kefu_post_list", $kefu_post_list);
$tpl->assign("order_serial", $order_serial);
$tpl->assign("marketno", $marketno);
$tpl->assign("Username",      $_SESSION['username']);//用戶名


if (strstr($kefu['type_chuli_name'],$KeFu_Pack['Already_Complete'])) { //已經完成
	$tpl->assign("display_post", 'display:none;');
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关

$tpl->assign("kefu_list_say",   $KeFu_Pack['type_list']);  //留言列表
$tpl->assign("kefu_add_say",    $KeFu_Pack['user_write']); //用户留言
$tpl->assign("kefu_type_say",   $KeFu_Pack['type']);       //留言類別
$tpl->assign("kefu_title_say",  $KeFu_Pack['title']);      //簡單標題
$tpl->assign("kefu_access_say", $KeFu_Pack['access_no']);  //帳號
$tpl->assign("kefu_name_say",   $KeFu_Pack['name']);       //姓名
$tpl->assign("kefu_email_say",  $KeFu_Pack['email']);      //電子郵件地址
$tpl->assign("kefu_content_say",$KeFu_Pack['content']);    //留言內容
$tpl->assign("kefu_submit_say", $KeFu_Pack['submit']);    //提交

$tpl->assign("kefu_No_say",     $KeFu_Pack['No']);    //編號
$tpl->assign("kefu_Status_say", $KeFu_Pack['Status']);    //狀態
$tpl->assign("kefu_LastUpdateTime_say", $KeFu_Pack['LastUpdateTime']);    //最後提交時間

$tpl->assign("kefu_Js_input_title",   $KeFu_Pack['Js_input_title']);    //请输入簡單標題！
$tpl->assign("kefu_Js_input_name",    $KeFu_Pack['Js_input_name']);    //请输入姓名！
$tpl->assign("kefu_Js_input_email",   $KeFu_Pack['Js_input_email']);    //请输入電子郵件地址！
$tpl->assign("kefu_Js_input_content", $KeFu_Pack['Js_input_content']);    //请输入留言內容！

$tpl->display("kefu_detail.html");

function getdate_kefu($value)
{

	$return_date = getdate($value);
	$return_date['mon']=strlen($return_date['mon'])==1?'0'.$return_date['mon']:$return_date['mon'];
	$return_date['mday']=strlen($return_date['mday'])==1?'0'.$return_date['mday']:$return_date['mday'];
	$return_date = $return_date['year'].'-'.$return_date['mon'].'-'.$return_date['mday'].'  '.$return_date['hours'].':'.$return_date['minutes']; 	return $return_date;
}
?>
