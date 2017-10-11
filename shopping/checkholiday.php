<?php
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
$date = $_GET['date'];
$date_array = explode("-",$date);
$datetime = mktime(0,0,0,$date_array[1],$date_array[2],$date_array[0]);
$week = date("w",$datetime);
//if($week==6||$week==0){
	//echo 0;exit;	
//}else{
	$Sql      = "select * from `{$INFO[DBPrefix]}holiday` where  startdate<='" . $date . "' and enddate>='" . $date . "'";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);	
	if($Num>0){
		echo 0;exit;
	}else{
		echo 1;exit;	
	}	
//}
?>