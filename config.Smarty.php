<?php
error_reporting(7);

/**
 *  装载系统配置文件
 */
include (RootDocumentShare."/conf.global.php");
include ("global.php");


/* 装载时区配置 */
if (function_exists("date_default_timezone_set")){
	date_default_timezone_set( "Asia/Taipei" );
}
/**
 * 如果前台临时修改的语言项目。这里将及时调整当前用户所用的资料
 */
if (!empty($_SESSION[CurrentUserLanguage])){
	$INFO['IS'] = $INFO['admin_IS']= $_SESSION[CurrentUserLanguage];
}
include (RootDocumentShare."/setindex.php");

$INFO['MaxNewProductNum']			=	intval($INFO['MaxNewProductNum'])>0 ? intval($INFO['MaxNewProductNum']) : 10;
$INFO['MaxProductNumForList']		=   intval($INFO['MaxProductNumForList'])>0 ? intval($INFO['MaxProductNumForList']) : 10;

/**
 * 下边这里是为了配合前台快速切换摸版使用的，目前我主要用于做摸版的演示使用。
 * 生成$_SESSION[Change_templates]的文件是根目录下的ChangeTemplates.php

$INFO[templates]= $_SESSION[Change_templates]!="" ? $_SESSION[Change_templates] : $INFO[templates];
*/


/**
 *  定义摸版根目录
 */
$Templates_root = RootDocument."/templates";
define("Templates",$Templates_root);

/**
 *  定义默认摸版根目录
 */

$templates  = isset($INFO['templates'])  ?  $INFO['templates']  :  'default'  ;
//電腦/手機對應關係
$cur_array = explode("/",$_SERVER["PHP_SELF"]);
$first_page = $cur_array[count($cur_array)-3];
$cur_page = $cur_array[count($cur_array)-2];
$last_page = $cur_array[count($cur_array)-1];
$page_array = array(
					array("product/index.php","product_index.php"),
					array("product/goods_detail.php","goods_detail.php"),
					array("article/index.php","article_list.php"),
					array("article/article.php","article.php"),
					array("modules/discount/discountsubject.php","discountsubject.php"),
					array("member/reg_detail.php","reg_detail.php"),
					array("help/Aboutour.php","Aboutour.php"),
					array("member/AccountInformation.php","account.php"),
					array("modules/discount/discountsubject_main.php","discountsubject_main.php"),
					array("modules/discount/discountsubject_main_all.php","discountsubject_main_all.php"),
					array("member/login_windows.php","login.php"),
					array("member/bonuspointrecord.php","member_bonus.php"),
					array("member/bonuspointbuyrecord.php","member_bonususe.php"),
					array("member/ChangePwd.php","member_chengepwd.php"),
					array("member/forget_password.php","member_forgetpwd.php"),
					array("member/index.php","member_index.php"),
					array("member/kefu_list.php","member_kefu.php"),
					array("member/kefu_detail.php","member_kefu_detail.php"),
					array("member/MyOrder.php","member_order.php"),
					array("member/ViewOrder.php","member_order_detail.php"),
					array("member/receiver_list.php","member_receiver.php"),
					array("member/receiver.php","member_receiver_detail.php"),
					array("member/recommendmember.php","member_recommendmember.php"),
					array("member/recommendpoint.php","member_recommendpoint.php"),
					array("member/myticket.php","member_ticket.php"),
					array("modules/sale/product_sale.php","product_sale.php"),
					array("shopping/receivePay.php","receivePay.php"),
					array("shopping/shopping.php","shopping.php"),
					array("shopping/shopping2.php","shopping2.php"),
					array("shopping/shopping3.php","shopping3.php"),
					array("shopping/showorder.php","showorder.php"),
					);
if($cur_page=="mobile"){
	$templates = "mobile";
	foreach($page_array as $k=>$v){
		if($last_page==$v[1])
			$locations = $v[0] . "?" . $_SERVER['QUERY_STRING'];
	}
}else{
	foreach($page_array as $k=>$v){
		if($last_page==$v[0] || $cur_page . "/" . $last_page==$v[0] || $first_page . "/" . $cur_page . "/" . $last_page==$v[0])
			$locations = "mobile/" . $v[1] . "?" . $_SERVER['QUERY_STRING'];
	}	
}



/**
 * 增加 Smarty 對 $INFO 中，有 `.` 的變數命名方式的支援
 */
foreach( $INFO as $key => $val ){
	$ary = explode( ".", $key );
	if( count($ary) > 1 ){
		$result = $val;
		for( $i=count($ary)-1; $i > 0 ; $i-- ){
			$kk = $ary[$i];
			$result = array( $kk => $result );
		}

		if( !array_key_exists(  $ary[0], $INFO ) ){
			$INFO[ $ary[0] ] = array();
		}

		$INFO[ $ary[0] ] = array_merge_recursive( $INFO[ $ary[0] ], $result );
	}
}


/**
 * 將原本夾帶在 Smarty.class.php 裡的程式，移出來到這裡
 */
if(trim($_GET['RID'])!=""){
	setcookie("RID", $_GET['RID'],time()+60*60*24,"/"); 
}
if(trim($_GET['u'])!=""){
	setcookie("u_recommendno", $_GET['u'],time()+60*60*24,"/"); 
}

/**
 * 装载Smarty.class.php,并实例化对象，在这里将设置Smarty的左右分界符号为<{ 与 }>
 */
include_once('Resources/Smarty/libs/Smarty.class.php');
$tpl = new Smarty();                                           //建立smarty实例对象$smarty
$tpl->debugging = false;
if( $INFO['tmpl.debug'] == "yes" ){
	$tpl->debug_tpl      = "";
	$tpl->debugging_ctrl = ( $_SERVER['SERVER_NAME'] == 'localhost' || strpos( $_SERVER['SERVER_NAME'], 'ddcs.com.tw') !== false ) ? 'URL' : 'NONE';
}
$tpl->template_dir   = Templates."/".$templates;                    //设置模板目录
$tpl->compile_dir    = Templates."/".$templates."/templates_c";     //设置编译目录
$tpl->cache_dir      = $doc_root."/cache";                     //设置缓存目录
//$tpl->cache_lifetime = 60 * 60 * 24;                           //设置缓存时间
$tpl->cache_lifetime = 0;                           //设置缓存时间
$tpl->caching        = false;                                  //这里是调试时设为false,发布时请使用true
$tpl->left_delimiter = '<{';
$tpl->right_delimiter= '}>';



/**
 *  软件适用地区信息
 *  例如：$VersionArea = "gb","big5";
 */
$VersionArea = "big5";
define("VersionArea",$VersionArea);




/**
 * 在这里将部分系统主要变量生成到摸板上，以便于调用。
 */
$tpl->assign("template_dir",  $INFO['site_url']."/templates/".$templates); //摸板路径
$tpl->assign("Site_Url",      $INFO['site_url']); //主站URL
$tpl->assign("LanguageIs",    $INFO['IS']); //语言包类型
$tpl->assign("advs_pic_path", $INFO['advs_pic_path']); //广告图片路径
$tpl->assign("good_pic_path", $INFO['good_pic_path']); //产品图片路径
$tpl->assign("HtmlTitle",     $INFO['site_title']);     //TITLE内容
$tpl->assign("Meta_keyword",  $INFO['meta_keyword']);   //META内容
$tpl->assign("Meta_desc",     $INFO['meta_desc']);      //META内容
$tpl->assign("SiteName",      $INFO['site_name']);      //站点名称
$tpl->assign("mobile_title",  $INFO['mobile_title']); 
$tpl->assign("shop_Meta_keyword",  $INFO['shop_meta_keyword']);   //META内容
$tpl->assign("shop_Meta_desc",     $INFO['shop_meta_desc']);      //META内容
$tpl->assign("shop_site_name",      $INFO['shop_site_name']);      //站点名称

$tpl->assign("RootDocument",         $doc_root);
$tpl->assign("RootDocumentShare",    $doc_root."/".ConfigDir);
$tpl->assign("RootDocumentAdmin",    $doc_root."/".ShopAdmin);
$tpl->assign("OtherPach",            $OtherPach);
$tpl->assign("locations",            $locations);

$tpl->assign("ismobile",            $ismobile);
$tpl->assign($INFO);
$url_From =  trim($_SERVER['QUERY_STRING'])!="" ? "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'] : "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
$tpl->assign("url_From",      base64_encode($url_From));


if ($_GET[Action]=='phpinfo'){
	phpinfo();
}
?>
