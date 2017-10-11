<?php
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
include("product.class.php");
$PRODUCT = new PRODUCT();
$discount_array=array();
$class_banner = array();
$list = 0;
$bid = intval($_GET['bid']) ;
if ($bid>0){
	$PRODUCT->getBanner($bid);   //導航
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}
$showbid = $class_banner[0][bid];

$class_array = $PRODUCT->getProductClass($showbid);
$tpl->assign("ProductListAll",     $class_array);
$tpl->assign("class_banner",     $class_banner);
$tpl->assign("banner",     $banner);
$i = 0;
if(intval($_GET['bid'])>0){
	 $subsql = " and b.top_id='" . intval($_GET['bid']) . "' ";	
}
$Sql = "select * from `{$INFO[DBPrefix]}bclass` as b inner join `{$INFO[DBPrefix]}discountsubject` as d on b.subject_id=d.dsid where d.subject_open=1 " . $subsql . " and d.start_date<='" . date("Y-m-d",time()) . "' and d.end_date>='" . date("Y-m-d",time()) . "'";
$Query = $DB->query($Sql);
while($Rs    = $DB->fetch_array($Query)){
	$Sql_g = "select g.*,dg.price as dgprice from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where dg.dsid='" . $Rs['dsid'] . "' and g.ifchange!=1 and g.ifpub=1 and dg.ifcheck=1 order by idate desc limit 0,6";
	$Query_g = $DB->query($Sql_g);
	$discount_array[$i]['subject_name'] = $Rs['subject_name'];
	$discount_array[$i]['subject_content'] = $Rs['subject_content'];
	$discount_array[$i]['remark'] = $Rs['remark'];
	$discount_array[$i]['dis_pic'] = $Rs['pic'];
	$discount_array[$i]['dsid'] = $Rs['dsid'];
	$discount_array[$i]['banner2'] = $Rs['banner2'];
	$j = 0;
	while($Rs_g = $DB->fetch_array($Query_g)){
		$discount_array[$i]['goods'][$j]['goodsname'] = $Rs_g['goodsname'];
		$discount_array[$i]['goods'][$j]['price'] = $Rs_g['price'];
		$discount_array[$i]['goods'][$j]['pricedesc'] = $Rs_g['pricedesc'];
		$discount_array[$i]['goods'][$j]['gid'] = $Rs_g['gid'];
		$discount_array[$i]['goods'][$j]['sale_name'] = $Rs_g['sale_name'];
		$discount_array[$i]['goods'][$j]['smallimg'] = $Rs_g['middleimg'];
		$j++;
	}
	$i++;
}
//print_r($discount_array);
$tpl->assign("discount_array",     $discount_array);
$tpl->display("discountsubject_main.html");
?>