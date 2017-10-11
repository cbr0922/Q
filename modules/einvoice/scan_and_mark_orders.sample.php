<?php
require_once( dirname(__FILE__)."/../../configs.inc.php" );
require_once( "mat.php" );
require_once( "libeinvoice.php" );

$DBPrefix  = matConf::getConf('info.DBPrefix');
$begindate = matConf::getConf('mod.einvoice.begindate');    // 發票模組開始採用日期
$db = matDB::getDB();

$beg_date = strtotime( $begindate );

$query = "SELECT * FROM `".$DBPrefix."order_table` AS ot "
. " WHERE createtime >= $beg_date"
. "   AND ot.pay_state=1 "
. "   AND ot.ifeinvoice IS NULL "
. " LIMIT 100 ";

$rows = $db->GetAll( $query );
foreach( (array)$rows as $row ){
	mark_use_einvoice( $row['order_serial'] );
}

?>
