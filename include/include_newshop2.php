<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$scid = intval($_GET['scid']);
if ($scid>0){
	$class_Sql = "select * from `{$INFO[DBPrefix]}shopclass` where top_id='" . $scid . "'";
	$class_Query  = $DB->query($class_Sql);
	$class_Rs =  $DB->fetch_array($class_Query);
	while($class_Rs =  $DB->fetch_array($class_Query)){
		$subsql .= " or s.shopclass='" . intval($class_Rs['scid']) . "'";
	}
	$subsql = " and (s.shopclass='" . $scid . "' " . $subsql . ")";	
}

$newshop_array = array();
$i = 0;
$Sql      = "select s.*,u.username,u.true_name from `{$INFO[DBPrefix]}shopinfo` s  left join `{$INFO[DBPrefix]}user` u on (s.uid = u.user_id) where s.state=1 " . $subsql . " order by s.sid desc limit 0,10";
$Query    = $DB->query($Sql);
while($class_Rs =  $DB->fetch_array($Query)){
	$newshop_array[$i]['username']	= $class_Rs['username'];
	$newshop_array[$i]['true_name']	= $class_Rs['true_name'];
	$newshop_array[$i]['shopname']	= $class_Rs['shopname'];
	$newshop_array[$i]['shoppic']	= $class_Rs['shoppic'];
	$newshop_array[$i]['content']	= $class_Rs['content'];
	$newshop_array[$i]['sid']	= $class_Rs['sid'];
	$i++;
}
$tpl->assign("newshop_array",$newshop_array);
$tpl->display("include_newshop2.html");
?>
