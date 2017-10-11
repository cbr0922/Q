<?php
include("../configs.inc.php");
include("global.php");
@header("Content-type: text/html; charset=utf-8");
	$Sql = "select id from `{$INFO[DBPrefix]}saler`  where openpwd='" .trim($_GET['password']). "' order by id desc limit 0,1";
$Query  = $DB->query($Sql);
$Num    = $DB->num_rows($Query);
if ($Num>0){
	echo '1';
}else{
	echo '0';
}
?>