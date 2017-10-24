<?php
error_reporting(7);
//$lifeTime = 24 * 3600;
//session_set_cookie_params($lifeTime);
@session_start();
include ("../configs.inc.php");
@header("Content-type: text/html; charset=utf-8");
include_once "../language/".$INFO['IS']."/JsMenu.php";

include "../language/big5.php";
/**
 * 判断浏览器类型
 */
$user_agent = getenv('HTTP_USER_AGENT');
$Ie_array = array('msie 5.0','msie 5.5','msie 6','msie 7','netscape','mozilla');
function Returntype ($type,$array){
	foreach ($array as $k=>$v){
		$pos = strpos(strtolower($type),$v);
		if ($pos === false) {
		}else{
			return $v;
		}
	}
}

$Ie    = Returntype ($user_agent,$Ie_array);

switch ($Ie){
	case "mozilla":
		$Ie_Type = "mozilla";
		break;
	default:
		$Ie_Type = "msie";
		break;
}

/**
 * 负责个人用户登陆状态的校验，如果是未登陆用户，将跳转到个人用户登陆界面！
 * 同时在此处将对用户ID进行校验。以确保用户资料的真实性。
 */


if ($_SESSION['LOGINADMIN_session_id'] == '' || empty($_SESSION['LOGINADMIN_session_id']))  {
	//echo "is here";

	$FUNCTIONS->header_location($INFO['site_shopadmin'] . "/login.php");

}
if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])){
	$FUNCTIONS->header_location($INFO['site_shopadmin'] . "/login.php");
	 exit;
}
/*这里为了校验SESSION表中数据，以保证后台管理者的正确登陆*/
$Session_sql   = " select session_id,sa_id from `{$INFO[DBPrefix]}session_table` where session_id='".$_SESSION['LOGINADMIN_session_id']."' and sa_id='".$_SESSION['sa_id']."' and actiontime>='" . (time()-50*60) . "' limit 0,1";
$Session_query = $DB->query($Session_sql);
$Session_num   = $DB->num_rows($Session_query);
if ($Session_num <= 0){
	$FUNCTIONS->header_location($INFO['site_shopadmin'] . "/login.php");
	exit;
}else{
	$u_Sql = "update `{$INFO[DBPrefix]}session_table` set actiontime='" . time() . "' where session_id='".$_SESSION['LOGINADMIN_session_id']."' and sa_id='".$_SESSION['sa_id']."'";
	$DB->query($u_Sql);
}
/**
 * 这里是获得当前文件没有PHP后缀的名字。
 */
if ($FileNamebody==""){
	$FileNamebodyarray    = explode("/",$_SERVER["PHP_SELF"]);
	$FileNamebodyarraynum = count($FileNamebodyarray)-1;
	$FileNamebody         = substr($FileNamebodyarray[$FileNamebodyarraynum],0,-4);
}
/**
 * 这里是对供应商部分的校验，来控制只能访问指定的几个文件。
 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$YesAcceProvider = "";
	$Provider_array = array("provider_ncon_list","provider_ncon","provider_ncon_save","provider_desktop","provider_desktop_right","provider_index","provider_js","provider_order_list","provider_psw","provider_goods_list","provider_goods_list_save","provider_goods","provider_goods_save","provider_goods_pic","provider_goods_pic_save","admin_goods_getmoreattrib","provider_goods_attrib","provider_comment_list","provider_comment","provider_comment_save","provider_order","admin_order_print","provider_order_act","admin_order_more","Check_ajax","provider_memberprice","provider_memberprice_save","admin_goods_ajax_attrib","admin_goods_ajax_changestorage","admin_goods_ajax_changestorage_save","admin_goods_ajax_changestoragelist","admin_goods_ajax_detail","admin_goods_ajax_detail_save","admin_goods_ajax_detailadd","admin_goods_ajax_link","admin_goods_ajax_linkgoods","admin_goods_ajax_linkgoodssave","admin_goods_ajax_pic","admin_goods_ajax_pic_save","admin_goods_ajax_linkgoodslist","admin_goods_ajaxclass","admin_goods_ajax_attribno","admin_goods_ajax_attribno_save","provider_kefu_list","provider_kefu","provider_kefu_save","provider_statistic_provider","provider_statistic_provider_excel","admin_goods_detail","admin_order_trans","provider_info","provider_save","admin_wait_buy","provider_discountsubject_list","provider_discountsubject_ajax_goods","provider_discountsubject_ajax_goods_list","provider_discountsubject_ajax_goods_save","provider_discountsubject_ajax_goods_xlist","ajax_updateProduct","provider_goods_excel","provider_order_excel","provider_order_showact","provider_statistic_provider_sale","provider_statistic_save","provider_statistic_provider_detail","provider_statistic_provider_sale_print","provider_statistic_provider_sale_excel","provider_statistic_provider_detail_excel","admin_waitbuy","admin_waitbuy_send","admin_waitbuy_ajax_sendmail");
	foreach ($Provider_array as $k=>$v){
		if ($v==$FileNamebody){
			$YesAcceProvider = "Yes";
			break 1;
		}
	}

	if ($YesAcceProvider != "Yes"){
		$FUNCTIONS->sorry_back("provider_index.php","");
	}
}

if (intval($_SESSION[LOGINADMIN_TYPE])==3){
	$YesAcceProvider = "";
	$Provider_array = array("saler_desktop","saler_index","saler_js","saler_info","saler_info","saler_info_save","saler_search");
	foreach ($Provider_array as $k=>$v){
		if ($v==$FileNamebody){
			$YesAcceProvider = "Yes";
			break 1;
		}
	}

	if ($YesAcceProvider != "Yes"){
		$FUNCTIONS->sorry_back("saler_index.php","");
	}
}


/**
 * 因为privilege是普通用户拥有的值，所以只有这个值有效的时候，才对文件权限做跟踪！
 */
require "File_AcceptTable.php";  //获得文件匹配资料


/**
 *  这里是对会员的文件存取权限做审核的操作
 */
//echo $_SESSION['privilege'];
if( isset($_SESSION['privilege']) && $_SESSION['LOGINADMIN_TYPE']==1){
	$YesAccetp = "No";
	//$ifAccetp = File_Accept($FileNamebody);
	//echo substr($FileNamebody,strlen($FileNamebody)-5);

	if (substr($FileNamebody,strlen($FileNamebody)-5)=="_save"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,strlen($FileNamebody)-5) . ".php%'";
	}
	if (substr($FileNamebody,6,11)=="publication"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,17) . "%.php%'";
	}
	if (substr($FileNamebody,6,5)=="group" && $FileNamebody!="admin_groupbuyrecord_list" && $FileNamebody!="admin_grouppoint_list" && $FileNamebody!="admin_grouppoint_save"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,11) . "%.php%'";
	}
	if ($FileNamebody=="admin_grouppoint_save"){
		$subsql = " or pageurl like '%admin_grouppoint%.php%'";
	}
	//echo $FileNamebody;exit;
	if (substr($FileNamebody,6,4)=="kefu"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,10) . "%.php%'";
	}
	if (substr($FileNamebody,6,9)=="attribute"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,10) . "%.php%'";
	}
	if (substr($FileNamebody,6,5)=="order" || $FileNamebody=="admin_excel_ps" || $FileNamebody=="admin_excel_check"){
		$subsql = " or pageurl like '%admin_order%.php%'";
	}
	if (substr($FileNamebody,6,18)=="statistic_provider"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,24) . "%.php%'";
	}
	if (substr($FileNamebody,6,7)=="comment"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,13) . "%.php%'";
	}
	if (substr($FileNamebody,6,5)=="level"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,11) . "%.php%'";
	}
	if (substr($FileNamebody,6,7)=="contact"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,13) . "%.php%'";
	}
	if (substr($FileNamebody,6,15)=="discountsubject"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,21) . "%.php%'";
	}
	if (substr($FileNamebody,6,7)=="mailset"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,13) . "%.php%'";
	}
	if (substr($FileNamebody,6,3)=="msg"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,9) . "%.php%'";
	}
	if (substr($FileNamebody,6,5)=="ttype"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,11) . "%.php%'";
	}
	if (substr($FileNamebody,6,14)=="transportation"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,20) . "%.php%'";
	}
	if (substr($FileNamebody,6,4)=="area"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,10) . "%.php%'";
	}
	if (substr($FileNamebody,6,8)=="timetype"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,14) . "%.php%'";
	}
	if (substr($FileNamebody,6,10)=="paymanager"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,16) . "%.php%'";
	}
	if (substr($FileNamebody,6,2)=="ip"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,8) . "%.php%'";
	}
	if (substr($FileNamebody,6,11)=="newscomment"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,17) . "%.php%'";
	}
	if (substr($FileNamebody,6,10)=="photoclass"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,16) . "%.php%'";
	}
	if (substr($FileNamebody,6,5)=="photo"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,11) . "%.php%'";
	}
	if (substr($FileNamebody,6,6)=="ticket"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,12) . "%.php%'";
	}
	if (substr($FileNamebody,6,9)=="falsemail"){
		$subsql = " or pageurl like '%publication%.php%'";
	}
	if (substr($FileNamebody,6,8)=="falsesmg"){
		$subsql = " or pageurl like '%edmsmg%.php%'";
	}
	if (substr($FileNamebody,6,7)=="waitbuy"){
		$subsql = " or pageurl like '%admin_goods%.php%'";
	}
	if (substr($FileNamebody,6,11)=="goods_excel"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,17) . "%.php%'";
	}
	if (substr($FileNamebody,6,6)=="member"){
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,12) . "%.php%'";
	}
	if (substr($FileNamebody,6,9)=="writemail"){
		$subsql = " or pageurl like '%member%.php%'";
	}
	if (substr($FileNamebody,6,8)=="writesms"){
		$subsql = " or pageurl like '%member%.php%'";
	}
	if (substr($FileNamebody,6,9)=="recommend"){
		$subsql = " or pageurl like '%member%.php%'";
	}
	if (substr($FileNamebody,6,8)=="userback"){
		$subsql = " or pageurl like '%order%.php%'";
	}
	if (substr($FileNamebody,6,9)=="paymethod"){
		$subsql = " or pageurl like '%paymanager%.php%'";
	}
	if (substr($FileNamebody,6,5)=="image"){
		$subsql = " or pageurl like '%photo%.php%'";
	}
	/*if (substr($FileNamebody,6,4)=="sale"){
		$subsql = " or pageurl like '%sale%.php%'";
	}*/
	if (substr($FileNamebody,6,4)=="poll"){
		$subsql = " or pageurl like '%poll%.php%'";
	}
	if (substr($FileNamebody,6,16)=="goods_collection")
		$subsql = " or pageurl like '%" . substr($FileNamebody,0,22) . "%.php%'";
	if ($FileNamebody == "admin_create_smtpconfig")
		$FileNamebody = "admin_mailbasic";
	if ($FileNamebody == "admin_log_save")
		$FileNamebody = "admin_log";
	if ($FileNamebody == "admin_create_productclassshow")
		$FileNamebody = "admin_pcat";
	if ($FileNamebody == "admin_create_newsclassshow")
		$FileNamebody = "admin_ncon";
	if ($FileNamebody == "admin_create_forumclassshow")
		$FileNamebody = "admin_fcat";
	if ($FileNamebody == "admin_create_smtpconfig")
		$FileNamebody = "admin_mailbasic";
	if ($FileNamebody == "ajax_updateProduct")
		$FileNamebody = "admin_goods";
	if ($FileNamebody == "admin_AutoCreateMemberlist")
		$FileNamebody = "admin_group_list";
	if ($FileNamebody == "admin_pubgroup")
		$FileNamebody = "admin_publication_list";
	if ($FileNamebody == "admin_pubsend_new")
		$FileNamebody = "admin_publication_list";
	if ($FileNamebody == "admin_ajax_sendmail")
		$FileNamebody = "admin_publication_list";
	if ($FileNamebody == "admin_poll_edit")
		$FileNamebody = "admin_poll_create";

	if (substr($FileNamebody,0,16)=="admin_goods_ajax"){
		$FileNamebody = "admin_goods";
	}
	if (substr($FileNamebody,0,16)=="admin_store_ajax"){
		$FileNamebody = "admin_store";
	}
	if (substr($FileNamebody,0,28)=="admin_discountsubject_ajax"){
		$FileNamebody = "admin_discountsubject";
	}
	if ($FileNamebody == "admin_goods_attributeclass")
		$FileNamebody = "admin_goods";
	if ($FileNamebody == "admin_goods_getmoreattrib")
		$FileNamebody = "admin_goods";
	if ($FileNamebody == "admin_goods_nocheck")
		$FileNamebody = "admin_goods";

	if ($FileNamebody == "admin_goodscollection_save")
		$FileNamebody = "admin_goods_collection_list";
	if ($FileNamebody == "admin_goodscollection_save")
		$FileNamebody = "admin_goods_collection_list";
	if ($FileNamebody == "admin_buypoint_save")
		$FileNamebody = "admin_buypoint_list";
	if ($FileNamebody == "admin_goods_detail" ){
		$FileNamebody = "admin_goods_list";
		$subsql="";
	}

	if ($FileNamebody == "admin_crm_member_advantage_excel")
		$FileNamebody = "admin_crm_member_advantage";
	if ($FileNamebody == "admin_crm_member_report_excel")
		$FileNamebody = "admin_crm_member_report";
	if ($FileNamebody == "admin_bonusrecord_save")
		$FileNamebody = "admin_bonusrecord_list";
	if ($FileNamebody == "Member_HistoryList")
		$FileNamebody = "admin_member";
	if ($FileNamebody == "admin_crm_dashiboard")
		$FileNamebody = "admin_member_statistics";
	if ($FileNamebody == "admin_crm_member_advantage")
		$FileNamebody = "admin_member_statistics";
	if ($FileNamebody == "admin_crm_member_report")
		$FileNamebody = "admin_member_statistics";
	if ($FileNamebody == "admin_pubsmg")
		$FileNamebody = "admin_edmsmg_list";
	if ($FileNamebody == "admin_edmsmg")
		$FileNamebody = "admin_edmsmg_list";
	if ($FileNamebody == "admin_edmsmg_save")
		$FileNamebody = "admin_edmsmg_list";
	if ($FileNamebody == "admin_redgreensubject_ajax_red")
		$FileNamebody = "admin_redgreensubject_list";
	if ($FileNamebody == "admin_redgreensubject_ajax_red_xlist")
		$FileNamebody = "admin_redgreensubject_list";
	if ($FileNamebody == "admin_redgreensubject_ajax_green")
		$FileNamebody = "admin_redgreensubject_list";
	if ($FileNamebody == "admin_redgreensubject_ajax_green_xlist")
		$FileNamebody = "admin_redgreensubject_list";
	if ($FileNamebody == "admin_redgreensubject_ajax_goods_list")
		$FileNamebody = "admin_redgreensubject_list";
	if ($FileNamebody == "admin_redgreensubject_ajax_red_save")
		$FileNamebody = "admin_redgreensubject_list";
	if ($FileNamebody == "admin_redgreensubject_ajax_green_save")
		$FileNamebody = "admin_redgreensubject_list";
	if ($FileNamebody == "admin_score")
		$FileNamebody = "admin_score_list";
	if ($FileNamebody == "admin_score_save")
		$FileNamebody = "admin_score_list";
	if ($FileNamebody == "admin_ncon_goods")
		$FileNamebody = "admin_ncon";
	if ($FileNamebody == "admin_ncon_goods_list")
		$FileNamebody = "admin_ncon";
	if ($FileNamebody == "admin_linkgoods")
		$FileNamebody = "admin_ncon";
	if ($FileNamebody == "admin_privilege")
		$FileNamebody = "admin_operatergroup_list";
	if ($FileNamebody == "admin_operatergroup")
		$FileNamebody = "admin_operatergroup_list";
	if ($FileNamebody == "admin_operatergroup_save")
		$FileNamebody = "admin_operatergroup_list";
	if ($FileNamebody == "admin_holiday")
		$FileNamebody = "admin_holiday_list";
	if ($FileNamebody == "admin_holiday_save")
		$FileNamebody = "admin_holiday_list";
	if ($FileNamebody == "admin_store")
		$FileNamebody = "admin_store_list";
	if ($FileNamebody == "admin_store_save")
		$FileNamebody = "admin_store_list";
	if ($FileNamebody == "getAuth")
		$FileNamebody = "admin_big5_sys";
	if ($FileNamebody == "admin_brand_class_list")
		$FileNamebody = "admin_brand_list";
	if ($FileNamebody == "admin_brand_class")
		$FileNamebody = "admin_brand_list";
	if ($FileNamebody == "admin_brand_class_save")
		$FileNamebody = "admin_brand_list";

	switch ($FileNamebody) {
		//設置-->系統設置-->版面配置
		case "admin_indexseting_ajaxgrid":
		case "admin_indexiframe":
			$FileNamebody = "admin_indexseting";
			$subsql = "";
			break;
		//行銷工具-->統計分析-->商品統計
		case "admin_sale_report":
		case "admin_sale_report_excel":
		case "productSaleStatistics":
		case "productViewStatistics":
		case "unsalableProduct":
		case "zerosalableProduct":
			$FileNamebody = "admin_goods_statistics";
			$subsql = "";
			break;
		//行銷工具-->統計報表-->進階商品統計
		case "admin_sale_dashiboard":
		case "admin_shopping_cart_goods_statistics":
		case "admin_shopping_cart_goods_statistics_save":
		case "admin_sale_hot":
		case "admin_sale_hot_excel":
		case "admin_sale_brand":
		case "admin_sale_brand_excel":
		case "admin_sale_class":
		case "admin_sale_provider":
		case "admin_sale_provider_excel":
		case "admin_track_goods_statistics":
		case "admin_track_goods_statistics_save":
			$FileNamebody = "admin_goods_statistics_advanced";
			$subsql = "";
			break;
		//行銷工具-->統計報表-->進階商品統計
		case "admin_sale_analysis":
			$FileNamebody = "admin_order_statistics";
			$subsql = "";
			break;
		//行銷工具-->統計報表-->進階客戶關係管理
		case "admin_crm_member_advantage_save":
		case "admin_crm_member_report_save":
			$FileNamebody = "admin_member";
			$subsql = "";
			break;
			//供應商管理-->業績查詢
		case "admin_saler_excel":
			$FileNamebody = "admin_saler_search";
			$subsql = "";
			break;
	}

	switch ($FileNamebody) {
		case "admin_indexseting_ajaxgrid":
		case "admin_indexseting_ajax_adv":
		case "admin_indexseting":
		case "admin_ordergoods_stock":
		case "admin_ordergoods_excel":
			$FileNamebody = "desktop";
			$subsql = "";
			break;
		case "admin_goods_excel_out":
		case "admin_goods_excelstorage":
		case "admin_goods_excel_in":
		case "admin_goods_excel_price":
		case "admin_goods_checkbn":
			$FileNamebody = "admin_goods_list";
			$subsql = "";
			break;
		case "admin_language":
		case "admin_language_save":
			$FileNamebody = "admin_language_list";
			$subsql = "";
			break;
	}

	if ($FileNamebody == "admin_gridseting")
		$FileNamebody = "admin_gridseting";
	if ($FileNamebody == "admin_indexseting_ajax_banner")
		$FileNamebody = "admin_gridseting";
	if ($FileNamebody == "admin_indexseting_ajax_advsave")
		$FileNamebody = "admin_gridseting";

	if ($FileNamebody == "admin_outputemail_act")
		$FileNamebody = "admin_outputemail";
	if ($FileNamebody == "admin_pubsmg_new")
		$FileNamebody = "dmin_edmsmg_list";

	if ($FileNamebody == "admin_buypointrefund_save")
		$FileNamebody = "admin_buypointrefund_list";

	$sql_f = "select * from `{$INFO[DBPrefix]}menu_right` where pageurl like '%" . $FileNamebody . ".php%' " . $subsql;
	$Query_f = $DB->query($sql_f);
	$Num_f   = $DB->num_rows($Query_f);
	$Result_f= $DB->fetch_array($Query_f);
	$ifAccetp = intval($Result_f['mrid']);
	$ArrayPrivilege = explode("%",$_SESSION['privilege']);
	foreach ($ArrayPrivilege as $k=>$v){
		if ($v==$ifAccetp && $v!=""){
			$YesAccetp = "Yes";
			break 1;
		}
	}
	if (stristr("desktop",$FileNamebody)==false){
	}else{
		$YesAccetp = "Yes";
	}
	if ($FileNamebody == "admin_modi_ok" ){
		$YesAccetp = "Yes";
	}
	//echo "select * from `{$INFO[DBPrefix]}menu_right` where pageurl like '%" . $FileNamebody . ".php%' " . $subsql;
	//echo $FileNamebody;exit;
	//echo $FileNamebody;
	//echo  stripos($FileNamebody,"ajax")===0;exit;
	if($YesAccetp != "Yes" || trim($_SESSION['privilege'])==""){
		//echo $ifAccetp.$FileNamebody.trim($_SESSION['privilege']);exit;
		$FUNCTIONS->sorry_back("Error.php","");
		exit;
	}
	$subsql = "";

}

function Add_LOGINADMIN_TYPE($Sql){
	switch  (intval($_SESSION[LOGINADMIN_TYPE]))
	{
		case 0 :
			$Add_Sql = "";
			break;
		case 1:
			$Add_Sql = "";
			break;
		case 2:
			$Add_Sql = "provider_id=".$_SESSION['sa_id']." ";
			$SqlPos   = strpos($Sql, "where");
			if ($SqlPos===false){
				$Sql      = $Sql." where ".$Add_Sql;
			}else{
				$Sql      = $Sql." and ".$Add_Sql;
			}
			break;
	}

	return  $Sql;
}

/**
 * 这部分是为了判断一下登陆状态 ,如果是供应商,就采用PROVIDER_JS.PHP
 */
switch  (intval($_SESSION[LOGINADMIN_TYPE]))
{
	case 0 :
		$Js_Top = VersionArea."_js.php";
		break;
	case 1:
		$Js_Top = VersionArea."_js.php";
		break;
	case 2:
		$Js_Top = "provider_js.php";
		break;
	case 3:
		$Js_Top = "saler_js.php";
		break;
}

//得到商品類別
if ($_SESSION['LOGINADMIN_TYPE']==1){
	$op_class_array = array();
	$c_sql = "select * from `{$INFO[DBPrefix]}operater_class` where opid='" . intval($_SESSION['sa_id']) . "'";
	$c_query = $DB->query($c_sql);
	$i = 0;
	while($c_row= $DB->fetch_array($c_query)){
		$op_class_array[$i] = $c_row[bid];
		$i++;
	}
}
$piaoqi_array = array("30天","45天","60天","90天");
$mode_array = array("網路店家","網路/門市","門市");
$paytype_array = array("月結","半個月結一次","週週結","買斷");

$goods_field['gid'] = "商品ID";
$goods_field['goodsname'] = "商品名稱";
$goods_field['en_name'] = "英文名稱";
$goods_field['sale_name'] = "促銷名稱";
$goods_field['salename_color'] = "促銷名稱顏色";
$goods_field['bid'] = "商品分類";
$goods_field['bn'] = "貨號";
$goods_field['goodsno'] = "賣場編號";

$goods_field['provider_id'] = "供應商";
$goods_field['iftogether'] = "是否統倉";
$goods_field['brand_id'] = "品牌";
$goods_field['unit'] = "單位";
$goods_field['cost'] = "成本";
$goods_field['salecost'] = "促銷成本";
$goods_field['price'] = "建議價格";
$goods_field['pricedesc'] = "網購價";
$goods_field['memberprice'] = "會員價格";
$goods_field['combipoint'] = "紅利點數（會員價格）";
$goods_field['point'] = "贈送紅利點數";
$goods_field['guojima'] = "國際碼";
$goods_field['xinghao'] = "型號";
$goods_field['weight'] = "重量";
$goods_field['chandi'] = "產地";
$goods_field['ERP'] = "ERP";
$goods_field['video_url'] = "影音";
$goods_field['good_color'] = "商品顏色";
$goods_field['good_size'] = "商品尺寸";
$goods_field['gattribs'] = "商品屬性";
$goods_field['gattribs_content'] = "屬性內容";
$goods_field['intro'] = "簡介";
$goods_field['alarmcontent'] = "使用規則";
$goods_field['keywords'] = "關鍵字";
$goods_field['cap_des'] = "成份規格";
$goods_field['body'] = "詳細內容";


$goods_field['ifalarm'] = "庫存警告";
$goods_field['alarmnum'] = "庫存提醒數量";

$goods_field['nocarriage'] = "免運件數";
$goods_field['bonusnum'] = "兌換所需積分";
$goods_field['subject_id'] = "主題名稱";

$goods_field['ifpub'] = "是否發佈";
$goods_field['goodorder'] = "商品排序";
$goods_field['view_num'] = "查看次數";
$goods_field['ifrecommend'] = "是否推薦";
$goods_field['ifspecial'] = "是否特價";
$goods_field['ifbonus'] = "是否紅利";
$goods_field['ifhot'] = "是否熱賣";
$goods_field['ifjs'] = "是否集殺";
$goods_field['js_begtime'] = "集殺開始時間";
$goods_field['js_endtime'] = "集殺結束時間";
$goods_field['js_price'] = "集殺價格";
$goods_field['jscount'] = "集殺件數";
$goods_field['js_totalnum'] = "集殺累計數量";
//$goods_field['goodattr'] = "商品屬性";
$goods_field['if_monthprice'] = "是否分期";
$goods_field['month'] = "分期";

$goods_field['ifpresent'] = "是否額滿禮";
$goods_field['present_money'] = "額滿禮金額（最小）";
$goods_field['present_endmoney'] = "額滿禮金額（最大）";

$goods_field['trans_type'] = "配送方式";
$goods_field['trans_special'] = "使用特殊配送方式";
$goods_field['iftransabroad'] = "是否支持海外";
$goods_field['trans_special_money'] = "特殊配送運費";
$goods_field['transtype'] = "貨運寄送類型";
$goods_field['ifmood'] = "是否支持額滿免運費";
$goods_field['transtypemonty'] = "每件運費";
$goods_field['addtransmoney'] = "運費加價";

$goods_field['ifxygoods'] = "是否超值任選商品";
$goods_field['ifxy'] = "是否屬於超值任選商品";
$goods_field['ifchange'] = "是否加購商品";
$goods_field['xycount'] = "超值任選數量";

$goods_field['sale_subject'] = "多件折扣活動";
$goods_field['ifsales'] = "是否同商品多件折扣";
$goods_field['sale_price'] = "多件折扣活動價格";
$goods_field['ifsaleoff'] = "是否整點促銷1";
$goods_field['saleoff_starttime'] = "整點促銷1開始時間";
$goods_field['saleoff_endtime'] = "整點促銷1結束時間";
$goods_field['ifadd'] = "是否額滿加購商品";
$goods_field['addmoney'] = "額滿加購金額";
$goods_field['addprice'] = "額滿加購價格";
$goods_field['oeid'] = "OEID";
$goods_field['iftimesale'] = "是否整點促銷2";
$goods_field['saleoffprice'] = "整點促銷2價格";
$goods_field['timesale_starttime'] = "整點促銷2開始時間";
$goods_field['timesale_endtime'] = "整點促銷2結束時間";

$goods_field['checkstate'] = "審核狀態";
$goods_field['nocheckreason'] = "取消審核原因";

$member_field['user_id'] = "會員ID";
$member_field['username'] = "帳號";
$member_field['memberno'] = "會員編號";
$member_field['recommendno'] = "推薦人編號";
$member_field['user_state'] = "狀態";
$member_field['true_name'] = "中文姓名";
$member_field['sex'] = "性別";
$member_field['born_date'] = "生日";
$member_field['bornCountry'] = "國籍";
$member_field['Country'] = "居住國家";
$member_field['city'] = "區";
$member_field['canton'] = "市";
$member_field['zip'] = "郵遞區號";
$member_field['addr'] = "地址";
$member_field['email'] = "電子信箱";
$member_field['tel'] = "聯絡電話";
$member_field['other_tel'] = "手機";
$member_field['fax'] = "傳真";
$member_field['companyid'] = "公司";
$member_field['certcode'] = "護照號碼";
$member_field['dianzibao'] = "電子報";
$member_field['user_level'] = "會員等級";
$member_field['bouns'] = "紅利點數";
$member_field['buypoint'] = "購物金";
$member_field['grouppoint'] = "團購金";
$member_field['ordertotal'] = "消費金額";
$member_field['reg_date'] = "註冊時間";
$provider_state = array("申請中","審核中","啟用","快到期","已到期","停用","申請失敗","簽約中");
?>
