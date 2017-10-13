<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
if (intval($INFO['siteOpen'])==0){
	echo "<Br><br><div align='center'>網站正在維護中...</div>";exit;
}
include (Classes."/global.php");
include (ConfigDir."/setindex.php");

/** 裝載語言包*/
include RootDocument."/language/".$INFO['IS']."/Menu_Pack.php";

//經銷商
if (trim($_GET['saler']) != "" ){
	$_SESSION['saler'] = trim($_GET['saler']);
}
if (trim($_SESSION['saler']) != "" ){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}saler` where login='" . trim($_SESSION['saler']) . "' and ifpub=1 and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
	$Num   = $DB->num_rows($Query);
  	if ($Num>0){
  		//setcookie("saler",trim($_GET['saler']),time()+60*60*24*300,"/");
		$_SESSION['saler'] = trim($_SESSION['saler']);
	}else{
		$_SESSION['saler'] = "";
	}
}
/**
 *LOGO的尺寸
 */
$tpl->assign("logo_width",  $INFO["logo_width"]);
$tpl->assign("logo_height", $INFO["logo_height"]);
/**
這裡是首頁的標籤公告ID,及高度
*/
$tpl->assign("index_iframe_height",  $INFO['index_iframe_height']);
$tpl->assign("index_iframe_id", $INFO['index_iframe_id']);

$weeklist = array('(日)', '(一)', '(二)', '(三)', '(四)', '(五)', '(六)');
$begtime = date("Y-m-d ",time()+4*60*60);
$endtime = date("Y-m-d ",time()+(7*24*60*60)+(4*60*60));
$begweek = date('w', time()+4*60*60);
$endweek = date('w', time()+(7*24*60*60)+(4*60*60));
$begtime = str_replace (" ",$weeklist[$begweek]." ",$begtime);
$endtime = str_replace (" ",$weeklist[$endweek]." ",$endtime);
$tpl->assign("begtime", $begtime);
$tpl->assign("endtime", $endtime);

$i=0;
$Query   = $DB->query("select * from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id=0 and bid>0 order by catord,bid  asc");
while ( $ClassRow = $DB->fetch_array($Query)){
	$ClassArray[$i]['classname'] = $ClassRow['catname'];
	$ClassArray[$i]['banner'] = $ClassRow['banner'];
	$ClassArray[$i]['banner2'] = $ClassRow['banner2'];
	$ClassArray[$i]['link'] = $ClassRow['link'];
	$ClassArray[$i]['bid'] = $ClassRow['bid'];
	$ClassArray[$i]['url'] = $ClassRow['url'];
	$ClassArray[$i]['bordercolor'] = $color_array[$i%9];
	$ClassArray[$i]['bgcolor'] = $colorbg_array[$i%9];

	$k=0;
	//$class_string = "('".implode("','",explode(",",$ClassRow['brandlist']))."')";
	//$Query_class = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id in ".$class_string." order by brand_id asc");
	$Query_class = $DB->query("SELECT brand_id,brandname FROM `{$INFO[DBPrefix]}brand` where bdiffb=1 and classid REGEXP '^". $ClassRow['bid'] ."$' OR classid REGEXP '^". $ClassRow['bid'] .",' OR classid REGEXP ',". $ClassRow['bid'] .",' OR classid REGEXP ',". $ClassRow['bid'] ."$' order by orderby asc,brand_id asc");
	while ($Result_class = $DB->fetch_array($Query_class)){
		$ClassArray[$i]['brand'][$k]['brand_id'] = $Result_class['brand_id'];
		$ClassArray[$i]['brand'][$k]['brandname'] = $Result_class['brandname'];
		$k++;
	}
	$z=0;

	$Sql_bclass    = "select bid,catname,pic1,pic2,banner,url from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $ClassRow['bid'] . "' order by catord  asc,bid asc  ";
	$query_bclass  = $DB->query($Sql_bclass);
	$num_bclass    = $DB->num_rows($query_bclass);
	while($Rs_bclass =  $DB->fetch_array($query_bclass)){
		$ClassArray[$i]['sub'][$z][catname] = $Rs_bclass['catname'];
		$ClassArray[$i]['sub'][$z][banner] = $Rs_bclass['banner'];
		$ClassArray[$i]['sub'][$z][url] = $Rs_bclass['url'];
		$ClassArray[$i]['sub'][$z][bid] = $Rs_bclass['bid'];

		$sub_bid = explode(",",$Rs_bclass['bid'].",".$FUNCTIONS->Sun_pcon_class($Rs_bclass['bid']));
		array_pop($sub_bid);
		$sub_bid = array_unique($sub_bid);
		$sub_bid_str = " bid in ('".implode("','",$sub_bid)."')";
		$Sqlc = "select count(*) as totalcount from `{$INFO[DBPrefix]}goods` where " . $sub_bid_str;
		$Queryc  = $DB->query($Sqlc);
		$Resultc = $DB->fetch_array($Queryc);
		$ClassArray[$i]['sub'][$z][totalcount] = intval($Resultc['totalcount']);

		$Sql_bclass2    = "select bid,catname,banner,pic2,url from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "' order by catord  asc,bid asc  ";
		$query_bclass2  = $DB->query($Sql_bclass2);
		$num_bclass2    = $DB->num_rows($query_bclass2);
		$j = 0;
		while($Rs_bclass2 =  $DB->fetch_array($query_bclass2)){
			$ClassArray[$i]['sub'][$z]['sub'][$j][catname] = $Rs_bclass2['catname'];
			$ClassArray[$i]['sub'][$z]['sub'][$j][bid] = $Rs_bclass2['bid'];
			$ClassArray[$i]['sub'][$z]['sub'][$j][banner] = $Rs_bclass2['banner'];
			$ClassArray[$i]['sub'][$z]['sub'][$j][url] = $Rs_bclass2['url'];

			$sub_bid = explode(",",$Rs_bclass2['bid'].",".$FUNCTIONS->Sun_pcon_class($Rs_bclass2['bid']));
			array_pop($sub_bid);
			$sub_bid = array_unique($sub_bid);
			$sub_bid_str = " bid in ('".implode("','",$sub_bid)."')";
			$Sqlc = "select count(*) as totalcount from `{$INFO[DBPrefix]}goods` where " . $sub_bid_str;
			$Queryc  = $DB->query($Sqlc);
			$Resultc = $DB->fetch_array($Queryc);
			$ClassArray[$i]['sub'][$z]['sub'][$j][totalcount] = intval($Resultc['totalcount']);

			$Sql_bclass3    = "select bid,catname,banner,pic2,url from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass2['bid'] . "' order by catord  asc,bid asc  ";
			$query_bclass3  = $DB->query($Sql_bclass3);
			$num_bclass3    = $DB->num_rows($query_bclass3);
			$t = 0;
			while($Rs_bclass3 =  $DB->fetch_array($query_bclass3)){
				$ClassArray[$i]['sub'][$z]['sub'][$j]['sub'][$t][catname] = $Rs_bclass3['catname'];
				$ClassArray[$i]['sub'][$z]['sub'][$j]['sub'][$t][bid] = $Rs_bclass3['bid'];
				$ClassArray[$i]['sub'][$z]['sub'][$j]['sub'][$t][banner] = $Rs_bclass3['banner'];
				$ClassArray[$i]['sub'][$z]['sub'][$j]['sub'][$t][url] = $Rs_bclass3['url'];

				$sub_bid = explode(",",$Rs_bclass3['bid'].",".$FUNCTIONS->Sun_pcon_class($Rs_bclass3['bid']));
				array_pop($sub_bid);
				$sub_bid = array_unique($sub_bid);
				$sub_bid_str = " bid in ('".implode("','",$sub_bid)."')";
				$Sqlc = "select count(*) as totalcount from `{$INFO[DBPrefix]}goods` where " . $sub_bid_str;
				$Queryc  = $DB->query($Sqlc);
				$Resultc = $DB->fetch_array($Queryc);
				$ClassArray[$i]['sub'][$z]['sub'][$j]['sub'][$t][totalcount] = intval($Resultc['totalcount']);
				$t++;
			}

			$j++;
		}
		$z++;
	}
	/*
	$class_sql = "select a.attrid,a.attributename from `{$INFO[DBPrefix]}attribute` as a";
	$Query_class    = $DB->query($class_sql);
	$ic = 0;
	$attr_class = array();
	while($Rs_class=$DB->fetch_array($Query_class)){
		$attr_class[$ic]['attrid']=$Rs_class['attrid'];
		$attr_class[$ic]['bid']=$bid;
		$attr_class[$ic]['attributename']=$Rs_class['attributename'];
		$Sql_value      = "select * from `{$INFO[DBPrefix]}attributevalue` as v inner join `{$INFO[DBPrefix]}attribute` as a on a.attrid=v.attrid where v.attrid='" . intval($Rs_class['attrid']) . "' order by valueid desc ";
		$Query_value     = $DB->query($Sql_value );
		$iv = 0;
		while ($Rs_value =$DB->fetch_array($Query_value)) {
			$attr_class[$ic]['value'][$iv]['valueid'] = $Rs_value['valueid'];
			$attr_class[$ic]['value'][$iv]['value'] = $Rs_value['value'];
			if($_GET['valueid'] == $Rs_value['valueid'])
				$valuename = $Rs_value['value'];
			$iv++;
		}
		$ic++;
	}
	$tpl->assign("attr_array",  $attr_class);
	*/
	$i++;
}
//print_r($ClassArray);

include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");

$Sql =  "select ncid,ncname,ncimg,top_id from  `{$INFO[DBPrefix]}nclass`  where  ncatiffb=1 and top_id=0 order by ncatord desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){

	$i=0;
	while ( $Rs = $DB->fetch_array($Query)){
		$Ncat[$i][ncid]        =  $Rs['ncid'];
        $Ncat[$i][ncname]      =  $Rs['ncname'];
		$Ncat[$i][ncimg]       =  $Rs['ncimg'];
		$Ncat[$i][top_id]      =  $Rs['top_id'];

$Sqlcount =  "select count(top_id) AS top_idcount from  `{$INFO[DBPrefix]}news`  where top_id=".$Rs['ncid'];
$Querycount =  $DB->query($Sqlcount);
$Numcount   =  $DB->num_rows($Querycount);
if ($Numcount>0){

	$j=0;
	while ( $Rscount = $DB->fetch_array($Querycount)){
		$Ncat[$i][count1]  =  $Rscount['top_idcount'];
		$j++;
	}
}
		$i++;
    }

}
$tpl->assign("Ncat", $Ncat);
$tpl->assign($Article_Pack);
$tpl->assign("m_Num",     $num_bclass);
$tpl->assign("ClassArray",               $ClassArray);
$MemberState =  intval($_SESSION['user_id'])>0 ? 1 : 0 ;
$Query_old = $DB->query("select  c.companyname from `{$INFO[DBPrefix]}user` as u left join `{$INFO[DBPrefix]}company` as c on u.companyid=c.id where u.user_id='".intval($_SESSION['user_id'])."' limit 0,1");
$Num_old   = $DB->num_rows($Query_old);
$Result = $DB->fetch_array($Query_old);
$tpl->assign("companyname", $Result['companyname']); //登入後用會員
$tpl->assign("Session_truename", $_SESSION['true_name']); //登入後用會員
$tpl->assign("Session_userlevel",$_SESSION['userlevelname']); //登入後會員等级

$user_old = $DB->query("select sex from `{$INFO[DBPrefix]}user` where user_id='".intval($_SESSION['user_id'])."' limit 0,1");
$Num_old   = $DB->num_rows($user_old);
$Result1 = $DB->fetch_array($user_old);
$tpl->assign("Session_sex",$Result1['sex']);

$tpl->assign("MemberState", intval($MemberState)); //會員狀態
if (trim($_GET[url])!=""){
	$en_url_From = trim($_GET[url]);
	$tpl->assign("en_url_From", $en_url_From); //獲得來自何方的URL
}
$tpl->assign("subclass",               $subclass);
$tpl->assign("shop_logo",               $INFO['shop_logo']);
$tpl->assign($Menu_Pack);
$tpl->assign("Head_radio",               	intval($INFO['head_radio']));                    	//頭部長條廣告開關
$tpl->assign("Head_adv_tag",               $INFO['head_adv_tag']);                    				//頭部長條廣告tag
/* GTM碼 */
$track_id = '3';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = "
<!-- Google Tag Manager -->
<noscript><iframe src='//www.googletagmanager.com/ns.html?id='" . $track_array[trackcode] . "'
height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','" . $track_array[trackcode] . "');</script>
<!-- End Google Tag Manager -->
";
  }
	else $track_Js="";
	$tpl->assign("googleTagManager_js",   $track_Js);
}
/*結束GTM碼 */
$Sql = "select sum(ut.count) as count,ut.ticketid,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.userid=".intval($_SESSION['user_id'])." group by ut.ticketid";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$ticketcount = 0;
while ( $Rs = $DB->fetch_array($Query)){
		$ticketcount+=intval($Rs['count']);
}
$Sql = "select ut.ticketcode,ut.ticketid,ut.userid,ut.usetime,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}ticketcode` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.ownid=".intval($_SESSION['user_id'])." and ut.userid =0  and t.use_endtime>='" . date("Y-m-d",time()) . "'";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$ticketcount+=intval($Num);
$tpl->assign("ticketcount",               $ticketcount);
if($INFO['headerStyle'] ==0)
	$tpl->display("menu1.html");
elseif($INFO['headerStyle'] ==1)
	$tpl->display("menu2.html");
	elseif($INFO['headerStyle'] ==2)
	$tpl->display("menu3.html");
else
	$tpl->display("menu4.html");
?>
