<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include "../language/".$INFO['IS']."/Adv_Pack.php";

$tag    = trim($_GET['tag']);
$adv_id = intval($_GET['adv_id']);
if ($adv_id>0){ // 修改
	$Sql    =   " select count(*) as haved from `{$INFO[DBPrefix]}advertising`  where adv_id!='".$adv_id ."'   and adv_tag='".$tag."' limit 0,1";
}elseif($adv_id==0){	        // 新增
	$Sql    =   " select count(*) as haved from `{$INFO[DBPrefix]}advertising`  where adv_tag='".$tag."' limit 0,1";
}
//echo "sql:".$Sql;
$Query  = $DB->query($Sql);
$Num    = $DB->num_rows($Query);
$Result = $DB->fetch_array($Query);

if ($Result[haved]>0){
	echo $Adv_Pack[HavedTag_alert_nopass] ;
}elseif($Result[haved]==0){
	echo $Adv_Pack[HavedTag_alert_pass] ;
}

?>