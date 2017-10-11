<?php
include_once "Check_Admin.php";
include_once 'crypt.class.php';
$Sql = "select * from `{$INFO[DBPrefix]}order_table` ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
while($Rs=$DB->fetch_array($Query)){
	$tel = $Rs['receiver_tele'];
	$other_tel = $Rs['receiver_mobile'];
	$new_tel = MD5Crypt::Encrypt ( $tel, $INFO['tcrypt']);
	$new_other_tel = MD5Crypt::Encrypt ( $tel, $INFO['mcrypt']);
	$uSql = "update `{$INFO[DBPrefix]}order_table` set receiver_tele='" . $new_tel . "',receiver_mobile='" . $new_other_tel . "' where order_id='" . $Rs['order_id'] . "'";
	$DB->query($uSql);
}

?>