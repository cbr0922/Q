<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

if (is_file("configs.inc.php")){
	include("./configs.inc.php");
}
else if (is_file("../configs.inc.php")){
	include("../configs.inc.php");
}else if (is_file("../../configs.inc.php")){
	include("../../configs.inc.php");
}
if (intval($INFO['siteOpen'])==0){
	echo "<Br><br><div align='center'>網站正在維護中...</div>";exit;
}
include (Classes."/global.php");
include (ConfigDir."/setindex.php");

$color_array = array("#c2fcef","#ddd6ff","#c3fdc1","#ffdabf","#fbc5e7","#c3d1fb","#fbc3c3","#b0daff","#fff08b");
$colorbg_array = array("#65cfb7","#beb1fc","#77d074","#ffb077","#f08aca","#859fee","#f78b8b","#7ab9f0","#dcc73d");

//經銷商
if (trim($_GET['saler']) != ""){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}saler` where login='" . trim($_GET['saler']) . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
  
  	if ($Num>0){
  		//setcookie("saler",trim($_GET['saler']),time()+60*60*24*300,"/");
		$_SESSION['saler'] = trim($_GET['saler']);
	}
}
if ($_SESSION['saler']!="" )
	$_SESSION['saler'] = $_SESSION['saler'];

/**
 *主页面LOGO的尺寸
 */ 
$tpl->assign("logo_width",  $INFO["logo_width"]);
$tpl->assign("logo_height", $INFO["logo_height"]);

/**
这里是主页的标签公告ID,及高度
*/
$tpl->assign("index_iframe_height",  $INFO['index_iframe_height']);
$tpl->assign("index_iframe_id", $INFO['index_iframe_id']);

$i=0;
$Query   = $DB->query("select * from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id=0 and bid>2 order by catord,bid  asc");
while ( $ClassRow = $DB->fetch_array($Query)){
	
	$ClassArray[$i]['classname'] = $ClassRow['catname'];
	$ClassArray[$i]['bid'] = $ClassRow['bid'];
	$ClassArray[$i]['bordercolor'] = $color_array[$i%9];
	$ClassArray[$i]['bgcolor'] = $colorbg_array[$i%9];
	$z=0;
	$Sql_bclass    = "select bid,catname,pic1,pic2 from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $ClassRow['bid'] . "' order by catord  asc  ";
	$query_bclass  = $DB->query($Sql_bclass);
	$num_bclass    = $DB->num_rows($query_bclass);
	while($Rs_bclass =  $DB->fetch_array($query_bclass)){
		$ClassArray[$i]['sub'][$z][catname] = $Rs_bclass['catname'];
		$ClassArray[$i]['sub'][$z][bid] = $Rs_bclass['bid'];
		$z++;
	}
	
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
	$i++;
}
//print_r($ClassArray);

$tpl->assign("ClassArray",               $ClassArray);

$MemberState =  intval($_SESSION['user_id'])>0 ? 1 : 0 ;

$Query_old = $DB->query("select  c.companyname from `{$INFO[DBPrefix]}user` as u left join `{$INFO[DBPrefix]}company` as c on u.companyid=c.id where u.user_id='".intval($_SESSION['user_id'])."' limit 0,1");
$Num_old   = $DB->num_rows($Query_old);
$Result = $DB->fetch_array($Query_old);

$tpl->assign("companyname", $Result['companyname']); //登陆后用户名

$tpl->assign("Session_truename", $_SESSION['true_name']); //登陆后用户名
$tpl->assign("Session_userlevel",$_SESSION['userlevelname']); //登陆后用户等级
$tpl->assign("MemberState", intval($MemberState)); //用户状态
if (trim($_GET[url])!=""){
	$en_url_From = trim($_GET[url]);
	$tpl->assign("en_url_From", $en_url_From); //获得来自何方的URL
}

$tpl->assign("subclass",               $subclass);
$tpl->assign($Bottom_Pack);
$tpl->display("menu.html");
?>

