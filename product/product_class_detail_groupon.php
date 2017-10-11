<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");
include("PageNav.class.php");

$bid  = $FUNCTIONS->Value_Manage($_GET['bid'],'','back','');
$bid  =intval($bid);

$class_banner = array();
$list = 0;
function getBanner($bid){
	global $DB,$INFO,$class_banner,$list,$Bcontent;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}groupclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$class_banner[$list]['bid'] = $Result['bid'];
		$class_banner[$list]['catname'] = $Result['catname'];
		$class_banner[$list]['banner'] = $Result['banner'];
		$class_banner[$list]['catcontent'] = $Result['catcontent'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		
	}
}

if (intval($bid)>0){
	getBanner($bid);
	$tpl->assign("Bcontent",  $class_banner[0][catcontent]);
	$tpl->assign("Bname",  $class_banner[0][catname]);
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
	$tpl->assign("class_banner",     $class_banner);
	$tpl->assign("top_Bid",  $class_banner[0][bid]);
}


$Next_ArrayClass  = $FUNCTIONS->Sun_groupcon_class($bid);
$Next_ArrayClass  = explode(",",$Next_ArrayClass);
$Array_class      = array_unique($Next_ArrayClass);

foreach ($Array_class as $k=>$v){
	$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
}

$Query   = $DB->query("select bid from  `{$INFO[DBPrefix]}groupclass` where catiffb=1 and bid=".intval($bid)." limit 0,1");
$Num   = $DB->num_rows($Query);
if ( $Num==0 ){ //如果不存在资料
	$FUNCTIONS->header_location("index.php");
}
$DB->free_result($Query);


//轮播变量
$Sql_top = "select g.* from `{$INFO[DBPrefix]}groupdetail` as g where g.ifpub=1 and (g.bid='" . $bid . " '" . $Add . ") and g.ifrecommend=1 " . $subsql . " order by g.view_num desc limit 0,2";
$Query_top  = $DB->query($Sql_top);

$i=0;
$j=1;
$Sql_level = "";
$ProTop_array = array();

while ( $ProNav = $DB->fetch_array($Query_top)){
	$ProTop_array[$i][groupname] = $Char_class->cut_str($ProNav['groupname'],20);
	$ProTop_array[$i][salename] = $Char_class->cut_str($ProNav['salename'],20);
	$ProTop_array[$i][intro] = $Char_class->cut_str($ProNav['intro'],50);
		$ProTop_array[$i][groupbn] = $ProNav['groupbn'];
		$ProTop_array[$i][gdid] = $ProNav['gdid'];
		$ProTop_array[$i][price] = $ProNav['price'];
		$ProTop_array[$i][groupprice] = $ProNav['groupprice'];
		$ProTop_array[$i][grouppoint] = $ProNav['grouppoint'];
		$ProTop_array[$i][smallimg] = $ProNav['groupMimg'];

	$i++;
	$j++;
}
//print_r($ProTop_array);
$tpl->assign("ProTop_array",       $ProTop_array);

$Sql = "select g.* from `{$INFO[DBPrefix]}groupdetail` as g where g.ifpub=1 and (g.bid='" . $bid . " '" . $Add . ") " . $subsql . " order by g.view_num desc ";

$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;
$sale_p_array = array();
//echo "cccccccccc";
if ($Num>0){
	$i=0;
	$j=1;
	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$sale_p_array[$i][groupname] = $Char_class->cut_str($ProNav['groupname'],20);
		$sale_p_array[$i][salename] = $Char_class->cut_str($ProNav['salename'],20);
		$sale_p_array[$i][groupbn] = $ProNav['groupbn'];
		$sale_p_array[$i][gdid] = $ProNav['gdid'];
		$sale_p_array[$i][price] = $ProNav['price'];
		$sale_p_array[$i][groupprice] = $ProNav['groupprice'];
		$sale_p_array[$i][grouppoint] = $ProNav['grouppoint'];
		$sale_p_array[$i][smallimg] = $ProNav['groupSimg'];
		$i++;
		$j++;
	}
}
//echo "cccccccccc";
$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条
$tpl->assign("sale_p_array",       $sale_p_array);
//print_r($sale_p_array);
$tpl->assign($Good);
$tpl->display("product_class_detail_groupon.html");
?>