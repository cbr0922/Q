<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
$order_id = intval($_POST['order_id']);

$Sql   =  " select * from `{$INFO[DBPrefix]}order_table` where user_id=".$_SESSION['user_id']." and order_id='" . $order_id . "' and shopid>0 and order_state=4 order by createtime desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Rs = $DB->fetch_array($Query);
	$Query_detail = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_detail` as g where g.order_id=".intval($order_id)." order by g.order_detail_id desc ");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
		$s_Sql   =  " select * from `{$INFO[DBPrefix]}score` where user_id=".$_SESSION['user_id']." and order_id='" . $order_id . "' and gid='" . $Rs_detail['gid'] . "' ";
		$s_Query =  $DB->query($s_Sql);	
		$s_Num   =  $DB->num_rows($s_Query);
		if ($s_Num<=0){
			$db_string = $DB->compile_db_insert_string( array (
			'score1'          => intval($_POST['score1_' . $Rs_detail['gid']]),
			'score2'          => intval($_POST['score2_' . $Rs_detail['gid']]),
			'score3'          => intval($_POST['score3_' . $Rs_detail['gid']]),
			'order_id'          => $order_id,
			'user_id'          => $_SESSION['user_id'],
			'scoretime'          => time(),
			'shopid'          => $Rs['shopid'],
			'gid'          => $Rs_detail['gid'],
			)      );	
			$Sql="INSERT INTO `{$INFO[DBPrefix]}score` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Insert=$DB->query($Sql);
		}
	}
}
$FUNCTIONS->sorry_back("MyOrder.php","");
?>