<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");
include("product.class.php");
$PRODUCT = new PRODUCT();

$bid  = $_GET['bid'];

if ($bid>0){
	$PRODUCT->getBanner($bid);   //導航
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
}

//商品列表
$product_array = $PRODUCT->getProductList($bid,"attr",array(),array($_GET['orderby'],$_GET['ordertype']),0,1,1,0,1);



	$class_sql = "select a.attrid,a.attributename from `{$INFO[DBPrefix]}attribute` as a";
	if (intval($bid)>0){
		$class_sql = "select ac.attrid,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac inner join `{$INFO[DBPrefix]}attribute` as a on a.attrid = ac.attrid";
		$class_sql .= " where ac.cid='" . intval($bid) . "' ";
	}
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
			if($_GET['valueid'] == $Rs_value['valueid']){
				$valuename = $Rs_value['value'];
				$valuecontent = $Rs_value['content'];
			}
			$iv++;
		}
		$ic++;
	}
	//print_r($attr_class);
	$tpl->assign("attr_array",  $attr_class);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($Good);
//print_r($product_array);
$tpl->assign("product_array",     $product_array);
$tpl->assign("valuename",     $valuename);
$tpl->assign("valuecontent",     $valuecontent);
$tpl->display("product_attr.html");
?>
