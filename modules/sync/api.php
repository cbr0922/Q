<?php
/**
 * API函式
 *
 * @copyright Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>. All rights reserved.
 */

require_once( dirname(__FILE__).'/common.php' );
require_once( dirname(__FILE__).'/init.php' );

/*
 * 主程式
 */
//print_r( $_GET  );
//print_r( $_POST );

$apikey = $_GET["apikey"];
$action = $_GET["action"];
$param1 = $_GET["param1"];
$param2 = $_GET["param2"];
$param3 = $_GET["param3"];
$param4 = $_GET["param4"];

if ( $apikey !== $var_api_key ){ echo "API KEY not match\n"; exit(); }

$db = Database::getInstance();
$db->use_database( $var_database );

if( $action == "fetch" ){
	$min = $param1;
	$max = $param2;

	$query =  "SELECT * "
		. " FROM ntssi_goods_sync "
		. " WHERE site='$var_site' " 
		. " AND ( '$min' <= `key` AND `key` <= '$max' ) " 
		. " ORDER BY `key` ASC " 
		. "" 
		;
        $result = $db->query($query);
	$output = serialize($result );
	
	echo $output;
}
else if( $action == "get" ){
	$key = $param1;

	$query =  "SELECT gds.* "
		. " FROM ntssi_goods_sync AS sync, ntssi_goods AS gds"
		. " WHERE sync.site='$var_site' AND sync.gid=gds.gid" 
		. " AND `key`='$key' " 
		. " LIMIT 1 " 
		;
        $result = $db->query($query);
	$output = serialize($result );
	
	echo $output;
}
else if( $action == "info" ){
	$page_size = $param1;

	$pages    = make_pagination_by_key( $var_site, $page_size );
	$total    = goods_sync_total( $var_site );
	$item_min = goods_sync_get( $var_site, 0 );
	$item_max = goods_sync_get( $var_site, $total-1 );
	$key_min  = $item_min['key'];
	$key_max  = $item_max['key'];
	$info = array(
			"pages"  => $pages
			,"total" => $total
			,"min"   => $key_min
			,"max"   => $key_max
		     );

	echo serialize($info);
}
else if( $action == "list_providers" ){
	$query =  "SELECT provider_id, providerno "
		. " FROM ntssi_provider "
		;
        $result = $db->query($query);
	$output = serialize($result );
	
	echo $output;
}
else{

}



?>
