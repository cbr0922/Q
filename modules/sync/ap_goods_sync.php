<?php
/**
 * 主程式
 *
 * @copyright Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>. All rights reserved.
 */

require_once( dirname(__FILE__).'/common.php' );
require_once( dirname(__FILE__).'/init.php' );


echo("stage1. 更新 ntssi_goods_sync 中 local 區的狀態\n");
{
	// 先更新本地站的資訊
	update_sync_from_local( $var_site );
}

echo("stage2. 更新 ntssi_goods_sync 中 remote 區的狀態\n");
{
	// 再一一更新遠端站的資訊
	foreach( $var_sites as $site ){
		if ( $site['id'] != $var_site ){
			update_sync_from_remote( $site );
		}
	}
}

echo("stage3. 根據更新後的 ntssi_goods_sync 資訊，更新 ntssi_goods 的項目\n");
{
	foreach( $var_sites as $site ){
		if( $site['id'] != $var_site ){
			update_goods_from_sync( $site );
		}
	}
}

echo("stage4. 再更新 ntssi_goods_sync 中 local 區的狀態一次\n");
{
	// 先更新本地站的資訊
	update_sync_from_local( $var_site );
}

/**
 * 依 local 的 ntssi_goods 的資料，更新 ntssi_goods_sync 狀態中 local 的部份
 * 
 * @param $site 網站
 */
function update_sync_from_local( $site ) {
	global $var_page_size;

	$db = Database::getInstance();

	$result = $db->query( "SELECT gid FROM ntssi_goods ORDER BY gid ASC LIMIT 1" );
	$goods_min_idx = $result[0]['gid'];

	$result = $db->query( "SELECT gid FROM ntssi_goods ORDER BY gid DESC LIMIT 1" );
	$goods_max_idx = $result[0]['gid'];

	$pages = make_pagination( $goods_min_idx, $goods_max_idx, $var_page_size );
	foreach( $pages as $page ){
		$min = $page['min'];
		$max = $page['max'];
		echo "update_sync_from_local_by_range( $site, $min, $max )\n";
		update_sync_from_local_by_range( $site, $min, $max );
	}
}

/**
 * 更新 local 端的 ntssi_goods <-> ntssi_goods_sync 的 mapping
 *
 * @param $site
 * @param $min
 * @param $max
 */
function update_sync_from_local_by_range( $site, $min, $max ) {
	$mem_beg = memory_get_usage();
	$cur_time = time();

	$ary_goods = array();
	$ary_sync  = array();
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	$db = Database::getInstance();

	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );
	// 取出 goods 的內容
	$col_keys = " `goodsname`, `intro`, `pricedesc`, `price`, `js_price`, `gimg`, `body`, `cost`, `goodsno`, `checkstate`";
	$query = "SELECT `gid`,`bn`, $col_keys "
		. " FROM ntssi_goods "
		. " WHERE $min <= `gid` AND `gid` < $max "
		. " AND `gid` > 0 AND `bn` != '' "
		;
	echo "$query\n";

	$result = $db->query( $query );
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );
	if( $result ){
		foreach( $result as $row ){
			$row['key']    = key_of_goods( $row );
			$row['chksum'] = checksum_goods( $row );
			array_push( $ary_goods, $row );
		}
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	// 取出 goods_sync 的內容
	$query = "SELECT * "
		. " FROM ntssi_goods_sync "
		. " WHERE site='$site' "
		. " AND ( $min <= `gid` AND `gid` < $max )"
		. " AND `gid` > 0 "
		;

	$result = $db->query( $query );
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );
	if( $result ){
		foreach( $result as $row ){
			array_push( $ary_sync, $row );
		}
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	//
	// 比對 goods 跟 sync 的項目，並分類
	//
	$ary_goods_gt_sync = array(); // ntssi_goods 有   ntssi_goods_sync 沒有
	$ary_goods_lt_sync = array(); // ntssi_goods 沒有 ntssi_goods_sync 有
	$ary_goods_eq_sync = array(); // ntssi_goods 有   ntssi_goods_sync 有
	$ary_goods_ne_sync = array(); // ntssi_goods 有   ntssi_goods_sync 有，但非 1-1 對應
	$eqs_ary = array();

	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	foreach( $ary_goods as $item ){
		$key = $item['key'];
		$gid = $item['gid'];

		$matches_sync  = find_by_key_and_gid( $key, $gid, $ary_sync );
		//$matches_goods = find_by_key_and_gid( $key, $gid, $ary_goods );
		if( count($matches_sync) == 1 ){
			$itm = array(
					"good" => $item
					,"sync" => $matches_sync[0]
				    );

			$kk = $itm["good"]["key"].$itm["good"]["gid"].$itm["sync"]["key"].$itm["sync"]["gid"];
			if( !in_array( $kk, $eqs_ary ) ){
				array_push( $ary_goods_eq_sync, $itm );
				array_push( $eqs_ary, $kk );
			}
		}
		// ntssi_goods_sync 沒有對應的項目
		else if( count($matches_sync) == 0 ){
			array_push( $ary_goods_gt_sync, $item );
		}
		else{
			array_push( $ary_goods_ne_sync, $item ); // 例外處理
		}
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	foreach( $ary_sync as $item ){
		$key = $item['key'];
		$gid = $item['gid'];

		//$matches_sync  = find_by_key_and_gid( $key, $gid, $ary_sync );
		$matches_goods = find_by_key_and_gid( $key, $gid, $ary_goods );
		if( count($matches_goods) == 1 ){
			$itm = array(
					"good"  => $matches_goods[0]
					,"sync" => $item
				    );

			$kk = $itm["good"]["key"].$itm["good"]["gid"].$itm["sync"]["key"].$itm["sync"]["gid"];
			if( !in_array( $kk, $eqs_ary ) ){
				array_push( $ary_goods_eq_sync, $itm );
				array_push( $eqs_ary, $kk );
			}
		}
		// ntssi_goods 沒有對應的項目
		else if( count($matches_goods) == 0 ){ 
			array_push( $ary_goods_lt_sync, $item );
		}
		else{
			array_push( $ary_goods_ne_sync, $item ); // 例外處理
		}
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	//
	// 針對不同的狀況作處理
	//

	// ntssi_goods 有   ntssi_goods_sync 沒有
	foreach( $ary_goods_gt_sync as $item ){
		$ret = goods_sync_add_from_good( $item, $site, $cur_time );
		if( $ret != 0 ){
			echo "goods_sync_add_from_good() 回傳 $ret\n";
			show_item( $item );
		}
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	// ntssi_goods 沒有 ntssi_goods_sync 有
	foreach( $ary_goods_lt_sync as $item ){
		$ret = goods_sync_del_from_good( $item, $site );
		if( $ret != 0 ){
			echo "goods_sync_del_from_good() 回傳 $ret\n";
			show_item( $item );
		}
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	// ntssi_goods 有   ntssi_goods_sync 有
	foreach( array_chunk( $ary_goods_eq_sync, 200 ) as $ary ){
		$keys = array();
		foreach( $ary as $item ){
			$itm_good    = $item["good"];
			$itm_sync    = $item["sync"];

			$key = $itm_good['key'];
			$gid = $itm_good['gid'];

			if( $itm_good['chksum'] != $itm_sync['chksum'] ){
				echo( " ($key, $gid) 在 ntssi_goods/ntssi_goods_sync 的狀態不一,進行更新\n" );
				$ret = goods_sync_edit_from_good( $itm_good, $site, $cur_time );
				if( $ret != 0 ){
					echo "goods_sync_edit_from_good() 回傳 $ret\n";
					show_item( $itm_good );
				}
			}

			array_push( $keys, $key );
		}
		goods_sync_touch_from_good( $keys, $site, $cur_time );
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	// ntssi_goods 有   ntssi_goods_sync 有，但非 1-1 對應
	foreach( $ary_goods_ne_sync as $item ){
		echo "例外: ".show_item($item)."\n";
	}
	$mem_delta_cur = memory_get_usage() - $mem_beg ; echo sprintf( "[mem] %10s    %10s ( %10s ) update_sync_from_local_by_range\n", "", "", $mem_delta_cur );

	//
	// 狀況報表
	//
	echo  __FUNCTION__."[ $min ... $max ]\n";
	echo "  ntssi_goods                總共有 ". count( $ary_goods )        ."個\n"; 
	echo "  ntssi_goods_sync           總共有 ". count( $ary_sync )         ."個\n";
	echo "  ntssi_goods > ntssi_goods_sync 有 ". count( $ary_goods_gt_sync )."個\n";
	echo "  ntssi_goods < ntssi_goods_sync 有 ". count( $ary_goods_lt_sync )."個\n";
	echo "  ntssi_goods = ntssi_goods_sync 有 ". count( $ary_goods_eq_sync )."個\n";
	echo "  ntssi_goods ~ ntssi_goods_sync 有 ". count( $ary_goods_ne_sync )."個\n";

	$mem_end = memory_get_usage(); $mem_delta = $mem_end - $mem_beg ;
        echo sprintf( "[mem] %10s -> %10s ( %10s ) update_sync_from_local_by_range\n", $mem_beg, $mem_end, $mem_delta );
}

function update_sync_from_remote( $site ) {
	global $var_handle_limit;
	global $var_page_size;

	$site_id = $site['id'];
	$name    = $site['name'];
	$api_url = $site['api_url'];
	$api_key = $site['api_key'];

	$remote_info  = remote_info( $api_url, $api_key, $var_page_size );
	$remote_pages = $remote_info["pages"];
	$remote_total = $remote_info["total"];
	$remote_min   = $remote_info["min"];
	$remote_max   = $remote_info["max"];

	$pages = $remote_pages;
	print_r( $pages );
	foreach( $pages as $page ){
		$min = $page['min'];
		$max = $page['max'];
		echo "update_sync_from_remote_by_range( $site, $min, $max )\n";
		update_sync_from_remote_by_range( $site, $min, $max );
	}

	$loop = true;
	while( $loop ){
		$db = Database::getInstance();
		$query =  "SELECT * "
			. " FROM ntssi_goods_sync "
			. " WHERE site='$site_id' " 
			. " AND ( `key` < '$remote_min' OR '$remote_max' < `key` ) " 
			. " ORDER BY `key` ASC " 
			. " LIMIT $var_handle_limit " 
			. " " 
			;
		$result3   = $db->query($query);
		if( count($result3) < $var_handle_limit ) { $loop = false; }

		if( count($result3) > 0 ){
			foreach( $result3 as $item ){
				echo 'goods_sync_del_from_sync( $item ) '.show_item($item)."\n";
				goods_sync_del_from_sync( $item );
			}
		}
	}
}

function update_sync_from_remote_by_range( $site, $min, $max ) {
	$mem_beg = memory_get_usage();
	$site_id = $site['id'];
	$name    = $site['name'];
	$api_url = $site['api_url'];
	$api_key = $site['api_key'];

	$ary_sync_remote = remote_fetch( $api_url, $api_key, $min, $max );
	$ary_sync_local  = goods_sync_get_range_by_key( $site_id, $min, $max );

	//
	// 比對 local 跟 remote 的項目，並分類
	//
	$ary_local_gt_remote = array(); // local 有   remote 沒有
	$ary_local_lt_remote = array(); // local 沒有 remote 有
	$ary_local_eq_remote = array(); // local 有   remote 有
	$ary_local_ne_remote = array(); // local 有   remote 有，但非 1-1 對應
	$eqs_ary = array();

	foreach( $ary_sync_remote as $item ){
		$key = $item["key"];
		$gid = $item["gid"];

		$matches = find_by_key_and_gid( $key, $gid, $ary_sync_local );

		if( count( $matches ) == 0 ){
			array_push( $ary_local_lt_remote, $item ); // 本地沒有, 遠端有
		}
		else if( count( $matches ) == 1 ){
			$itm = array( 
					"local"    => $matches[0]
					, "remote" => $item
				    );

			$kk = $itm["local"]["key"].$itm["local"]["gid"].$itm["remote"]["key"].$itm["remote"]["gid"];
			if( !in_array( $kk, $eqs_ary ) ){
				array_push( $ary_local_eq_remote, $itm ); // 本地有, 遠端有
				array_push( $eqs_ary, $kk );
			}
		}
		else{
			array_push( $ary_local_ne_remote, $item ); // 例外處理
		}
	}

	foreach( $ary_sync_local as $item ){
		$key = $item["key"];
		$gid = $item["gid"];

		$matches = find_by_key_and_gid( $key, $gid, $ary_sync_remote );

		if( count( $matches ) == 0 ){
			array_push( $ary_local_gt_remote, $item ); // 本地有，遠端沒有
		}
		else if( count( $matches ) == 1 ){
			$itm = array( 
					"local"    => $item
					, "remote" => $matches[0]
				    );

			$kk = $itm["local"]["key"].$itm["local"]["gid"].$itm["remote"]["key"].$itm["remote"]["gid"];
			if( !in_array( $kk, $eqs_ary ) ){
				array_push( $ary_local_eq_remote, $itm ); // 本地有, 遠端有
				array_push( $eqs_ary, $kk );
			}
		}
		else{
			array_push( $ary_local_ne_remote, $item ); // 例外處理
		}
	}

	//
	// 針對不同的狀況作處理
	//
	foreach( $ary_local_gt_remote as $item ){
		echo "刪除: ".show_item($item)."\n";
		goods_sync_del_from_sync( $item );
	}

	foreach( $ary_local_lt_remote as $item ){
		echo "新增: ".show_item($item)."\n";
		goods_sync_add_from_sync( $item );
	}

	foreach( $ary_local_eq_remote as $item ){
		$itm_local  = $item['local'];
		$itm_remote = $item['remote'];

		if( $itm_local['chksum'] != $itm_remote['chksum'] ){
			echo "更新: ".show_item($itm_remote)."\n";
			goods_sync_del_from_sync( $itm_local  );
			goods_sync_add_from_sync( $itm_remote );
		}
	}

	// local 有   remote 有，但非 1-1 對應
	foreach( $ary_local_ne_remote as $item ){
		echo "例外: ".show_item($item)."\n";
	}

	//
	// 狀況報表
	//
	echo  __FUNCTION__."[ $min ... $max ]\n";
	echo "  local      總共有 ". count( $ary_goods )          ."個\n"; 
	echo "  remote     總共有 ". count( $ary_sync )           ."個\n";
	echo "  local > remote 有 ". count( $ary_local_gt_remote )."個\n";
	echo "  local < remote 有 ". count( $ary_local_lt_remote )."個\n";
	echo "  local = remote 有 ". count( $ary_local_eq_remote )."個\n";
	echo "  local ~ remote 有 ". count( $ary_local_ne_remote )."個\n";

	$mem_end = memory_get_usage(); $mem_delta = $mem_end - $mem_beg ;
        echo sprintf( "[mem] %10s -> %10s ( %10s ) update_sync_from_remote_by_range\n", $mem_beg, $mem_end, $mem_delta );
}

function update_goods_from_sync( $site ) {
	global $var_page_size;

	$site_remote = $site['id'];

	$pages = make_pagination_by_key( $site_remote, $var_page_size ); 
	print_r( $pages );
	foreach( $pages as $page ){
		$key_min = $page["min"];
		$key_max = $page["max"];
		echo "update_goods_from_sync_by_range( $site, $key_min, $key_max )\n";
		update_goods_from_sync_by_range( $site, $key_min, $key_max );
	}
}

function update_goods_from_sync_by_range( $site, $key_min, $key_max ){
	$mem_beg = memory_get_usage();
	global $var_site;

	$site_id      = $site['id'];
	$name         = $site['name'];
	$api_url      = $site['api_url'];
	$api_key      = $site['api_key'];
	$URL_PREFIX   = $site['url_prefix'];

	$providers_remote  = remote_list_providers( $api_url, $api_key );
	$providers_local   = provider_list();

	$site_local   = $var_site;
	$site_remote  = $site_id;

	$items_remote = goods_sync_get_range_by_key( $site_remote, $key_min, $key_max );
	$items_local  = goods_sync_get_range_by_key( $site_local, $key_min, $key_max );

	//
	// 比對 goods 跟 sync 的項目，並分類
	//
	$items_local_lt_remote = array(); // 本地沒，遠端有
	$items_local_gt_remote = array(); // 本地有，遠端沒
	$items_local_eq_remote = array(); // 本地、遠端都有
	$items_local_ne_remote = array(); // 本地、遠端都有，但非 1-1 對應
	$eqs_ary = array();

	foreach( $items_local as $item ){
		$key = $item["key"];
		$gid = $item["gid"];

		// 本站有，另一站也有
		//$matches_local1  = find_by_key_and_gid( $key, $gid, $items_local );
		$matches_local2  = find_by_key( $key, $items_local );
		//$matches_remote1 = find_by_key_and_gid( $key, $gid, $items_remote );
		$matches_remote2 = find_by_key( $key, $items_remote );
		if ( count($matches_remote2) == 0 ) { 
			array_push( $items_local_gt_remote, $item );
		}
		else if ( count($matches_remote2) == 1 && count($matches_local2) == 1 ) { 
			$itm_local  = $item;
			$itm_remote = $matches_remote2[0];

			$itm = array( 
					"local" => $itm_local
					, "remote" => $itm_remote 
				    );

			$kk = $itm["local"]["key"].$itm["remote"]["key"];
			if( !in_array( $kk, $eqs_ary ) ){
				array_push( $items_local_eq_remote, $itm );
				array_push( $eqs_ary, $kk );
			}
		}
		else{
			// 例外處理
			array_push( $items_local_ne_remote, $item );
		}
	}

	foreach( $items_remote as $item ){
		$key = $item["key"];
		$gid = $item["gid"];

		//$matches_local1  = find_by_key_and_gid( $key, $gid, $items_local );
		$matches_local2  = find_by_key( $key, $items_local );
		//$matches_remote1 = find_by_key_and_gid( $key, $gid, $items_remote );
		$matches_remote2 = find_by_key( $key, $items_remote );
		if( count( $matches_local2 ) == 0 ){
			array_push( $items_local_lt_remote, $item );
		}
		else if( count( $matches_local2 ) == 1 && count($matches_remote2) == 1 ){
			$itm_local  = $matches_local2[0];
			$itm_remote = $item;

			$itm = array( 
					"local" => $itm_local
					, "remote" => $itm_remote 
				    );

			$kk = $itm["local"]["key"].$itm["remote"]["key"];
			if( !in_array( $kk, $eqs_ary ) ){
				array_push( $items_local_eq_remote, $itm );
				array_push( $eqs_ary, $kk );
			}
		}
		else{
			// 例外處理
			array_push( $items_local_ne_remote, $item );
		}
	}

	//
	// 針對不同的狀況作處理
	//

	// 另一個站有，而本站沒有的項目
	foreach( $items_local_lt_remote as $item ){
		$key        = $item["key"];
		$checkstate = $item['checkstate'];

		// 別站已通過初審、複審的才新增同步過來
		if( intval( $checkstate ) == 1 || intval( $checkstate ) == 2 ){
			echo " $site_remote >> $site_local , 將新增 $key 項目 \n";
			$remote_items = remote_get_item( $api_url, $api_key, $key );
			$remote_item  = $remote_items[0];

			convert_before_apply( $remote_item, $providers_local, $providers_remote );
			goods_sync_apply_item( $remote_item, $URL_PREFIX );
		}
		else{
			echo " $key 商品項目不在初審、複審內，先skip \n";
		}
	}

	// 本站有，而另一站沒有的項目
	foreach( $items_local_gt_remote as $item ){
		// 本站已通過初審、複審的保留，剩下的刪除
		$checkstate = $item['checkstate'];
		if( intval( $checkstate ) == 1 || intval( $checkstate ) == 2 ){
			// 保留
			; // do nothing
		}
		else{
			echo "本站有，而別站沒有的項目".show_item($item)."\n";
			//goods_del_item( $item );
		}
	}

	// 本站有，另一站也有
	foreach( $items_local_eq_remote as $item ){ 
		$itm_local  = $item['local'];
		$itm_remote = $item['remote'];

		// 本站已通過初審、複審的保持不動作
		if( intval($itm_local['checkstate']) == 1 || intval($itm_local['checkstate']) == 2 ){
			; // 保持
		}
		// 剩下的，若對應項目在別站是未審或退審的，則刪除
		else{
			if( intval($itm_remote['checkstate']) == 0 || intval($itm_remote['checkstate']) == 3 ){
				$key = $itm_local['key'];
				$gid = $itm_local['gid'];
				echo "刪除本站有，而別的站未審、退審的項目 ".show_item($itm_local)."\n";
				goods_del_item( $itm_local );
			}
		}
	}

	// 本站有，另一站也有，但非 1-1 對應
	foreach( $items_local_ne_remote as $item ){
		echo "例外: ".show_item($item)."\n";
	}

	//
	// 狀況報表
	//
	echo  __FUNCTION__."[ $key_min ... $key_max ]\n";
	echo "  local      總共有 ". count( $items_local )          ."個\n"; 
	echo "  remote     總共有 ". count( $items_remote )         ."個\n";
	echo "  local > remote 有 ". count( $items_local_gt_remote )."個\n";
	echo "  local < remote 有 ". count( $items_local_lt_remote )."個\n";
	echo "  local = remote 有 ". count( $items_local_eq_remote )."個\n";
	echo "  local ~ remote 有 ". count( $items_local_ne_remote )."個\n";

	$mem_end = memory_get_usage(); $mem_delta = $mem_end - $mem_beg ;
        echo sprintf( "[mem] %10s -> %10s ( %10s ) update_goods_from_sync_by_range\n", $mem_beg, $mem_end, $mem_delta );
}

function convert_provider_id_from_remote_to_local( $provider_id, $providers_local, $providers_remote ){
	// 1. 找對應的 providerno
	$no_remote = -1;
	for( $i=0; $i<count($providers_remote); $i++ ){
		$item = $providers_remote[$i];
		if( $item['provider_id'] == $provider_id ){
			$no_remote = $item['providerno'];
		}

		if( $no_remote >= 0 ){ break; }
	}

	// 2. 用 providerno 找 local 的 provider_id
	$id_local = -1;
	for( $i=0; $i<count($providers_local); $i++ ){
		$item = $providers_local[$i];
		if( $item['providerno'] == $no_remote ){
			$id_local = $item['provider_id'];
		}

		if( $id_local >= 0 ){ break; }
	}

	echo " convert $provider_id => $id_local \n";

	return $id_local;
}

function convert_before_apply( &$item, $providers_local, $providers_remote ){
	foreach( $item as $k => $v ){
		$item[$k] = text_tw2cn( $v ); 
	}

	$item["cost"]       = 0; // 成本價歸零
	$item["combipoint"] = 0; // 紅利折抵歸零
	$item["ifalarm"]    = 1; // 貨到通知
	$item["view_num"]   = 0; // 瀏覽次數
	$item["ifpub"]      = 0; // 發佈 -> 否
	$item["checkstate"] = 0; // 狀態 => 未審核
	$item["iftogether"] = 1; // 統倉 => 統倉

	$provider_id = $item["provider_id"];
	$item["provider_id"] = convert_provider_id_from_remote_to_local( $provider_id, $providers_local, $providers_remote );
}

/*
 * 生效並更新 ntssi_goods_sync 的項目
 *
 * @param $site 遠端站點
 * @param $item 新項目
 * 
 * @return 0	執行成功
 * @return 1	項目已存在
 * @return -2	有重複的項目
 */
function goods_sync_apply_item( $item, $URL_PREFIX ){
	$ret_code = 0;

	$key = key_of_goods( $item );

	$db = Database::getInstance();

	$matched_goods = goods_get( $key );
	if ( count( $matched_goods ) == 1 ){ 
		$ret_code = 1; // 該項目已存在
		return $ret_code;
	}

	if ( count( $matched_goods ) > 1 ){ 
		$ret_code = -2; // 有重複的項目 
		return $ret_code;
	}

	$ret = goods_add_item( $item, $URL_PREFIX );
	if ( $ret != 0 ){
		$ret_code = -1; // 新增失敗
		echo "新增失敗!!\n";
	}

	return $ret_code;
}

/**
 * 異常及例外的商品資料處理
 * 
 * @param	$item	商品資料，一個陣列，分別放 site, gid, bn 的資訊. ex: array( "site" => "local", "bn" => 10003500009, "gid" => 99873 )
 */
function goods_exception_handler( $item ){
	$site = $item['site'];
	$bn   = $item['bn'];
	$gid  = $item['gid'];
}

/**
 * 效正網站的 ntssi_goods_sync 唯一對應
 * 
 * @param $site 網站
 */
function unify_sync_info( $site ) {
	global $var_page_size;

	$db = Database::getInstance();

	// 取出 goods 的內容
	$query = "SELECT * , count( * ) AS cnt"
		. " FROM `ntssi_goods` "
		//. " GROUP BY bn, idate "
		. " GROUP BY bn "
		. " HAVING cnt > 1 "
		. " LIMIT $var_page_size " ;
	$result = $db->query( $query );

	$target_ary = array();
	foreach( $result as $row ){
		$row['key']    = key_of_goods( $row );
		$row['chksum'] = checksum_goods( $row );
		array_push( $target_ary, $row );
	}

	foreach( $target_ary as $item ){
		$key = $item['key'];

		$goods_sync_items = goods_sync_get_by_key( $site, $key );
		$goods_items = goods_get( $key );

		if( count( $goods_sync_items ) == 1 ){

			$sync_item = $goods_sync_items[0];
			$gid = $sync_item['gid'];

			$hold_ary = array();
			$del_ary = array();
			foreach( $goods_items as $itm ){
				if( $itm['gid'] == $gid ){
					array_push( $hold_ary, $itm );
				}
				else{
					array_push( $del_ary, $itm );
				}
			}

			if( count( $hold_ary) == 1 ){
				foreach( $del_ary as $del_itm ){
					$id = $del_itm['gid'];
					echo "_goods_del_item( $id );\n";
					_goods_del_item( $del_itm );
				}
			}
		}
	}
}

/**
 * 消去 ntssi_goods_sync 中，重複的 metadata 項目
 */
function reduce_duplicated_sync_info() {
	global $var_handle_limit;

	$db = Database::getInstance();

	// 取出 goods 的內容
	$loop = true;
	while( $loop ){
		$query = " SELECT * FROM ("
			. "	SELECT * , count( * ) AS cnt"
			. "	FROM `ntssi_goods_sync`                                                                                  "
			. "	GROUP BY `site`,`key`"
			. "	HAVING cnt > 1 "
			. "	LIMIT $var_handle_limit"
			. "	) AS sync"
			. " ORDER BY `key` ASC";

		$result = $db->query( $query );

		$target_ary = array();
		foreach( $result as $row ){
			array_push( $target_ary, $row );
		}

		if( count( $target_ary ) < $var_handle_limit ) { $loop = false; }

		foreach( $target_ary as $item ){
			$the_key  = $item['key'];
			$the_site = $item['key'];

			echo "刪除重覆的 ( $the_site, $the_key ) 的 metadata 項目\n";
			// 注意，這裡只會刪1個
			goods_sync_del_from_good( $item );
		}
	}
}

?>
