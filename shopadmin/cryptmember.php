<?php
include_once "Check_Admin.php";
include_once 'crypt.class.php';
$Sql = "select * from `{$INFO[DBPrefix]}user` ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
while($Rs=$DB->fetch_array($Query)){
	$tel = $Rs['tel'];
	$other_tel = $Rs['other_tel'];
	$new_tel = MD5Crypt::Encrypt ( $tel, $INFO['tcrypt']);
	$new_other_tel = MD5Crypt::Encrypt ( $tel, $INFO['mcrypt']);
	$uSql = "update `{$INFO[DBPrefix]}user` set tel='" . $new_tel . "',other_tel='" . $new_other_tel . "' where user_id='" . $Rs['user_id'] . "'";
	$DB->query($uSql);
}

?>