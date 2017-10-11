<?php
@ob_start();
//session_start();
include "Check_Admin.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
@ob_implicit_flush(0);
$Creatfile ="provider_".date("Y-m-d");
@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
	
$file_string .= "類別ID,商品類別名稱,父級類別,本周特殺對應文章分類,顯示順序,毛利,meta description,meta keyword,詳細描述,促銷折扣,促銷成本價百分比\n";
echo iconv("UTF-8","big5",$file_string);
	
$Sql      = "select * from `{$INFO[DBPrefix]}bclass`";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
while ($Rs=$DB->fetch_array($Query)) {
	$memo = str_replace(",","，",$Rs['catcontent']);
	$memo = str_replace("\"","“",$memo);
	$memo = str_replace("\r"," ",$memo);
	$memo = str_replace("\n"," ",$memo);
	
	$meta_des = str_replace(",","，",$Rs['meta_des']);
	$meta_des = str_replace("\"","“",$meta_des);
	$meta_des = str_replace("\r"," ",$meta_des);
	$meta_des = str_replace("\n"," ",$meta_des);
	
	$meta_key = str_replace(",","，",$Rs['meta_key']);
	$meta_key = str_replace("\"","“",$meta_key);
	$meta_key = str_replace("\r"," ",$meta_key);
	$meta_key = str_replace("\n"," ",$meta_key);
	
	echo $file_string = $Rs['bid'] . "," . iconv("UTF-8","big5",$Rs['catname']) . "," . iconv("UTF-8","big5",$Rs['top_id']) . "," . iconv("UTF-8","big5",$Rs['nclass']) . "," . iconv("UTF-8","big5",$Rs['catord']) . "," . iconv("UTF-8","big5",$Rs['gain']) . "," . iconv("UTF-8","big5",$meta_des) . ","  . iconv("UTF-8","big5",$meta_key) . "," . iconv("UTF-8","big5",$memo) . "," . iconv("UTF-8","big5",$Rs['rebate']) . "," . iconv("UTF-8","big5",$Rs['costrebate']) . "\n";
}
?>