<?php
include("../configs.inc.php");
include("global.php");
@header("Content-type: text/html; charset=utf-8");
switch (intval($_GET['type'])){
	case 1:  //用户名称
	$Sql = "select username from `{$INFO[DBPrefix]}user`  where username='".trim($_GET['username'])."' order by user_id desc limit 0,1";
	break;
	case 2:
		$Sql = "select email from `{$INFO[DBPrefix]}user`  where email='".trim($_GET['email'])."' order by user_id desc limit 0,1";
		break;
	case 3:
		$Sql = "select order_id from `{$INFO[DBPrefix]}order_table`  where order_serial='".trim($_GET['order_serial'])."' limit 0,1";
		break;
	case 4:
		$Sql = "select order_id from `{$INFO[DBPrefix]}order_table`  where receiver_email='".trim($_GET['email'])."' limit 0,1";
		break;
}
$Query  = $DB->query($Sql);
$Num    = $DB->num_rows($Query);
if ($Num>0){
	echo '1';
}else{
	echo '0';
}
?>