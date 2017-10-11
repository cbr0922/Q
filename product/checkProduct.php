<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("product.class.php");
$PRODUCT = new PRODUCT();
$gid = intval($_GET['gid']);
$detail_id = intval($_GET['detail_id']);
$checktype = intval($_GET['checktype']);
if ($_GET['color'] == "undefined")
	$_GET['color'] = "";
if ($_GET['size'] == "undefined")
	$_GET['size'] = "";
if ($_GET['act'] == "checkstorage"){
	if ($gid>0){
		echo $PRODUCT->checkStorage($gid,$detail_id,$_GET['color'],$_GET['size'],$checktype);
		exit;
	}
}
?>
