<?php
/**
 *
 * 批次改價程式
 *
 * Copyright © 2011-2012 Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>
 * All rights reserved.
 *
 */

include_once "Check_Admin.php";
require_once( "mat.php" );

/*                                                                                               
 * 通用變數及定義
 */
global $tpl;
$providers = list_providers();
$providers2 = list_providers2();

for( $i=0; $i < count($providers); $i++ ){
	$providers[$i]["total"] = 0;
	for( $j=0; $j < count($providers2); $j++ ){
		if( $providers[$i]["provider_id"] == $providers2[$j]["provider_id"] ){
			$providers[$i]["total"] = $providers2[$j]["total"];
			break;
		}
	}
}

$bclasses  = list_bclasses();

function get_provider( $id ){
	$result = "";
	global $providers;
	foreach( $providers as $provider ){
		if( $provider['provider_id'] == $id ){
			$result = $provider['provider_name'];
			break;
		}
	}

	return $result;
}

function get_provider_total( $id ){
	$result = "";
	global $providers;
	foreach( $providers as $provider ){
		if( $provider['provider_id'] == $id ){
			$result = $provider['total'];
			break;
		}
	}

	return $result;
}

function get_bclass( $id ){
	$result = "";
	global $bclasses;
	foreach( $bclasses as $bclass ){
		if( $bclass['bid'] == $id ){
			$result = $bclass['catname'];
			break;
		}
	}

	return $result;
}

function format_export_data( $goods_ary ){
	global $INFO;
	$ret_data = array();
	$ret_code = -1;

	foreach( $goods_ary as $good ){

		$item = array( 
				'bn'        => $good['bn'],
				'pricedesc' => $good['pricedesc'],
				'price'     => $good['price'],
				'cost'      => $good['cost'],
				'goodsname'      => $good['goodsname'],
				'商品類別名稱'    => get_bclass( $good['bid'] ),
				'供應商名稱'     => get_provider( $good['provider_id'] ),
				'賣場編號'     => $good['goodsno'],
				'產地'        => $good['chandi'],
				'重量'        => $good['weight'],
				'計量單位'      => $good['unit'],
				'上架狀態'      => $good['ifpub'],
				'備註'        => sprintf( "%s/product/goods_detail.php?goods_id=%s", $INFO['site_url'] ,$good['gid'] )
			     );
		array_push( $ret_data, $item );
	}

	return $ret_data;
}

/* 
 * 接受 $_GET, $_POST 之 request 參數
 */
$action      = getVar( "both", "Action", "" );
$provider_id = getVar( "both", "pid", -1 );
$bclass_id   = getVar( "both", "bid", -1 );
$begin       = getVar( "both", "beg", 0 );
$size        = getVar( "both", "size", 0 );
$filename    = getVar( "both", "filename", "goods.csv" );

$preview_cnt = 5;

/*
 * 依指定執行對應的操作
 */
$cond = " WHERE 1=1 ";
if ( $bclass_id >=0 ){
	$cond .= " AND bid=$bclass_id";
}

if ( $provider_id >=0 ){
	$cond .= " AND provider_id=$provider_id ";
}

// 匯出商品資料預覽
if ( $action=='export_preview' ) {
	$goods = list_goods( $cond, 0, $preview_cnt );

	for( $i=0; $i < count($goods); $i++ ){
		$goods[$i]['note'] = sprintf( "%s/product/goods_detail.php?goods_id=%s", $INFO['site_url'] ,$goods[$i]['gid'] );
	}

	$tpl->assign( "goods", $goods );
	$tpl->display( dirname(__FILE__)."/goods_batch/export_preview.html");
}
// 匯出商品資料 CSV 格式，供工作人員下載修改
elseif ( $action=='export' ) {
	$total = count_goods( $cond );
	$beg_idx = 0;
	$end_idx = ( $total - ( $total % $size) ) / $size ;

	$pname = get_provider( $provider_id );
	$bname = get_bclass( $bclass_id );

	$links = array();
	for( $i=0; $i <= $end_idx; $i++ ){
		$start = $i*$size + 1;
		$end   = $start + $size;
	
		// 如果是最後一筆，則用 $total 作結尾
		if( $i == $end_idx ){
			$end      = $total;
		}

		$filename = sprintf( "Goods_%s_%s_%s-%s.csv", $pname, $bname, $start, $end );
		$link     = sprintf( "admin_goods_batch.php?Action=download&bid=%s&pid=%s&beg=%s&size=%s&filename=%s", $bclass_id, $provider_id, $start-1, $size, $filename );

		$item = array ( 
				"link" => $link, 
				"filename" => $filename
			      );
		array_push( $links, $item );
	}

	$tpl->assign( "links", $links );
	$tpl->display( dirname(__FILE__)."/goods_batch/export_done.html");
}
// 匯出商品資料 CSV 格式，供工作人員下載修改
elseif ( $action=='download' ) {
	$goods = list_goods( $cond, $begin, $size );

	$output = format_export_data( $goods );

	header( 'Content-Type: text/csv' );
	header( 'Content-Disposition: attachment;filename='.$filename);
	$fp = fopen('php://output', 'w');

	if( count($output) > 0 ){
		fwrite( $fp, "\xEF\xBB\xBF");
		fputcsv( $fp, array_keys($output[0]) );
		foreach( $output as $row ){
			fputcsv($fp, $row);
		}
	}

	fclose($fp);
}
// 匯入商品資料預覽
else if ( $action=='import_preview' ) {

	$tempfile_path = get_uploadfile_path( "uploadfile" );
	if( is_file( $tempfile_path ) ){
		$csv_data = get_array_from_csv( $tempfile_path, $preview_cnt, "\t" );

		$tpl->assign( "prices", $csv_data );
	}
	$tpl->display( dirname(__FILE__)."/goods_batch/import_preview.html");
}
// 匯入商品資料 CSV 格式，執行批次改價之作業
else if ( $action=='import' ) {
	$tempfile_path = get_uploadfile_path( "uploadfile" );
	if( is_file( $tempfile_path ) ){
		$csv_data = get_array_from_csv( $tempfile_path, 0, "\t" );

		$result_ary = update_goods_prices( $csv_data );

		$tpl->assign( "num_done", $result_ary["done"] );
		$tpl->assign( "num_fail", $result_ary["fail"] );
		$tpl->assign( "ary_fail", $result_ary["fail_list"] );
	}
	$tpl->display( dirname(__FILE__)."/goods_batch/import_done.html");
}
// 顯示預設的 UI 表單
else {

	$tpl->assign( "tpl_bclasses", $bclasses );
	$tpl->assign( "tpl_providers", $providers );
	$tpl->display( dirname(__FILE__)."/goods_batch/index.html");
}

?>
