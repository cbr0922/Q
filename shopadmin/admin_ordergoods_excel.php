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
	$Creatfile ="order_".date("Y-m-d");
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
	@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");

$FUNCTIONS->setLog("匯出訂單庫存");
$file_string .= ",序號,商品品號,商品名稱,尺寸,顏色,規格,預計庫存量,目前庫存量\n";
echo iconv("UTF-8","big5",$file_string);



$Where    = $_GET['skey']!="" ?  " and ( g.goodsname like '%".trim(urldecode($_GET['skey']))."%' ) "  : $Where ;
$Where2    = $_GET['skey']!="" ?  " and ( od.goodsname like '%".trim(urldecode($_GET['skey']))."%' )"  : $Where2 ;

$Sql      = "select g.*,bc.* from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid)  where 1=1 ";


if($_GET['provider'] != "na" && isset($_GET['provider'])){
	if($_GET['provider'] == "yes"){
		$Where .= " AND provider_id=".intval($_GET['provider_id']);
	}else if($_GET['provider'] == "no"){
		if($_GET['provider_id'] == 0 && isset($_GET['provider_id'])){
			$Where .= " AND provider_id>".intval($_GET['provider_id']);
		}else{
			$Where .= " AND provider_id=".intval($_GET['provider_id']);
		}
		if($_GET['item_id']!="" && isset($_GET['item_id'])){
			$Where .= " AND `gid`=".intval($_GET['item_id']);
		}
	}
}

if($_GET['top_id'] != 0 && isset($_GET['top_id'])){
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
	$Next_ArrayClass  = array_filter(explode(",",$Next_ArrayClass));
	$Array_class      = array_unique($Next_ArrayClass);
	//print_r($Array_class);
	if (count($Array_class)>0){
		$top_ids = intval($_GET['top_id']).",".implode(",",$Array_class);
		$Where .= " AND g.bid in (".$top_ids.")";
	}else{
		$top_ids = intval($_GET['top_id']);
		$Where .= " AND g.`bid`=".$top_ids;
	}
}

$Sql      = $Sql.$Where." group by g.gid order by g.idate desc , g.goodorder desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i = 0;
while ($Rs=$DB->fetch_array($Query)) {
		$storage = $Rs['storage'] != '' ? $Rs['storage'] : 0 ;
		$sales	= $Rs['sales'] != '' ? $Rs['sales'] : 0 ;
		$Rs['goodsname'] = str_replace("\r","",$Rs['goodsname']);
		$Rs['goodsname'] = str_replace("\n","",$Rs['goodsname']);

		$size_Sql = "select * from `{$INFO[DBPrefix]}storage` where goods_id='" . $Rs['gid'] . "' and (color!='' or size!='')";
		$size_Query =  $DB->query($size_Sql);
		$size_Num   =  $DB->num_rows($size_Query );
		if ($size_Num>0){
			while ($size_Rs = $DB->fetch_array($size_Query)) {
				$sales += intval($size_Rs['sales']);
			}
		}
		$detail_Sql   =  "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $Rs['gid'] . "'";
		$detail_Query =  $DB->query($detail_Sql);
		$detail_Num   =  $DB->num_rows($detail_Query );
		if ($detail_Num>0){
			while ($detail_Rs = $DB->fetch_array($detail_Query)) {
				$sales += intval($detail_Rs['sales']);
			}
		}

		$file_string_m = "," . ($i+1) . "," .$Rs['bn'] . "," . iconv("UTF-8","big5",$Rs['goodsname']) . ",,,," . $sales  . "," . $storage  . "\n";
		if (trim($Rs['good_color'])!=""){
			$Good_color_array    =  explode(',',trim($Rs['good_color']));

			if (!is_array($Good_color_array)){
				$Good_color_array = array("");
			}
		}else {
			$Good_color_array = array("");
		}
		if (trim($Rs['good_size'])!=""){
			$Good_size_array    =  explode(',',trim($Rs['good_size']));

			if (!is_array($Good_color_array)){
				$Good_size_array = array("");
			}
		}else {
			$Good_size_array = array("");
		}
		$Sql_s = "select * from `{$INFO[DBPrefix]}storage` where goods_id='" . $Rs['gid'] . "' and (color!='' or size!='')";
		$Query_s    = $DB->query($Sql_s);
		$file_string_c = "";
		while ($Rs_s=$DB->fetch_array($Query_s)) {
			if ((in_array($Rs_s['color'],$Good_color_array) || trim($Rs_s['color']) == "") && (in_array($Rs_s['size'],$Good_size_array) || trim($Rs_s['size'])=="")){
				$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . $Rs['gid'] . "' and size='" . $Rs_s['size'] . "' and color='" . $Rs_s['color'] . "'";
			$goods_Query =  $DB->query($goods_Sql);
			$goods_Num   =  $DB->num_rows($goods_Query );
			if ($goods_Num>0){
				$goods_Rs = $DB->fetch_array($goods_Query);
				$goodsno = ($goods_Rs['goodsno']);
			}
				$file_string_c .=",," .$goodsno . ",," . iconv("UTF-8","big5",$Rs_s['size']) . "," . iconv("UTF-8","big5",$Rs_s['color']) . ",," . $Rs_s['sales']  . "," . $Rs_s['storage']  . "\n";
			}
		}
		$Sql_s = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $Rs['gid'] . "' ";
		$Query_s    = $DB->query($Sql_s);
		$file_string_d = "";
		while ($Rs_s=$DB->fetch_array($Query_s)) {
			$file_string_d .=",," .$Rs_s['detail_bn'] . ",,,," . iconv("UTF-8","big5",$Rs_s['detail_name']) . "," . $Rs_s['sales']  . "," . $Rs_s['storage']  . "\n";
		}
		if($file_string_c!="" || $file_string_d!=""){
			echo "*" . $file_string_m . $file_string_c . $file_string_d;
		}else{
			echo $file_string_m;
		}
		$i++;
}
?>
