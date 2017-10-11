<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$json='{"act":"save","mgroup_name":"","user_level":"0","Year":"1954","Month":"1","Sex":"0","checkArea":"1","county":"u53f0u7063","province":"u81fau5317u5e02","city":"","company":"3","dianzibao":"0","begtime":"","endtime":"","order_begtime":"","order_endtime":"","minmoney":"","maxmoney":""}';
$arr_json = json_decode($json,TRUE);
$filename="會員活耀度統計_".date("Y-m-d",time());
$arr_json['mgroup_name']=$filename;

$searchlist = json_encode($arr_json);
$db_string = $DB->compile_db_insert_string( array (
	'mgroup_name'                 => trim($filename),
	'auto'                => 0,
	'searchlist'           => trim($searchlist),
)      );
$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
$Result_Insert=$DB->query($Sql);
$group_id = mysql_insert_id();

$Sql = "select u.* from `{$INFO[DBPrefix]}user` u";

$goods_starttime = $_POST['post_goods_starttime'];
$goods_endtime = $_POST['post_goods_endtime']." 23:59:59";

if ( ($_POST['post_goods_starttime'] != "") && ($_POST['post_goods_endtime'] != "")  ){

}
if ($_POST['post_gender']!="" && $_POST['post_gender']!="none"){
	if( $_POST['post_gender'] == "male"){
		$Sex_string = " sex='".intval("0")."' ";
	}
	else if( $_POST['post_gender'] == "female"){
		$Sex_string = " sex='".intval("1")."' ";
	}
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Sex_string);
}
if ($_POST['post_login_count'] != 0 && isset($_POST['post_login_count'])){
	$login_count = $_POST['post_login_count'];
	$Sql.=" JOIN (SELECT `user_id` FROM `{$INFO[DBPrefix]}user_log` WHERE `logintime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id` HAVING COUNT(*) >= '".$login_count."') l ON (u.`user_id`=l.`user_id`)";
}
if ( ($_POST['post_buymoney_start'] != "") && ($_POST['post_buymoney_end'] != "")  ){
	$buymoney_start = $_POST['post_buymoney_start'];
	$buymoney_end = $_POST['post_buymoney_end'];
	if($buymoney_start == 0 && $buymoney_end == 0){
		$Sql.=" JOIN (SELECT a.`user_id` FROM `{$INFO[DBPrefix]}user` a LEFT JOIN ";
		$Sql.="(SELECT `user_id` FROM `{$INFO[DBPrefix]}order_table` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND order_state=4 GROUP BY `user_id` HAVING SUM(`totalprice`) > 0) b ON (a.`user_id`=b.`user_id`) where b.`user_id` is null) t ON (u.`user_id`=t.`user_id`)";
	}else{
		$Sql.=" JOIN (SELECT `user_id`,SUM(`totalprice`) as totalprice FROM `{$INFO[DBPrefix]}order_table` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND order_state=4 GROUP BY `user_id` HAVING SUM(`totalprice`) BETWEEN '".$buymoney_start."' AND '".$buymoney_end."') t ON (u.`user_id`=t.`user_id`)";
	}
}
if ($_POST['post_item_id'] != "" && isset($_POST['post_item_id'])){
	$item_id = $_POST['post_item_id'];
	$subSql = " AND  d.`gid` IN (" . implode(",",array_filter(explode(",",$item_id))) . ")";
	$Sql.=" JOIN (SELECT `user_id`,t.`order_id` FROM `{$INFO[DBPrefix]}order_table` t,`{$INFO[DBPrefix]}order_detail` d WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND t.`order_id` = d.`order_id` AND t.`order_state` = 4 ".$subSql." GROUP BY `user_id`) o ON (u.`user_id`=o.`user_id`)";
}
if ($_POST['post_recommendmember'] != 0 ){
	$recommendmember = $_POST['post_recommendmember'];
	$Sql.=" JOIN (SELECT `user_id`,`recommendno` FROM `{$INFO[DBPrefix]}user` WHERE `recommendno`!='') r ON (u.`user_id`=r.`user_id`)";
}
if ( ($_POST['post_bonusrecord_start'] != "") && ($_POST['post_bonusrecord_end'] != "")  ){
	$bonusrecord_start = $_POST['post_bonusrecord_start'];
	$bonusrecord_end = $_POST['post_bonusrecord_end'];
	$Sql.=" JOIN (SELECT a.`user_id` , (a.`point` - IFNull(b.`point`,0)) AS point FROM ";
	$Sql.="(SELECT SUM(`point`) AS point ,`user_id`  FROM `{$INFO[DBPrefix]}bonuspoint` WHERE UNIX_TIMESTAMP('".$goods_starttime."') <= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') >= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') <= `endtime` AND `saleorlevel`=1 GROUP BY `user_id`) a LEFT JOIN ";
	$Sql.="(SELECT SUM(`usepoint`) AS point,bd.`user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` bd INNER JOIN  `{$INFO[DBPrefix]}bonuspoint` bp ON bd.`combipoint_id`=bp.`id` WHERE UNIX_TIMESTAMP('".$goods_starttime." 23:59:59') >= bp.`addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') <= bp.`endtime` AND bd.`usetime` <= UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND bp.`saleorlevel`=1 GROUP BY bd.`user_id`) b ";
	$Sql.="ON a.`user_id`=b.`user_id` WHERE (a.`point` - IFNull(b.`point`,0)) BETWEEN '".$bonusrecord_start."' AND '".$bonusrecord_end."') p ON (u.`user_id`=p.`user_id`)";
}
if ($_POST['post_bonusrecord'] != "" ){
	$bonusbuydetail = $_POST['post_bonusrecord'];
	if( $bonusbuydetail == 0){
		$Sql.=" JOIN (SELECT a.`user_id` FROM `{$INFO[DBPrefix]}user` a LEFT JOIN ";
		$Sql.="(SELECT `user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` WHERE `usetime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id`) b ON (a.`user_id`=b.`user_id`) where b.`user_id` is null) b ON (u.`user_id`=b.`user_id`)";
	}elseif( $bonusbuydetail == 1){
		$Sql.=" JOIN (SELECT `user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` WHERE `usetime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id`) b ON (u.`user_id`=b.`user_id`)";
	}
}
if ($_POST['post_ticketrecord'] != 0 ){
	$ticketrecord = $_POST['post_ticketrecord'];
	$Sql.=" JOIN (SELECT `userid` FROM `{$INFO[DBPrefix]}use_ticket` WHERE `usetime` >= UNIX_TIMESTAMP('".$goods_starttime."') AND `usetime` <= UNIX_TIMESTAMP('".$goods_endtime."') GROUP BY `userid`) i ON (u.`user_id`=i.`userid`)";
}

$Sql = $Sql.$Create_Sql." order by u.user_id desc";

$Query    = $DB->query($Sql);
while ($Rs=$DB->fetch_array($Query)) {
	$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) VALUES ('" . $group_id . "','" . $Rs['user_id'] . "','" . $Rs['email'] . "')";
	$DB->query($Sql);
}
$FUNCTIONS->setLog("新建郵件組");
echo "<script language=javascript>alert('郵件組已保存!');window.close();</script>";
exit;
?>
