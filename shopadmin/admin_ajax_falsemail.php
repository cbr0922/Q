<?php
@header("Content-type: text/html; charset=utf-8");
include_once "Check_Admin.php";

include("../configs.inc.php");
include(Classes . "/global.php");

	$Query = $DB->query(" select * from `{$INFO[DBPrefix]}falsemail` where mail!='' and no='" . $_GET['no'] . "' ");
	while ($Rs = $DB->fetch_array($Query)){
		echo $Rs["mail"] . "<br>";
	}
?>
