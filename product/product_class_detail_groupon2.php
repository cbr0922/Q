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

$Query   = $DB->query("select bid,subject_id from  `{$INFO[DBPrefix]}groupclass` where catiffb=1 and bid=".intval($bid)." limit 0,1");
$Num   = $DB->num_rows($Query);
if ( $Num==0 ){ //如果不存在资料
	$FUNCTIONS->header_location("index.php");
}
$Rs = $DB->fetch_array($Query);
$subject_id = $Rs['subject_id'];
$DB->free_result($Query);


//轮播变量
if ($subject_id!=""){
$Sql_top = "select g.* from `{$INFO[DBPrefix]}groupsubject` as g where g.gsid in (" .$subject_id  . ") and g.subject_open =1 and g.ifrecommend=1 order by rand() limit 0,2";
$Query_top  = $DB->query($Sql_top);

$i=0;
$j=1;
$Sql_level = "";
$ProTop_array = array();

while ( $ProNav = $DB->fetch_array($Query_top)){
	$ProTop_array[$i][subject_name] = $ProNav['subject_name'];
	$ProTop_array[$i][start_date] = $ProNav['start_date'];
	$ProTop_array[$i][end_date] = $ProNav['end_date'];
	$ProTop_array[$i][gsid] = $ProNav['gsid'];
	$ProTop_array[$i][min_money] = $ProNav['min_money'];
	$ProTop_array[$i][min_count] = $ProNav['min_count'];
	$ProTop_array[$i][manyunfei ] = $ProNav['manyunfei '];
	$ProTop_array[$i][buygrouppoint] = $ProNav['buygrouppoint'];
	$ProTop_array[$i][pic] = $ProNav['pic'];

	$i++;
	$j++;
}
//print_r($ProTop_array);
$tpl->assign("ProTop_array",       $ProTop_array);

$Sql = "select g.*  from `{$INFO[DBPrefix]}groupsubject` g where g.gsid in (" .$subject_id  . ") and g.subject_open =1 order by gsid desc";

$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;
$sale_p_array = array();
if ($Num>0){
	$i=0;
	$j=1;
	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$sale_p_array[$i][subject_name] = $ProNav['subject_name'];
		$sale_p_array[$i][start_date] = $ProNav['start_date'];
		$sale_p_array[$i][end_date] = $ProNav['end_date'];
		$sale_p_array[$i][gsid] = $ProNav['gsid'];
		$sale_p_array[$i][min_money] = $ProNav['min_money'];
		$sale_p_array[$i][min_count] = $ProNav['min_count'];
		$sale_p_array[$i][manyunfei ] = $ProNav['manyunfei '];
		$sale_p_array[$i][buygrouppoint] = $ProNav['buygrouppoint'];
		$sale_p_array[$i][pic] = $ProNav['pic'];
		$i++;
		$j++;
	}
	$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条
}
}
//echo "cccccccccc";

$tpl->assign("sale_p_array",       $sale_p_array);
//print_r($sale_p_array);
$tpl->assign($Good);
$tpl->display("product_class_detail_groupon2.html");
?>