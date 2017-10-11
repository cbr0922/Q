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
$Sql_sub   = " select * from `{$INFO[DBPrefix]}discountsubject` where subject_open=1 and start_date<='" . date("Y-m-d",time()) . "' and end_date>='" . date("Y-m-d",time()) . "'";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][dsid]    =  $Rs_sub['dsid'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$Array_sub[$sub_i][start_date]  =  $Rs_sub['start_date'];
	$Array_sub[$sub_i][end_date]  =  $Rs_sub['end_date'];
	$Array_sub[$sub_i][min_money]  =  $Rs_sub['min_money'];
	$Array_sub[$sub_i][min_count]  =  $Rs_sub['min_count'];
	$Array_sub[$sub_i][mianyunfei]  =  $Rs_sub['mianyunfei'];
	$sub_i++;
}

$tpl->assign("Array_sub",       $Array_sub);
$Sql_sub   = " select subject_name,subject_id from `{$INFO[DBPrefix]}subject` where subject_open=1  and starttime<='" .time() . "' and endtime>='" . time() . "' order by subject_num desc ";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][subject_id]    =  $Rs_sub['subject_id'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$sub_i++;
}

$tpl->assign("Array_sub1",       $Array_sub);

$class_array = $PRODUCT->getProductClass($showbid);
$tpl->assign("ProductListAll",     $class_array);
$tpl->assign("class_banner",     $class_banner);
$tpl->assign("banner",     $banner);
$i = 0;
if(intval($_GET['bid'])>0){
	 $subsql = " and b.top_id='" . intval($_GET['bid']) . "' ";
}
$Sql = "select * from `{$INFO[DBPrefix]}discountsubject` as d where d.subject_open=1 and d.start_date<='" . date("Y-m-d",time()) . "' and d.end_date>='" . date("Y-m-d",time()) . "'";
//$Sql = "select * from `{$INFO[DBPrefix]}discountsubject` as d left join `{$INFO[DBPrefix]}bclass` as b  on b.subject_id=d.dsid where d.subject_open=1 " . $subsql . " and d.start_date<='" . date("Y-m-d",time()) . "' and d.end_date>='" . date("Y-m-d",time()) . "'";
$Query = $DB->query($Sql);
while($Rs    = $DB->fetch_array($Query)){
	$Sql_g = "select g.*,dg.price as dgprice from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where dg.dsid='" . $Rs['dsid'] . "' and g.ifchange!=1 and g.ifpub=1".$subSql." and dg.ifcheck=1 order by idate desc limit 0,6";
	$Query_g = $DB->query($Sql_g);
	$discount_array[$i]['subject_name'] = $Rs['subject_name'];
	$discount_array[$i]['subject_content'] = $Rs['subject_content'];
	$discount_array[$i]['remark'] = $Rs['remark'];
	$discount_array[$i]['dis_pic'] = $Rs['pic'];
	$discount_array[$i]['dsid'] = $Rs['dsid'];
	$discount_array[$i]['banner2'] = $Rs['banner2'];
	
	if($discount_array[$i]['dis_pic'] != ""){$discount_array[$i]['maxnum']=3;}
	else{$discount_array[$i]['maxnum']=6;}
	
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
$tpl->assign("discount_array",     $discount_array);
$discount_array = array();
$i = 0;
$Sql   = " select * from `{$INFO[DBPrefix]}subject` where subject_open=1 and starttime<='" . time() . "' and endtime>='" . time() . "' order by subject_num desc ";
$Query = $DB->query($Sql);
while($Rs    = $DB->fetch_array($Query)){
	$discount_array[$i] = array();
	$Sql_g = "select * from `{$INFO[DBPrefix]}goods` g  inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid ) where (g.subject_id like '".$Rs['subject_id']."' or  g.subject_id like '%,".$Rs['subject_id'].",%' or g.subject_id like '%,".$Rs['subject_id']."' or g.subject_id like '".$Rs['subject_id'].",%') and b.catiffb=1 and g.ifpub=1 and g.ifjs!=1 and g.ifbonus!=1  order by idate desc limit 0,6 ";
	$Query_g = $DB->query($Sql_g);
	$discount_array[$i]['subject_name'] = $Rs['subject_name'];
	$discount_array[$i]['subject_content'] = $Rs['subject_content'];
	$discount_array[$i]['subject_id'] = $Rs['subject_id'];
	$discount_array[$i]['banner'] = $Rs['banner'];

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
$Sql_sub   = " select * from `{$INFO[DBPrefix]}subject_redgreen` where subject_open=1 and start_date<='" . date("Y-m-d",time()) . "' and end_date>='" . date("Y-m-d",time()) . "'";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][dsid]    =  $Rs_sub['rgid'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$Array_sub[$sub_i][start_date]  =  $Rs_sub['start_date'];
	$Array_sub[$sub_i][end_date]  =  $Rs_sub['end_date'];
	$Array_sub[$sub_i][min_money]  =  $Rs_sub['min_money'];
	$Array_sub[$sub_i][min_count]  =  $Rs_sub['min_count'];
	$Array_sub[$sub_i][mianyunfei]  =  $Rs_sub['mianyunfei'];
	$Array_sub[$sub_i][subject_content]  =  $Rs_sub['subject_content'];
	$sub_i++;
}

$tpl->assign("Array_sub",       $Array_sub); 
//print_r($discount_array);
$tpl->assign("discount_array2",     $discount_array);
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));//耳朵广告开关
$tpl->display("discountsubject_main_all.html");
?>
